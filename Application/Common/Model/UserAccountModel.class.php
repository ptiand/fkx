<?php
namespace Common\Model;

use Think\Model;

/**
 * 用户余额明细
 * Class UserAccountModel
 * @package Common\Model
 */
class UserAccountModel extends Model
{
    protected $trueTableName = 'user_account';

    public static $State = array(
        'Dealing' => '1',
        'Done' => '2',
        'Cancel' => '3'
    );
    public static $StateCaption = array(
        '1' => '处理中',
        '2' => '已完成',
        '3' => '已取消'
    );
    public static $PayType = array(
        'Commission' => '1',
        'Withdrawal' => '2'
    );
    public static $PayTypeCaption = array(
        '1' => '佣金',
        '2' => '提现'
    );
    public static function generatePayNo()
    {
        $time = strtotime(date('Y-m-d'));
        $res = D('UserAccount') -> where(array('pay_time'=>array('GT',$time))) -> order('id desc') -> find();
        if($res){
            $no = $res['pay_no'] + 1;
        }else{
            $no = date('Ymd').'00001';
        }
        return $no;
    }

    public function saveUserAccount($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('UserAccount') -> where($where) -> save($data);
        }
        else
        {
            if(!$data['pay_time'])
            {
                $data['pay_time'] = time();
            }
            if(!$data['state'])
            {
                $data['state'] = self::$State['Dealing'];
            }
            if(!$data['pay_no'])
            {
                $data['pay_no'] = self::generatePayNo();
            }
            return D('UserAccount')->add($data);
        }
    }
    public function findById($id)
    {
        return $this->where(array('id' => $id))->find();
    }
}