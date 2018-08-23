<?php
namespace Home\Controller;
use Think\Controller;
class WeixinController extends Controller {


    public function index(){

		$this -> checkSignature();

		$postStr = file_get_contents("php://input");
		$post = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
		$he = $post->FromUserName;//与我们发生事件的用户
		$me = $post->ToUserName;//我们自己的公众号
		$msgType = $post->MsgType;//消息类型

		switch($msgType){
			case  'event':
				$eve = $post->Event;
				if($eve == 'SCAN'){
					//获取用户信息并且注册
					$user_info = $this -> get_user_info($he);
					$openid = $user_info->openid;
					$info = M('users')->where(['openid'=>$openid])->find();
					if(!$info){
						$data['pid'] = (int)$post->EventKey;
						$data['openid'] = $openid;
						$data['created_at'] = time();
						$data['pic'] = $user_info->headimgurl;
						session('weixin',$data);
						$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/login/regin_1?pid='.$data['pid'].'&openid='.$openid;
						$content ="你好，欢迎关注房客行！请先点击<a href='{$url}'>注册</a>，方可为好友接力。";
					}else{
						$content = "你好，欢迎关注房客行！";
					}

					$string = $this->getTextMsg($he, $me ,time(), $content);
					echo $string;
				}elseif($eve == 'subscribe'){

					$keys = $post->EventKey;
					$tiket = $post->Ticket;

					if(!empty($tiket) && isset($tiket)){
						//$data['pid'] = trim($data['pid'],'qrscene_');
						$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/login/regin_1?pid='.$keys.'&openid='.$he;
						$content = "您好，欢迎关注房客行全民经纪人平台！
					我们汇集厦漳地区众多优质的新房房源；
					您可以在房客行得到购房折扣、推介客户得奖金；
					专业客服团队提供客户对接、专车带看服务；
					↓点击“<a href='{$url}'>注册</a>”，进入平台了解更多优质房源";

					} else {

						$content = '您好，欢迎关注房客行全民经纪人平台！
					我们汇集厦漳地区众多优质的新房房源；
					您可以在房客行得到购房折扣、推介客户得奖金；
					专业客服团队提供客户对接、专车带看服务；
					↓点击下方“进入首页”列表，了解更多优质房源';
					}

					$string = $this->getTextMsg($he, $me ,time(), $content);
					echo $string;

				}
				break;

		}



	}
	//获取用户的信息
	public function get_user_info($openid){
		$token = $this->getToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
		$result = curl_request($url);
		return json_decode($result);
	}

	public function getTextMsg($to,$from,$time,$content){
		$tpl = '<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		</xml>';
		return sprintf($tpl,$to,$from,$time,$content);
	}


	private function checkSignature()
	{

		$echostr = $_GET["echostr"];
		//	echo $signature;
		//	exit;
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = '1234567';
		$signature = $_GET["signature"];
		$tmpArr = array($timestamp, $nonce, $token);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr ==  $signature){
			echo $echostr;
		}

	}
	public function center(){
		$user = get_user();
		if(!$user){
			//	session('back','Client/index');
			$this -> redirect('Login/index');
		}
		$filename = $this ->qrcode();
		$user_id = $user['id'];
		$info = M('users') -> where(['pid'=>$user_id]) -> select();
		$this->assign('filename', $filename);
		$this->assign('info', $info);
		$this->display();

	}
	//生产二维码
	public function qrcode(){
		$ticket = $this->getTicket();
		$user = get_user();
		$user_id = $user['id'];
		$ticket=urlencode($ticket);
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
//保存到sae
		$file = ROOT_PATH.'/uploads/qr/qr_'.$user_id.'.jpg';
		$filename = ROOT_PATH.'/uploads/qr/'.$user_id.'.jpg';
		$result = curl_request($url);
		file_put_contents($file,$result);

		$dst = ROOT_PATH.'/public/Home/zhuce.jpg';
		$this ->shuiyin($dst ,$file, $filename);

		return '/uploads/qr/'.$user_id.'.jpg';
	}
	//图片加水印
	public function shuiyin($dst_path ,$src_path, $filename=''){

//创建图片的实例
		$dst = imagecreatefromstring(file_get_contents($dst_path));
		$src = imagecreatefromstring(file_get_contents($src_path));
//获取水印图片的宽高
		list($src_w, $src_h) = getimagesize($src_path);
//将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
		imagecopymerge($dst, $src, 160, 700, 0, 0, $src_w, $src_h, 100);
//如果水印图片本身带透明色，则使用imagecopy方法
//imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);

//输出图片
		list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
		switch ($dst_type) {
			case 1://GIF
				header('Content-Type: image/gif');
				imagegif($dst,$filename);
				break;
			case 2://JPG
				header('Content-Type: image/jpeg');
				imagejpeg($dst,$filename);
				break;
			case 3://PNG
				header('Content-Type: image/png');
				imagepng($dst,$filename);
				break;
			default:
				break;
		}

		imagedestroy($dst);
		imagedestroy($src);
	}

	//获取tik
	public function getTicket(){
		$token = $this ->getToken();
		$user = get_user();
		$user_id= $user['id'];
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
		$params = '{
			   "expire_seconds": 604800,
			   "action_name": "QR_SCENE",
			   "action_info": {
			   "scene": {
					"scene_id": '.$user_id.'
				}
			}
 }';
		$result = curl_request($url, $params, 'POST');
		$result=json_decode($result);
		return $result->ticket;
	}

	//获取token
	public function getToken(){
		$appid = C('WEIXIN_APP_ID');
		$appkey = C('WEIXIN_APP_SECRET');
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appkey}";
		$result = curl_request($url);

		$result=json_decode($result);
		return $result->access_token;


	}

	//设置菜单
	public function setMenu(){
		$token = $this ->getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";

		$params = ' {
     "button":[
     {
          "type":"view",
          "name":"进入首页",
          "url":"http://www.fangkexing.cn/index.php/index/index"
      },
      {
           "name":"个人中心",
           "sub_button":[
           {
               "type":"view",
               "name":"我要登录",
               "url":"http://www.fangkexing.cn/index.php/Login/index.html"
            },
            {
                 "type":"view",
                 "name":"我要注册",
                 "url":"http://www.fangkexing.cn/index.php/Login/regin_1"

             }]
       },
       {
           "name":"关于平台",
           "sub_button":[
           {
               "type":"view",
               "name":"联系客服",
               "url":"http://www.fangkexing.cn/index.php/Center/kefu.html"
            },
            {
                 "type":"view",
                 "name":"平台协议",
                 "url":"http://www.fangkexing.cn/index.php/Index/pingtai.html"

             },
            {
               "type":"view",
               "name":"平台介绍",
               "url":"http://www.fangkexing.cn/index.php/Center/xy.html"
            }]

       	},
   ]
 }';
		$result = curl_request($url, $params, 'POST');
		var_dump($result);
	}



}