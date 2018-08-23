<?php
namespace Home\Controller;
use Think\Controller;
class ListsController extends Controller {
	public function _initialize(){
		$user = get_user();
		if($user){
			//	session('back','Client/index');
			$this -> assign('yongjin',1);
		}else{
			$this -> assign('yongjin',2);
		}

	}
    public function index(){
		session('key',2);
		//查询楼盘
		//查询总价、
		$where['is_del'] = 0;
		$where['status'] = 1;
		$data = I('get.');
		if(isset($data['hx']) && !empty($data['hx'])){
			$where['huxing_id'] = $data['hx'];
		}
		if(isset($data['zx']) && !empty($data['zx'])){
			$where['zxiu_id'] = $data['zx'];
		}
		if(isset($data['zj']) && !empty($data['zj'])){
			$where['jiage_id'] = $data['zj'];
		}
		if(isset($data['ty']) && !empty($data['ty'])){
			$where['type_id'] = $data['ty'];
		}
		if(isset($data['cy']) && !empty($data['cy'])){
			$where['area_id'] = $data['cy'];
		}
		if(isset($data['sh']) && !empty($data['sh'])){
			$where['title|address'] = array('like',"%{$data['sh']}%");
		}
		$info = M('loupan') -> where($where) -> order('sort desc') ->limit(0,5) -> select();
		$jiage = M('jiage') -> where(['is_del'=>0]) -> select();
		$huxings = M('huxing') -> where(['is_del'=>0]) -> select();
		$zxiu = M('zxiu') -> where(['is_del'=>0]) -> select();
		$types = M('types') -> where(['is_del'=>0]) -> select();
		$city = M('citys') -> where(['is_del'=>0,'pid'=>0]) -> select();
		foreach($city as &$v){
			$v['son'] =  M('citys') -> where(['is_del'=>0,'pid'=>$v['id']]) -> select();
		}
	//	$u_info = $_SERVER['QUERY_STRING'];

	//	unset($hx_arr['hx']);

		parse_str($_SERVER['QUERY_STRING'],$arr);
		$sh = $cy = $zj = $ty = $zx = $huxing = $arr;
		unset($huxing['hx']);
		$url['hx'] = '/index.php/Home/Lists/index?'.http_build_query($huxing);
		unset($zx['zx']);
		$url['zx'] = '/index.php/Home/Lists/index?'.http_build_query($zx);
		unset($ty['ty']);
		$url['ty'] = '/index.php/Home/Lists/index?'.http_build_query($ty);
		unset($zj['zj']);
		$url['zj'] = '/index.php/Home/Lists/index?'.http_build_query($zj);
		unset($cy['cy']);
		$url['cy'] = '/index.php/Home/Lists/index?'.http_build_query($cy);
		unset($sh['sh']);
		$url['sh'] = '/index.php/Home/Lists/index?'.http_build_query($sh);
		$pagewhere = http_build_query($arr);
		$this -> assign('jiage',$jiage);
		$this -> assign('huxing',$huxings);
		$this -> assign('zxiu',$zxiu);
		$this -> assign('types',$types);
		$this -> assign('info',$info);
		$this -> assign('city',$city);
		$this -> assign('url',$url);
		$this -> assign('pagewhere',$pagewhere);
		$this -> display();
	}

	public function loadloupan(){
		$where['is_del'] = 0;
		$where['status'] = 1;
		$count = I('time');
		$pagewhere = I('where');
		parse_str($pagewhere,$data);
		if(isset($data['hx']) && !empty($data['hx'])){
			$where['huxing_id'] = $data['hx'];
		}
		if(isset($data['zx']) && !empty($data['zx'])){
			$where['zxiu_id'] = $data['zx'];
		}
		if(isset($data['zj']) && !empty($data['zj'])){
			$where['jiage_id'] = $data['zj'];
		}
		if(isset($data['ty']) && !empty($data['ty'])){
			$where['type_id'] = $data['ty'];
		}
		if(isset($data['cy']) && !empty($data['cy'])){
			$where['area_id'] = $data['cy'];
		}
		if(isset($data['sh']) && !empty($data['sh'])){
			$where['title|address'] = array('like',"%{$data['sh']}%");
		}
		$info = M('loupan') -> where($where) -> order('sort desc') ->limit(5*$count,5) -> select();
		//   dump($project);
		if($info){
			$this -> assign('info',$info);
			$this->success($this->fetch(),"",true);
		}else{
			$this -> error('没有更多数据！');
		}
	}

	public function lpinfo(){

		$user = get_user();
		if(!$user){
			$this -> redirect('Login/index');
		}
		$id = I('id');
		$where['id'] = $id;
		$info = M('loupan') -> where($where) ->  find();
		$info['city'] = M('citys') -> where(['id'=>$info['city_id']]) -> getField('city_name');
		$info['tag'] = explode(';',$info['tag']);
		//处处相应的佣金规则 跟 合作规则
		$cooperation = M('cooperation') -> where(['loupan_id'=>$id]) ->  find();
//		$cooperation['des'] = explode(';',$cooperation['des']);
		//佣金规则
		$commission = M('commission') -> where(['loupan_id'=>$id]) ->  find();
//		$commission['des'] = explode(';',$commission['des']);
		//查出轮播图
		$luobo = M('zhuli') -> where(['loupan_id'=>$id, 'type'=>1]) ->select();
		//   dump($luobo);
		$huxing = M('zhuli') -> where(['loupan_id'=>$id, 'type'=>2]) ->select();
		$this -> assign('luobo',$luobo);
		$this -> assign('huxing',$huxing);
		$this -> assign('info',$info);
		$this -> assign('cooperation',$cooperation);
		$this -> assign('commission',$commission);
		$this -> display();
	}
	public function detail(){
		$data = I('get.');
		if($data['type'] == 1){
			$table = 'commission';
		}else{
			$table = 'cooperation';
		}
		$info = M($table) -> where(['id' => $data['pid']]) -> find();
		$info['info'] = explode(';',$info['contents']);
		$info['type'] = $data['type'];
		$this -> assign('info',$info);
		$this -> display();
	}

}