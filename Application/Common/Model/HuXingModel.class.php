<?php
namespace Common\Model;

use Think\Model;

class HuXingModel extends Model
{
    protected $trueTableName = 'huxing';
    public static $IsDelete = array(
        'YES' => 1,
        'NO' => 0
    );
    private $huXingCache = array();
    public function findById($id)
    {
        if(!$this->huXingCache[$id])
        {
            $huXing = D('HuXing')->where(array('id' => $id))->find();
            $this->huXingCache[$id] = $huXing;
        }
        return $this->huXingCache[$id];
    }
    public function findNameById($id)
    {
        $huXing = $this->findById($id);
        return $huXing['huxing_name'];
    }

    public function queryAll() {
        $where['is_del'] = array('NEQ', self::$IsDelete['YES']);
        return D('HuXing') -> where($where)-> order('sort asc') -> select();
    }
}