<?php
namespace Common\Model;

use Think\Exception;
use Think\Model;

/**
 * 用户提现申请
 * Class WithdrawalModel
 * @package Common\Model
 */
class WithdrawalModel extends Model
{
    protected $trueTableName = 'withdrawals';
    public static $Type = array(
        'Zfb' => '1',
        'Bank' => '2'
    );
    public static $TypeCaption = array(
        '1' => '支付宝',
        '2' => '银行卡'
    );
    public static $Status = array(
        'UnDeal' => '1',
        'Done' => '2'
    );
    public static $StatusCaption = array(
        '1' => '未处理',
        '2' => '已处理'
    );
    public function saveWithdrawal($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Withdrawal') -> where($where) -> save($data);
        }
        else
        {
            if(!$data['status'])
            {
                $data['status'] = self::$Status['UnDeal'];
            }
            if(!$data['node'])
            {
                $data['node'] = '';
            }
            $data['time'] = time();
            return D('Withdrawal')->add($data);
        }
    }
    public function findById($id)
    {
        return $this->where(array('id'=> $id))->find();
    }
    public function pay($id)
    {
        $data = $this->findById($id);
        if(!$data)
        {
            throw new Exception('查找提现申请失败！',  1006);
        }
        if($data['status'] != self::$Status['UnDeal'])
        {
            throw new Exception('提现申请状态错误！', 1007);
        }
        $userAccount = D('UserAccount')->findById($data['userAccountId']);
        if(!$userAccount)
        {
            throw new Exception('找不到余额扣款明细！', 1008);
        }
        $model = D('Withdrawal');
        $model -> startTrans();
        $data['status'] = self::$Status['Done'];
        $saveResult = $this->saveWithdrawal($data);
        if(!$saveResult)
        {
            $model->rollback();
            throw new Exception('保存提现申请失败！', 1009);
        }
        $userAccount['state'] = UserAccountModel::$State['Done'];
        $uaResult = D('UserAccount')->saveUserAccount($userAccount);
        if(!$uaResult)
        {
            $model->rollback();
            throw new Exception('保存余额扣款记录失败！', 1010);
        }
        $model->commit();
        return true;
    }
}