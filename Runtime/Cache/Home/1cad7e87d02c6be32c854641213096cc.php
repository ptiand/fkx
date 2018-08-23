<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title><?php echo ((isset($title) && ($title !== ""))?($title):'房客行'); ?></title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/seting.css">
    <style type="text/css">
        html body{background-color: #fff;}
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
            <span>关于平台</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="con">
        <div class="logo">
            <img src="/public/Home/images/ICON_FKX_180x180.png" width="72" height="72" />
            <h1>房客行</h1>
            <p>当前版本：V2.0</p>
        </div>
        <div class="discription">房客行----移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。</div>
        <div class="d-wrap">
            <ul>
                <li><b>租房：</b>100%个人房源，租房全免中介费，汇集精品公寓，海量租房券等你拿。</li>
                <li><b>二手房：</b>100%业主房源，买卖双方直沟通，全免中介费，5800元带看过户全程服务。</li>
                <li><b>新房：</b>购新房高返佣，专业顾问全程服务，全民经纪人，推荐成交即拿现金</li>
            </ul>
        </div>
        <div class="d-contact">
            <p>官方网址：<a href="www.fangkexing.cn">www.fangkexing.cn</a></p>
            <p>联系我们：<a href="tel:0592-310673">0592-3106673</a></p>
        </div>
    </div>




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