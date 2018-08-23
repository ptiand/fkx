<?php
namespace Common\Model;

use Think\Model;

class InfoModel extends Model
{
    protected $trueTableName = 'infos';
    public static $HouseType = array(
        'Loupan' => 0,
        'NewHouse'=>1,
        'OldHouse' => 2,
        'RentalHouse' => 3
    );
    public static $Status = array(
        'Submitted' => 0,
        'Done'  => 1,
        'Expired' => 2
    );
    public static $StatusCaption = array(
        '0' => '进行中',
        '1' => '已完成',
        '2' => '已失效',
    );
    public function saveInfo($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Info') -> where($where) -> save($data);
        }
        else
        {
            $data['created_at'] = time();
            $data['status']  = self::$Status['Submitted'];
            return D('Info')->add($data);
        }
    }
}