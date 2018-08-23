<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    
    <title>发布租房</title>

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
        <a class="button" href="<?php echo U('Service/tenant_info', array('house_id'=>$room_info['house_id']));?>">
            <img src="/public/Home/images/nav_back_icon.png" alt="" width="22" height="22" />
        </a>
        <div class="title">
            <span>登记信息</span>
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
        .group-container .item .gray {
            color: #999 !important;
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
            <div class="title">租客信息</div>
            <div class="item">
                <span class="item-label">租客1</span>
                <input class="input-container gray" type="text" readonly name="buy_user_name" value="<?php echo ($agreement_info['buy_user_name']); ?>" required placeholder="请输入姓名" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">&nbsp;</span>
                <input class="input-container gray" type="number" readonly maxlength="11" name="buy_user_phone" value="<?php echo ($agreement_info['buy_user_phone']); ?>" required placeholder="请输入手机号" />
                <span class="item-tips"></span>
            </div>
        </div>

        <div class="group-container">
            <div class="title">租约信息</div>
            <div class="item">
                <span class="item-label">起始日</span>
                <input class="input-container date gray" id="start_date" readonly name="start_date" value="<?php echo ($agreement_info['start_date']); ?>" required placeholder="请选择" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">截止日</span>
                <input class="input-container date gray" id="end_date" readonly name="end_date" value="<?php echo ($agreement_info['end_date']); ?>" required placeholder="请选择" />
                <span class="item-tips"></span>
            </div>
        </div>

        <div class="group-container">
            <div class="item">
                <span class="item-label">租金</span>
                <input class="input-container gray" type="number" step="0.01" name="price" readonly value="<?php echo ($agreement_info['price']); ?>" required placeholder="请输入" />
                <span class="item-tips">元/月</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">押金</span>
                <input class="input-container gray" type="number" step="0.01" readonly name="foregift" value="<?php echo ($agreement_info['foregift']); ?>" required placeholder="请输入" />
                <span class="item-tips">元</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">押付方式</span>
                <select class="input-container gray" name="rental_type" disabled required>
                    <?php if(is_array($apartment_type)): foreach($apartment_type as $id=>$rental_type): ?><option value="<?php echo ($id); ?>" <?php echo ($agreement_info['rental_type']==$id?'selected':''); ?>><?php echo ($rental_type); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">收费方式</span>
                <select class="input-container gray" name="payment_type" disabled required>
                    <?php if(is_array($payment_type)): foreach($payment_type as $id=>$item): ?><option value="<?php echo ($id); ?>" <?php echo ($agreement_info['rental_type']==$id?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">收费日期</span>
                <select class="input-container gray" name="pay_date" disabled required>
                    <?php if(is_array($pay_date)): foreach($pay_date as $id=>$item): ?><option value="<?php echo ($id); ?>" <?php echo ($agreement_info['rental_type']==$id?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
        </div>
        <div class="group-container other-cost-group">
            <div class="title">其他费用</div>
            <?php if(is_array($agreement_info['cost_info'])): foreach($agreement_info['cost_info'] as $key=>$cost): ?><div class="other-cost-item">
                <div class="item">
                    <span class="item-label"><?php echo ($cost_name_id[$cost['cost_unit_id']][$cost['cost_name_id']]); ?></span>
                    <input class="cost-input gray" type="number" step="0.01" disabled name="cost" value="<?php echo ($cost['cost']); ?>" required placeholder="金额" />
                    <input type="hidden" name="cost_name_id" value="<?php echo ($cost['cost_name_id']); ?>"/>
                    <input type="hidden" name="cost_type_id" value="<?php echo ($cost['cost_type_id']); ?>"/>
                    <input type="hidden" name="cost_unit_id" value="<?php echo ($cost['cost_unit_id']); ?>"/>
                    <span class="item-label"><?php echo ($cost_type_id[$cost['cost_unit_id']][$cost['cost_type_id']]); ?></span>
                    <input class="cost-input current gray" type="number" step="0.01" disabled name="cur_num" value="<?php echo ($cost['cur_num']); ?>" required placeholder="当前数" />
                    <span class="current">[#size]</span>
                </div>
                <div class="seperator"></div>
            </div><?php endforeach; endif; ?>
        </div>
    </form>


    <div class="bottom-btn flex">
        <a class="flex-1" href="<?php echo U('/Service/bills_info', array('room_id'=>$room_info['id']));?>">账单</a>
        <a class="flex-1" href="javascript:;" id="exit-btn">退房</a>
    </div>



    <script src="/public/Static/layer_mobile/layer.js"></script>
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
                        showToast(resData.info || '登记成功', function() {
                            location.href = "<?php echo U('Service/tenant_info', array('house_id'=>$room_info['house_id']));?>"
                        });
                    } else {
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

            $('.other-cost-item .item').each(function(idx, item) {
                var me = $(this);
                if(me.find('[name=cost_unit_id]').val() != '2') {
                    me.find('.current').hide();
                } else {
                    me.find('span.current').text(me.find('.item-label:eq(1)').text().split('/')[1])
                }
            })

            var costNameId = <?php echo json_encode($cost_name_id);?>;
            var costTypeId = <?php echo json_encode($cost_type_id);?>;
            var costUnitId = <?php echo json_encode($cost_unit_id);?>;
            // var costTypeData = $.map(costTypeId, function(val, key) {return {id: key, value: val}});
            var costUnitData = $.map(costUnitId, function(val, key) {return {id: key, value: val}});
            function costNameData(unitId, cb) {
                cb($.map(costNameId[unitId], function(val, key) {
                    return {id: key, value: val}
                }));
            }
            function costTypeData(unitId, nameId, cb) {
                if (unitId == '2') {
                    cb([{id: nameId, value: costTypeId[unitId][nameId]}]);
                } else {
                    cb($.map(costTypeId[unitId], function(val, key) {
                        return {id: key, value: val}
                    }));
                }
            }
            $('.other-cost-group').on('click', '.add-btn', function() {
                var me = $(this), temp = $('#cost-temp').text();

                new IosSelect(3, [costUnitData, costNameData, costTypeData], {
                    title: '',
                    itemHeight: 35,
                    callback: function (unit, name, type) {
                        var unitSplit = type.value.split('/')
                        console.log('unit:', unit, name, type)
                        if (me.parent().find('[name="cost_name_id"][value="' + name.id + '"]').siblings('[name="cost_unit_id"][value="' + unit.id + '"]').length){
                            return showToast(name.value + '项已经存在');
                        }
                        $temp = $(temp.replace('[#name]', name.value)
                                   .replace('[#cost_name_id]', name.id)
                                   .replace('[#cost_type_id]', type.id)
                                   .replace('[#cost_unit_id]', unit.id)
                                   .replace('[#unit]', type.value)
                                   .replace('[#size]', unitSplit.length > 1 ? unitSplit[1] : ''));
                        if (unit.value != '计量费用') {
                            $temp.find('.current').hide().find('input').val('0');
                        }
                        me.before($temp);
                        me[0].scrollIntoView();
                    }
                });
                // var idx = (me.parent().find('.seperator').length / 2 + 1) || 1
            }).on('click', '.del-btn', function() {
                $(this).closest('.other-cost-item').remove();
            })
            

            $('#exit-btn').on('click', function() {
                layer.open({
                    title: '退房确认',
                    content: '请确定是否有账单未结清？',
                    shadeClose: false,
                    btn: ['确定', '取消'],
                    yes: function(index) {
                        layer.close(index);
                        sendAjax({
                            url: '<?php echo U("Service/check_out");?>',
                            data: {a_id: "<?php echo ($agreement_info['id']); ?>"}
                        })
                    }
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