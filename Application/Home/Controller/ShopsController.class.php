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
use Common\Model\Shops;
use Think\Upload;
use Think\Log;

class ShopsController extends UserController
{
    private $userId;
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
        parent::_initialize();
    }

    public function index() {
        $data = array(
            'user_id'   =>  $this->userId,
            'weixin_no' =>  '15080811371',
            'shop_name' =>  '测试店铺',
            'shop_info' =>  '',
        );
        //D('Shops')->add($data);
        
        
        $this -> display();
    }
    
    public function apply4shop() {
        $this->assign('apply_type', '1');
        $this->display('apply');
    }
    
    public function apply4personal() {
        $this->assign('apply_type', '2');
        $this->display('apply');
    }
    
    public function edit_shop_info()
    {
        $shop_info = D('Shops')->where(array('user_id'=>$this->userId))->find();
        if(empty($shop_info) || $shop_info['status'] == 2)
        {
            $this->redirect('index', 5, '您没有店铺或被管理员封停');
        }
        $this->assign('shop_info', $shop_info);
        $this -> display('apply');
    }

    
    public function apply_shop() {
        $shop_info = D('Shops')->where(array('user_id'=>$this->userId))->find();
        if(!empty($shop_info) && $shop_info['request_flag'] != 1)
        {
            $apply_info = array(
                2 => '您的申请正在审核中..请耐心等待',
                3 => '您的审核不通过',
            );
            $this->error($apply_info[$shop_info['request_flag']]);
        }
        
        if(IS_POST) {
            $data = i('post.');
            $data['user_id'] = $this->userId;
            
            
            $return_msg = '申请成功';
            
            if(!empty($shop_info['id']))
            {
                $data['id'] = $shop_info['id'];
                $data['request_flag'] = $shop_info['request_flag'];
                $return_msg = '编辑成功';
            }
            else
            {
                $data['create_time'] = time();
            }
            D('Shops')->saveShops($data);
            
            $this->success($return_msg, '');
        }
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