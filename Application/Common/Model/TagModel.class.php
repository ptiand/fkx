<?php
namespace Common\Model;
use Think\Model;
class TagModel extends Model {
    protected $trueTableName = 'Tags';
    public static $TagType = array(
        'NewHouse'=>1,
        'OldHouse'=>2,
        'RentalHouse'=>3
    );
    public static $State = array(
        'Enable'=>1,
        'Disabled' => 2,
        'Deleted'=>3
    );
    public static $TagTypes = array(
        1=>"新房",
        2=>"二手房",
        3=>"租房"
    );
    public function queryNewHouseTags()
    {
        $where['type'] = self::$TagType['NewHouse'];
        $where['state'] = self::$State['Enable'];
        $tags = D('Tag')->where($where)->order('id ASC')->select();
        return $tags;
    }
    public function queryRentalHouseTags()
    {
        $where['type'] = self::$TagType['RentalHouse'];
        $where['state'] = self::$State['Enable'];
        $tags = D('Tag')->where($where)->order('id ASC')->select();
        return $tags;
    }
    public function queryOldHouseTags()
    {
        $where['type'] = self::$TagType['OldHouse'];
        $where['state'] = self::$State['Enable'];
        $tags = D('Tag')->where($where)->order('id ASC')->select();
        return $tags;
    }
}