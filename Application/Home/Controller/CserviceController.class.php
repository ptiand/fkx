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

class CserviceController extends UserController
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
                $this->redirect('../index', array(), 5, '房东身份请刷新进入！');
            }
        } else {
            // $this->redirect('../index', array(), 5, '您尚未登录！');
            $this->assign('isLogin', FALSE);
            $path = $_SERVER["REQUEST_URI"];
            session("back" , $path);
        }
        parent::_initialize();
        session('NavBar', "Service");
    }

    public function index() {
        $this -> display();
    }
    
    
    //载入账单
    public function load_bills()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        
        $where = array(
            'buy_user_id'    =>  $this->userId,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('bills')->where($where)->order('id desc')->limit($offset,$page_size)->select();
        //print_r($list);exit;
        
        $count = M('bills')-> where($where) -> order('id desc') -> count();
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        echo json_encode($list_array);
    }

    
    public function bills_info() {
        $bills_id = I("bills_id") ? I("bills_id") : 0;
        if(empty($bills_id))
        {
            $this->error('目标不存在！');
        }
        $where = array(
            'id'    =>  $bills_id,
        );
        $bills_info =  M('bills')->where($where)->find();
        if(empty($bills_info))
        {
            $this->error('目标账单不存在！');
        }
        
        //账单明细
        $where = array(
            'b_id'  =>  $bills_info['id']
        );
        $details = M('bills_detail')->where($where)->select();
        $bills_info['details'] = $details;
        
        //登记信息
        $where = array('id' => $bills_info['a_id']);
        $agreement_info = M('agreement')->where($where)->find();
        $this->assign('agreement_info', $agreement_info);
        
        $where = array(
            'id'    =>  $bills_info['house_id'],
        );
        $house_info = D('RentalHouse')->where($where)->find();
        $this->assign('house_info', $house_info);
        
        $where = array(
            'id'    =>  $bills_info['room_id'],
        );
        $room_info = M('apartment_rooms')->where($where)->find();
        $this->assign('room_info', $room_info);
        
        unset($details);
        
        //押付方式
        $this->assign('apartment_type', RentalHouseModel::$RentalTypeCaption);
        
        //其他费用项目
        $this->assign('cost_unit_id', RentalHouseModel::$cost_unit_id);
        
        //项目名称
        $this->assign('cost_name_id', RentalHouseModel::$cost_name_id);
        
        //收费方式
        $this->assign('cost_type_id', RentalHouseModel::$cost_type_id);
        
        //支付方式配置
        $this->assign('payment_type', RentalHouseModel::$payment_type);
        
        //收费日
        $this->assign('pay_date', RentalHouseModel::$pay_date);
        
        $this->assign('bills_info', $bills_info);
        $this -> display();
    }
    



}