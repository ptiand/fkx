<?php

namespace Admin\Controller;

use Common\Model\AppealModel;

class AppealController extends AdminController
{
    public function index()
    {
        $this->display();
    }
    public function loadAppealList()
    {
        $offset = i("offset");
        $limit = i("limit");
        $search = i('search');
        if($search)
        {
            $searchCondition['U.`user_name`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['U.`phone`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['A.`content`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['_logic'] = 'OR';
            $condition[] = $searchCondition;
        }
        $condition['state'] = array('NEQ', AppealModel::$State['Disabled']);
        $appeals = D('Appeal')
            ->field('A.*, U.`user_name` as userName, U.`phone` AS userPhone ')
            ->alias('A')->join('LEFT JOIN `users` AS U ON A.`userId` = U.`id` ')
            ->where($condition)
            ->order('A.`id` DESC')
            ->limit($offset, $limit)
            ->select();
        foreach ($appeals as &$a)
        {
            $a['createTime'] = $a['createTime']>0?date('Y-m-d H:i:s',$a['createTime']):'-';
            if($a['houseType'] == AppealModel::$HouseType['NewHouse'])
            {
                $house = D('NewHouse')->findById($a['houseId']);
                $a['houseName'] = $house['name'];
            }
            else if($a['houseType'] == AppealModel::$HouseType['OldHouse'])
            {
                $house = D('OldHouse')->findById($a['houseId']);
                $a['houseName'] = $house['name'];
            }
            else if($a['houseType'] == AppealModel::$HouseType['RentalHouse'])
            {
                $house = D('RentalHouse')->findById($a['houseId']);
                $a['houseName'] = $house['name'];
            }
        }
        $count = D('Appeal')
            ->field('A.*, U.`user_name` as userName, U.`phone` AS userPhone ')
            ->alias('A')->join('LEFT JOIN `users` AS U ON A.`userId` = U.`id` ')
            ->where($condition)
            -> count();
        $resArr = array("total"=>$count,"rows"=>$appeals?$appeals:array());
        echo json_encode($resArr);
    }
}