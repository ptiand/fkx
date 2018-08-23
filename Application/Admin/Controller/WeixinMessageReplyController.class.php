<?php


namespace Admin\Controller;
use Common\Model\WeixinMessageReplyModel;
use Common\Service\WeixinService;
/**
*微信消息回复
**/
class WeixinMessageReplyController extends AdminController
{

    public function index()
    {
        $this->display();
    }

    public function loadReplyList(){
        $offset = i("offset");
        $limit = i("limit");

        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort."".$order;
        }else{
            $reorder = 'id DESC';
        }
        $where['state'] = WeixinMessageReplyModel::$State['Active'];
        $list =  D('WeixinMessageReply')->where($where)->order($reorder)->limit($offset,$limit)->select();
        $reply_type = D('WeixinMessageReply')->reply_type;
        $match_mode = D('WeixinMessageReply')->match_mode;
        foreach ($list as $k => &$v) {
            $v['reply_type'] = isset($reply_type[$v['reply_type']]) ? $reply_type[$v['reply_type']] : '';
            $v['match_mode'] = isset($match_mode[$v['match_mode']]) ? $match_mode[$v['match_mode']] : '';
        }
        //dump($list);
        $count =  D('WeixinMessageReply')->where($where)->count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    public function add()
    {
        $id = I('request.id');
        $info = D('WeixinMessageReply')->findWeixinMessageReplyById($id);
        $reply_type_arr = D('WeixinMessageReply')->reply_type;
        if(IS_POST)
        {   
            $msg_type = empty($id) ? '添加' : '修改';
            $reply_type = I('post.reply_type');
            $data = array();
            if($id)
            {
                $data['id'] = $id;
            }
            if ($reply_type == WeixinMessageReplyModel::$ReplyType['Keyword']) {//关键字消息回复
                $data = I('post.');
                unset($data['replycontent_long']);
                if (empty($data['keyword'])) {
                    $this->error('关键字不能为空');
                }
                if (empty($data['replycontent'])) {
                    $this->error('回复内容不能为空');
                }
            }
            else if($reply_type == WeixinMessageReplyModel::$ReplyType['Subscribe'])
            {
                $data['reply_type'] = $reply_type;
                $data['replycontent'] = I('post.replycontent_long');
                if (empty($data['replycontent'])) {
                    $this->error('回复内容不能为空');
                }

                $res = D('WeixinMessageReply')->getSubscribeReply();
                if ((!$id && !empty($res))|| $res['id'] != $id) {
                    $this->error($reply_type_arr[$data['reply_type']].'只能设置一条回复信息');
                }
            }
            else if($reply_type == WeixinMessageReplyModel::$ReplyType['Default'])
            {
                $data['reply_type'] = $reply_type;
                $data['replycontent'] = I('post.replycontent_long');
                if (empty($data['replycontent'])) {
                    $this->error('回复内容不能为空');
                }

                $res = D('WeixinMessageReply')->getDefaultReply();
                if ((!$id && !empty($res))|| $res['id'] != $id) {
                    $this->error($reply_type_arr[$data['reply_type']].'只能设置一条回复信息');
                }
            }
            $savedId = D('WeixinMessageReply')->saveWeixinMessageReply($data);
            if($savedId !== false)
            {
                $this->success($msg_type.'成功！');
            }
            else
            {
                $this->error($msg_type.'失败！');
            }
        }
        else
        {
            if ($info['reply_type'] != WeixinMessageReplyModel::$ReplyType['Keyword']) {//非关键字回复
                $info['replycontent_long'] = $info['replycontent'];
            }
            $this->assign('match_mode', D('WeixinMessageReply')->match_mode);
            $this->assign('reply_type', $reply_type_arr);
            $this->assign('info',$info);
            $this->display('add');
        }
    }
    public function delete()
    {
        $ids = i('ids');
        if($ids)
        {
            foreach ($ids as $id)
            {
                D('WeixinMessageReply')->deleteWeixinMessageReply($id);
            }
            $this->success('删除成功！');
        }
        else
        {
            $this->error('删除失败！');
        }
    }
}