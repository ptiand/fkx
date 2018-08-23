<?php

namespace Home\Controller;
use Home\Service\LocationService;
use Think\Controller;

class LocationController extends Controller
{
    public function report()
    {
        if(IS_POST)
        {
            $longitude = I('longitude');
            $latitude = I('latitude');
            if($longitude && $latitude)
            {
                LocationService::getInstance()->saveLocation($longitude, $latitude);
            }
            $this->success();
        }
        else
        {
            //var_dump(session('longitude'), session('latitude'));
            echo '';
        }

    }

}