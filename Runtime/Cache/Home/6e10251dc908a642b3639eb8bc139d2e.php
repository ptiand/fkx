<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <link rel="stylesheet" href="/public/Home/css/common.css">
    <link rel="stylesheet" href="/public/Home/css/register.css">
    <script src="/public/Home/js/jquery-1.7.1.min.js"></script>
    <title>验证码登录</title>
</head>
<body>
<div id="container" class="bg-img"></div>
<div class="goback">
    <img src="/public/Home/images/nav_back_icon.png" alt="" id="nav-back-img" width="22" height="22"/>
</div>
<div class="login">
    <ul>
        <li><img src="/public/Home/images/phone.png" alt=""><input id="phone" type="text" placeholder="请输入手机号码"></li>
        <li><input style="margin-left:24px;" id="code" type="text" placeholder="请输入验证码"></li>  <div id="sendCodeBtn" class="button"><p>获取验证码</p></div>
    </ul>
    <div class="btn sub"><a>登录</a></div>
    <div class="text">
        <a href="javascript:;"></a>
        <!-- <a href="/index.php/Login/regin_1">注册</a> -->
        <a href="/index.php/Login/index">密码登录</a>
        <a href="/index.php/Login/forgetPwd">忘记密码</a>
    </div>
    <div class="footer">
        <p>
            未注册过的手机号将自动创建账号，并同意《<a href="<?php echo U('Index/pingtai');?>">用户协议</a>》
        </p>
    </div>
</div>

<script>
    $("#nav-back-img").click(function () {
        window.history.back();
    });
    // $('.login>ul li').on('click','input',function(){
    //     $(this).val('')
    // });
    var timer = 0;
    $('#sendCodeBtn').click(function(){

        var phone = $('#phone').val();
        if(!phone){
            alert('手机号不能为空');
            return false;
        }
        if(timer>0)
        {
            return false;
        }
        timer = 1;
        var info={'phone':phone};
        $.post("<?php echo U('Login/sendLoginCode');?>", info, function(data)
        {
            console.log(data);
            if(data.status == 1)
            {
                //发送成功
                timer = 60;
                //alert(data.info);
                var t = setInterval(function(){
                    timer--;
                    if(timer===0) {
                        clearInterval(t);
                        $('#sendCodeBtn p').html('获取验证码')
                    } else {
                        $('#sendCodeBtn p').html(timer);
                    }
                },1000);
            }
            else
            {
                timer = 0;
                alert(data.info);
            }
        });
    });
    $('.sub').click(function(){
        var phone = $('#phone').val();
        var code = $('#code').val();
        if(!phone || !code){
            alert('手机号或者验证码不能为空');
            return false;
        }
        var info = {'code':code,phone:phone};

        $.ajax({
            'url' : "<?php echo U('Login/codeLogin');?>",
            type : 'post',
            dataType : 'json',
            data : {'code':code,'phone':phone},
            success : function(res) {
                if (res.status == 1) {
                    if ('<?php echo ($redirectUrl); ?>') {
                        history.replaceState('',null,window.history.back());
                        location.href = '<?php echo ($redirectUrl); ?>';
                    } else {
                        location.href = "<?php echo U('Index/index');?>";
                    }
                } else if (res.status == 2) {
                    window.location.href = '/index.php/Login/regin/?id=' + res.id + '&phone=' + res.phone;
                } else {
                    alert(res.info);
                }
            }
        })
    });
</script>
</body>
</html>