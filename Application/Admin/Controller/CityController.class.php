<?php

namespace Admin\Controller;

class CityController extends AdminController
{
    public function citys(){
        $this -> display();
    }
    //加载项目类型
    public function loadCitys(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        $where['is_del'] = 0;
        if($search_value){
            $where['city_name'] = array('LIKE',"%$search_value%");
        }
        $sort = i('sort');
        $order = i('order');
        if(!empty($sort)){
            $reorder = $sort." ".$order;
        }else{
            $reorder = 'id asc';
        }
        $list =  M('citys')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();

        foreach ($list as &$v){
            if($v['pid'] == 0){
                $v['pid'] = '顶级栏目';
            }else{
                $v['pid'] = M('citys')-> where(['id'=>$v['pid']]) -> getField('city_name');
            }

        }
        //dump($list);
        $count =   M('citys')-> where($where) -> order($reorder) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
    //增加项目类型
    public function addCitys(){
        if(IS_POST){
            $data = i('post.');
            M('citys') -> add($data);
            $this->success('增加成功！');
        }else{
            $res = M('citys') ->  where(['is_del'=>0,'pid'=>0]) ->getField("id,city_name");
            $this->assign('menu',$res);
            $this-> display('editcitys');
        }
    }
    //修改项目类型
    public function editCitys(){
        $id = i('id');
        $where['id'] = $id;
        $res = M('citys') -> where($where)-> find();
        if(IS_POST){
            $data = i('post.');
            M('citys') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('info',$res);
            $res = M('citys') -> where(['is_del'=>0,'pid'=>0]) ->getField("id,city_name");
            $this->assign('menu',$res);
            $this-> display('editcitys');
        }
    }
    //删除项目类型
    public function delCitys(){
        $array_id['id'] = array('in',$_POST['ids']);
        M('citys') -> where($array_id) -> save(['is_del'=>1]);
        $this -> success('删除成功！');
    }

    /**
     * 根据pid加载下拉框的html
     */
    public function loadCitySelectHtml()
    {
        $pid = i('post.pid');
        $defaultValue = i('post.value');
        $selectId = i('post.selectId');
        $selectName = i('post.selectName');
        $emptyValue = i('post.emptyValue');
        if($pid=='')
        {
            echo renderHtmlSelect($selectName, $selectId, array(), $defaultValue, $emptyValue,'');
            return;
        }
        $cities = D('City')->queryCityChildren($pid);
        $cityMap = array();
        foreach ($cities as $city)
        {
            $cityMap[$city['id']] = $city['city_name'];
        }
        echo renderHtmlSelect($selectName, $selectId, $cityMap, $defaultValue, $emptyValue,'');
    }
}