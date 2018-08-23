<?php

namespace Admin\Controller;

use Common\Model\TagModel;

class TagController extends AdminController
{

    public function addTag(){
        if(IS_POST){
            $data = i('post.');
            D("Tag") -> add($data);
            $this->success('增加成功！');
        }else{
            $this-> assign("tagTypes", TagModel::$TagTypes);
            $this-> display('editTag');
        }
    }

    public function editTag(){
        $id = i('id');
        $where['id'] = $id;
        $res = D('Tag') -> where($where)-> find();
        $this-> assign("tagTypes", TagModel::$TagTypes);
        if(IS_POST){
            $data = i('post.');
            D('Tag') -> where($where) -> save($data);
            $this->success('修改成功！');
        }else{
            $this->assign('tag',$res);
            $this-> display('editTag');
        }
    }

    public function delTag() {
        $array_id['id'] = array('in',$_POST['ids']);
        D('Tag') -> where($array_id) -> delete();
        $this -> success('删除成功！');
    }

    public function listTag(){
        $offset = i("offset");
        $limit = i("limit");
        $search_key = i('search_key');
        $search_value = i('search');
        $where['1=1'] = 1;
        if($search_value){
            $where['name'] = array('LIKE',"%$search_value%");;
        }
//        $sort = i('sort');
//        $order = i('order');
//        if(!empty($sort)){
//            $reorder = $sort." ".$order;
//        }else{
//            $reorder = 'id asc';
//        }
//        $list =  M('huxing')-> where($where) -> order($reorder) ->limit($offset,$limit) -> select();
//        //dump($list);
//        $count =   M('huxing')-> where($where) -> order($reorder) -> count();
//        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
//        echo json_encode($list_array);

        $list =  D('Tag')-> where($where) -> limit($offset,$limit) -> select();
        foreach ($list as &$tag)
        {
            $tag['typeCaption'] = TagModel::$TagTypes[$tag['type']];
        }
        $count = D('Tag')-> where($where) -> count();
        $list_array= array("total"=>$count,"rows"=>$list?$list:array());
        echo json_encode($list_array);
    }
}