<?php

namespace Home\Controller;

use Common\Model\CardModel;
use Common\Model\FavoriteModel;
use Common\Model\RentalHouseModel;
use Home\Service\LocationService;
use Think\Model;
use Think\Controller;
use Common\Model\MediaModel;
use Common\Model\TypeModel;
use Common\Model\CityModel;
use Common\Model\HuXingModel;
use Common\Model\ZhuangXiuModel;

class RentalHouseController extends Controller
{
    protected $userId = null;
    public function _initialize()
    {
        $user = get_user();
        if ($user) {
            $this->assign('isLogin', TRUE);
            $this->userId = $user['id'];
        } else {
            $this->assign('isLogin', FALSE);
            $path = $_SERVER["REQUEST_URI"];
            session("back" , $path);
        }
        $this->assign('houseType', "RentalHouse");
    }

    public function detail()
    {
        $id = I('id');
        $where['id'] = $id;
        $houseInfo = D('RentalHouse') -> findById($id);
        $houseInfo['stateCaption'] = RentalHouseModel::$StateCaption[$houseInfo['state']];
        $typeInfo = D('Type') -> findById($houseInfo['type']);
        $houseInfo['typeCaption'] = $typeInfo['type_name'];
        $houseInfo['updateTimeCaption'] = date('m月d日', $houseInfo['updateTime']);
        $houseInfo['renovationStandardCaption'] = D('ZhuangXiu')->findNameById($houseInfo['renovationStandard']);
        $houseInfo['rentalTypeCaption'] = RentalHouseModel::$RentalTypeCaption[$houseInfo['rentalType']];
        $houseInfo['sourceCaption'] = RentalHouseModel::$SourceCaption[$houseInfo['source']];
        $houseInfo['cityCaption'] = D('City')->findNameById($houseInfo['cityId']);
        $houseInfo['areaCaption'] = D('City')->findNameById($houseInfo['areaId']);;
        $video = D('Media') -> queryHouseVideo($houseInfo['id'], MediaModel::$HouseType['RentalHouse']);
        if ($video) {
            $houseInfo['video'] = $video['path'];
            //有视频时，才加载广告信息
            $mediaAd = D('MediaAd')->queryMediaAdByAreaId($houseInfo['areaId']);
            if($mediaAd)
            {
                $this->assign('mediaAd',$mediaAd);
            }
        }
        $pics = D('Media')->queryHousePics($id, MediaModel::$HouseType['RentalHouse']);
        $this->assign('pics', $pics);
        $tags = D('Tag')->queryRentalHouseTags();
        $tagNames = array();
        foreach ($tags as $tag)
        {
            $tagNames[$tag['id']] = $tag['name'];
        }
        $this->assign('tagNames', $tagNames);
        $otherArr = json_decode($houseInfo['others'], true);
        $otherArr['area'] = D('City')->findNameById($otherArr['sldzAreaId']);
        $this -> assign('others', $otherArr);
        $houseInfo['tags'] = json_decode($houseInfo['tags'], true);
        $this->assign('ptxxCaptions', RentalHouseModel::$PtxxOptions);
        $this->assign('house', $houseInfo);
        $this->assign('sources', RentalHouseModel::$Source);
        $card = D('Card')->queryHouseCard($id, CardModel::$HouseType['RentalHouse']);
        $this->assign('card', $card);
        $favorite = D('Favorite')->queryFavorite($this->userId, $id, FavoriteModel::$HouseType['RentalHouse']);
        $this->assign('favorite', $favorite);
        $this -> assign('userId', $this->userId);
        $this ->assign('houseUserId', $houseInfo['userId']);
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this -> display();
    }

    public function index() {
        $this -> assign('districtList', D('City') -> queryAll());
        $this -> assign('huxingList', RentalHouseModel::$HuxingList);
        $this -> assign('PriceList', RentalHouseModel::$PriceList);
        $this -> assign("buildingTypeList", D("Type") -> findAll());
        $this -> assign("sourceMap", RentalHouseModel::$Source);
        $this->assign('source',RentalHouseModel::$Source['Personal']);
        $this -> assign('keyWord', I("keyWord"));

        $this -> display();
    }
    
    public function shops()
    {
        
        $this -> display();
    }
    
    public function load_shops()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $page_size = 20;
        
        $where = array(
            'status'    =>  1,
            'request_flag'    =>  1,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  D('Shops')->where($where)->limit($offset,$page_size)->select();
        //print_r($list);exit;
        
        $count = D('Shops')-> where($where)->count();
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    
    public function shop_info()
    {
        $shop_id = I("shop_id") ? I("shop_id") : 0;
        
        $where = array('id' => $shop_id);
        $shop_info =  D('Shops')->where($where)->find();
        $this -> assign('shop_info', $shop_info);

        $this -> display();
    }
    
    public function load_Apartment()
    {
        $cur_page = I("cur_page") ? I("cur_page") : 1;
        $shop_id = I("shop_id") ? I("shop_id") : 1;
        $page_size = 20;
        
        $where = array(
            'source'    =>  2,
            'shop_id'    =>  $shop_id,
        );
        
        $offset = ($cur_page - 1) * $page_size;
        $list =  D('RentalHouse')->where($where)->limit($offset,$page_size)->select();
        //print_r($list);exit;
        
        $count = D('RentalHouse')-> where($where)->count();
        $list_array= array("total"=>$count, "cur_page"=>$cur_page, "rows"=>$list?$list:array());
        
        if(!empty($list_array['rows'])){
            $this->assign('Source', RentalHouseModel::$Source);
            $this->assign('houseList', $list_array['rows']);
            $list_array['rows'] = $this->fetch('listItem');
        }
        echo json_encode($list_array);
    }
    

    public function index2() {
        $this -> assign('districtList', D('City') -> queryAll());
        $this -> assign('huxingList', RentalHouseModel::$HuxingList);
        $this -> assign('PriceList', RentalHouseModel::$PriceList);
        $this -> assign("buildingTypeList", D("Type") -> findAll());
        $this -> assign("sourceMap", RentalHouseModel::$Source);
        $this->assign('source',RentalHouseModel::$Source['Apartment']);
        $this -> assign('keyWord', I("keyWord"));

        $this -> display();
    }

    public function listItem() {
        $filter = I("filter");
        $currentPage = I("currentPage");
        $pageSize = I("pageSize");
        $district = $filter['district'];
        $huxing = $filter['huxing'];
        $name = $filter['name'];
        $source = $filter['source'];
        $price = $filter['price'];
        $where['state'] = array('NEQ', RentalHouseModel::$State['Deleted']);
        if ($huxing != -1) {
            if ($huxing == RentalHouseModel::$Huxing['FiveAndGT']) {
                $where['houseRoom'] = array('EGT', $huxing);
            } else {
                $where['houseRoom'] = $huxing;
            }
        }
        if ($price['type'] == 1) {
            if ($price['price'] != -1) {
                $where['price'] = D('RentalHouse') -> genConditionByPrice($price['price']);
            }
        } else {
            $customPrice = $price['customPrice'];
            $where['price'] = array(
                array('EGT', $customPrice['min']>0?$customPrice['min']:0),
                array('ELT', $customPrice['max']>0?$customPrice['max']:9999999));
        }
        if ($district['area'] != -1) {
            $where['areaId'] = $district['area'];
        } else if ($district['city'] != -1) {
            $where['cityId'] = $district['city'];
        }
        if ($name != -1) {
            $where['name'] = array('like', "%" . $name . "%");
        }
        if ($source != -1) {
            $where['source'] = $source;
        }
        $houseList = $this -> getList($currentPage*$pageSize, 10, $where);
        if ($houseList) {
            $this->assign('Source', RentalHouseModel::$Source);
            $this->assign('houseList', $houseList);
            $this->success($this->fetch(), "", true);
        } else {
            $this->error('没有更多数据！');
        }
    }

    private function getList($start, $limit, $where) {
        $location = LocationService::getInstance()->getLocation();
        $houseList = array();
        if($location && $location['longitude'] && $location['latitude'])
        {
            $houseModule = D('RentalHouse');
            $field = '*';
            $orderField = '`updateTime` DESC';// ' 1/`sort` DESC, `updateTime` DESC'
            $field .= ', ABS(`longitude`-'.$location['longitude'].')+ABS(`latitude`-'.$location['latitude'].') as distance';
            $orderField = ' 1/`distance` DESC, '.$orderField;
            $houseList = $houseModule->field($field)-> where($where) -> limit($start, $limit) -> order($orderField) -> select();
            if(!$start)
            {
                $houseIds = array();
                foreach ($houseList as &$house)
                {
                    $houseIds[$house['id']] = true;
                }
                $where['sort'] = array('gt', 0);
                $jxHouseList = D('RentalHouse')->where($where)->order(' 1/`sort` DESC, `updateTime` DESC')->select();
                foreach($jxHouseList as $house)
                {
                    if(!$houseIds[$house['id']])
                    {
                        $houseList[] = $house;
                    }
                }
            }
        }
        else
        {
            //如果没有定位则采用默认的方式， 把精选排在最前面
            $houseModule = D('RentalHouse');
            $houseList = $houseModule-> where($where) -> limit($start, $limit) -> order(' 1/`sort` DESC, `updateTime` DESC') -> select() ;
        }

        // echo $houseModule->getLastSql();
        $typeList = D('Type') -> select();
        $tagList = D('Tag') -> queryRentalHouseTags();
        $tagMap = array();
        foreach ($tagList as $tag) {
            $tagMap[$tag['id']] = $tag['name'];
        }
        foreach ($houseList as &$house) {
            foreach ($typeList as $type) {
                if ($type['id'] == $house['type']) {
                    $house['typeCaption'] = $type['type_name'];
                }
            }
            $house['stateCaption'] = RentalHouseModel::$StateCaption[$house['state']];
            $house['tagList'] = array();
            foreach (json_decode($house['tags']) as $tagId) {
                $house['tagList'][] = $tagMap[$tagId];
            }
            $house['areaCaption'] = D('City') -> findNameById($house['areaId']);
            // $house['huxingRangeList'] = $this -> getHouseRangeList($house['id']);
        }
        // var_dump($houseList);
        return $houseList;
    }

}