<?php

namespace Home\Controller;
use Think\Controller;

class MediaAdController extends Controller
{
    public function count()
    {
        $mediaAdId = I('mediaAdId');
        if($mediaAdId)
        {
            $mediaAd = D('MediaAd')->findMediaAdById($mediaAdId);
            if($mediaAd)
            {
                $mediaAd['playCount']  = $mediaAd['playCount'] + 1;
                D('MediaAd')->saveMediaAd($mediaAd);
            }
        }
    }
}