<?php
namespace Common\Model;

use Think\Exception;
use Think\Model;

/**
 * 用户
 * Class UserModel
 * @package Common\Model
 */
class UserModel extends Model
{
    protected $trueTableName = 'users';

    public static $LocationType = array(
        'Browser' => '1',
        'Wechat' => '2',
        'App' => '3'
    );

    public function findById($id)
    {
        return D('User')->where(array('id'=>$id))->find();
    }
    public function saveUser($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('User') -> where($where) -> save($data);
        }
        else
        {
            $data['create_at'] = time();
            return D('User')->add($data);
        }
    }
    public function addCommission($userId, $amount)
    {
        $user = $this->findById($userId);
        if(!$user)
        {
            throw new Exception('用户不存在！', 1001);
        }
        $model = D('User');
        $model -> startTrans();
        $user['amount'] += $amount;//用户佣金为累计佣金
        $user['balance'] += $amount;//添加的佣金也需要加到余额进去，余额可提现
        $userAccount = array(
            'user_id' => $userId,
            'state' => UserAccountModel::$State['Done'],
            'pay_type' => UserAccountModel::$PayType['Commission'],
            'amount' => $amount
        );
        $uaResult = D('UserAccount')->saveUserAccount($userAccount);
        if(!$uaResult)
        {
            $model->rollback();
            throw new Exception('保存余额明细失败！',1002);
        }
        $result = D('User')->where(array('id'=>$userId))->save($user);
        if(!$result)
        {
            $model->rollback();
            throw new Exception('保存余额失败！',1003);
        }
        $model->commit();
        return true;
    }

    /*
     * 用户提现申请
     * @param $userId
     * @param $withdrawalData
     */
    public function applyWithdrawal($userId, $withdrawalData)
    {
        $user = $this->findById($userId);
        if(!$user)
        {
            throw new Exception('用户不存在！',1001);
        }
        $amount = $withdrawalData['amount'];
        if($user['balance'] < $amount)
        {
            throw new Exception('余额不足！',1005);
        }
        $model = D('User');
        $model -> startTrans();
        $user['balance'] -= $amount;
        $userAccount = array(
            'user_id' => $userId,
            'state' => UserAccountModel::$State['Dealing'],
            'pay_type' => UserAccountModel::$PayType['Withdrawal'],
            'amount' => $amount
        );
        $userAccountId = D('UserAccount')->saveUserAccount($userAccount);
        if(!$userAccountId)
        {
            $model->rollback();
            throw new Exception('保存余额明细失败！',1002);
        }
        $withdrawalData['userAccountId'] = $userAccountId;
        $withdrawalData['user_id'] = $userId;
        $withdrawalId = D('Withdrawal')->saveWithdrawal($withdrawalData);
        if(!$withdrawalId)
        {
            $model->rollback();
            throw new Exception('保存余额明细失败！',1004);
        }
        $result = D('User')->where(array('id'=>$userId))->save($user);
        if(!$result)
        {
            $model->rollback();
            throw new Exception('保存余额失败！',1003);
        }
        $model->commit();
        return true;
    }

    public function findByOpenid($openid)
    {
        return D('User')->where(array('openid'=>$openid))->find();
    }

    /**
     * 上报用户的地理位置信息
     * @param $userId
     * @param $longitude
     * @param $latitude
     * @param $locationType
     * @return bool|mixed
     */
    public function reportLocation($userId, $longitude, $latitude, $locationType)
    {
        $user = $this->findById($userId);
        if(!$user)
            return false;
        //如果上报类型不为微信而且最后一次上报类型为微信，而且判断10分钟内微信有上报过，则不更新
        //因为微信上报的位置会比浏览器或者App上报的位置更准
        if($locationType != self::$LocationType['Wechat']
            && $user['locationType'] == self::$LocationType['Wechat']
            && ($user['locationReportTime'] + 10*60) > time())
        {
            return false;
        }

        $data = array(
            'id' => $userId,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'locationType' => $locationType,
            'locationReportTime' => time()
        );
        return $this->saveUser($data);
    }

    /**
     * 上报微信用户地理位置信息
     * @param $openId
     * @param $longitude
     * @param $latitude
     * @return bool|mixed
     */
    public function reportWechatUserLocation($openId, $longitude, $latitude)
    {
        $user = $this->findByOpenid($openId);
        if(!$user)
            return false;
        return $this->reportLocation($user['id'], $longitude, $latitude, self::$LocationType['Wechat']);
    }

}