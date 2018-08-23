<?php
namespace Common\Model;

use Think\Model;

class ReportModel extends Model
{
    protected $trueTableName = 'report';
    public static $HouseType = array(
        'Loupan' => 0,
        'NewHouse'=>1,
        'OldHouse' => 2,
        'RentalHouse' => 3
    );
    public static $Status = array(
        'FollowUp' => 1,
        'Done'  => 2,
        'Failed' => 3
    );
    public static $StatusCaption = array(
        '1' => '跟进中',
        '2' => '成交',
        '3' => '失败'
    );
    //1跟进，2成交，3失效
    public static $HuxingCaption = array(
        '1'=>'一房',
        '2'=>'二房',
        '3'=>'三房',
        '4'=>'四房',
        '5'=>'五房'
    );
    //1一房，2二房，3三方，4四房，5房

    //1投资，2自住，3投资兼自住
    public static $ZhiYeCaption = array(
        '1'=>'投资',
        '2'=>'自住',
        '3'=>'投资兼自住'
    );
    public static $SexCaption = array(
        '0'=>'无',
        '1'=>'女',
        '2'=>'男'
    );
    public function findById($id)
    {
        $where['id'] = $id;
        $house = D('Report') -> where($where) -> find();
        return $house;
    }
    public function saveReport($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Report') -> where($where) -> save($data);
        }
        else
        {
            $data['created_at'] = time();
            return D('Report')->add($data);
        }
    }
}