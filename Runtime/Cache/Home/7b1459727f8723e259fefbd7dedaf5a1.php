<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1 user-scalable=no">
    <link rel="stylesheet" href="/public/Home/css/common.css">
    <link rel="stylesheet" href="/public/Home/css/register.css">
    <script src="/public/Home/js/jquery-1.7.1.min.js"></script>
    <title>登录房客行</title>
</head>
<body>
<?php if($isLogin): ?><script type="text/javascript">
        window.history.back();
    </script>
<?php else: ?>
    <div id="container" class="bg-img"></div>
    <div class="goback">
        <img src="/public/Home/images/nav_back_icon.png" alt="" id="nav-back-img" width="22" height="22"/>
    </div>
    <div class="login">
        <ul>
            <li><img src="/public/Home/images/phone.png" alt=""><input id="phone" type="text" placeholder="请输入手机号码"></li>
            <li><img src="/public/Home/images/lock.png" alt=""><input id="pwd" type="password" placeholder="请输入密码"></li>            </ul>
        <div class="btn sub">
            <a class="">登录</a>
        </div>
        <div class="text">
            <a href="/index.php/Login/regin_1">注册</a>
            <a href="/index.php/Login/code_login">验证码登录</a>
            <a href="/index.php/Login/forgetPwd">忘记密码</a>
        </div>
    </div>
    <script>
        $("#nav-back-img").click(function () {
            window.history.back();
        });

        $('.login>ul li').on('click','input',function(){
            $(this).val('')
        })
    </script>
    <script>
        $('.sub').click(function(){
            var phone = $('#phone').val();
            var pwd = $('#pwd').val();
            //判断手机号码是否已经注册过了
            if(!phone || !pwd){
                alert('手机号码或者密码不能为空');
                return false;
            }
            if(pwd.length < 6 ){
                alert('密码长度必须大于6位数');
                return false;
            }
            var info = {'pwd':pwd,phone:phone}
            $.post("<?php echo U('Login/login_in');?>",info,function(data){
                console.log(data)
                if(1 == data.status){
                    if ('<?php echo ($redirectUrl); ?>') {
                        history.replaceState('',null,window.history.back());
                        location.href = '<?php echo ($redirectUrl); ?>';
                    } else {
                        location.href = "<?php echo U('Index/index');?>";
                    }
                }else{
                    alert(data.info)
                }
            })
        });
    </script><?php endif; ?>
</body>
</html>