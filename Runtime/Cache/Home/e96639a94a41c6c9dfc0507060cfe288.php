<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    
    <title>发布租房</title>

    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" type="text/css" href="/public/Static/iconfont/iconfont.css">
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap.css">
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
        <div class="button" onclick="history.back(-1)">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22" />
        </div>
        <div class="title">
            <span><?php echo ($room_info["room_name"]); ?>房间</span>
        </div>
        <div class="button"></div>
    </div>


    <style>
        html body .container {
            padding: 40px 0 50px 0;
        }

        .iconfont-container:after{
            clear: both;
            content: "";
            display: table;
        }
        .iconfont-container li {
            float: left;
            text-align: center;
            padding: 5px 12px;
        }
        .iconfont-container .iconfont{
            font-size: 40px;
        }
    </style>
    <form class="container" id="Main-Form">
        <input value="<?php echo ($room_info['id']); ?>" name="id" hidden/>
        <div class="group-container">
            <div class="title">基本信息</div>
            <div class="item">
                <span class="item-label">房间类型</span>
                <!--<input class="input-container" type="text" placeholder="请选择"/>-->
                <select class="input-container" name="apartment_type" required>
                    <?php if(is_array($apartment_type)): foreach($apartment_type as $key=>$type): ?><option value="<?php echo ($key); ?>" <?php echo ($room_info['apartment_type'] == $key ? 'selected' : ''); ?>><?php echo ($type); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>

            <div class="seperator"></div>

            <div class="item">
                <span class="item-label">房间户型</span>
                <div class="input-container multiple-line">
                    <?php $numList=array(0, 1, 2, 3, 4, 5, 6, 7, 8)?>
                    <div class="inner-container">
                        <!--<input class="inner-input" type="number" placeholder="请选择"/>-->
                        <select class="inner-input" name="room_num" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($room_info['room_num']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">室</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="living_num" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($room_info['living_num']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">厅</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="rest_num" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($room_info['rest_num']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">卫</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="kitchen_num" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($room_info['kitchen_num']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">厨</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                    <div class="inner-container">
                        <select class="inner-input" name="veranda_num" required>
                            <?php if(is_array($numList)): foreach($numList as $key=>$item): ?><option value="<?php echo ($item); ?>" <?php echo ($room_info['veranda_num']==$item?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                        <span class="item-tips inner-tail">阳台</span>
                        <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
                    </div>
                </div>

            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">装修标准</span>
                <select class="input-container" name="zx_id" required>
                    <?php if(is_array($zx_list)): foreach($zx_list as $key=>$type): ?><option value="<?php echo ($type['id']); ?>" <?php echo ($room_info['zx_id']==$type['id']?'selected':''); ?>><?php echo ($type['zxiu_name']); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">朝向</span>
                <select class="input-container" name="orientations" required>
                    <?php if(is_array($orientations)): foreach($orientations as $key=>$type): ?><option value="<?php echo ($key); ?>" <?php echo ($room_info['orientations']==$key?'selected':''); ?>><?php echo ($type); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
        </div>

        <div class="group-container">
            <div class="title">报价信息</div>
            <div class="item">
                <span class="item-label">住房面积</span>
                <input class="input-container" type="number" name="area_count" value="<?php echo ($room_info['area_count']); ?>" required placeholder="请输入" />
                <span class="item-tips">平方米(㎡)</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">租金单价</span>
                <input class="input-container" type="number" name="price" value="<?php echo ($room_info['price']); ?>" required placeholder="请输入" />
                <span class="item-tips">元/月</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">租金</span>
                <input class="input-container" type="number" name="foregift" value="<?php echo ($room_info['foregift']); ?>" required placeholder="请输入" />
                <span class="item-tips">元</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">租赁方式</span>
                <select class="input-container" name="rental_type" required>
                    <?php if(is_array($rental_type_caption)): foreach($rental_type_caption as $id=>$rental_type): ?><option value="<?php echo ($id); ?>" <?php echo ($room_info['rental_type']==$id?'selected':''); ?>><?php echo ($rental_type); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
        </div>
        <div class="group-container">
            <div class="title">房间设备</div>
            <div class="item iconfont-item">
                <ul class="iconfont-container">
                    <li class="<?php echo ($room_info['more_options']->wbl == '1' ? 'clr-primary' : ''); ?>" name="wbl">
                        <i class="icon iconfont icon-wbl"></i>
                        <div class="name">微波炉</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->kd == '1' ? 'clr-primary' : ''); ?>" name="kd">
                        <i class="icon iconfont icon-kd"></i>
                        <div class="name">宽带</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->kzf == '1' ? 'clr-primary' : ''); ?>" name="kzf">
                        <i class="icon iconfont icon-kzf"></i>
                        <div class="name">厨房</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->rsq == '1' ? 'clr-primary' : ''); ?>" name="rsq">
                        <i class="icon iconfont icon-rsq"></i>
                        <div class="name">热水器</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->c == '1' ? 'clr-primary' : ''); ?>" name="c">
                        <i class="icon iconfont icon-c"></i>
                        <div class="name">床</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->xyj == '1' ? 'clr-primary' : ''); ?>" name="xyj">
                        <i class="icon iconfont icon-xyj"></i>
                        <div class="name">洗衣机</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->wsj == '1' ? 'clr-primary' : ''); ?>" name="wsj">
                        <i class="icon iconfont icon-wsj"></i>
                        <div class="name">卫生间</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->yt == '1' ? 'clr-primary' : ''); ?>" name="yt">
                        <i class="icon iconfont icon-yt"></i>
                        <div class="name">阳台</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->yg == '1' ? 'clr-primary' : ''); ?>" name="yg">
                        <i class="icon iconfont icon-yg"></i>
                        <div class="name">衣柜</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->sf == '1' ? 'clr-primary' : ''); ?>" name="sf">
                        <i class="icon iconfont icon-sf"></i>
                        <div class="name">沙发</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->ds == '1' ? 'clr-primary' : ''); ?>" name="ds">
                        <i class="icon iconfont icon-ds"></i>
                        <div class="name">电视机</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->bx == '1' ? 'clr-primary' : ''); ?>" name="bx">
                        <i class="icon iconfont icon-bx"></i>
                        <div class="name">冰箱</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->nq == '1' ? 'clr-primary' : ''); ?>" name="nq">
                        <i class="icon iconfont icon-nq"></i>
                        <div class="name">暖气</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->kt == '1' ? 'clr-primary' : ''); ?>" name="kt">
                        <i class="icon iconfont icon-kt"></i>
                        <div class="name">空调</div>
                    </li>

                    <li class="<?php echo ($room_info['more_options']->yz == '1' ? 'clr-primary' : ''); ?>" name="yz">
                        <i class="icon iconfont icon-yz"></i>
                        <div class="name">椅子</div>
                    </li>
            </div>
        </div>
    </form>


    <div class="bottom-btn" id="submit-btn">
        保存
    </div>



    <script src="/public/Static/bootstrap/js/bootstrap.js"></script>
    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script>
        function showToast(msg, cb) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5, end: cb});
        }
        function sendAjax(data, layerIdx) {
            var loading = layer.open({type: 2, shadeClose: false});
            $.ajax({
                url: data.url,
                data: data.data,
                type: 'POST',
                dataType: 'json',
                success: function (resData) {
                    layer.close(loading);
                    if (resData.status == 1) {
                        showToast('保存成功', function() {
                            location.reload()
                        });
                    } else {
                        layer.close(loading);
                        showToast(resData.info);
                    }

                },
                error: function (msg) {
                    console.log('msg: ', msg);
                    layer.close(loading);
                    showToast('操作失败')
                }
            });
        }
        $(function() {
            $('.iconfont-container li').on('click', function() {
                $(this).toggleClass('clr-primary')
            })
            $('#submit-btn').on('click', function() {
                var dataArray = $('#Main-Form').serializeArray();
                var data = {more_options: {}};
                dataArray.forEach(function (val) {
                    data[val.name] = val.value;
                })
                $('.iconfont-container li').each(function(idx, val) {
                    var me = $(this)
                    data.more_options[me.attr('name')] = me.hasClass('clr-primary') ? '1' : '0'
                })
                // layer.close(index);
                sendAjax({
                    url: '<?php echo U("Service/update_edit_room");?>',
                    data: data
                })
            })
        })

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