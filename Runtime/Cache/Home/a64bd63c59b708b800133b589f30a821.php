<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>我的发布</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <!--<link rel="stylesheet" href="/public/Home/css/House.css">-->
    <link rel="stylesheet" href="/public/Home/css/house_list.css">
    <script src="/public/Home/js/jquery-1.7.1.min.js"></script>
    <script src="/public/Home/js/items.js"></script>


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

    <style type="text/css">
    html body,.container {background-color: #F2F2F2;}
    .filter-line-container .active {font-weight: bold;}
    #list-container {padding:0;}
    .recommend-item {background-color: #FFF;padding:0 12px 10px 12px;}
    .gt {color: #999;}
    .gt img {float: right;margin-top: 9px;}
    </style>
    <div class="header">
        <div class="button">
            <img src="/public/Home/images/nav_back_icon.png" alt="" id="nav-back-img" width="22" height="22"/>
        </div>
        <div class="title">
            <span>我的发布</span>
        </div>
        <div class="button">
        </div>
    </div>


    <div class="container">
        <div class="filter-line-container">
            <div class="item active" value="-1">
                <span>全部(<?php echo ($allStateCount); ?>)</span>
            </div>
            <?php if(is_array($stateList)): foreach($stateList as $id=>$state): ?><div class="item" value="<?php echo ($id); ?>">
                    <span><?php echo ($state['name']); ?>(<?php echo ($state['count']); ?>)</span>
                </div><?php endforeach; endif; ?>
        </div>
        <div id="list-container">
        </div>
        <div id="empty-container">
            <img src="/public/Home/images/empty_list_icon.png"/>
            <span>尚无相应的房源信息</span>
        </div>
        <div id="empty-tail">-- 已经到底了 --</div>
    </div>




<script>
    var Filter = {
        state: -1
    };
    $("#nav-back-img").click(function () {
        window.history.back();
    });

    (function () {
        var pending = false;
        var currentPage = 0;
        var pageSize = 10;
        var scrollBefore = $(window).scrollTop();
        $(window).scroll(function () {
            var scrollAfter = $(window).scrollTop();
            if (scrollAfter < scrollBefore) {
                console.log("scroll to top, ignore");
                return;
            }
            var body = $('body').get(0);
            var scrollBottom = body.scrollHeight - body.scrollTop - body.clientHeight;
            if (scrollBottom<=80 && !pending) {
                pending = true;
                currentPage;
                console.log('load next, current page ', currentPage);
                loadMore(currentPage, 10);
            }
        });

        loadMore();
        function loadMore() {
            $.get("<?php echo U('Center/mypublish_listItem');?>", {currentPage, pageSize, filter:Filter}, function (data) {
                pending = false;
                if (data.status == 0) {
                    var isEmpty = isListEmpty();
                    setVisibility($("#empty-container"), isEmpty);
                    setVisibility($("#empty-tail"), !isEmpty);
                } else {
                    setVisibility($("#empty-container"), false);
                    setVisibility($("#empty-tail"), getListSize()<pageSize);
                    $("#list-container").last().append(data.info);
                    currentPage++;
                }
            });
        };

        function isListEmpty() {
            return getListSize()==0;
        }

        function getListSize() {
            return $('#list-container').children().length
        }

        $('.filter-line-container .item').click(function (e) {
            $('.filter-line-container .active').removeClass('active');
            $(this).addClass('active');
            Filter.state = $(this).attr('value');
            currentPage=0;
            $("#list-container").empty();
            loadMore();
        });
    })();

    function setVisibility($target, visible) {
        return $target.css('display', visible ? 'flex' : 'none');
    }

    function isVisible($target) {
        return $target.css('display')!='none';
    }
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