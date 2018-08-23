<?php

namespace Home\Controller;

use Admin\Model\UserModel;
use Common\Model\CardModel;
use Common\Model\UserCardModel;

class UserCardController extends UserController
{
    public function index()
    {
        $this->display('Center/card_list');
    }
    public function loadData()
    {
        $currentPage = I("currentPage", 1);
        $pageSize = I("pageSize", 10);
        $where['UC.`userId`'] = $this->userId;
        $start = ($currentPage-1)*$pageSize;
        $where['UC.`state`'] = array('NEQ', UserCardModel::$State['Deleted']);

        $cards = D('UserCard')->field('UC.*, C.`houseId`, C.`houseType`, C.`name`, C.`returnCash`')->alias('UC')->join('LEFT JOIN `Cards` AS C ON UC.`cardId` = C.`id` ')
                    ->where($where)
                    ->order('UC.`id` desc')
                    ->limit($start, $pageSize)
                    ->select();
        if($cards)
        {
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
            $this->assign('StateCaption', UserCardModel::$StateCaption);
            $this->assign('cards', $cards);
            $this->success(array(
                'hasMore' => count($cards)>=$pageSize,
                'html' => $this->fetch('Center/card_listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }
    }
    public function add()
    {
        $cardId = i('cardId');
        $userCard = D('UserCard')->queryUserCard($this->userId, $cardId);
        if($userCard)
        {
            $this->error('您已领取，请勿重复领取！');
        }
        else
        {
            $data['userId'] = $this->userId;
            $data['cardId'] = $cardId;
            D('UserCard')->saveUserCard($data);
            $this->success('领取成功！');
        }
    }
}