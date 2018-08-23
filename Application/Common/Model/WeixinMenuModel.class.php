<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhuyajie <xcoolcc@gmail.com>
// +----------------------------------------------------------------------

namespace Common\Model;
use Think\Model;
/**
 * 微信菜单模型
 */
class WeixinMenuModel extends Model{

    protected $trueTableName = 'WeixinMenus';

    public static $MenuType = array(
        'view'  =>'跳转网页',
        'click'=> '发送消息'
    );

    protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('menu_name', 'require', '菜单名称不能为空！', 1, 'regex', Model:: MODEL_BOTH ),
        array('type', 'require', '菜单内容不能为空！', 1, 'regex', Model:: MODEL_BOTH ),
        array('url', 'url', '链接地址格式不正确！', 2, 'regex', Model:: MODEL_BOTH )
    );
    public function saveWeixinMenu($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return $this -> where($where) -> save($data);
        }
        else
        {
            $data['createTime'] = time();
            return $this->add($data);
        }
    }
    public function findWeixinMenuById($id)
    {
        return $this->where(array('id'=>$id))->find();
    }

    //通过父类ID获取子菜单信息
    public function getMenulistByparentId($parent_id = 0, $field = '', $order = '')
    {
        if ($order) {
            $this->order($order);
        }
        if ($field) {
            $this->field($field);
        }
        return $this->where(array('parent_id' => $parent_id))->select();
    }
    //微信菜单的数据
    public function getWxmenuList()
    {
        $menulist = $this->getMenulistByparentId(0,'id,name,type,key,url,media_id,appid,pagepath','ordernum');
        $data = array();
        foreach ($menulist as $k => $v) {
            unset($v['ordernum']);
            $submenulist = $this->getMenulistByparentId($v['id'],'name,type,key,url,media_id,appid,pagepath', 'ordernum');
            unset($v['id']);
            if(!empty($submenulist)){
                $data[] = array(
                    'name' => $v['name'],
                    'sub_button' => $submenulist
                    );
            }else{
                $data[] = $v;
            }
        }
        return array('button' => $data);
    }

    public function getMenuClickReplyByid($id)
    {
        return $this->where(array('id'=>$id))->getField('text_content');
    }
}
