<?php
namespace Common\Model;

use Think\Model;

/**
 * 投诉举报
 * Class AppealModel
 * @package Common\Model
 */
class AppealModel extends Model
{
    protected $trueTableName = 'Appeals';
    public static $State = array(
        'Submitted' => 1,
        'Disabled' => 9
    );
    public static $StateCaption = array(
        '1' => '已提交',
        '9' => '已删除'
    );
    public static $HouseType = array(
        'NewHouse'=>1,
        'OldHouse' => 2,
        'RentalHouse' => 3
    );
    public function saveAppeal($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Appeal') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            $data['state'] = self::$State['Submitted'];
            return D('Appeal')->add($data);
        }
    }
}