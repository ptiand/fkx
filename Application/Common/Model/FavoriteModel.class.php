<?php
namespace Common\Model;

use Think\Model;

class FavoriteModel extends Model
{
    protected $trueTableName = 'Favorites';
    public static $HouseType = array(
        'NewHouse'=>1,
        'OldHouse' => 2,
        'RentalHouse' => 3
    );
    public static $State = array(
        'Favored' => 1,
        'Deleted' => 9
    );
    public function saveFavorite($data)
    {
        $data['updateTime'] = time();
        if($data['id'])
        {
            $where['id'] = $data['id'];
            return D('Favorite') -> where($where) -> save($data);
        }
        else
        {
            $data['state'] = self::$State['Favored'];
            $data['createTime'] = time();
            return D('Favorite')->add($data);
        }
    }
    public function queryFavorite($userId, $houseId, $houseType)
    {
        $where['userId'] = $userId;
        $where['houseId'] = $houseId;
        $where['houseType'] = $houseType;
        $where['state'] = self::$State['Favored'];
        return D('Favorite')->where($where)->find();
    }
    public function deleteFavorite($userId, $houseId, $houseType)
    {
        $data = $this->queryFavorite($userId, $houseId, $houseType);
        if($data)
        {
            $data['state'] = self::$State['Deleted'];
            $this->saveFavorite($data);
        }
        return true;
    }
}