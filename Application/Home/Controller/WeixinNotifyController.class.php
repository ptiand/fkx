<?php

namespace Home\Controller;

use Common\Service\WeixinService;
use Think\Controller;
use Think\Log;

class WeixinNotifyController extends Controller
{
    private $wxService;
    public function __construct()
    {
        $this->wxService = WeixinService::getInstance();
    }

    public function index()
    {
        Log::record('接收到微信推送消息', Log::ERR);
        $echoStr = $_GET["echostr"];
        if($echoStr && !IS_POST)
        {
            Log::record('微信验证开发者,echostr:'.$echoStr.' , signature:'.$_GET["signature"].' , timestamp:'.$_GET["timestamp"].' , nonce:'.$_GET["nonce"], Log::INFO);
            if($this->devValid())
            {
                Log::record('微信验证开发者通过', Log::INFO);
                echo $echoStr;
            }
            else
            {
                Log::record('微信验证开发者不通过', Log::ERR);
                echo 'error';
            }
            exit;
        }

        $res = $this->wxService->valid();
        if ($res === false) {
            die('非法数据');
        }
        $postData = $this->wxService->getReceiveData();
        Log::record('微信消息'.json_encode($postData, true), Log::ERR);

        $type = $postData['MsgType'];
        switch ($type) {
            case 'text':
                $this->receiveText($postData);
                break;
            case 'event':
                $this->receiveEvent($postData);
                break;
            case 'image':
                break;
            case 'voice':
                break;
            case 'video':
                break;
        }
    }
    private function receiveEvent($postData)
    {
        $event = $postData['Event'];
        $eventKey = $postData['EventKey'];
        switch ($event)
        {
            case 'subscribe':
                $this->wxService->subscribe($postData['FromUserName']);
                //回复消息
                $reply = D('WeixinMessageReply')->getSubscribeReply();
                if (!empty($reply)) {
                    $xmlData = $this->wxService->newsTextReply($reply['replycontent'], true);
                    Log::record('subscribe $xmlData'.$xmlData, Log::ERR);
                    echo $xmlData;
                }
                break;
            case 'unsubscribe':
                $this->wxService->unsubscribe($postData['FromUserName']);
                break;
            case 'LOCATION':
                $this->receiveLocation($postData);
                break;
            case 'CLICK':
                if (is_numeric($eventKey)) {
                    $reply_text = D('WeixinMenu')->getMenuClickReplyByid($eventKey);
                    if (!empty($reply_text)) {
                        $this->wxService->newsTextReply($reply_text);
                    }
                }
                break;
        }
    }

    private function receiveLocation($postData)
    {
        $longitude = $postData['Longitude'];
        $latitude = $postData['Latitude'];
        $openid = $postData['FromUserName'];
        $this->wxService->reportLocation($openid, $longitude, $latitude);
    }
    private function receiveText($postData)
    {
        $keyword = $postData['Content'];
        $reply = D('WeixinMessageReply')->getReplyByKeyword($keyword);
        Log::record('reply'.$reply['replycontent'], Log::ERR);
        if (empty($reply)) {
            return;
        }
        $xmlData = $this->wxService->newsTextReply($reply['replycontent'], true);
        echo $xmlData;
        Log::record('$xmlData'.$xmlData, Log::ERR);
    }
    private function devValid()
    {
        if($this->wxService->checkSignature())
            return true;
        else
            return false;
    }

}
