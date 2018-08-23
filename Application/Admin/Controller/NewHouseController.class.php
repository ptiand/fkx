<?php


namespace Admin\Controller;
use Common\Model\CardModel;
use Common\Model\NewHouseModel;
use Common\Model\TypeModel;

class NewHouseController extends AdminController
{
    public function index()
    {
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this->display();
    }
    public function loadNewHouse()
    {
        $condition['state'] = array('NEQ', NewHouseModel::$State['Deleted']);
        $offset = i("offset");
        $limit = i("limit");
        $search = i('search');
        if($search){
            $condition['name'] = array('LIKE',"%$search%");;
        }
        $newHouses =  D('NewHouse')
            -> where($condition)
            //-> order($reorder)
            ->limit($offset,$limit)
            -> select();
        foreach($newHouses as &$house)
        {
            $house['stateCaption'] = NewHouseModel::$StateCaption[$house['state']];
            $house['saleTimeCaption'] = date('Y-m-d',$house['saleTime']);
            $house['cityCaption'] = D('City')->findNameById($house['cityId']);
            $house['areaCaption'] = D('City')->findNameById($house['areaId']);
            //$house['huxingCaption'] = D('HuXing')->findNameById($house['huxingId']);
            $house['typeCaption'] = D('Type')->findNameById($house['type']);
        }
        $count =  D('NewHouse')
            -> where($condition)
            -> count();
        $resArr = array("total"=>$count,"rows"=>$newHouses?$newHouses:array());

        echo json_encode($resArr);
    }
    public function add()
    {
        if(IS_POST)
        {
            $data = i('post.');
            $data['saleTime'] = strtotime($data['saleTime']);
            $data['priceUpdateTime'] = strtotime($data['priceUpdateTime']);
            $data['tags'] = $data['tags'] ? json_encode($data['tags']) : json_encode(array());
            $data['minPrice'] = $data['minPrice']? $data['minPrice'] : 0;
            $data['maxPrice'] = $data['maxPrice']? $data['maxPrice'] : 0;
            $data['minTotalPrice'] = $data['minTotalPrice']? $data['minTotalPrice'] : 0;
            $data['maxTotalPrice'] = $data['maxTotalPrice']? $data['maxTotalPrice'] : 0;
            $data['square'] = $data['square']? $data['square'] : 0;
            $pics = i('pics');
            if($pics)
            {
                $data['primaryImage'] = $pics[0];
            }
            else {
                $data['primaryImage'] = '';
            }
            $savedId = D('NewHouse')->saveHouse($data);
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
        D('NewHouse')->saveHouseMedias($houseId, $video, $pics);
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
        unset(NewHouseModel::$StateCaption[NewHouseModel::$State['Deleted']]);
        $this->assign('houseState', NewHouseModel::$StateCaption);
        $tags = D('Tag')->queryNewHouseTags();
        $this->assign('tags', $tags);
    }
    public function edit()
    {
        $id = i('id');
        $house = D('NewHouse')->findById($id);
        if(IS_POST)
        {
            $data = i('post.');
            $data['saleTime'] = strtotime($data['saleTime']);
            $data['priceUpdateTime'] = strtotime($data['priceUpdateTime']);
            $data['tags'] = $data['tags'] ? json_encode($data['tags']) : json_encode(array());
            $data['minPrice'] = $data['minPrice']? $data['minPrice'] : 0;
            $data['maxPrice'] = $data['maxPrice']? $data['maxPrice'] : 0;
            $data['minTotalPrice'] = $data['minTotalPrice']? $data['minTotalPrice'] : 0;
            $data['maxTotalPrice'] = $data['maxTotalPrice']? $data['maxTotalPrice'] : 0;
            $data['longitude'] = $data['longitude']? $data['longitude'] : 0;
            $data['latitude'] = $data['latitude']? $data['latitude'] : 0;
            $data['square'] = $data['square']? $data['square'] : 0;
            $pics = i('pics');
            if($pics)
            {
                $data['primaryImage'] = $pics[0];
            }
            else {
                $data['primaryImage'] = '';
            }
            $saved = D('NewHouse')->saveHouse($data);
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
            $house['saleTime'] = $house['saleTime']>0?date('Y-m-d',$house['saleTime']):'';
            $house['priceUpdateTime'] = $house['priceUpdateTime']>0?date('Y-m-d',$house['priceUpdateTime']):'';
            $this->assign('house', $house);
            $this->loadDatas();
            $houseVideo = D('ZhuLi')->queryHouseVideo($id);
            $this->assign('houseVideo', $houseVideo);
            $housePics = D('ZhuLi')->queryHousePics($id);
            $this->assign('housePics', $housePics);
            $this->assign('houseTags', json_decode($house['tags']));
            $this->display('detail');
        }
    }
    public function editExtra()
    {
        $id = i('id');
        $house = D('NewHouse')->findById($id);
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
            $huxingPrefix = 'huxing_';
            $huxignPreLength = strlen($huxingPrefix);
            $huxingArr = array();
            foreach ($data as $key=>$value)
            {
                if(strpos($key, $extraPrefix) === 0)
                {
                    $otherArray[substr($key, $preLength)] = $value;
                }
                if(strpos($key, $huxingPrefix) === 0)
                {
                    //户型字段
                    $fieldName = substr($key, $huxignPreLength);
                    foreach ($value as $huxingKey=>$huxingValue)
                    {
                        $huxingArr[$huxingKey][$fieldName] = $huxingValue;
                    }
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
            $otherArray['contacts'] = $contacts;
            $house['others'] = json_encode($otherArray);
            $saved = D('NewHouse')->saveHouse($house);
            D('NewHouse')->saveHouseHuxing($id, $huxingArr);
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
            $houseHuxings = D('HouseHuxing')->queryHouseHuxing($id);
            $this->assign('houseHuxings', $houseHuxings);
            $this->assign('house', $house);
            $this->assign('extra', json_decode($house['others'], true));
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
                D('NewHouse')->deleteHouse($id);
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
            $data['houseType'] = CardModel::$HouseType['NewHouse'];
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
            $card = D('Card')->queryHouseCard($id, CardModel::$HouseType['NewHouse']);
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