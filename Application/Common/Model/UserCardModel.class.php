<?php
namespace Common\Model;

use Think\Model;

class UserCardModel extends Model
{
    protected $trueTableName = 'UserCards';

    public static $State = array(
        'UnUsed' => 1,
        'Used' => 2,
        'Deleted' => 9
    );
    public static $StateCaption = array(
        '1' => '已领取',
        '2' => '已使用',
        '9' => '已删除'
    );
    public function saveUserCard($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('UserCard') -> where($where) -> save($data);
        }
        else
        {
            $data['state'] = self::$State['UnUsed'];
            $data['createTime'] = time();
            return D('UserCard')->add($data);
        }
    }
    public function queryUserCard($userId, $cardId)
    {
        $where['userId'] = $userId;
        $where['cardId'] = $cardId;
        $where['state'] = self::$State['UnUsed'];
        return D('UserCard')->where($where)->find();
    }
}