<?php
namespace Common\Model;

use Think\Model;

class MediaAdModel extends Model
{
    protected $trueTableName = 'MediaAds';
    public static $State = array(
        'Active' => '1',
        'Expired' => '2',
        'Deleted' => '999'
    );
    public static $StateCaption = array(
        '1' => "有效",
        '2' => '已失效',
        '999' => '已删除'
    );

    public function saveMediaAd($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('MediaAd') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            $data['playCount'] = 0;
            $data['state'] = self::$State['Active'];
            return D('MediaAd')->add($data);
        }
    }
    public function findMediaAdById($id)
    {
        return $this->where(array('id' => $id))->find();
    }
    public function saveNewMediaAd($title, $filepath, $cityId, $areaId)
    {
        $data = array(
            'cityId' => $cityId,
            'areaId' => $areaId,
            'title' => $title,
            'filepath' => $filepath,
        );
        $currentMediaAd = $this->queryMediaAdByAreaId($areaId);
        if($currentMediaAd)
        {
            $currentMediaAd['state'] = self::$State['Expired'];
            $this->saveMediaAd($currentMediaAd);
        }
        return $this->saveMediaAd($data);
    }
    public function queryMediaAdByAreaId($areaId)
    {
        $where['state'] = self::$State['Active'];
        $where['areaId'] = $areaId;
        return $this->where($where)->order('id DESC')->find();
    }

}