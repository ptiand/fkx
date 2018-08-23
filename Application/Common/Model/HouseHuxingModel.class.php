<?php
namespace Common\Model;

use Think\Model;
use Think\Model\RelationModel;

/**
 * 新房户型关联表
 * Class HouseHuxingModel
 * @package Common\Model
 */
class HouseHuxingModel extends RelationModel
{
//    protected $_link = array(
//        'HouseHuxing' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'NewHouseModule',
//            'foreign_key'   => 'newHouseId',
//            'mapping_name'  => 'XXX',
//            'mapping_order' => 'sort desc',
//        ),
//    );
    protected $trueTableName = 'HouseHuxings';
    public function queryHouseHuxing($houseId)
    {
        $where['newHouseId'] = $houseId;
        $huxings = D('HouseHuxing')->where($where)->order('sort ASC')->select();
        return $huxings;
    }
    public function saveHouseHuxing($data)
    {
        if(!$data['id'])
            unset($data['id']);
        if(!$data['square'])
            $data['square'] = 0;
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('HouseHuxing') -> where($where) -> save($data);
        }
        else
        {
            return D('HouseHuxing')->add($data);
        }
    }
    public function deleteHouseHuxing($id)
    {
        $where['id'] = $id;
        return D('HouseHuxing')->where($where)->delete();
    }
}