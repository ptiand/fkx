<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>发布租房</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/public/Home/css/swiper-4.2.2.min.css">
    <link rel="stylesheet" href="/public/Static/layer_mobile/need/layer.css">
    <link rel="stylesheet" href="/public/Home/css/index.css">
    <link rel="stylesheet" href="/public/Home/css/publish.css">


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

    <div class="header">
        <div class="button">
            <img src="/public/Home/images/nav_back_icon.png" alt="" id="NavBackImg" width="22" height="22"/>
        </div>
        <div class="title">
            <span>发布租房</span>
        </div>
        <?php if($house['id'] != null): ?><div class="button" id="HeaderDeleteBtn">
                删除
            </div>
            <?php else: ?>
            <div class="button" >
            </div><?php endif; ?>
    </div>


    <style>
        html body .container {
            padding: 40px 0 50px 0;
        }
        .modal-dialog {
            margin: 0px;
        }
        .modal-dialog .modal-content{
            background-color: rgba(242, 242, 242, 1);
            border: none;
        }
        body .modal {
            overflow-y: auto;
        }
        #HeaderDeleteBtn{
            color: #ff0000;
        }
    </style>
    <form class="container" id="Main-Form">
        <input value="<?php echo ($house['id']); ?>" name="id" id="HouseId" hidden/>
        <input value="<?php echo ($houseType); ?>" id="HouseType" hidden/>
        <?php if($shop_house): ?><input value="1" name="is_shop_house" type="hidden"/><?php endif; ?>
        <input name="longitude" type="hidden" value="<?php echo ($house['longitude']); ?>"/>
        <input name="latitude" type="hidden" value="<?php echo ($house['latitude']); ?>"/>
        <div class="group-container">
            <div class="title"><span>基本信息</span></div>

            <div class="item">
                <span class="item-label">房源名称</span>
                <input class="input-container" type="text" name="name" value="<?php echo ($house['name']); ?>" id="NameInput" required placeholder="请输入"/>
                <span class="item-tips">0/64</span>
            </div>

            <div class="seperator"></div>

            <div class="item">
                <span class="item-label">建筑类型</span>
                <!--<input class="input-container" type="text" placeholder="请选择"/>-->
                <select class="input-container" name="type" required>
                    <?php if(is_array($buildingTypeList)): foreach($buildingTypeList as $key=>$type): ?><option value="<?php echo ($type['id']); ?>"><?php echo ($type['type_name']); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>

            <div class="seperator"></div>

            <div class="item">
                <span class="item-label">房屋户型</span>
                <div class="input-container multiple-line">
                    <?php $numList=array(0, 1, 2, 3, 4, 5, 6, 7, 8)?>
                    <div class="inner-container">
                        <!--<input class="inner-input" type="number" placeholder="请选择"/>-->
                        <select class="inner-input" name="houseRoom" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($house['houseRoom']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">室</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="livingRoom" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($house['livingRoom']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">厅</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="restRoom" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($house['restRoom']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">卫</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="diningRoom" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($house['diningRoom']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">厨</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="veranda" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($house['veranda']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">阳台</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                </div>

            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">装修标准</span>
                <select class="input-container" name="renovationStandard" required>
                    <?php if(is_array($decorationTypeList)): foreach($decorationTypeList as $key=>$type): ?><option value="<?php echo ($type['id']); ?>" <?php echo ($house['renovationStandard']==$type['id']?'selected':''); ?>><?php echo ($type['zxiu_name']); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
        </div>

        <div class="group-container">
            <div class="title">报价信息</div>
            <div class="item">
                <span class="item-label">住房面积</span>
                <input class="input-container" type="number" name="square" value="<?php echo ($house['square']); ?>" required placeholder="请输入"/>
                <span class="item-tips">平方米(㎡)</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">租金单价</span>
                <input class="input-container" type="number" name="price" value="<?php echo ($house['price']); ?>" required placeholder="请输入"/>
                <span class="item-tips">元/月</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">租赁方式</span>
                <select class="input-container" name="rentalType" required>
                    <?php if(is_array($rentalTypeList)): foreach($rentalTypeList as $id=>$rentalType): ?><option value="<?php echo ($id); ?>" <?php echo ($house['rentalType']==$id?'selected':''); ?>><?php echo ($rentalType); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
        </div>

        <div class="group-container">
            <div class="title width-tail"><span>视频/相册</span><span class="item-tips" id="ImagesPercentage">0/8</span></div>
            <div class="item image-item">
                <div class="images-container">
                    <?php if(!empty($videoPath)): ?><span class="images-item"><video class="image-container" src="<?php echo ($videoPath); ?>" ></video></span><?php endif; ?>
                    <?php if(is_array($imagePaths)): foreach($imagePaths as $key=>$path): ?><span class="images-item"><img class="image-container" src="<?php echo ($path); ?>" /></span><?php endforeach; endif; ?>
                    <span class="images-item add-image" id="Add-Image"><img src="/public/Home/images/icon-addimg.png" /></span>
                </div>
            </div>
        </div>

        <div class="group-container">
            <div class="title width-tail"><span>房屋介绍</span><span class="item-tips" id="IntroduceTips">0/300</span></div>

            <div class="item">
                <textarea
                    id="IntroduceTextArea"
                    class="input-container multiple-line-input"
                    maxlength="300"
                    type="text" placeholder="如房屋介绍、亮点"
                    name="introduce"></textarea>
            </div>
        </div>

        <!--
        <div class="group-container">
            <div class="title width-tail"><span>我要说</span><span class="item-tips">0/114</span></div>

            <div class="item">
                <textarea
                    class="input-container multiple-line-input"
                    maxlength="114"
                    type="text" placeholder="如房屋介绍、亮点"></textarea>
            </div>
        </div>
        -->
    </form>


    <div class="bottom-container" id="to-step-2">
        下一步，填写楼房信息
    </div>


    <div class="modal fade" id="Modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="Step2Modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="header"  style="">
                    <div class="button" id="Step2-Back">
                        <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                    </div>
                    <div class="title">
                        <span>发布租房</span>
                    </div>
                    <div class="button" id="Skip-Btn">
                        跳过
                    </div>
                </div>
                <form class="container content-container" id="Step-2-Form">
                    <style type="text/css">
.header .button {margin-right: 10px;}
</style>
<div class="group-container">
    <div class="title"><span>房屋</span></div>

    <div class="item">
        <span class="item-label">所在小区</span>
        <input class="input-container" type="text" name="xq" value="<?php echo ($others->xq); ?>" placeholder="请输入"/>
    </div>

    <div class="seperator"></div>

    <div class="item">
        <span class="item-label">限制要求</span>
        <input class="input-container" type="text" name="xz" value="<?php echo ($others->xz); ?>" placeholder="请输入"/>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>

    <div class="seperator"></div>

    <div class="item">
        <span class="item-label">房屋朝向</span>
        <select class="input-container" name="cx">
            <?php if(is_array($directionList)): foreach($directionList as $key=>$direction): ?><option value="<?php echo ($direction); ?>" <?php echo ($others->cx==$direction?'selected':''); ?>><?php echo ($direction); ?></option><?php endforeach; endif; ?>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>

    <div class="seperator"></div>

    <div class="item">
        <span class="item-label">所在楼层</span>
        <input class="input-container" type="text" name="lc" value="<?php echo ($others->lc); ?>" placeholder="请输入"/>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>

    <div class="seperator"></div>

    <div class="item">
        <span class="item-label">有无电梯</span>
        <select class="input-container" name="dt">
            <option value="有" <?php echo ($others->cx=='有'?'selected':''); ?>>有</option>
            <option value="无" <?php echo ($others->cx=='无'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
</div>

<div class="group-container">
    <div class="title"><span>配套信息</span></div>

    <div class="item">
        <span class="item-label">床铺</span>
        <select class="input-container ptxx" name="c">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->c=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->c=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">卫生间</span>
        <select class="input-container ptxx" name="wsj">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->wsj=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->wsj=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">宽带</span>
        <select class="input-container ptxx" name="kd">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->kd=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->kd=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">衣柜</span>
        <select class="input-container ptxx" name="yg">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->yg=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->yg=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">座椅</span>
        <select class="input-container ptxx" name="zy">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->zy=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->zy=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">沙发</span>
        <select class="input-container ptxx" name="sf">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->sf=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->sf=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">空调</span>
        <select class="input-container ptxx" name="kt">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->kt=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->kt=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">热水器</span>
        <select class="input-container ptxx" name="rsq">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->rsq=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->rsq=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">洗衣机</span>
        <select class="input-container ptxx" name="xyj">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->xyj=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->xyj=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">电视</span>
        <select class="input-container ptxx" name="ds">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->ds=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->ds=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">冰箱</span>
        <select class="input-container ptxx" name="bx">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->bx=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->bx=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">微波炉</span>
        <select class="input-container ptxx" name="wbl">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->wbl=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->wbl=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">阳台</span>
        <select class="input-container ptxx" name="yt">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->yt=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->yt=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">暖气</span>
        <select class="input-container ptxx" name="nq">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->nq=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->nq=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>
    <div class="item">
        <span class="item-label">做饭</span>
        <select class="input-container ptxx" name="kzf">
            <option value="0" >请选择</option>
            <option value="1" <?php echo ($others->ptxx->kzf=='1'?'selected':''); ?>>有</option>
            <option value="0" <?php echo ($others->ptxx->kzf=='0'?'selected':''); ?>>无</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="seperator"></div>

</div>
                    <div class="bottom-container" id="to-step-3" style="z-index: 99999;">
                        下一步，联系信息
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Step3Modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="header" id="page-header"  style="">
                    <div class="button" id="Step3-Back">
                        <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                    </div>
                    <div class="title">
                        <span>发布租房</span>
                    </div>
                    <div class="button">
                    </div>
                </div>
                <form class="container content-container" id="Step-3-Form">
                    <div class="group-container">
    <div class="title"><span>房屋地址</span></div>

    <div class="item">
        <span class="item-label">市、区</span>
        <Select class="input-container" name="cityId" id="CitySelect" autofocus required>
            <option value="">请选择</option>
            <?php if(is_array($districtList)): foreach($districtList as $key=>$district): if(($district['pid']) == "0"): ?><option value="<?php echo ($district['id']); ?>"
                            <?php echo ($house['cityId']==$district['id']?"selected":""); ?>><?php echo ($district['city_name']); ?></option><?php endif; endforeach; endif; ?>
        </Select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
        <Select class="input-container" name="areaId" id="AreaSelect" required>
            <option value="">请选择</option>
            <?php if(is_array($districtList)): foreach($districtList as $key=>$district): if(($district['pid']) != "0"): ?><option class="area-option hide-option" pid="<?php echo ($district['pid']); ?>" value="<?php echo ($district['id']); ?>"
                            <?php echo ($house['areaId']==$district['id']?"selected":""); ?>><?php echo ($district['city_name']); ?></option><?php endif; endforeach; endif; ?>
        </Select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
    <div class="item">
        <span class="item-label">详细地址</span>
        <input class="input-container" type="text" name="address" value="<?php echo ($house['address']); ?>" placeholder="请输入" required/>
    </div>
    <div class="item">
        <span class="item-label">地图选址</span>
        <?php
 $isSelected = false; if($house['longitude'] > 0 && $house['latitude'] > 0) { $isSelected = true; } ?>
        <input class="input-container" type="text" name="" id="SelectMap" placeholder="请选择" value="<?php echo ($isSelected?'已选择':''); ?>" readonly/>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
</div>

<div class="group-container">
    <div class="title"><span>联系信息</span></div>

    <div class="item">
        <span class="item-label">联系姓名</span>
        <input class="input-container" type="text" name="lxxm" value="<?php echo ($others->lxxm); ?>" placeholder="请输入" required/>
    </div>

    <div class="seperator"></div>

    <div class="item">
        <span class="item-label">联系电话</span>
        <input class="input-container" type="text" name="lxdh" value="<?php echo ($others->lxdh); ?>" placeholder="请输入" required/>
    </div>

    <div class="seperator"></div>

    <div class="item" style="display: none;">
        <span class="item-label">是否显示</span>
        <select class="input-container" name="xsyz" required>
            <option value="1" <?php echo ($others->xsyz=='1'?'selected':''); ?>>显示</option>
            <option value="0" <?php echo ($others->xsyz=='0'?'selected':''); ?>>不显示</option>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>

</div>
<div class="item-info" style="display: none;">*个人号码如隐藏，客户可通过微聊咨询您，或申请房客行服务。</div>
<div class="group-container">
    <div class="title"><span>当前状态</span></div>

    <div class="item">
        <span class="item-label">当前状态</span>
        <select class="input-container" name="state">
            <?php if(is_array($StateCaption)): foreach($StateCaption as $id=>$state): ?><option value="<?php echo ($id); ?>"  <?php echo ($house['state']==$id?'selected':''); ?>><?php echo ($state); ?></option><?php endforeach; endif; ?>
        </select>
        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
    </div>
</div>

                    <div class="bottom-container" id="Submit" style="z-index: 99999;">
                        保存并发布
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ImageViewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="header" style="">
                    <div class="button" id="Image-View-Back">
                        <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                    </div>
                    <div class="title">
                        <span>视频/相册</span>
                    </div>
                    <div class="button" id="CompleteBtn">
                        完成
                    </div>
                </div>
                <div class="container content-container">
                    <div class="image-view-container">
                        <div class="image-list">
                            <?php if(!empty($videoPath)): ?><video class="image-container" src="<?php echo ($videoPath); ?>" ></video><?php endif; ?>
                            <?php if(is_array($imagePaths)): foreach($imagePaths as $key=>$path): ?><img class="image-container" src="<?php echo ($path); ?>" /><?php endforeach; endif; ?>
                        </div>
                        <div class="Image-Swiper">
                            <div class="swiper-container" id="imageDetailSwiper">
                                <div class="swiper-wrapper" style="display: flex;">
                                    <?php if(!empty($videoPath)): ?><div class="swiper-slide img-container"
                                             style=" width: 100%; height: 100%;"><video class="image-container" style="width: 100%;" src="<?php echo ($videoPath); ?>" ></video></div><?php endif; ?>
                                    <?php if(is_array($imagePaths)): foreach($imagePaths as $key=>$path): ?><div class="swiper-slide img-container"
                                             style=" width: 100%; height: 100%;"><img class="image-container" style="width: 100%;" src="<?php echo ($path); ?>" /></div><?php endforeach; endif; ?>
                                </div>
                            </div>
                        </div>
                        <span class="image-index"></span>
                        <span class="image-delete">删除当前</span>
                    </div>
                    <div class="bottom-container" style="z-index: 99999;">
                        <span class="box-item" id="Upload-Video">上传视频</span>
                        <span class="box-item" id="Upload-Image">上传图片</span>
                    </div>
                    <form enctype="multipart/form-data">
                        <input id="Upload-Input" style="display: none;" type="file" name="image" multiple="" accept="image/*" />
                        <input id="Upload-Video-Input" style="display: none;" type="file" name="video" accept="video/*" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<div class="modal fade" id="MapModal" tabindex="4" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="header" style="">
                <div class="button" id="MapModalClose">
                    <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
                </div>
                <div class="title">
                    <span>房屋地址</span>
                </div>
                <div class="button" id="MapPositive">
                    <span>完成</span>
                </div>
            </div>
            <div class="content-container" style="display: flex; justify-content: center; align-items: center">
                <div id="mapContainer" style="height: 100%; width:100%;"></div>
            </div>
        </div>
    </div>
</div>



    <script src="/public/Home/js/swiper-4.2.2.min.js"></script>
    <script src="/public/Static/bootstrap/js/bootstrap.js"></script>
    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script>

        $('#Step2Modal').on('touchmove', function (e) {
            console.log(".............");
            // e.preventDefault();
        });

        $('#Step2Modal').on('scroll', function (e) {
            console.log(">>>>>>>>>>>>>");
            // e.preventDefault();
        });

        var IMAGE_MAX_LENGTH = 8;
        var IMAGE_URL_LIST = [];
        var VIDEO_URL = null;

        $('#NavBackImg').click(function () {
            window.history.back(-1);
        });

        $("#HeaderDeleteBtn").click(function () {
            // if (confirm('确定删除该房源吗？' )) {
            //     // console.log('确定');
            //     return $.post('<?php echo U("Publish/delete");?>', {
            //         id: $("#HouseId").val(),
            //         type: $('#HouseType').val()
            //     }, function (e) {
            //         if (e.status == '1') {
            //             alert('删除成功！');
            //             // window.location.href = '<?php echo U("Center/mypublish");?>';
            //             window.history.back();
            //         } else {
            //             alert('删除失败！请稍后重试。');
            //         }
            //     });
            // }
            layer.open({
                content: '确定删除该房源吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    return $.post('<?php echo U("Publish/delete");?>', {
                        id: $("#HouseId").val(),
                        type: $('#HouseType').val()
                    }, function (e) {
                        layer.close(index);
                        if (e.status == '1') {
                            alert('删除成功！');
                            window.location.href = '<?php echo U("Center/mypublish");?>';
                            // window.history.back();
                        } else {
                            alert('删除失败！请稍后重试。');
                        }
                    });
                }
            });
            // console.log('取消');
        });

        $("#Add-Image").click(function() {
            $('#ImageViewModal').modal({
                show: true,
            });
        });
        $("#to-step-2").click(function () {
            var form = document.getElementById("Main-Form");
            try{
                if (form.reportValidity()) {
                    $('#Step2Modal').modal('show');
                }
            } catch(e) {
                console.log(e);
                $('#Step2Modal').modal('show');
            }
        });

        $("#Skip-Btn").click(function () {
            $('#Step3Modal').modal('show');
        });

        $("#Step2-Back").click(function () {
            $('#Step2Modal').modal('hide');
        });

        $("#to-step-3").click(function () {
            $('#Step3Modal').modal('show');
        });

        $("#Step3-Back").click(function () {
            // $('#Step2Modal').modal('hide');
            $('#Step3Modal').modal('hide');
            // $('#Step2Modal').modal({
            //     show: true,
            // });
        });

        $("#Image-View-Back").click(function () {
            $("#ImageViewModal").modal("hide");
        });

        $("#CompleteBtn").click(function () {
            $("#ImageViewModal").modal("hide");
        });

        $("#CitySelect").change(function () {
            var cityId = $(this).val();
            if (!cityId) {
                return;
            }
            $('#AreaSelect').val('');
            $('.hide-option').removeClass('hide-option');
            $(".area-option").each(function (e) {
                if ($(this).attr('pid') != cityId) {
                    $(this).addClass('hide-option');
                };
            });
            $("#AreaSelect").select();
        });

        $("#Submit").click(function () {
            submit();
        });

        function updateImagesPercentage() {
            $("#ImagesPercentage").text(`${IMAGE_URL_LIST.length+(VIDEO_URL?1:0)}/${IMAGE_MAX_LENGTH}`);
        }

        function submit() {
            var step3form = document.getElementById("Step-3-Form");
            try {
                if (!step3form.reportValidity()) {
                    return;
                }
            } catch (e) {

            }
            var $mainForm = $('#Main-Form');
            var $step2Form = $('#Step-2-Form');
            var $step3Form = $('#Step-3-Form');

            var formData = {};
            var othersData = {};
            buildFormData($mainForm.serializeArray(), formData);
            buildFormData($step2Form.serializeArray(), othersData);
            buildFormData($step3Form.serializeArray(), othersData);
            formData['state'] = othersData.state;
            formData['address'] = othersData.address;
            formData['cityId'] = othersData.cityId;
            formData['areaId'] = othersData.areaId;
            delete othersData['state'];
            delete othersData['address'];
            delete othersData.cityId;
            delete othersData.areaId;
            var ptxx = {};
            $('.ptxx').each(function (i, e) {
                delete othersData[$(this).attr('name')];
                ptxx[$(this).attr('name')] = $(this).val();
            });
            othersData['ptxx'] = ptxx;
            formData['others'] = othersData;
            // if ($(".images-container").find('video').length) {
            //     formData['video'] = IMAGE_URL_LIST[0];
            //     IMAGE_URL_LIST.shift();
            // }
            formData['video'] = VIDEO_URL;
            formData['images'] = IMAGE_URL_LIST;
            console.log(">>>", formData);
            // if (confirm("确定发布？")) {
            //     $.post('<?php echo U("Publish/rentalHouseSubmit");?>', formData, function (e) {
            //         // console.log('hello world', e);
            //         if (e.status) {
            //             alert('发布成功');
            //             window.location.href = "/index";
            //         }
            //     });
            // }

            layer.open({
                content: '确定发布？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post('<?php echo U("Publish/rentalHouseSubmit");?>', formData, function (e) {
                        // console.log('hello world', e);
                        layer.close(index);
                        if (e.status) {
                            alert('发布成功');
                            if ('<?php echo ($shop_house); ?>'){
                                window.location.href = '<?php echo U("Service/house_setup");?>';
                            } else {
                                window.location.href = "/index";
                            }
                        }else {
                            alert('发布失败，请稍后重试！');
                        }
                    });

                }
            });

            function buildFormData(serializeArray, targetObj) {
                for (var item of serializeArray) {
                    targetObj[item.name] = item.value;
                }
                return targetObj;
            }
        }

        /*----on edit---*/
        (function () {
            $("#IntroduceTextArea").val("<?php echo ($house['introduce']); ?>");
            $(".images-item:not(.add-image)").click(function () {
                onFormImageClick($(this));
            });
            $(".images-item:not(.add-image)").each(function (e) {
                var $elem = $(this).find('img');
                if ($elem.length) {
                    IMAGE_URL_LIST.push($elem.attr('src'));
                }
            });
            $(".images-item:not(.add-image)").each(function (e) {
                var $elem = $(this).find('video');
                if($elem.length) {
                    VIDEO_URL = $elem.attr('src');
                }
            });
            updateImagesPercentage();
            $('.image-list').children().click(function () {
                onModalImageClick($(this));
            });
            if("<?php echo ($house['videoPath']); ?>") {
                $(".swiper-wrapper").prepend(genSwiperItem('video', "<?php echo ($house['videoPath']); ?>"));
            }
            // genSwiperItem();

        })();
        /*-------------------*/
        (function () {
            var clientHeight = document.body.clientHeight;
            var clientWidth = document.body.clientWidth;
            $(".modal-dialog").css('min-height', clientHeight);
            $(".modal-dialog .modal-content").css('min-height', clientHeight);

            $(".Image-Swiper").css({'width': clientWidth, 'height': clientWidth});
        })();

        var swiper = new Swiper ('.swiper-container', {
            direction: 'horizontal',
            width: document.body.clientWidth,
            pagination: {
                el: '.image-index',
                type: 'custom',
                renderCustom: function (swiper, current, total) {
                    return current + '/' + total;
                }
            }
        });

        (function () {

            swiper.on('slideChange', function () {
                $('.active-image').removeClass('active-image');
                $(".image-list").find('.image-container').eq(swiper.activeIndex).addClass('active-image');
            });

            var imageUpload = document.getElementById('Upload-Input');
            var videoUpload = document.getElementById('Upload-Video-Input');

            $("#Upload-Video").click(function () {
                if (!imagesLengthValidate()) {
                    return alert('最多上传8张图片/视频');
                }
                $(videoUpload).click();
            });

            $("#Upload-Image").click(function () {
                if (!imagesLengthValidate()) {
                    return alert('最多上传8张图片/视频');
                }
                $(imageUpload).click();
            });

            $(".image-delete").click(function () {
                if (IMAGE_URL_LIST.length!=0 ) {
                    // if (confirm('确定删除当前图片/视频?')) {
                    //     var index = swiper.activeIndex;
                    //     removeImage(index);
                    //     removeFormImage(index);
                    // }
                    layer.open({
                        content: '确定删除当前图片/视频?'
                        ,btn: ['确定', '取消']
                        ,yes: function(index){
                            layer.close(index);
                            var aindex = swiper.activeIndex;
                            removeImage(aindex);
                            removeFormImage(aindex);
                        }
                    });
                }
            });

            imageUpload.onchange = function (e){onUploadInputChange(e, false, function () {
                imageUpload.value = '';
            })};
            videoUpload.onchange = function (e){onUploadInputChange(e, true, function () {
                videoUpload.value = '';
            })};

            function imagesLengthValidate() {
                return IMAGE_URL_LIST.length+(VIDEO_URL?1:0) < IMAGE_MAX_LENGTH;
            }

            // 注册并更新input后面那个n/m
            function onInputKeyUp($input, $label, maxLength) {
                $input.on('keyup', function (e) {
                    $label.text(`${$(this).val().length}/${maxLength}`);
                }).keyup();
            }
            (function () {
                onInputKeyUp($("#NameInput"), $("#NameInput").next(), 64);
                onInputKeyUp($("#IntroduceTextArea"), $("#IntroduceTips"), 300);
            })();

            function onUploadInputChange(event, isVideo, onFinish) {
                transformFileToFormData(event.target.files, isVideo);

                function onUploadFinished(url) {
                    var tag = '';
                    if (isVideo) {
                        // IMAGE_URL_LIST.unshift(url);
                        VIDEO_URL = url;
                        tag = 'video';
                        $(".swiper-wrapper video").parent().remove();
                        $(".swiper-wrapper").prepend(genSwiperItem(tag, url));
                    } else {
                        IMAGE_URL_LIST.push(url);
                        tag = 'img';
                        $(".swiper-wrapper").append(genSwiperItem(tag, url));
                    }

                    function genSwiperItem(tag, url) {
                        return `<div class="swiper-slide img-container"
                                     style=" width: 100%; height: 100%;"><${tag} class="image-container" style="width: 100%;" src="${url}" /></div>`;
                    }

                    swiper.updateSlides();
                    var $listItem = $(`<${tag} class="image-container" src="${url}" />`);
                    $listItem.click(function () {
                        onModalImageClick($listItem);
                    });
                    appendImageList($listItem, isVideo);
                    var $listItem2 = $(`<span class="images-item"><${tag} class="image-container" src="${url}" /></span>`);
                    $listItem2.click(function () {
                        onFormImageClick($listItem);
                    });
                    appendFormImageList($listItem2, isVideo);
                    updateImagesPercentage();
                    onFinish();
                }

                // 上传图片
                function upload (formData, isVideo) {
                    var startTime = Date.now();
                    layer.open({
                        type: 2,
                        content: '上传中，请稍候',
                        shadeClose: false
                    });
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e){console.log('上传中 ', e.loaded / e.total*100+"%")}, false);
                    xhr.addEventListener('load', function(){console.log("加载中");}, false);
                    xhr.addEventListener('error', function(){console.error("上传失败！");}, false);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4) {
                            layer.closeAll();
                            var result = JSON.parse(xhr.responseText);
                            if (xhr.status === 200) {
                                // 上传成功
                                console.log("result <>>>", result);
                                if (result.status == 1) {
                                    for(var i=0; i< result.data.length;i++){
                                        onUploadFinished(result.data[i]);
                                    }
                                } else {
                                    alert('上传失败！请稍后重试！');
                                }
                            } else {
                                // 上传失败
                                alert('failed');
                            }
                        }
                    };
                    var path = isVideo ? '<?php echo U("Publish/uploadVideo");?>' : '<?php echo U("Publish/uploadPic");?>';
                    xhr.open('POST', path , true);
                    xhr.send(formData);
                }

                function transformFileToFormData (file, isVideo) {
                    var type = isVideo? "video/*" : "image/*";
                    var formData = new FormData();
                    //formData.append('type', file.type);
                    //formData.append('size', file.size || type);
                    //formData.append('name', file.name);
                    //formData.append('lastModifiedDate', file.lastModifiedDate);
                    //formData.append('file', file);
                    for (var i = 0; i <file.length; i++) {
                        formData.append(["file["+i+"]"], file[i]);
                    }
                    upload(formData, isVideo);
                }
            }

            function appendImageList(elem, isVideo) {
                if (isVideo) {
                    $(".image-list video:eq(0)").remove();
                    $(".image-list").prepend(elem);
                } else
                {
                    $(".image-list").append(elem);
                }
            }

            function appendFormImageList(elem, isVideo) {
                if (isVideo) {
                    $(".images-container .images-item:eq(0)").find('video').parent().remove();
                    $(".images-container").prepend(elem);
                } else {
                    $("#Add-Image").before(elem);
                }
            }

            function removeImage(i) {
                var $elem = $(".image-list").find('.image-container').eq(i);
                if ($elem.prop('tagName').toLowerCase() == 'video') {
                    VIDEO_URL = null;
                } else {
                    IMAGE_URL_LIST.splice(i, 1);
                }
                swiper.removeSlide(i);
                $elem.remove();
            }

            function removeFormImage(i) {
                $(".images-container").find('.images-item').eq(i).remove();
                updateImagesPercentage();
            }

        })();

        function onFormImageClick($image) {
            var index = $image.index();
            swiper.slideTo(index);
            onModalImageClick($(`.image-list .image-container:eq(${index})`));
            $('#ImageViewModal').modal({ show: true, });
        }

        function onModalImageClick($image) {
            $('.active-image').removeClass('active-image');
            $image.addClass('active-image');
            swiper.slideTo($image.index());
        }

        function genSwiperItem(tag, url) {
            return `<div class="swiper-slide img-container"
                                     style=" width: 100%; height: 100%;"><${tag} class="image-container" style="width: 100%;" src="${url}" /></div>`;
        }

    </script>
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?php echo ($BaiduMapAk); ?>"></script>
<script type="text/javascript">
$(function ()
{
    $('#SelectMap').click(function ()
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
        var map;
        var markLng, markLat;
        function show()
        {
            if(mapInit)
            {
                return;
            }
            map = new BMap.Map("mapContainer");    // 创建Map实例
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
                        markLat = p.lat;
                        markLng = p.lng;
                        map.setCenter(new BMap.Point(p.lng, p.lat));
                        addMarker(p);
                    }
                    else
                    {
                        var geolocation = new BMap.Geolocation();
                        geolocation.getCurrentPosition(function(r){
                            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                                map.setCenter(r.point);
                            }
                        },{enableHighAccuracy: true});
                    }
                }
            });
            map.addEventListener("click",function(e, p){
                addMarker(e.point);
                markLng = e.point.lng;
                markLat = e.point.lat;
            });
        }
        $('#MapPositive').click(function ()
        {
            if(!markLng||!markLat)
            {
                alert('请点击地图定位！');
                return;
            }
            $('[name="longitude"]').val(markLng);
            $('[name="latitude"]').val(markLat);
            $('#MapModal').modal('hide');
            $('#SelectMap').val('已选择');
        });
        return show;
    })();

});
</script>

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