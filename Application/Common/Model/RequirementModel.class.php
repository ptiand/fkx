<?php
namespace Common\Model;

use Think\Model;

class RequirementModel extends Model
{
    protected $trueTableName = 'Requirements';

    public static $State = array(
        'Submitted' => 1,
        'Deleted' => 9
    );
    public static $HuXingCaption = array(
        '1'=>'一室',
        '2'=>'二室',
        '3'=>'三室',
        '4'=>'四室',
        '5'=>'五室及以上',
    );
    public static $ChaoXiangCaption = array(
        'S' => '南',
        'N' => '北',
        'E' => '东',
        'W' => '西',
        'ES' => '东南',
        'EN' => '东北',
        'WS' => '西南',
        'WN' => '西北',
        'SN' => '南北通透',
        'EW' => '东西通透',
    );
    public static $ZhuangXiuCaption = array(
        '1' => '毛坯',
        '2' => '简装',
        '3' => '精装修'
    );


    public function saveRequirement($data)
    {
        if(is_array($data['huxing']))
        {
            $data['huxing'] = json_encode($data['huxing'], true);
        }
        if(is_array($data['chaoxiang']))
        {
            $data['chaoxiang'] = json_encode($data['chaoxiang'], true);
        }
        if(is_array($data['zhuangxiu']))
        {
            $data['zhuangxiu'] = json_encode($data['zhuangxiu'], true);
        }
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Requirement') -> where($where) -> save($data);
        }
        else
        {
            $data['state'] = self::$State['Submitted'];
            $data['createTime'] = time();
            return D('Requirement')->add($data);
        }
    }
    public function deleteRequirement($id)
    {
        $where['id'] = $id;
        $data['state'] = self::$State['Deleted'];
        return D('Requirement') -> where($where) -> save($data);
    }
}