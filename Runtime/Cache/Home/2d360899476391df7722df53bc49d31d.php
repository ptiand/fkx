<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>我的</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/center.css">


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


    <div class="banner">
        <a href="<?php echo U('Center/editinfo');?>">
            <img src="<?php echo ((isset($info["pic"]) && ($info["pic"] !== ""))?($info["pic"]):'/public/Home/images/img_member.png'); ?>" alt="个人头像">
        </a>
        <h1><?php echo ($info["user_name"]); ?></h1>
        <p><?php echo ($info["phone"]); ?></p>
        <?php if($isWeixin): if($info['openid']): ?><p class="clr-green">已绑定微信</p>
            <?php else: ?>
                <p class="clr-yellow"><a href="javascript:void(0);" class="J_bind_wx">去绑定微信</a></p><?php endif; endif; ?>
        <div class="seting">
            <a href="<?php echo U('Center/editinfo');?>">
                <img src="/public/Home/images/seting.png" alt="">
            </a>
        </div>
    </div>
    <div class="container" style="margin-bottom: 45px;padding-top: 165px;">
        <ul>
            <li>
                <a href="<?php echo U('Center/mypublish');?>">
                <div class="left">
                    <img src="/public/Home/images/my/publish.png" alt="">
                    <p>我的发布</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="<?php echo U('Info/index');?>">
                <div class="left">
                    <img src="/public/Home/images/my/alarm.png" alt="">
                    <p>我的预约</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Report/index');?>">
                <div class="left">
                    <img src="/public/Home/images/my/report.png" alt="">
                    <p>报备记录</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Requirement/index');?>">
                <div class="left">
                    <img src="/public/Home/images/my/custom.png" alt="">
                    <p>定制需求</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <!--
            <a href="<?php echo U('Client/index');?>">
                <li>

                    <div class="left">
                        <img src="/public/Home/images/person.png" alt="">
                        <p>我的客户</p>
                    </div>
                    <div class="right">
                        <img src="/public/Home/images/arrow.png" alt="">
                    </div>

                </li>
            </a>
            -->
            <li>
                <a href="<?php echo U('Center/prize');?>">
                <div class="left">
                    <img src="/public/Home/images/my/reward.png" alt="">
                    <p>佣金和奖励</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('UserCard/index');?>">
                <div class="left">
                    <img src="/public/Home/images/my/card.png" alt="">
                    <p>卡券包</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <!--
            <li>
                <div class="left">
                    <img src="/public/Home/images/my/message.png" alt="">
                    <p>我的消息</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            
            <a href="<?php echo U('Weixin/center');?>">
            <li style="border:none">
                <div class="left">
                    <img src="/public/Home/images/new.png" alt="">
                    <p>推荐注册分佣</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            </a>
            -->
        </ul>
        <!--<ul>-->
            <!--<li>-->
                <!--<div class="left">-->
                    <!--<img src="/public/Home/images/phonelittle.png" alt="">-->
                    <!--<p>绑定手机号</p>-->
                <!--</div>-->
                <!--<div class="right">-->
                    <!--<img src="/public/Home/images/arrow.png" alt="">-->
                <!--</div>-->
            <!--</li>-->
            <!--<li>-->
                <!--<div class="left">-->
                    <!--<img src="/public/Home/images/new.png" alt="">-->
                    <!--<p>推荐注册佣金</p>-->
                <!--</div>-->
                <!--<div class="right">-->
                    <!--<img src="/public/Home/images/arrow.png" alt="">-->
                <!--</div>-->
            <!--</li>-->
        <!--</ul>-->
        <ul>
            <?php if($info["user_type"] == 0): ?><a href="<?php echo U('shops/index');?>">
            <li>
                <div class="left">
                    <img src="/public/Home/images/icon-new.png" alt="">
                    <p>申请开店</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            </a><?php endif; ?>
            <?php if($shop_info["request_flag"] == 1): ?><a href="<?php echo U('shops/edit_shop_info');?>">
            <li>
                <div class="left">
                    <img src="/public/Home/images/icon-new.png" alt="">
                    <p>店铺信息管理</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            </a><?php endif; ?>
            <a href="<?php echo U('Index/pingtai');?>">
            <li>
                <div class="left">
                    <img src="/public/Home/images/lows.png" alt="">
                    <p>平台规则</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            </a>
            <a href="<?php echo U('Center/xy');?>">
            <li>
                <div class="left">
                    <img src="/public/Home/images/about.png" alt="">
                    <p>关于平台</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            </a>
            <a href="tel:0592-3106673">
            <li style="border:none">
                <div class="left">
                    <img src="/public/Home/images/call.png" alt="">
                    <p>联系客服</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
            </li>
            </a>
        </ul>
        <div style="height: 20px;"></div>
    </div>



    
<style>
    .bottom-tips {
        background-color: #FF0000;
        width: 8px;
        height: 8px;
        position: absolute;
        border-radius: 50%;
        overflow: hidden;
        display: none;
    }
</style>
<div class="bottom-tab-bar">
    <div class="bottom-tab">
        <?php if(session('NavBar') =='Index' ){ ?>
        <a class="" href="<?php echo U('Index/index');?>"><img src="/public/Home/images/btbar_index2.png" alt=""></a>
        <a class="actives" href="<?php echo U('Index/index');?>">房客行</a>
        <?php }else{ ?>
        <a class="" href="<?php echo U('Index/index');?>"><img src="/public/Home/images/btbar_index1.png" alt=""></a>
        <a class="" href="<?php echo U('Index/index');?>">房客行</a>
        <?php } ?>
    </div>
    <div class="bottom-tab" id="tabIm">
        <?php if(session('NavBar') =='Im' ){ ?>
        <a href="<?php echo U('Im/index');?>" style="position: relative;"><img src="/public/Home/images/btbar_talk2.png" alt=""><i class="bottom-tips"></i></a>
        <a class="actives" href="<?php echo U('Im/index');?>">微聊</a>
        <?php }else{ ?>
        <a href="<?php echo U('Im/index');?>" style="position: relative;"><img src="/public/Home/images/btbar_talk1.png" alt=""><i class="bottom-tips"></i></a>
        <a href="<?php echo U('Im/index');?>">微聊</a>
        <?php } ?>
    </div>
    <div class="bottom-tab">
        <?php if(session('NavBar') =='Service' ){ ?>
        <a href="<?php echo U('Service/index');?>"><img src="/public/Home/images/btbar_fav2.png" alt=""></a>
        <a class="actives" href="<?php echo U('Service/index');?>">服务</a>
        <?php }else{ ?>
        <a href="<?php echo U('Service/index');?>"><img src="/public/Home/images/btbar_fav1.png" alt=""></a>
        <a href="<?php echo U('Service/index');?>">服务</a>
        <?php } ?>
    </div>
    <div class="bottom-tab">
        <?php if(session('NavBar') =='Center' ){ ?>
        <a href="<?php echo U('Center/index');?>"><img src="/public/Home/images/btbar_my2.png" alt=""></a>
        <a class="actives" href="<?php echo U('Center/index');?>">我的</a>
        <?php }else{ ?>
        <a href="<?php echo U('Center/index');?>"><img src="/public/Home/images/btbar_my1.png" alt=""></a>
        <a href="<?php echo U('Center/index');?>">我的</a>
        <?php } ?>
    </div>
</div>
<script src="/public/Static/webim/sdk/webim.js"></script>
<script src="/public/Static/webim/sdk/json2.js"></script>
<?php if(session('NavBar') !='Im' && $isLogin){ ?>
<script>
    (function () {


        getLoginInfo(function (content) {
            var info = content.loginInfo;
            var loginInfo = {
                identifier: info.identifier,
                userSig: info.sig,
                sdkAppID: info.sdkAppID,
                accountType: info.accountType,
            };
            //监听新消息事件
            //newMsgList 为新消息数组，结构为[Msg]
            function onMsgNotify(newMsgList) {
                getRecentContactList(updateTabImTip);
            }

            var listeners = {
                "onConnNotify": function(e){} //监听连接状态回调变化事件,必填
                ,
                "jsonpCallback": function(e){} //IE9(含)以下浏览器用到的jsonp回调函数，
                ,
                "onMsgNotify": function(e){onMsgNotify(e);console.log("onMsgNotify >>> ", e);} //监听新消息(私聊，普通群(非直播聊天室)消息，全员推送消息)事件，必填
                ,
                "onBigGroupMsgNotify": function(e){} //监听新消息(直播聊天室)事件，直播场景下必填
                ,
                "onGroupSystemNotifys": function(e){} //监听（多终端同步）群系统消息事件，如果不需要监听，可不填
                ,
                "onGroupInfoChangeNotify": function(e){} //监听群资料变化事件，选填
                ,
                "onFriendSystemNotifys": function(e){} //监听好友系统通知事件，选填
                ,
                "onProfileSystemNotifys": function(e){} //监听资料系统（自己或好友）通知事件，选填
                ,
                "onKickedEventCall": function(e){} //被其他登录实例踢下线
                ,
                "onC2cEventNotifys": function(e){} //监听C2C系统消息通道
                ,
                "onAppliedDownloadUrl": function(e){} //申请文件/音频下载地址的回调
            };

            var options = {
                'isAccessFormalEnv': true, //是否访问正式环境，默认访问正式，选填
                'isLogOn': false //是否开启控制台打印日志,默认开启，选填
            };

            webim.login(loginInfo, listeners, options, function(resp){
                // 登录成功
                console.log("webim.login >>> ", resp);
                getRecentContactList(updateTabImTip);
            }, function(err) {
                // alert(err.ErrorInfo);
            });

            function getRecentContactList(callback) {
                webim.getRecentContactList({
                    'Count': 10 //最近的会话数 ,最大为 100
                },function(resp){
                    //业务处理
                    console.log("getRecentContactList >>> ", resp);
                    callback(resp.SessionItem);
                },function(resp){
                    // 错误回调
                    // alert('服务器出错!');
                });
            }

            function hasMessage(itemList) {
                var count = 0;
                for (var item of itemList) {
                    if (count>0) {
                        return true;
                    }
                    count += item.UnreadMsgCount;
                }
                return count>0;
            }

            function updateTabImTip(itemList) {
                if (hasMessage(itemList)) {
                    console.log('new message >>>');
                    $('#tabIm').find('.bottom-tips').show();
                } else {
                    console.log('no message');
                }
            }
        });

        function getLoginInfo(callback) {
            $.post('<?php echo U("Im/getLoginInfo");?>', {}, function (e) {
                // console.log('getLoginInfo', e);
                if (!e || e.status!=1) {
                    return;
                }
                callback(e.content);
            });
        }

        // $('#tabIm').click(function () {
        //     $('#tabIm').find('.bottom-tips').hide();
        // });

    })();
</script>
<?php }?>




    <script type="text/javascript">
        $(".J_bind_wx").click(function(){
            $.ajax({
                url: '<?php echo U("Center/bindWx");?>',
                data:{ajax:1},
                type: 'POST',
                dataType: 'json',
                success: function (resData)
                {
                    if (resData.status == 1) {
                        FkxToast.success(resData.info);
                        location.reload();
                    }else{
                        FkxToast.error(resData.info);
                    }
                },
                error:function(){
                    FkxToast.error('提交失败！');
                }
            });
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