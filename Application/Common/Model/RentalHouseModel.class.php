<?php
namespace Common\Model;

use Think\Model;

class RentalHouseModel extends Model
{
    protected $trueTableName = 'RentalHouses';

    public static $PublishType = '2';

    public static $IsLocked = array(
        'Normal' => '1',
        'Locked' => '2',
    );

    public static $Source = array(
        'Personal' => '1',
        'Apartment' => '2',
        'shop_house'    =>  '9'
    );
    public static $SourceCaption = array(
        '1' => '个人',
        '2' => '精品公寓'
    );
    
    public static $Apartment_type = array(
        1   =>  '高级公寓',
        2   =>  '豪华公寓',
        3   =>  '简约公寓',
        4   =>  '单身公寓',
    );

    public static $RentalType = array(
        'Yyfy' => '1',
        'Yyfe' => '2',
        'Yyfs' => '3',
        'Wxyj' => '9'
    );
    public static $RentalTypeCaption = array(
        '1'=>'押一付一',
        '2'=>'押一付二',
        '3'=>'押一付三',
        '6'=>'押一付六',
        '12'=>'押一付十二',
        '9'=>'无需押金(付一)',
    );

    /**
     * 配套信息
     * @var array
     */
    public static $PtxxOptions = array(
        'c' => '床',
        'wsj' => '卫生间',
        'kd' => '宽带',
        'yg' => '衣柜',
        'zy' => '桌椅',
        'sf' => '沙发',
        'kt' => '空调',
        'rsq' => '热水器',
        'xyj' => '洗衣机',
        'ds' => '电视',
        'bx' => '冰箱',
        'wbl' => '微波炉',
        'yt' => '阳台',
        'nq' => '暖气',
        'kzf' => '可做饭',
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

    public static $Price = array(
        'Under500' => '1',
        '500To800' => '2',
        '800To1000' => '3',
        '1000To1500' => '4',
        '1500To2000' => '5',
        '2000To3000' => '6',
        '30000To5000' => '7',
        'Beyond5000' => '8',
    );

    public static $PriceList = array(
        array('value'=> '1', 'caption' => '500元以下',),
        array('value'=> '2', 'caption' => '500 - 800元',),
        array('value'=> '3', 'caption' => '800 - 1000元',),
        array('value'=> '4', 'caption' => '1000 - 1500元',),
        array('value'=> '5', 'caption' => '1500 - 2000元',),
        array('value'=> '6', 'caption' => '2000 - 3000元',),
        array('value'=> '7', 'caption' => '3000 - 5000元',),
        array('value'=> '8', 'caption' => '5000元及以上',),
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
    
    public static $cost_unit_id = array(
        1   =>  '固定费用',
        2   =>  '计量费用',
        3   =>  '押金费用',
    );
    
    public static $cost_name_id = array(
        1   =>  array(
            1   =>  '水费',
            2   =>  '电费',
            3   =>  '燃气费',
            4   =>  '物业费',
            5   =>  '宽带费',
            6   =>  '取暖费',
            7   =>  '停车费',
            8   =>  '卫生费',
            9   =>  '管理费',
            10  =>  '保洁费',
            11  =>  '公摊费',
            12  =>  '其他固定费',
        ),
        2   =>  array(
            1   =>  '水费',
            2   =>  '电费',
            3   =>  '燃气费',
        ),
        3   =>  array(
            1   =>  '钥匙押金',
            2   =>  '门卡押金',
            3   =>  '家电押金',
            4   =>  '家具押金',
            5   =>  '其他押金',
        )
    );
    
    public static $cost_type_id = array(
        1 => array(
            1   =>  '一次性收取',
            2   =>  '元/月',
        ),
        2 => array(
            1   =>  '元/吨',
            2   =>  '元/度',
            3   =>  '元/立方',
        ),
        3 => array(
            1   =>  '一次性收取',
        ),
    );
    
    public static $payment_type = array(
        1 => '平台', //2 => '微信', 3 => '支付宝'
    );
    
    public static $pay_date = array(
        1 => '签约日'
    );


    public function findById($id)
    {
        $where['id'] = $id;
        $house = D('RentalHouse') -> where($where) -> find();
        return $house;
    }
    public function saveHouse($data, $userId)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('RentalHouse') -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            if ($userId) {
                $data['userId'] = $userId;
            }
            return D('RentalHouse')->add($data);
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
