<?php
namespace Common\Model;

use Think\Model;

class CityModel extends Model
{
    protected $trueTableName = 'citys';
    private $cityCache = array();
    public static $IsDelete = array(
        'YES' => 1,
        'NO' => 0
    );
    public function findById($id)
    {
        if(!$this->cityCache[$id])
        {
            $city = D('City')->where(array('id' => $id))->find();
            $this->cityCache[$id] = $city;
        }
        return $this->cityCache[$id];
    }
    public function findNameById($id)
    {
        $city = $this->findById($id);
        return $city['city_name'];
    }
    public function queryTopCity()
    {
        return $this->queryCityChildren(0);
    }
    public function queryCityChildren($pid)
    {
        $where['is_del'] = array('NEQ', self::$IsDelete['YES']);
        $where['pid'] = $pid;
        $cities = D('City')->where($where)->select();
        return $cities;
    }

    public function queryAll() {
        $where['is_del'] =  self::$IsDelete['NO'];
        return D('City') -> where($where) -> select();
    }
}