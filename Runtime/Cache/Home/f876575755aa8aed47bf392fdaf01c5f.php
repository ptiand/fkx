<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title><?php echo ($msg_info['msg_type'] == '1' ? '租凭确认' : '系统消息'); ?></title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <style>
        .content-section {
            text-align: center;
            padding: 5px 10px;
        }
        .date-span {
            background-color: #919191;
            color: #fff;
            padding: 2px 10px;
            border-radius: 4px;
        }
        .content {
            background-color: #fff;
            border-radius: 4px;
            padding: 10px 20px;
            text-align: left;
            margin-top: 10px;
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
        <div class="button" onclick="history.back()">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
        </div>
        <div class="title">
            <span><?php echo ($msg_info['msg_type'] == '1' ? '租凭确认' : '系统消息'); ?></span>
        </div>
        <div class="button"></div>
    </div>


    <div class="btn-content-container">
      <div class="content-section">
          <span class="date-span"><?php echo ($msg_info['create_time']); ?></span>
          <div class="content"><?php echo ($msg_info['content']); ?></div>
        </div>
    </div>


    <a class="bottom-btn" href="javascript:;" id="submit-btn" data-id="<?php echo ($msg_info['id']); ?>">确认</a>



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
                    console.log('resp: ', resData)
                    if (resData.status == 1) {
                        showToast(resData.info || '确认成功', function() {
                            location.reload();
                        });
                    } else {
                        // layer.close(loading);
                        showToast(resData.info);
                    }

                },
                error: function (msg) {
                    layer.close(loading);
                    showToast('操作失败')
                }
            });
        }
        $(function () {
            $('#submit-btn').on('click', function() {
                var me = $(this)
                layer.open({
                    title: '租凭确认',
                    content: '确认租凭信息无误？',
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        layer.close(index);
                        sendAjax({
                            url: '<?php echo U("Mymsg/confirm_do");?>',
                            data: {msg_id: me.data('id')}
                        })
                    }
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