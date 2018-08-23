<?php
namespace Common\Model;

use Think\Model;

class RewardModel extends Model
{
    protected $trueTableName = 'Rewards';
    public static $State = array(
        'UnReceived' => 1,
        'Received' => 2
    );
    public static $StateCaption = array(
        '1' => '未领取',
        '2' => '已领取'
    );
    public function findById($id)
    {
        return D('Reward')->where(array('id'=>$id))->find();
    }
    public function saveReward($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Reward') -> where($where) -> save($data);
        }
        else
        {
            if(!$data['state'])
            {
                $data['state'] = self::$State['UnReceived'];
            }
            $data['createTime'] = time();
            return D('Reward')->add($data);
        }
    }
}