<?php


namespace Home\Controller;
use Common\Service\WeixinService;
use think\Controller;

class DemoController extends Controller
{
    public function _initialize()
    {
//        if(!APP_DEBUG)
//            exit();
    }
    public function loginIndex()
    {
        if($this->isWeixin())
        {
            $weixinService = WeixinService::getInstance();
            $authUrl = $weixinService->buildAuth2Url(urlencode($this->getRedirectUrl()));
            // echo $authUrl;
            redirect($authUrl);
        }
    }
    public function weixinRedirect()
    {
        $data = I('get.');
        var_dump($data);
        $code = I('code');
        $weixinService = WeixinService::getInstance();
        $wechatUser = $weixinService->getUserInfo($code);
        var_dump($wechatUser);
        if($wechatUser['openId'])
        {
            session('openId', $wechatUser['openId']);
            //没有打开开发者模式，获取不大搜unionid
            //session('unionid', $wechatUser['unionid']);
        }
    }
    private function getRedirectUrl()
    {
        $url = 'http';
        if ($_SERVER["HTTPS"] == "on")
        {
            $url .= "s";
        }
        $url .= "://".$_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $url .= ":" . $_SERVER["SERVER_PORT"];
        }
        $url .= U('Demo/weixinRedirect');
        return $url;
    }

    private function isWeixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
        {
            return true;
        } else {
            return false;
        }
    }
    public function index()
    {
        $file = I('get.file');
        $this->display($file);
    }

}