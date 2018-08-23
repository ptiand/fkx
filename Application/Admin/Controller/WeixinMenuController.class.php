<?php


namespace Admin\Controller;
use Common\Model\WeixinMenuModel;
use Common\Service\WeixinService;
/**
*微信菜单
**/
class WeixinMenuController extends AdminController
{
    //是否有子菜单 1有 2无
    public static $SubMenuStatus = array(
        'HasSubmenu' => '1',
        'NoSubmenu' => '2'
    );
    public function index()
    {
        $title_name = '微信一级菜单列表';
        $this->assign('title_name', $title_name);
        $this->display();
    }

    public function loadMenus(){
        $reorder = 'ordernum DESC';
        $list =  D('WeixinMenu')->where(array('parent_id'=>0))->order($reorder)->select();
        $menu_type = WeixinMenuModel::$MenuType;
        foreach($list as $k => &$v){
            $v['parent_id'] = '一级菜单';
            $v['type'] = $menu_type[$v['type']];
            $sub_menus = D('WeixinMenu')->where(array('parent_id'=>$v['id']))->getField('id');
            $v['has_submenu'] = empty($sub_menus) ? self::$SubMenuStatus['NoSubmenu']:self::$SubMenuStatus['HasSubmenu'];
        }
        $list_array= array("total"=>count($list),"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }

    public function subMenu()
    {
        $parentId = I('parentId');
        $menu = D('WeixinMenu')->findWeixinMenuById($parentId);
        $this->assign('menu', $menu);
        $this->display('subMenu');
    }
    public function loadSubMenu()
    {
        $parentId = I('parentId');
        //$menu = D('WeixinMenu')->findWeixinMenuById($parentId);
        $subMenus = D('WeixinMenu')->getMenulistByparentId($parentId,'', 'ordernum');
        $menuType = WeixinMenuModel::$MenuType;
        foreach($subMenus as &$subMenu)
        {
            $subMenu['type'] = $menuType[$subMenu['type']];
        }
        //$this->assign('menu', $menu);
        $res= array("total"=>count($subMenus),"rows"=>$subMenus?$subMenus:array());
        echo json_encode($res);
    }
    public function add()
    {
        $id = I('request.id');
        $info = D('WeixinMenu')->findWeixinMenuById($id);
        $parent_id = $id ? $info['parent_id'] : I('request.parent_id', 0);
        if(!$id && IS_POST)
        {
            //新增菜单时，校验菜单数量
            $count = D('WeixinMenu')->where(array('parent_id'=>$parent_id))->count();
            if($parent_id)
            {
                if($count >= 5)
                {
                    $this->error('二级菜单最多只能添加5个');
                    return;
                }
            }
            else
            {
                if($count >= 3)
                {
                    $this->error('一级菜单最多只能添加3个');
                    return;
                }
            }
        }

        if ($id) {//如果是添加子菜单 一级菜单的内容将被清除
            $submenuid = D('WeixinMenu')->where(array('parent_id'=>$id))->getField('id');
            $has_submenu = !$submenuid ? self::$SubMenuStatus['NoSubmenu']: self::$SubMenuStatus['HasSubmenu'];
        }else{
            $has_submenu = self::$SubMenuStatus['NoSubmenu'];
        }

        if(IS_POST)
        {
            $data = I('post.');
            $msg_type = empty($id) ? '添加' : '修改';
            if ($data['type'] == 'view') {//关键字消息回复
                unset($data['text_content']);
                if (!preg_match("/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/", $data['url'])) {
                    $this->error('页面地址格式不对！');
                }
            }elseif ($data['type'] == 'click') {
                unset($data['url']);
                if (empty($data['text_content'])) {
                    $this->error('回复内容不能为空');
                }
            }elseif($has_submenu == self::$SubMenuStatus['HasSubmenu']){
                if (empty($data['name'])) {
                    $this->error('菜单名称不能为空');
                }
            }else{
                $this->error('请选择菜单类型');
            }
            if ($id) {
                $savedId = D('WeixinMenu')->saveWeixinMenu($data);
            }else{
                //如果存在父id，判断该父菜单下是否已经有菜单，如果没有菜单，则为第一次添加子菜单，则把父菜单一些字段清空
                if($parent_id)
                {
                    $subMenus = D('WeixinMenu')->getMenulistByparentId($parent_id);
                    if (!$subMenus) {//第一次添加子菜单 清空
                        $clear_fields = array(
                            'id'=>$parent_id,
                            'type' => '',
                            'key' =>'',
                            'url' =>'',
                            'text_content' =>''
                        );
                        D('WeixinMenu')->saveWeixinMenu($clear_fields);
                    }
                }
                $savedId = D('WeixinMenu')->saveWeixinMenu($data);
                if ($savedId !== false) {//id 当作key 来存
                   $savedId = D('WeixinMenu')->saveWeixinMenu(array('id'=>$savedId, "key"=>$savedId));
                }
            }
            if($savedId !== false)
            {
                $this->success($msg_type.'成功！');
            }
            else
            {
                $this->error($msg_type.'失败！');
            }
        }
        else
        {
            $this->assign('info',$info);
            $this->assign('parent_id',$parent_id);
            $this->assign('firstmenu', D('WeixinMenu')->getMenulistByparentId());
            $this->assign('menu_type', WeixinMenuModel::$MenuType);
            $this->assign('has_submenu', $has_submenu);
            $this->display('add');
        }
    }
    public function delete()
    {
        $ids = i('ids');
        if($ids)
        {
            foreach ($ids as $id)
            {
                D('WeixinMenu')->where(array('id'=>$id))->delete();
            }
            $this->success('删除成功！');
        }
        else
        {
            $this->error('删除失败！');
        }
    }
    //发布微信公众号菜单
    public function create_menu()
    {
        $wxservice = WeixinService::getInstance();
        $res = $wxservice->createMenu();
        if ($res == false) {
            $this->error('发布菜单失败！');
        }else{
            $this->success('发布菜单成功！'); 
        }
    }
    //删除微信公众号菜单
    public function remove_menu()
    {
        $wxservice = WeixinService::getInstance();
        $res = $wxservice->deleteMenu();
        if ($res == false) {
            $this->error('删除菜单失败！');
        }else{
            $this->success('删除菜单成功！'); 
        }
    }
}