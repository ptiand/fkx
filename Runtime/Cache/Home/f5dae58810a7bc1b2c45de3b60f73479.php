<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>出租房频道</title>
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

    <div class="header">
        <div class="button">
            <!--<span>厦门</span>-->
            <img src="/public/Home/images/nav_back_icon.png" alt="" id="nav-back-img" width="22" height="22"/>
        </div>
        <div class="search">
            <input type="text" placeholder="搜索租房" id="search-input" value="<?php echo ($keyWord); ?>"/>
            <button id="SearchBtn" class="index_scbtn">搜索</button>
        </div>
        <div class="button">
            <a class="icon-with-dot" href="<?php echo U('Message/index');?>">
                <img src="/public/Home/images/message.png" alt="" width="23" height="23" />
            </a>
        </div>
    </div>


    <style>
        .city-children
        {
            display: none;
        }
        #custom-price-container {
            display: flex;
        }
        #custom-price-container span {
            display: flex;
            align-items: center;
        }
        #custom-price-container input {
            width: 64px;
            height: 24px;
            color: #333333;
            background-color: rgba(250, 250, 250, 1);
            box-sizing: border-box;
            border: 1px solid rgba(215, 215, 215, 1);
            border-radius: 3px;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            font-size: 11px;
            text-align: center;
        }
        #custom-price-container input::placeholder {
            color: #999999;
            text-align: center;
        }
        #min-price-input {
            margin-left: 10px;
        }

        #max-price-input {

        }

        #custom-price-container .between-min-max {
            margin: 0 5px 0 5px;
        }

        #custom-price-container .yuan {
            margin-left: 7px;
            color: #333333;
        }
        #list-container {
            margin-top: 32px;
        }
    </style>

    <div class="container">
        <!--<div class="source-filter">
            <span class="source-item active" value="<?php echo ($sourceMap['Apartment']); ?>">精品公寓</span>
            <div class='source-divide-line' style=""></div>
            <span class="source-item" value="<?php echo ($sourceMap['Personal']); ?>">个人房源</span>
        </div>
        <div class="filter-line-container" style="margin-top: 32px;">-->
        <div class="filter-line-container">
            <div class="item" id="filter-city-btn">
                <span>区域</span>
                <img class="icon" src="/public/Home/images/down.png" alt="">
            </div>
            <div class="item">
                <span>租金</span>
                <img class="icon" src="/public/Home/images/down.png" alt="">
            </div>
            <div class="item">
                <span>户型</span>
                <img class="icon" src="/public/Home/images/down.png" alt="">
            </div>
        </div>
        <div class="filter-container" style="top: 72px;">
            <div class="content">
                <div class="list-content" id="area">
                    <div class="left-content list-container" id="city-filter-container">
                        <div name="city">
                            <span class="item" value="-1">不限</span>
                            <div class="city-children">
                                <span class="item"  onclick="onAreaClick(this, -1)">不限
                                    <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                                </span>
                            </div>
                        </div>
                        <?php if(is_array($districtList)): foreach($districtList as $key=>$district): if($district['pid']=='0'): ?><div name="city">
                                    <span class="item" value="<?php echo ($district['id']); ?>" ><?php echo ($district['city_name']); ?></span>
                                    <div class="city-children">
                                        <span class="item" onclick="onAreaClick(this, -1)">不限
                                            <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                                        </span>
                                        <?php if(is_array($districtList)): foreach($districtList as $key=>$cItem): if($cItem['pid']==$district['id'] ): ?><span class="item" onclick="onAreaClick(this, <?php echo ($cItem['id']); ?>)"><?php echo ($cItem['city_name']); ?>
                                                    <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                                                </span><?php endif; endforeach; endif; ?>
                                    </div>
                                </div><?php endif; endforeach; endif; ?>
                    </div>
                    <div class="right-content list-container" id="area-filter-container">

                    </div>

                </div>

                <div class="list-content" id="price-per-month">
                    <div class="list-container">
                        <span class="item" onclick="onPriceClick(this, -1, 1)">不限
                            <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                        </span>
                        <?php if(is_array($PriceList)): foreach($PriceList as $key=>$price): ?><span class="item" onclick="onPriceClick(this, <?php echo ($price['value']); ?>, 1)"><?php echo ($price['caption']); ?>
                                <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                            </span><?php endforeach; endif; ?>
                        <span class="item" onclick="onPriceClick(this, {}, 2)">
                            <div id="custom-price-container">
                                <span>自定义</span>
                                <input type="number" id="min-price-input" placeholder="最低" max="999999" min="0"/>
                                <span class="between-min-max"> - </span>
                                <input type="number" id="max-price-input" placeholder="最高" max="999999" min="0"/>
                                <span class="yuan">元</span>
                            </div>
                            <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                        </span>
                    </div>
                </div>

                <div class="list-content" id="huxing">
                    <div class="list-container">
                        <span class="item" onclick="onHuxingClick(this, -1)">不限
                            <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                        </span>
                        <?php if(is_array($huxingList)): foreach($huxingList as $key=>$huxing): ?><span class="item" onclick="onHuxingClick(this, <?php echo ($huxing['value']); ?>)"><?php echo ($huxing['caption']); ?>
                                <img class="checked-icon" src="/public/Home/images/check_icon.png"/>
                            </span><?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
            <div class="btn-content">
                <button id="filter-confirm-btn">确定</button>
            </div>
            <div class="mask"></div>
        </div>
        <div id="list-container" class="storageRange">
        </div>
        <div id="empty-container">
            <img src="/public/Home/images/empty_list_icon.png"/>
            <span>找不到或者没有相关的信息</span>
        </div>
        <div id="empty-tail">-- 已经到底了 --</div>
    </div>




    <script>
        var Filter = {
            district: {city: -1, area: -1},
            huxing: -1,
            name: '<?php echo ($keyWord); ?>' || -1,
            source: <?php echo ($source); ?>,
            price: {
                type: 1,
                price: -1,
                customPrice: {
                    min: 0,
                    max: 0
                },
            },

        };

        $('.filter-line-container .item').click(function () {
            setVisibility($(".filter-container"), true);
            $('.list-content.show').removeClass('show');
            $('.filter-line-container .item.active').removeClass('active');
            var index = $(this).index();
            $('.list-content').eq(index).addClass('show');
            $(this).addClass('active');
        });

        $('.mask').click(function () {
            setVisibility($(".filter-container"), false);
            // $('.item.active').removeClass('active');
        });

        $("#nav-back-img").click(function () {
            window.history.back();
        });

        $("#min-price-input").on('keyup', function () {
            Filter.price.customPrice.min = $(this).val();
            // console.log( Filter.customPrice.min);
        });

        $("#max-price-input").on('keyup', function () {
            Filter.price.customPrice.max = $(this).val();
            // console.log( Filter.customPrice.max);
        });

        (function () {
            var pending = false;
            var currentPage = window.sessionStorage.getItem('RentalHouse/index/currentPage') ? window.sessionStorage.getItem('RentalHouse/index/currentPage') : 0;;
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
                console.log(currentPage);
                $.get("<?php echo U('RentalHouse/listItem');?>", {currentPage, pageSize, filter:Filter}, function (data) {
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
                    window.sessionStorage.setItem('RentalHouse/index/currentPage', 0);
                });
            };

            function isListEmpty() {
                return getListSize()==0;
            }

            function getListSize() {
                return $('#list-container').children().length
            }

            $('#filter-confirm-btn').click(function () {
                setVisibility($(".filter-container"), false);
                $('.filter-line-container .item.active').removeClass('active');
                currentPage=0;
                $("#list-container").empty();
                loadMore();
            });

            $('.source-filter .source-item').click(function () {
                $('.source-filter .source-item.active').removeClass('active');
                $(this).addClass('active');
                $("#list-container").empty();
                Filter.source = $(this).attr("value");
                currentPage=0;
                loadMore();
            });

            $("#SearchBtn").click(function (e) {
                var $searchInput = $('#search-input');
                var keyWord = $searchInput.val().trim();
                currentPage=0;
                Filter.name = keyWord||-1;
                $("#list-container").empty();
                loadMore();
            });
            $('#search-input').keydown(function (e) {
                if (e.keyCode == 13) {
                    $("#SearchBtn").click();
                }
            });

            // (function () {
            //     var interval = null;
            //     function startIntervalSearch() {
            //         if (interval) {
            //             return;
            //         }
            //         var $searchInput = $('#search-input');
            //         var oldValue = $searchInput.val().trim();
            //         interval = setInterval(function () {
            //             var value = $searchInput.val().trim();
            //             // console.log("value=", value, 'oldvalue=', oldValue);
            //             if ((oldValue || value) && value!=oldValue) {
            //                 Filter.name = value||-1;
            //                 currentPage=0;
            //                 $("#list-container").empty();
            //                 loadMore();
            //                 oldValue = value;
            //             }
            //         }, 300);
            //     }
            //     function clearIntervalSearch() {
            //         if (interval) {
            //             clearInterval(interval);
            //             interval = null;
            //         }
            //     }
            //
            //     $('#search-input').on('keyup', function () {
            //         startIntervalSearch();
            //     });
            //     $('#search-input').on('blur', function () {
            //         clearIntervalSearch();
            //     });
            // })();
        })();

        function onCityClick(dthis, value) {
            $("#city-filter-container .item.active").removeClass('active');
            $(dthis).addClass('active');
            Filter.district.city = value;
        }

        function onAreaClick(dthis, value) {
            $("#area-filter-container .item.active").removeClass('active');
            $(dthis).addClass('active');
            Filter.district.area = value;
        }

        function onHuxingClick(dthis, value) {
            $("#huxing .item.active").removeClass('active');
            $(dthis).addClass('active');
            Filter.huxing = value;
        }

        function onPriceClick(dthis, value, type) {
            $("#price-per-month .item.active").removeClass('active');
            $(dthis).addClass('active');
            Filter.price.type = type;
            if (type == 1) {
                Filter.price.price = value;
            } else {
                //
            }
        }

        $('#city-filter-container [name="city"]').click(function ()
        {
            // console.log($(this).find('.city-children'));
            var $area = $(this).find('.city-children')[0];
            $('#area-filter-container').html($area.innerHTML);
            Filter.district.area = -1;
            var $currCity = $($(this).find('.item')[0]);
            onCityClick($currCity, $currCity.attr('value'));
        });

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