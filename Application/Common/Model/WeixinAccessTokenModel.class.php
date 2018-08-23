<?php
namespace Common\Model;

use Think\Model;

class WeixinAccessTokenModel extends Model
{
    protected $trueTableName = 'WeixinAccessTokens';

    public function findValidAccessToken()
    {
        $data = $this->order('id DESC ')->find();
        if($data && ($data['createTime'] + $data['expiresIn'] - 60) > time())
        {
            return $data;
        }
        else
        {
            return null;
        }
    }

    public function saveWeixinAccessToken($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('WeixinAccessToken') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            return D('WeixinAccessToken')->add($data);
        }
    }
}