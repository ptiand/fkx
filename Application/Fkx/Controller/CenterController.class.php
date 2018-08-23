<?php
namespace Home\Controller;
use Think\Controller;
class CenterController extends Controller {
	private $user_id;
	public function _initialize(){
		$user = get_user();
		if(!$user){
			//	session('back','Client/index');
			$this -> redirect('Login/index');
		}
		$this -> user_id = $user['id'];
		//	echo $user_id;
		session('key',4);
	}
    public function index(){

		$info = M('users') -> where(['id'=>$this -> user_id]) -> find();

		$this -> assign('info',$info);
		$this -> display();
	}

	public function mymoney(){
		$info = M('users') -> where(['id'=>$this -> user_id]) -> find();
		$this -> assign('info',$info);
		$this -> display();
	}
	public function editinfo(){
		$info = M('users') -> where(['id'=>$this -> user_id]) -> find();

		$this -> assign('info',$info);
		$this -> display();
	}

	public function logout() {
		session_destroy();
		redirect("/index.php/index/index");
	}
	//修改用户名
	public function edit_name(){
		$info = M('users') -> where(['id'=>$this -> user_id]) -> getField('user_name');
		$this -> assign('name',$info);
		$this -> display();
	}

	public function edit_n(){
		$data['user_name'] = I('user_name');
		$info = M('users') -> where(['id'=>$this -> user_id]) -> save($data);
		$this -> success('修改成功');
	}
	//修改用户名
	public function edit_pwd(){
		$this -> display();
	}
	public function edit_p(){
		$data = I('post.');
		if($data['pass'] == $data['y_pass']){
			$this -> error('新旧密码不能一样');
		}
		if($data['pass'] != $data['pwd']){
			$this -> error('新密码与确认密码不一致');
		}
		//验证原密码
		$info = M('users') -> where(['id'=>$this -> user_id]) -> find();
		$pwd = get_pwd($data['y_pass'], $info['random']);
		if($pwd != $info['password']){
			$this -> error('原密码错误！');
		}
		$string = get_string();
		$save['random']= $string->rand_string(6,1);
		$save['password'] = get_pwd($data['pass'], $save['random']);
		$resu = M('users') -> where(['id'=>$this -> user_id]) -> save($save);
		if($resu){
			session_destroy();
			$this -> success('修改成功');
		}else{
			$this -> error('修改失败！');
		}

	}
	public function imgset(){
		$this -> display();
	}
	public function head_pic(){
		$user_id = $this -> user_id;
		$src = I('pic');
		$url = explode(',',$src);
		$img = base64_decode($url[1]);
		$filename=  '/uploads/pic/'.$user_id.'.png';
		$pic=ROOT_PATH.$filename;
		$a = file_put_contents($pic, $img);
		if($a){
			$newWidth = $newHeight = 60;
			list($width,$height)=getimagesize($pic);
			//   echo $width;
			$img=imagecreatefrompng($pic);
			$newImg=imagecreatetruecolor($newWidth,$newHeight);
			if($newWidth && ($width<$height)){
				$newWidth=($newHeight/$height)*$width;
			}else{
				$newHeight=($newWidth/$width)*$height;
			}
			imagecopyresampled($newImg,$img,0,0,0,0,$newWidth,$newHeight,$width,$height);
			// 6、保存图像
			header('Content-type: image/png');
			imagepng($newImg,$pic);
			// 7、是放资源
			imagedestroy($img);
			imagedestroy($newImg);
			$resule = M('users') -> where(['id'=>$user_id]) -> save(['pic'=>$filename]);

			$this -> success('修改成功！');

		}else{
			$this -> error('上传失败！');
		}
	}

	//添加提现记录
	public function addout(){

		$w_time = session('w_time');

		$balance['time'] = time();
		if($w_time && $balance['time'] < $w_time+5*60*60){
			$this -> error('请等待管理员的审核！');
		}

		$type = I('type');
		if($type == 1){
			$balance['amount'] = I('yue');
		}else{
			$yue = M('users') -> where(['id'=> $this -> user_id]) -> getfield('balance');
			$balance['amount'] = floor($yue);
		}
		$balance['address'] =  I('address');
		$balance['type'] =  I('types');
		$balance['name'] =  I('name');
		$balance['cards'] =  I('cards');
		$balance['phone'] =  I('phone');
		$balance['time'] =  time();
		$balance['user_id'] = $this -> user_id;
		$res = M('withdrawals') -> add($balance);
		if($res){
			session('w_time',time());
			$this -> success('申请成功！');
		}else{
			$this -> error('申请失败，请重新申请！');
		}

	}
	//注册分享
	public function qrcode(){

		$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/weixin/qrcode';
		$esult = curl_request($url);


	}
}