<?php
namespace Common\Model;

use Think\Model;

class CardModel extends Model
{
    protected $trueTableName = 'Cards';

    public static $State = array(
        'Enable' => 1,
        'Disabled' => 9
    );
    public static $HouseType = array(
        'NewHouse'=>1,
        'OldHouse' => 2,
        'RentalHouse' => 3
    );

    public static $StateCaption = array(
        '1' => '启用',
        '9' => '停用'
    );
    public function findById($id)
    {
        $where['id'] = $id;
        $card = D('Card') -> where($where) -> find();
        return $card;
    }

    /**
     * 查询房屋当前有效的卡券
     * @param $houseId
     * @return Card
     */
    public function queryHouseCard($houseId, $houseType)
    {
        $where['houseId'] = $houseId;
        $where['houseType'] = $houseType;
        $where['state'] =  self::$State['Enable'];
        $card = D('Card') -> where($where) -> order('id DESC') -> find();
        return $card;
    }

    /**
     * 保存房屋卡券
     * @param $data
     * @return true|false
     */
    public function saveHouseCard($data)
    {
        if($data['id'])
        {
            $card = $this->findById($data['id']);
            if($card && ($data['name'] != $card['name'] || $data['returnCash'] != $card['returnCash']))
            {
                $card['state'] = self::$State['Disabled'];
                D('Card')->save($card);
                unset($data['id']);//如果名称和返现金额不同，则保存为新记录
                $data['createTime'] = time();
                D('Card')->add($data);
            }
            else
            {
                $where['id'] = $data['id'];
                D('Card')->where($where)->save($data);
            }
        }
        else
        {
            $data['createTime'] = time();
            D('Card')->add($data);
        }
        return true;
    }

}