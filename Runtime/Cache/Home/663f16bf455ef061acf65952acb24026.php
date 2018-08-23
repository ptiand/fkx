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
        align-items: center;
    }
    .house{
        flex: 1;
        display: flex;
        align-items: center;
        color: #000;
    }
    .item-content {
        flex: 1;
        width: 10%;
        padding: 6px;
    }
    .logo-img {
        width: 60px;
        height: 60px;
        display: block
    }
    .address {
        font-size: 14px;
        margin-top: 8px;
    }
    .del-icon {
        display: block;
        width: 24px;
        height: 24px;
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
        <a class="button" href="<?php echo U('Service/index');?>">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22"/>
        </a>
        <div class="title">
            <span>公寓管理</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="btn-content-container">
        <div id="listContainer">
        </div>
        <div id="loadingTip" style="text-align:center;padding: 30px 0;">
            <div style="color: #999;font-size: 14px;margin-top: 12px;"> 加载中... </div>
        </div>
        <div id="endTip" style="text-align:center;padding: 30px 0;display: none;">
            <div style="color: #999;font-size: 14px;margin-top: 12px;">- 已经到底了 -</div>
        </div>
        <div id="emptyTip" style="text-align:center;padding: 30px 0;background-color:#fff;display: none;">
            <img src="/public/Home/images/empty_list_icon.png"/>
            <div style="color: #999;font-size: 14px;margin-top: 12px;">- 暂无公寓信息 -</div>
        </div>
    </div>


    <a class="bottom-btn" href="<?php echo U('Publish/shopHouse');?>">
        添加公寓
    </a>



<script id="item-temp" type="template/javascript">
    <div class="info-item">
        <a class="house" href="<?php echo U('Service/houses_info', array('house_id'=>'[#id]'));?>">
            <img class="logo-img" src="<?php echo ((isset($shop_info['logo_img']) && ($shop_info['logo_img'] !== ""))?($shop_info['logo_img']):'/public/no_photo.png'); ?>" alt="Logo">
            <div class="item-content">
                <div class="ellipsis">[#name]</div>
                <div class="address clr-gray ellipsis">
                    [#address]
                </div>
            </div>
        </a>
        <a class="tenant btn" href="<?php echo U('Service/tenant_info', array('house_id'=>'[#id]'));?>">租客</a>
    </div>
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
        if (scrollBottom<=80 && !loading) {
            getList();
        }
    });
    // <img class="del-icon" src="/public/Home/images/x.png" data-id="[#id]" />
    // $listContainer.on('click', '.del-icon', function(e) {
    //     e.stopPropagation();
    //     e.preventDefault();
    //     if (confirm('确认删除该公寓？')) {
    //         $.ajax({
    //             url: '<?php echo U("Service/load_houses");?>',
    //             data:{pageSize: pageSize, currentPage:currentPage},
    //             type: 'POST',
    //             dataType: 'json',
    //             success: function (resData) {
    //                 console.log('del', resData);
    //             }
    //         })
    //     }
    // })

    getList();
    function getList()
    {
        if(loading || isEnd) return;
        loading = true;
        currentPage ++;
        $.ajax({
            url: '<?php echo U("Service/load_houses");?>',
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
                        html.push(temp.replace("[#name]", item.name)
                            .replace("[#address]", item.address)
                            .replace(/\[\#id\]/g, item.id)
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