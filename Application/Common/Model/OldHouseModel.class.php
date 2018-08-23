<?php
namespace Common\Model;

use Think\Model;

class OldHouseModel extends Model
{
    protected $trueTableName = 'OldHouses';

    public static $PublishType = '1';

    public static $IsLocked = array(
        'Normal' => '1',
        'Locked' => '2'
    );

    public static $State = array(
        'OnSale'=>1,
        'ForSale'=>2,
        'SaleOut'=>3,
        'UnSale'=>4,
        'Deleted'=>9
    );
    public static $StateCaption = array(
        '1'=>'在售',
        '2'=>'待售',
        '3'=>'售罄',
        '4'=>'下架',
        '9'=>'删除'
    );

    public static $TotalPrice = array(
        'Under40Wan' => '1',
        '40WanTo60Wan' => '2',
        '60WanTo80Wan' => '3',
        '80WanTo100Wan' => '4',
        '100WanTo140Wan' => '5',
        '140WanTo180Wan' => '6',
        'Beyond180Wan' => '7',
    );

    public static $TotalPriceList = array(
        array('value'=> '1', 'caption' => '40万以下',),
        array('value'=> '2', 'caption' => '40 - 60万',),
        array('value'=> '3', 'caption' => '60 - 80万',),
        array('value'=> '4', 'caption' => '80 - 100万',),
        array('value'=> '5', 'caption' => '100 - 140万',),
        array('value'=> '6', 'caption' => '140 - 180万',),
        array('value'=> '7', 'caption' => '180万以上',),
    );

    public static $Huxing = array(
        'One' => '1',
        'Two' => '2',
        'Three' => '3',
        'Four' => '4',
        'FiveAndGT' => '5',
    );

    public static $HuxingList = array(
        array('value'=>'1', 'caption'=>'一室'),
        array('value'=>'2', 'caption'=>'两室'),
        array('value'=>'3', 'caption'=>'三室'),
        array('value'=>'4', 'caption'=>'四室'),
        array('value'=>'5', 'caption'=>'五室及以上'),
    );

    public static $HouseDirectionList = array(
        '朝南',
        '朝北',
        '朝东',
        '朝西',
        '朝东南',
        '朝东北',
        '朝西南',
        '朝西北',
    );

    public function findById($id)
    {
        $where['id'] = $id;
        $house = D('OldHouse') -> where($where) -> find();
        return $house;
    }
    public function saveHouse($data, $userId)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('OldHouse') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            if ($userId) {
                $data['userId'] = $userId;
            }
            return D('OldHouse')->add($data);
        }
    }
    public function saveHouseMedias($houseId, $video, $pics)
    {
        $houseVideo = D('Media')->queryHouseVideo($houseId, MediaModel::$HouseType['OldHouse']);
        if($video != $houseVideo['path'])
        {
            if($video)
            {
                $videoData = array(
                    'houseId' => $houseId,
                    'houseType' => MediaModel::$HouseType['OldHouse'],
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
        $housePics = D('Media')->queryHousePics($houseId, MediaModel::$HouseType['OldHouse']);
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
                        'houseType' => MediaModel::$HouseType['OldHouse'],
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
        $houses = D('OldHouse')->where($where)->order('sort ASC, updateTime DESC ')->select();
        return $houses;
    }

    public function genWhereByTotalPrice($toTalPrice) {
        $TP = self::$TotalPrice;
        $where = array();
        switch ($toTalPrice) {
            case $TP['Under40Wan']:
                $where['totalPrice'] = array('lt', 40);
                break;
            case $TP['40WanTo60Wan']:
                $where['totalPrice'] = array(array('egt', 40), array('elt', 60));
                break;
            case $TP['60WanTo80Wan']:
                $where['totalPrice'] = array(array('egt', 60), array('elt', 80));
                break;
            case $TP['80WanTo100Wan']:
                $where['totalPrice'] = array(array('egt', 80), array('elt', 100));
                break;
            case $TP['100WanTo140Wan']:
                $where['totalPrice'] = array(array('egt', 100), array('elt', 140));
                break;
            case $TP['140WanTo180Wan']:
                $where['totalPrice'] = array(array('egt', 140), array('elt', 180));
                break;
            case $TP['Beyond180Wan']:
                $where['totalPrice'] = array('gt', 180);
                break;
            default:
                break;
        }
        return $where;
    }

    public function countByUserId($userId) {
        $where['userId'] = $userId;
        $where['state'] = array('NEQ', self::$State['Deleted']);
        return D('OldHouse') -> where($where) -> count('id');
    }
}