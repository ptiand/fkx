<?php

namespace Admin\Controller;

use Common\Model\MediaAdModel;

class MediaAdController extends AdminController
{
    public function index()
    {
        $this->assign('State', MediaAdModel::$State);
        $this->display();
    }
    public function loadData()
    {
        $where['state'] = array('NEQ', MediaAdModel::$State['Deleted']);
        $dataList = D('MediaAd')->where($where)->order('state ASC , id DESC ')->select();
        foreach($dataList as &$data)
        {
            $data['stateCaption'] = MediaAdModel::$StateCaption[$data['state']];
            $data['createTime'] = $data['createTime']>0?date('Y-m-d H:i:s',$data['createTime']):'-';
            $data['areaCaption'] = D('City')->findNameById($data['areaId']);
        }
        $count = D('MediaAd')
            ->where($where)
            -> count();
        $resArr = array("total"=>$count,"rows"=>$dataList?$dataList:array());
        echo json_encode($resArr);
    }
    public function add()
    {
        if(!IS_POST)
        {
            $topCities = D('City')->queryTopCity();
            $topCityMap = array();
            foreach ($topCities as $city)
            {
                $topCityMap[$city['id']] = $city['city_name'];
            }
            $this->assign('topCities', $topCityMap);
            $this->display();
        }
        else
        {
            $title = I('title');
            $cityId = I('cityId');
            $areaId = I('areaId');
            $filepath = I('video');
            $result = D('MediaAd')->saveNewMediaAd($title, $filepath, $cityId, $areaId);
            if($result)
            {
                $this->success('保存成功!');
            }
            else
            {
                $this->error('保存失败!');
            }
        }
    }
    public function off()
    {
        $id = I('id');
        if(!$id)
        {
            $this->error('下线失败!');
            return;
        }
        $mediaAd = D('MediaAd')->findMediaAdById($id);
        if(!$mediaAd)
        {
            $this->error('下线失败, 没有找到广告信息!');
            return;
        }
        $mediaAd['state'] = MediaAdModel::$State['Expired'];
        $result = D('MediaAd')->saveMediaAd($mediaAd);
        if($result)
        {
            $this->success('下线成功!');
        }
        else
        {
            $this->error('下线失败!');
        }
    }
}