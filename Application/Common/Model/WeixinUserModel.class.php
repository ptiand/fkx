<?php
namespace Common\Model;

use Think\Model;

class WeixinUserModel extends Model
{
    public static $Subscribe = array(
        'Yes' => '1',
        'No' => '0'
    );
    public static $SubscribeCaption = array(
        '1' => '已关注',
        '0' => '已取消'
    );
    protected $trueTableName = 'WeixinUsers';
    public function saveWeixinUser($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('WeixinUser') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            return D('WeixinUser')->add($data);
        }
    }
    public function findWeixinUserByOpenid($openid)
    {
        return $this->where(array('openid'=>$openid))->find();
    }
}