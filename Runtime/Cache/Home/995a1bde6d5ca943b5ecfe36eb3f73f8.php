<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title><?php echo ($house_info["name"]); ?></title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <style>
        .total-section {
            padding: 20px 10px;
            color: #999;
            margin-bottom: 5px;
            background-color: #fff;
            display: none;
        }
        .list-item{
            color: #000;
            font-weight: bold;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-bottom: 1px solid #f4f4f4;
            background-color: #fff;
        }
        .list-item:last-child{
            border-bottom: none;
        }
        .item-content{
            flex: 1;
        }
        .item-content .clr-gray {
            font-weight: normal;
        }
        .item-price {
            width: 30%;
        }
        .tip-section {
            display: none;
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-top: 12px;
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
            <span><?php echo ($house_info["name"]); ?></span>
        </div>
        <div class="button"></div>
    </div>


    <div class="content-container">
        <div class="total-section">
            <?php echo ($type_name); ?>
            <b class="float-r clr-black"></b>
        </div>
        <div id="listContainer">
        </div>
        <div id="loadingTip" class="tip-section"> 加载中... </div>
        <div id="endTip" class="tip-section">- 已经到底了 -</div>
        <div id="emptyTip" class="tip-section">
            <img src="/public/Home/images/empty_list_icon.png"/>
            <div style="margin-top: 12px;">- 暂无信息 -</div>
        </div>
    </div>





    <script id="item-temp" type="template/javascript">
        <?php if($type == 2): ?><a class="list-item" href="javascript:;">
            <div class="item-content">
                <div>[#name]房<span class="clr-red">(欠)</span></div>
                <div class="clr-gray">[#start_date] ~ [#end_date]</div>
            </div>
            <div class="item-price clr-red">￥[#price]</div>
        </a>
        <?php elseif($type == 3): ?>
        <a class="list-item" href="javascript:;">
            <div class="item-content">
                <div>[#name]房</div>
                <div class="clr-gray">[#pay_date]</div>
            </div>
            <div class="item-price">￥[#price]</div>
        </a>
        <?php elseif($type == 4): ?>
        <a class="list-item" href="javascript:;">
            <div class="item-content">
                <div>[#name]房</div>
                <div class="clr-gray">[#start_date] ~ [#end_date]</div>
            </div>
            <div class="item-price">[#end_date]到期</div>
        </a>
        <?php elseif($type == 5): ?>
        <a class="list-item" href="javascript:;">
            <div class="item-content">
                <div>[#name]房</div>
            </div>
            <div class="item-price">空置</div>
        </a>
        <?php else: ?>
        <a class="list-item" href="<?php echo U('Service/bills_info', array('room_id' => '[#id]'));?>">
            <div class="item-content">
                <div>[#name]房</div>
                <div class="clr-gray">[#pay_date]</div>
            </div>
            <div class="item-price">￥[#price]</div>
            <img src="/public/Home/images/arrow.png" alt="">
        </a><?php endif; ?>
    </script>
    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script type="text/javascript">
        function showToast(msg, cb) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5, end: cb});
        }
        $(function() {
            var currentPage = 0,
                loading = false,
                pageSize = 10,
                isEnd = false;
            var $listContainer = $('#listContainer');
            var TYPE_FIELD_MAP = {1: 'month_in', 2: 'not_pay', 3: 'last_month_in', 4: 'be_expire', 5: 'empty_rate'};

            var scrollBefore = $(window).scrollTop();
            $(window).scroll(function () {
                var scrollAfter = $(window).scrollTop();
                if (scrollAfter < scrollBefore) {
                    return;
                }
                var body = $('body').get(0);
                var scrollBottom = body.scrollHeight - body.scrollTop - body.clientHeight;
                if (scrollBottom <= 80 && !loading) {
                    scrollBefore = scrollAfter;
                    getList();
                }
            });

            getList();
            $listContainer.on('click', '.house-title', function() {
                var me = $(this)
                me.toggleClass('expand')
                me.next().slideToggle()
            })
            function getList()
            {
                if(loading || isEnd) return;
                loading = true;
                $('#loadingTip').show();
                currentPage ++;
                $.ajax({
                    url: "<?php echo U('Service/'.$type_url);?>",
                    data:{page_size: pageSize, cur_page:currentPage, house_id: "<?php echo I('house_id');?>"},
                    type: 'POST',
                    dataType: 'json',
                    success: function (resData)
                    {
                        loading = false;
                        $('#loadingTip').hide();
                        // console.log('response data: ', resData);
                        if(Array.isArray(resData.rows))
                        {
                            var html = [], temp = $("#item-temp").text(), itemCount = 0;
                            
                            resData.rows.forEach(function(item, idx) {
                                var item_html = temp.replace("[#name]", item.room_name);

                                switch('<?php echo ($type); ?>'){
                                    case '2': 
                                        item_html = item_html.replace("[#start_date]", item.start_date)
                                            .replace("[#end_date]", item.end_date)
                                            .replace("[#price]", parseFloat(item[TYPE_FIELD_MAP['<?php echo ($type); ?>']]) || 0)
                                            .replace(RegExp(encodeURIComponent('[#id]'), 'g'), item.id);
                                        break;
                                    case '3': 
                                        item_html = item_html.replace("[#pay_date]", item.pay_date)
                                            .replace("[#price]", parseFloat(item[TYPE_FIELD_MAP['<?php echo ($type); ?>']]) || 0)
                                            .replace(RegExp(encodeURIComponent('[#id]'), 'g'), item.id);
                                        break;
                                    case '4': 
                                        item_html = item_html.replace("[#start_date]", item.start_date)
                                            .replace(/\[#end_date\]/g, item.end_date);
                                        break;
                                    case '5': 
                                        item_html = item_html;
                                        break;
                                    default:
                                        item_html = item_html.replace("[#pay_date]", item.pay_date)
                                            .replace("[#price]", item.price || 0)
                                            .replace(RegExp(encodeURIComponent('[#id]'), 'g'), item.room_id);
                                        break;
                                }
                                html.push(item_html);
                            })
                            if(html.length)
                            {
                                if(currentPage === 1)
                                {
                                    $listContainer.html(html);
                                }
                                else
                                {
                                    $listContainer.append(html);
                                }
                                var total_html = '';
                                switch('<?php echo ($type); ?>'){
                                    case '2': 
                                    total_html = '￥' + (resData.total_month_in || 0);
                                    break;
                                    case '3': 
                                    total_html = '￥' + (resData.total_month_in || 0);
                                    break;
                                    case '4': 
                                    total_html = resData.total_be_expire + '套';
                                    break;
                                    case '5': 
                                    total_html = resData.total_empty_rate + '%, 共' + resData.total + '套';
                                    break;
                                    default:
                                    total_html = '￥' + (resData.total_month_in || 0);
                                    break;
                                }
                                $('.total-section').show().find('.clr-black').html(total_html);
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