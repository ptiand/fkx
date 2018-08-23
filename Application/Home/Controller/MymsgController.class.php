<?php

namespace Home\Controller;

use Think\Exception;
use Think\Model;
use Think\Controller;
use Common\Model\OldHouseModel;
use Common\Model\MediaModel;
use Common\Model\TypeModel;
use Common\Model\CityModel;
use Common\Model\HuXingModel;
use Common\Model\RentalHouseModel;
use Common\Model\Shops;
use Think\Upload;
use Think\Log;

class MymsgController extends UserController
{
    protected $userId;
    protected $shop_id = 0;
    public function _initialize()
    {
        $user = get_user();
        if ($user) {
            $this->assign('isLogin', TRUE);
            $this->userId = $user['id'];
            
            if($user['user_type'] == 1)
            {
                $shop_info = D('Shops')->where(array('user_id' => $user['id']))->find();
                if($shop_info)
                {
                    $this->shop_id = $shop_info['id'];
                }
            }
        } else {
            $this->assign('isLogin', FALSE);
            $path = $_SERVER["REQUEST_URI"];
            session("back" , $path);
        }
        session('NavBar', "Service");
    }

    public function index() {
        $this -> display();
    }
    
    
    
    
    
    //载入公寓
    public function load_msg()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        
        $where = array(
            'to_user_id'    =>  $this->userId,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('user_msg')->where($where)->limit($offset,$page_size)->select();
        //print_r($list);exit;
        
        $count = M('user_msg')-> where($where) -> count();
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        if(!empty($list_array['rows'])){
            foreach ($list_array['rows'] as $key => $value) {
                if(!empty($value['create_time'])) {
                    $list_array['rows'][$key]['create_time'] = date('m-d H:i', $value['create_time']);
                }
            }
        }
        echo json_encode($list_array);
    }

    
    public function read_msg() {
        $msg_id = I("msg_id") ? I("msg_id") : 0;
        if(empty($msg_id))
        {
            $this->error('目标不存在！');
        }
        $where = array(
            'id'    =>  $msg_id,
        );
        $msg_info =  M('user_msg')->where($where)->find();
        if(empty($msg_info))
        {
            $this->error('目标消息不存在！');
        }
        
        if($msg_info['is_read'] == 0)
        {
            $update = array('is_read' => 1, 'update_time' => time());
            M('user_msg')->where($where)->save($update);
        }
        if(!empty($msg_info['create_time'])) {
            $msg_info['create_time'] = date('Y-m-d H:i:s', $msg_info['create_time']);
        }
        $this->assign('msg_info', $msg_info);
        $this -> display();
    }
    
    public function confirm_do()
    {
        $msg_id = I("msg_id") ? I("msg_id") : 0;
        if(empty($msg_id))
        {
            $uri = explode('/',$_SERVER["REQUEST_URI"]);
            $msg_id = $uri[count($uri)-1];
            if(empty($msg_id) || !is_numeric($msg_id))
            {
                $this->error('目标不存在！');
            }
        }
        $where = array(
            'id'    =>  $msg_id,
        );
        
        $msg_info =  M('user_msg')->where($where)->find();
        if(empty($msg_info))
        {
            $this->error('目标消息不存在！');
        }
        
        if($msg_info['to_user_id'] !=  $this->userId)
        {
            $this->error('您无操作权限！');
        }
        
        $update = array(
            'is_confirm'    =>  1,
            'update_time'   =>  time(),
        );
        M('user_msg')->where($where)->save($update);
        
        $where = array('id' => $msg_info['a_id']);
        $agreement_info = M('agreement')->where($where)->find();
        
        if(empty($agreement_info) || $agreement_info['buy_confirm'] == 1)
        {
            $this->error('租赁信息不存在或已确认过了！');
        }
        
        $update = array(
            'buy_confirm' => 1,
            'confirm_date'  =>  date('Y-m-d', time()),
            'update_time'   =>  time(),
        );
        
        M('agreement')->where($where)->save($update);
        
        //房间登记更新
        $where = array('id' => $agreement_info['room_id']);
        $update = array('is_tenant' => 1);
        M('apartment_rooms')->where($where)->save($update);
        
        $this->success('您已确认租赁信息，请与房东联系！', '');
    }

}