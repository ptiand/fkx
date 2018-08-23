<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible"content="IE=9; IE=8; IE=7; IE=EDGE">
    <title>房客行后台系统</title>
    <meta name="keywords"/>
    <meta name="description"/>
    <link rel="stylesheet" href="/public/Admin/css/login.css" />
    <script type="text/javascript" src='/public/Static/jquery-1.11.2.min.js'></script>
    <script type="text/javascript" src='/public/Static/jquery.show.js'></script>
    <script type="text/javascript" src="/public/Static/cookie.js"></script>
</head>
<body>
    <div class="loginBox">

        <div class="crmTitle center">
            <!--<img src="/public/Admin/img/logo.png">-->
        </div>
        <!--整体框架-->
        <div class="crm_main center">
            <!--幻灯片-->
            <div class="huandeng center">
                <div class="welcome-text">
                </div>
                <div class="con">
                    吃饱喝足后，美美的小睡一觉吧~~</div>
                <img src="/public/Admin/img/12-14.png">
            </div>
            <!--表单1-->
            <div class="select">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td width="24%" height="48" align="center">
                            用户名：
                        </td>
                        <td colspan="2">
                            <input name="txtusername" type="text" id="LoginName" class="l_text css3bg" title="账号" placeholder="请输入账号" style="border: none">
                        </td>
                    </tr>
                    <tr>
                        <td width="24%" height="48" align="center">
                            密&nbsp;&nbsp;&nbsp;码：
                        </td>
                        <td colspan="2">
                            <input name="txtpwd" type="password" id="Password" value="" class="l_text" title="请输入密码" placeholder="请输入密码" style="border: none">
                            <p>
                                <label id="msgpwd">
                                </label>
                            </p>
                        </td>
                    </tr>
                    <!--<tr>-->
                        <!--<td width="24%" height="48" align="center">-->
                            <!--验证码：-->
                        <!--</td>-->
                        <!--<td width="48%">-->
                            <!--<input name="txtyzm" type="text" id="VerifyCode" class="l_text_2" value="" title="" onfocus="if(this.value=='请输入验证码'){this.value='';}" style="border: none;">-->
                            <!--<p>-->
                                <!--<label id="msgcode">-->
                                <!--</label>-->
                            <!--</p>-->
                        <!--</td>-->
                        <!--<td width="28%">-->
                            <!--<div class="yz_img left">-->
                                <!--<img id="validImg" align="absMiddle" src="http://www.hybbms.com/index.php/admin/Account/vcode?Tue Dec 22 2015 15:43:29 GMT+0800 (中国标准时间)" alt="验证码"></a></div>-->
                        <!--</td>-->
                    <!--</tr>-->
                    <tr>
                        <td width="24%" rowspan="2">
                            &nbsp;
                        </td>
                        <td height="50" colspan="2" style="padding-top: 10px;">
                            <input type="image" name="ImageButton2" id="login_sub" onclick="doLogin(this)" title="请登录" onmouseover="this.src='/public/Admin/img/submit2.png'" onmouseout="this.src='/public/Admin/img/submit.png'" src="/public/Admin/img/submit.png">
                        </td>
                    </tr>
                    <tr>
                        <td height="16" colspan="2" class="l_lianjie">
                            <span class="right"><a href="" id="forget" title="忘记密码">忘记密码？</a> &nbsp;&nbsp;&nbsp; </span><span class="left" style="display: none;">
                                    <label>
                                        <span class="left" style="margin: 6px 5px">
                                            <input id="CheckBox1" type="checkbox" name="CheckBox1"></span> <span class="left">登录</span>
                                    </label>
                                </span>
                        </td>
                    </tr>
                    </tbody></table>
            </div>
        </div>
    </div>

<script type="text/javascript" language="javascript" src="/public/Static/jquery.md5.js"></script>
<script language="javascript" type="text/javascript">
    $(function () {
        refreshValid();
        //$('body').height($(window).height());
    });

    //刷新验证码
    function refreshValid() {
        $('#validImg').attr("src", "/verify.php?"+ new Date());
    }

    $("#validImg").click(function(){
        refreshValid();
    })

    $(function () {
        //鼠标放在文本框上面即放置焦点

        $("#LoginName").focus();

        $("input").keydown(function (e) {
            if (e.which == 13) {
                doLogin();
            }
        });
    });


    //登录界面
    function doLogin() {

        if (!$('#LoginName').val() || $('#LoginName').val()=='账号')
        {
            _popup("请填写用户名！");
            return;
        }
        var Password = $('#Password').val();
        if (!Password || Password == 'txtpwd') {
            _popup("请填写密码！");
            return;
        }
//        if (!$('#VerifyCode').val()) {
//            _popup("请填写验证码！");
//            return;
//        }

        var obj = $('#login_sub');

        $.post("Login", { LoginName: $('#LoginName').val(), Password: Password, VerifyCode: $('#VerifyCode').val(),type:$('#type').val()}, function (data) {
            if (!data.status) {
                _popup(data.info);
                ongoing($('#login_sub'), '登录', false);
                refreshValid();
                return;
            }
            else{
                $.cookie('LoginName',$('#LoginName').val(),{ expires: 865, path: '/' });
                parent.location.href = data.url;
            }

        }, 'json');

    }
</script>
</body>
</html>