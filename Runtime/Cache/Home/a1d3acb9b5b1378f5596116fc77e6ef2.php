<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>统筹详情</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <style>
        .total-section {
            background-color: #f8f8f8;
            display: block;
            margin-bottom: 0.625rem;
            text-align: center;
            font-size: 0.875rem;
        }
        .total-income {
            background: #fff;
            padding: 1.25rem;
            line-height: 2;
            display: block;
        }
        .total-income .clr-red{
            font-size: 1.875rem;
        }
        .total-list {
            display: flex;
            line-height: 1.5;
        }
        .total-item {
            flex: 1;
            padding: 5px 0;
        }
        .house-section {
            background-color: #fff;
            padding: 0 10px;
        }
        .house-title {
            padding: 10px 0;
        }
        .house-title.expand {
            border-bottom: 1px dashed #666;
        }
        .home-icon{
            width: 24px;
            margin-right: 10px;
        }
        .arrow-icon {
            transition: all linear 0.3s;
        }
        .expand .arrow-icon {
            transform: rotate(90deg);
        }
        .house-title::after{
            display: table;
            content: '';
            clear: both;
        }
        .house-content {
            display: flex;
            align-items: center;
            line-height: 1.5;
            text-align: center;
            padding: 10px 0;
        }
        .house-income {
            flex: 2.5;
            width: 20%;
        }
        .house-other {
            flex: 2;
            width: 20%;
        }
        .house-item{
            display: block;
            color: #000000;
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
            <span>统筹详情</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="content-container">
        <div class="total-section">
            <a class="total-income" href="<?php echo U('Service/total_item_detail', array('type' => '1'));?>">
                <span class="clr-red" data-field="month_in">0</span>
                <div class="clr-gray">本月收入 (元)</div>
            </a>
            <div class="total-list">
                <a class="total-item clr-gray" href="<?php echo U('Service/total_item_detail', array('type' => '2'));?>">
                    欠费 (元)
                    <div class="clr-red" data-field="not_pay">0</div>
                </a>
                <a class="total-item clr-gray" href="<?php echo U('Service/total_item_detail', array('type' => '4'));?>">
                    即将到期 (套)
                    <div class="clr-red" data-field="be_expire">0</div>
                </a>
                <a class="total-item clr-gray" href="<?php echo U('Service/total_item_detail', array('type' => '5'));?>">
                    空置率
                    <div class="clr-red" data-field="empty_rate">0</div>
                </a>
                <a class="total-item clr-gray" href="<?php echo U('Service/total_item_detail', array('type' => '3'));?>">
                    上月收入 (元)
                    <div class="clr-red" data-field="last_month_in">0</div>
                </a>
            </div>
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
        <div class="house-section">
            <div class="house-title expand">
                <img class="float-l home-icon" src="/public/Home/images/icon-mypublic.png" alt="">
                [#name]
                <img class="float-r arrow-icon" src="/public/Home/images/arrow.png" alt="">
            </div>
            <div class="house-content">
                <a class="house-income house-item" href="<?php echo U('Service/total_item4house_detail', array('type' => '1', 'house_id' => '[#id]'));?>">
                    <div class="clr-yellow">[#month_in]</div>本月收入 (元)
                </a>
                <div class="house-other">
                    <a class="house-item" href="<?php echo U('Service/total_item4house_detail', array('type' => '2', 'house_id' => '[#id]'));?>">
                        欠费 (元)
                        <div class="clr-red">[#not_pay]</div>
                    </a>
                    <a class="house-item" href="<?php echo U('Service/total_item4house_detail', array('type' => '4', 'house_id' => '[#id]'));?>">
                        即将到期 (套)
                        <div class="clr-red">[#be_expire]</div>
                    </a>
                </div>
                <div class="house-other">
                    <a class="house-item" href="<?php echo U('Service/total_item4house_detail', array('type' => '5', 'house_id' => '[#id]'));?>">
                        空置率
                        <div class="clr-red">[#empty_rate]</div>
                    </a>
                    <a class="house-item" href="<?php echo U('Service/total_item4house_detail', array('type' => '3', 'house_id' => '[#id]'));?>">
                        上月收入 (元)
                        <div class="clr-red">[#last_month_in]</div>
                    </a>
                </div>
            </div>
        </div>
    </script>
    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script type="text/javascript">
        function showToast(msg, cb) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5, end: cb});
        }
        $(function() {
            var loadingTotal = layer.open({type: 2, shadeClose: false});
            $.ajax({
                url: "<?php echo U('Service/load_tongchou');?>",
                type: 'GET',
                dataType: 'json',
                success: function (resData) {
                    layer.close(loadingTotal);
                    console.log('resp: ', resData)
                    $(".total-section .clr-red").each(function(idx, item) {
                        var me = $(item), field = me.data('field');
                        var text = resData[field] || 0;
                        me.html(field == 'empty_rate' ? (text + '%') : text);
                    })

                },
                error: function (msg) {
                    layer.close(loadingTotal);
                    showToast('操作失败')
                }
            });
            
            var currentPage = 0,
                loading = false,
                pageSize = 10,
                isEnd = false;
            var $listContainer = $('#listContainer');

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
                    url: '<?php echo U("Service/load_house_tongchou");?>',
                    data:{page_size: pageSize, cur_page:currentPage},
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
                                html.push(temp.replace("[#name]", item.name)
                                    .replace("[#month_in]", item.month_in || 0)
                                    .replace("[#not_pay]", item.not_pay || 0)
                                    .replace("[#be_expire]", item.be_expire || 0)
                                    .replace("[#empty_rate]", (item.empty_rate || 0) + '%')
                                    .replace("[#last_month_in]", item.last_month_in || 0)
                                    .replace(RegExp(encodeURIComponent('[#id]'), 'g'), item.id)
                                );
                            })
                            if(html)
                            {
                                if(currentPage === 1)
                                {
                                    $listContainer.html(html);
                                }
                                else
                                {
                                    $listContainer.append(html);
                                }
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