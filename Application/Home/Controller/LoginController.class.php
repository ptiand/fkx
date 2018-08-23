<?php
namespace Home\Controller;
use Common\Service\WeixinService;
use Think\Controller;
use Think\Log;

class LoginController extends Controller {

    public function index()
    {
        $userLogout = session('UserLogout');
        $userLogoutTime = session('UserLogoutTime');
        if($this->isWeixin() && (!$userLogout || ($userLogout && isset($userLogoutTime) && $userLogoutTime > 0 && $userLogoutTime < time())))
        {
            $weixinService = WeixinService::getInstance();
            $authUrl = $weixinService->buildAuth2Url(urlencode($this->getRedirectUrl()));
            redirect($authUrl);
        }
        else
        {
            $user = get_user();
            if(!$user)
            {
                $this -> assign("redirectUrl", session('back'));
            }
            else
            {
                $this->assign('isLogin', true);
            }
            $this -> display();
        }

	}
//	public function login_in(){
//		$pass = I('pwd');
//		$phone = I('phone');
//		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
//		if($res){
//			if($res['status'] ==1){
//				$this -> error('该手机号码已经被冻结，请联系管理员');
//			}
//			$pwd = get_pwd($pass, $res['random']);
//			if($pwd == $res['password']){
//				$current_user['user_name'] = $res['user_name'];
//				$current_user['phone'] = $res['phone'];
//				$current_user['id'] = $res['id'];
//				session("user" , $current_user);//写入Session 登录成功
//			//	$back = session('back');
////				if(isset($back) && !empty($back)){
////					$this -> redirect($back);
////				}else{
//                session('back', '');
//					$this->success('登录成功');
////				}
//
//
//			}else{
//				$this -> error('输入密码错误');
//			}
//		}else{
//			$this -> error('该手机号码不存在');
//		}
//	}
    public function login_in(){
        $pass = I('pwd');
        $phone = I('phone');
        $res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
        if($res){
            if($res['status'] ==1){
                $this -> error('该手机号码已经被冻结，请联系管理员');
            }
            $pwd = get_pwd($pass, $res['random']);
            if($pwd == $res['password']){
                $current_user['user_name'] = $res['user_name'];
                $current_user['phone'] = $res['phone'];
                $current_user['id'] = $res['id'];
                $current_user['user_type'] = $res['user_type'];
                
                session("user" , $current_user);//写入Session 登录成功
                session('back', '');
                session('UserLogout', false);
                session('UserLogoutTime', 0);
                $openId = session('openId');
                if($openId)
                {
                    if(!$res['openid'])
                    {
                        //未绑定，如果微信openId也未绑定，则绑定上
                        $u = D('User')->findByOpenid($openId);
                        if(!$u)
                        {
                            $user = array(
                                'id' => $res['id'],
                                'openid' => $openId
                            );
                            D('User')->saveUser($user);
                        }
                    }
                }
                $this->success('登录成功');
            }else{
                $this -> error('输入密码错误');
            }
        }else{
            $this -> error('该手机号码不存在');
        }
    }
	//zhuce
	public function regin_1(){
		$info['pid'] = I('pid');
		$info['openid'] = I('openid');
		$this->assign('info', $info);
		$this -> display();
	}
	//手机是否存在
	public function is_cunz(){
		$phone = I('phone');
		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
		if($res){
			$this -> success('该手机号码已经被注册过了');
		}else{
			$this -> error('该手机号码还没注册');
		}
	}

	public function sand_sms(){
		$phone = I('phone');
		$string = get_string();
		$code = $string->rand_string(4,1);
		$yanz = session('code');
		$yTime = time() + 4*60;
	//	dump($yTime);
		if(isset($yanz) && $yanz['sand _time'] > $yTime){
			$this -> error('已发送，请等待');
		}
		$result = send_sms($code, $phone);
		if($result){
			$heihei['num'] = $code.$phone;
			$heihei['sand _time'] = time() + 5*60;
			session("code" , $heihei);
			$this -> success('发送成功');
		}else{
			$this -> error('发送失败');
		}
	}
	public function next_reg(){
		$phone = I('phone');
        $code = I('code');
		if(!$phone || !$code){
			$this -> error('手机号或者验证码不能为空');
		}
		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
		if($res){
			$this -> error('该手机号码已经被注册过了');
		}
		$yanz = session('code');
		$time = time();
		if($code.$phone !=$yanz['num']){
			$this -> error('验证码错误');
		}
		if($time > $yanz['sand _time']){
			$this -> error('验证码过期');
		}
		$pid = I('pid');
		$aa = I('openid');
		if($aa && $pid){
			$info['pid'] = $pid;
			$info['openid'] = $aa;
		}
		$info['phone'] = $phone;
		$info['created_at'] = time();
		$info['is_del'] = 0;
        $result = M('users') -> add($info);
		if($result){
            session('new_user', md5(date("Y").$phone));
			$this -> success($result);
		}else{
			$this -> error('未知错误，请重试');
		}
	}
	public function regin(){
		$id = I('id');
		$phone = I('phone');
		$this -> assign('phone',$phone);
		$this -> assign('id',$id);
		$this -> display();
	}
	public function regin_in(){
		$pass = I('pwd');
		$repass = I('repwd');
		$id = I('id');
        $phone = I('phone');
        if (!session('new_user') || session('new_user') != md5(date('Y').$phone)) {
            $this->error('非法请求');
        }
		if(!$pass){
			$this -> error('密码不能为空');
		}
		// if($pass != $repass){
		// 	$this -> error('两次密码不一致');
		// }
		//获取随机数
		$string = get_string();
		$info['random']= $string->rand_string(6,1);
		$info['password'] = get_pwd($pass, $info['random']);
		$info['user_name'] = I('nick_name');
		$info['is_del'] = 0;
		$result = M('users') -> where(['id'=>$id, 'phone' => $phone]) ->save($info);
		if($result){
            $current_user['user_name'] = $info['user_name'];
            $current_user['phone'] = $phone;
            $current_user['id'] = $id;
            $current_user['user_type'] = 0;
            session("user" , $current_user);//写入Session 登录成功
            session('back', '');
            session('UserLogout', false);
            session('UserLogoutTime', 0);
			$this -> success('注册成功');
		}else{
			$this -> error('注册失败');
		}
	}
	//验证码登录
	public function code_login(){
        $userLogout = session('UserLogout');
        $userLogoutTime = session('UserLogoutTime');
        //if($this->isWeixin() && (!$userLogout || ($userLogout && isset($userLogoutTime) && $userLogoutTime > 0 && $userLogoutTime < time())))
        //{
        //    $weixinService = WeixinService::getInstance();
        //    $authUrl = $weixinService->buildAuth2Url(urlencode($this->getRedirectUrl()));
        //    redirect($authUrl);
        //}
        //else
        //{
            $user = get_user();
            if(!$user)
            {
                $this -> assign("redirectUrl", session('back'));
            }
            else
            {
                $this->assign('isLogin', true);
            }
            $this -> display();
        //}
	}
    public function sendLoginCode()
    {
        $phone = I('phone');
        // $res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
        // if(!$res){
        //     $this -> error('该手机号码还没注册');
        //     return;
        // }
        $string = get_string();
        $code = $string->rand_string(4,1);
        $lastSendTime = session('lastSendLoginCodeTime');
        if($lastSendTime && ($lastSendTime>(time()-60)))
        {
            $this -> error('已发送，请等待');
            return;
        }
        $result = send_sms($code, $phone);
        if($result)
        {
            $lastSendTime = time();
            $codeExpireTime = time()+5*60;
            session("loginCode", $code.$phone);
            session("lastSendLoginCodeTime", $lastSendTime);
            session("loginCodeExpireTime", $codeExpireTime);
            $this -> success('发送成功');
        }
        else
        {
            $this -> error('发送失败');
        }
    }
	//验证登录
	public function codeLogin(){
		$phone = I('phone');
		$code = I('code');
        $loginCode = session("loginCode");
        $codeExpireTime = session("loginCodeExpireTime");
        if($code.$phone != $loginCode)
        {
            $this -> error('验证码错误');
            return;
        }
        if(time() > $codeExpireTime)
        {
            $this -> error('验证码已过期');
            return;
        }
        $res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
        if($res){
            $current_user['user_name'] = $res['user_name'];
            $current_user['phone'] = $res['phone'];
            $current_user['id'] = $res['id'];
            $current_user['user_type'] = $res['user_type'];
            session("user" , $current_user);//写入Session 登录成功
            session('back', '');
            session('UserLogout', false);
            session('UserLogoutTime', 0);
            $this->success('登陆成功');
        }else{
            $pid = I('pid');
            $aa = I('openid');
            if($aa && $pid){
                $info['pid'] = $pid;
                $info['openid'] = $aa;
            }
            $info['phone'] = $phone;
            $info['created_at'] = time();
            $info['is_del'] = 0;
            $result = M('users') -> add($info);
            if ($result) {
                session('new_user', md5(date("Y").$phone));
                $this->ajaxReturn([
                    'status' => 2,
                    'id' => $result,
                    'phone' => $phone,
                    'info' => '验证成功',
                ]);
            } else {
                $this->error('验证失败');
            }
            
            // $this -> success('登录失败');
        }
//
//		$yanz = session('code');
//		$time = time();
//		if($code.$phone !=$yanz['num']){
//			$this -> error('验证码错误');
//		}
//		if($time > $yanz['sand _time']){
//			$this -> error('验证码过期');
//		}
//		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
//		if($res){
//			$current_user['user_name'] = $res['user_name'];
//			$current_user['phone'] = $res['phone'];
//			$current_user['id'] = $res['id'];
//			session("user" , $current_user);//写入Session 登录成功
//			//	$back = session('back');
////				if(isset($back) && !empty($back)){
////					$this -> redirect($back);
////				}else{
//			$this->success('登录成功');
//		}else{
//			$this -> error('登录失败');
//		}
	}

	public function forgetPwd(){

		$this -> display('forgetpwd');
	}
    public function sendResetPwdCode()
    {
        $phone = I('phone');
        $res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
        if(!$res){
            $this -> error('该手机号码还没注册');
            return;
        }
        $string = get_string();
        $code = $string->rand_string(4,1);
        $lastSendTime = session('lastSendResetPwdCodeTime');
        if($lastSendTime && ($lastSendTime>(time()-60)))
        {
            $this -> error('已发送，请等待');
            return;
        }
        $result = send_sms($code, $phone);
        if($result)
        {
            $lastSendTime = time();
            $codeExpireTime = time()+5*60;
            session("resetPwdCode", $code.$phone);
            session("lastSendResetPwdCodeTime", $lastSendTime);
            session("resetPwdCodeExpireTime", $codeExpireTime);
            $this -> success('发送成功');
        }
        else
        {
            $this -> error('发送失败');
        }
    }
	public function verifyResetPwd(){
		$phone = I('phone');
		$code = I('code');
		//$yanz = session('code');
		//$time = time();
        $resetPwdCode = session("resetPwdCode");
        $codeExpireTime = session("resetPwdCodeExpireTime");
        if($code.$phone != $resetPwdCode)
        {
            $this -> error('验证码错误');
            return;
        }
        if(time() > $codeExpireTime)
        {
            $this -> error('验证码已过期');
            return;
        }
//		if($code.$phone !=$yanz['num']){
//			$this -> error('验证码错误');
//		}
//		if($time > $yanz['sand _time']){
//			$this -> error('验证码过期');
//		}
		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
		if($res){
			session('change_pwd', $res['id']);
			$this->success('验证成功');
		}else{
			$this -> error('验证失败');
		}
	}
	public function resetPwd(){
        if(IS_POST)
        {
            $pwd = I('pwd');
            $confirmPwd = I('confirmPwd');
            $id = session('change_pwd');
            if(!$pwd || !$confirmPwd){
                $this -> error('密码或者确认密码不能为空');
                return;
            }
            if($pwd != $confirmPwd){
                $this -> error('两次密码不一致');
                return;
            }
            if(!$id)
            {
                $this -> error('修改失败');
                return;
            }
            $string = get_string();
            $info['random']= $string->rand_string(6,1);
            $info['password'] = get_pwd($pwd, $info['random']);
            $result = M('users') -> where(array('id'=>$id)) ->save($info);
            if($result){
                $this -> success('修改成功');
            }else{
                $this -> error('修改失败');
            }
        }
        else
        {
            $this -> display('pwd_change');
        }
	}
//	public function pwd_ch(){
//		$phone = I('phone');
//		$code = I('code');
//		$yanz['id'] = session('change_pwd');
//		if(!$phone || !$code){
//			$this -> error('密码或者确认密码不能为空');
//		}
//		if($phone != $code){
//			$this -> error('两次密码不一致');
//		}
//		$string = get_string();
//		$info['random']= $string->rand_string(6,1);
//		$info['password'] = get_pwd($phone, $info['random']);
//		$result = M('users') -> where($yanz) ->save($info);
//		if($result){
//			$this -> success('修改成功');
//		}else{
//			$this -> error('修改失败');
//		}
//	}
//    public function loginIndex()
//    {
//        if($this->isWeixin())
//        {
//            $weixinService = WeixinService::getInstance();
//            $authUrl = $weixinService->buildAuth2Url(urlencode($this->getRedirectUrl()));
//            redirect($authUrl);
//        }
//    }
    public function weixinRedirect()
    {
        $code = I('code');
        $weixinService = WeixinService::getInstance();
        $wechatUser = $weixinService->getUserInfo($code);
        if($wechatUser['openId'])
        {
            session('openId', $wechatUser['openId']);
            //没有打开开发者模式，获取不大搜unionid
            //session('unionid', $wechatUser['unionid']);
            $user = D('User')->findByOpenid($wechatUser['openId']);
            if($user)
            {
                $current_user['user_name'] = $user['user_name'];
                $current_user['phone'] = $user['phone'];
                $current_user['id'] = $user['id'];
                $current_user['user_type'] = $user['user_type'];
                session("user" , $current_user);//写入Session 登录成功
                $back = session('back');
                session('back','');
                if(isset($back) && !empty($back))
                {
                    redirect($back);
                    return;
                }else{
                    $this -> redirect("Index/index");
                    return;
                }
            }
            $this -> assign("redirectUrl", session('back'));
            $this -> display("index");
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
        $url .= U('Login/weixinRedirect');
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
}