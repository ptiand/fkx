<?php


namespace Admin\Controller;

use Common\Model\CardModel;
use Common\Model\MediaModel;
use Common\Model\RentalHouseModel;

class RentalHouseController extends AdminController
{
    public function index()
    {
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this->display();
    }
    public function loadRentalHouse()
    {
        $condition['state'] = array('NEQ', RentalHouseModel::$State['Deleted']);
        $offset = i("offset");
        $limit = i("limit");
        $search = i('search');
        if($search){
            $condition['name'] = array('LIKE',"%$search%");;
        }
        $houses =  D('RentalHouse')
            -> where($condition)
            //-> order($reorder)
            ->limit($offset,$limit)
            -> select();
        foreach($houses as &$house)
        {
            $house['stateCaption'] = RentalHouseModel::$StateCaption[$house['state']];
            $house['cityCaption'] = D('City')->findNameById($house['cityId']);
            $house['areaCaption'] = D('City')->findNameById($house['areaId']);
            //$house['huxingCaption'] = D('HuXing')->findNameById($house['huxingId']);
            $house['typeCaption'] = D('Type')->findNameById($house['type']);
        }
        $count =  D('RentalHouse')
            -> where($condition)
            -> count();
        $resArr = array("total"=>$count,"rows"=>$houses?$houses:array());

        echo json_encode($resArr);
    }
    public function add()
    {
        if(IS_POST)
        {
            $data = i('post.');
            $data['tags'] = $data['tags'] ? json_encode($data['tags']) : json_encode(array());
            $pics = i('pics');
            if($pics)
            {
                $data['primaryImage'] = $pics[0];
            }
            else {
                $data['primaryImage'] = '';
            }
            $savedId = D('RentalHouse')->saveHouse($data);
            $this->saveMedias($savedId);
            if($savedId)
            {
                $this->success('增加成功！');
            }
            else
            {
                $this -> error('添加失败！');
            }
        }
        else
        {
            $this->loadDatas();
            $this->display('detail');
        }
    }
    public function saveMedias($houseId)
    {
        $video = i('video');
        $pics = i('pics');
        D('RentalHouse')->saveHouseMedias($houseId, $video, $pics);
    }
    private function loadDatas()
    {
        $houseTypes = D('Type')->findAll();
        $typeMap = array();
        foreach ($houseTypes as $type)
        {
            $typeMap[$type['id']] = $type['type_name'];
        }
        $this->assign('houseTypes', $typeMap);
        $zXiuList = D('ZhuangXiu')->findAll();
        $zxiuMap = array();
        foreach ($zXiuList as $xiu)
        {
            $zxiuMap[$xiu['id']] = $xiu['zxiu_name'];
        }
        $this->assign('zxiuMap', $zxiuMap);

        $topCities = D('City')->queryTopCity();
        $topCityMap = array();
        foreach ($topCities as $city)
        {
            $topCityMap[$city['id']] = $city['city_name'];
        }
        $this->assign('topCities', $topCityMap);
        unset(RentalHouseModel::$StateCaption[RentalHouseModel::$State['Deleted']]);
        $this->assign('houseState', RentalHouseModel::$StateCaption);
        $tags = D('Tag')->queryRentalHouseTags();
        $this->assign('tags', $tags);
        $this->assign('rentalTypes', RentalHouseModel::$RentalTypeCaption);
        $this->assign('sources', RentalHouseModel::$SourceCaption);
        $range = array();
        for ($i=0; $i<=10; $i++)
        {
            $range[$i] = $i;
        }
        $this->assign('houseRoomRange',$range);
        $this->assign('livingRoomRange',$range);
        $this->assign('restRoomRange',$range);
        $this->assign('diningRoomRange', $range);
        $this->assign('verandaRange', $range);
    }
    public function edit()
    {
        $id = i('id');
        $house = D('RentalHouse')->findById($id);
        if(IS_POST)
        {
            $data = i('post.');
            $data['longitude'] = $data['longitude']? $data['longitude'] : 0;
            $data['latitude'] = $data['latitude']? $data['latitude'] : 0;
            $data['tags'] = $data['tags'] ? json_encode($data['tags']) : json_encode(array());
            $pics = i('pics');
            if($pics)
            {
                $data['primaryImage'] = $pics[0];
            }
            else {
                $data['primaryImage'] = '';
            }
            $saved = D('RentalHouse')->saveHouse($data);
            $this->saveMedias($id);
            if($saved)
            {
                $this->success('保存成功！');
            }
            else
            {
                $this -> error('保存失败！');
            }
        }
        else
        {
            $this->assign('house', $house);
            $this->loadDatas();
            $houseVideo = D('Media')->queryHouseVideo($id, MediaModel::$HouseType['RentalHouse']);
            $this->assign('houseVideo', $houseVideo);
            $housePics = D('Media')->queryHousePics($id, MediaModel::$HouseType['RentalHouse']);
            $this->assign('housePics', $housePics);
            $this->assign('houseTags', json_decode($house['tags']));
            $this->display('detail');
        }
    }
    public function editExtra()
    {
        $id = i('id');
        $house = D('RentalHouse')->findById($id);
        if(IS_POST)
        {
            if(!$house)
            {
                $this->error('找不到楼房信息');
                return;
            }
            $data = i('post.');
            $otherArray = json_decode($house['others'], true);
            $extraPrefix = "extra_";
            $preLength = strlen($extraPrefix);
            $ptxxPrefix = 'ptxx_';
            $ptxxPreLength = strlen($ptxxPrefix);
            $ptxxArr = array();
            foreach ($data as $key=>$value)
            {
                if(strpos($key, $extraPrefix) === 0)
                {
                    $otherArray[substr($key, $preLength)] = $value;
                }
                if(strpos($key, $ptxxPrefix) === 0)
                {
                    //配套信息字段
                    $fieldName = substr($key, $ptxxPreLength);
                    $ptxxArr[$fieldName] = $value;
                }
            }
            $contactNames = $data['contact_name'];
            $contactTels = $data['contact_tel'];
            $contacts = array();
            if($contactNames)
            {
                foreach ($contactNames as $key=>$name)
                {
                    $contacts[$key] = array(
                        'name' => $name,
                        'tel' =>$contactTels[$key]
                    );
                }
            }
            $otherArray['ptxx'] = $ptxxArr;
            $otherArray['contacts'] = $contacts;
            $house['others'] = json_encode($otherArray);
            $saved = D('RentalHouse')->saveHouse($house);
            if($saved)
            {
                $this->success('保存成功！');
            }
            else
            {
                $this->error('保存失败！');
            }
        }
        else
        {
            $this->assign('house', $house);
            $extraArr = json_decode($house['others'], true);
            $this->assign('extra', $extraArr);
            $this->assign('ptxx', $extraArr['ptxx']);
            $this->assign('ptxxOptions', RentalHouseModel::$PtxxOptions);
            $this->loadDatas();
            $this->display('extra');
        }
    }
    public function delete()
    {
        $ids = i('ids');
        if($ids)
        {
            foreach ($ids as $id)
            {
                D('RentalHouse')->deleteHouse($id);
            }
            $this->success('删除成功！');
        }
        else
        {
            $this->error('删除失败！');
        }
    }
    public function setCard()
    {
        if(IS_POST)
        {
            $data = i('post.');
            $data['houseType'] = CardModel::$HouseType['RentalHouse'];
            $data['beginTime'] = strtotime($data['beginTime']);
            $data['endTime'] = strtotime($data['endTime']);
            $result = D('Card')->saveHouseCard($data);
            if($result)
            {
                $this->success('设置成功！');
            }
            else
            {
                $this->error('设置失败！');
            }
        }
        else
        {
            $id = i('id');
            $card = D('Card')->queryHouseCard($id, CardModel::$HouseType['RentalHouse']);
            if($card)
            {
                $card['beginTime'] = $card['beginTime']?date('Y-m-d',$card['beginTime']):'';
                $card['endTime'] = $card['endTime']?date('Y-m-d',$card['endTime']):'';
            }
            $this->assign('houseId', $id);
            $this->assign('card', $card);
            $this->assign('stateCaption', CardModel::$StateCaption);
            $this->display('card');
        }
    }

}