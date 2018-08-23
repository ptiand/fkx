<?php
/**
 * Created by PhpStorm.
 * User: Huge
 * Date: 2016/3/5 0005
 * Time: 14:00
 */

namespace Admin\Controller;


use Common\Model\InfoModel;
use Common\Model\ReportModel;
use Common\Model\RewardModel;
use Common\Model\ShopsModel;
use Common\Model\UserAccountModel;
use Think\Exception;

class ShopsController extends AdminController
{
    protected function _initialize(){
        parent::_initialize();

    }
    public function shop_list(){
        $this->assign('status_name', array(1 => '审核通过', 2 => '审核中', 3 => '审核未通过'));
        // $user_info = D('users')->where(array('id' => '100022'))->find();
        // print_r($user_info);exit;
//        $list =  D('Shops')->limit(0, 20)->select();
//        print_r($list);exit;
        $this->display();
    }
    
    //店铺列表
    public function load_list(){
        // sort:id order:desc limit:10 offset:0
        $offset = i("offset");
        $limit = i("limit");
        $auto = i("auto");
        $search_key = i('search_key');
        $where = array('status' => 1);

        $search_value = i('search');
        if($search_value){
            $where['user_id|shop_name'] = array('LIKE',"%$search_value%");;
        }

        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id desc';
        }
        $list =  D('Shops')->where($where)->order($reorder)->limit($offset,$limit)->select();
        foreach($list as &$v){
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
        }
        
        //dump($list);
        $count = D('Shops')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //添加店铺信息
    public function add_shop(){
       if(IS_POST){
            $data = i('post.');
            $data['create_time'] = time();
            $user_info = D('users')->where(array('id' => $data['user_id']))->find();
            if(empty($user_info))
            {
                $this->error('用户不存在！');
            }
            if($user_info['user_type'] == 1)    //已有店铺
            {
                $this->error('用户已是房东身份');
            }
            $res = D('Shops') -> add($data);
            if($res){
                D('users')->where(array('id' => $data['user_id']))->save(array('user_type' => 1));
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！');
            }
       }else{
           $this -> display('edit_shop');
       }
    }
    //修改会员
    public function edit_shop(){
        $id = i('id');
        $where['id'] = $id;
        $info = D('Shops')->where($where)->find();
        if(IS_POST){
            $data = i('post.');
            //相等的话，则不做图片删除处理
            if($data['user_id'] != $info['user_id']){
                $user_info = D('users')->where(array('id' => $data['user_id']))->find();
                if(empty($user_info))
                {
                    $this->error('用户不存在！');
                }
                if($user_info['user_type'] == 1)    //已有店铺
                {
                    $this->error('用户已是房东身份');
                }
            }
            D('Shops')->where($where)->save($data);
            //D('users')->where(array('id' => $data['user_id']))->save(array('user_type' => 1));
            $this -> success('修改成功！');
        }else{
            $this->assign('info',$info);
            $this -> display('edit_shop');
        }
    }

    //删除店铺
    public function del_shop(){
        //$array_id = explode(';',$_POST['ids']);
        $arr["id"] = array("in",$_POST['ids']);
        $data['status'] = 2;
        if(D('Shops')->where($arr)->save($data) !== false){
            $this -> success('删除成功！');
        }else{
            $this -> error("删除失败！");
        }
    }
    
    // 单个店铺审批
    public function approve_shop(){
        $id = i('id');
        $where['id'] = $id;
        $info = D('Shops')->where($where)->find();
        $this->assign('info',$info);
        $this -> display('approve_shop');
        
    }
    
    //审核店铺
    public function apply_shop(){
        $arr["id"] = array("in",$_POST['ids']);
        
        $shop_info = D('Shops')->where($arr)->select();
        
        $data = i('post.');
        if($data['request_flag'] == 1)
        {
            $retrun_msg = '审核通过..';
            $user_update = array('user_type' => 1);
            $where_id = array(0);
            foreach($shop_info as $val)
            {
                $where_id[] = $val['user_id'];
            }
        }
        else
        {
            $retrun_msg = '审核不通过..';
        }
        if(D('Shops')->where($arr)->save($data) !== false){
            if(!empty($user_update))
            {
                D('users')->where(array('id' => array('in', $where_id)))->save($user_update);
            }
            $this -> success($retrun_msg.'成功！');
        }else{
            $this -> error($retrun_msg."失败！");
        }
    }
    
    

}
