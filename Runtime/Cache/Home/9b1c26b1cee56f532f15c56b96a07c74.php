<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title><?php echo ((isset($title) && ($title !== ""))?($title):'房客行'); ?></title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/index.css">
    <style type="text/css">
    .header input
    {
        padding-left: 60px;
        padding-right: 36px;
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
        <div class="search">
            <select id="TypeSelect" class="index_sc">
                <option value="3">&nbsp;&nbsp;租房</option>
                <option value="1">&nbsp;&nbsp;新房</option>
                <option value="2">&nbsp;&nbsp;二手</option>
            </select>
            <i class="index_scdrop"><img src="/public/Home/images/icon_drop.png" width="12" /></i>
            <input type="text" placeholder="搜索房源信息" id="SearchInput" />
            <button id="SearchBtn" class="index_scbtn">搜索</button>
        </div>
        <a class="button" href="<?php echo U('Message/index');?>">
            <img src="/public/Home/images/message.png" width="23" height="23">
        </a>
    </div>


    <div class="tab-content-container storageRange">
        <div class="downapp" id="downapp">
            <a href="<?php echo U('Index/downloadapp');?>">
                <img src="/public/Home/images/icon_down.png" alt="房客行图标" width="48" height="48" />
                <h3 class="tit">下载房客行APP</h3>
                <span class="titsub">买卖双方直沟通&nbsp;&nbsp;买房租房全免中介费</span>
                <span class="downbtn">立即打开</span>
                <span></span>
            </a>
            <img src="/public/Home/images/x.png" id="HideDownApp"
                 style="position: absolute;
                        bottom: 52px;
                        right: 5px;
                        height: 12px;
                        margin: 0;"
            />
        </div>
        <div class="main_visual">
            <div class="flicking_con">
            </div>
            <div class="main_image">
                <ul>
                    <?php if(is_array($ads)): $ak = 0; $__LIST__ = $ads;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($ak % 2 );++$ak;?><li><span><a href="<?php echo ($a["url"]); ?>"><img id="b_<?php echo ($ak + 1); ?>" src="<?php echo ($a["pic"]); ?>"></a></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <a href="javascript:;" id="btn_prev"></a>
                <a href="javascript:;" id="btn_next"></a>
            </div>
        </div>
        <div class="mid-container">
            <ul>
                <li>
                    <a class="img" href="<?php echo U('Lists/index2');?>">
                        <img src="/public/Home/images/icon-new.png" alt="新房">
                    </a>
                    <a href="<?php echo U('Lists/index2');?>">新房</a>
                </li>
                <li>
                    <a class="img" href="<?php echo U('OldHouse/index');?>">
                        <img src="/public/Home/images/icon-er.png" alt="二手房">
                    </a>
                    <a href="<?php echo U('OldHouse/index');?>">二手房</a>
                </li>
                <li>
                    <a class="img" href="<?php echo U('RentalHouse/index');?>">
                        <img src="/public/Home/images/icon-zu.png" alt="个人房源">
                    </a>
                    <a href="<?php echo U('RentalHouse/index');?>">个人房源</a>
                </li>
			</ul>
			<ul>
                <li>
                    <a class="img" href="<?php echo U('RentalHouse/index2');?>">
                        <img src="/public/Home/images/icon-mypublic.png" alt="精品公寓">
                    </a>
                    <a href="<?php echo U('RentalHouse/index2');?>">精品公寓</a>
                </li>
                <li>
                    <a class="img" href="<?php echo U('Publish/index');?>">
                        <img src="/public/Home/images/icon-shou.png" alt="租售发布">
                    </a>
                    <a href="<?php echo U('Publish/index');?>">租售发布</a>
                </li>
                <li>
                    <a class="img" href="<?php echo U('MapHouse/index');?>">
                        <img src="/public/Home/images/icon-weituo.png" alt="地图找房">
                    </a>
                    <a href="<?php echo U('MapHouse/index');?>">地图找房</a>
                </li>
            </ul>
        </div>
        <div class="recommend">
            <div class="recommend-header">
                <span class="left">二手房服务</span>
                <a href="<?php echo U('Index/trade');?>"><span class="right">查看完整服务介绍 <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i></span></a>
            </div>
            <div class="recommend-list">
                <a class="right" href="<?php echo U('Index/trade');?>"><img src="/public/Home/images/trade_process.png" alt="交易流程图" width="100%" /></a>
            </div>
        </div>
        <div class="requirement">
            <span>没有我要的房源</span>
            <a class="custom-button" href="<?php echo U('Requirement/custom');?>">
                <span>购房需求定制</span>
            </a>
        </div>
        <!-- 租房 -->
        <div class="recommend">
            <div class="recommend-header">
                <span class="left">精选租房</span>
                <a href="<?php echo U('RentalHouse/index');?>"><span class="right">更多租房 <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i></span></a>
            </div>
            <div class="recommend-list"  id="rentalHouseList">
                <div class="message">
                    <span>加载中...</span>
                </div>
            </div>
        </div>
        <!-- 二手房 -->
        <div class="recommend">
            <div class="recommend-header">
                <span class="left">精选二手房</span>
                <a href="<?php echo U('OldHouse/index');?>"><span class="right">更多二手房 <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i></span></a>
            </div>
            <div class="recommend-list" id="oldHouseList">
                <div class="message">
                    <span>加载中...</span>
                </div>
                <?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$house): $mod = ($i % 2 );++$i;?><div class="recommend-item" url="<?php echo U('Lists/lpinfo',array('id'=>$house['id']));?>">
                        <div class="recommend-item-img">
                            <img src="<?php echo ($house["pic"]); ?>" alt="" />
                        </div>
                        <div class="houseInfo">
                            <span class="title"><?php echo ($house["title"]); ?></span>
                            <div class="attr">
                                <div class="left">
                                    <span><?php echo ($house["size"]); ?></span>
                                    <span><?php echo ($house["address"]); ?></span>
                                </div>
                                <div class="right">
                                    <span><?php echo ($house["price"]); ?></span>
                                    <!-- <span>340000元/㎡</span> -->
                                </div>
                            </div>
                            <!--
                            <div class="label">
                                <span>代售</span>
                                <span>别墅</span>
                                <span>商业区</span>
                            </div>
                            -->
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <!-- 新房 -->
        <div class="recommend">
            <div class="recommend-header">
                <span class="left">精选新房</span>
                <a href="<?php echo U('Lists/index2');?>"><span class="right">更多新房 <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i></span></a>
            </div>
            <div class="recommend-list" id="newHouseList">
                <div class="message">
                    <span>加载中...</span>
                </div>
                <?php if(is_array($houses)): $i = 0; $__LIST__ = $houses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$house): $mod = ($i % 2 );++$i;?><div class="recommend-item" url="<?php echo U('Lists/lpinfo',array('id'=>$house['id']));?>">
                        <div class="recommend-item-img">
                            <img src="<?php echo ($house["pic"]); ?>" alt="" />
                        </div>
                        <div class="houseInfo">
                            <span class="title"><?php echo ($house["title"]); ?></span>
                            <div class="attr">
                                <div class="left">
                                    <span><?php echo ($house["size"]); ?></span>
                                    <span><?php echo ($house["address"]); ?></span>
                                </div>
                                <div class="right">
                                    <span><?php echo ($house["price"]); ?></span>
                                    <!-- <span>340000元/㎡</span> -->
                                </div>
                            </div>
                            <!--
                            <div class="label">
                                <span>代售</span>
                                <span>别墅</span>
                                <span>商业区</span>
                            </div>
                            -->
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="content-footer">
            <span>-- 已经到底了 --</span>
        </div>
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




<script>
function setSliderHeight(){
    var sliderHeight = $(window).width()*120/320;
    $('.main_image,.main_image ul,.main_image li,.main_image img').css('height',sliderHeight);
}
$(window).resize(function() {
    setSliderHeight();
});
var indexLi = $('.main_image>ul li').length;

(function() {
    var wid = window.innerWidth*0.04;
    var left = Number($('.flicking_con').css('left').slice(0,3))
    var num = indexLi-3;
    var slip = left-num*wid;
    console.log(slip);
    $('.flicking_con').css('left',slip)
    for(i=0;i<indexLi;i++){
        var a = '<a></a>'

    $('.flicking_con').append(a)
}
})();

        // var current_loading = false;
        // var page_container_object =  $("body").get(0);
        // var time=1;
        // $(window).scroll(function(){
        //     var scroll_interval = page_container_object.scrollHeight - page_container_object.scrollTop-page_container_object.clientHeight;
        //     if (scroll_interval <= 100 && !current_loading) {
        //         current_loading = true;
        //         loadMore(time);
        //     }
        // });
        // //"向服务器请求数据"
        // function loadMore(te) {
        //     $.get("<?php echo U('Index/loadloupan');?>",{ time : te} , function(data) {
        //         if(data.status==0){
        //             // $(".loadingA").add(".loadingB").add(".loadingC").hide();
        //         }else{
        //             $(".con").last().append(data.houses);
        //             current_loading =false;
        //             time++;
        //         }
        //     });
        // }
        (function () {
            $.get("<?php echo U('Index/loadRecommendNewHouse');?>", function (respHtml)
            {
                $('#newHouseList').html(respHtml);
            });
            $.get("<?php echo U('Index/loadRecommendOldHouse');?>", function (respHtml)
            {
                $('#oldHouseList').html(respHtml);
            });
            $.get("<?php echo U('Index/loadRecommendRentalHouse');?>", function (respHtml)
            {
                $('#rentalHouseList').html(respHtml);
            });
        })();

        $(document).ready(function(){
            $(".main_visual").hover(function(){
                $("#btn_prev,#btn_next").fadeIn()
            },function(){
                $("#btn_prev,#btn_next").fadeOut()
            });

            $dragBln = false;

            $(".main_image").touchSlider({
                flexible : true,
                speed : 200,
                btn_prev : $("#btn_prev"),
                btn_next : $("#btn_next"),
                paging : $(".flicking_con a"),
                counter : function (e){
                    $(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
                }
            });

            $(".main_image").bind("mousedown", function() {
                $dragBln = false;
            });

            $(".main_image").bind("dragstart", function() {
                $dragBln = true;
            });

            $(".main_image a").click(function(){
                if($dragBln) {
                    return false;
                }
            });

            timer = setInterval(function(){
                $("#btn_next").click();
            }, 2500);

            $("#SearchBtn").click(function () {
                var type = $('#TypeSelect').val();
                var keyWord = $("#SearchInput").val();
                var url = '';
                if (type==1) {
                    url = '<?php echo U("Lists/index2");?>';
                } else if (type==2) {
                    url = '<?php echo U("OldHouse/index");?>';
                } else {
                    url = '<?php echo U("RentalHouse/index");?>';
                }
                return window.location.href = `${url}?keyWord=${keyWord}`;
            });
        });
        $("#HideDownApp").click(function () {
            sessionStorage.hideDownLoad = true;
            $("#downapp").hide();
        });
</script>
<script type="text/javascript">
    var downapp=document.getElementById("downapp");
    if(navigator.userAgent.search('fkxapp')>-1 || sessionStorage.hideDownLoad) {
        downapp.style.display ='none';
    } else {
        downapp.style.display ='block';
    }
    var agent = navigator.userAgent.toLowerCase();
    if (agent.match(/MicroMessenger/i) != "micromessenger") {
        if(navigator && navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(function(position){
                if(position && position.coords)
                {
                    console.log('longitude:', position.coords.longitude, 'latitude:', position.coords.latitude);
                    $.post('<?php echo U("Location/report");?>',{longitude: position.coords.longitude, latitude: position.coords.latitude});
                }
            }, function(err){console.log(err);});
        }
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