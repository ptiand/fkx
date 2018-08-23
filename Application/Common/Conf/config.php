<?php
return array(
    //'配置项'=>'配置值'
    /* 主题设置 */
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common'),
    'MODULE_ALLOW_LIST'   => array('Admin','Home', 'Fkx' ), //公司的配置
    //'MODULE_ALLOW_LIST'   => array('Park'),//产业园配置

    //'TMPL_EXCEPTION_FILE' => __ROOT__ . '500.html',
    'WEB_TITLE' => '福建果园网络科技股份有限公司',


    'SHOW_PAGE_TRACE' => false,
    //'DEFAULT_FILTER'        => 'htmlspecialchars,remove_xss', //

    /* URL配置 */
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
    'URL_CASE_INSENSITIVE'  =>  false,


    /* 数据库配置 */
    'DB_DEPLOY_TYPE' => 1,
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => 'a1.host.uiyee.com', // 服务器地址
    'DB_NAME'   => 'fkx_dev', // 数据库名
    'DB_USER'   => 'fkx', // 用户名
    'DB_PWD'    => 'Vd49Brfdv9TaYyUf',  // 密码
    'DB_PORT'   => '56033', // 端口
    //'DB_PREFIX' => 'park_', // 数据库表前缀

    'PAGE_SIZE' => 10 ,

    /* 图片上传相关配置 */
    'PICTURE_UPLOAD' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 1*1024*1024, //上传的文件大小限制 (0-不做限制)
        'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './uploads/picture/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //图片上传相关配置（文件上传类配置）

    'UPLOAD_LOCAL_CONFIG'=>array(),

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/public',
    ),
    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR',
    'LOG_FILE_SIZE' => 1024 * 1024 * 256 ,
    'WEIXIN_APP_ID' => 'wxe047b7d9f8d0c470' ,   //wxe047b7d9f8d0c470    wx5edee0430947c8b8
    'WEIXIN_APP_SECRET' => '3abde64f39640ab60de5f152d2a79d0f' , //3abde64f39640ab60de5f152d2a79d0f  5ff41c4f8b3437c44ae7f82e9dc456b6
    'WEIXIN_PUBLIC_TOKEN' => '1234567' ,//TODO 公众号token //1234567   AXQSTQySmj0tB9WY
    'WEIXIN_ENCODINGAESKEY' => 'yfxFQbtJgg4AlGVzTvUG281xEvERcVzMyLXVAVh3Rzm',//TODO 消息加密密钥    //yfxFQbtJgg4AlGVzTvUG281xEvERcVzMyLXVAVh3Rzm   RQeA6rHHedKYTBaDBDXWlqJR8ixFELBEmLtcBDe4AJd
    'WEIXIN_BILLS_MSGID'    =>  'LFjL3N7-kxojuVUN5QYF58ZMDv1UAtAsPYPYHCc2yHk',  //账单模板ID
    'WEIXIN_TENANT_ID'    =>  '1508011371', //登记模板ID
    
    'WEIXINPAY_CONFIG' => array( 
        'APPID' => 'wxe047b7d9f8d0c470', // 公众号APPID 微信支付APPID 
        'MCHID' => '1508011371', // 微信支付MCHID 商户收款账号 
        'KEY' => '18c823313f6e868b8507dfd27d38e81d', // 微信支付KEY 
        'APPSECRET' => '3abde64f39640ab60de5f152d2a79d0f', // 公众帐号secert (公众号支付专用) 
        'NOTIFY_URL' => 'http://fkx-dev.tests.uidev.work/index.php/WeixinPay/notify_wx', // 接收支付状态的连接 
        'TOKEN' => '1234567',//公众号设置的token值 
    ),




    /*Thinkphp 字段名强制转换为小写*/
    'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES'=>array(
        '/html\/(\w+)$/' => 'Demo/index?file=:1',
    ),
    'BAIDU_MAP_AK' => 'gjtMiwXXXMwuxFbBNCzxuCcyhyv1roc4',
    'AUTOLOAD_NAMESPACE' => array(
        'Library'     => ROOT_PATH.'/../Library',
    )
);