<?php

namespace Home\Controller;

use Common\Model\AppealModel;

class AppealController extends UserController
{
    public function submit()
    {
        if(!IS_POST)
            return;
        $houseId = I('houseId');
        $houseType = I('houseType');
        $content = I('content');
        if(!$content)
        {
            $this->error('请输入举报内容！');
        }
        $houseType = AppealModel::$HouseType[$houseType];
        $data = array(
            'houseId' => $houseId,
            'houseType' => $houseType,
            'content' => $content,
            'userId' => $this->userId
        );
        $result = D('Appeal')->saveAppeal($data);
        if($result)
        {
            $this->success('您的举报已提交，感谢您的反馈！');
        }
        else
        {
            $this->error('提交失败！');
        }
    }
}