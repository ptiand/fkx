<?php
/**
 * Created by PhpStorm.
 * User: Huge
 * Date: 2016/3/5 0005
 * Time: 14:00
 */

namespace Admin\Controller;


class LoupanController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();


    }

    public function index(){
        $this->display();
    }
    public function loadLoupan(){
        // sort:id order:desc limit:10 offset:0
        $where['is_del'] = 0;
        //$page = i('page');
        //$rows = i('rows');
        $offset = i("offset");

        $limit = i("limit");
        $status = i("status");
        $search_key = i('search_key');
        if(!empty($status) && isset($status)){
            $where['status'] = $status;
        }
        $search_value = i('search');
        if($search_value){
            $where['title'] = array('LIKE',"%$search_value%");;
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort) && $sort!='aid'){
            $reorder = ''.$sort." ".$order;
        }else{
            $reorder = 'id desc';
        }
        $list =  M('loupan')
            -> where($where)
            -> order($reorder)
            ->limit($offset,$limit)
            -> select();
//        dump(M('project')->getlastsql());
        foreach($list as &$v){
            $v['city_id'] = M('citys') -> where(['id' => $v['city_id']]) -> getField('city_name');
            $v['area_id'] = M('citys') -> where(['id' => $v['area_id']]) -> getField('city_name');
            $v['huxing_id'] = M('huxing') -> where(['id' => $v['huxing_id']]) -> getField('huxing_name');
            $v['type_id'] = M('types') -> where(['id' => $v['type_id']]) -> getField('type_name');
            $v['zxiu_id'] = M('zxiu') -> where(['id' => $v['zxiu_id']]) -> getField('zxiu_name');
            $v['jiage_id'] = M('jiage') -> where(['id' => $v['jiage_id']]) -> getField('jiage_name');
            $v['add_at'] = date('Y-m-d',$v['add_at']);
            $v['status'] = $v['status']==1?'正常':'锁定';
        }
        //dump($list);
        $count =  M('loupan')
            -> where($where)
            -> order($reorder)
            -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //添加楼盘信息
    public function addLoupan(){
       if(IS_POST){
            $data = i('post.');
           $data['add_at'] = strtotime($data['add_at']);
           $data['tag'] = rtrim($data['tag'],';');
           $data['created_at'] = strtotime($data['created_at']);
           $data['city_id'] = M('citys')->where(['id'=> $data['area_id']]) -> getfield('pid');
           $res = M('loupan') -> add($data);
            if($res){
                $this->success('添加成功！');
            }else{
                $this -> error('添加失败！');
            }
       }else{
           $res = M('citys') -> where(['pid'=>['GT',0],'is_del'=>0]) ->getField("id,city_name");
           $jiage = M('jiage') -> where(['is_del'=>0]) ->getField("id,jiage_name");
           $type = M('types') -> where(['is_del'=>0]) ->getField("id,type_name");
           $huxing = M('huxing') -> where(['is_del'=>0]) ->getField("id,huxing_name");
           $zxiu = M('zxiu') -> where(['is_del'=>0]) ->getField("id,zxiu_name");
           //  $arr =$this->dal_cs ->where($where)->order("Sort asc")->getField("CtmStatusID,CtmStatusName");
           $this->assign('menu',$res);
           $this->assign('jiage',$jiage);
           $this->assign('type',$type);
           $this->assign('huxing',$huxing);
           $this->assign('zxiu',$zxiu);
           //dump($res);
           $this -> display('editloupan');
       }
    }
    //修改项目
    public function editLoupan(){
        $id = i('id');
        $where['id'] = $id;
        $info = M('loupan') -> where($where) -> find();
        if(IS_POST){
            $data = i('post.');
            if($data['pic'] != $info['pic']){
                $file = ROOT_PATH.$info['pic'];
                unlink($file);
            }

            $data['add_at'] = strtotime($data['add_at']);
            $data['created_at'] = strtotime($data['created_at']);
            $data['tag'] = rtrim($data['tag'],';');
            M('loupan') -> where($where) -> save($data);
            $this -> success('修改成功！');
        }else{
            $info['add_at'] = date('Y-m-d',$info['add_at']);
            $info['created_at'] =  date('Y-m-d',$info['created_at']);
            $res = M('citys') -> where(['pid'=>['GT',0],'is_del'=>0]) ->getField("id,city_name");
            $jiage = M('jiage') -> where(['is_del'=>0]) ->getField("id,jiage_name");
            $type = M('types') -> where(['is_del'=>0]) ->getField("id,type_name");
            $huxing = M('huxing') -> where(['is_del'=>0]) ->getField("id,huxing_name");
            $zxiu = M('zxiu') -> where(['is_del'=>0]) ->getField("id,zxiu_name");
            //  $arr =$this->dal_cs ->where($where)->order("Sort asc")->getField("CtmStatusID,CtmStatusName");
            $this->assign('menu',$res);
            $this->assign('jiage',$jiage);
            $this->assign('type',$type);
            $this->assign('huxing',$huxing);
            $this->assign('zxiu',$zxiu);
            $this->assign('info',$info);
            $this -> display('editloupan');
        }
    }
    //删除项目
    public function delLoupan(){
        //$array_id = explode(';',$_POST['ids']);
        $arr["id"] = array("in",$_POST['ids']);
        $data['is_del'] = 1;
        if(M('loupan')->where($arr)->save($data) !== false){
            $this -> success('删除成功！');
        }else{
            $this -> error("删除失败！");
        }
    }
    //修改详细信息
    public function editinfo(){

        $id = I('id');
        $luobo = M('zhuli') -> where(['loupan_id'=>$id, 'type'=>1]) ->select();
     //   dump($luobo);
        $huxing = M('zhuli') -> where(['loupan_id'=>$id, 'type'=>2]) ->select();
        $this -> assign('luobo', $luobo);
        $this -> assign('huxing', $huxing);
        $this -> assign('id', $id);

        $this->display();
    }
    public function uplb(){
        $info = I('post.');
        $info['type'] = 1;
        $res = M('zhuli')->add($info);
        if($res){
            $this -> success('上传成功！');
        }else{
            $this -> error("上传失败！");
        }
    }
    public function uphx(){
        $info = I('post.');
        $info['type'] = 2;
        $res = M('zhuli')->add($info);
        if($res){
            $this -> success('上传成功！');
        }else{
            $this -> error("上传失败！");
        }
    }

    public function updel(){
        $id = I('id');
        $info = M('zhuli')-> where(['id'=>$id]) ->find();
        $res = M('zhuli')-> where(['id'=>$id]) ->delete();
        if($res){
            unlink(ROOT_PATH.$info['pic']);
            $this -> success('删除成功！');
        }else{
            $this -> error("删除失败！");
        }
    }
    //佣金规则
    public function addYongjin(){
        if(IS_POST){
            $data = i('post.');
            M('commission') -> add($data);
            $this->success('增加成功！');

        }else{
            $id = I('id');
            $info = M('commission')-> where(['loupan_id'=>$id]) -> select();
            $this -> assign('info',$info);
            $this -> display('addyongjin');
        }
    }

    public function editYongjin(){
        $data = i('post.');
        $info['des'] = $data['des'];
        $info['contents'] = $data['contents'];
        $where['id'] = $data['id'];
        $res = M('commission')-> where($where) -> save($info);
        if($res){
            $this->success('修改成功！');
        }
    }
    public function delYongjin(){
        $data = i('id');
        $where['id'] = $data;
        $res = M('commission')-> where($where) -> delete();
        if($res){
            $this->success('删除成功！');
        }
    }

    //合作规则
    public function addHezuo(){
        if(IS_POST){
            $data = i('post.');
            M('cooperation') -> add($data);
            $this->success('增加成功！');

        }else{
            $id = I('id');
            $info = M('cooperation')-> where(['loupan_id'=>$id]) -> select();
            $this -> assign('info',$info);
            $this -> display('addhezuo');
        }
    }

    public function editHezuo(){
        $data = i('post.');
        $info['des'] = $data['des'];
        $info['contents'] = $data['contents'];
        $where['id'] = $data['id'];
        $res = M('cooperation')-> where($where) -> save($info);
        if($res){
            $this->success('修改成功！');
        }
    }
    public function delHezuo(){
        $data = i('id');
        $where['id'] = $data;
        $res = M('cooperation')-> where($where) -> delete();
        if($res){
            $this->success('删除成功！');
        }
    }

    //项目类型
    public function jiage(){
        $this -> display();
    }
    //加载项目类型
    public function loadJiage(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        $where['is_del'] = 0;
        if($search_value){
            $where['jiage_name'] = array('LIKE',"%$search_value%");;
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id asc';
        }
        $list =  M('jiage')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();
        //dump($list);
        $count =   M('jiage')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //增加项目类型
    public function addJiage(){
        if(IS_POST){
            $data = i('post.');
            M('jiage') -> add($data);
            $this->success('增加成功！');
        }else{

            $this-> display('editjiage');
        }
    }
    //修改项目类型
    public function editJiage(){
        $id = i('id');
        $where['id'] = $id;
        $res = M('jiage') -> where($where)-> find();
        if(IS_POST){
            $data = i('post.');

            if($data['small_pic'] != $res['small_pic']){
                unlink(ROOT_PATH.$res['small_pic']);
            }
            M('jiage') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('info',$res);
            $this-> display('editjiage');
        }
    }
    //删除项目类型
    public function delJiage(){
        $array_id['id'] = array('in',$_POST['ids']);
        M('jiage') -> where($array_id) -> save(['is_del'=>1]);
        $this -> success('删除成功！');
    }
    //项目类型
    public function huxing(){
        $this -> display();
    }
    //加载项目类型
    public function loadHuxing(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        $where['is_del'] = 0;
        if($search_value){
            $where['huxing_name'] = array('LIKE',"%$search_value%");;
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id asc';
        }
        $list =  M('huxing')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();
        //dump($list);
        $count =   M('huxing')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //增加项目类型
    public function addHuxing(){
        if(IS_POST){
            $data = i('post.');
            M('huxing') -> add($data);
            $this->success('增加成功！');
        }else{

            $this-> display('edithuxing');
        }
    }
    //修改项目类型
    public function editHuxing(){
        $id = i('id');
        $where['id'] = $id;
        $res = M('huxing') -> where($where)-> find();
        if(IS_POST){
            $data = i('post.');

            M('huxing') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('info',$res);
            $this-> display('edithuxing');
        }
    }
    //删除项目类型
    public function delHuxing(){
        $array_id['id'] = array('in',$_POST['ids']);
        M('huxing') -> where($array_id) -> save(['is_del'=>1]);
        $this -> success('删除成功！');
    }
//项目类型
    public function zxiu(){
        $this -> display();
    }
    //加载项目类型
    public function loadZxiu(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        $where['is_del'] = 0;
        if($search_value){
            $where['zxiu_name'] = array('LIKE',"%$search_value%");;
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id asc';
        }
        $list =  M('zxiu')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();
        //dump($list);
        $count =   M('zxiu')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //增加项目类型
    public function addZxiu(){
        if(IS_POST){
            $data = i('post.');
            M('zxiu') -> add($data);
            $this->success('增加成功！');
        }else{

            $this-> display('editzxiu');
        }
    }
    //修改项目类型
    public function editZxiu(){
        $id = i('id');
        $where['id'] = $id;
        $res = M('zxiu') -> where($where)-> find();
        if(IS_POST){
            $data = i('post.');

            M('zxiu') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('info',$res);
            $this-> display('editzxiu');
        }
    }
    //删除项目类型
    public function delZxiu(){
        $array_id['id'] = array('in',$_POST['ids']);
        M('zxiu') -> where($array_id) -> save(['is_del'=>1]);
        $this -> success('删除成功！');
    }




    //项目类型
    public function types(){
        $this -> display();
    }
    //加载项目类型
    public function loadTypes(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        $where['is_del'] = 0;
        if($search_value){
            $where['type_name'] = array('LIKE',"%$search_value%");;
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id asc';
        }
        $list =  M('types')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();
        //dump($list);
        $count =   M('types')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //增加项目类型
    public function addTypes(){
        if(IS_POST){
            $data = i('post.');
            M('types') -> add($data);
            $this->success('增加成功！');
        }else{

            $this-> display('edittypes');
        }
    }
    //修改项目类型
    public function editTypes(){
        $id = i('id');
        $where['id'] = $id;
        $res = M('types') -> where($where)-> find();
        if(IS_POST){
            $data = i('post.');
            M('types') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('info',$res);
            $this-> display('edittypes');
        }
    }
    //删除项目类型
    public function delTypes(){
        $array_id['id'] = array('in',$_POST['ids']);
        M('types') -> where($array_id) -> save(['is_del'=>1]);
        $this -> success('删除成功！');
    }

    //项目类型


    //广告
    public function ad(){
        $this -> display();
    }
    //加载广告
    public function loadAd(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        if($search_value){
            $where['title'] = array('LIKE',"%$search_value%");;
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id asc';
        }
        $list =  M('ad')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();

        foreach ($list as &$v){
            $v['pic'] = "<img src='{$v['pic']}' width='250' alt='广告图'>";
            $v['status'] = $v['status']==1?'下线':'上线';
        }
        //dump($list);
        $count =   M('ad')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //增加广告
    public function addAd(){
        if(IS_POST){
            $data = i('post.');
            M('ad') -> add($data);
            $this->success('增加成功！');
        }else{

            $this-> display('editad');
        }
    }
    //修改广告
    public function editAd(){
        $id = i('id');
        $where['id'] = $id;
        $res = M('ad') -> where($where)-> find();
        if(IS_POST){
            $data = i('post.');

            if($data['pic'] != $res['pic']){
                unlink(ROOT_PATH.$res['pic']);
            }
            M('ad') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('info',$res);
            $this-> display('editad');
        }
    }
    //删除广告
    public function delAd(){
        $array_id['id'] = array('in',$_POST['ids']);
        $pic = M('ad') -> where($array_id) -> field('pic') -> select();
        $res = M('ad') -> where($array_id) -> delete();
        if($res){
            foreach($pic as $p){
                unlink(ROOT_PATH.$p['pic']);
            }
            $this -> success('删除成功！');
        }else{
            $this -> error('删除失败！');
        }
    }






















}
