<?php
namespace Common\Model;

use Think\Model;

class ZhuangXiuModel extends Model
{
    protected $trueTableName = 'zxiu';
    public static $IsDelete = array(
        'YES' => 1,
        'NO' => 0
    );
    public function findAll()
    {
        $map['is_del'] = array('NEQ', self::$IsDelete['YES']);
        $zXius = D('ZhuangXiu')->where($map)->select();
        return $zXius;
    }
    public function findById($id)
    {
        $zx = D('ZhuangXiu')->where(array('id' => $id))->find();
        return $zx;
    }
    public function findNameById($id)
    {
        $type = $this->findById($id);
        return $type['zxiu_name'];
    }
}