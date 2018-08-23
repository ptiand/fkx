<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>服务</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/center.css">
    <style>
        .total-section {
            background-color: #f8f8f8;
            display: block;
            margin-bottom: 10px;
            text-align: center;
            font-size: 14px;
        }
        .total-income {
            background: #fff;
            padding: 20px;
            line-height: 2;
        }
        .total-income .clr-red{
            font-size: 30px;
        }
        .total-list {
            display: flex;
            line-height: 1.5;
        }
        .total-item {
            flex: 1;
            padding: 5px 0;
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
        <div class="button">
        </div>
        <div class="title">
            <span>服务</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="container" style="padding-top: 40px;margin-bottom: 45px;">
        <?php if($shop_info['request_flag'] == 1): ?><a class="total-section" href="<?php echo U('Service/total_detail');?>">
            <div class="total-income">
                <span class="clr-red" data-field="month_in">0</span>
                <div class="clr-gray">本月收入 (元)</div>
            </div>
            <div class="total-list">
                <div class="total-item clr-gray">
                    欠费 (元)
                    <div class="clr-red" data-field="not_pay">0</div>
                </div>
                <div class="total-item clr-gray">
                    即将到期 (套)
                    <div class="clr-red" data-field="be_expire">0</div>
                </div>
                <div class="total-item clr-gray">
                    空置率
                    <div class="clr-red" data-field="empty_rate">0</div>
                </div>
                <div class="total-item clr-gray">
                    上月收入 (元)
                    <div class="clr-red" data-field="last_month_in">0</div>
                </div>
            </div>
        </a><?php endif; ?>
        <ul>
            <?php if($shop_info['request_flag'] == 1): ?><li>
                <a href="<?php echo U('Service/house_setup');?>">
                <div class="left">
                    <img src="/public/Home/images/my/publish.png" alt="">
                    <p>公寓管理</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <?php else: ?>
            <li>
                <a href="<?php echo U('Cservice/index');?>">
                <div class="left">
                    <img src="/public/Home/images/content-2.png" alt="">
                    <p>账单管理</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li><?php endif; ?>
            <li>
                <a href="<?php echo U('Mymsg/index');?>">
                <div class="left">
                    <img src="/public/Home/images/content-2.png" alt="">
                    <p>消息管理</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <!-- <li>
                <a href="<?php echo U('Service/check_in');?>">
                <div class="left">
                    <img src="/public/Home/images/my/report.png" alt="">
                    <p>租客登记</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li> -->
        </ul>
        <ul>
            <li>
                <a href="<?php echo U('Favorite/index');?>">
                <div class="left">
                    <img src="/public/Home/images/btbar_fav2.png" alt="">
                    <p>我的收藏</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
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




    <?php if($shop_info["request_flag"] == 1): ?><script src="/public/Static/layer_mobile/layer.js"></script>
    <script type="text/javascript">
        function showToast(msg, cb) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5, end: cb});
        }
        $(function() {
            var loading = layer.open({type: 2, shadeClose: false});
            $.ajax({
                url: "<?php echo U('Service/load_tongchou');?>",
                type: 'GET',
                dataType: 'json',
                success: function (resData) {
                    layer.close(loading);
                    console.log('resp: ', resData)
                    $(".total-section .clr-red").each(function(idx, item) {
                        var me = $(item), field = me.data('field');
                        var text = resData[field] || 0;
                        me.html(field == 'empty_rate' ? (text + '%') : text);
                    })

                },
                error: function (msg) {
                    layer.close(loading);
                    showToast('操作失败')
                }
            });
        });
    </script><?php endif; ?>

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