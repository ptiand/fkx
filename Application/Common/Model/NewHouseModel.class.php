<?php
namespace Common\Model;

use Think\Model;

class NewHouseModel extends Model
{
    protected $trueTableName = 'NewHouses';
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
        'Under2million' => '1',
        '2millionTo3million' => '2',
        '3millionTo4million' => '3',
        '4millionTo5million' => '4',
        '5millionToTenMillion' => '5',
        'TenMillionTo30Million' => '6',
        'Beyond30million' => '7',
    );

    public static $TotalPriceList = array(
        array('value'=> '1', 'caption' => '200万以下',),
        array('value'=> '2', 'caption' => '200 - 300万',),
        array('value'=> '3', 'caption' => '300 - 400万',),
        array('value'=> '4', 'caption' => '400 - 500万',),
        array('value'=> '5', 'caption' => '500 - 1000万',),
        array('value'=> '6', 'caption' => '1000 - 3000万',),
        array('value'=> '7', 'caption' => '3000万以上',),
    );

    public static $huxingList = array(
        array('value'=>'1', 'caption'=>'一室'),
        array('value'=>'2', 'caption'=>'两室'),
        array('value'=>'3', 'caption'=>'三室'),
        array('value'=>'4', 'caption'=>'四室'),
        array('value'=>'5', 'caption'=>'五室及以上'),
    );
    public function findById($id)
    {
        $where['id'] = $id;
        $house = D('NewHouse') -> where($where) -> find();
        return $house;
    }
    public function saveHouse($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('NewHouse') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            return D('NewHouse')->add($data);
        }
    }
    public function saveHouseMedias($houseId, $video, $pics)
    {
        $houseVideo = D('ZhuLi')->queryHouseVideo($houseId);
        if($video != $houseVideo['pic'])
        {
            if($video)
            {
                $videoData = array(
                    'loupan_id' => $houseId,
                    'pic' => $video,
                    'type' => ZhuLiModel::$Type['Video'],
                    'isDel' => ZhuLiModel::$IsDelete['NO']
                );
                D('ZhuLi')->saveZhuLi($videoData);
            }
            if($houseVideo)
            {
                D('ZhuLi')->deleteZhuLi($houseVideo['id']);
            }
        }
        $housePics = D('ZhuLi')->queryHousePics($houseId);
        $housePicMap = array();
        foreach ($housePics as $housePic)
        {
            $housePicMap[$housePic['pic']] = $housePic;
        }
        if($pics)
        {
            foreach ($pics as $key=>$pic)
            {
                if($housePicMap[$pic])
                {
                    $data = $housePicMap[$pic];
                    $data['sort'] = $key+1;
                    D('ZhuLi')->saveZhuLi($data);
                    unset($housePicMap[$pic]);
                }
                else
                {
                    $data = array(
                        'loupan_id' => $houseId,
                        'pic' => $pic,
                        'type' => ZhuLiModel::$Type['Pic'],
                        'isDel' => ZhuLiModel::$IsDelete['NO'],
                        'sort' => $key+1
                    );
                    D('ZhuLi')->saveZhuLi($data);
                }
            }
        }
        foreach ($housePicMap as $key=>$pic)
        {
            D('ZhuLi')->deleteZhuLi($pic['id']);
        }
    }

    /**
     * 保存房屋户型信息
     * @param $houseId
     * @param $houseHuxings
     */
    public function saveHouseHuxing($houseId, $houseHuxings)
    {
        //@TODO
        $huxingList = D('HouseHuxing')->queryHouseHuxing($houseId);
        $houseHuxingMap = array();
        foreach ($huxingList as $hx)
        {
            $houseHuxingMap[$hx['id']] = $hx;
        }
        foreach ($houseHuxings as $index=>$hHx)
        {
            $data = $hHx;
            if($houseHuxingMap[$hHx['id']])
            {
                $hhxData = $houseHuxingMap[$hHx['id']];
                $data = array_merge($hhxData, $hHx);
            }
            $data = array_merge($data, array('newHouseId'=>$houseId ,'sort'=>$index+1));
            D('HouseHuxing')->saveHouseHuxing($data);
            unset($houseHuxingMap[$hHx['id']]);
        }
        foreach ($houseHuxingMap as $id=>$data)
        {
            D('HouseHuxing')->deleteHouseHuxing($id);
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
            $this->saveHouse($house);
        }
    }

    public function genWhereByTotalPrice($toTalPrice) {
        $TP = self::$TotalPrice;
        $where = array();
        switch ($toTalPrice) {
            case $TP['Under2million']:
                $where['maxTotalPrice'] = array('lt', 200);
                break;
            case $TP['2millionTo3million']:
                $where['minTotalPrice'] = array(array('egt', 200), array('elt', 300));
                $where['maxTotalPrice'] = array(array('egt', 200), array('elt', 300));
                $where['_logic'] = 'or';
                break;
            case $TP['3millionTo4million']:
                $where['minTotalPrice'] = array(array('egt', 300), array('elt', 400));
                $where['maxTotalPrice'] = array(array('egt', 300), array('elt', 400));
                $where['_logic'] = 'or';
                break;
            case $TP['4millionTo5million']:
                $where['minTotalPrice'] = array(array('egt', 400), array('elt', 500));
                $where['maxTotalPrice'] = array(array('egt', 400), array('elt', 500));
                $where['_logic'] = 'or';
                break;
            case $TP['5millionToTenMillion']:
                $where['minTotalPrice'] = array(array('egt', 500), array('elt', 1000));
                $where['maxTotalPrice'] = array(array('egt', 500), array('elt', 1000));
                $where['_logic'] = 'or';
                break;
            case $TP['TenMillionTo30Million']:
                $where['minTotalPrice'] = array(array('egt', 1000), array('elt', 3000));
                $where['maxTotalPrice'] = array(array('egt', 1000), array('elt', 3000));
                $where['_logic'] = 'or';
                break;
            case $TP['Beyond30million']:
                $where['minTotalPrice'] = array('gt', 3000);
                break;
            default:
                break;
        }
        return $where;
    }
    /**
     * 查询推荐房源
     */
    public function queryRecommendHouse()
    {
        $where['state'] = array('NEQ', self::$State['Deleted']);
        $where['sort'] = array('GT', 0);
        $houses = D('NewHouse')->where($where)->order('sort ASC, updateTime DESC ')->select();
        return $houses;
    }

    public function queryHouseHuxingRange($id)
    {
        $huxingList = D('HouseHuxing')->queryHouseHuxing($id);
        $minHouseRoom = null;
        $maxHouseRoom = null;
        $minSquare = null;
        $maxSquare = null;
        foreach ($huxingList as $huxing)
        {
            if($huxing['houseRoom'])
            {
                if(!$minHouseRoom)
                {
                    $minHouseRoom = $huxing['houseRoom'];
                }
                if($huxing['houseRoom']<$minHouseRoom)
                {
                    $minHouseRoom = $huxing['houseRoom'];
                }
                if($huxing['houseRoom']>$maxHouseRoom)
                {
                    $maxHouseRoom = $huxing['houseRoom'];
                }
            }
            if($huxing['square'])
            {
                if(!$minSquare)
                {
                    $minSquare = $huxing['square'];
                }
                if($huxing['square']<$minSquare)
                {
                    $minSquare = $huxing['square'];
                }
                if($huxing['square']>$maxSquare)
                {
                    $maxSquare = $huxing['square'];
                }
            }
        }
        $h['minHouseRoom'] = $minHouseRoom;
        $h['maxHouseRoom'] = $maxHouseRoom;
        $h['minSquare'] = $minSquare;
        $h['maxSquare'] = $maxSquare;
        return $h;
    }

}