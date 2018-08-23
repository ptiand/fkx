<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhuyajie <xcoolcc@gmail.com>
// +----------------------------------------------------------------------

namespace Common\Model;
use Think\Log;
use Think\Model;
/**
 * 微信消息自动回复模型
 */
class WeixinMessageReplyModel extends Model{

    protected $trueTableName = 'WeixinMessageReplies';

    public static $MatchMode = array(
        'MatchAll' => '1',
        'MatchHalf' => '2'
    );
    public $match_mode = array(
        '1'  =>'全匹配',
        '2'  =>'半匹配'
    );
    public static $ReplyType = array(
        'Keyword' => '1',
        'Subscribe' => '2',
        'Default' =>  '3'
    );
    public static $State = array(
        'Active' => '1',
        'Deleted' => '999'
    );
    //回复类型
    public $reply_type = array(
        '1'  =>'关键字消息回复',
        '2'  =>'关注后自动回复',
        '3'  => '默认消息回复'
    );
    public function findWeixinMessageReplyById($id)
    {
        return $this->where(array('id'=>$id))->find();
    }
    public function saveWeixinMessageReply($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return $this -> where($where) -> save($data);
        }
        else
        {
            $data['state'] = self::$State['Active'];
            $data['createTime'] = time();
            return $this->add($data);
        }
    }
    public function getReplyByKeyword($keyword)
    {
        $where = array(
            'keyword'=>$keyword,
            'reply_type' => self::$ReplyType['Keyword'],
            'match_mode'=> self::$MatchMode['MatchAll'],
            'state' => self::$State['Active']
        );
        //先全匹配
        $res = $this->where($where)->find();
        if ($res) {
            return $res;
        }
        $where = array(
            'keyword'=> array('LIKE', "%".$keyword."%"),
            'reply_type' => self::$ReplyType['Keyword'],
            'match_mode'=>  self::$MatchMode['MatchHalf'],
            'state' => self::$State['Active']
        );
            //再半匹配
        $res = $this->where($where)->find();
        if ($res) {
            return $res;
        }
        return $this->getDefaultReply();

    }
    //获取关注回复信息
    public function getSubscribeReplyContent(){
        $where  = array(
            'reply_type'=> self::$ReplyType['Subscribe'],
            'state' => self::$State['Active']
        );
        return $this->where($where)->getField('replycontent');
    }
    public function getSubscribeReply()
    {
        $where  = array(
            'reply_type'=> self::$ReplyType['Subscribe'],
            'state' => self::$State['Active']
        );
        return $this->where($where)->find();
    }
    public function getDefaultReply()
    {
        $where  = array(
            'reply_type'=> self::$ReplyType['Default'],
            'state' => self::$State['Active']
        );
        return $this->where($where)->find();
    }
    public function deleteWeixinMessageReply($id)
    {
        $data = array(
            'state' => self::$State['Deleted'],
            'id' => $id
        );
        return $this->saveWeixinMessageReply($data);
    }
}
