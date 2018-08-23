<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title><?php echo ((isset($title) && ($title !== ""))?($title):'房客行'); ?></title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/common.css">
    <link rel="stylesheet" href="/public/Home/css/seting.css">
    <style>
        .bg {
            background: rgba(0, 0, 0, 0.5);
            position: fixed;
            width: 100%;
            height: 100%;
            display: none;
            top: 0;
            z-index: 99;
        }
        .bg-list {
            width: 100%;
            position: fixed;
            bottom: 0;
        }
        .action {
            animation: ok 1s;
            -webkit-animation: ok 1s;
        }
        @keyframes ok {
            from{opacity: 0}to{opacity: 1}
        }
        @-webkit-keyframes ok {
            from{opacity: 0}to{opacity: 1}
        }
        .bg-list ul li {
            width: 100%;
            height: 50px;
            margin-top: 1px;
            text-align: center;
            background: #fff;
        }
        .bg-list ul li a {
            font-size: 15px;
            color: #666;
            line-height: 50px;
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
            <span>设置</span>
        </div>
        <div class="button">
        </div>
    </div>


    <div class="content-container">
        <div class="bg">
            <div class="bg-list">
                <ul>
                    <li><a href="">拍照</a></li>
                    <li><a href="">从相册选取</a></li>
                    <li id="cancel"><a href="">取消</a></li>
                </ul>
            </div>
        </div>
        <a href="<?php echo U('Center/imgset');?>">
            <div class="head">
                <p>头像</p>
                <img id="pic" style="border-radius: 30px;" src="<?php echo ((isset($info["pic"]) && ($info["pic"] !== ""))?($info["pic"]):'/public/Home/images/img_member.png'); ?>" alt="个人头像">
                <div style="top:24px" class="arrow">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </div>
        </a>
        <a href="<?php echo U('Center/edit_name');?>">
            <div style="border-bottom:10px solid #e5e5e5">
                <div style="height:45px;border:none" class="head">
                    <p style="line-height:45px">用户名</p>
                    <div class="arrow">
                        <img src="/public/Home/images/arrow.png" alt="">
                    </div>
                </div>
            </div>
        </a>
        <!--<div style="height:45px" class="head">-->
        <!--<p style="line-height:45px">银行卡绑定</p>-->
        <!--<div class="arrow">-->
        <!--<img src="/public/Home/images/arrow.png" alt="">-->
        <!--</div>-->
        <!--</div>-->
        <a href="<?php echo U('Center/edit_pwd');?>">
            <div style="height:45px" class="head">
                <p style="line-height:45px">修改登录密码</p>
                <div class="arrow">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </div>
        </a>

        <div class="bottom">
            <a href="<?php echo U('Center/logout');?>">退出登录</a>
        </div>
    </div>




<script>
    $('.bg-list>ul>li a').click(function(e){
        e.preventDefault()
    })
    $('#pic').click(function(){
        $('.bg').css('display','block');
        $('.bg-list').addClass('action')
    });
    $('#cancel').click(function(){
        $('.bg').css('display','none');
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