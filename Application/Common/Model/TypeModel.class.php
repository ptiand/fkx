<?php
namespace Common\Model;

use Think\Model;

class TypeModel extends Model
{
    protected $trueTableName = 'types';
    private $typeCache = array();
    public static $IsDelete = array(
        'YES' => 1,
        'NO' => 0
    );
    public function findById($id)
    {
        if(!$this->typeCache[$id])
        {
            $type = D('Type')->where(array('id' => $id))->find();
            $this->typeCache[$id] = $type;
        }
        return $this->typeCache[$id];
    }
    public function findNameById($id)
    {
        $type = $this->findById($id);
        return $type['type_name'];
    }
    public function findAll()
    {
        $map['is_del'] = array('NEQ', self::$IsDelete['YES']);
        $types = D('Type')->where($map)->select();
        if($types)
        {
            foreach ($types as $type)
            {
                $this->typeCache[$type['id']] = $type;
            }
        }
        return $types;
    }
}