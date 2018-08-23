<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>发布房源</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/index.css">


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
            <span>发布房源</span>
        </div>
        <div class="button">
        </div>
    </div>


    <style>
        .container {
            padding-top: 40px;
        }
        .container .item {
            padding: 0 10px 0 10px;
            display: flex;
            height: 72px;
            background: white;
            align-items: center;
            justify-content: space-between;
        }
        .container .item .img-container {
            width: 58px;
            height: 58px;
        }
        .container .item .img-container img {
            width: 58px;
            height: 58px;
        }
        .container .item .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-left: 10px;
        }
        .container .item .content span {
            font-size: 13px;
            color: #777;
        }
        .container .item .content .title {
            font-weight: bold;
            font-size: 15px;
            line-height: 24px;
            color: #333;
        }
        .item-seperator {
            width: 100%;
            height: 1px;
            overflow: hidden;
            background-color: #efefef;
        }
        #publish-list {
            margin-top: 8px;
        }
    </style>
    <div class="container">
        <div class="item" id="publish-old-house">
            <div class="img-container"><img src="/public/Home/images/icon-er.png" alt="二手房" /></div>
            <div class="content">
                <span class="title">发布二手房</span>
                <span>发布您的出售房源信息</span>
            </div>
            <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
        </div>
        <div class="item-seperator"></div>
        <div class="item" id="publish-rental-house">
            <div class="img-container"><img src="/public/Home/images/icon-zu.png" alt="租房" /></div>
            <div class="content">
                <span class="title">发布房屋出租</span>
                <span>发布您的个人房源出租信息</span>
            </div>
            <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
        </div>
        <div class="item-seperator"></div>
        <a class="item" href="tel:0592-3106673">
            <div class="img-container"><img src="/public/Home/images/icon-weituo.png" alt="委托发布" /></div>
            <div class="content">
                <span class="title">委托发布</span>
                <span>请拨打客服热线：0592-3106673</span>
            </div>
            <img src="/public/Home/images/btbar_phone2.png" width="20" height="20" />
        </a>

        <a class="item" id="publish-list">
            <div class="img-container"><img src="/public/Home/images/icon-mypublic.png" alt="我发布的房源" /></div>
            <div class="content">
                <span class="title">我发布的房源</span>
                <span>当前已发布的房源(<?php echo ($publishCount); ?>)</span>
            </div>
            <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
        </a>
    </div>




    <script>
        $("#publish-old-house").click(function () {
            window.location.href = '<?php echo U("Publish/oldHouse");?>';
        });

        $("#publish-rental-house").click(function () {
            window.location.href = '<?php echo U("Publish/rentalHouse");?>';
        });

        $("#publish-list").click(function () {
            window.location.href = '<?php echo U("Center/mypublish");?>';
        });

        $("#NavBackImg").click(function () {
            window.history.back(-1);
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