<?php

namespace Admin\Controller;

use Admin\Model\UserModel;
use Common\Model\CardModel;
use Common\Model\UserCardModel;

class UserCardController extends AdminController
{
    public function index()
    {
        $this->display();
    }
    public function loadList()
    {
        $offset = i("offset");
        $limit = i("limit");
        $search = i('search');
        $where['UC.`state`'] = array('NEQ', UserCardModel::$State['Deleted']);
        if ($search) {

            $searchCondition['US.`user_name`'] = array('LIKE', "%$search%");
            $searchCondition['US.`phone`'] = array('LIKE', "%$search%");
            $searchCondition['C.`name`'] = array('LIKE', "%$search%");
            $searchCondition['C.`returnCash`'] = array('LIKE', "%$search%");
            $searchCondition['_logic'] = 'OR';
            $where[] = $searchCondition;
        }
        $count = D('UserCard')
                -> alias('UC')
                -> join('LEFT JOIN `Cards` AS C ON UC.`cardId` = C.`id` ')
                -> join('LEFT JOIN `users` AS US ON US.`id`=UC.`UserId`')
                ->where($where) -> count('UC.id');
        $cards = D('UserCard')->field('UC.*, C.`houseId`, C.`houseType`, C.`name`, C.`returnCash`, 
        US.`phone` AS userPhone, US.`user_name` AS userName')
            -> alias('UC')
            -> join('LEFT JOIN `Cards` AS C ON UC.`cardId` = C.`id` ')
            -> join('LEFT JOIN `users` AS US ON US.`id`=UC.`UserId`')
            ->where($where)
            ->order('UC.`id` desc')
            ->limit($offset, $limit)
            ->select();

        $newHouseIds = array();
        $newHouseNames = array();
        $oldHouseIds = array();
        $oldHouseNames = array();
        $rentalHouseIds = array();
        $rentalHouseNames = array();
        foreach ($cards as $card)
        {
            switch ($card['houseType'])
            {
                case CardModel::$HouseType['NewHouse']:
                    $newHouseIds[] = $card['houseId'];
                    break;
                case CardModel::$HouseType['OldHouse']:
                    $oldHouseIds[] = $card['houseId'];
                    break;
                case CardModel::$HouseType['RentalHouse']:
                    $rentalHouseIds[] = $card['houseId'];
                    break;
            }
        }
        if(count($newHouseIds) > 0)
        {
            $where = array();
            $where['id'] = array('IN', $newHouseIds);
            $newHouseNames = D('NewHouse')->where($where)->getField('id, name');
        }
        if(count($oldHouseIds) > 0)
        {
            $where = array();
            $where['id'] = array('IN', $oldHouseIds);
            $oldHouseNames = D('OldHouse')->where($where)->getField('id, name');
        }
        if(count($rentalHouseIds) > 0)
        {
            $where = array();
            $where['id'] = array('IN', $rentalHouseIds);
            $rentalHouseNames = D('RentalHouse')->where($where)->getField('id, name');
        }
        foreach ($cards as &$card)
        {
            switch ($card['houseType'])
            {
                case CardModel::$HouseType['NewHouse']:
                    $card['houseName'] = $newHouseNames[$card['houseId']];
                    break;
                case CardModel::$HouseType['OldHouse']:
                    $card['houseName'] = $oldHouseNames[$card['houseId']];
                    break;
                case CardModel::$HouseType['RentalHouse']:
                    $card['houseName'] = $rentalHouseNames[$card['houseId']];
                    break;
                case CardModel::$HouseType['Loupan']:
                    $card['houseName'] = M('loupan') -> where(array('id'=>$card['loupan_id'])) -> getField('title');
                    break;
            }
        }
        $resArr = array('total'=>$count, 'rows' => $cards);
        echo json_encode($resArr);
    }
}