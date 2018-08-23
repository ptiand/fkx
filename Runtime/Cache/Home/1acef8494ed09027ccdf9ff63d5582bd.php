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
            <span>房间登记</span>
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
    </style>
    <form class="btn-content-container" id="Main-Form">
        <input value="<?php echo ($agreement_info['id']); ?>" name="id" hidden/>
        <input value="<?php echo ($room_info['id']); ?>" name="room_id" hidden/>
        <div class="info-item">
            <img class="info-icon" src="/public/Home/images/icon-new.png" alt="">
            <div class="item-content">
                <div class="ellipsis"><?php echo ($house_info["name"]); ?>  <?php echo ($room_info["room_name"]); ?></div>
                <div class="address clr-gray ellipsis"><?php echo ($house_info["address"]); ?></div>
            </div>
        </div>
        <div class="group-container user-info-group">
            <div class="title">租客信息</div>
            <div class="item">
                <span class="item-label">租客1</span>
                <input class="input-container" type="text" name="buy_user_name" value="<?php echo ($agreement_info['buy_user_name']); ?>" required placeholder="请输入姓名" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">&nbsp;</span>
                <input class="input-container" type="number" maxlength="11" name="buy_user_phone" value="<?php echo ($agreement_info['buy_user_phone']); ?>" required placeholder="请输入手机号" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <a class="add-btn" href="javascript:;">
                + 添加租客
            </a>
        </div>

        <div class="group-container">
            <div class="title">租约信息</div>
            <div class="item">
                <span class="item-label">起始日</span>
                <input class="input-container date" id="start_date" readonly name="start_date" value="<?php echo ($agreement_info['start_date']); ?>" required placeholder="请选择" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">截止日</span>
                <input class="input-container date" id="end_date" readonly name="end_date" value="<?php echo ($agreement_info['end_date']); ?>" required placeholder="请选择" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <div class="item date-range">
                <a class="date-range-item" data-range="3" href="javascript:;">三个月</a>
                <a class="date-range-item" data-range="6" href="javascript:;">半年</a>
                <a class="date-range-item" data-range="12" href="javascript:;">一年</a>
            </div>
        </div>

        <div class="group-container">
            <div class="item">
                <span class="item-label">租金</span>
                <input class="input-container" type="number" step="0.01" name="price" value="<?php echo ($agreement_info['price']); ?>" required placeholder="请输入" />
                <span class="item-tips">元/月</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">押金</span>
                <input class="input-container" type="number" step="0.01" name="foregift" value="<?php echo ($agreement_info['foregift']); ?>" required placeholder="请输入" />
                <span class="item-tips">元</span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">押付方式</span>
                <select class="input-container" name="rental_type" required>
                    <?php if(is_array($apartment_type)): foreach($apartment_type as $id=>$rental_type): ?><option value="<?php echo ($id); ?>" <?php echo ($agreement_info['rental_type']==$id?'selected':''); ?>><?php echo ($rental_type); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">收费方式</span>
                <select class="input-container" name="payment_type" required>
                    <?php if(is_array($payment_type)): foreach($payment_type as $id=>$item): ?><option value="<?php echo ($id); ?>" <?php echo ($agreement_info['rental_type']==$id?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label">收费日期</span>
                <select class="input-container" name="pay_date" required>
                    <?php if(is_array($pay_date)): foreach($pay_date as $id=>$item): ?><option value="<?php echo ($id); ?>" <?php echo ($agreement_info['rental_type']==$id?'selected':''); ?>><?php echo ($item); ?></option><?php endforeach; endif; ?>
                </select>
                <i class="gt"><img src="/public/Home/images/icon_gt.png" /></i>
            </div>
        </div>
        <div class="group-container other-cost-group">
            <div class="title">其他费用</div>
            <a class="add-btn" href="javascript:;">
                + 添加费用
            </a>
        </div>
    </form>


    <div class="bottom-btn" id="submit-btn">
        保存
    </div>



    <script id="user-temp" type="template/javascript">
        <div class="user-info-item">
            <div class="item">
                <span class="item-label">租客[#idx]</span>
                <input class="input-container" type="text" name="user_name" value="" required placeholder="请输入姓名" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
            <div class="item">
                <span class="item-label"><a class="del-btn" href="javascript:;">删除</a></span>
                <input class="input-container" type="number" maxlength="11" name="user_phone" value="" required placeholder="请输入手机号" />
                <span class="item-tips"></span>
            </div>
            <div class="seperator"></div>
        </div>
    </script>
    <script id="cost-temp" type="template/javascript">
        <div class="other-cost-item">
            <div class="item">
                <span class="item-label">[#name]</span>
                <input class="cost-input" type="number" step="0.01" name="cost" value="" required placeholder="金额" />
                <input type="hidden" name="cost_name_id" value="[#cost_name_id]"/>
                <input type="hidden" name="cost_type_id" value="[#cost_type_id]"/>
                <input type="hidden" name="cost_unit_id" value="[#cost_unit_id]"/>
                <span class="item-label">[#unit]</span>
                <input class="cost-input current" type="number" step="0.01" name="cur_num" value="" required placeholder="当前数" />
                <span class="current">[#size]</span>
                <a class="del-btn" href="javascript:;"><img src="/public/Home/images/x.png" alt="删除"/></a>
            </div>
            <div class="seperator"></div>
        </div>
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
                    console.log('resp: ', resData)
                    if (resData.status == 1) {
                        showToast(resData.info || '登记成功', function() {
                            location.href = "<?php echo U('Service/tenant_info', array('house_id'=>$room_info['house_id']));?>"
                        });
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

            $('.user-info-group').on('click', '.add-btn', function() {
                var me = $(this);
                var idx = (me.parent().find('.seperator').length / 2 + 1) || 1
                me.before($('#user-temp').text().replace(/\[#idx\]/g, idx))
            }).on('click', '.del-btn', function() {
                $(this).closest('.user-info-item').remove();
            })

            $('#start_date').datePicker({change: function(val) {
                var end_date = $('#end_date').val()
                if(end_date && end_date < val) {
                    showToast('截止日不能大于起始日')
                    return false;
                }
            }});
            
            $('#end_date').datePicker({change: function(val) {
                var start_date = $('#start_date').val()
                if(start_date && start_date > val) {
                    showToast('截止日不能大于起始日')
                    return false;
                }
            }});
            
            $('.date-range-item').on('click', function() {
                var startDateStr = $('#start_date').val();
                
                if (!startDateStr) {
                    return showToast('请选择起始日');
                }

                var endDate, dateArr = [], range = parseInt($(this).data('range'));
                var date = 0;
                endDate = new Date(startDateStr);
                date = endDate.getDate();
                endDate.setMonth(endDate.getMonth() + range);
                endDate.setDate(date == endDate.getDate() ? date - 1 : 0);
                dateArr.push(endDate.getFullYear());
                dateArr.push(endDate.getMonth() + 1);
                dateArr.push(endDate.getDate());
                if (dateArr[1] < 10) {
                    dateArr[1] = '0' + dateArr[1];
                }
                if (dateArr[2] < 10) {
                    dateArr[2] = '0' + dateArr[2];
                }
                $("#end_date").val(dateArr.join('-'))
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
                        var unitSplit = type.value.split('/');
                        var $sameNameAType = me.parent().find(':contains("' + name.value + '")');
                        // console.log('unit:', unit, name, type)
                        if ($sameNameAType.length){
                            return showToast(name.value + '项已经存在');
                        }
                        $temp = $(temp.replace('[#name]', name.value)
                                   .replace('[#cost_name_id]', name.id)
                                   .replace('[#cost_type_id]', type.id)
                                   .replace('[#cost_unit_id]', unit.id)
                                   .replace('[#unit]', type.value)
                                   .replace('[#size]', unitSplit.length > 1 ? unitSplit[1] : ''));
                        if (unit.value != '计量费用') {
                            $temp.find('.current').hide().filter('input').val('0');
                        }
                        me.before($temp);
                        me[0].scrollIntoView();
                    }
                });
                // var idx = (me.parent().find('.seperator').length / 2 + 1) || 1
            }).on('click', '.del-btn', function() {
                $(this).closest('.other-cost-item').remove();
            })
            
            $('#submit-btn').on('click', function() {
                var dataArray = $('#Main-Form').serializeArray();
                var form = document.getElementById("Main-Form");
                try {
                    if (!form.reportValidity()) {
                        return;
                    }
                } catch (e) {

                }
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
                if(!data.start_date){
                    return showToast('租约起始日不能为空')
                }
                if(!data.end_date){
                    return showToast('租约截至日不能为空')
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