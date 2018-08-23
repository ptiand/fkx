<?php
namespace Home\Controller;
use Think\Controller;
use Library\TencentIMSdk\IMSdk;
use Think\Exception;

class ImController extends UserController
{
    public function _initialize(){
        parent::_initialize();
        session('NavBar','Im');
    }
    public function index()
    {
        $userId = $this -> userId;
        $identifier = $this -> genImIdentifier($userId);
        $sig = $this -> genImSign($identifier);

        $this -> assign('identifier', $identifier);
        $this -> assign('sig', $sig);
        $this -> assign('sdkAppID', IMSdkAppId);
        $this -> assign('accountType', IMAccountType);

        $this->display();
    }

    public function getLoginInfo() {
        $resData = array('status'=>'1', 'errMsg'=>'');
        $userId = $this -> userId;
        if (!$userId || !IS_POST) {
            $resData['status'] = '0';
            $resData['errMsg'] = '未登录';
            return $this -> ajaxReturn($resData);
        }
        $identifier = $this -> genImIdentifier($userId);
        $sig = $this -> genImSign($identifier);
        $loginInfo = array(
            'identifier'=>$identifier,
            'sig'=>$sig,
            'sdkAppID'=>IMSdkAppId,
            'accountType'=>IMAccountType
        );
        $resData['content']['loginInfo'] = $loginInfo;
        return $this->ajaxReturn($resData);
    }

    public function listItem() {
        $this -> display();
    }

    private function getUserIdFromHouse($houseId, $houseType) {
        switch ($houseType)
        {
            case 'OldHouse':
                $house = D('OldHouse')->findById($houseId);
                if($house)
                {
                    return $house['userId']?$house['userId'] : 0;
                }
                break;
            case 'RentalHouse':
                $house = D('RentalHouse')->findById($houseId);
                if($house)
                {
                    return $house['userId']?$house['userId'] : 0;
                }
                break;
            case 'NewHouse':
                $house = D('NewHouse')->findById($houseId);
                if($house)
                {
                    return $house['userId']?$house['userId'] : 0;
                }
                break;
        }
    }

    public function chat()
    {
        $houseId = I('houseId');
        $houseType = I('houseType');
        $groupId = I('groupId');
        $groupName = '';
        $chatUserId = null;

        if(!$houseId && !$houseType && !$groupId)
        {
            echo '参数错误';
            return;
        }
        if($groupId)
        {
            $group = D('IMGroup')->queryGroupByGroupId($groupId);
            $groupId = $group['groupId'];
            $groupName = $group['groupName'];
            $maybeChatUserId = $group['houseUserId']==$this->userId ? $group['askerId'] : $group['houseUserId'];
            $chatUserId = $maybeChatUserId > 0 ? $maybeChatUserId : 0;
        }
        else if($houseId && $houseType)
        {
            //echo '跟房主聊天';
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
//                case 'NewHouse':
//                    $house = D('NewHouse')->findById($houseId);
//                    if($house)
//                    {
//                        $chatUserId = $house['userId']?$house['userId'] : 0;
//                    }
//                    break;
//            }
            $chatUserId = $this->getUserIdFromHouse($houseId, $houseType);
            if($chatUserId !== null)
            {
                $group = D('IMGroup')->queryUserGroup($this->userId, $chatUserId);
                if($group)
                {
                    $groupId = $group['groupId'];
                    $groupName = $group['groupName'];
                }
                else
                {
                    $askUser = D('User')->findById($this->userId);
                    $houseUser = null;
                    if($chatUserId > 0)
                    {
                        $houseUser = D('User')->findById($chatUserId);
                    }
                    $groupName = IMSdk::createGroupName($askUser['user_name'], $houseUser?$houseUser['user_name']:'房客行服务');
                }
            }
            else
            {
                echo '参数错误';
                return;
            }

        }
        $userId = $this -> userId;
        $userImgUrl = $this -> getHeadImgByUserId($userId);
        $userName = '';
        $userName2 = '';
        if ($userId) {
            $user = D('User') -> findById($userId);
            $userName = $user['user_name'];
        }
        if ($chatUserId) {
            $user2 = D('User') -> findById($chatUserId);
            $userName2 = $user2['user_name'];
        }
        $identifier = $this -> genImIdentifier($userId);
        $identifier2 = $this -> genImIdentifier($chatUserId);
        $chatUserImgUrl = $this -> getHeadImgByUserId($chatUserId);
        $sig = $this -> genImSign($identifier);

        $this -> assign('userName', $userName);
        $this -> assign('userName2', $userName2);
        $this->assign('groupId', $groupId);
        $this->assign('groupName', $groupName);
        $this -> assign('identifier', $identifier);
        $this -> assign('sig', $sig);
        $this -> assign('chatIdentifier', $identifier2);
        $this -> assign('sdkAppID', IMSdkAppId);
        $this -> assign('accountType', IMAccountType);
        $this -> assign("chatUserId", $chatUserId);
        $this -> assign("userId", $this -> userId);
        $this->assign('houseId', $houseId);
        $this->assign('houseType', $houseType);
        $this -> assign('userImgUrl', $userImgUrl);
        $this -> assign('chatUserImgUrl', $chatUserImgUrl);
        $this->display();
    }

    private function getHeadImgByUserId($userId) {
        if (!$userId) {
            return '';
        }
        return '/uploads/pic/' . $userId . '.png';
    }

    private function genImSign($identifier) {
        if($identifier == md5('100001'.'dev'))
        {
            return 'eJxlj11PgzAYhe-5FYTbGVf6YYt3ZC7auGHIpsmuSEvLqGNdwyq4GP-7Ji6RxPf2ec45eb*CMAyj9WJ1K8ry8GF94U9OR*F9GIHo5g86Z1QhfIFa9Q-qT2daXYjK63aAMSEEAjB2jNLWm8pcDUlUKSQlEEugMUoIhSQp5SVVASZhOUoe1a4Y5n*r8aU3QQzQsWK2A1zONzOez1jTe15PTvKBP7-vmhfVVa*UAIq20HZpn*oe14gyNtU5r9NF5Zdmbq2biv3qSbzVNHVtljM*MRzTHOjHLNGyWW8yNpr0Zq*vv8K7hGBE0Ih2uj2agx0ECGISQwR*Lgq*gzOFyWMe';
        }
        $api = IMSdk::createAndInitRestAPI();
        $sig = $api->generate_user_sig($identifier, '86400', IMPrivateKeyPath, IMSignature);
        return $sig[0];
    }

    private function genImIdentifier($id) {
        return IMSdk::createIdentifier($id);
    }
    public function createGroup()
    {
        if(!IS_POST)
        {
            return;
        }
        $askerId = $this -> userId;
        $houseUserId = I('chatUserId');

        $resData = array('status' => '1', 'content' => array(), 'errMsg'=>'' );
        //var_dump($askerId);
        //var_dump($houseUserId);
        $askUser = D('User')->findById($askerId);
        $houseUser = null;
        if($houseUserId > 0)
        {
            $houseUser = D('User')->findById($houseUserId);
            if(!$houseUser)
            {
                $resData['status'] = 0;
                $resData['errMsg'] = '参数错误！';
                return $this -> ajaxReturn($resData);
            }
        }
        if(!$askUser)
        {
            // return $this->error('参数错误！');
            $resData['status'] = 0;
            $resData['errMsg'] = '参数错误！';
            return $this -> ajaxReturn($resData);
        }
        $group = D('IMGroup')->queryUserGroup($askerId, $houseUserId);
        // var_dump($group);
        if($group)
        {
            $groupInfo = array(
                'id' => $group['id'],
                'groupId' => $group['groupId'],
                'groupName' => $group['groupName'],
            );
            //var_dump($groupInfo);
            $resData['content'] = $groupInfo;
            return $this -> ajaxReturn($resData);
        }
        $userSig = 'eJxlj1FLwzAYRd-7K0peJy5tlmUTfNhKK9OqdHWDPZW4pPrZJS1JtA7xv6t1YMD7es7lcj*CMAzRQ16e8-2*fdWucsdOovAiRBid-cGuA1FxVxEj-kH53oGRFa*dNAOMKKUxxr4DQmoHNZwMLhRoD1vRVMPGb3-yXZ6TGWa*Ak8DvE03ySq7Ge9GBSE6xTJbH8ePy5JZdciomLf15jpd9m5r1rRc4dEC0oUFvlNYuOYqmeTPhdRZ3x4oY23R0yjF9842L3dblaukuPQmHSh5OhRPp4QxMvPomzQWWj0IMY5oFBP8ExR8Bl*mDFxl';
        $identifier = 'admin';
        $api = IMSdk::createAndInitRestAPI($identifier);
        $ret = $api->set_user_sig($userSig);
        if($ret == false)
        {
            $resData['errMsg'] = '微聊功能正在维护，您可进行电话沟通或联系房客行进行反馈！';
            return $this -> ajaxReturn($resData);
        }
        $info_set = array(
            'group_id' => null,
            'introduction' => null,
            'notification' => null,
            'face_url' => null,
            'max_member_num' => 500,
        );
        try
        {
            $importResult = $api->account_import(IMSdk::createIdentifier($askerId), '房客行', '');
            if($importResult['ErrorCode'] != 0)
            {
                throw new Exception('导入用户失败！');
            }
            if($houseUserId>=0)
            {
                $importResult = $api->account_import(IMSdk::createIdentifier($houseUserId), '房客行', '');
                if($importResult['ErrorCode'] != 0)
                {
                    throw new Exception('导入用户失败！');
                }
            }
        }
        catch (Exception $e)
        {
            $resData['errMsg'] = '微聊功能正在维护，您可进行电话沟通或联系房客行进行反馈！';
            return $this -> ajaxReturn($resData);
        }
        $memList = array();
        $memList[] = array('Member_Account' => IMSdk::createIdentifier($askerId));
        if($houseUserId>=0)
        {
            $memList[] = array('Member_Account' => IMSdk::createIdentifier($houseUserId));
        }
        $groupName = IMSdk::createGroupName($askUser['user_name'], $houseUser?$houseUser['user_name']:'房客行服务');
        $ret = $api->group_create_group2('Public', '房客行', $identifier, $info_set, $memList);
        //var_dump($ret);
        if($ret['ErrorCode'] != 0)
        {
            $resData['status'] = 0;
            $resData['errMsg'] = '微聊功能正在维护，您可进行电话沟通或联系房客行进行反馈！';
            return $this -> ajaxReturn($resData);
        }
        //创建群聊成功
        $groupId = $ret['GroupId'];
        $groupData = array(
            'groupId' => $groupId,
            'groupName' => $groupName,
            'askerId' => $askerId,
            'houseUserId' => $houseUserId,
            'creator' => $identifier
        );
        $id = D('IMGroup')->saveIMGroup($groupData);
        $groupInfo = array(
            'id' => $id,
            'groupId' => $groupId,
            'groupName' => $groupName,
        );
        //var_dump($groupInfo);
        $resData['content'] = $groupInfo;
        return $this -> ajaxReturn($resData);
//        $groupInfo = $api->group_get_group_info($groupId);
//        var_dump($groupInfo);
    }

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