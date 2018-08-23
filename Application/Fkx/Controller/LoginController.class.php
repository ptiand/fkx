<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){

		$this -> display();
	}
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
				session("user" , $current_user);//写入Session 登录成功
			//	$back = session('back');
//				if(isset($back) && !empty($back)){
//					$this -> redirect($back);
//				}else{
					$this->success('登录成功');
//				}


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
		$info['is_del'] = 1;
		$result = M('users') -> add($info);
		if($result){
			$this -> success($result);
		}else{
			$this -> error('未知错误，请重试');
		}
	}
	public function regin(){
		$id = I('id');
		$this -> assign('id',$id);
		$this -> display();
	}
	public function regin_in(){
		$pass = I('pwd');
		$repass = I('repwd');
		$id = I('id');

		if(!$pass){
			$this -> error('密码不能为空');
		}
		if($pass != $repass){
			$this -> error('两次密码不一致');
		}
		//获取随机数
		$string = get_string();
		$info['random']= $string->rand_string(6,1);
		$info['password'] = get_pwd($pass, $info['random']);
		$info['user_name'] = I('nick_name');
		$info['is_del'] = 0;
		$result = M('users') -> where(['id'=>$id]) ->save($info);
		if($result){
			$this -> success('注册成功');
		}else{
			$this -> error('注册失败');
		}
	}
	//验证码登录
	public function code_login(){
		$this -> display();
	}
	//验证登录
	public function code_in(){
		$phone = I('phone');
		$code = I('code');
		$yanz = session('code');
		$time = time();
		if($code.$phone !=$yanz['num']){
			$this -> error('验证码错误');
		}
		if($time > $yanz['sand _time']){
			$this -> error('验证码过期');
		}
		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
		if($res){
			$current_user['user_name'] = $res['user_name'];
			$current_user['phone'] = $res['phone'];
			$current_user['id'] = $res['id'];
			session("user" , $current_user);//写入Session 登录成功
			//	$back = session('back');
//				if(isset($back) && !empty($back)){
//					$this -> redirect($back);
//				}else{
			$this->success('登录成功');
		}else{
			$this -> error('登录失败');
		}
	}

	public function forgetPwd(){

		$this -> display('forgetpwd');
	}
	public function code_change(){
		$phone = I('phone');
		$code = I('code');
		$yanz = session('code');
		$time = time();
		if($code.$phone !=$yanz['num']){
			$this -> error('验证码错误');
		}
		if($time > $yanz['sand _time']){
			$this -> error('验证码过期');
		}
		$res = M('users') -> where(['phone'=>$phone,'is_del'=>0]) -> find();
		if($res){
			session('change_pwd', $res['id']);
			$this->success('验证成功');
		}else{
			$this -> error('验证失败');
		}
	}
	public function pwd_change(){
		$this -> display();
	}
	public function pwd_ch(){
		$phone = I('phone');
		$code = I('code');
		$yanz['id'] = session('change_pwd');
		if(!$phone || !$code){
			$this -> error('密码或者确认密码不能为空');
		}
		if($phone != $code){
			$this -> error('两次密码不一致');
		}
		$string = get_string();
		$info['random']= $string->rand_string(6,1);
		$info['password'] = get_pwd($phone, $info['random']);
		$result = M('users') -> where($yanz) ->save($info);
		if($result){
			$this -> success('修改成功');
		}else{
			$this -> error('修改失败');
		}
	}

}