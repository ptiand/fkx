<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>公寓管理</title>
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
        width: 18px;
        height: 16px;
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
    }
    .room-item:after {
        display: block;
        background-color: #f11;
        color: #fff;
        position: absolute;
        top: -8px;
        right: -8px;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        overflow: hidden;
        line-height: 14px;
        padding-left: 4px;
        box-sizing: border-box;
    }
    .room-item.show-del:after{
        content: "-";
    }
    .layui-m-layer4 .layui-m-layerchild{
        width: 90%;
    }
    .layui-m-layer4 .layui-m-layercont{
        padding: 0 !important;
        line-height: 2;
    }
    .layer-input {
        width: 100%;
        padding: 12px 4px;
        box-sizing: border-box !important;
        border: 1px solid #999;
        border-radius: 4px;
    }
    .form-item {
        text-align: left;
        border-top: 1px solid #f1f1f1;
        padding: 10px 20px;
    }
    .form-input {
        float: right;
        line-height: 2;
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
            <span><?php echo ($house_info["name"]); ?></span>
        </div>
        <div class="button"></div>
    </div>


    <div class="btn-content-container">
        <div class="info-item">
            <img class="info-icon" src="/public/Home/images/icon-new.png" alt="">
            <div class="item-content">
                <div class="ellipsis"><?php echo ($house_info["name"]); ?></div>
                <div class="address clr-gray ellipsis"><?php echo ($house_info["address"]); ?></div>
            </div>
            <a href="<?php echo U('Publish/shopHouse', array('id'=>$house_info['id']));?>">
                修改
            </a>
        </div>
        <?php if(is_array($house_info["floor_info"])): foreach($house_info["floor_info"] as $key=>$floor): ?><div class="floor-section">
            <div class="floor-title"><?php echo ($floor["floor_name"]); ?> 层 
                <img class="arrow-icon expand" src="/public/Home/images/arrow.png" alt="">
                <img class="del-icon" src="/public/Home/images/x.png" data-id="<?php echo ($floor["id"]); ?>" data-name="<?php echo ($floor["floor_name"]); ?>" alt="删除"/>
            </div>
            <div class="floor-content">
                <?php if(is_array($floor["room_info"])): foreach($floor["room_info"] as $key=>$room): ?><a class="room-item" href="<?php echo U('Service/edit_room', array('room_id'=>$room['id']));?>" data-id="<?php echo ($room["id"]); ?>" data-name="<?php echo ($room["room_name"]); ?>"><?php echo ($room["room_name"]); ?>
                    <a class="del-icon" href="javascript:;">-</a>
                </a><?php endforeach; endif; ?>
                <div class="room-item room-add" data-id="<?php echo ($floor["id"]); ?>">+</div>
                <div class="room-item room-del" data-id="<?php echo ($floor["id"]); ?>">-</div>
            </div>
        </div><?php endforeach; endif; ?>
        <?php if(empty($house_info['floor_info'])): ?><div class="floor-section">
                <div class="floor-title clr-gray">未添加楼层</div>
            </div><?php endif; ?>
    </div>


    <a class="bottom-btn" href="javascript:;">
        添加楼层
    </a>



    <script id="add-room-temp" type="template/javascript">
        <div class="form-item">
            <input id="single-room" type="radio" name="type" checked /> <label for="single-room">添加单个房号</label>
            <input id="multi-room" type="radio" name="type" /> <label for="multi-room">批量添加房号</label>
        </div>
        <form class="single-room">
            <input type="hidden" name="floor_id" value="[#floor_id]"/>
            <input type="hidden" name="add_num" value="1"/>
            <div class="form-item">
                房间号<input class="form-input" name="room_name" placeholder="输入房间号" />
            </div>
        </form>
        <form class="multi-room" style="display: none">
            <input type="hidden" name="floor_id" value="[#floor_id]"/>
            <div class="form-item">
                需添加房间数 <input class="form-input" name="add_num" placeholder="输入房间数" />
            </div>
        </form>
    </script>
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
        function addFloor (name) {
            sendAjax({
                url: '<?php echo U("Service/add_floor");?>',
                data: {house_id: '<?php echo ($house_info["id"]); ?>', floor_name: name}
            })
        }
        function delFloor (id) {
            sendAjax({
                url: '<?php echo U("Service/del_floor");?>',
                data: {house_id: '<?php echo ($house_info["id"]); ?>', floor_id: id}
            })
        }
        $(function () {
            $('.floor-title').on('click', function() {
                var me = $(this)
                me.find('.arrow-icon').toggleClass('expand')
                me.next().slideToggle()
            })
            $('.floor-title .del-icon').on('click', function(e) {
                var me = $(this)
                
                e.stopPropagation();
                layer.open({
                    title: '楼层删除',
                    content: '确认删除 “' + me.data('name') + '” 楼层？',
                    btn: ['是', '否'],
                    yes: function(index) {
                        layer.close(index)
                        delFloor(me.data('id'))
                    }
                });
            })
            $('.room-add').on('click', function() {
                var me = $(this)
                layer.open({
                    title: '添加房间',
                    type: 4,
                    content: $('#add-room-temp').text().replace(/\[\#floor_id\]/g, me.data('id')),
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        var checkedRadio = $('.form-item [name="type"]:checked')
                        var dataArray = $('.' + checkedRadio.attr('id')).serializeArray();
                        var data = {house_id: '<?php echo ($house_info["id"]); ?>'};
                        dataArray.forEach(function (val) {
                            data[val.name] = val.value;
                        })
                        // layer.close(index);
                        sendAjax({
                            url: '<?php echo U("Service/add_room");?>',
                            data: data
                        })
                    }
                })
                $(".form-item [name=type]").on('change', function(){
                    $('.' + this.id).show().siblings('form').hide();
                })
            })
            $('.room-del').on('click', function() {
                $(this).siblings('.room-item[data-name]').toggleClass('show-del');
            })
            $('.room-item').on('click', function(e) {
                var me = $(this)

                if(!me.hasClass('show-del')){
                    return;
                }
                e.preventDefault();
                layer.open({
                    title: '删除房间',
                    content: '确认删除 “' + me.data('name') + '” 房间？',
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        var data = {room_id: me.data('id')};
                        // layer.close(index);
                        sendAjax({
                            url: '<?php echo U("Service/del_room");?>',
                            data: data
                        })
                    }
                })
            })
            $('.bottom-btn').on('click', function() {
                layer.open({
                    title: '添加楼层',
                    content: '<input id="floor-input" class="layer-input" placeholder="请输入楼层名称"/>',
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        var input = $('#floor-input');
                        var value = $('#floor-input').val();
                        if(value.length > 0){
                            //layer.close(index);
                            addFloor(value, index)
                        } else {
                            showToast('楼层名称不能为空')
                        }
                    }
                })
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