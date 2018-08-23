<?php

namespace Home\Controller;

use Common\Model\InfoModel;
use Common\Model\NewHouseModel;
use Common\Model\OldHouseModel;
use Common\Model\RentalHouseModel;
use Common\Model\TagModel;

class InfoController extends UserController
{
    public function index()
    {
        $this->display('info_list');
    }
    public function loadData()
    {
        $currentPage = I("currentPage", 1);
        $pageSize = I("pageSize", 10);
        $where['user_id'] = $this->userId;
        $start = ($currentPage-1)*$pageSize;
        $infos = D('Info')->where($where)->order('id desc')->limit($start, $pageSize)->select();
        if($infos)
        {
            $newHouseIds = array();
            $newHouses = array();
            $oldHouseIds = array();
            $oldHouses = array();
            $rentalHouseIds = array();
            $rentalHouses = array();
            foreach ($infos as $info)
            {
                switch ($info['houseType'])
                {
                    case InfoModel::$HouseType['NewHouse']:
                        $newHouseIds[] = $info['loupan_id'];
                        break;
                    case InfoModel::$HouseType['OldHouse']:
                        $oldHouseIds[] = $info['loupan_id'];
                        break;
                    case InfoModel::$HouseType['RentalHouse']:
                        $rentalHouseIds[] = $info['loupan_id'];
                        break;
                }
            }
            if(count($newHouseIds) > 0)
            {
                $where = array();
                $where['id'] = array('IN', $newHouseIds);
                $houses = D('NewHouse')->where($where)->select();
                foreach ($houses as $h)
                {
                    $h['typeCaption'] = D('Type')->findNameById($h['type']);
                    $h['stateCaption'] = NewHouseModel::$StateCaption[$h['state']];
                    $h['cityCaption'] = D('City') -> findNameById($h['cityId']);
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
                    $h['cityCaption'] = D('City') -> findNameById($h['cityId']);
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
                    $h['cityCaption'] = D('City') -> findNameById($h['cityId']);
                    $h['areaCaption'] = D('City') -> findNameById($h['areaId']);
                    $h['tags'] = json_decode($h['tags'], true);
                    $h['others'] = json_decode($h['others'], true);
                    $rentalHouses[$h['id']] = $h;
                }
            }
            foreach ($infos as &$info)
            {
                switch ($info['houseType'])
                {
                    case InfoModel::$HouseType['NewHouse']:
                        $info['house'] = $newHouses[$info['loupan_id']];
                        break;
                    case InfoModel::$HouseType['OldHouse']:
                        $info['house'] = $oldHouses[$info['loupan_id']];
                        break;
                    case InfoModel::$HouseType['RentalHouse']:
                        $info['house'] = $rentalHouses[$info['loupan_id']];
                        break;
                }
            }
            //var_dump($favorites);
            $tags = D('Tag')->where(array('state'=>array('NEQ', TagModel::$State['Deleted'])))->getField('id, name');
            $this->assign('tagNames', $tags);
            $this->assign('HouseType', InfoModel::$HouseType);
            $this->assign('Status', InfoModel::$Status);
            $this->assign('StatusCaption', InfoModel::$StatusCaption);
            $this->assign('Source', RentalHouseModel::$Source);
            $this->assign('infos', $infos);
            $this->success(array(
                'hasMore' => count($infos)>=$pageSize,
                'html' => $this->fetch('info_listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }
    }
    public function info()
    {
        $houseId = I('houseId');
        $houseType = I('houseType');
        if(!IS_POST)
        {
            $house = array();
            $tagNames = array();
            if($houseType == 'NewHouse')
            {
                $house = D('NewHouse') -> findById($houseId);
                $house['stateCaption'] = NewHouseModel::$StateCaption[$house['state']];
                $tags = D('Tag')->queryNewHouseTags();
                foreach ($tags as $tag)
                {
                    $tagNames[$tag['id']] = $tag['name'];
                }
                $ranges = D('NewHouse')->queryHouseHuxingRange($house['id']);
                foreach ($ranges as $key=>$v)
                {
                    $house[$key] = $v;
                }
            } else if($houseType == 'RentalHouse')
            {
                $house = D('RentalHouse') -> findById($houseId);
                $house['stateCaption'] = RentalHouseModel::$StateCaption[$house['state']];
                $tags = D('Tag')->queryRentalHouseTags();
                foreach ($tags as $tag)
                {
                    $tagNames[$tag['id']] = $tag['name'];
                }
            } else if($houseType == 'OldHouse')
            {
                $house = D('OldHouse') -> findById($houseId);
                $house['stateCaption'] = OldHouseModel::$StateCaption[$house['state']];
                $tags = D('Tag')->queryOldHouseTags();
                foreach ($tags as $tag)
                {
                    $tagNames[$tag['id']] = $tag['name'];
                }
            }
            $house['typeCaption'] = D('Type') -> findNameById($house['type']);
            $house['cityCaption'] = D('City')->findNameById($house['cityId']);
            $house['areaCaption'] = D('City')->findNameById($house['areaId']);
            $this->assign('tagNames', $tagNames);
            $house['tags'] = json_decode($house['tags'], true);
            $this->assign('house', $house);
            $this->assign('houseType', $houseType);
            $this->display();
            return;
        }
        $name = I('info_name');
        $phone = I('info_phone');
        $time = I('info_time');
        $remark = I('info_remark');
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'loupan_id' => $houseId,
            'houseType' => InfoModel::$HouseType[$houseType],
            'user_id' => $this->userId,
            'time_at' => $time,
            'node' => $remark
        );
        $result = D('Info')->saveInfo($data);
        if($result)
        {
            $this->success('提交成功！');
        }
        else
        {
            $this->error('提交失败！');
        }

    }
}