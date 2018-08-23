<?php

namespace Home\Controller;

use Common\Model\FavoriteModel;
use Common\Model\NewHouseModel;
use Common\Model\OldHouseModel;
use Common\Model\RentalHouseModel;
use Common\Model\TagModel;

class FavoriteController extends UserController
{

    public function _initialize()
    {
        parent::_initialize();
        session('NavBar','Favorite');
    }
    public function index()
    {
        $this->display();
    }
    public function loadData()
    {
        $currentPage = I("currentPage", 1);
        $pageSize = I("pageSize", 10);
        $where['userId'] = $this->userId;
        $start = ($currentPage-1)*$pageSize;
        $where['state'] = array('NEQ', FavoriteModel::$State['Deleted']);
        $favorites = D('Favorite')->where($where)->limit($start, $pageSize)->select();
        if($favorites)
        {
            $newHouseIds = array();
            $newHouses = array();
            $oldHouseIds = array();
            $oldHouses = array();
            $rentalHouseIds = array();
            $rentalHouses = array();
            foreach ($favorites as $fav)
            {
                switch ($fav['houseType'])
                {
                    case FavoriteModel::$HouseType['NewHouse']:
                        $newHouseIds[] = $fav['houseId'];
                        break;
                    case FavoriteModel::$HouseType['OldHouse']:
                        $oldHouseIds[] = $fav['houseId'];
                        break;
                    case FavoriteModel::$HouseType['RentalHouse']:
                        $rentalHouseIds[] = $fav['houseId'];
                        break;
                }
            }
            if(count($newHouseIds) > 0)
            {
                $where = array();//如果没有重新初始化，会导致前面查询收藏信息的条件也加入进去
                $where['id'] = array('IN', $newHouseIds);
                $houses = D('NewHouse')->where($where)->select();
                foreach ($houses as $h)
                {
                    $h['typeCaption'] = D('Type')->findNameById($h['type']);
                    $h['stateCaption'] = NewHouseModel::$StateCaption[$h['state']];
                    $h['areaCaption'] = D('City') -> findNameById($h['areaId']);
                    $h['tags'] = json_decode($h['tags'], true);
                    $h['others'] = json_decode($h['others'], true);
                    $ranges = D('NewHouse')->queryHouseHuxingRange($h['id']);
                    foreach ($ranges as $key=>$v)
                    {
                        $h[$key] = $v;
                    }
                    $newHouses[$h['id']] = $h;
                }
            }
            if(count($oldHouseIds) > 0)
            {
                $where = array();
                $where['id'] = array('IN', $oldHouseIds);
                $houses = D('OldHouse')->where($where)->select();
                foreach ($houses as $h)
                {
                    $h['typeCaption'] = D('Type')->findNameById($h['type']);
                    $h['stateCaption'] = OldHouseModel::$StateCaption[$h['state']];
                    $h['areaCaption'] = D('City') -> findNameById($h['areaId']);
                    $h['tags'] = json_decode($h['tags'], true);
                    $h['others'] = json_decode($h['others'], true);
                    $oldHouses[$h['id']] = $h;
                }
            }
            if(count($rentalHouseIds) > 0)
            {
                $where = array();
                $where['id'] = array('IN', $rentalHouseIds);
                $houses = D('RentalHouse')->where($where)->select();
                foreach ($houses as $h)
                {
                    $h['typeCaption'] = D('Type')->findNameById($h['type']);
                    $h['stateCaption'] = RentalHouseModel::$StateCaption[$h['state']];
                    $h['areaCaption'] = D('City') -> findNameById($h['areaId']);
                    $h['tags'] = json_decode($h['tags'], true);
                    $h['others'] = json_decode($h['others'], true);
                    $rentalHouses[$h['id']] = $h;
                }
            }
            foreach ($favorites as &$fav)
            {
                switch ($fav['houseType'])
                {
                    case FavoriteModel::$HouseType['NewHouse']:
                        $fav['house'] = $newHouses[$fav['houseId']];
                        break;
                    case FavoriteModel::$HouseType['OldHouse']:
                        $fav['house'] = $oldHouses[$fav['houseId']];
                        break;
                    case FavoriteModel::$HouseType['RentalHouse']:
                        $fav['house'] = $rentalHouses[$fav['houseId']];
                        break;
                }
            }
            //var_dump($favorites);
            $tags = D('Tag')->where(array('state'=>array('NEQ', TagModel::$State['Deleted'])))->getField('id, name');
            $this->assign('tagNames', $tags);
            $this->assign('HouseType', FavoriteModel::$HouseType);
            $this->assign('Source', RentalHouseModel::$Source);
            $this->assign('favorites', $favorites);
            $this->success(array(
                'hasMore' => count($favorites)>=$pageSize,
                'html' => $this->fetch('listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }

    }


    public function favor()
    {
        $houseId = i('houseId');
        $houseType = i('houseType');
        switch ($houseType)
        {
            case 'NewHouse':
                $houseType = FavoriteModel::$HouseType['NewHouse'];
                break;
            case 'OldHouse':
                $houseType = FavoriteModel::$HouseType['OldHouse'];
                break;
            case 'RentalHouse':
                $houseType = FavoriteModel::$HouseType['RentalHouse'];
                break;
            default:
                $this->error('收藏失败！');
                return;
        }
        $data['houseId'] = $houseId;
        $data['houseType'] = $houseType;
        $data['userId'] = $this->userId;
        $result = D('Favorite')->saveFavorite($data);
        if($result)
        {
            $this->success('收藏成功！');
        }
        else
        {
            $this->error('收藏失败！');
        }
    }
    public function cancelFavor()
    {
        $houseId = i('houseId');
        $houseType = i('houseType');
        switch ($houseType)
        {
            case 'NewHouse':
                $houseType = FavoriteModel::$HouseType['NewHouse'];
                break;
            case 'OldHouse':
                $houseType = FavoriteModel::$HouseType['OldHouse'];
                break;
            case 'RentalHouse':
                $houseType = FavoriteModel::$HouseType['RentalHouse'];
                break;
            default:
                $this->error('取消收藏失败！');
                return;
        }
        $result = D('Favorite')->deleteFavorite($this->userId, $houseId, $houseType);
        if($result)
        {
            $this->success('取消成功！');
        }
        else
        {
            $this->error('取消失败！');
        }
    }
}