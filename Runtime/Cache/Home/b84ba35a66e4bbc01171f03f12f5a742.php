<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>消息管理</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <style>
        #listContainer {
            background-color: #fff;
            padding: 0 6px;
        }
        .info-item {
            display: flex;
            position: relative;
            align-items: center;
            padding: 6px;
            border-bottom: 1px solid #f2f2f2;
        }
        .info-logo {
            position: relative;
            margin-right: 5px;
        }
        .info-logo.un-read::after {
            content: "";
            display: block;
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #f11;
            top: 0px;
            right: 0px;
        }
        .logo-img {
            width: 60px;
            height: 60px;
            display: block;
            border-radius: 30px;
        }
        .info-content{
            flex: 1;
            width: 10%;
            align-items: center;
            color: #666;
            font-size: 14px;
        }
        .info-content .info-title {
            margin-bottom: 6px;
            font-size: 16px;
            color: #000;
        }
        .tip-section {
            text-align:center;
            padding: 30px 0;
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

    <div class="header">
        <div class="button" onclick="history.back()">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
        </div>
        <div class="title">
            <span>消息管理</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="content-container">
        <div id="listContainer">
        </div>
        <div id="loadingTip" class="tip-section">
            <div style="color: #999;font-size: 14px;margin-top: 12px;"> 加载中... </div>
        </div>
        <div id="endTip" class="tip-section">
            <div style="color: #999;font-size: 14px;margin-top: 12px;">- 已经到底了 -</div>
        </div>
        <div id="emptyTip" class="tip-section" style="background-color:#fff;">
            <img src="/public/Home/images/empty_list_icon.png"/>
            <div style="color: #999;font-size: 14px;margin-top: 12px;">- 暂无信息 -</div>
        </div>
    </div>




<script id="item-temp" type="template/javascript">
    <a class="info-item" href="<?php echo U('Mymsg/read_msg', array('msg_id'=>'[#id]'));?>">
        <div class="info-logo [#is_read]">
            <img src="/public/Home/images/message/icon_logo.png" class="logo-img"/>
        </div>
        <div class="info-content">
            <div class="info-title">[#msg_type]<span class="float-r clr-gray">[#create_time]</span></div>
            <div class="ellipsis">[#content]</div>
        </div>
    </a>
</script>
<script type="text/javascript">
$(function () {
    var currentPage = 0,
        loading = false,
        pageSize = 10,
        isEnd = false;
    var $listContainer = $('#listContainer');

    var scrollBefore = $(window).scrollTop();
    $(window).scroll(function () {
        var scrollAfter = $(window).scrollTop();
        if (scrollAfter < scrollBefore) {
            return;
        }
        var body = $('body').get(0);
        var scrollBottom = body.scrollHeight - body.scrollTop - body.clientHeight;
        if (scrollBottom <= 80 && !loading) {
            scrollBefore = scrollAfter;
            getList();
        }
    });

    getList();
    function getList()
    {
        if(loading || isEnd) return;
        loading = true;
        $('#loadingTip').show();
        currentPage ++;
        $.ajax({
            url: '<?php echo U("Mymsg/load_msg");?>',
            data:{page_size: pageSize, cur_page:currentPage},
            type: 'POST',
            dataType: 'json',
            success: function (resData)
            {
                loading = false;
                $('#loadingTip').hide();
                console.log('response data: ', resData);
                if(Array.isArray(resData.rows))
                {
                    var html = [], temp = $("#item-temp").text(), itemCount = 0;
                    resData.rows.forEach(function(item, idx) {
                        html.push(temp.replace("[#is_read]", item.is_read == '0' ? 'un-read' : '')
                            .replace("[#msg_type]", item.msg_type == '1' ? '租凭确认' : '系统消息')
                            .replace("[#create_time]", item.create_time)
                            .replace("[#content]", item.content)
                            .replace(RegExp(encodeURIComponent('[#id]'), 'g'), item.id)
                        );
                    })
                    if(html)
                    {
                        if(currentPage === 1)
                        {
                            $listContainer.html(html);
                        }
                        else
                        {
                            $listContainer.append(html);
                        }
                    }

                    itemCount = $listContainer.children().length;
                    if(itemCount >= resData.total)
                    {
                        if(itemCount)
                        {
                            isEnd = true;
                            $('#endTip').show();
                        } else
                        {
                            $('#emptyTip').show();
                        }
                    }
                }
            },
            error: function ()
            {
                loading = false;
                $('#loadingTip').hide();
            }
        });
    }

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