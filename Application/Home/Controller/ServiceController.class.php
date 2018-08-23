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

class ServiceController extends UserController
{
    protected $userId;
    protected $shop_id = 0;
    public function _initialize()
    {
        $user = get_user();
        if ($user) {
            $this->assign('isLogin', TRUE);
            $this->userId = $user['id'];
            $shop_info = D('Shops')->where(array('user_id' => $user['id']))->find();
			// 根据shop_info进行判断，使用user可能存在状态未及时更新
            if(empty($shop_info) || $shop_info['request_flag'] != 1)
            {
                $this->redirect('/Cservice', array(), 0, '');
            }
            if($shop_info)
            {
                $this->shop_id = $shop_info['id'];
                $this->assign('shop_info', $shop_info);
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
    
    
    //公寓管理
    public function houses_setup()
    {
        $where = array(
            'user_id'   =>  $this->userId,
            'request_flag'  =>  1,
            'status'    =>  1,
        );
        $shop_info = D('Shops')->where($where)->find();
        if(empty($shop_info))
        {
            $this->redirect('index', 5, '您没有店铺或被管理员关闭！');
        }
        $this->assign('shop_info', $shop_info);
        $this -> display();
    }
    
    
    //载入公寓
    public function load_houses()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        
        $where = array(
            'source'    =>  9,
            'userId'    =>  $this->userId,
            'shop_id'   =>  $this->shop_id,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  D('RentalHouse')->where($where)->limit($offset,$page_size)->select();
        //print_r($list);exit;
        
        $count = D('RentalHouse')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    
    public function del_house()
    {
        $house_id = I("house_id") ? I("house_id") : 0;
        if(empty($house_id))
        {
            $this->error('目标不存在！');
        }
        
        $where = array(
            'id'    =>  $house_id,
        );
        $house_info =  D('RentalHouse')->where($where)->find();
        if(empty($house_info))
        {
            $this->error('目标公寓不存在！');
        }
        
        //是否有未结束登记信息
        $where = array(
            'house_id'  =>  $house_id,
            'status'    =>  1
        );
        $count_agreement = M('agreement')->where($where)->count();
        if($count_agreement > 0)
        {
            $this->error('该公寓尚有租凭登记未结束！');
        }
        
        $where = array(
            'house_id'  =>  $house_id,
        );
        M('apartment_rooms')->where($where)->delete();
        M('apartment_floor')->where($where)->delete();
        $where = array(
            'id'    =>  $house_id,
        );
        D('RentalHouse')->where($where)->delete();
        $this->success('删除成功！', '');
    }

    
    public function houses_info() {
        $house_id = I("house_id") ? I("house_id") : 0;
        if(empty($house_id))
        {
            $this->error('目标不存在！');
        }
        $where = array(
            'id'    =>  $house_id,
        );
        $house_info =  D('RentalHouse')->where($where)->find();
        if(empty($house_info))
        {
            $this->error('目标公寓不存在！');
        }
        
        //楼层数据
        $field = 'id,floor_name';
        $where = array(
            'house_id'  =>  $house_id,
        );
        $floor_info = M('apartment_floor')->field($field)->where($where)->select();
        
        
        //房间数据
        $room_info = M('apartment_rooms')->where($where)->select();
        if(!empty($room_info))
        {
            foreach($floor_info as &$val)
            {
                foreach($room_info as $room)
                {
                    if($room['floor_id'] == $val['id'])
                    {
                        $val['room_info'][] = $room;
                    }
                }
            }
        }
        unset($room_info);
        
        $house_info['floor_info'] = $floor_info;
        
        //print_r($house_info);
        //print_r($floor_info);
        $this->assign('house_info', $house_info);
        $this -> display();
    }
    
    public function tenant_info() {
        $house_id = I("house_id") ? I("house_id") : 0;
        if(empty($house_id))
        {
            $this->error('目标不存在！');
        }
        $where = array(
            'id'    =>  $house_id,
        );
        $house_info =  D('RentalHouse')->where($where)->find();
        if(empty($house_info))
        {
            $this->error('目标公寓不存在！');
        }
        
        //楼层数据
        $field = 'id,floor_name';
        $where = array(
            'house_id'  =>  $house_id,
        );
        $floor_info = M('apartment_floor')->field($field)->where($where)->select();
        
        
        //房间数据
        $room_info = M('apartment_rooms')->where($where)->select();
        if(!empty($room_info))
        {
            foreach($floor_info as &$val)
            {
                foreach($room_info as $room)
                {
                    if($room['floor_id'] == $val['id'])
                    {
                        if($room['is_tenant'] == 1)
                        {
                            //找到对应的登记ID
                            $a_where = array(
                                'house_id'  =>  $room['house_id'],
                                'room_id'   =>  $room['id'],
                                'status'    =>  1,
                            );
                            
                            $a_info = M('agreement')->field('id')->where($a_where)->find();
                            $room['agreement_id'] = empty($a_info['id']) ? 0 : $a_info['id'];
                        }
                        else
                        {
                            $room['agreement_id'] = 0;
                        }
                        $val['room_info'][] = $room;
                    }
                }
            }
        }
        unset($room_info);
        
        $house_info['floor_info'] = $floor_info;
        
        //print_r($house_info);
        //print_r($floor_info);
        $this->assign('house_info', $house_info);
        $this -> display();
    }
    
    
    public function add_floor() {
        if(IS_POST) {
        
            $data = array(
                'house_id' => I("house_id") ? I("house_id") : 0,
                'floor_name'    =>  I("floor_name") ? I("floor_name") : '',
            );
            if(empty($data['house_id']) || empty($data['floor_name']))
            {
                $this->error('参数错误，请检查！');
            }
            
            //公寓是否存在
            $where = array(
                'id'    =>  $data['house_id']
            );
            $house_info =  D('RentalHouse')->where($where)->find();
            if(empty($house_info))
            {
                $this->error('目标公寓不存在！');
            }
            
            //楼层是否存在
            $check_floor = M('apartment_floor')->field('id')->where($data)->select();
            if(!empty($check_floor))
            {
                $this->error('该公寓下楼层名称已存在');
            }
            
            
            $data['create_time'] = time();
            $flag = M('apartment_floor')->add($data);
            
            if($flag != false)
            {
                $return_msg = '增加楼层成功';
            }
            else
            {
                $return_msg = '异常错误，请刷新重试';
            }
            
            $this->success($return_msg, '');
        }
    }
    
    public function add_room()
    {
        if(IS_POST) {
            $data = array(
                'house_id' => I("house_id") ? I("house_id") : 0,
                'floor_id' => I("floor_id") ? I("floor_id") : 0,
            );
            
            $room_name = I("room_name") ? I("room_name") : '';
            $add_num = I("add_num") ? I("add_num") : 0;
            
            if(empty($data['house_id']) || empty($data['floor_id']) || empty($add_num))
            {
                $this->error('参数错误，请检查！');
            }
            
            //公寓是否存在
            $where = array(
                'id'  =>  $data['house_id'],
            );
            $house_info =  D('RentalHouse')->where($where)->find();
            if(empty($house_info))
            {
                $this->error('目标公寓不存在！');
            }
            
            //楼层数据是否存在
            $where = array(
                'house_id'  =>  $data['house_id'],
                'id'  =>  $data['floor_id'],
            );
            $floor_info = M('apartment_floor')->where($where)->find();
            if(empty($floor_info))
            {
                $this->error('目标楼层不存在！');
            }
            
            $insert_arr = array();
            if($add_num > 1)
            {
                //取出当前楼层最后一个房间名
                $tmp_room = M('apartment_rooms')->field('id,room_name')->where($data)->order('id desc')->find();
                if(empty($tmp_room))
                {
                    if(is_numeric($floor_info['floor_name']))
                    {
                        $tmp_room_name = $floor_info['floor_name'].'000';
                    }
                }
                else
                {
                    if(is_numeric($tmp_room['room_name']))
                    {
                        $tmp_room_name = $tmp_room['room_name'];
                    }
                }
                
                for($i = 1; $i <= $add_num; $i++)
                {
                    if(is_numeric($tmp_room_name))
                    {
                        $data['room_name'] = $tmp_room_name + $i;
                    }
                    else
                    {
                        $data['room_name'] = $tmp_room_name.'00'.$i;
                    }
                    $data['room_num'] = 1;
                    $data['living_num'] = 1;
                    $data['rest_num'] = 1;
                    $data['create_time'] = time();
                    $insert_arr[] = $data;
                }
            }
            else
            {
                if(empty($room_name))
                {
                    $this->error('房间名称不能为空！');
                }
                $data['room_name'] = $room_name;
                $check_room = M('apartment_rooms')->field('id')->where($data)->find();
                if(!empty($check_room))
                {
                    $this->error('该公寓楼层房名重复！');
                }
                $data['room_num'] = 1;
                $data['living_num'] = 1;
                $data['rest_num'] = 1;
                $data['create_time'] = time();
                $insert_arr[] = $data;
            }
            
            
            M('apartment_rooms')->addAll($insert_arr);
            $this->success('添加成功！', '');
        }
    }
    
    public function del_room()
    {
        if(IS_POST)
        {
            $room_id = I("room_id") ? I("room_id") : 0;
            
            if(!is_numeric($room_id) || empty($room_id))
            {
                $this->error('参数错误！');
            }
            
            $where = array(
                'id'    =>  $room_id
            );
            $check_room = M('apartment_rooms')->where($where)->find();
            if($check_room['is_tenant'] != 0)
            {
                $this->error('该房间已出租或已在进行租凭登记，不能删除！');
            }
            
            M('apartment_rooms')->where($where)->delete();
            $this->success('删除成功！', '');
            
        }
    }
    
    
    public function del_floor()
    {
        if(IS_POST)
        {
            $floor_id = I("floor_id") ? I("floor_id") : 0;
            
            if(!is_numeric($floor_id) || empty($floor_id))
            {
                $this->error('参数错误！');
            }
            
            $where = array(
                'id'    =>  $floor_id
            );
            $check_floor = M('apartment_floor')->where($where)->find();
            if(empty($check_floor))
            {
                $this->error('目标楼层不存在！');
            }
            
            //判断当前楼层是否有不能删除的房间
            $where = array(
                'floor_id'    =>  $floor_id,
                'is_tenant' =>      1
            );
            $check_room = M('apartment_rooms')->where($where)->find();
            if(!empty($check_room))
            {
                $this->error('该楼层存在房间已出租或已在进行租凭登记，不能删除！');
            }
            
            M('apartment_rooms')->where($where)->delete();
            M('apartment_floor')->where(array('id' => $floor_id))->delete();
            $this->success('删除成功！', '');
            
        }
    }
    
    public function edit_room()
    {
        $room_id = I("room_id") ? I("room_id") : 0;
            
        if(!is_numeric($room_id) || empty($room_id))
        {
            $this->error('参数错误！');
        }
        
        //房间信息
        $where = array(
            'id'    =>  $room_id
        );
        $room_info = M('apartment_rooms')->where($where)->find();
        if(empty($room_info))
        {
            $this->error('目标房间不存在！');
        }
        
        //朝向配置
        $this->assign('orientations', RentalHouseModel::$HouseDirectionList);
        //装修配置
        $zx_list = M('zxiu')->where(array('is_del' => 0))->select();
        $this->assign('zx_list', $zx_list);
        //公寓类型
        $this->assign('apartment_type', RentalHouseModel::$Apartment_type);
        //押付方式
        $this->assign('rental_type_caption', RentalHouseModel::$RentalTypeCaption);
        //设备信息
        $this->assign('ptxx_options', RentalHouseModel::$PtxxOptions);
        
        if(!empty($room_info['more_options']))
        {
            $room_info['more_options'] = json_decode($room_info['more_options']);
        }
        $this->assign('room_info', $room_info);
        $this -> display();
    }
    
    
    public function update_edit_room()
    {
        if(IS_POST)
        {
            $data = i('post.');
            
            if(empty($data['id']) || !is_numeric($data['id']))
            {
                $this->error('参数错误！');
            }
            
            $where = array('id' => $data['id']);
            $room_info = M('apartment_rooms')->where($where)->find();
            
            if(empty($room_info))
            {
                $this->error('目标房间不存在！');
            }
            
            $update_arr = array(
                'room_num'      =>  $data['room_num'],
                'living_num'    =>  $data['living_num'],
                'rest_num'      =>  $data['rest_num'],
                'kitchen_num'   =>  $data['kitchen_num'],
                'veranda_num'   =>  $data['veranda_num'],
                'area_count'    =>  $data['area_count'],
                'orientations'  =>  $data['orientations'],
                'zx_id'         =>  $data['zx_id'],
                'apartment_type'  =>  $data['apartment_type'],
                'price'         =>  $data['price'],
                'foregift'      =>  $data['foregift'],
                'rental_type'   =>  $data['rental_type'],
                'more_options'  => json_encode($data['more_options']),
                'update_time'   =>  time()
            );
            
            M('apartment_rooms')->where($where)->save($update_arr);
            $this->success('设置成功！', '');
        }
    }
    
    //登记租客
    public function register_tenant()
    {
        $id = I("id") ? I("id") : 0;
        $agreement_info = array();
        if(!empty($id))
        {
            $agreement_info = M('agreement')->where(array('id' => $id))->find();
            //租凭其他费用信息
            $agreement_cost = array();
            $where = array(
                'a_id'  =>  $agreement_info['id'],
            );
            $agreement_cost = M('agreement_cost')->where($where)->select();

            $agreement_info['cost_info'] = $agreement_cost;
        }
        $this->assign('agreement_info', $agreement_info);
        
        $room_id = I("room_id") ? I("room_id") : 0;
        if(!is_numeric($room_id) || empty($room_id))
        {
            $this->error('参数错误！');
        }
        
        //房间信息
        $where = array(
            'id'    =>  $room_id
        );
        $room_info = M('apartment_rooms')->where($where)->find();
        if(empty($room_info))
        {
            $this->error('目标房间不存在！');
        }
        
        $where = array(
            'id'    =>  $room_info['house_id'],
        );
        $house_info = D('RentalHouse')->where($where)->find();
        $this->assign('house_info', $house_info);
        
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
        
        $this->assign('room_info', $room_info);
        if (empty($agreement_info)){
            $this -> display();
        } else {
            $this -> display('edit_tenant');
        }
    }
    
    //登记租客保存程序
    public function update_register_tenant()
    {
        if(IS_POST)
        {
            $data = i('post.');
            
            //验证租户是否存在
            $buy_user_phone = $data['buy_user_phone'] ? $data['buy_user_phone'] : 0;
            $where = array(
                'phone' =>  $buy_user_phone,
            );
            $buy_user = M('users')->where($where)->find();
            if(empty($buy_user))
            {
                $this->error('目标租客尚未绑定房客行公众号！');
            }
            if($buy_user['user_type'] == 1)
            {
                $this->error('目标租客是房东身份，不能登记！');
            }
            
            //检查房间是否有效
            $where = array(
                'id'    =>  $data['room_id'] ? $data['room_id'] : 0,
            );
            $room_info = M('apartment_rooms')->where($where)->find();
            if(empty($room_info))
            {
                $this->error('目标房间不存在！');
            }
            if($room_info['is_tenant'] != 0)
            {
                $this->error('目标房间已登记租凭！');
            }
            
            $house_where = array('id' => $room_info['house_id']);
            $house_info =  D('RentalHouse')->where($house_where)->find();
            
            
            //主表信息
            $agreement_insert = array(
                'house_id'  =>  $room_info['house_id'],
                'room_id'   =>  $data['room_id'],
                'sell_user_id'  =>  $this->userId,
                'buy_user_id'   =>  $buy_user['id'],
                'buy_user_name' =>  $data['buy_user_name'],
                'buy_user_phone'    =>  $data['buy_user_phone'],
                'start_date'    =>  $data['start_date'],
                'end_date'      =>  $data['end_date'],
                'price'         =>  $data['price'],
                'foregift'      =>  $data['foregift'],
                'rental_type'   =>  $data['rental_type'],
                'payment_type'  =>  $data['payment_type'],
                'pay_date'      =>  $data['pay_date'],
                'create_time'   =>  time(),
            );
            
            //租客表信息
            $other_user = $data['users'];
            $user_insert = array();
            if(!empty($other_user))
            {
                foreach($other_user as $val)
                {
                    $user_insert[] = array(
                        'user_name' =>  $val['user_name'],
                        'user_phone' =>  $val['user_phone'],
                        'create_time'   =>  time(),
                    );
                }
            }
            
            //费用信息
            $other_cost = $data['costs'];
            $cost_insert = array();
            if(!empty($other_cost))
            {
                foreach($other_cost as $val)
                {
                    $cost_insert[] = array(
                        'cost_name_id'  =>  $val['cost_name_id'],
                        'cost_type_id'  =>  $val['cost_type_id'],
                        'cost_unit_id'  =>  $val['cost_unit_id'],
                        'cost'  =>  $val['cost'],
                        'cur_num'  =>  $val['cur_num'],
                        'create_time'   =>  time(),
                    );
                }
            }
            
            //进表操作
            if(!empty($data['id']))
            {
                $a_info = M('agreement')->where(array('id' => $data['id']))->find();
                if(empty($a_info))
                {
                    $this->error('目标登记租凭信息不存在！');
                }
                if($a_info['buy_confirm'] == 1 || $a_info['status'] == 2)
                {
                    $this->error('当前状态不可编辑！');
                }
                $flag = M('agreement')->where(array('id' => $data['id']))->save($agreement_insert);
                if($flag === false)
                {
                    $this->error('操作失败，请刷新重试！');
                }
                $a_id = $data['id'];
            }
            else
            {
                $a_id = M('agreement')->add($agreement_insert);
                $room_update = array('is_tenant' => 2);
                M('apartment_rooms')->where(array('id' => $data['room_id']))->save($room_update);
            }
            
            if($a_id === false)
            {
                $this->error('操作失败，请刷新重试！');
            }
            if(!empty($user_insert))
            {
                foreach($user_insert as &$val)
                {
                    $val['a_id'] = $a_id;
                }
                M('agreement_user')->where(array('a_id' => $a_id))->delete();
                M('agreement_user')->addAll($user_insert);
                
            }
            if(!empty($other_cost))
            {
                foreach($other_cost as &$val)
                {
                    $val['a_id'] = $a_id;
                }
                M('agreement_cost')->where(array('a_id' => $a_id))->delete();
                M('agreement_cost')->addAll($other_cost);
            }
            
            
            //站内通知
            $content = "尊敬的{$buy_user['user_name']},您有一条租凭信息需要确认.";
            $msg_insert = array(
                'send_user_id'  =>  0,  //系统
                'to_user_id'    =>  $buy_user['id'],
                'content'       =>  $content,
                'msg_type'      =>  1,
                'a_id'          =>  $a_id,
                'create_time'   =>  time(),
            );
            $msg_id = M('user_msg')->add($msg_insert);
            
            //走微信通知
            $weixin_msg = false;
            if($weixin_msg)
            {
                $msg = array(
                    'touser'    =>  $buy_user['openid'],
                    'template_id'   =>  C('WEIXIN_TENANT_ID'),
                    'url'   =>  ($_SERVER['SERVER_NAME'].'/index.php/Mymsg/confirm_do/'.$msg_id),
                    'data'  =>  array(
                        'first'    =>  array('value' => "您好，以下为您的租赁明细"),
                        'keyword1' =>  array('value' => $house_info['name'].'-'.$room_info['room_name'].'房'),
                        'keyword2' =>  array('value' => "租金{$agreement_insert['price']}元；押金{$agreement_insert['foregift']}元；"),
                        'keyword3' =>  array('value' => $agreement_insert['start_date'].' 至 '.$agreement_insert['end_date']),
                        'remark'   =>  array('value' => '点击确认~'),
                    )
                );
                      //print_r($msg);exit;  
                $weixin = A('Weixin');
                $result = $weixin->push_message($msg);

                if($result->errcode != 0)
                {
                    $this->error('异常错误，请刷新重试！');
                }
            }
            
            $this->success('成功登记，已发站内消息给租客确认！', '');
        }
    }
    
    //抄表程序
    public function update_cost_detail()
    {
        $detail_ids = I('detail_id')? I('detail_id') : array();
        $cur_num = I('cur_num');
        
        $bills_info = array();
        $bills_update['total_price'] = 0;
        foreach($detail_ids as $key => $detail_id)
        {
            
            if(empty($bills_info))
            {
                $where = array('id' => $detail_info['b_id']);
                $bills_info = M('bills')->where($where)->find();
                $bills_update['total_price'] = $bills_info['total_price'];
            }
            
            if(empty($detail_id) || !is_numeric($detail_id))
            {
                 $this->error('参数错误！');
            }
            $detail_info = M('bills_detail')->where(array('id' => $detail_id))->find();
            
            if($detail_info['cur_num'] > 0)
            {
                $bills_update['total_price'] -= $detail_info['total_cost'];
            }
            
            $update = array(
                'cur_num'  =>  $cur_num[$key],
                'total_cost'    =>  (($cur_num[$key] - $detail_info['last_num']) * $detail_info['cost']),
                'update_time'   =>  time(),
            );
            M('bills_detail')->where(array('id' => $detail_id))->save($update);
            
            
            
            $bills_update['total_price'] += (($cur_num[$key] - $detail_info['last_num']) * $detail_info['cost']);
        }

        M('bills')->where($where)->save($bills_update);
        
        
        $this->success('成功抄表', '');
    }
    
    //账单通知
    public function push_bills()
    {
        $b_id = I('b_id')? I('b_id') : 1;
        if(empty($b_id) || !is_numeric($b_id))
        {
             $this->error('参数错误！');
        }
        $bills_info = M('bills')->where(array('id' => $b_id))->find();
        if(empty($bills_info))
        {
             $this->error('目标账单不存在！');
        }
        
        $keyword1 = '租金'.$bills_info['price'].'元; ';
        //费用明细
        $where = array('b_id' => $b_id);
        $cost_infos = M('bills_detail')->where($where)->select();
        $cost_name_sys = RentalHouseModel::$cost_name_id;
        //print_r($cost_name_sys);exit;
        foreach($cost_infos as $val)
        {
            $keyword1 .= $cost_name_sys[$val['cost_unit_id']][$val['cost_name_id']].$val['total_cost'].'元; ';
        }
        
        
        //租凭登记信息
        $where = array(
            'id' => $bills_info['a_id'],
        );
        $agreement_info = M('agreement')->where($where)->find();
        
        //用户信息
        $where = array(
            'id'    =>  $agreement_info['buy_user_id'],
        );
        $buy_user = M('users')->where($where)->find();
        if(empty($buy_user['openid']))
        {
            $this->error('租客没有绑定微信！');
        }
        
        //公寓名称
        $where = array('id' => $agreement_info['house_id']);
        $house_info = D('RentalHouse')->where($where)->find();
        
        //房间名称
        $where = array('id' => $agreement_info['room_id']);
        $room_info = M('apartment_rooms')->where($where)->find();
        
        $msg = array(
            'touser'    =>  $buy_user['openid'],
            'template_id'   =>  C('WEIXIN_BILLS_MSGID'),
            'url'   =>  ($_SERVER['SERVER_NAME'].'/index.php/WeixinPay/pay/'.$b_id),
            'data'  =>  array(
                'first'    =>  array('value' => "尊敬的{$house_info['name']}·{$room_info['room_name']}租户，以下为您的月度账单，请查收"),
                'keyword1' =>  array('value' => $keyword1),
                'keyword2' =>  array('value' => "{$bills_info['start_date']} 至 {$bills_info['end_date']}"),
                'keyword3' =>  array('value' => $bills_info['total_price']),
                'remark'   =>  array('value' => '点击支付~'),
            )
        );
              //print_r($msg);exit;  
        $weixin = A('Weixin');
        $result = $weixin->push_message($msg);
        
        if($result->errcode != 0)
        {
            $this->error('异常错误，请刷新重试！');
        }
        
        $update_arr = array(
            'is_msg'    =>  1,
        );
        $where = array('id' => $b_id);
        M('bills')->where($where)->save($update_arr);
        
        $this->success('公众号成功推送账单给租客！', '');
    }
    
    
    public function bills_info()
    {
        $room_id = I("room_id");
        if(!is_numeric($room_id) || empty($room_id))
        {
            $this->error('参数错误！');
        }
        
        //房间信息
        $where = array(
            'id'    =>  $room_id
        );
        $room_info = M('apartment_rooms')->where($where)->find();
        if(empty($room_info))
        {
            $this->error('目标房间不存在！');
        }
        
        if($room_info['is_tenant'] != 1)
        {
            $this->error('目标房间没有租赁信息！');
        }
        
		// 添加公寓和房间信息 by qzh
        $where = array(
            'id'    =>  $room_info['house_id'],
        );
        $house_info = D('RentalHouse')->where($where)->find();
        $this->assign('room_info', $room_info);
        $this->assign('house_info', $house_info);
        
        //租凭主信息
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'room_id'   =>  $room_id,
            'status'    =>  1,
        );
        
        $agreement_info = M('agreement')->where($where)->find();
        
        //print_r($agreement_info);exit;
        if(empty($agreement_info))
        {
            $this->error('租赁信息不存在！');
        }
        
        //租凭其他费用信息
        $agreement_cost = array();
        $where = array(
            'a_id'  =>  $agreement_info['id'],
        );
        $agreement_cost = M('agreement_cost')->where($where)->select();
        
        
        
        
        //账单信息
        //计算当前月份账单时间
        $cur_date = $this->get_tenant_date($agreement_info['start_date'],$agreement_info['rental_type']);
        
        $where = array(
            'room_id'   =>  $agreement_info['room_id'],
            'start_date'=>  $cur_date[0],
            'end_date'=>  $cur_date[1],
        );
        
        $bill_info = M('bills')->where($where)->find();
        if(empty($bill_info))
        {
            //生成账单
            $bill_info = $this->create_bill($agreement_info, $agreement_cost, $cur_date);
        }
        else
        {
            $bill_info['details'] = M('bills_detail')->where(array('b_id' => $bill_info['id']))->select();
        }
        
        //print_r($bill_info);exit;
        $this->assign('bill_info', $bill_info);

        $agreement_info['rental_type'] = RentalHouseModel::$RentalTypeCaption[$agreement_info['rental_type']];
        $agreement_info['pay_date'] = RentalHouseModel::$pay_date[$agreement_info['pay_date']];
        $agreement_info['cost_info'] = $agreement_cost;
        $this->assign('agreement_info', $agreement_info);
        
        //其他费用项目
        $this->assign('cost_unit_id', RentalHouseModel::$cost_unit_id);
        
        //项目名称
        $this->assign('cost_name_id', RentalHouseModel::$cost_name_id);
        
        //收费方式
        $this->assign('cost_type_id', RentalHouseModel::$cost_type_id);
        $this->display();
    }

    public function total_detail() {
        $this->display();
    }

    public function total_item_detail() {
        $type_map = array('1' => '月收入', '2' => '欠费', '3' => '上月收入', '4' => '即将到期', '5' => '空置率');
        $type = I('type');
        if (empty($type) || !isset($type_map[$type])) {
            $type = '1';
            $type_name = $type_map['1'];
        } else {
            $type_name = $type_map[$type];
        }
        $this->assign('type', $type);
        $this->assign('type_name', $type_name);
        $this->display();
    }

    public function total_item4house_detail() {
        $house_id = I('house_id')? I('house_id') : 0;
        if(empty($house_id) || !is_numeric($house_id))
        {
             $this->error('参数错误！');
        }

        $where = array(
            'id'    =>  $house_id,
        );
        $house_info = D('RentalHouse')->where($where)->find();
        $this->assign('house_info', $house_info);

        $type_map = array('1' => '月收入', '2' => '欠费', '3' => '上月收入', '4' => '即将到期', '5' => '空置率');
        $type_url_map = array(
            '1' => 'load_month_in_detail', 
            '2' => 'load_not_pay_detail', 
            '3' => 'load_last_month_in_detail', 
            '4' => 'load_be_expire_detail', 
            '5' => 'load_empty_rate_detail'
        );
        $type = I('type');
        if (empty($type) || !isset($type_map[$type])) {
            $type = '1';
            $type_name = $type_map['1'];
            $type_url = $type_url_map['1'];
        } else {
            $type_name = $type_map[$type];
            $type_url = $type_url_map[$type];
        }
        $this->assign('type', $type);
        $this->assign('type_name', $type_name);
        $this->assign('type_url', $type_url);

        $this->display();
    }
    
    public function gift_bill()
    {
        $bill_id = I('bill_id')? I('bill_id') : 0;
        if(empty($bill_id) || !is_numeric($bill_id))
        {
             $this->error('参数错误！');
        }
        $bill_info = M('bills')->where(array('id' => $bill_id))->find();
        
        if($bill_info['gift_price'] > 0)
        {
            $bill_info['total_price'] += $bill_info['gift_price'];
        }
        $update = array(
            'gift_price'    =>  I('gift_price')? I('gift_price') : 0,
            'total_price'   =>  $bill_info['total_price'] - (I('gift_price')? I('gift_price') : 0)
        );
        M('bills')->where(array('id' => $bill_id))->save($update);
        
        $this->success('成功设置优惠', '');
    }
    
    //确认收款
    public function confirm_pay()
    {
        $bill_id = I('bill_id')? I('bill_id') : 0;
        if(empty($bill_id) || !is_numeric($bill_id))
        {
             $this->error('参数错误！');
        }
        $bill_info = M('bills')->where(array('id' => $bill_id))->find();
        
        if($bill_info['is_pay'] == 1 || $bill_info['pay_state'] == 1)
        {
            $this->error('不能重复确认！');
        }
        $update = array(
            'is_pay'    =>  1,
        );
        M('bills')->where(array('id' => $bill_id))->save($update);
        
        $this->success('确认收款成功~！', '');
    }
    
    public function check_out()
    {
        $a_id = I("a_id") ? I("a_id") : 0;
        if(empty($a_id))
        {
            $this->error('参数错误！');
        }
        
        $where = array(
            'id' => $a_id,
            'status'    =>  1,
        );
        $agreement_info = M('agreement')->where($where)->find();
        $upadte = array(
            'status'    =>  2,
            'update'    =>  time(),
        );
        M('agreement')->where($where)->save($upadte);
        
        
        $where = array(
            'id'    =>  $agreement_info['room_id'],
        );
        $room_info = M('apartment_rooms')->where($where)->find();
        $upadte = array(
            'is_tenant'    =>  0,
            'tenant_user_id'    =>  0,
            'update_time'   =>  time(),
        );
        M('apartment_rooms')->where($where)->save($upadte);
        
        
        $this->success('成功退房', '');
    }
    
    public function load_not_pay_detail()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        $house_id = I('house_id') ? I('house_id') : 0;
        
        // 本月起始时间:
        $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
        $end_date = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
        
        //欠费
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'house_id'  =>  $house_id,
            //'start_date'  =>  array('between',array($start_date,$end_date)),
            'is_msg'    =>  1,
            'is_pay'    =>  0,
            'pay_state' =>  0,
        );
        $not_pay = M('bills')->where($where)->sum('total_price');
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('bills')->where($where)->limit($offset,$page_size)->select();
        $count = M('bills')->where($where)->count();
        
        $where_ids = array(0);
        foreach($list as &$val)
        {
            $where_ids[] = $val['room_id'];
        }
        $where = array('id' => array('in', $where_ids));
        $room_name = M('apartment_rooms')->field('id,room_name')->where($where)->select();
        
        foreach($list as &$val)
        {
            foreach($room_name as $rkey => $rval)
            {
                if($rval['id'] == $val['room_id'])
                {
                    $val['room_name'] = $rval['room_name'];
                    break;
                }
            }
        }
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array(), 'total_not_pay' => $not_pay);
        echo json_encode($list_array);
    }
    
    public function load_month_in_detail()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        $house_id = I('house_id') ? I('house_id') : 0;
        
        // 本月起始时间:
        $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
        $end_date = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
        
        //月收入
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'house_id'  =>  $house_id,
            'pay_date'  =>  array('between',array($start_date,$end_date)),
            '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
        );
        $month_in = M('bills')->where($where)->sum('total_price');
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('bills')->where($where)->limit($offset,$page_size)->select();
        $count = M('bills')->where($where)->count();
        
        $where_ids = array(0);
        foreach($list as &$val)
        {
            $where_ids[] = $val['room_id'];
        }
        $where = array('id' => array('in', $where_ids));
        $room_name = M('apartment_rooms')->field('id,room_name')->where($where)->select();
        
        foreach($list as &$val)
        {
            foreach($room_name as $rkey => $rval)
            {
                if($rval['id'] == $val['room_id'])
                {
                    $val['room_name'] = $rval['room_name'];
                    break;
                }
            }
        }
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array(), 'total_month_in' => $month_in);
        //print_r($list_array);exit;
        echo json_encode($list_array);
    }
    
    public function load_empty_rate_detail()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        $house_id = I('house_id') ? I('house_id') : 0;
        
        //所有房间
        $where = array(
            'house_id'  =>  $house_id,
        );
        $all_room = M('apartment_rooms')->where($where)->count();

        //空置房
        $where['is_tenant'] = 0;
        $empty_room = M('apartment_rooms')->where($where)->count();


        $empty_rate = intval($empty_room / $all_room * 100);
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('apartment_rooms')->where($where)->limit($offset,$page_size)->select();
        $count = M('apartment_rooms')->where($where)->count();
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array(), 'total_empty_rate' => $empty_rate);
        //print_r($list_array);exit;
        echo json_encode($list_array);
    }
    
    public function load_be_expire_detail()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        $house_id = I('house_id') ? I('house_id') : 0;
        
        //即将到期
        $today = date('Y-m-d', time());
        $expire = date('Y-m-d', time() + 86400 * 30);
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'house_id'  =>  $house_id,
            'end_date'  =>  array('between',array($today,$expire)),
        );
        $be_expire = M('agreement')->where($where)->count();
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('agreement')->where($where)->limit($offset,$page_size)->select();
        $count = M('agreement')->where($where)->count();
        
        $where_ids = array(0);
        foreach($list as &$val)
        {
            $where_ids[] = $val['room_id'];
        }
        $where = array('id' => array('in', $where_ids));
        $room_name = M('apartment_rooms')->field('id,room_name')->where($where)->select();
        
        foreach($list as &$val)
        {
            foreach($room_name as $rkey => $rval)
            {
                if($rval['id'] == $val['room_id'])
                {
                    $val['room_name'] = $rval['room_name'];
                    break;
                }
            }
        }
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array(), 'total_be_expire' => $be_expire);
        //print_r($list_array);exit;
        echo json_encode($list_array);
    }
    
    
    public function load_last_month_in_detail()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        $house_id = I('house_id') ? I('house_id') : 0;
        
        
        //上月收入
        // 上个月的起始时间:
        $last_start_date = date('Y-m-01',strtotime('-1 month'));
        $last_end_date = date("Y-m-d", strtotime(-date('d').'day'));
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'house_id'  =>  $house_id,
            'pay_date'  =>  array('between',array($last_start_date,$last_end_date)),
            '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
        );
        $last_month_in = M('bills')->where($where)->sum('total_price');
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  M('bills')->where($where)->limit($offset,$page_size)->select();
        $count = M('bills')->where($where)->count();
        
        $where_ids = array(0);
        foreach($list as &$val)
        {
            $where_ids[] = $val['room_id'];
        }
        $where = array('id' => array('in', $where_ids));
        $room_name = M('apartment_rooms')->field('id,room_name')->where($where)->select();
        
        foreach($list as &$val)
        {
            foreach($room_name as $rkey => $rval)
            {
                if($rval['id'] == $val['room_id'])
                {
                    $val['room_name'] = $rval['room_name'];
                    break;
                }
            }
        }
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array(), 'total_last_month_in' => $last_month_in);
        //print_r($list_array);exit;
        echo json_encode($list_array);
    }
    
    public function load_total_tongchou()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        
        $load_type = I('load_type') ? I('load_type') : 1;
        
        $where = array(
            'source'    =>  9,
            'userId'    =>  $this->userId,
            'shop_id'   =>  $this->shop_id,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  D('RentalHouse')->where($where)->limit($offset,$page_size)->select();
        $count = D('RentalHouse')->where($where)->count();
        
        foreach($list as &$val)
        {
            // 本月起始时间:
            $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
            $end_date = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
            switch($load_type)
            {
                case 1:
                    //月收入
                    $where = array(
                        'sell_user_id'  =>  $this->userId,
                        'house_id'  =>  $val['id'],
                        'pay_date'  =>  array('between',array($start_date,$end_date)),
                        '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
                    );
                    $val['month_in'] = M('bills')->where($where)->sum('total_price');
                    break;
                case 2:
                    //欠费
                    $where = array(
                        'sell_user_id'  =>  $this->userId,
                        'house_id'  =>  $val['id'],
                        //'start_date'  =>  array('between',array($start_date,$end_date)),
                        'is_msg'    =>  1,
                        'is_pay'    =>  0,
                        'pay_state' =>  0,
                    );
                    $val['not_pay'] = M('bills')->where($where)->sum('total_price');
                    break;
                case 3:
                    //上月收入
                    // 上个月的起始时间:
                    $last_start_date = date('Y-m-01',strtotime('-1 month'));
                    $last_end_date = date("Y-m-d", strtotime(-date('d').'day'));
                    $where = array(
                        'sell_user_id'  =>  $this->userId,
                        'house_id'  =>  $val['id'],
                        'pay_date'  =>  array('between',array($last_start_date,$last_end_date)),
                        '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
                    );
                    $val['last_month_in'] = M('bills')->where($where)->sum('total_price');
                    break;
                case 4:
                    //即将到期
                    $today = date('Y-m-d', time());
                    $expire = date('Y-m-d', time() + 86400 * 30);
                    $where = array(
                        'sell_user_id'  =>  $this->userId,
                        'house_id'  =>  $val['id'],
                        'end_date'  =>  array('between',array($today,$expire)),
                    );
                    $val['be_expire'] = M('agreement')->where($where)->count();
                    break;
                case 5:
                     //空置率
                    //所有房间
                    $where = array(
                        'house_id'  =>  $val['id'],
                    );
                    $all_room = M('apartment_rooms')->where($where)->count();

                    //空置房
                    $where['is_tenant'] = 0;
                    $empty_room = M('apartment_rooms')->where($where)->count();


                    $val['empty_rate'] = intval($empty_room / $all_room * 100);
                    break;
            }

        }
        
        // print_r($list);exit;
        
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    
    public function load_house_tongchou()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        
        $where = array(
            'source'    =>  9,
            'userId'    =>  $this->userId,
            'shop_id'   =>  $this->shop_id,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  D('RentalHouse')->where($where)->limit($offset,$page_size)->select();
        $count = D('RentalHouse')->where($where)->count();
        
        foreach($list as &$val)
        {
            // 本月起始时间:
            $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
            $end_date = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));

            //月收入
            $where = array(
                'sell_user_id'  =>  $this->userId,
                'house_id'  =>  $val['id'],
                'pay_date'  =>  array('between',array($start_date,$end_date)),
                '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
            );
            $val['month_in'] = M('bills')->where($where)->sum('total_price');
            
            //欠费
            $where = array(
                'sell_user_id'  =>  $this->userId,
                'house_id'  =>  $val['id'],
                //'start_date'  =>  array('between',array($start_date,$end_date)),
                'is_msg'    =>  1,
                'is_pay'    =>  0,
                'pay_state' =>  0,
            );
            $val['not_pay'] = M('bills')->where($where)->sum('total_price');

            //上月收入
            // 上个月的起始时间:
            $last_start_date = date('Y-m-01',strtotime('-1 month'));
            $last_end_date = date("Y-m-d", strtotime(-date('d').'day'));
            $where = array(
                'sell_user_id'  =>  $this->userId,
                'house_id'  =>  $val['id'],
                'pay_date'  =>  array('between',array($last_start_date,$last_end_date)),
                '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
            );
            $val['last_month_in'] = M('bills')->where($where)->sum('total_price');

            //即将到期
            $today = date('Y-m-d', time());
            $expire = date('Y-m-d', time() + 86400 * 30);
            $where = array(
                'sell_user_id'  =>  $this->userId,
                'house_id'  =>  $val['id'],
                'end_date'  =>  array('between',array($today,$expire)),
            );
            $val['be_expire'] = M('agreement')->where($where)->count();

            //空置率
            //所有房间
            $where = array(
                'house_id'  =>  $val['id'],
            );
            $all_room = M('apartment_rooms')->where($where)->count();

            //空置房
            $where['is_tenant'] = 0;
            $empty_room = M('apartment_rooms')->where($where)->count();


            $val['empty_rate'] = intval($empty_room / $all_room * 100);
        }
        
        //print_r($list);exit;
        
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    
    public function load_tongchou()
    {
        // 本月起始时间:
        $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
        $end_date = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));

        //月收入
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'pay_date'  =>  array('between',array($start_date,$end_date)),
            '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
        );
        $return['month_in'] = M('bills')->where($where)->sum('total_price');
        
        //欠费
        $where = array(
            'sell_user_id'  =>  $this->userId,
            //'start_date'  =>  array('between',array($start_date,$end_date)),
            'is_msg'    =>  1,
            'is_pay'    =>  0,
            'pay_state' =>  0,
        );
        $return['not_pay'] = M('bills')->where($where)->sum('total_price');
        
        //上月收入
        // 上个月的起始时间:
        $last_start_date = date('Y-m-01',strtotime('-1 month'));
        $last_end_date = date("Y-m-d", strtotime(-date('d').'day'));
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'pay_date'  =>  array('between',array($last_start_date,$last_end_date)),
            '_string'   =>  ' (is_pay = 1)  OR ( pay_state = 1) ',
        );
        $return['last_month_in'] = M('bills')->where($where)->sum('total_price');
        
        //即将到期
        $today = date('Y-m-d', time());
        $expire = date('Y-m-d', time() + 86400 * 30);
        $where = array(
            'sell_user_id'  =>  $this->userId,
            'end_date'  =>  array('between',array($today,$expire)),
        );
        $return['be_expire'] = M('agreement')->where($where)->count();
        
        //空置率
        //所有公寓
        $where = array(
            'source'    =>  9,
            'userId'    =>  $this->userId,
        );
        $all_house  =   D('RentalHouse')->where($where)->select();
        $all_room = 0;
        $empty_room = 0;
        if(!empty($all_house)){
            $where_ids = array();
            foreach($all_house as $val)
            {
                $where_ids[] = $val['id'];
            }
            //所有房间
            $where = array(
                'house_id' => array('in', $where_ids),
            );
            $all_room = M('apartment_rooms')->where($where)->count();
            
            //空置房
            $where['is_tenant'] = 0;
            $empty_room = M('apartment_rooms')->where($where)->count();
        }
        
        
        $return['empty_rate'] = empty($all_room) ? 0 : intval($empty_room / $all_room * 100);
        //print_r($all_house);exit;
        echo json_encode($return);
        
    }
    
    private function create_bill($agreement_info, $agreement_cost, $cur_date)
    {   
        
        $month = $agreement_info['rental_type'] == 9 ? 1 : $agreement_info['rental_type'];
        
        //检查是否是第一次账单
        $is_first = true;
        $where = array('a_id' => $agreement_info['id']);
        $is_first = M('bills')->where($where)->order('id desc')->find();
        $last_detail = array();
        if(!empty($is_first))
        {
            //上次明细
            $where = array('b_id' => $is_first['id']);
            $last_detail = M('bills_detail')->where($where)->find();
        }
        
        $bill_info = array(
            'a_id'  =>  $agreement_info['id'],
            'sell_user_id'  =>  $agreement_info['sell_user_id'],
            'buy_user_id'  =>  $agreement_info['buy_user_id'],
            'house_id'  =>  $agreement_info['house_id'],
            'room_id'  =>  $agreement_info['room_id'],
            'start_date'  =>  $cur_date[0],
            'end_date'  =>  $cur_date[1],
            'total_price'  =>  ($agreement_info['price'] * $month),
            'price'  =>  ($agreement_info['price'] * $month),
            'is_msg'  =>  0,
            'is_pay'  =>  0,
            'create_time'  =>  time(),
            'update_time'   =>  0,
        );
        
        //计算其他费用
        $cost_info = array();
        if(!empty($agreement_cost))
        {
            foreach($agreement_cost as $key => $val)
            {
                $tmp_data = array(
                    'a_c_id'    =>  $val['id'],
                    'cost_unit_id'  =>  $val['cost_unit_id'],
                    'cost_name_id'  =>  $val['cost_name_id'],
                    'cost_type_id'  =>  $val['cost_type_id'],
                    'cost'          =>  0,
                    'total_cost'   =>  0,
                    'last_num'      =>  0,
                    'cur_num'      =>  0,
                    'create_time'  =>  time(),
                );
                switch($val['cost_unit_id'])
                {
                    case 1: //固定费用
                        if($val['cost_type_id'] == 1 && $is_first == false)
                        {
                            $tmp_data['cost'] = $val['cost'];
                            $tmp_data['total_cost'] = $val['cost'];
                            $bill_info['total_price'] += $val['cost'];
                        }
                        if($val['cost_type_id'] == 2)
                        {
                            $tmp_data['cost'] = $val['cost'];
                            $tmp_data['total_cost'] = $val['cost'] * $month;
                            $bill_info['total_price'] += $val['cost'] * $month;
                        }
                        break;
                    case 2:
                        if($is_first == false)  //首次
                        {
                            $tmp_data['last_num'] = $val['cur_num'];
                            $tmp_data['cost'] = $val['cost'];
                        }
                        else
                        {
                            foreach($last_detail as $d_val)
                            {
                                if($val['id'] == $d_val['a_c_id'])
                                {
                                    $tmp_data['last_num'] = $d_val['cur_num'];
                                    $tmp_data['cost'] = $d_val['cost'];
                                    break;
                                }
                            }
                        }
                        break;
                    case 3:
                        if($is_first == false)  //首次
                        {
                            $tmp_data['cost'] = $val['cost'];
                            $tmp_data['total_cost'] = $val['cost'];
                            $bill_info['total_price'] += $val['cost'];
                        }
                        break;
                }
                $cost_info[] = $tmp_data;
            }
        }
        
        //首次加上押金
        if($is_first == false && $agreement_info['rental_type'] != 9)
        {
            $cost_info[] = array(
                'a_c_id'    =>  0,
                'cost_unit_id'  =>  3,
                'cost_name_id'  =>  5,
                'cost_type_id'  =>  1,
                'cost'  =>  $agreement_info['foregift'],
                'total_cost'  =>  $agreement_info['foregift'],
                'last_num'      =>  0,
                'cur_num'      =>  0,
                'create_time'  =>  time(),
            );
            $bill_info['total_price'] += $agreement_info['foregift'];
        }
        
//        print_r($bill_info);
//        print_r($cost_info);exit;
        $b_id = M('bills')->add($bill_info);
        if(empty($b_id))
        {
            return false;
        }
        foreach($cost_info as &$val)
        {
            $val['b_id'] = $b_id;
        }
        
        if(!empty($cost_info))
        {
            M('bills_detail')->addAll($cost_info);
        }
        
        $where = array('b_id' => $b_id);
        $bill_info['id'] = $b_id;
        $bill_info['details'] = M('bills_detail')->where($where)->select();
        
        return $bill_info;
            
    }
    
    
    private function get_tenant_date($date = '', $month = 1)
    {
        if($month == 9)
        {
            $month = 1;
        }
        $days_num = array(
            '01' => 31, '02' => 28, '03' => 31, '04' => 30, '05' => 31, '06' => 30, '07' => 31, '08' => 31, '09' => 30, '10' => 31, '11' => 30, '12' => 31
        );
        //当前时间
        $cur_date = explode('-', date('Y-m-d', time()));
        
        //是否润年
        $is_leap = ($cur_date[0] % 4 ==0 || $cur_date[0] % 400 ==0) && ($cur_date[0] % 100 !=0);
        
        $return = array();
        
        if($is_leap)
        {
            $days_num['02'] = 29;
        }
        
        $tmp = explode('-', $date);
        
        if($tmp[2] > $days_num[$cur_date[1]])
        {
            $tmp[2] = $days_num[$cur_date[1]];
        }
        
        $return[] = $cur_date[0].'-'.$cur_date[1].'-'.$tmp[2];
        
        if($cur_date[1] + $month >= 13)
        {
            $cur_date[0] += 1;
        }
        
        $cur_date[1] = $cur_date[1] + $month > 9 ? $cur_date[1] + $month : '0'.($cur_date[1] + $month);
        if($cur_date[1] > 12)
        {
            $cur_date[1] -= 12;
            $cur_date[1] = $cur_date[1] > 9 ? $cur_date[1] : '0'.$cur_date[1];
        }
        $tmp[2] = ($tmp[2] - 1) < 10 ? '0'.($tmp[2] - 1) : ($tmp[2] - 1);
        
        $return[] = $cur_date[0].'-'.$cur_date[1].'-'.$tmp[2];
        
        return $return;

        //与当前时间月份相同
        if($tmp[1] == $cur_date[1])
        {
            $return[] = $date;
        }
        //次月，但未到次月账单
        else if($tmp[1] != $cur_date[1] && $tmp[2] < $cur_date[2])
        {
            $return[] = $date;
        }
        //次月，已到次月账单
        else if($tmp[1] != $cur_date[1] && $tmp[2] >= $cur_date[2])
        {
            //跨年
            if($tmp[1] + 1 > 12)
            {
                $tmp[0] += 1;
                $tmp[1] = 1;
            }
            else
            {
                $tmp[1] += 1;
            }
        }
        
        
    }


}