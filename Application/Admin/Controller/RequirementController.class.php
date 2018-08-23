<?php

namespace Admin\Controller;

use Common\Model\RequirementModel;

class RequirementController extends AdminController
{
    public function index()
    {
        $this->display();
    }
    public function loadRequirementList()
    {
        $offset = i("offset");
        $limit = i("limit");
        $search = i('search');
        if($search)
        {
            $searchCondition['U.`user_name`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['U.`phone`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['R.`username`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['R.`phone`'] = array('LIKE',"%$search%",'OR');
            $searchCondition['_logic'] = 'OR';
            $condition[] = $searchCondition;
        }
        $condition['state'] = array('NEQ', RequirementModel::$State['Deleted']);
        $requires = D('Requirement')
            ->field('R.*, U.`user_name` as userName, U.`phone` AS userPhone ')
            ->alias('R')->join('LEFT JOIN `users` AS U ON R.`userId` = U.`id` ')
            ->where($condition)
            ->order('R.`id` DESC')
            ->limit($offset, $limit)
            ->select();
        foreach ($requires as &$r)
        {
            $r['createTime'] = $r['createTime']>0?date('Y-m-d H:i:s',$r['createTime']):'-';
            $huxingCaption = array();
            if($r['huxing'])
            {
                $hxArr = json_decode($r['huxing'], true);
                foreach ($hxArr as $hx)
                {
                    $huxingCaption[] = RequirementModel::$HuXingCaption[$hx];
                }
            }
            $r['huxingCaption'] = join('、', $huxingCaption);
            $chaoxiangCaption = array();
            if($r['chaoxiang'])
            {
                $cxArr = json_decode($r['chaoxiang'], true);
                foreach ($cxArr as $cx)
                {
                    $chaoxiangCaption[] = RequirementModel::$ChaoXiangCaption[$cx];
                }
            }
            $r['chaoxiangCaption'] = join('、', $chaoxiangCaption);
            $zhuangxiuCaption = array();
            if($r['zhuangxiu'])
            {
                $zxArr = json_decode($r['zhuangxiu'], true);
                foreach ($zxArr as $zx)
                {
                    $zhuangxiuCaption[] = RequirementModel::$ZhuangXiuCaption[$zx];
                }
            }
            $r['zhuangxiuCaption'] = join('、', $zhuangxiuCaption);
        }
        $count = D('Requirement')
            ->field('R.*, U.`user_name` as userName, U.`phone` AS userPhone ')
            ->alias('R')->join('LEFT JOIN `users` AS U ON R.`userId` = U.`id` ')
            ->where($condition)
            -> count();
        $resArr = array("total"=>$count,"rows"=>$requires?$requires:array());
        echo json_encode($resArr);
    }
}