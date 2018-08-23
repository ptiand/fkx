<?php
namespace Common\Model;

use Think\Model;

class ReportFollowupModel extends Model
{
    protected $trueTableName = 'followup';
    public function saveFollowup($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Followup') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            return D('Followup')->add($data);
        }
    }
    public function queryFollowup($reportId)
    {
        $where['report_id'] = $reportId;
        return D('Followup')->where($where)->order('id desc')->select();
    }
}