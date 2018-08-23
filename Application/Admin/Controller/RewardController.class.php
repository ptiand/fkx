<?php

namespace Admin\Controller;

use Common\Model\RewardModel;

class RewardController extends AdminController
{
    public function index()
    {
        $this->assign('State', RewardModel::$State);
        $this -> display();
    }
    public function loadRewardList()
    {
        $condition = array();
        $offset = i("offset");
        $limit = i("limit");
        $search = i('search');
        if($search)
        {
            $condition['U.`user_name`'] = array('LIKE',"%$search%");
            $condition['U.`phone`'] = array('LIKE',"%$search%");
            $condition['R.`username`'] = array('LIKE',"%$search%");
            $condition['R.`phone`'] = array('LIKE',"%$search%");
            $condition['_logic'] = 'OR';
        }
        $rewards = D('Reward')
            ->field('R.*, U.`user_name` as userName, U.`phone` AS userPhone ')
            ->alias('R')->join('LEFT JOIN `users` AS U ON R.`userId` = U.`id` ')
            ->where($condition)
            ->order('R.`id` DESC')
            ->limit($offset, $limit)
            ->select();
        foreach ($rewards as &$reward)
        {
            $reward['stateCaption'] = RewardModel::$StateCaption[$reward['state']];
            $reward['receiveTime'] = $reward['receiveTime']>0?date('Y-m-d H:i:s',$reward['receiveTime']):'-';
        }
        $count =  D('Reward')
            ->field('R.*, U.`user_name` as userName, U.`phone` AS userPhone ')
            ->alias('R')->join('LEFT JOIN `users` AS U ON R.`userId` = U.`id` ')
            ->where($condition)
            -> count();
        $resArr = array("total"=>$count,"rows"=>$rewards?$rewards:array());
        echo json_encode($resArr);
    }
    public function give()
    {
        if(!IS_POST)
        {
            return;
        }
        $id = I('id');
        $reward = D('Reward')->findById($id);
        if(!$reward)
        {
            $this->error('获取奖品信息失败');
            return;
        }
        $reward['state'] = RewardModel::$State['Received'];
        $reward['receiveTime'] = time();
        $result = D('Reward')->saveReward($reward);
        if($result)
        {
            $this->success('发放成功！');
        }
        else
        {
            $this->error('发放失败！');
        }
    }

}