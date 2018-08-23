<?php
namespace Admin\Controller;
use Think\Controller;
use Library\TencentIMSdk\IMSdk;

class ImController extends AdminController
{
    public function index()
    {
        $userId = 'admin';
        $identifier = $userId;
        $sig = $this -> genImSign($identifier);

        $this -> assign('identifier', $identifier);
        $this -> assign('sig', $sig);
        $this -> assign('sdkAppID', IMSdkAppId);
        $this -> assign('accountType', IMAccountType);

        $this->display();
    }

    public function listItem() {
        $this -> display();
    }

    public function chat()
    {
//        $houseId = I('houseId');
//        $houseType = I('houseType');
        $groupId = I('groupId');
        $groupName = '';
//        $chatUserId = null;
//        if(!$houseId && !$houseType && !$groupId)
//        {
//            echo '参数错误';
//            return;
//        }
        if($groupId)
        {
            $group = D('IMGroup')->queryGroupByGroupId($groupId);
            $groupId = $group['groupId'];
            $groupName = $group['groupName'];
//            $chatUserId = $group['houseUserId'] > 0 ? $group['houseUserId'] : 0;
        }
//        else if($houseId && $houseType)
//        {
//            //echo '跟房主聊天';
//            switch ($houseType)
//            {
//                case 'OldHouse':
//                    $house = D('OldHouse')->findById($houseId);
//                    if($house)
//                    {
//                        $chatUserId = $house['userId']?$house['userId'] : 0;
//                    }
//                    break;
//                case 'RentalHouse':
//                    $house = D('RentalHouse')->findById($houseId);
//                    if($house)
//                    {
//                        $chatUserId = $house['userId']?$house['userId'] : 0;
//                    }
//                    break;
//            }
//            if($chatUserId !== null)
//            {
//                $group = D('IMGroup')->queryUserGroup($this->userId, $chatUserId);
//                if($group)
//                {
//                    $groupId = $group['groupId'];
//                    $groupName = $group['groupName'];
//                }
//                else
//                {
//                    $askUser = D('User')->findById($this->userId);
//                    $houseUser = null;
//                    if($chatUserId > 0)
//                    {
//                        $houseUser = D('User')->findById($chatUserId);
//                    }
//                    $groupName = IMSdk::createGroupName($askUser['user_name'], $houseUser?$houseUser['user_name']:'房客行服务');
//                }
//            }
//            else
//            {
//                echo '参数错误';
//                return;
//            }
//
//        }
//        $userId = $this -> userId;
        $identifier = 'admin';
//        $identifier2 = $this -> genImIdentifier($chatUserId);
        $sig = $this -> genImSign($identifier);

        $userNameMap = array();
        if ($group['askerId']) {
            $user1 = D('User') -> findById($group['askerId']);
            $userNameMap[$this->genImIdentifier($group['askerId'])] =  $user1['user_name'];
        }
        if ($group['houseUserId']) {
            $user2 = D('User') -> findById($group['houseUserId']);
            $userNameMap[$this->genImIdentifier($group['houseUserId'])] =  $user2['user_name'];
        }
        $this -> assign("userNameMap", $userNameMap);

        $this->assign('groupId', $groupId);
        $this->assign('groupName', $groupName);
        $this -> assign('identifier', $identifier);
        $this -> assign('sig', $sig);
//        $this -> assign('chatIdentifier', $identifier2);
        $this -> assign('sdkAppID', IMSdkAppId);
        $this -> assign('accountType', IMAccountType);
//        $this -> assign("chatUserId", $chatUserId);
        $this -> assign("userId", $this -> userId);
//        $this->assign('houseId', $houseId);
//        $this->assign('houseType', $houseType);
        $this->display();
    }

    private function genImSign($identifier) {
        $api = IMSdk::createAndInitRestAPI();
        $sig = $api->generate_user_sig($identifier, '86400', IMPrivateKeyPath, IMSignature);
        return $sig[0];
    }

    private function genImIdentifier($id) {
        return IMSdk::createIdentifier($id);
    }
//    public function createGroup()
//    {
//        if(!IS_POST)
//        {
//            return;
//        }
//        $askerId = $this -> userId;
//        $houseUserId = I('chatUserId');
//
//        $resData = array('status' => '1', 'content' => array(), 'errMsg'=>'' );
//        //var_dump($askerId);
//        //var_dump($houseUserId);
//        $askUser = D('User')->findById($askerId);
//        $houseUser = null;
//        if($houseUserId > 0)
//        {
//            $houseUser = D('User')->findById($houseUserId);
//            if(!$houseUser)
//            {
//                $resData['status'] = 0;
//                $resData['errMsg'] = '参数错误！';
//                return $this -> ajaxReturn($resData);
//            }
//        }
//        if(!$askUser)
//        {
//            // return $this->error('参数错误！');
//            $resData['status'] = 0;
//            $resData['errMsg'] = '参数错误！';
//            return $this -> ajaxReturn($resData);
//        }
//        $group = D('IMGroup')->queryUserGroup($askerId, $houseUserId);
//        // var_dump($group);
//        if($group)
//        {
//            $groupInfo = array(
//                'id' => $group['id'],
//                'groupId' => $group['groupId'],
//                'groupName' => $group['groupName'],
//            );
//            //var_dump($groupInfo);
//            $resData['content'] = $groupInfo;
//            return $this -> ajaxReturn($resData);
//        }
//        $userSig = 'eJxlj1FLwzAYRd-7K0peJy5tlmUTfNhKK9OqdHWDPZW4pPrZJS1JtA7xv6t1YMD7es7lcj*CMAzRQ16e8-2*fdWucsdOovAiRBid-cGuA1FxVxEj-kH53oGRFa*dNAOMKKUxxr4DQmoHNZwMLhRoD1vRVMPGb3-yXZ6TGWa*Ak8DvE03ySq7Ge9GBSE6xTJbH8ePy5JZdciomLf15jpd9m5r1rRc4dEC0oUFvlNYuOYqmeTPhdRZ3x4oY23R0yjF9842L3dblaukuPQmHSh5OhRPp4QxMvPomzQWWj0IMY5oFBP8ExR8Bl*mDFxl';
//        $identifier = 'admin';
//        $api = IMSdk::createAndInitRestAPI($identifier);
//        $ret = $api->set_user_sig($userSig);
//        if($ret == false)
//        {
//            $resData['errMsg'] = '微聊功能正在维护，您可进行电话沟通或联系房客行进行反馈！';
//            return $this -> ajaxReturn($resData);
//        }
//        $info_set = array(
//            'group_id' => null,
//            'introduction' => null,
//            'notification' => null,
//            'face_url' => null,
//            'max_member_num' => 500,
//        );
//        //$importResult = $api->account_import('fangkexing', '房客行', '');
//        //var_dump($importResult);
//        $memList = array();
//        $memList[] = array('Member_Account' => IMSdk::createIdentifier($askerId));
//        if($houseUserId>=0)
//        {
//            $memList[] = array('Member_Account' => IMSdk::createIdentifier($houseUserId));
//        }
//        $groupName = IMSdk::createGroupName($askUser['user_name'], $houseUser?$houseUser['user_name']:'房客行服务');
//        $ret = $api->group_create_group2('Public', '房客行', $identifier, $info_set, $memList);
//        //var_dump($ret);
//        if($ret['ErrorCode'] != 0)
//        {
//            $resData['status'] = 0;
//            $resData['errMsg'] = '微聊功能正在维护，您可进行电话沟通或联系房客行进行反馈！';
//            return $this -> ajaxReturn($resData);
//        }
//        //创建群聊成功
//        $groupId = $ret['GroupId'];
//        $groupData = array(
//            'groupId' => $groupId,
//            'groupName' => $groupName,
//            'askerId' => $askerId,
//            'houseUserId' => $houseUserId,
//            'creator' => $identifier
//        );
//        $id = D('IMGroup')->saveIMGroup($groupData);
//        $groupInfo = array(
//            'id' => $id,
//            'groupId' => $groupId,
//            'groupName' => $groupName,
//        );
//        //var_dump($groupInfo);
//        $resData['content'] = $groupInfo;
//        return $this -> ajaxReturn($resData);
//    }

    public function getGroupName() {
        $resData = array('status'=>'1', 'errMsg' => '');
        if (!IS_POST) {
            $resData['status'] = '0';
            $resData['errMsg'] = '非法请求';
            return $this -> ajaxReturn($resData);
        }
        $groupIdList = I('groupIdList');
        $groupNames = null;
        foreach ($groupIdList as $id) {
            $group = D('IMGroup') -> queryGroupByGroupId($id);
            $groupNames[$id] = $group['groupName'];
        }
        $resData['content']['groupNames'] = $groupNames;
        return $this -> ajaxReturn($resData);
    }
}