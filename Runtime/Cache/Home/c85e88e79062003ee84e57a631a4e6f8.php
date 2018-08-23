<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title><?php echo ((isset($title) && ($title !== ""))?($title):'房客行'); ?></title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
<link rel="stylesheet" href="/public/Home/css/form.css">
<style type="text/css">
.group-container {padding: 0 12px 30px 12px;background-color: #fff;}
.btn-content {padding:12px 0;}
.more {display: flex;flex-direction: row;justify-content: flex-end;padding:0 12px;background-color: #fff;line-height: 32px;}
.more a {font-size: 14px;color:#34BBBE; }
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
            <span>交易流程</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="content-container">
        <img src="/public/Home/images/img_service_01.png" width="100%">
        <div class="more">
            <a id="CommissionRule" class="item-label" href="<?php echo U('Index/trade2');?>">费用标准<i class="gt"><img src="/public/Home/images/icon_gt.png"></i></a>
        </div>
        <p style="background-color: #fff;text-align: center;color: #666;font-size: 16px;">以总价500万房源计算</p>
        <img src="/public/Home/images/img_service_02.png" width="100%">
        <form id="InfoForm">
        <input type="hidden" name="houseId" value="<?php echo ($houseId); ?>"/>
        <input type="hidden" name="houseType" value="<?php echo ($houseType); ?>"/>
        <div class="group-container">
            <div class="item">
                <span class="item-label">称呼</span>
                <input type="text" name="info.name" required class="input-container" placeholder="请输入" />
            </div>
            <div class="item">
                <span class="item-label">电话</span>
                <input type="text" name="info.phone" required class="input-container" placeholder="请输入" />
            </div>
            <div class="item">
                <span class="item-label">期望时间</span>
                <input type="text" name="info.time" class="input-container" placeholder="请输入看房时间以便房小二联系" />
            </div>
            <div class="btn-content">
                <button id="filter-confirm-btn" onclick="submitInfo();return false;">确定</button>
            </div>
        </div>
        </form>
    </div>




<script type="text/javascript">
function submitInfo()
{
    try {
        if(!document.getElementById('InfoForm').reportValidity())
        {
            return;
        }
    }catch (e) {

    }
    var data = $('#InfoForm').serialize();
    $.ajax({
        url:'<?php echo U("Info/info");?>',
        type:'POST',
        data: data,
        success:function (resData)
        {
            if(resData.status == 1)
            {
                FkxToast.success(resData.info);
                history.back();
            }
            else
            {
                FkxToast.error(resData.info);
            }
        },
        error: function ()
        {
            FkxToast.error('提交失败！');
        }
    });
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