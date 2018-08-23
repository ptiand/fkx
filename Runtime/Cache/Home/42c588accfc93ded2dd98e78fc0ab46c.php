<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>出租房详情</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/public/Home/css/bottom_btns.css">
    <link rel="stylesheet" href="/public/Home/css/house_detail.css">
    <link rel="stylesheet" href="/public/Home/css/swiper-4.2.2.min.css">
    <link rel="stylesheet" href="/public/Home/css/form.css">
    <style type="text/css">
        video::-webkit-media-controls-enclosure {
            overflow:hidden;
        }
        video::-webkit-media-controls-panel {
            width: calc(100% + 30px);
        }
        .backIcon
        {
            width: 20px;
            height: 20px;
            margin-top: 1px;
            margin-left: 0;
        }
        .swiper-pagination
        {
            color: #fff;
            font-size: 10px;
            display: flex;
            justify-content: flex-end;
            width: 98%;
        }
        .swiper-pagination .page-number
        {
            background-color: #000;
            padding: 3px 8px 3px 8px;
        }
        .video-container
        {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .modal-dialog {
            margin: 0px;
            height: 100%;
        }
        .modal-dialog .modal-content{
            height: 100%;
            background-color: rgba(242, 242, 242, 1);
        }
        #extra-content {
            margin-top: 5px;
        }

        .button {
            cursor:pointer;
        }

        .ui-loader.ui-corner-all.ui-body-a.ui-loader-default {
            display: none;
        }
    </style>


    <script src="/public/Static/jquery-2.1.3.min.js"></script>
    <script src="/public/Home/js/jquery.touchSlider.js"></script>
    <style>
        .actives{
            color:#44c3ca !important;
        }
    </style>
    <script type="text/javascript">
        function FkxToast()
        {
        }
        FkxToast.success = function (msg) {
            alert(msg);
        };
        FkxToast.error = function (msg) {
            alert(msg);
        };
    </script>
</head>
<body>
    <input type="hidden" id="path_info_" value="<?php if (!empty($_SERVER['PATH_INFO'])){ echo $_SERVER['PATH_INFO'];}else{echo 'main';} ?>">

    <div class="detail-header" id="detailHeader">
        <div class="button goBackBtn" name="goBack">
            <span><img class="backIcon" src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22" /></span>
        </div>
    </div>
    <div class="header" id="header" style="display: none;">
        <div class="button goBackBtn" name="goBack">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
        </div>
        <div class="title">
            <span>详情</span>
        </div>
        <div class="button">
        </div>
    </div>


    <div class="container">
        <input type="hidden" name="houseId" value="<?php echo ($house["id"]); ?>"/>
        <input type="hidden" name="houseType" value="RentalHouse"/>
        <div class="picture-container">
            <?php if($house['video']): ?><div class="btn-video" style="" id="VideoPlayBtn">
                    <!--<img style="position: absolute;" src="/public/Home/images/house/circle.png">-->
                    <img style="" src="/public/Home/images/house/trangle.png">
                    <span>点击播放视频</span>
                </div><?php endif; ?>
            <div class="swiper-container" id="HeaderSwiper">
                <div class="swiper-wrapper">
                    <!--<?php if($house['video']): ?>-->
                        <!--<div class="swiper-slide">-->
                            <!--<div class="video-container" id="videoContainer">-->
                                <!--<img style="position: absolute;" src="/public/Home/images/house/circle.png">-->
                                <!--<img style="position: absolute;transform: rotate(90deg);margin-left: 3px;" src="/public/Home/images/house/trangle.png">-->
                                <!--<video id="video" src="<?php echo ($house["video"]); ?>" style="width: 100%; height: 200px;"/>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--<?php endif; ?>-->
                    <?php if(is_array($pics)): foreach($pics as $key=>$pic): ?><div class="swiper-slide">
                            <img src="<?php echo ($pic["path"]); ?>" width="100%" height="200" >
                        </div><?php endforeach; endif; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>
        <div class="top-content content" style="margin-top: 0;">
            <div class="title">
                <span>
                    <span class="title-text" style="font-size: 16px;"><?php echo ($house["name"]); ?></span>
                    <!--
                    <span class="title-block state"><?php echo ($house["stateCaption"]); ?></span>
                    <span class="title-block type"><?php echo ($house["typeCaption"]); ?></span>
                    -->
                </span>
                <span class="right-text" <?php echo renderLoginHtml('id="report-text"');?>>投诉举报</span>
            </div>

            <div class="tag-container">
                <?php if(is_array($house['tags'])): foreach($house['tags'] as $key=>$tag): ?><span class="block"><?php echo ($tagNames[$tag]); ?></span><?php endforeach; endif; ?>
            </div>

            <div class="divide-line"></div>

            <div class="base-content">
                <div class="base-item">
                    <span class="item-primer-text"><?php echo renderMoney($house['price'], 0);?>元/月</span>
                    <span class="item-label"><?php echo ($house['rentalTypeCaption']); ?></span>
                </div>
                <div class="vertical-line"></div>
                <div class="base-item">
                    <span class="item-primer-text">
                        <?php
 $text=''; if($house['houseRoom']) $text .= $house['houseRoom'] . '室'; if($house['livingRoom']) $text .= $house['livingRoom'] . '厅'; if($house['restRoom']) $text .= $house['restRoom'] . '卫'; if($house['diningRoom']) $text .= $house['diningRoom'] . '厨'; if($house['veranda']) $text .= $house['veranda'].'阳台'; echo $text; ?>
                    </span>
                    <span class="item-label">户型</span>
                </div>
                <div class="vertical-line"></div>
                <div class="base-item">
                    <span class="item-primer-text"><?php echo renderMoney($house['square'], 0);?>㎡</span>
                    <span class="item-label">面积</span>
                </div>
            </div>

            <div class="divide-line"></div>

            <div class="item" style="display: none;">
                <span class="item-label">时间</span>
                <span class="item-content">
                    <span>
                        <?php echo date('Y年m月d日', $house['updateTime'])?>发布
                    </span>
                </span>
            </div>
            <div class="item">
                <span class="item-label">地址</span>
                <?php
 $showMap = false; if($house['longitude']>0 && $house['latitude']>0) { $showMap = true; } ?>
                <span class="item-content <?php echo ($house['address'] ? 'has-tail' :''); ?>" <?php echo ($showMap?'id="showMap"':""); ?>>
                    <input name="longitude" type="hidden" value="<?php echo ($house['longitude']); ?>"/>
                    <input name="latitude" type="hidden" value="<?php echo ($house['latitude']); ?>"/>
                    <span><?php echo ($house['cityCaption'] . "&nbsp;" . $house['areaCaption'] . '&nbsp;' . $house['address']) ?></span>
                    <?php if($showMap): ?><span><a class="item-label"><i class="gt"><img src="/public/Home/images/icon_gt.png" /></i></a></span><?php endif; ?>
                </span>
            </div>
            <?php if($sources && $house['source'] == $sources['Apartment']): ?><div class="item">
                    <span class="item-label">佣金</span>
                    <span class="item-content has-tail">
                    <span><?php echo ($house["commission"]); ?></span>
                    <a id="CommissionRule" class="item-label" style="min-width: 64px;">佣金规则 <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i></a>
                </span>
                </div><?php endif; ?>

        </div>
        <?php if(!empty($card)): ?><div id="coupon-content" class="content" <?php if($isLogin): ?>name="houseCard"<?php else: ?>onclick="javascript:window.location.href='<?php echo U('Login/code_login');?>'"<?php endif; ?>>
                <div class="left">
                    <input type="hidden" name="cardId" value="<?php echo ($card["id"]); ?>">
                    <span class="title"><?php echo ($card["name"]); ?></span>
                    <span class="second-title">最高返现<?php echo $card['returnCash'];?></span>
                </div>
                <div class="line"></div>
                <div class="right">
                    <span>立即领取</span>
                </div>
            </div><?php endif; ?>

        <div id="extra-content" class="content">
            <div class="title">
                <span class="title-text">房源信息</span>
            </div>
            <div class="double-row">
                <div class="item">
                    <span class="item-label double-width">限制</span>
                    <span class="item-content"><?php echo ((isset($others['xz']) && ($others['xz'] !== ""))?($others['xz']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">方式</span>
                    <span class="item-content"><?php echo ((isset($others['fs']) && ($others['fs'] !== ""))?($others['fs']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">朝向</span>
                    <span class="item-content"><?php echo ((isset($others['cx']) && ($others['cx'] !== ""))?($others['cx']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">装修</span>
                    <span class="item-content"><?php echo ((isset($house['renovationStandardCaption']) && ($house['renovationStandardCaption'] !== ""))?($house['renovationStandardCaption']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">楼层</span>
                    <span class="item-content"><?php echo ((isset($others['lc']) && ($others['lc'] !== ""))?($others['lc']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">类型</span>
                    <span class="item-content"><?php echo ((isset($house['typeCaption']) && ($house['typeCaption'] !== ""))?($house['typeCaption']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">电梯</span>
                    <span class="item-content"><?php echo ((isset($others['dt']) && ($others['dt'] !== ""))?($others['dt']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">来源</span>
                    <span class="item-content"><?php echo ((isset($house['sourceCaption']) && ($house['sourceCaption'] !== ""))?($house['sourceCaption']):"暂无信息"); ?></span>
                </div>
                <div class="item">
                    <span class="item-label double-width">小区</span>
                    <span class="item-content"><?php echo ((isset($others['xq']) && ($others['xq'] !== ""))?($others['xq']):"暂无信息"); ?></span>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="title">
                <span class="title-text">配套信息</span>
            </div>
            <div class="grid-container">
                <?php if(is_array($others['ptxx'])): foreach($others['ptxx'] as $key=>$pttxItem): if($pttxItem[$key] == 1): ?><div class="item">
                            <img src="/public/Home/images/house/checked.png" width="16" height="16" />
                            <span style="font-size: 13px;"><?php echo ($ptxxCaptions[$key]); ?></span>
                        </div><?php endif; endforeach; endif; ?>
            </div>
        </div>
        <?php if(!empty($house['fkxSay'])): ?><div class="content">
            <div class="title">
                <span class="title-text">房客行说</span>
            </div>
            <div class="item" style="line-height: 24px;font-size: 14px;">
                <span class="item-label" style="white-space: pre-wrap;"><?php echo ($house["fkxSay"]); ?></span>
            </div>
        </div><?php endif; ?>
        <?php if(!empty($house['introduce'])): ?><div class="content">
                <div class="title">
                    <span class="title-text">房源介绍</span>
                </div>
                <div class="item">
                    <span class="item-label" style="line-height: 22px;font-size: 13px;white-space: pre-wrap;"><?php echo ($house["introduce"]); ?></span>
                </div>
            </div><?php endif; ?>

        <div id="detail-tail">-- 已经到底了 --</div>
        <div id="bottom-container">
    <div class="left-content item">
        <?php if($isLogin): ?><div class="item favored" id="liked" <?php if(!$favorite) echo 'style="display:none;"';?> >已收藏<img src="/public/Home/images/btbar_fav2.png"/></div>
            <div class="item" id="like" <?php if($favorite) echo 'style="display:none;"';?> >收藏<img src="/public/Home/images/btbar_fav1.png"/></div>
            <div class="item" id="online-ask">在线咨询<img src="/public/Home/images/btbar_talk1.png"/></div>
            <div class="item" id="phone-ask">电话咨询<img src="/public/Home/images/btbar_phone1.png"/></div>
        <?php else: ?>
            <a class="item" href="<?php echo U('Login/code_login');?>">收藏<img src="/public/Home/images/btbar_fav1.png"/></a>
            <a class="item" href="<?php echo U('Login/code_login');?>">在线咨询<img src="/public/Home/images/btbar_talk1.png"/></a>
            <a class="item" href="<?php echo U('Login/code_login');?>">电话咨询<img src="/public/Home/images/btbar_phone1.png"/></a><?php endif; ?>
    </div>
    <?php if($houseType == 'RentalHouse' && $sources && $house['source']!= $sources['Personal']): ?><div class="right-content item">
            <?php if($isLogin): ?><a class="item appoint" href="<?php echo U('Info/info', array('houseId'=>$house['id'],'houseType'=>$houseType));?>">预约看房</a>
                <div class="item record" id="record">客户报备</div>
            <?php else: ?>
                <a class="item appoint" href="<?php echo U('Login/code_login');?>">预约看房</a>
                <a class="item record" href="<?php echo U('Login/code_login');?>">客户报备</a><?php endif; ?>
        </div><?php endif; ?>
    <?php if($houseType == 'OldHouse'): ?><div class="right-content item">
            <?php if($isLogin): ?><a class="item appoint" id="FkxService" href="<?php echo U('Index/trade', array('houseId'=>$house['id'],'houseType'=>$houseType));?>">房客行服务</a>
            <?php else: ?>
                <a class="item appoint" href="<?php echo U('Login/code_login');?>">房客行服务</a><?php endif; ?>
        </div><?php endif; ?>
</div>
    </div>




    <div class="modal" id="ContactModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content contact-modal" style="background-color: rgba(242, 242, 242, 0);">
                <?php if($isLogin): ?><div class="contact-view">
                    <span class="title">欢迎来电咨询</span>
                    <?php if($others['xsyz'] && $others['yzdh']): ?><div class="contact border-bottom">
                            <div class="contact-label">业主电话</div>
                            <div class="contact-info">
                                <a class="contact-item" href="tel:<?php echo ($others['yzdh']); ?>">
                                    <span><?php echo ($others['yzdh']); ?> <?php echo ($others['yzxm']); ?></span>
                                    <img src="/public/Home/images/house/phone.png" width="16" height="16">
                                </a>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="contact-item">
                            <span>暂无联系信息</span>
                        </div><?php endif; ?>
                    <?php if(count($others['contacts']) > 0): ?><div class="contact">
                        <div class="contact-label">房客行</div>
                        <div class="contact-info">
                                <?php if(is_array($others['contacts'])): foreach($others['contacts'] as $key=>$contact): ?><a class="contact-item" href="tel:<?php echo ($contact["tel"]); ?>">
                                        <span><?php echo ($contact["tel"]); ?>&nbsp;<?php echo ($contact["name"]); ?></span>
                                        <img src="/public/Home/images/house/phone.png" width="16" height="16">
                                    </a><?php endforeach; endif; ?>
                                <!--<div class="contact-item">-->
                                    <!--<span>暂无联系信息</span>-->
                                <!--</div>-->
                        </div>
                    </div><?php endif; ?>
                    <div class="close-icon" id="ContactClose">
                        <img src="/public/Home/images/close_icon.png" width="20" height="20">
                    </div>
                </div><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ImageGridModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: rgba(51, 51, 51, 1);">
                <div class="header" id="page-header"  style="background-color: rgba(51, 51, 51, 1);">
                    <div class="button" id="imageGridBack">
                        <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                    </div>
                    <div class="title">
                        <span>全部<?php echo count($pics)+($house['video']?1:0);?>张图片</span>
                    </div>
                    <div class="button">
                    </div>
                </div>
                <div class="content-container">
                    
    <style type="text/css">
        #page-header.header {
            background-color: rgba(51, 51, 51, 1);
        }
        .content-container {
            padding-bottom: 0;
        }
        #main-container {
            display: flex;
            /*flex: 1;*/
            background-color: rgba(51, 51, 51, 1);
            /*padding: 10px;*/
            flex-wrap: wrap;
            overflow: auto;
            justify-content: space-around;
        }
        #main-container .img-container {
            width: 46%;
            height: 100px;
            background: gray;
            margin-bottom: 10px;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #main-container .img-container video {
            width: 100%;
            height: 100%;
        }

        #main-container .img-container.empty-item {
            background: none;
        }

    </style>



    <div id="main-container" >
        <?php if($house['video']): ?><div class="img-container">
                <img style="position: absolute;width: 13%;" src="/public/Home/images/house/circle.png">
                <img style="position: absolute;transform: rotate(90deg);width: 5%;margin-left: 2px;" src="/public/Home/images/house/trangle.png">
                <video src="<?php echo ($house['video']); ?>"></video>
            </div><?php endif; ?>
        <?php if(is_array($pics)): foreach($pics as $key=>$pic): ?><div class="img-container" style="background-image: url('<?php echo ($pic['path']); ?>')"></div><?php endforeach; endif; ?>
        <?php if((count($pics)+($house['video']!=null?1:0))%2==1) { ?>
            <div class="img-container empty-item">
            </div>
        <?php } ?>
    </div>


    <script>
        // $('#main-container').on('touchmove', function (e) {
        //     e.preventDefault();
        // });
        //
        // $('#main-container').on('scroll', function (e) {
        //     e.preventDefault();
        // });
    </script>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ImageDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: rgba(51, 51, 51, 1);display: flex;flex-direction: column;">
                <div class="header"  style="background-color: rgba(51, 51, 51, 1);position: unset">
                    <div class="button" id="imageDetailBack">
                        <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                    </div>
                    <div class="title">
                        <span id="imageDetailPagination"></span>
                    </div>
                    <div class="button">
                    </div>
                </div>
                <div style="flex:1;display: flex;">
                    <div class="swiper-container" id="imageDetailSwiper">
                        <div class="swiper-wrapper" style="display: flex;">
                            <?php if($house['video']): ?><div class="swiper-slide" style="display: flex;align-items: center;">
                                    <div class="video-container" id="videoDetailContainer">
                                        <img style="position: absolute;" src="/public/Home/images/house/circle.png">
                                        <img style="position: absolute;transform: rotate(90deg);" src="/public/Home/images/house/trangle.png">
                                        <div id="adInterval" class="btn-video" style="justify-self: flex-end;align-self: flex-end; bottom: unset">
                                            <span>广告倒计时<span id="adLeftTime"></span></span>
                                        </div>
                                        <video id="videoDetail" src="<?php echo ($house["video"]); ?>" style="width: 100%;"/>
                                    </div>
                                </div><?php endif; ?>
                            <?php if(is_array($pics)): foreach($pics as $key=>$pic): ?><div class="swiper-slide img-container swiper-zoom-container"
                                     style="background-image: url('<?php echo ($pic["path"]); ?>');
                                    background-repeat: no-repeat;
                                 background-position:center;
                                 background-size: contain;
                                 background: none;
                                 width: 100%;">
                                    <img src="<?php echo ($pic["path"]); ?>"/>
                                </div><?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if($sources && $house['source'] == $sources['Apartment']): ?><div class="modal fade" id="CommissionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="header">
                    <div class="button" id="commissionBack">
                        <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                    </div>
                    <div class="title">
                        <span>佣金规则</span>
                    </div>
                    <div class="button">
                    </div>
                </div>
                <div class="container content-container">
                    <div class="content" style="margin-top: 0px;">
                        <span class="commissionlabel">佣金</span>
                        <span style="font-weight: 700; font-size: 13px; color: #FF6600;margin-left: 15px;">
                            <span><?php echo ($house["commission"]); ?></span>
                        </span>
                    </div>
                    <div class="content">
                        <div class="title">
                            <span class="commissionlabel">佣金说明</span>
                        </div>
                        <span class="commissionRule"><?php echo ($house["commissionRule"]); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div><?php endif; ?>
    <div class="modal fade" id="AppealModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="display: flex;flex-direction: column;">
            <div class="header"  style="position: unset">
                <div class="button" id="AppealModalClose">
                    <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                </div>
                <div class="title">
                    <span>投诉举报</span>
                </div>
                <div class="button">
                </div>
            </div>
            <div style="flex:1;display: flex;">
                <div class="container">
                    <div class="group-container">
                        <div class="group-container">
                            <div class="title"><span>举报内容</span></div>
                            <div class="item">
                                <textarea name="appeal.content"
                                    class="input-container multiple-line-input"
                                    type="text" placeholder="请输入举报内容"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-container" id="SubmitAppeal">
                        提交
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="MapModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="display: flex;flex-direction: column;">
            <div class="header"  style="position: unset">
                <div class="button" id="MapModalClose">
                    <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                </div>
                <div class="title">
                    <span>位置</span>
                </div>
                <div class="button">
                </div>
            </div>
            <div style="flex:1;display: flex;">
                <div class="container" style="margin-bottom: 0px;">
                    <div id="mapContainer" style="height: 100%; width:100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php if($sources && $house['source']!= $sources['Personal']): ?><!-- 客户报备 -->
<div class="modal fade" id="ReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="ReportModalContent" style="display: flex;flex-direction: column;">
        </div>
    </div>
</div><?php endif; ?>


    <script src="/public/Home/js/swiper-4.2.2.min.js"></script>
    <script src="/public/Static/bootstrap/js/bootstrap.js"></script>
    <script>
        $('#videoContainer').click(function ()
        {
            var videoEle = document.getElementById('video');
            if(videoEle.paused)
            {
                videoEle.play();
                $('#videoContainer img').hide();
            }
            else
            {
                $('#videoContainer img').show();
                videoEle.pause();
            }
        });
        // $('[name="goBack"]').on('tap', function () {
        //     alert();
        //     window.history.go(-1);
        // });

        window.onscroll = function()
        {
            if($('body').scrollTop() > 20)
            {
                setVisibility($('#detailHeader'), false);
                setVisibility($('#header'), true);
            }
            else
            {
                setVisibility($('#header'), false);
                setVisibility($('#detailHeader'), true);
            }
        };

        function setVisibility($target, visible) {
            return $target.css('display', visible ? 'flex' : 'none');
        }

        function isVisible($target) {
            return $target.css('display')!='none';
        }
        $('.swiper-container').css('width',  document.body.clientWidth);
        var mySwiper = new Swiper ('#HeaderSwiper', {
            direction: 'horizontal',
            //loop: true,
            width:  document.body.clientWidth,
            // autoplay: {
            //     delay: 3000,
            //     stopOnLastSlide: false,
            //     disableOnInteraction: false,
            // },
            pagination: {
                el: '.swiper-pagination',
                type: 'custom',
                renderCustom: function (swiper, current, total) {
                    return '<div class="page-number">'+current + '/' + total+'</div>';
                }
            },
            on: {
                slideChangeTransitionEnd: function()
                {
                    if(this.activeIndex != 0)
                    {
                        var video = document.getElementById('video');
                        if(video)
                        {
                            $('#videoContainer img').show();
                            video.pause();
                        }
                    }
                },
            },
        });

        $('.picture-container img').click(function () {
            $('#ImageGridModal').modal({
                show: true,
            });
        });

        $('#imageGridBack').click(function () {
            $('#ImageGridModal').modal('hide');
        });

        $('#ImageGridModal .img-container').click(function ()
        {
            var i = $(this).index();
            imgDetailSwiper.slideTo(i);
            $('#ImageDetailModal').modal({show:true});
        });
        $('#imageDetailBack').click(function () {
            $('#ImageDetailModal').modal('hide');
        });
        $("#ImageDetailModal").on("hidden.bs.modal", function() {
            video.pause();
        });
        $('#imageDetailSwiper .img-container').css('height', document.body.clientHeight-40);
        $('#imageDetailSwiper').css('width',  document.body.clientWidth);
        var imgDetailSwiper = new Swiper('#imageDetailSwiper', {
            direction: 'horizontal',
            zoom: true,
            width: document.body.clientWidth,
            pagination: {
                el: '#imageDetailPagination',
                type: 'custom',
                renderCustom: function (swiper, current, total) {
                    return current + '/' + total
                }
            },
            on: {
                slideChangeTransitionEnd: function()
                {
                    if(this.activeIndex != 0)
                    {
                        video.pause();
                    }
                },
            },
        });
        $('#videoDetailContainer').click(function ()
        {
            video.click();
        });

        $('#CommissionRule').click(function ()
        {
            $('#CommissionModal').modal({
                show: true,
            });
        });
        $('#commissionBack').click(function () {
            $('#CommissionModal').modal('hide');
        });
        $('#phone-ask').click(function ()
        {
            $('#ContactModal').modal({
                show: true
            });
        });
        $('#ContactClose').click(function ()
        {
            $('#ContactModal').modal('hide');
        });
        $('[name="houseCard"]').click(function ()
        {
            $.post('<?php echo U("UserCard/add");?>',
                {cardId: $('[name="cardId"]').val()},
                function (resData)
                {
                    if(resData.status == 1)
                    {
                        FkxToast.success(resData.info);
                    }
                    else
                    {
                        FkxToast.error(resData.info);
                    }
                }
            );
        });
        $('#like').click(function ()
        {
            $.post('<?php echo U("Favorite/favor");?>',
                {houseId: <?php echo ((isset($house['id']) && ($house['id'] !== ""))?($house['id']):'0'); ?>, houseType:'RentalHouse'},
                function (resData)
                {
                    if(resData.status == 1)
                    {
                        setVisibility($('#like'), false);
                        setVisibility($('#liked'), true);
                        FkxToast.success(resData.info);
                    }
                    else
                    {
                        FkxToast.error(resData.info);
                    }
                }
            );
        });
        $('#liked').click(function ()
        {
            $.post('<?php echo U("Favorite/cancelFavor");?>',
                {houseId: <?php echo ((isset($house['id']) && ($house['id'] !== ""))?($house['id']):'0'); ?>, houseType:'RentalHouse'},
                function (resData)
                {
                    if(resData.status == 1)
                    {
                        setVisibility($('#liked'), false);
                        setVisibility($('#like'), true);
                        FkxToast.success(resData.info);
                    }
                    else
                    {
                        FkxToast.error(resData.info);
                    }
                }
            );
        });

        $("#online-ask").click(function (e) {
            if ( <?php echo ((isset($houseUserId) && ($houseUserId !== ""))?($houseUserId):'0'); ?> == '<?php echo ($userId); ?>') {
                return alert('这个房子是你自己发布的!');
            }
            // window.location.href = '<?php echo U("Im/detail", array("houseUserId" => $houseUserId?$houseUserId:"0"));?>';
            window.location.href = '<?php echo U("Im/chat", array("houseId" => $house['id'],"houseType" => $houseType));?>';
        });

        $("#VideoPlayBtn").click(function () {
            imgDetailSwiper.slideTo(0);
            $('#ImageDetailModal').modal({show:true});
            video.play();
        });
        var video = (function ()
        {
            var videoElem = document.getElementById('videoDetail');
            var mediaAdPath = '<?php echo ($mediaAd["filepath"]); ?>';
            var mediaAdId = '<?php echo ($mediaAd["id"]); ?>';
            var houseVideo = '<?php echo ($house["video"]); ?>';
            var hasAd = false;
            var adPlayEnd = false;
            var initPlay = true;
            if(mediaAdPath)
            {
                hasAd = true;
                $(videoElem).attr('src', mediaAdPath);
            }
            else
            {
                $(videoElem).attr('src', houseVideo);
            }
            var adInterval;
            if(videoElem)
            {
                videoElem.addEventListener('ended', function (e,v)
                {
                    if(hasAd && !adPlayEnd)
                    {
                        clearInterval(adInterval);
                        $('#adInterval').hide();
                        adPlayEnd = true;
                        $(videoElem).attr('src', houseVideo);
                        play();
                    }
                });
                if(hasAd)
                {
                    adInterval = setInterval(function ()
                    {
                        $('#adLeftTime').text(Math.ceil(videoElem.duration - videoElem.currentTime));
                    }, 500);
                }
                else
                {
                    $('#adInterval').hide();
                }

            }
            function play()
            {
                if(videoElem)
                {
                    $('#videoDetailContainer img').hide();
                    videoElem.play();
                }
                if(hasAd && initPlay)
                {
                    initPlay = false;
                    $.post('<?php echo U("MediaAd/count");?>', {mediaAdId: mediaAdId});
                }
            }
            function pause()
            {

                if(videoElem)
                {
                    $('#videoDetailContainer img').show();
                    videoElem.pause();
                }
            }
            function click()
            {
                if(!videoElem)
                    return;
                if(videoElem.paused)
                {
                    play();
                }
                else
                {
                    pause();
                }
            }
            return {
                play,
                pause,
                click
            }
        })();

    </script>
    <script type="text/javascript">
    $(function () {
        $('#report-text').click(function ()
        {
            $('#AppealModal').modal({
                show: true
            });
        });
        $('#AppealModalClose').click(function ()
        {
            $('#AppealModal').modal('hide');
        });
        $("#AppealModal").on("hidden.bs.modal", function() {
            $('[name="appeal.content"]').val("")
        });
        $('#SubmitAppeal').click(function ()
        {
            var houseId = $('[name="houseId"]').val(),
                houseType = $('[name="houseType"]').val(),
                content = $('[name="appeal.content"]').val();
            $.ajax({
                url: "<?php echo U('Appeal/submit');?>",
                data: {content: content,houseId: houseId, houseType: houseType},
                type: 'POST',
                success: function (resData)
                {
                    if(resData.status == 1)
                    {
                        FkxToast.success(resData.info);
                        $('#AppealModal').modal('hide');
                    }
                    else
                    {
                        FkxToast.error(resData.info);
                    }
                },
                error: function ()
                {
                    FkxToast.error('提交失败！');
                }
            });
        });
    });
</script>
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?php echo ($BaiduMapAk); ?>"></script>
<script type="text/javascript">
$(function ()
{
    $('#showMap').click(function ()
    {
        $('#MapModal').modal({
            show: true
        });
    });
    $('#MapModalClose').click(function ()
    {
        $('#MapModal').modal('hide');
    });
    $("#MapModal").on("hidden.bs.modal", function() {

    });
    $("#MapModal").on("show.bs.modal", function() {
        showMap();
    });
    var showMap = (function()
    {
        var mapInit = false;
        function show()
        {
            if(mapInit)
            {
                return;
            }
            var map = new BMap.Map("mapContainer");    // 创建Map实例
            map.centerAndZoom('厦门', 15);  // 初始化地图,设置中心点坐标和地图级别

            //添加地图类型控件
            map.addControl(new BMap.MapTypeControl({
                mapTypes:[
                    BMAP_NORMAL_MAP,
                    BMAP_HYBRID_MAP
                ]}));
            map.addControl(new BMap.NavigationControl({
                anchor: BMAP_ANCHOR_TOP_LEFT,
                type:BMAP_NAVIGATION_CONTROL_LARGE,
            }));
            map.setCurrentCity("厦门");          // 设置地图显示的城市 此项是必须设置的
            map.enableScrollWheelZoom(true);

            var marker = null;
            function addMarker(point)
            {
                map.removeOverlay(marker);
                marker = new BMap.Marker(point);        // 创建标注
                map.addOverlay(marker);
            }
            map.addEventListener('tilesloaded', function () {
                if(!mapInit)
                {
                    mapInit = true;
                    if(parseFloat($('[name="latitude"]').val())>0 && parseFloat($('[name="longitude"]').val())>0)
                    {
                        var p = {lat:parseFloat($('[name="latitude"]').val()), lng: parseFloat($('[name="longitude"]').val())};
                        map.setCenter(new BMap.Point(p.lng, p.lat));
                        addMarker(p);
                    }
                    else
                    {
                        var geolocation = new BMap.Geolocation();
                        geolocation.getCurrentPosition(function(r){
                            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                                map.panTo(r.point);
                            }
                        },{enableHighAccuracy: true});
                    }
                }

            });
        }
        return show;
    })();

});
</script>
    <?php if($sources && $house['source']!= $sources['Personal']): ?><script type="text/javascript">
$(function ()
{
    $('#record').click(function ()
    {
        $('#ReportModal').modal({
            show: true
        });
    });

    $("#ReportModal").on("show.bs.modal", function() {
        $.ajax({
            url:'<?php echo U("Report/report");?>',
            success:function (html)
            {
                $('#ReportModalContent').html(html);
            }
        }).
        then(function ()
        {
            if($('[name="houseType"]').val() == 'RentalHouse')
            {
                $('.report-huxing').hide();
                $('.report-zhiye').hide();
            }
        }).
        then(function ()
        {
            $('#ReportModalClose').click(function ()
            {
                $('#ReportModal').modal('hide');
            });
            $('#SubmitReport').click(function ()
            {
                try {
                    if(!document.getElementById('ReportForm').reportValidity())
                    {
                        return;
                    }
                } catch (e) {
                    if(!document.getElementById("NameInput").value)
                    {
                        alert("请输入姓名");
                        return;
                    }
                    if(!document.getElementById("PhoneInput").value)
                    {
                        alert("请输入手机号");
                        return;
                    }
                }
                var data = $('#ReportForm').serialize()+'&houseId='+$('[name="houseId"]').val()+'&houseType='+$('[name="houseType"]').val();
                $.ajax({
                    url:'<?php echo U("Report/report");?>',
                    type:'POST',
                    data: data,
                    success:function (resData)
                    {
                        if(resData.status == 1)
                        {
                            FkxToast.success(resData.info);
                            $('#ReportModal').modal('hide');
                        }
                        else
                        {
                            FkxToast.error(resData.info);
                        }
                    },
                    error: function ()
                    {
                        FkxToast.error('提交失败！');
                    }
                });
            });
        });
    });
});
</script><?php endif; ?>

<script>
$(".goBackBtn").click(function () {
    window.history.back();
});
(function () {
    var path_info = $('#path_info_').val() == 'main' ? 'Index/index' : $('#path_info_').val();
    var index_list = path_info + '/list';
    var index_top = path_info + '/top';
    var index_current_page = path_info + '/currentPage';
    var back = true;
    function historyTopAndContent() {
        if ( back ) {
            setTimeout(function() {
                $(document).scrollTop(window.sessionStorage.getItem(index_top));
            }, 100);
            if (window.sessionStorage.getItem(index_list)) {
                $('.storageRange').html(window.sessionStorage.getItem(index_list));
            }
            back = false;
            return false;
        } 
    }
    historyTopAndContent();
    $(window).scroll(function () {
        if ($('.storageRange').html() != undefined) {
            window.sessionStorage.setItem(index_list, $('.storageRange').html());
        }
        window.sessionStorage.setItem(index_top, $(document).scrollTop());
    });
})();

// if(navigator && navigator.geolocation)
// {
//     navigator.geolocation.getCurrentPosition(function(position){
//         if(position && position.coords)
//         {
//             console.log('longitude:', position.coords.longitude, 'latitude:', position.coords.latitude);
//             $.post('<?php echo U("Location/report");?>',{longitude: position.coords.longitude, latitude: position.coords.latitude});
//         }
//     }, function(err){console.log(err);});
// }
</script>
</body>
</html>