<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    
    <title>账单</title>

    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Static/ios-select/iosSelect.css">
    <link rel="stylesheet" href="/public/Home/css/index.css">
    <link rel="stylesheet" href="/public/Home/css/publish.css">


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
        <a class="button" href="javascript:history.go(-1)">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22" />
        </a>
        <div class="title">
            <span>账单</span>
        </div>
        <div class="button"></div>
    </div>


    <style>
        .info-item {
            display: flex;
            position: relative;
            background-color: #fff;
            padding: 6px;
            color: #000;
        }
        .item-content {
            flex: 1;
            width: 10%;
            padding: 0 6px;
        }
        .info-icon {
            display: block;
            width: 24px;
            height: 24px;
        }
        .address {
            font-size: 14px;
            margin-top: 8px;
        }
        .detail-item{
            font-size: 13px;
        }
        .flex-2 {
            flex: 2;
            text-align: right;
        }
        .flex-3 {
            flex: 3;
        }
        .input-container.price-text {
            text-align: right;
            display: block !important;
            margin-right: 0!important;
        }
        .price-input {
            width: 80px;
            border: 1px solid #cdcdcd;
            padding: 4px 0;
            text-align: right;
        }
        .add-btn {
            width: 100%;
            text-align: center;
            display: block;
            background-color: white;
            padding: 10px 0;
        }
        .group-container .item.date-range {
            justify-content: space-around;
        }
        .date-range-item {
            border: 1px solid #34BBBE;
            border-radius: 2px;
            padding: 2px 10px;
        }
        .other-cost-item .cost-input{
            width: 10%;
            flex: 1;
            border: 1px solid #dedede;
            margin-right: 8px;
            padding: 4px 6px;
            border-radius: 4px;
        }
        .other-cost-item .del-btn {
            margin-left: 6px;
        }
        .other-cost-item .del-btn img {
            width: 18px;
        }
        .bottom-btn.flex {
            display: flex;
        }
        .bottom-btn.flex a {
            flex: 1;
            color: #fff;
            border-left: 1px solid #fff;
        }
        .bottom-btn.flex a.disabled {
            background-color: #cdcdcd;
        }
        .layui-m-layer4 .layui-m-layercont{
            padding: 20px;
        }
    </style>
    <form class="btn-content-container" id="Main-Form">
        <input value="<?php echo ($agreement_info['id']); ?>" name="id" hidden/>
        <input value="<?php echo ($room_info['id']); ?>" name="room_id" hidden/>
        <div class="info-item">
            <img class="info-icon" src="/public/Home/images/icon-new.png" alt="">
            <div class="item-content">
                <div class="ellipsis"><?php echo ($house_info["name"]); ?> <?php echo ($room_info["room_name"]); ?></div>
                <div class="address clr-gray ellipsis"><?php echo ($house_info["address"]); ?></div>
            </div>
        </div>
        <div class="group-container user-info-group">
            <div class="title">账单信息</div>
            <div class="item">
                <div class="input-container">租客： <?php echo ($agreement_info['buy_user_name']); ?></div>
                <div class="input-container">押付方式： <?php echo ($agreement_info['rental_type']); ?></div>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <div class="input-container">手机号码： <?php echo ($agreement_info['buy_user_phone']); ?></div>
                <div class="input-container">收费日期： <?php echo ($agreement_info['pay_date']); ?></div>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <div class="input-container">入住时间： <?php echo ($agreement_info['start_date']); ?></div>
                <div class="input-container">
                    <?php if($agreement_info['pay_status'] == '1'): ?><span>账单状态： <span class="clr-green">已支付</span></span>
                    <?php else: ?>
                        <span>账单状态： <span class="clr-yellow">未支付</span></span><?php endif; ?>
                </div>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <div class="input-container">账单周期： <?php echo ($bill_info['start_date']); ?> ~ <?php echo ($bill_info['end_date']); ?> </div>
            </div>
        </div>
        <div class="group-container">
            <div class="title">账单明细</div>
            <div class="item">
                <div class="flex-3">收费项</div>
                <div class="flex-2">使用量</div>
                <div class="flex-2">金额</div>
            </div>
            <div class="seperator"></div>
            <div class="item detail-item">
                <div class="flex-3">房租</div>
                <div class="flex-2"></div>
                <div class="flex-2">￥<?php echo ($bill_info['price']); ?></div>
            </div>
            <?php if(is_array($bill_info['details'])): foreach($bill_info['details'] as $key=>$item): if($item['cost_unit_id'] != '2' || $item['cur_num'] != 0): ?><div class="seperator"></div>
                <div class="item detail-item">
                    <div class="flex-3">
                        <?php if($item['cost_unit_id'] != '2' && $item['cost_type_id'] == 1): echo ($cost_name_id[$item['cost_unit_id']][$item['cost_name_id']]); ?>(<?php echo ($cost_type_id[$item['cost_unit_id']][$item['cost_type_id']]); ?>)
                        <?php else: ?>
                            <?php echo ($cost_name_id[$item['cost_unit_id']][$item['cost_name_id']]); ?>(<?php echo ($item['cost']); ?> <?php echo ($cost_type_id[$item['cost_unit_id']][$item['cost_type_id']]); ?>)<?php endif; ?>
                    </div>
                    <?php if($item['cost_unit_id'] == '2'): ?><div class="flex-2"><?php echo ($item['cur_num']); ?> - <?php echo ($item['last_num']); ?></div>
                    <?php else: ?>
                        <div class="flex-2"></div><?php endif; ?>
                    <div class="flex-2">￥<?php echo ($item['total_cost']); ?></div>
                </div><?php endif; endforeach; endif; ?>
            <div class="item">
                <span class="item-label">合计</span>
                <div class="input-container price-text">
                    ￥<?php echo ($bill_info['gift_price'] + $bill_info['total_price']); ?>
                </div>
            </div>
            <div class="item">
                <span class="item-label">优惠金额</span>
                <?php if($bill_info['gift_price'] == 0): ?><div class="input-container price-text">
                    ￥<input class="price-input" type="number" name="gift_price" id="gift_price" />
                </div>
                <?php else: ?>
                <div class="input-container price-text">
                    ￥<?php echo ($bill_info['gift_price']); ?>
                </div><?php endif; ?>
            </div>
            <div class="item">
                <span class="item-label">应收金额</span>
                <div class="input-container price-text" id="proceeds" data-total="<?php echo ($bill_info['total_price']); ?>">
                    ￥<?php echo ($bill_info['total_price']); ?>
                </div>
            </div>
        </div>
    </form>


    <div class="bottom-btn flex">
        <?php if(empty($unwrite_bill_detail)): ?><a class="flex-1 disabled" href="javascript:;">抄表</a>
        <?php else: ?>
            <a class="flex-1" href="javascript:;" id="save-cost-btn">抄表</a><?php endif; ?>
        <?php if($bill_info['gift_price'] == 0): ?><a class="flex-1" href="javascript:;" id="save-gift-btn">保存优惠金额</a>
        <?php else: ?>
            <a class="flex-1 disabled" href="javascript:;">保存优惠金额</a><?php endif; ?>
        <?php if($bill_info['is_msg'] == 0): ?><a class="flex-1" href="javascript:;" id="notice-btn">通知</a>
        <?php else: ?>
            <a class="flex-1 disabled" href="javascript:;">通知</a><?php endif; ?>
    </div>



    <script id="cost-form-temp" type="template/javascript">
        <form id="cost-form" class="user-info-item">
            <div class="group-container">
                <div class="item">
                    <div class="flex-3">抄表项</div>
                    <div class="flex-2">上期数</div>
                    <div class="flex-2">抄表数</div>
                </div>
                <div class="seperator"></div>
                <?php if(is_array($unwrite_bill_detail)): foreach($unwrite_bill_detail as $key=>$item): ?><input type="hidden" name="detail_id" value="<?php echo ($item['id']); ?>" />
                    <div class="item detail-item">
                        <div class="flex-3">
                            <?php echo ($cost_name_id[$item['cost_unit_id']][$item['cost_name_id']]); ?>
                        </div>
                        <div class="flex-2"><?php echo ($item['last_num']); ?></div>
                        <div class="flex-2">
                            <input class="price-input" style="width: 60%" type="number" name="cur_num" value="" />
                        </div>
                    </div>
                    <div class="seperator"></div><?php endforeach; endif; ?>
            </div>
        </form>
    </script>
    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script src="/public/Static/ios-select/iosSelect.js"></script>
    <script src="/public/Static/ios-select/date-picker.js"></script>
    <script>
        function showToast(msg, cb) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5, end: cb});
        }
        function sendAjax(data, layerIdx) {
            var loading = layer.open({type: 2, shadeClose: false});
            $.ajax({
                url: data.url,
                data: data.data,
                type: 'POST',
                dataType: 'json',
                success: function (resData) {
                    layer.close(loading);
                    if (resData.status == 1) {
                        showToast(resData.info || '操作成功', function() {
                            location.reload();
                        });
                        layer.close(layerIdx);
                    } else {
                        // layer.close(loading);
                        showToast(resData.info);
                    }

                },
                error: function (msg) {
                    console.log('msg: ', msg);
                    layer.close(loading);
                    showToast('操作失败')
                }
            });
        }

        $(function() {
            
            // $('#gift_price').on('change', function(e) {
            //     var me = $(this), $proceeds = $('#proceeds');
            //     console.log('gift_price change:', me.val());
            //     var input = Number(me.val()) || 0, total = Number($proceeds.data('total'));

            //     $proceeds.text('￥' + (total - input).toFixed(2));
            // })

            $('#notice-btn').on('click', function() {
                layer.open({
                    title: '通知确认',
                    content: '确认发送账单通知？',
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        layer.close(index);
                        sendAjax({
                            url: '<?php echo U("Service/push_bills");?>',
                            data: {b_id: "<?php echo ($bill_info['id']); ?>"}
                        })
                    }
                })
            })

            $('#save-gift-btn').on('click', function() {
                var giftPrice = Number($('#gift_price').val());
                if(!giftPrice || giftPrice < 0){
                    return showToast('优惠金额必须是数值且大于0');
                }
                if(giftPrice > parseFloat($('#proceeds').data('total'))){
                    return showToast('优惠金额不能大于合计金额');
                }
                layer.open({
                    title: '保存确认',
                    content: '确认保存优惠金额，保存后将不可修改？',
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        layer.close(index);
                        sendAjax({
                            url: '<?php echo U("Service/gift_bill");?>',
                            data: {bill_id: "<?php echo ($bill_info['id']); ?>", gift_price: giftPrice}
                        })
                    }
                })
            })

            $('#save-cost-btn').on('click', function() {
                layer.open({
                    title: '抄表',
                    type: 4,
                    content: $('#cost-form-temp').text(),
                    shadeClose: false,
                    btn: ['提交', '取消'],
                    yes: function(index) {
                        
                        var form = $('#cost-form');
                        var dataArray = form.serializeArray();
                        try {
                            if (!form[0].reportValidity()) {
                                return;
                            }
                        } catch (e) {

                        }
                        
                        var data = {detail_id: [], cur_num: []};
                        var formData = {detail_id: [], cur_num: []};

                        dataArray.forEach(function (val) {
                            if (!data[val.name]) {
                                data[val.name] = []
                            }
                            data[val.name].push(val.value);
                        })
                        $.each(data.cur_num, function(idx, val) {
                            if (val && val > 0) {
                                formData.detail_id.push(data.detail_id[idx]);
                                formData.cur_num.push(val);
                            }
                        })

                        if(formData.cur_num.length){
                            sendAjax({
                                url: '<?php echo U("Service/update_cost_detail");?>',
                                data: formData
                            }, index)
                        } else {
                            showToast('请填写抄表数');
                        }
                    }
                })
            })
            
            $('#submit-btn').on('click', function() {
                var dataArray = $('#Main-Form').serializeArray();
                var form = document.getElementById("Main-Form");
                // try {
                //     if (!form.reportValidity()) {
                //         return;
                //     }
                // } catch (e) {

                // }
                var data = {}, arrayData = {};
                var arrayNames = ['user_name', 'user_phone', 'cost', 'cost_name_id', 'cost_type_id', 'cost_unit_id', 'cur_num']
                dataArray.forEach(function (val) {
                    if (arrayNames.indexOf(val.name) !== -1) {
                        if (!arrayData[val.name]) {
                            arrayData[val.name] = [];
                        }
                        arrayData[val.name].push(val.value);
                    } else {
                        data[val.name] = val.value;
                    }
                })
                
                if(!/1[3-9]\d{9}/.test(data.buy_user_phone)){
                    return showToast('租客1的手机号码格式错误')
                }
                if(arrayData.user_name) {
                    var msg = '';
                    data.users = [];
                    arrayData.user_name.forEach(function(name, i) {
                        if(!/1[3-9]\d{9}/.test(arrayData.user_phone[i])){
                            return msg = '租客' + (i + 2) + '的手机号码格式错误'
                        }
                        data.users.push({user_name: name, user_phone: arrayData.user_phone[i]})
                    })
                    if (msg) {
                        return showToast(msg);
                    }
                }
                if(arrayData.cost) {
                    data.costs = [];
                    arrayData.cost.forEach(function(cost, i) {
                        data.costs.push({
                            cost: cost, 
                            cost_name_id: arrayData.cost_name_id[i], 
                            cost_type_id: arrayData.cost_type_id[i], 
                            cost_unit_id: arrayData.cost_unit_id[i], 
                            cur_num: arrayData.cur_num[i]
                        })
                    })
                }
                
                // layer.close(index);
                console.log('data:', data);
                sendAjax({
                    url: '<?php echo U("Service/update_register_tenant");?>',
                    data: data
                })
            })
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