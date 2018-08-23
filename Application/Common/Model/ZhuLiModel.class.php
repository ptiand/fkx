<?php
namespace Common\Model;

use think\Model;

/**
 * 楼盘相册，我也不知道之前的开发为什么取名叫ZhuLi
 * Class ZhuLiModel
 * @package Common\Model
 */
class ZhuLiModel extends Model
{
    protected $trueTableName = 'zhuli';
    public static $IsDelete = array(
        'YES' => 1,
        'NO' => 0
    );
    public static $Type = array(
        'Pic' => 1,
        'HuXing' => 2,
        'Video' => 3
    );
    public function queryHouseVideo($houseId)
    {
        $where['loupan_id'] = $houseId;
        $where['type'] = self::$Type['Video'];
        $where['isDel'] = array('NEQ', self::$IsDelete['YES']);
        $video = D('ZhuLi')->where($where)->order('id DESC')->find();
        return $video;
    }
    public function queryHousePics($houseId)
    {
        $where['loupan_id'] = $houseId;
        $where['type'] = self::$Type['Pic'];
        $where['isDel'] = array('NEQ', self::$IsDelete['YES']);
        $pics = D('ZhuLi')->where($where)->order('sort ASC')->select();
        return $pics;
    }
    public function saveZhuLi($data)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('ZhuLi') -> where($where) -> save($data);
        }
        else
        {
            return D('ZhuLi')->add($data);
        }
    }
    public function deleteZhuLi($id)
    {
        $data['isDel'] = self::$IsDelete['YES'];
        $where['id'] = $id;
        return D('ZhuLi')->where($where)->save($data);
    }
}