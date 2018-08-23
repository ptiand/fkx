<?php


namespace Home\Service;


class LocationService
{
    private static $ins = null;
    public static function getInstance()
    {
        if(!self::$ins)
        {
            self::$ins = new LocationService();
        }
        return self::$ins;
    }
    public function saveLocation($longitude, $latitude)
    {
        session('longitude', $longitude);
        session('latitude', $latitude);
    }
    public function getLocation()
    {
        $openId = session('openId');
        if($openId)
        {
            $weixinUser = D('WeixinUser')->findWeixinUserByOpenid($openId);
            if($weixinUser && $weixinUser['longitude'] && $weixinUser['latitude'])
            {
                return array(
                    'longitude' => $weixinUser['longitude'],
                    'latitude' => $weixinUser['latitude']
                );
            }
        }
        $longitude = session('longitude');
        $latitude = session('latitude');
        if($longitude && $latitude)
        {
            return array(
                'longitude' => $longitude,
                'latitude' => $latitude
            );
        }
//        $user = get_user();
//        if($user && $user['id'])
//        {
//
//        }
    }

}