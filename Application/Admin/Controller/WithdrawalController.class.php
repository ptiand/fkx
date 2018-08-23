<?php

namespace Admin\Controller;

use Common\Model\WithdrawalModel;
use Common\Model\UserAccountModel;
use Think\Controller;
use Think\Exception;

class WithdrawalController extends AdminController
{
    public function index()
    {
        $this->assign('StatusCaption', WithdrawalModel::$StatusCaption);
        $this->display();
    }
    public function loadWithdrawal()
    {
        $offset = i("offset");
        $limit = i("limit");
        $search_value = i('search');
        $type = i('status');
        $beginTime = i('beginTime')?strtotime(i('beginTime')):'';
        $endTime = i('endTime')?strtotime(i('endTime')):'';
        $where = array();
        if($beginTime && $endTime){
            $where['W.time'] = array('between',array($beginTime,$endTime));
        }
        else
        {
            if($beginTime)
            {
                $where['W.time'] = array('GT',$beginTime);
            }
            if($endTime)
            {
                $where['W.time'] = array('LT',$endTime);
            }
        }
        if($search_value){
            $where['U.user_name'] = array('LIKE',"%$search_value%");;
        }
        if(!empty($type) && isset($type)){
            $where['W.status'] = $type;
        }
        $list = D('Withdrawal')-> alias('W')
            ->join('LEFT JOIN users as U on U.id = W.user_id')
            -> field('W.*,U.user_name AS userName, U.phone AS userPhone')
            -> where($where)
            -> order(' W.id DESC ')
            ->limit($offset,$limit)
            -> select();
        foreach($list as &$v){

            $v['time'] = date('Y-m-d H:i:s',$v['time']);
            $v['statusCaption'] = WithdrawalModel::$StatusCaption[$v['status']];
        }
        $count =  M('withdrawals')-> alias('W')
            ->join('LEFT JOIN users as U on U.id = W.user_id')
            -> where($where)
            -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    public function pay()
    {
        $id = I('id');
        if(!IS_POST)
        {
            $withdrawal = D('Withdrawal')->findById($id);
            $this->assign('Type', WithdrawalModel::$Type);
            $this->assign('TypeCaption', WithdrawalModel::$TypeCaption);
            $this->assign('Status', WithdrawalModel::$Status);
            $this->assign('StatusCaption', WithdrawalModel::$StatusCaption);
            $this->assign('withdrawal',$withdrawal);
            $this->display();
        }
        else
        {
            try
            {
                $payResult = D('Withdrawal')->pay($id);
                if($payResult)
                {
                    $this->success('打款成功！');
                }
                else
                {
                    $this->error('打款失败！');
                }
            }
            catch(Exception $e)
            {
                if($e->getCode() > 0)
                {
                    $this->error($e->getMessage());
                    return;
                }
                $this->error('打款失败！');
            }
        }
    }
    
    public function bills_list()
    {
        $this->display();
    }
    
    public function load_bills()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $limit = i("limit") ? i("limit") : 20;
        
        
        $offset = ($cur_page - 1) * $limit;
        
        $list = M('bills')->order(' id DESC ')->limit($offset,$limit)->select();
        $count =  M('bills')->count();
        
        $ids = array(0);
        foreach($list as $val)
        {
            if(!in_array($val['sell_user_id'], $ids))
            {
                $ids[] = $val['sell_user_id'];
            }
            if(!in_array($val['buy_user_id'], $ids))
            {
                $ids[] = $val['buy_user_id'];
            }
        }
        
        $user_name = M('users')->field('id,user_name')->where(array('id' => array('in', $ids)))->select();
        $tmp = array();
        foreach($user_name as $val)
        {
            $tmp[$val['id']] = $val['user_name'];
        }
        $user_name = $tmp;
        
        foreach($list as &$val)
        {
            $val['sell_user_name'] = $user_name[$val['sell_user_id']];
            $val['buy_user_name'] = $user_name[$val['buy_user_id']];
        }
        
        //print_r($user_name);exit;
        
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    
    public function create_withdrawal()
    {
        //余额大于100的房东
        $where = array(
            'user_type' =>  1,
            'balance'   =>  array('gt', 100)
        );
        $user_count = M('users')->where($where)->count();
        
        if($user_count == 0)
        {
            $this->error('尚无需要生成的房东提款！');
        }
        
        $limit = 20;
        $all_times = ceil($user_count / $limit);
        $result_success = 0;
        $result_error = 0;
        
        for($i = 1; $i <= $all_times; $i++)
        {
            $offset = ($i - 1) *  $size;
            $list = M('users')->field('id, balance')->where($where)->limit($offset,$limit)->select();
            //print_r($list);exit;
            $shops_ids = array();
            foreach($list as $key => $val)
            {
                $shops_ids[] = $val['id'];
            }
            
            $shops_list = D('Shops')->where(array('user_id' => array('in', $shops_ids)))->select();
            $tmp_shop = array();
            foreach($shops_list as $key => $val)
            {
                $tmp_shop[$val['user_id']] = $val;
            }
            $shops_list = $tmp_shop;
            
            foreach($list as $key => $val)
            {
                $data = array();
                if(!empty($shops_list[$val['id']]['bank_card']))
                {
                    $data = array(
                        'address'   =>  $shops_list[$val['id']]['bank_name'].'-'.$shops_list[$val['id']]['bank_address'],
                        'amount'    =>  $val['balance'],
                        'cards'     =>  $shops_list[$val['id']]['bank_card'],
                        'name'      =>  $shops_list[$val['id']]['user_name'],
                        'phone'     =>  $shops_list[$val['id']]['user_phone'],
                        'type'      =>  2,
                    );
                }
                else if(!empty($shops_list[$val['id']]['zhifubao_no']))
                {
                    $data = array(
                        'address'   =>  '',
                        'amount'    =>  $val['balance'],
                        'cards'     =>  $shops_list[$val['id']]['zhifubao_no'],
                        'name'      =>  $shops_list[$val['id']]['user_name'],
                        'phone'     =>  $shops_list[$val['id']]['user_phone'],
                        'type'      =>  1,
                    );
                }
                else
                {
                    $result_error += 1;
                    continue;
                }
                //print_r($shops_list[$val['id']]);exit;
                $result = D('User')->applyWithdrawal($val['id'], $data);
                if($result)
                {
                    $result_success += 1;
                }
                else
                {
                    $result_error += 1;
                }
            }
            //print_r($shops_list);exit;
        }
        
        $list_array= array("success"=>$result_success,"error"=>$result_error, "total_num" => $user_count);
        echo json_encode($list_array);
    }
}