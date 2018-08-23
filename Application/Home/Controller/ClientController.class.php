<?php
namespace Home\Controller;
use Think\Controller;
class ClientController extends Controller {
	private $user_id;
	public function _initialize(){
		$user = get_user();
		if(!$user){
		//	session('back','Client/index');
			$this -> redirect('Login/code_login');
		}
		$this -> user_id = $user['id'];

	//	echo $user_id;
	}

    public function index(){
		session('key',3);
		$status = I('status');
		$search = I('search');
		$where['user_id'] = $this -> user_id;

		if(isset($status) && !empty($status)){
			$w['status'] = $where['status'] = $status;
		}else{
			$w['status'] = '';
		}
		if(isset($search) && !empty($search)){
			$w['search'] = $where['phone|nick_name'] = ['like',"%{$search}%"];
		}else{
			$w['search'] = '';
		}

		$info = M('report') -> where($where) -> order('created_at desc')-> limit(0,20) ->select();

	//	dump($info);

		$count['all'] = M('report') -> where(['user_id'=>$where['user_id']]) -> count();
		$count['gj'] = M('report') -> where(['user_id'=>$where['user_id'],'status'=>1]) -> count();
		$count['cj'] = M('report') -> where(['user_id'=>$where['user_id'],'status'=>2]) -> count();
		$count['sx'] = M('report') -> where(['user_id'=>$where['user_id'],'status'=>3]) -> count();
		foreach($info as &$v){
			$v['loupan_name'] = M('loupan') -> where(['id'=>$v['loupan_id']]) -> getField('title');
			$v['created_at'] = date('Y-m-d',$v['created_at']);
			$v['status'] = rostatus($v['status']);
		}
		$this -> assign('info',$info);
		$this -> assign('w',$w);
		$this -> assign('count',$count);
		$this -> display();
	}

	public function info(){
		$id = I('id');
		$where['id'] = $id;
		$info = M('report') -> where($where) ->find();
		switch($info['zhiye']){
			case 1;
				$info['zhiye'] = '投资';
				break;
			case 2;
				$info['zhiye'] = '自住';
				break;
			case 3;
				$info['zhiye'] = '投资兼自住';
				break;
			default:
				$info['zhiye'] = '无';
				break;
		}
		switch($info['huxing']){
			case 1;
				$info['huxing'] = '一房';
				break;
			case 2;
				$info['huxing'] = '两房';
				break;
			case 3;
				$info['huxing'] = '三房';
				break;
			case 4;
				$info['huxing'] = '四房';
				break;
			case 5;
				$info['huxing'] = '五房及以上';
				break;
			default:
				$info['huxing'] = '无';
				break;
		}
		$info['sex'] = $info['sex']==1?'女':'男';
		$fin = M('followup') -> where(['report_id'=>$id]) ->select();
		foreach($fin as &$v){
			$v['created_at'] = date('Y-m-d',$v['created_at']);
		}
		$this -> assign('info',$info);
		$this -> assign('fin',$fin);
		$this -> display();
	}
	//客户报备
	public function report(){
		$this -> display();
	}
	//天假报备
	public function report_in(){
		$data = I('post.');
		$where['phone'] = $data['phone'];
		$where['status'] = ['lt',3];
		$ing = M('report') -> where($where) ->find();
		if($ing){
			$this -> error('该号码已被报备，尚在审核期，暂不可报备');
		}
		$data['user_id'] = $this -> user_id;
		$data['status'] = 1;
		$data['created_at'] = time();
		$info = M('report') -> add($data);
		if($info){
			$this -> success('报备成功');
		}else{
			$this -> error('报备失败');
		}

	}
	//看房
	public function kanf(){
		//查询楼盘
		$id = I('loupan_id');
		$info = M('loupan') -> where(['id'=>$id]) -> find();
		$this -> assign('info',$info);
		$this->display();
	}
	public function kanf_in(){
		$data = I('post.');
		$where['phone'] = $data['phone'];
		$ing = M('infos') -> where($where) ->find();
		if($ing){
			$this -> error('已经预约看房，请等待客服联系');
		}
		$data['user_id'] = $this -> user_id;

		$data['created_at'] = time();
		$info = M('infos') -> add($data);
		if($info){
			$this -> success('预约成功');
		}else{
			$this -> error('预约失败');
		}
	}
}