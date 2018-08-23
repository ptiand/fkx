<?php

namespace Home\Controller;

use Common\Model\CardModel;
use Common\Model\FavoriteModel;
use Home\Service\LocationService;
use Think\Model;
use Think\Controller;
use Common\Model\NewHouseModel;
use Common\Model\TypeModel;
use Common\Model\ZhuLiModel;
use Common\Model\CityModel;
use Common\Model\HuXingModel;
use Common\Model\ZhuangXiuModel;

class ListsController extends Controller
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
        $this->assign('houseType', "NewHouse");
    }

    public function index()
    {
        session('key', 2);
        //查询楼盘
        //查询总价、
        $where['is_del'] = 0;
        $where['status'] = 1;
        $data = I('get.');
        if (isset($data['hx']) && !empty($data['hx'])) {
            $where['huxing_id'] = $data['hx'];
        }
        if (isset($data['zx']) && !empty($data['zx'])) {
            $where['zxiu_id'] = $data['zx'];
        }
        if (isset($data['zj']) && !empty($data['zj'])) {
            $where['jiage_id'] = $data['zj'];
        }
        if (isset($data['ty']) && !empty($data['ty'])) {
            $where['type_id'] = $data['ty'];
        }
        if (isset($data['cy']) && !empty($data['cy'])) {
            $where['area_id'] = $data['cy'];
        }
        if (isset($data['sh']) && !empty($data['sh'])) {
            $where['title|address'] = array('like', "%{$data['sh']}%");
        }
        $info = M('loupan')->where($where)->order('sort desc')->limit(0, 5)->select();
        $jiage = M('jiage')->where(['is_del' => 0])->select();
        $huxings = M('huxing')->where(['is_del' => 0])->select();
        $zxiu = M('zxiu')->where(['is_del' => 0])->select();
        $types = M('types')->where(['is_del' => 0])->select();
        $city = M('citys')->where(['is_del' => 0, 'pid' => 0])->select();
        foreach ($city as &$v) {
            $v['son'] = M('citys')->where(['is_del' => 0, 'pid' => $v['id']])->select();
        }
        //	$u_info = $_SERVER['QUERY_STRING'];

        //	unset($hx_arr['hx']);

        parse_str($_SERVER['QUERY_STRING'], $arr);
        $sh = $cy = $zj = $ty = $zx = $huxing = $arr;
        unset($huxing['hx']);
        $url['hx'] = '/index.php/Home/Lists/index?' . http_build_query($huxing);
        unset($zx['zx']);
        $url['zx'] = '/index.php/Home/Lists/index?' . http_build_query($zx);
        unset($ty['ty']);
        $url['ty'] = '/index.php/Home/Lists/index?' . http_build_query($ty);
        unset($zj['zj']);
        $url['zj'] = '/index.php/Home/Lists/index?' . http_build_query($zj);
        unset($cy['cy']);
        $url['cy'] = '/index.php/Home/Lists/index?' . http_build_query($cy);
        unset($sh['sh']);
        $url['sh'] = '/index.php/Home/Lists/index?' . http_build_query($sh);
        $pagewhere = http_build_query($arr);
        $this->assign('jiage', $jiage);
        $this->assign('huxing', $huxings);
        $this->assign('zxiu', $zxiu);
        $this->assign('types', $types);
        $this->assign('info', $info);
        $this->assign('city', $city);
        $this->assign('url', $url);
        $this->assign('pagewhere', $pagewhere);
        $this->display();
    }

    public function loadloupan()
    {
        $where['is_del'] = 0;
        $where['status'] = 1;
        $count = I('time');
        $pagewhere = I('where');
        parse_str($pagewhere, $data);
        if (isset($data['hx']) && !empty($data['hx'])) {
            $where['huxing_id'] = $data['hx'];
        }
        if (isset($data['zx']) && !empty($data['zx'])) {
            $where['zxiu_id'] = $data['zx'];
        }
        if (isset($data['zj']) && !empty($data['zj'])) {
            $where['jiage_id'] = $data['zj'];
        }
        if (isset($data['ty']) && !empty($data['ty'])) {
            $where['type_id'] = $data['ty'];
        }
        if (isset($data['cy']) && !empty($data['cy'])) {
            $where['area_id'] = $data['cy'];
        }
        if (isset($data['sh']) && !empty($data['sh'])) {
            $where['title|address'] = array('like', "%{$data['sh']}%");
        }
        $info = M('loupan')->where($where)->order('sort desc')->limit(5 * $count, 5)->select();
        //   dump($project);
//        dump($info);
        if ($info) {
            $this->assign('info', $info);
            $this->success($this->fetch(), "", true);
        } else {
            $this->error('没有更多数据！');
        }
    }

    public function lpinfo()
    {

        $user = get_user();
        if (!$user) {
            $this->redirect('Login/code_login');
        }
        $id = I('id');
        $where['id'] = $id;
        $info = M('loupan')->where($where)->find();
        $info['city'] = M('citys')->where(['id' => $info['city_id']])->getField('city_name');
        $info['tag'] = explode(';', $info['tag']);
        //处处相应的佣金规则 跟 合作规则
        $cooperation = M('cooperation')->where(['loupan_id' => $id])->find();
//		$cooperation['des'] = explode(';',$cooperation['des']);
        //佣金规则
        $commission = M('commission')->where(['loupan_id' => $id])->find();
//		$commission['des'] = explode(';',$commission['des']);
        //查出轮播图
        $luobo = M('zhuli')->where(['loupan_id' => $id, 'type' => 1])->select();
        //   dump($luobo);
        $huxing = M('zhuli')->where(['loupan_id' => $id, 'type' => 2])->select();
        $this->assign('luobo', $luobo);
        $this->assign('huxing', $huxing);
        $this->assign('info', $info);
        $this->assign('cooperation', $cooperation);
        $this->assign('commission', $commission);
        $this->display();
    }

    public function detail()
    {
        $data = I('get.');
        if ($data['type'] == 1) {
            $table = 'commission';
        } else {
            $table = 'cooperation';
        }
        $info = M($table)->where(['id' => $data['pid']])->find();
        $info['info'] = explode(';', $info['contents']);
        $info['type'] = $data['type'];
        $this->assign('info', $info);
        $this->display();
    }

    public function newHouseDetail()
    {
        $id = I('id');
        $where['id'] = $id;
        $houseInfo = D('NewHouse') -> findById($id);
        $houseInfo['stateCaption'] = NewHouseModel::$StateCaption[$houseInfo['state']];
        $typeInfo = D('Type') -> findById($houseInfo['type']);
        $houseInfo['typeCaption'] = $typeInfo['type_name'];
        $houseInfo['priceUpdateTimeCaption'] = date('m月d日', $houseInfo['priceUpdateTime']);
        $houseInfo['saleTimeCaption'] = $houseInfo['saleTime']>0?date('Y年m月d日', $houseInfo['saleTime']):'';
        $houseInfo['renovationStandardCaption'] = D('ZhuangXiu')->findNameById($houseInfo['renovationStandard']);
        $houseInfo['cityCaption'] = D('City')->findNameById($houseInfo['cityId']);
        $houseInfo['areaCaption'] = D('City')->findNameById($houseInfo['areaId']);
        $video = D('ZhuLi') -> queryHouseVideo($houseInfo['id']);
        if ($video) {
            $houseInfo['video'] = $video['pic'];
            //有视频时，才加载广告信息
            $mediaAd = D('MediaAd')->queryMediaAdByAreaId($houseInfo['areaId']);
            if($mediaAd)
            {
                $this->assign('mediaAd',$mediaAd);
            }
        }
        $pics = D('ZhuLi')->queryHousePics($id);
        $this->assign('pics', $pics);
        $tags = D('Tag')->queryNewHouseTags();
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
        $houseHuxingList = D('HouseHuxing')->queryHouseHuxing($id);
        $this->assign('houseHuxingList', $houseHuxingList);
        $this->assign('house', $houseInfo);
        $card = D('Card')->queryHouseCard($id, CardModel::$HouseType['NewHouse']);
        $this->assign('card', $card);
        $favorite = D('Favorite')->queryFavorite($this->userId, $id, FavoriteModel::$HouseType['NewHouse']);
        $this->assign('favorite', $favorite);
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this -> display();
    }

    public function index2() {
        $this -> assign('districtList', D('City') -> queryAll());
        $this -> assign('totalPriceList', NewHouseModel::$TotalPriceList);
        $this -> assign('huxingList', NewHouseModel::$huxingList);
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
        $where['state'] = array('NEQ', NewHouseModel::$State['Deleted']);
        $totalPriceWhere = null;
        if ($totalPrice != -1) {
            $totalPriceWhere = D('NewHouse') -> genWhereByTotalPrice($totalPrice);
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
            $where['huxing'] = $huxing;
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
        $houseList = null;
        $houseModule = null;
        if(!$where['huxing']) {
            $houseModule = D('NewHouse');
        } else {
            $Model = new Model();
            $houseModule =
                $Model ->
                table('(SELECT nh.*,hh.newHouseId, hh.houseRoom AS huxing FROM NewHouses nh LEFT JOIN HouseHuxings hh ON nh.id=hh.newHouseId) t')
                    -> group('newHouseId');
        }
        $location = LocationService::getInstance()->getLocation();
        $houseList = array();
        if($location && $location['longitude'] && $location['latitude'])
        {
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
                $Model = new Model();
                $jxHouseList = $Model ->table('(SELECT nh.*,hh.newHouseId, hh.houseRoom AS huxing FROM NewHouses nh LEFT JOIN HouseHuxings hh ON nh.id=hh.newHouseId) t')
                    -> group('newHouseId')->where($where)->order(' 1/`sort` DESC, `updateTime` DESC')->select();
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
            $houseList = $houseModule-> where($where) -> limit($start, $limit) -> order(' 1/`sort` DESC, `updateTime` DESC') -> select();
        }

//         echo D('NewHouse')->getLastSql();
        $typeList = D('Type') -> select();
        $tagList = D('Tag') -> queryNewHouseTags();
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
            $house['stateCaption'] = NewHouseModel::$StateCaption[$house['state']];
            $house['tagList'] = array();
            foreach (json_decode($house['tags']) as $tagId) {
                $house['tagList'][] = $tagMap[$tagId];
            }
            $house['areaCaption'] = D('City') -> findNameById($house['areaId']);
            $ranges = D('NewHouse')->queryHouseHuxingRange($house['id']);
            foreach ($ranges as $key=>$v)
            {
                $house[$key] = $v;
            }
        }
        // var_dump($houseList);
        return $houseList;
    }

    public function listArea() {
        $pid = I('pid');
        $areas = D('City') -> queryCityChildren($pid);
        $this->assign('areaList', $areas);
        $this->success($this->fetch(), "", true);
    }

    public function ajaxAllCity() {
        $cities = D('City') -> queryAll();
        $this -> ajaxReturn($cities);
    }

    public function ajaxTotalPrice() {
        $this -> ajaxReturn(NewHouseModel::$TotalPriceCaption);
    }

//    public function ImageGrid() {
//        $id = I('id');
//
//        $where['id'] = $id;
//        $houseInfo = D('NewHouse') -> findById($id); // 为什么没有这一行就报错？
//        $video = D('ZhuLi') -> queryHouseVideo($id);
//        $pics = D('ZhuLi')->queryHousePics($id);
//        $this -> assign('video', $video);
//        $this->assign('pics', $pics);
//        $this->assign('length', $video ? count($pics)+1 : count($pics));
//        $this -> display();
//    }
//
//    public function HuxingGrid() {
//        $id = I('id');
//        dump($id);
//        exit();
//        $huxingList = D('HouseHuxing') -> queryHouseHuxing($id);
//        $this->assign('huxingList', $huxingList);
//        $this -> display();
//    }

}