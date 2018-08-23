<?php

namespace Home\Controller;
use Common\Model\ReportModel;

/**
 * 客户报备控制器
 * Class ReportController
 * @package Home\Controller
 */
class ReportController extends UserController
{
    public function index()
    {
        $where['user_id'] = $this->userId;
        $result = D('Report')->where($where)->field('count(*) as count, status')->group('status')->select();
        $countMap = array();
        foreach ($result as $row)
        {
            $countMap[$row['status']] = $row['count'];
        }
        $this->assign('countMap', $countMap);
        $total = 0;
        foreach ($countMap as $c)
        {
            $total += $c;
        }
        $this->assign('status', I('status',''));
        $this->assign('total',$total);
        $this->assign('StatusCaption', ReportModel::$StatusCaption);
        $this->display('report_list');
    }
    public function loadData()
    {
        $currentPage = I("currentPage", 1);
        $pageSize = I("pageSize", 10);
        $status = I('status','');
        $where['user_id'] = $this->userId;
        $start = ($currentPage-1)*$pageSize;
        if($status)
        {
            $where['status'] = $status;
        }
        $reports = D('Report')->where($where)->order('id desc')->limit($start, $pageSize)->select();
        if($reports){
            $newHouseIds = array();
            $newHouseNames = array();
            $oldHouseIds = array();
            $oldHouseNames = array();
            $rentalHouseIds = array();
            $rentalHouseNames = array();
            foreach ($reports as $report)
            {
                switch ($report['houseType'])
                {
                    case ReportModel::$HouseType['NewHouse']:
                        $newHouseIds[] = $report['loupan_id'];
                        break;
                    case ReportModel::$HouseType['OldHouse']:
                        $oldHouseIds[] = $report['loupan_id'];
                        break;
                    case ReportModel::$HouseType['RentalHouse']:
                        $rentalHouseIds[] = $report['loupan_id'];
                        break;
                }
            }
            if(count($newHouseIds) > 0)
            {
                $where = array();
                $where['id'] = array('IN', $newHouseIds);
                $newHouseNames = D('NewHouse')->where($where)->getField('id, name');
            }
            if(count($oldHouseIds) > 0)
            {
                $where = array();
                $where['id'] = array('IN', $oldHouseIds);
                $oldHouseNames = D('OldHouse')->where($where)->getField('id, name');
            }
            if(count($rentalHouseIds) > 0)
            {
                $where = array();
                $where['id'] = array('IN', $rentalHouseIds);
                $rentalHouseNames = D('RentalHouse')->where($where)->getField('id, name');
            }
            foreach ($reports as &$report)
            {
                switch ($report['houseType'])
                {
                    case ReportModel::$HouseType['NewHouse']:
                        $report['houseName'] = $newHouseNames[$report['loupan_id']];
                        break;
                    case ReportModel::$HouseType['OldHouse']:
                        $report['houseName'] = $oldHouseNames[$report['loupan_id']];
                        break;
                    case ReportModel::$HouseType['RentalHouse']:
                        $report['houseName'] = $rentalHouseNames[$report['loupan_id']];
                        break;
                    case ReportModel::$HouseType['Loupan']:
                        $report['houseName'] = M('loupan') -> where(array('id'=>$report['loupan_id'])) -> getField('title');
                        break;
                }
                $followups = D('ReportFollowup')->queryFollowup($report['id']);
                $report['followups'] = $followups;
            }
            $this->assign('reports', $reports);
            $this->assign('Status', ReportModel::$Status);
            $this->assign('StatusCaption', ReportModel::$StatusCaption);
            $this->success(array(
                'hasMore' => count($reports)>=$pageSize,
                'html' => $this->fetch('report_listItem')
            ),'', true);
        }
        else
        {
            $this->success(array(
                'hasMore' => false
            ),'', true);
        }
    }
    public function report()
    {
        if(!IS_POST)
        {
            $this->assign('HuxingCaption', ReportModel::$HuxingCaption);
            $this->assign('SexCaption', ReportModel::$SexCaption);
            $this->assign('ZhiyeCaption', ReportModel::$ZhiYeCaption);
            $this->display();
            return;
        }
        $houseId = I('houseId');
        $houseType = I('houseType');
        $username = I('report_username');
        $phone = I('report_phone');
        $sex = I('report_sex', 0);
        $content = I('report_content');
        $zhiye = I('report_zhiye');
        $huxing = I('report_huxing');
        $data = array();
        $data['user_id'] = $this->userId;
        $data['loupan_id'] = $houseId;
        $data['houseType'] = ReportModel::$HouseType[$houseType];
        $data['nick_name'] = $username;
        $data['phone'] = $phone;
        $data['sex'] = $sex;
        $data['node'] = $content;
        $data['zhiye'] = $zhiye;
        $data['huxing'] = $huxing;
        $result = D('Report')->saveReport($data);
        if($result)
        {
            $this->success('报备成功！');
        }
        else
        {
            $this->error('报备失败！');
        }
    }
    public function detail()
    {
        $id = I('id');
        $report = D('Report')->findById($id);
        switch ($report['houseType'])
        {
            case ReportModel::$HouseType['NewHouse']:
                $report['houseUrl'] = U('Lists/newHouseDetail', array('id'=>$report['loupan_id']));
                $report['houseName'] = D('NewHouse')->where(array('id'=>$report['loupan_id']))->getField('name');
                break;
            case ReportModel::$HouseType['OldHouse']:
                $report['houseUrl'] = U('OldHouse/detail', array('id'=>$report['loupan_id']));
                $report['houseName'] = D('OldHouse')->where(array('id'=>$report['loupan_id']))->getField('name');
                break;
            case ReportModel::$HouseType['RentalHouse']:
                $report['houseUrl'] = U('RentalHouse/detail', array('id'=>$report['loupan_id']));
                $report['houseName'] = D('RentalHouse')->where(array('id'=>$report['loupan_id']))->getField('name');
                break;
            case ReportModel::$HouseType['Loupan']:
                $report['houseName'] = M('loupan') -> where(array('id'=>$report['loupan_id'])) -> getField('title');
                break;
        }
        $followups = D('ReportFollowup')->queryFollowup($report['id']);
        $report['followups'] = $followups;
        $this->assign('report', $report);
        $this->assign('Status', ReportModel::$Status);
        $this->assign('StatusCaption', ReportModel::$StatusCaption);
        $this->assign('HouseType', ReportModel::$HouseType);
        $this->assign('HuxingCaption', ReportModel::$HuxingCaption);
        $this->assign('ZhiyeCaption', ReportModel::$ZhiYeCaption);
        $this->display('report_detail');
    }
}