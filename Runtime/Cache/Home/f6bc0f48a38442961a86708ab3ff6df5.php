<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <meta name="description" content="房客行——移动互联网房产C2C服务平台，买卖双方直沟通，买房租房全免中介费，新房返高佣，海量租房券等你拿。" />
    <meta name="Keywords" content="房地产 新房 一手房 二手房 公寓 出租 C2C 中介" />
    <title>店铺申请</title>
    <link rel="stylesheet" href="/public/Home/css/common.css">
    
    <link rel="stylesheet" href="/public/Home/css/center.css">
    <style>
        .shop-intro{
            text-indent: 28px;
            font-size: 16px;
            padding: 10px;
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
            <img src="/public/Home/images/nav_back_icon.png" alt="" id="NavBackImg" width="22" height="22"/>
        </div>
        <div class="title">
            <span>店铺申请</span>
        </div>
        <div class="button"></div>
    </div>


    <div class="content-container container">
        <ul>
            <li>
                <a href="<?php echo U('Shops/apply4shop');?>">
                <div class="left">
                    <img src="/public/Home/images/my/alarm.png" alt="">
                    <p>公寓申请</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Shops/apply4personal');?>">
                <div class="left">
                    <img src="/public/Home/images/my/report.png" alt="">
                    <p>个人申请</p>
                </div>
                <div class="right">
                    <img src="/public/Home/images/arrow.png" alt="">
                </div>
                </a>
            </li>
        </ul>
        <div class="shop-intro">
            房客行将为店铺免费提供租房saas系统，实现房源管理,租客合同管理,账单管理, 在线催租收租,水电抄表计算等一系列租后管理。
        </div>
    </div>





    <script src="/public/Static/layer_mobile/layer.js"></script>
    <script>
        function showToast(msg, cb) {
            layer.open({content: msg, type: 3, skin: 'msg', time: 1.5, end: cb});
        }
        // 注册并更新input后面那个n/m
        function onInputKeyUp($input, $label, maxLength) {
            $input.on('keyup', function (e) {
                $label.text(`${$(this).val().length}/${maxLength}`);
            }).keyup();
        }
            
        function onUploadFile(file, onFinish) {
            transformFileToFormData(file);

            function onUploadFinished(url) {
                console.log('url:', url)
                onFinish(url);
            }

            // 上传图片
            function upload (formData) {
                var startTime = Date.now();
                layer.open({
                    type: 2,
                    content: '上传中，请稍候',
                    shadeClose: false
                });
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e){console.log('上传中 ', e.loaded / e.total*100+"%")}, false);
                xhr.addEventListener('load', function(){console.log("加载中");}, false);
                xhr.addEventListener('error', function(){console.error("上传失败！");}, false);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        layer.closeAll();
                        var result = JSON.parse(xhr.responseText);
                        if (xhr.status === 200) {
                            // 上传成功
                            console.log("result <>>>", result);
                            if (result.status == 1) {
                                for (var i = 0; i < result.data.length; i++) {
                                    onUploadFinished(result.data[i]);
                                }
                            } else {
                                showToast('上传失败！请稍后重试！');
                            }

                        } else {
                            // 上传失败
                            showToast('failed');
                        }
                    }
                };
                var path = '<?php echo U("Shops/uploadPic");?>';
                xhr.open('POST', path , true);
                xhr.send(formData);
            }

            function transformFileToFormData (file) {
                var formData = new FormData();
                for (var i = 0; i <file.length; i++) {
                    formData.append(["file["+i+"]"], file[i]);
                }
                upload(formData);
            }
        }
        
        function submit() {
            var form = document.getElementById("Main-Form");
            try {
                if (!form.reportValidity()) {
                    return;
                }
            } catch (e) {

            }

            var formData = {};
            buildFormData($(form).serializeArray(), formData);
            console.log(">>>", formData);
            layer.open({
                content: '确定提交申请？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post('<?php echo U("Shops/apply_shop");?>', formData, function (e) {
                        // console.log('hello world', e);
                        layer.close(index);
                        if (e.status) {
                            showToast('提交成功');
                            window.location.href = '<?php echo U("Shops/edit_shop_info");?>';
                        } else {
                            showToast(e.info || '提交失败，请稍后重试！');
                        }
                    });
                }
            });
            
            function buildFormData(serializeArray, targetObj) {
                for (var item of serializeArray) {
                    targetObj[item.name] = item.value;
                }
                return targetObj;
            }
        }

        $(function(){
            var logoFile = $("#logo-Upload-Input")
            var bgFile = $("#bg-Upload-Input")
            var certFile = $("#cert-Upload-Input")
            var bgInput = $("#bg-input")

            $('#NavBackImg').click(function () {
                window.history.back(-1);
            });
            
            if(bgInput.val()){
                $("#banner").css("backgroundImage", "url(" + bgInput.val() + ")")
            }
            
            $("#logo-up-btn").on("click", function(){
                logoFile.click()
            })
            $("#bg-up-btn").on("click", function(){
                bgFile.click()
            })
            $(".cert-img").on("click", function(){
                certFile.click()
            })
            logoFile.on("change", function() {
                console.log('this:', this, this.files)
                onUploadFile(this.files, function(url) {
                    $("#logoImg").attr("src", url)
                    $("#logo-input").val(url)
                })
            })
            bgFile.on("change", function() {
                onUploadFile(this.files, function(url) {
                    $("#banner").css("backgroundImage", "url(" + url + ")")
                    bgInput.val(url)
                })
            })
            certFile.on("change", function() {
                onUploadFile(this.files, function(url) {
                    $(".cert-img").attr("src", url)
                    $("#cert-input").val(url)
                })
            })

            $(".input-container[type='text']").each(function(idx, item) {
                var me = $(item), maxLength = me.attr("maxlength")
                if (maxLength) {
                  onInputKeyUp(me, me.next(), maxLength)
                }
            })
            onInputKeyUp($("#IntroduceTextArea"), $("#IntroduceTips"), 500);

            $('#get-code-btn').click(function(){
                var me = $(this);
                var phone = $('#phone').val();

                if(!phone){
                    showToast('手机号不能为空');
                    return false;
                }
                var info={'phone': phone}

                if(me.text() !== '获取'){
                    return false
                }
                $.post("<?php echo U('Login/sand_sms');?>", info, function(datas){

                    if(datas.status == 1){
                        showToast(datas.info);
                        var n = 300;
                        me.html(n);
                        var t = setInterval(function(){
                            n--;
                            me.html(n);
                            if(n===0){
                                clearInterval(t);
                                me.html('获取')
                            }
                        },1000)
                    }else{
                        showToast(datas.info);
                    }
                })
            })
            $("#submit-btn").on("click", submit)
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