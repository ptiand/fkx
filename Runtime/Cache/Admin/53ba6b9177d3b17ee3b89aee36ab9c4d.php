<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>厦门房客行后台管理系统</title>
    <meta name="keywords"/>
    <meta name="description"/>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap.min.css">
    <!-- 可选的Bootstrap主题文件（一般不用引入）
    <link rel="stylesheet" href="/public/Static/bootstrap/css/bootstrap-theme.min.css">-->
    <link rel="stylesheet" href="/public/Admin/css/style.min.css?v=4.1.0">
    <link href="/public/Admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/public/Admin/css/animate.min.css" rel="stylesheet">
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script type="text/javascript" src='/public/Static/jquery-1.11.2.min.js'></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="/public/Static/bootstrap/js/bootstrap.min.js"></script>
    <script src="/public/Admin/js/jquery.metisMenu.js"></script>
    <script src="/public/Admin/js/jquery.slimscroll.min.js"></script>
    <script src="/public/Admin/js/pace.min.js"></script>
    <script src="/public/Admin/js/layer/layer.min.js"></script>
    <script src="/public/Admin/js/welcome.min.js"></script>
    <script src="/public/Admin/js/huge.min.js"></script>
    <script src="/public/Admin/js/contabs.min.js"></script>
    
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
<div class="pace  pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="width: 100%;">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>
<!-- 头部 -->
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="slimScrollDiv" style="position: relative; width: auto; height: 100%;"><div class="sidebar-collapse" style="width: auto; height: 100%;">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" src="<?php echo ((isset($user["pic"]) && ($user["pic"] !== ""))?($user["pic"]):'/public/Admin/img/profile_small.jpg'); ?>"></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold"><?php echo ($user["position"]); ?></strong></span>
                                <span class="text-muted text-xs block"><?php echo ($user["name"]); ?><b class="caret"></b></span>
                                </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="J_menuItem" href="form_avatar.html" data-index="0">修改头像</a>
                        </li>
                        <li><a class="J_menuItem" href="profile.html" data-index="1">个人资料</a>
                        </li>
                        <li><a class="J_menuItem" href="contacts.html" data-index="2">联系我们</a>
                        </li>
                        <li><a class="J_menuItem" href="mailbox.html" data-index="3">信箱</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="/Admin/Account/logout">安全退出</a>
                        </li>
                    </ul>
                </div>
                <div class="logo-element">果
                </div>
            </li>

            <li>
                <a href="">
                    <i class="fa fa-user"></i>
                    <span class="nav-label">会员管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Customer/customer');?>" data-index="0">会员信息</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Customer/report');?>" data-index="0">会员报备</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Customer/infos');?>" data-index="0">我要看房</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Requirement/index');?>" data-index="0">需求定制列表</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('UserCard/index');?>" data-index="0">领券列表</a>
                    </li>
                    <!--<li>-->
                        <!--<a class="J_menuItem" href="<?php echo U('Customer/grade');?>" data-index="1">会员等级</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a class="J_menuItem" href="<?php echo U('Customer/address');?>" data-index="5">收货地址管理</a>-->
                    <!--</li>-->
                    <!--<li>-->
                        <!--<a class="J_menuItem" href="<?php echo U('Customer/auto');?>" data-index="5">会员认证信息</a>-->
                    <!--</li>-->
                </ul>
            </li>
            
            <li>
                <a href="">
                    <i class="fa fa-user"></i>
                    <span class="nav-label">店铺管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('shops/shop_list');?>" data-index="0">店铺列表</a>
                    </li>
                    
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">楼盘管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Tag/index');?>" data-index="0">标签管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('NewHouse/index');?>" data-index="0">新房管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('RentalHouse/index');?>" data-index="0">出租房管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('OldHouse/index');?>" data-index="0">二手房管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('loupan/index');?>" data-index="0">楼盘信息(旧)</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('loupan/jiage');?>" data-index="0">价格区间(旧)</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('loupan/huxing');?>" data-index="0">户型管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('loupan/zxiu');?>" data-index="0">装修管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('loupan/types');?>" data-index="0">类型管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('City/citys');?>" data-index="0">城市管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Appeal/index');?>" data-index="0">投诉举报管理</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('loupan/ad');?>" data-index="0">轮播图片广告</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('MediaAd/index');?>" data-index="0">视频广告</a>
                    </li>
                </ul>

            </li>

            <li>
                <a href="#">
                    <i class="fa fa fa-bar-chart-o"></i>
                    <span class="nav-label">财务信息</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Withdrawal/index');?>" data-index="0">会员提现申请</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Customer/userAccount');?>" data-index="0">会员余额明细</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Reward/index');?>" data-index="0">奖励列表</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa fa-bar-chart-o"></i>
                    <span class="nav-label">文章管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Wen/index');?>" data-index="0">文章信息</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa fa-bar-chart-o"></i>
                    <span class="nav-label">微聊</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('Im/index');?>" data-index="0">微聊</a>
                    </li>
                </ul>
            </li>
             <li>
                <a href="#">
                    <i class="fa fa fa-bar-chart-o"></i>
                    <span class="nav-label">微信公众号</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a class="J_menuItem" href="<?php echo U('WeixinMenu/index');?>" data-index="0">微信菜单</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="<?php echo U('WeixinMessageReply/index');?>" data-index="0">消息回复</a>
                    </li>
                </ul>
            </li>
            <!--<li>-->
                <!--<a href="#">-->
                    <!--<i class="fa fa-edit"></i>-->
                    <!--<span class="nav-label">意见消息</span>-->
                    <!--<span class="fa arrow"></span>-->
                <!--</a>-->

                <!--<ul class="nav nav-second-level collapse">-->
                    <!--<li>-->
                        <!--<a class="J_menuItem" href="<?php echo U('Message/index');?>" data-index="0">投诉意见</a>-->
                    <!--</li>-->
                <!--</ul>-->
            <!--</li>-->

            <!--<li>-->
                <!--<a href="#"><i class="fa fa-flask"></i> <span class="nav-label">员工管理</span><span class="fa arrow"></span></a>-->
                <!--<ul class="nav nav-second-level collapse">-->
                    <!--<li>-->
                        <!--<a class="J_menuItem" href="<?php echo U('Employee/index');?>" data-index="56">员工信息</a>-->
                    <!--</li>-->
                    <!--&lt;!&ndash;<li>&ndash;&gt;-->
                        <!--&lt;!&ndash;<a class="J_menuItem" href="/admin/Employee/department" data-index="56">部门信息</a>&ndash;&gt;-->
                    <!--&lt;!&ndash;</li>&ndash;&gt;-->
                    <!--&lt;!&ndash;<li>&ndash;&gt;-->
                        <!--&lt;!&ndash;<a class="J_menuItem" href="/admin/Employee/userGroup" data-index="56">用户组管理</a>&ndash;&gt;-->
                    <!--&lt;!&ndash;</li>&ndash;&gt;-->
                    <!--&lt;!&ndash;<li>&ndash;&gt;-->
                        <!--&lt;!&ndash;<a class="J_menuItem" href="/admin/Employee/menu" data-index="56">菜单管理</a>&ndash;&gt;-->
                    <!--&lt;!&ndash;</li>&ndash;&gt;-->
                <!--</ul>-->

            <!--</li>-->
            <!--
            <li>
                <a href="#"><i class="fa fa-table"></i> <span class="nav-label">表格</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a class="J_menuItem" href="table_basic.html" data-index="78">基本表格</a>
                    </li>
                    <li><a class="J_menuItem" href="table_data_tables.html" data-index="79">DataTables</a>
                    </li>
                    <li><a class="J_menuItem" href="table_jqgrid.html" data-index="80">jqGrid</a>
                    </li>
                    <li><a class="J_menuItem" href="table_foo_table.html" data-index="81">Foo Tables</a>
                    </li>
                    <li><a class="J_menuItem" href="table_bootstrap.html" data-index="82">Bootstrap Table
                        <span class="label label-danger pull-right">推荐</span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-picture-o"></i> <span class="nav-label">相册</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a class="J_menuItem" href="basic_gallery.html" data-index="83">基本图库</a>
                    </li>
                    <li><a class="J_menuItem" href="carousel.html" data-index="84">图片切换</a>
                    </li>
                    <li><a class="J_menuItem" href="blueimp.html" data-index="85">Blueimp相册</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="J_menuItem" href="/admin/huge/" data-index="86"><i class="fa fa-magic"></i> <span class="nav-label">CSS动画</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-cutlery"></i> <span class="nav-label">工具 </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a class="J_menuItem" href="form_builder.html" data-index="87">表单构建器</a>
                    </li>
                </ul>
            </li>
-->
        </ul>
    </div><div class="slimScrollBar" style="width: 4px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 213.293943870015px; background: rgb(0, 0, 0);"></div><div class="slimScrollRail" style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.9; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div>
</nav>
<div id="page-wrapper" class="gray-bg dashbard-1">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                    <div class="form-group">
                        <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                    </div>
                </form>
            </div>
            <ul class="nav navbar-top-links navbar-right" style="display:none;">
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li class="m-t-xs">
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">

                                </a>
                                <div class="media-body">
                                    <small class="pull-right">46小时前</small>
                                    <strong>小四</strong> 这个在日本投降书上签字的军官，建国后一定是个不小的干部吧？
                                    <br>
                                    <small class="text-muted">3天前 2014.11.8</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">

                                </a>
                                <div class="media-body ">
                                    <small class="pull-right text-navy">25小时前</small>
                                    <strong>国民岳父</strong> 如何看待“男子不满自己爱犬被称为狗，刺伤路人”？——这人比犬还凶
                                    <br>
                                    <small class="text-muted">昨天</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a class="J_menuItem" href="mailbox.html" data-index="88">
                                    <i class="fa fa-envelope"></i> <strong> 查看所有消息</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts" style="display:none;">
                        <li>
                            <a href="mailbox.html">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息
                                    <span class="pull-right text-muted small">4分钟前</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="profile.html">
                                <div>
                                    <i class="fa fa-qq fa-fw"></i> 3条新回复
                                    <span class="pull-right text-muted small">12分钟钱</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a class="J_menuItem" href="notifications.html" data-index="89">
                                    <strong>查看所有 </strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="hidden-xs">
                    <a href="" class="J_menuItem" data-index="0"><i class="fa fa-cart-arrow-down"></i> 预留</a>
                </li>
                <li class="dropdown hidden-xs">
                    <a class="right-sidebar-toggle" aria-expanded="false">
                        <i class="fa fa-tasks"></i> 主题
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row content-tabs">
        <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
        </button>
        <nav class="page-tabs J_menuTabs">
            <div class="page-tabs-content" style="margin-left: 0px;">
                <a href="javascript:;" class="J_menuTab active" data-id="index_v1.html">首页</a>
            </div>
        </nav>
        <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
        </button>
        <div class="btn-group roll-nav roll-right">
            <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

            </button>
            <ul role="menu" class="dropdown-menu dropdown-menu-right">
                <li class="J_tabShowActive"><a>定位当前选项卡</a>
                </li>
                <li class="divider"></li>
                <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                </li>
                <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                </li>
            </ul>
        </div>
        <a href="/Admin/Account/logout" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
    </div>
    <div class="row J_mainContent" id="content-main">
        <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="" frameborder="0" data-id="" seamless="" style="display: inline;"></iframe>
    </div>
    <div class="footer">
        <div class="pull-right">© 2017-2018 <a href="" target="_blank">厦门房客行</a>
        </div>
    </div>
</div>
<!-- /头部 -->

<div id="main" class="clearfix">
    <!-- 菜单 -->
    
    <!-- /菜单-->

    <!-- 主体 -->
    <div id="content" class="bootstrap">
        


    </div>
    <!-- /主体 -->

</div>
<!-- 底部 -->

<!-- /底部 -->



</body>
</html>