<?php
namespace Common\Model;

use Think\Model;

class IMGroupModel extends Model
{
    protected $trueTableName = 'IMGroups';
    public static $State = array(
        'Normal' => 1,
        'Deleted' => 9
    );
    public function saveIMGroup($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('IMGroup') -> where($where) -> save($data);
        }
        else
        {
            if(!$data['state'])
            {
                $data['state'] = self::$State['Normal'];
            }
            $data['createTime'] = time();
            return D('IMGroup')->add($data);
        }
    }
    public function queryUserGroup($askId,$houseUserId)
    {
        $where = array(
            'state' => self::$State['Normal']
        );
        $userWhere = array(array(
            'askerId' => $askId,
            'houseUserId' => $houseUserId
        ),
        array(
            'askerId' => $houseUserId,
            'houseUserId' => $askId
        ));
        $userWhere['_logic'] = 'OR';
        $where[] = $userWhere;
        $group = $this->where($where)->find();
        return $group;
    }
    public function queryGroupByGroupId($groupId)
    {
        return D('IMGroup')->where(array('groupId' => $groupId))->find();
    }

}