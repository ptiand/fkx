<?php

namespace Home\Controller;

use Common\Model\OldHouseModel;
use Common\Model\RentalHouseModel;
use Common\Model\RewardModel;
use Common\Model\UserAccountModel;
use Common\Model\WithdrawalModel;
use Think\Controller;
use Think\Exception;
use Think\Model;

class CenterController extends UserController
{
    private $user_id;

    public function _initialize()
    {
        $user = get_user();
        //print_r($user);exit;
        parent::_initialize();
        $this->user_id = $user['id'];
        	//echo $user_id;exit;
        if (!$user) {
            session('back','Client/index');
            $this->redirect('Login/index');
        }
        session('NavBar', 'Center');
    }

    public function index()
    {
        $info = D('User')->findById($this->user_id);
        $shop_info = D('Shops')->where(array('user_id'=>$this->user_id))->find();
        $this->assign('shop_info', $shop_info);
        $this->assign('isWeixin', $this->isWeixin());
        $this->assign('info', $info);
        //print_r($info);exit;
        $this->display();
    }
    private function isWeixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
        {
            return true;
        } else {
            return false;
        }
    }

    public function mymoney()
    {
        $info = M('users')->where(['id' => $this->user_id])->find();
        $this->assign('info', $info);
        $this->display();
    }

    public function editinfo()
    {
        $info = M('users')->where(['id' => $this->user_id])->find();

        $this->assign('info', $info);
        $this->display();
    }

    public function logout()
    {
        //session_destroy();
        session('user',null);
        session('back',null);
        session('UserLogout', true);
        session('UserLogoutTime', time() + 600);//微信端手动登出10分钟，10分钟后重新登陆
        redirect("/index.php/index/index");
    }

    //修改用户名
    public function edit_name()
    {
        $info = M('users')->where(['id' => $this->user_id])->getField('user_name');
        $this->assign('name', $info);
        $this->display();
    }

    public function edit_n()
    {
        $data['user_name'] = I('user_name');
        $info = M('users')->where(['id' => $this->user_id])->save($data);
        $this->success('修改成功');
    }

    //修改用户名
    public function edit_pwd()
    {
        $this->display();
    }

    public function edit_p()
    {
        $data = I('post.');
        if ($data['pass'] == $data['y_pass']) {
            $this->error('新旧密码不能一样');
        }
        if ($data['pass'] != $data['pwd']) {
            $this->error('新密码与确认密码不一致');
        }
        //验证原密码
        $info = M('users')->where(['id' => $this->user_id])->find();
        $pwd = get_pwd($data['y_pass'], $info['random']);
        if ($pwd != $info['password']) {
            $this->error('原密码错误！');
        }
        $string = get_string();
        $save['random'] = $string->rand_string(6, 1);
        $save['password'] = get_pwd($data['pass'], $save['random']);
        $resu = M('users')->where(['id' => $this->user_id])->save($save);
        if ($resu) {
            session_destroy();
            $this->success('修改成功');
        } else {
            $this->error('修改失败！');
        }

    }

    public function imgset()
    {
        $this->display();
    }

    public function head_pic()
    {
        $user_id = $this->user_id;
        $src = I('pic');
        $url = explode(',', $src);
        $img = base64_decode($url[1]);
        $filename = '/uploads/pic/' . $user_id . '.png';
        $pic = ROOT_PATH . $filename;
        $a = file_put_contents($pic, $img);
        if ($a) {
            $newWidth = $newHeight = 60;
            list($width, $height) = getimagesize($pic);
            //   echo $width;
            $img = imagecreatefrompng($pic);
            $newImg = imagecreatetruecolor($newWidth, $newHeight);
            if ($newWidth && ($width < $height)) {
                $newWidth = ($newHeight / $height) * $width;
            } else {
                $newHeight = ($newWidth / $width) * $height;
            }
            imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // 6、保存图像
            header('Content-type: image/png');
            imagepng($newImg, $pic);
            // 7、是放资源
            imagedestroy($img);
            imagedestroy($newImg);
            $resule = M('users')->where(['id' => $user_id])->save(['pic' => $filename]);

            $this->success('修改成功！');

        } else {
            $this->error('上传失败！');
        }
    }

    //添加提现记录
    public function addout()
    {

        $w_time = session('w_time');

        $balance['time'] = time();
        if ($w_time && $balance['time'] < $w_time + 5 * 60 * 60) {
            $this->error('请等待管理员的审核！');
        }

        $type = I('type');
        if ($type == 1) {
            $balance['amount'] = I('yue');
        } else {
            $yue = M('users')->where(['id' => $this->user_id])->getfield('balance');
            $balance['amount'] = floor($yue);
        }
        $balance['address'] = I('address');
        $balance['type'] = I('types');
        $balance['name'] = I('name');
        $balance['cards'] = I('cards');
        $balance['phone'] = I('phone');
        $balance['time'] = time();
        $balance['user_id'] = $this->user_id;
        $res = M('withdrawals')->add($balance);
        if ($res) {
            session('w_time', time());
            $this->success('申请成功！');
        } else {
            $this->error('申请失败，请重新申请！');
        }

    }

    //注册分享
    public function qrcode()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php/weixin/qrcode';
        $esult = curl_request($url);
    }

    /*------------------------------------- New Useful Code -------------------------------------*/

    private function getOldHouseStateCaption() {
        $caption = null;
        $State = OldHouseModel::$State;
        foreach (OldHouseModel::$StateCaption as $key=>$item) {
            switch ($key) {
                case $State['OnSale']:
                case $State['SaleOut']:
                case $State['UnSale']:
                    $caption[$key] = array('name'=>$item);
            }
        }
        return $caption;
    }

    public function mypublish() {
        $stateList = $this -> getOldHouseStateCaption();
        $countList = $this -> countMyPublishes();
        $allCount = 0;

        foreach ($stateList as $key=>&$state) {
            $found = false;
            foreach ($countList as $count) {
                if ($count['state'] == $key) {
                    $state['count'] = $count['count'];
                    $allCount += $count['count'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $state['count'] = 0;
            }
        }

        // tips: 打开这里的注释有惊喜哦 ~
//        var_dump($stateList);
//        foreach ($stateList as $s) {
//            //$state['count'];
//        }
//        var_dump($stateList);

        $this -> assign('stateList', $stateList);
        $this -> assign('allStateCount',$allCount);
        // var_dump($countList);

        $this -> display();
    }

    public function mypublish_listItem() {
        $filter = I("filter");
        $currentPage = I("currentPage");
        $pageSize = I("pageSize");
        $houseState = $filter['state'];
        $where['userId'] = $this -> user_id;
        $where['source'] = array('in', array(1,2));
        if ($houseState != -1) {
            $where['state'] = $houseState;
        } else {
            $where['state'] = array('neq', OldHouseModel::$State['Deleted']);
        }
        $houseList = $this -> getList($currentPage*$pageSize, 10, $where);
        // print_r($houseList);exit;
        if ($houseList) {
            $this->assign('houseList', $houseList);
            $this->success($this->fetch(), "", true);
        } else {
            $this->error('没有更多数据！');
        }
    }

    private function getList($start, $limit, $where) {
        $typeOldHouse = OldHouseModel::$PublishType;
        $typeRentalHouse = RentalHouseModel::$PublishType;
        $fields = join(',', array(
            'id',
            //'source',
            'name',
            'state',
            'updateTime',
            'userId',
            'isLocked',
            'primaryImage',
            'type',
            'cityId',
            'areaId',
            'address',
            'price',
            'houseRoom',
            'livingRoom',
            'restRoom',
            'diningRoom',
            'veranda',
            'square'
        ));

        $oldHouseFields = join(', ', array(
            '\'[]\' as source',
            'totalPrice',
            'tags',
        ));

        $rentalHouseFields = join(', ', array(
            'source',
            '0 as totalPrice',
            '\'[]\' as tags'
        ));

        $Model = new Model();
        $Model -> table('(SELECT '.$fields. ', '.$typeOldHouse.' as publishType, '.$oldHouseFields .' from OldHouses oh
        UNION ALL SELECT '.$fields. ', '.$typeRentalHouse .' as publishType, '.$rentalHouseFields. ' from RentalHouses rh) as t');
        $houseList = $Model-> where($where) -> limit($start, $limit) -> order(' updateTime DESC') -> select() ;
         //echo $Model->getLastSql();exit;
        $typeList = D('Type') -> select();
        $tagList = D('Tag') -> queryOldHouseTags();
        $tagMap = array();
        foreach ($tagList as $tag) {
            $tagMap[$tag['id']] = $tag['name'];
        }
        foreach ($houseList as &$house) {
            foreach ($typeList as $type) {
                if ($type['id'] == $house['type']) {
                    $house['typeCaption'] = $type['type_name'];
                }
            }
            $house['stateCaption'] = OldHouseModel::$StateCaption[$house['state']];
            $house['tagList'] = array();
            if ($house['publishType'] == $typeOldHouse) {
                foreach (json_decode($house['tags']) as $tagId) {
                    $house['tagList'][] = $tagMap[$tagId];
                }
                $house['priceCaption'] = $house['totalPrice'];
                $house['priceCaptionUnit'] = '万元/套';
                $house['publishTypeCaption'] = '二手';
                $house['publishPath'] = 'oldHouse';
                $house['detailPath'] = 'OldHouse';
            } else {
                $house['priceCaption'] = $house['price'];
                $house['priceCaptionUnit'] = '元/月';
                $house['publishTypeCaption'] = '租房';
                $house['publishPath'] = 'rentalHouse';
                $house['detailPath'] = 'RentalHouse';
            }
            $house['areaCaption'] = D('City') -> findNameById($house['areaId']);
        }
        // var_dump($houseList);
        return $houseList;
    }

    private function countMyPublishes() {
        $where['userId'] = $this -> user_id;
        $where['state'] = array('neq', OldHouseModel::$State['Deleted']);
        $filed = '*, count(*) as count';
        
        $state = OldHouseModel::$State;
        unset($state['Deleted']);
        $return = array();
        foreach($state as $val)
        {
            $where = array(
                'userId'    =>  $this -> user_id,
                'state'     =>  $val
            );
            $old_info = D('OldHouse')->field($field)->where($where)->count();
            $where['source'] = array('neq', 9);
            $Rental_info = D('RentalHouse')->where($where)->field($field)->count();
            
            $tmp = array(
            'userId'    =>  $this -> user_id,
            'state'     =>  $val,
            'count' => 0);
            
            if(!empty($old_info))
            {
                $tmp['count'] += $old_info;
            }
            if(!empty($Rental_info))
            {
                $tmp['count'] += $Rental_info;
            }
            $return[] = $tmp;
        }
        return $return;
        print_r($return);exit;
        
        
        
        
        
        
        if(!empty($old_info))
        {
            $return[0]['count'] += $old_info;
        }
        if(!empty($Rental_info))
        {
            $return[0]['count'] += $Rental_info;
        }
        
        $sql = '(SELECT userId, state  FROM OldHouses oh UNION ALL SELECT userId, state FROM RentalHouses) as t';
        $Model = new Model();
        $result = $Model -> table($sql) -> where($where) -> group('state') -> field('*, count(*) as count') -> select();
        print_R($result);exit; 
        echo $Model->getLastSql();exit;
        return $return;
    }

    public function prize()
    {
        $user = D('User')->findById($this->user_id);
        $this->assign('amount', $user['balance']);
        $this->display();
    }
    public function loadAccountData()
    {
        $userAccounts = D('UserAccount')->where(array('user_id' => $this->user_id))->order('id DESC ')->select();
        if($userAccounts)
        {
            $this->assign('userAccounts', $userAccounts);
            $this->assign('PayType', UserAccountModel::$PayType);
            $this->assign('PayTypeCaption', UserAccountModel::$PayTypeCaption);
            $this->assign('State', UserAccountModel::$State);
            $this->assign('StateCaption', UserAccountModel::$StateCaption);
            $this->success(array(
                'hasMore' => count($userAccounts)>=$userAccounts,
                'html' => $this->fetch('prize_account_listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }
    }
    public function loadRewardData()
    {
        $rewardList = D('Reward')->where(array('userId'=>$this->user_id))->order('id DESC ')->select();
        if($rewardList)
        {

            $this->assign('rewardList', $rewardList);
            $this->assign('State', RewardModel::$State);
            $this->assign('StateCaption', RewardModel::$StateCaption);
            $this->success(array(
                'hasMore' => count($rewardList)>=$rewardList,
                'html' => $this->fetch('prize_reward_listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }
    }
    public function withdrawal()
    {
        if(!IS_POST)
        {
            $user = D('User')->findById($this->user_id);
            $this->assign('amount', $user['balance']);
            $this->assign('Type', WithdrawalModel::$Type);
            $this->display('prize_withdrawal');
        }
        else
        {
            //var_dump(I('post.'));
            $amount = I('amount');
            if(!is_numeric($amount) || $amount <= 0)
            {
                $this->error('提现金额错误！');
                return;
            }
            try{
                $data = I('post.');
                $result = D('User')->applyWithdrawal($this->user_id, $data);
                if($result)
                {
                    $this->success('申请成功！');
                }
                else
                {
                    $this->error('申请失败！');
                }
            }
            catch (Exception $e)
            {
                if($e->getCode() > 0)
                {
                    $this->error($e->getMessage());
                    return;
                }
                $this->error('申请失败！');
            }
        }
    }

    public function supplyRewardInfo()
    {
        $id = I('id');
        if(!IS_POST)
        {
            $reward = D('Reward')->findById($id);
            if(!$reward || $reward['userId'] != $this->user_id)
            {
                $this->redirect('Center/prize');
                return;
            }
            $this->assign('reward', $reward);
            $this->display('prize_supplyRewardInfo');
        }
        else
        {
            $username = I('username');
            $phone = I('phone');
            $address = I('address');
            $reward = D('Reward')->findById($id);
            if(!$reward || $reward['userId'] != $this->user_id)
            {
                $this->error('保存失败');
                return;
            }
            if($reward['state'] != RewardModel::$State['UnReceived'])
            {
                $this->error('此奖品不为未领取状态，无法修改！');
                return;
            }
            $reward['username'] = $username;
            $reward['phone'] = $phone;
            $reward['address'] = $address;
            D('Reward')->saveReward($reward);
            $this->success('保存成功！');
        }
    }

    public function bindWx(){
        $user = session("user");
        //$this->success($user['id']);
        $openId = session('openId');
        //$this->success($openId);
        if(empty($user)){
            $this->error('请先登录');
            return;
        }
        if(empty($openId)){
            $this->error('获取不到openId信息');
            return;
        }
        $u = D('User')->findByOpenid($openId);
        if(!empty($u)){
            if($u['id'] != $user['id']){
                $this->error('该微信帐号已绑定过帐号');
                return;
            }
        }
        $userData = array(
            'id' => $user['id'],
            'openid' => $openId
        );
        D('User')->saveUser($userData);
        $this->success('保存成功！');
    }
}