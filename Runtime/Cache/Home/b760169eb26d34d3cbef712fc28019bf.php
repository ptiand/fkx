<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>租客登记</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <style>
    html body
    {
        background-color: #F2F2F2;
    }
    .info-item {
        display: flex;
        position: relative;
        background-color: #fff;
        padding: 6px;
        color: #000;
    }
    .item-content {
        flex: 1;
        width: 10%;
        padding: 0 6px;
    }
    .info-icon {
        display: block;
        width: 24px;
        height: 24px;
    }
    .address {
        font-size: 14px;
        margin-top: 8px;
    }
    .floor-section {
        padding: 10px 5px;
    }
    .floor-title {
        padding: 10px;
        background: #fff;
        border-bottom: 1px solid #f4f4f4;
    }
    .floor-title .del-icon {
        width: 24px;
        height: 24px;
        float: right;
    }
    .floor-title .arrow-icon {
        width: 24px;
        height: 20px;
        float: right;
        transition: all linear 0.3s
    }
    .arrow-icon.expand {
        transform: rotate(90deg);
    }
    .floor-content {
        background: #fff;
        padding: 10px;
        font-size: 0;
    }
    .room-item {
        display: inline-block;
        padding: 5px 15px 6px;
        border: 1px solid #34BBBE;
        border-radius: 4px;
        font-size: 20px;
        margin-right: 12px;
        margin-bottom: 5px;
        font-weight: 600;
        color: #34BBBE;
        position: relative;
        text-align: center;
    }
    .room-item span{
        font-size: 12px;
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

    <div class="header">
        <a class="button" href="<?php echo U('Service/house_setup');?>">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
        </a>
        <div class="title">
            <span>租客登记</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="content-container">
        <div class="info-item">
            <img class="info-icon" src="/public/Home/images/icon-new.png" alt="">
            <div class="item-content">
                <div class="ellipsis"><?php echo ($house_info["name"]); ?></div>
                <div class="address clr-gray ellipsis"><?php echo ($house_info["address"]); ?></div>
            </div>
        </div>
        <?php if(is_array($house_info["floor_info"])): foreach($house_info["floor_info"] as $key=>$floor): ?><div class="floor-section">
            <div class="floor-title"><?php echo ($floor["floor_name"]); ?> 层 
                <img class="arrow-icon expand" src="/public/Home/images/arrow.png" alt="">
            </div>
            <div class="floor-content">
                <?php if(is_array($floor["room_info"])): foreach($floor["room_info"] as $key=>$room): if($room['is_tenant'] == '0'): ?><a class="room-item" href="<?php echo U('Service/register_tenant', array('room_id'=>$room['id']));?>">
                            <?php echo ($room["room_name"]); ?><br/>
                            <span>未登记</span>
                        </a>
                    <?php else: ?>
                        <a class="room-item" data-tenant="<?php echo ($room['is_tenant']); ?>" href="<?php echo U('Service/register_tenant', array('id'=>$room['agreement_id'], 'room_id'=>$room['id']));?>">
                            <?php echo ($room["room_name"]); ?><br/>
                            <span><?php echo ($room['is_tenant'] == '2' ? '待确认' : '已登记'); ?></span>
                        </a><?php endif; endforeach; endif; ?>
            </div>
        </div><?php endforeach; endif; ?>
        <?php if(empty($house_info['floor_info'])): ?><div class="floor-section">
                <div class="floor-title clr-gray">未添加楼层</div>
            </div><?php endif; ?>
    </div>





    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script type="text/javascript">
        function showToast(msg) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5});
        }
        function sendAjax(data, layerIdx) {
            var loading = layer.open({type: 2, shadeClose: false});
            $.ajax({
                url: data.url,
                data: data.data,
                type: 'POST',
                dataType: 'json',
                success: function (resData) {
                    //layer.close(loading);
                    console.log('resp: ', resData)
                    if (resData.status == 1) {
                        location.reload();
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
        $(function () {
            $('.floor-title').on('click', function() {
                var me = $(this)
                me.find('.arrow-icon').toggleClass('expand')
                me.next().slideToggle()
            })

            $('.room-item').on('click', function(e){
                if($(this).data('tenant') == '2') {
                    e.preventDefault();
                    e.stopPropagation();
                    showToast('待确认暂不支持操作')
                }
            })
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