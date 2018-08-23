<?php

namespace Home\Controller;

use Common\Model\RentalHouseModel;
use Think\Exception;
use Think\Model;
use Think\Controller;
use Common\Model\OldHouseModel;
use Common\Model\MediaModel;
use Common\Model\TypeModel;
use Common\Model\CityModel;
use Common\Model\HuXingModel;
use Common\Model\ZhuangXiuModel;
use Think\Upload;
use Think\Log;

class PublishController extends UserController
{
    public static $HouseTypes = array(
        'OldHouse' => '1',
        'RentalHouse' => '2',
    );

    public function index() {

        $oldHouseCount = D('OldHouse') -> countByUserId($this -> userId);
        $rentalHouseCount = D('RentalHouse') -> countByUserId($this -> userId);
        $this -> assign('publishCount', $oldHouseCount+$rentalHouseCount);
        $this -> display();
    }

    public function oldHouse() {
        $this -> assign("buildingTypeList", D("Type") -> findAll());
        $this -> assign("decorationTypeList", D('ZhuangXiu')->findAll());
        $this -> assign("directionList", OldHouseModel::$HouseDirectionList);
        $this -> assign("StateCaption", $this -> getOldHouseStateCaption());
        $this -> assign('districtList', D('City') -> queryAll());
        $oldHouse = D('OldHouse')->findById(I('id'));
        $images = D('Media') -> queryHousePics($oldHouse['id'], MediaModel::$HouseType['OldHouse']);
        $video = D('Media') -> queryHouseVideo($oldHouse['id'], MediaModel::$Type['Video']);
        $imagePaths = null;
        $videoPath = null;
        foreach($images as $image) {
            $imagePaths[] = $image['path'];
        }
        if ($video) {
            $videoPath = $video['path'];
        }
        $others = json_decode($oldHouse['others']);
        $this->assign('house', $oldHouse);
        $this -> assign('houseType', self::$HouseTypes['OldHouse']);
        $this->assign("imagePaths", $imagePaths);
        $this->assign('videoPath', $videoPath);
        $this->assign('others', $others);
        // var_dump($videoPath);
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this -> display();

    }
    
    // 服务下的添加公寓
    public function shopHouse() {
        $this->assign('shop_house', true);
        $this->rentalHouse();
    }

    public function delete() {
        $resData = array('status'=>'1', 'errMsg'=>'');
        $id = I('id');
        $type = I('type');
        try {
            if (!IS_POST) {
                throw new Exception('非法操作!');
            }
            if ($type == self::$HouseTypes['OldHouse']) {
                $result = D('OldHouse') -> deleteHouse($id);
                if ($result<1) {
                    throw new Exception('删除二手房失败!');
                }
                return $this -> ajaxReturn($resData);
            } else if ($type == self::$HouseTypes['RentalHouse']) {
                $result = D('RentalHouse') -> deleteHouse($id);
                if ($result<1) {
                    throw new Exception('删除出租房失败!');
                }
                return $this -> ajaxReturn($resData);
            } else {
                throw new Exception('非法房屋类型!');
            }
        } catch (Exception $e) {
            $resData['status'] = '0';
            $resData['errMsg'] = $e -> getMessage();
            return $this -> ajaxReturn($resData);
        }
    }

    public function oldHouse_step2() {

    }

    private function getOldHouseStateCaption() {
        $caption = null;
        $State = OldHouseModel::$State;
        foreach (OldHouseModel::$StateCaption as $key=>$item) {
            switch ($key) {
                case $State['OnSale']:
                case $State['SaleOut']:
                case $State['UnSale']:
                    $caption[$key] = $item;
            }
        }
        return $caption;
    }

    private function getRentalHouseStateCaption() {
        $caption = null;
        $State = RentalHouseModel::$State;
        foreach (RentalHouseModel::$StateCaption as $key=>$item) {
            switch ($key) {
                case $State['OnSale']:
                case $State['SaleOut']:
                case $State['UnSale']:
                    $caption[$key] = $item;
            }
        }
        return $caption;
    }

    public function oldHouseSubmit() {
        if(IS_POST) {
            $data = i('post.');
            $data['others'] = json_encode($data['others']);
            if (count($data['images'])) {
                $data['primaryImage'] = $data['images'][0];
            }
            $data['longitude'] = $data['longitude']?$data['longitude']:null;
            $data['latitude'] = $data['latitude']?$data['latitude']:null;
            $houseId = D('OldHouse') -> saveHouse($data, $this->userId);
            if($data['id'])
            {
                $houseId = $data['id'];
            }

            D('OldHouse') -> saveHouseMedias($houseId, $data['video'], $data['images']);
            $this->success('新增成功', '');
        }
    }

    public function rentalHouse() {
        $this -> assign("buildingTypeList", D("Type") -> findAll());
        $this -> assign("decorationTypeList", D('ZhuangXiu')->findAll());
        $this -> assign("rentalTypeList", RentalHouseModel::$RentalTypeCaption);
        $this -> assign("directionList", RentalHouseModel::$HouseDirectionList);
        $this -> assign("StateCaption", $this -> getRentalHouseStateCaption());
        $this -> assign('districtList', D('City') -> queryAll());
        $rentalHouse = D('RentalHouse')->findById(I('id'));
        $images = D('Media') -> queryHousePics($rentalHouse['id'], MediaModel::$HouseType['RentalHouse']);
        $video = D('Media') -> queryHouseVideo($rentalHouse['id'], MediaModel::$Type['Video']);
        $imagePaths = null;
        $videoPath = null;
        foreach($images as $image) {
            $imagePaths[] = $image['path'];
        }
        if ($video) {
            $videoPath = $video['path'];
        }
        $others = json_decode($rentalHouse['others']);
        $this->assign('house', $rentalHouse);
        $this -> assign('houseType', self::$HouseTypes['RentalHouse']);
        $this->assign("imagePaths", $imagePaths);
        $this->assign('videoPath', $videoPath);
        $this->assign('others', $others);
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this -> display('rentalHouse');
    }

    public function rentalHouseSubmit() {        
        if(IS_POST) {
            $data = i('post.');
            $data['others'] = json_encode($data['others']);
            if (count($data['images'])) {
                $data['primaryImage'] = $data['images'][0];
            }
            $data['longitude'] = $data['longitude']?$data['longitude']:null;
            $data['latitude'] = $data['latitude']?$data['latitude']:null;
            $data['source'] = RentalHouseModel::$Source['Personal'];
            
            // $user_info = get_user();
            
            // if($user_info['user_type'] == 1)
            // {
                $where = array(
                    'user_id'   =>  $this -> userId,
                    'request_flag'  =>  1,
                );
                $shop_info = D('Shops')->where($where)->find();
                if(!empty($shop_info) && !empty($data['is_shop_house']))
                {
                    $data['source'] = RentalHouseModel::$Source['shop_house'];
                    $data['shop_id'] = $shop_info['id'];
                    unset($data['is_shop_house']);
                }
            // }
            //print_r($data);exit;
            $houseId = D('RentalHouse') -> saveHouse($data, $this->userId);
            if($data['id'])
            {
                $houseId = $data['id'];
            }
            
            D('RentalHouse') -> saveHouseMedias($houseId, $data['video'], $data['images']);
            $this->success('新增成功', '');
        }
    }

    /**
     * 上传视频
     */
    public function uploadVideo()
    {
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        $setting['rootPath'] = "uploads/videos/";
        makeDir(ROOT_PATH."/".$setting['rootPath']);
        $Upload = new Upload($setting, "local", C("UPLOAD_LOCAL_CONFIG"));
        $info   = $Upload->upload($_FILES);
        // dump($info);
        if ($info) {
            $return['data'] = "/" . $setting['rootPath'] . $info['file']['savepath'] . $info['file']['savename'];
        }
        else {
            $return['info'] =  $Upload->getError();
            $return['status'] = 0;
        }
        Log::record(json_encode($return) , Log::INFO);
        $this->ajaxReturn($return);
    }

    /**
     * 上传图片
     */
    public function uploadPic()
    {
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        $setting['rootPath'] = "uploads/pictures/";
        $md = makeDir(ROOT_PATH."/".$setting['rootPath']);
        $Upload = new Upload($setting, "local", C("UPLOAD_LOCAL_CONFIG"));
        $info   = $Upload->upload($_FILES);
        if ($info) {
            foreach ($info as $k => $v) {
                 $return['data'][] = "/" . $setting['rootPath'] . $v['savepath'] . $v['savename'];
            }
            // $return['data'] = "/" . $setting['rootPath'] . $info['file']['savepath'] . $info['file']['savename'];
        }
        else {
            $return['info'] =  $Upload->getError();
            $return['status'] = 0;
        }
        Log::record(json_encode($return) , Log::INFO);
        $this->ajaxReturn($return);
    }

}