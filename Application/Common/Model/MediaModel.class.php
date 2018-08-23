<?php
namespace Common\Model;

use Think\Model;

class MediaModel extends Model
{
    protected $trueTableName = 'Medias';
    public static $HouseType = array(
        'OldHouse' => '2',
        'RentalHouse' => '3'
    );
    public static $HouseTypeCaption = array(
        '2' => '二手房',
        '3' => '出租房'
    );
    public static $IsDelete = array(
        'YES' => 1,
        'NO' => 0
    );
    public static $Type = array(
        'Pic' => 1,
        'Video' => 2
    );
    public function queryHouseVideo($houseId, $houseType)
    {
        $where['houseId'] = $houseId;
        $where['type'] = self::$Type['Video'];
        $where['houseType'] = $houseType;
        $where['isDel'] = array('NEQ', self::$IsDelete['YES']);
        $video = D('Media')->where($where)->order('id DESC')->find();
        return $video;
    }
    public function queryHousePics($houseId, $houseType)
    {
        $where['houseId'] = $houseId;
        $where['type'] = self::$Type['Pic'];
        $where['houseType'] = $houseType;
        $where['isDel'] = array('NEQ', self::$IsDelete['YES']);
        $pics = D('Media')->where($where)->order('sort ASC')->select();
        return $pics;
    }
    public function saveMedia($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Media') -> where($where) -> save($data);
        }
        else
        {
            return D('Media')->add($data);
        }
    }
    public function deleteMedia($id)
    {
        $data['isDel'] = self::$IsDelete['YES'];
        $where['id'] = $id;
        return D('Media')->where($where)->save($data);
    }

    public function queryAll($houseId, $houseType) {
        $where['isDel'] = array('neq', self::$IsDelete);
        return D('Media')->where($where)->select();
    }
}