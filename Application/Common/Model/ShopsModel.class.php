<?php
namespace Common\Model;

use Think\Model;

class ShopsModel extends Model
{
    protected $trueTableName = 'Shops';

    public static $RentalTypeCaption = array(
        '1'=>'押一付一',
        '2'=>'押一付二',
        '3'=>'押一付三',
        '9'=>'无需押金',
    );

    

    public function findById($id)
    {
        $where['id'] = $id;
        $house = D($this->trueTableName) -> where($where) -> find();
        return $house;
    }
    public function saveShops($data, $userId)
    {
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D($this->trueTableName) -> where($where) -> save($data);
        }
        else
        {
            return D($this->trueTableName)->add($data);
        }
    }
    public function saveHouseMedias($houseId, $video, $pics)
    {
        $houseVideo = D('Media')->queryHouseVideo($houseId, MediaModel::$HouseType['RentalHouse']);
        if($video != $houseVideo['path'])
        {
            if($video)
            {
                $videoData = array(
                    'houseId' => $houseId,
                    'houseType' => MediaModel::$HouseType['RentalHouse'],
                    'path' => $video,
                    'type' => MediaModel::$Type['Video'],
                    'isDel' => MediaModel::$IsDelete['NO']
                );
                D('Media')->saveMedia($videoData);
            }
            if($houseVideo)
            {
                D('Media')->deleteMedia($houseVideo['id']);
            }
        }
        $housePics = D('Media')->queryHousePics($houseId, MediaModel::$HouseType['RentalHouse']);
        $housePicMap = array();
        foreach ($housePics as $housePic)
        {
            $housePicMap[$housePic['path']] = $housePic;
        }
        if($pics)
        {
            foreach ($pics as $key=>$pic)
            {
                if($housePicMap[$pic])
                {
                    $data = $housePicMap[$pic];
                    $data['sort'] = $key+1;
                    D('Media')->saveMedia($data);
                    unset($housePicMap[$pic]);
                }
                else
                {
                    $data = array(
                        'houseId' => $houseId,
                        'houseType' => MediaModel::$HouseType['RentalHouse'],
                        'path' => $pic,
                        'type' => MediaModel::$Type['Pic'],
                        'isDel' => MediaModel::$IsDelete['NO'],
                        'sort' => $key+1
                    );
                    D('Media')->saveMedia($data);
                }
            }
        }
        foreach ($housePicMap as $key=>$pic)
        {
            D('Media')->deleteMedia($pic['id']);
        }
    }
    /**
     * 根据房屋id删除房屋
     * @param $id
     */
    public function deleteHouse($id)
    {
        $house = $this->findById($id);
        if($house)
        {
            $house['state'] = self::$State['Deleted'];
            return $this->saveHouse($house);
        }
    }
    /**
     * 查询推荐房源
     */
    public function queryRecommendHouse()
    {
        $where['state'] = array('NEQ', self::$State['Deleted']);
        $where['sort'] = array('GT', 0);
        $houses = D('RentalHouse')->where($where)->order('sort ASC, updateTime DESC ')->select();
        return $houses;
    }

    public function genConditionByPrice($price) {
        $PriceConstant = self::$Price;
        switch ($price) {
            case $PriceConstant['Under500']:
                return array('lt', 500);
            case $PriceConstant['500To800']:
                return array(array('gt', 500), array('lt', 800));
            case $PriceConstant['800To1000']:
                return array(array('gt', 800), array('lt', 1000));
            case $PriceConstant['1000To1500']:
                return array(array('gt', 1000), array('lt', 1500));
            case $PriceConstant['1500To2000']:
                return array(array('gt', 1500), array('lt', 2000));
            case $PriceConstant['2000To3000']:
                return array(array('gt', 2000), array('lt', 3000));
            case $PriceConstant['30000To5000']:
                return array(array('gt', 3000), array('lt', 5000));
            case $PriceConstant['Beyond5000']:
                return array('gt', 5000);
            default:
                return null;
        }
    }

    public function countByUserId($userId) {
        $where['userId'] = $userId;
        $where['state'] = array('NEQ', self::$State['Deleted']);
        return D('RentalHouse') -> where($where) -> count('id');
    }
}
