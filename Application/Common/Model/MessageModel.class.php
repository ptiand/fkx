<?php
namespace Common\Model;

use Think\Model;

class MessageModel extends Model {
    protected $trueTableName = 'Messages';

    public static $Types = array(
        'System' => '1',
        'User' => '2'
    );

    public static $States = array(
        'Normal' => '1',
        'Deleted' => '9',
    );

    public static $TypeCaption = array(
        '1' => '系统消息',
        '2' => '用户消息',
    );

    public function queryList($receiverId, $start=0, $pageSize=10, $type=null) {
        $where['state'] = array('NEQ', self::$States['Deleted']);
        $where['receiverId'] = $receiverId;
        if ($type) {
            $where['type'] = $type;
        }
        return $this -> where($where) -> limit($start, $pageSize) -> order('`createTime` DESC') -> select();
    }

    public function findById($id)
    {
        $where['id'] = $id;
        return $this -> where($where) -> find();
    }

    public function iReadIt($id) {
        $where['id'] = $id;
        $where['readTime'] = array('EQ', 0);
        $data['readTime'] = time();
        return $this -> where($where) -> save($data);
    }

    public function createSystemMessage($receiverId, $content) {
        $data = array(
            'type' => self::$Types['System'],
            'senderId' => 0,
            'receiverId' => $receiverId,
            'content' => $content,
            'createTime' => time(),
            'state' => self::$States['Normal']
        );
        return $this -> add($data);
    }

    public function createMessage($receiverId, $content, $type, $senderId) {
        $data = array(
            'type' => $type,
            'senderId' => $senderId,
            'receiverId' => $receiverId,
            'content' => $content,
            'createTime' => time(),
            'state' => self::$States['Normal']
        );
        return $this -> add($data);
    }
}