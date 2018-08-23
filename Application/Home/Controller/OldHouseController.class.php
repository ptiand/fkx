<?php

namespace Home\Controller;

use Home\Service\LocationService;
use Think\Model;
use Think\Controller;
use Common\Model\OldHouseModel;
use Common\Model\MediaModel;
use Common\Model\TypeModel;
use Common\Model\CityModel;
use Common\Model\HuXingModel;
use Common\Model\ZhuangXiuModel;
use Common\Model\FavoriteModel;

class OldHouseController extends Controller
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
        $this->assign('houseType', "OldHouse");
    }

    public function detail()
    {
        $id = I('id');
        $where['id'] = $id;
        $houseInfo = D('OldHouse') -> findById($id);
        $houseInfo['stateCaption'] = OldHouseModel::$StateCaption[$houseInfo['state']];
        $typeInfo = D('Type') -> findById($houseInfo['type']);
        $houseInfo['typeCaption'] = $typeInfo['type_name'];
        $houseInfo['saleTimeCaption'] = date('Y年m月d日', $houseInfo['saleTime']);
        $houseInfo['renovationStandardCaption'] = D('ZhuangXiu')->findNameById($houseInfo['renovationStandard']);
        $houseInfo['cityCaption'] = D('City')->findNameById($houseInfo['cityId']);
        $houseInfo['areaCaption'] = D('City')->findNameById($houseInfo['areaId']);
        $video = D('Media') -> queryHouseVideo($houseInfo['id'], MediaModel::$HouseType['OldHouse']);
        if ($video) {
            $houseInfo['video'] = $video['path'];
            //有视频时，才加载广告信息
            $mediaAd = D('MediaAd')->queryMediaAdByAreaId($houseInfo['areaId']);
            if($mediaAd)
            {
                $this->assign('mediaAd',$mediaAd);
            }
        }
        $pics = D('Media')->queryHousePics($id, MediaModel::$HouseType['OldHouse']);
        $this->assign('pics', $pics);
        $tags = D('Tag')->queryOldHouseTags();
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
        $this->assign('house', $houseInfo);
        $favorite = D('Favorite')->queryFavorite($this->userId, $id, FavoriteModel::$HouseType['OldHouse']);
        $this->assign('favorite', $favorite);
        $this -> assign('userId', $this->userId);
        $this ->assign('houseUserId', $houseInfo['userId']);
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this -> display();
    }

    public function index() {
        $this -> assign('districtList', D('City') -> queryAll());
        $this -> assign('totalPriceList', OldHouseModel::$TotalPriceList);
        $this -> assign('huxingList', OldHouseModel::$HuxingList);
        $this -> assign("decorationTypeList", D('ZhuangXiu')->findAll());
        $this -> assign("buildingTypeList", D("Type") -> findAll());
        $this -> assign('keyWord', I("keyWord"));

        $this -> display();
    }

    public function listItem() {
        $filter = I("filter");
        $currentPage = I("currentPage");
        $pageSize = I("pageSize");
        $district = $filter['district'];
//        dump($district);
        $totalPrice = $filter['totalPrice'];
        $huxing = $filter['huxing'];
        $decoration = $filter['decoration'];
        $type = $filter['type'];
        $name = $filter['name'];
        $where['state'] = array('NEQ', OldHouseModel::$State['Deleted']);
        $totalPriceWhere = null;
        if ($totalPrice != -1) {
            $totalPriceWhere = D('OldHouse') -> genWhereByTotalPrice($totalPrice);
        }
        if ($totalPriceWhere) {
            $where['_complex'] = $totalPriceWhere;
        }
        if ($decoration != -1) {
            $where['renovationStandard'] = $decoration;
        }
        if ($type != -1) {
            $where['type'] = $type;
        }
        if ($district['area'] != -1) {
            $where['areaId'] = $district['area'];
        } else if ($district['city'] != -1) {
            $where['cityId'] = $district['city'];
        }
        if ($huxing != -1) {
            if ($huxing == OldHouseModel::$Huxing['FiveAndGT']) {
                $where['houseRoom'] = array('EGT', $huxing);
            } else {
                $where['houseRoom'] = $huxing;
            }
        }
        if ($name != -1) {
            $where['name'] = array('like', "%" . $name . "%");
        }

        $houseList = $this -> getList($currentPage*$pageSize, 10, $where);
        if ($houseList) {
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
            $houseModule = D('OldHouse');
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
                $jxHouseList = D('OldHouse')->where($where)->order(' 1/`sort` DESC, `updateTime` DESC')->select();
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
            $houseModule = D('OldHouse');
            $houseList = $houseModule-> where($where) -> limit($start, $limit) -> order(' 1/`sort` DESC, `updateTime` DESC') -> select() ;
        }

//         echo D('OldHouse')->getLastSql();
        $typeList = D('Type') -> select();
        $tagList = D('Tag') -> queryOldHouseTags();
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
            $house['stateCaption'] = OldHouseModel::$StateCaption[$house['state']];
            $house['tagList'] = array();
            foreach (json_decode($house['tags']) as $tagId) {
                $house['tagList'][] = $tagMap[$tagId];
            }
            $house['areaCaption'] = D('City') -> findNameById($house['areaId']);
        }
        // var_dump($houseList);
        return $houseList;
    }

}