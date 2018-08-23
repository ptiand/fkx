<?php

namespace Home\Controller;

use Think\Controller;

class MapHouseController extends Controller
{
    /**
     * 地图找房
     *
     * @return void
     */
     public function index()
     {
        $this->assign('type', I('type', 3));
        $this->assign('source', I('source', 0));
        $this->assign('longitude', session('longitude'));
        $this->assign('latitude', session('latitude'));
        $this->assign('BaiduMapAk',C('BAIDU_MAP_AK'));
        $this->display("index");
     }

     public function houseList()
     {
         $type = I('type',1);
         $source = I('source',1);
         switch($type) {
             case 1:
                 $table = 'NewHouses';
                 $urlTable = 'NewHouse';
                 break;
             case 2:
                 $table = 'OldHouses';
                 $urlTable = 'OldHouse';
                 break;
             case 3:
                 $table = 'RentalHouses';
                 $urlTable = 'RentalHouse';
                 break;
             default:
                 break;
         }
         $id = I('id');
         $houseInfo = D($urlTable) -> findById($id);
         if(empty($houseInfo)){
             exit;
         }

				 if(3 == $type){
				 		$where = " xqName LIKE '%{$houseInfo['xqName']}%' AND source = {$source}";	
				 }else{
				 	  $where = " xqName LIKE '%{$houseInfo['xqName']}%'";
				 }
         $sql = "SELECT * FROM {$table} WHERE {$where}  ORDER BY `updateTime` DESC";
         $Model = M();//或者 $Model = D(); 或者 $Model = M();
         $houseList = $Model->query($sql);

         $listWhere['xqName'] = $houseInfo['xqName'];
         $this->assign('houseList', $houseList);
         $this->assign('type',$type);
         //print_r($houseList);
         $this->display();
     }
 
     public function ajaxTest()
     {
        $point = I('point');
		$where = "";
		$leftLng = I('leftLng');
		$rightLng = I('rightLng');
		$where .= " AND longitude BETWEEN {$leftLng} AND {$rightLng} ";
		$bottomLat = I('bottomLat');
		$topLat = I('topLat');
		$where .= " AND latitude BETWEEN {$bottomLat} AND {$topLat} ";
		$source = I('get.source', 0);
        //$where = [
        //    'longitude' => ['BETWEEN', [I('leftLng'), I('rightLng')]],
        //    'latitude' => ['BETWEEN', [I('bottomLat'), I('topLat')]],
        //];
        //if (I('get.source', 0)) {
        //    $where['source'] = I('get.source', 0);
        //}
        $houseList = $this->getList(I('get.type', 0), I('get.source', 0), $point, $where);
        if ($houseList) {
            $this->success($houseList);
        } else {
            $this->error('没有更多数据！');
        }
    }

    protected function getList($type, $source, $point, $where)
    {
        if (empty($point) || !in_array($type, [1, 2, 3])) {
            return [];
        }

        $houseList = array();
        $table = $urlTable = '';
        switch($type) {
            case 1:
                $table = 'NewHouses';
				$urlTable = 'NewHouse';
                break;
            case 2:
                $table = 'OldHouses';
				$urlTable = 'OldHouse';
                break;
            case 3:
                $table = 'RentalHouses';
				$urlTable = 'RentalHouse';
                if(empty($source)){
                    $source = 2;
                }
                break;
            default:
                break;
        }
        if($source){
            $where .= " AND source = {$source} ";
        }
        //$houseModule = D($table);
        //$field = '*';
        //$orderField = '`updateTime` DESC';// ' 1/`sort` DESC, `updateTime` DESC'
        //$field .= ', ABS(`longitude`-'.$point['lng'].')+ABS(`latitude`-'.$point['lat'].') as distance';
        //$orderField = ' 1/`distance` DESC, '.$orderField;
        //$houseList = $houseModule->field($field)-> where($where) -> limit(0, 50) -> order($orderField) -> select();
		   $sql = "SELECT * FROM (select max(id) id, count(1) num, xqName from {$table} WHERE `longitude` <> '0.00000000' AND `latitude` <> '0.00000000' AND `xqName` <> '' {$where} Group by `xqName`) V LIMIT 0,50";
        
        $Model = M();//或者 $Model = D(); 或者 $Model = M();
        $countHouseList = $Model->query($sql);
        if (empty($countHouseList)) {
            return [];
        }

        $list = [];
        foreach ($countHouseList as $key => $value) {
            $ids[] = $value['id'];
            $list[$value['id']] = $value['num'];
        }

        $houseList = $Model->query("SELECT * FROM {$table} WHERE id IN (" . implode(',', $ids) . ")");
        //echo $Model->getLastSql();exit;
        foreach ($houseList as $key => $value) {
            $houseList[$key]['url'] = U('/MapHouse/houseList?type='.$type.'&id='.$value['id'].'&source='.$value['source']);
            /**$value['other'] = json_decode($value['others'], true);
            if(!empty($value['other']['xq'])){
                $tmp_name = $value['other']['xq'];
            }else{
                $tmp_name = $value['name'];
            }**/
            $houseList[$key]['num'] = isset($list[$value['id']]) ? $list[$value['id']] : 0;
            $tmp_name = $value['xqName'];
            if($type == 3){
                $tmp_price_name = !empty($value['price'])?'•'.renderMoney($value['price'],0).'元':'';
            }elseif($type == 2){
                $tmp_price_name = !empty($value['totalPrice'])?renderMoney($value['totalPrice'],0).'万元/套':'';
            }else{
                if($value['minTotalPrice'] > 0){
                    $tmp_price_name = !empty($value['minTotalPrice'])?renderMoney($value['minTotalPrice'],0).'万元/套起':'';
                }elseif($value['minTotalPrice'] <= 0 && $value['minPrice']){
                    $tmp_price_name = !empty($value['minPrice'])?'•'.renderMoney($value['minPrice'],0).'元/㎡起':'';
                }else{
                    $tmp_price_name = '';
                }
            }
            if ( $houseList[$key]['num'] > 1) {
                $houseList[$key]['name'] = $tmp_name.'  '. $houseList[$key]['num'] . '套';
            } else {
                $houseList[$key]['name'] = $tmp_name.'  '.$tmp_price_name;
            }
        }
        return $houseList;
    }

	public function getXqName(){
        $type = I('type');
		$table = '';
        switch($type) {
            case 1:
                $table = 'NewHouses';
                break;
            case 2:
                $table = 'OldHouses';
                break;
            case 3:
                $table = 'RentalHouses';
                break;
            default:
                break;
        }
		$sql = "SELECT * FROM {$table} WHERE `xqName` = ''";
		$Model = M();//或者 $Model = D(); 或者 $Model = M();
		$houseList = $Model->query($sql);
		//echo $Model->getLastSql();exit;
        foreach ($houseList as $key => $value) {
            $value['other'] = json_decode($value['others'], true);
			print_r($value['other']);
            $tmp_name = '';
			if(!empty($value['other']['xq'])){
                $tmp_name = $value['other']['xq'];
            }
			echo $tmp_name.PHP_EOL;

            $m = M();
            $sql="update {$table} set xqName = '{$tmp_name}' where id=".$value['id'];
            $m->execute($sql);

			echo $m->getLastSql().PHP_EOL;
        }
        echo 'success';
	}
}