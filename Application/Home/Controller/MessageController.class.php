<?php

namespace Home\Controller;

use Common\Model\MessageModel;

class MessageController extends UserController
{
    public function index()
    {
        $this -> assign('typeCaption', MessageModel::$TypeCaption);
        $this->display();
    }

    public function listItem() {
        $currentPage = I("currentPage");
        $pageSize = I("pageSize");

        $messageList = D('Message') -> queryList($this->userId, $currentPage*$pageSize, $pageSize);
        foreach ($messageList  as &$message) {
            $message['typeCaption'] = MessageModel::$TypeCaption[$message['type']];
            $message['createTimeCaption'] = date('Y-m-d h:i:s', $message['createTime']);
        }
        if ($messageList) {
            $this->assign('list', $messageList);
            $this->success($this->fetch(), "", true);
        } else {
            $this->error('没有更多数据！');
        }
    }

    public function iReadIt() {
        D('Message') -> iReadIt(I('id'));
        $data['status'] = 1;
        $data['data'] = array('id'=> I('id'));
        $this->ajaxReturn($data);
    }

    public function detail() {
//        D('Message') -> iReadIt(I('id'));
//        $message = D('Message') -> findById(I('id'));
//        $this -> assign('message', $message);
//        $this -> display();
    }

}