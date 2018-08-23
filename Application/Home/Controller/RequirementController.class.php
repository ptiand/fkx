<?php

namespace Home\Controller;

use Common\Model\RequirementModel;

class RequirementController extends UserController
{
    public function index()
    {
        $this->display('requirement_list');
    }
    public function loadData()
    {
        $currentPage = I("currentPage", 1);
        $pageSize = I("pageSize", 10);
        $where['userId'] = $this->userId;
        $start = ($currentPage-1)*$pageSize;
        $where['state'] = array('NEQ', RequirementModel::$State['Deleted']);
        $requires = D('Requirement')->where($where)->order('id desc')->limit($start, $pageSize)->select();
        if($requires)
        {
            foreach ($requires as &$req)
            {
                $req['huxing'] = json_decode($req['huxing'], true);
                $req['chaoxiang'] = json_decode($req['chaoxiang'], true);
                $req['zhuangxiu'] = json_decode($req['zhuangxiu'], true);
            }
            $this->assign('requires', $requires);
            $this->assign('HuXingCaption', RequirementModel::$HuXingCaption);
            $this->assign('ChaoXiangCaption', RequirementModel::$ChaoXiangCaption);
            $this->assign('ZhuangXiuCaption', RequirementModel::$ZhuangXiuCaption);
            $this->success(array(
                'hasMore' => count($requires)>=$pageSize,
                'html' => $this->fetch('requirement_listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }
    }
    public function custom()
    {
        if(!IS_POST)
        {
            $user = session("user");
            $phone = $user['phone'];
            $this->assign('phone', $phone);
            $this->assign('HuXingCaption', RequirementModel::$HuXingCaption);
            $this->assign('ChaoXiangCaption', RequirementModel::$ChaoXiangCaption);
            $this->assign('ZhuangXiuCaption', RequirementModel::$ZhuangXiuCaption);
            $this->display();
            return;
        }
        $data = I('post.');
        $data['userId'] = $this->userId;
        $result = D('Requirement')->saveRequirement($data);
        if($result)
        {
            $this->success('保存成功！');
        }
        else
        {
            $this->error('保存失败！');
        }
    }
    public function delete()
    {
        $id = I('id');
        $result = D('Requirement')->deleteRequirement($id);
        if($result)
        {
            $this->success('删除成功！');
        }
        else
        {
            $this->error('删除失败！');
        }
    }
}