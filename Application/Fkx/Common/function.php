<?php
//项目状态
function rostatus($type) {
    $list = array(
        '1' => '跟进中' ,
        '2' => '成交' ,
        '3' => '失效' ,
    );
    return $list[$type];
}
function get_string() {
    Vendor("Wifisoft.Strings");
    return new Strings;
}
function get_pwd($pwd,$rad){
    return md5(md5($pwd).$rad);
}

function get_user () {
    $user = session("user");
    if (empty($user)){
        return false;
    }
    return $user;
}
function send_sms($code,$phone){

    $params = array ();
    // *** 需用户填写部分 ***
    Vendor('sand_sms.SignatureHelper');

    // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $accessKeyId = "LTAIG43B4dtlqQhQ";
    $accessKeySecret = "cY9NZtoN8Cw3GqkQobViudNED3Gsnu";

    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = "{$phone}";

    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = "房客行";

    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = "SMS_116593362";

    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam'] = Array (
        "code" => $code,
    );

    // fixme 可选: 设置发送短信流水号
    $params['OutId'] = "12345";

    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
    $params['SmsUpExtendCode'] = "1234567";


    // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"]);
    }

    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $helper = new SignatureHelper();

    // 此处可能会抛出异常，注意catch
    $content = $helper->request(
        $accessKeyId,
        $accessKeySecret,
        "dysmsapi.aliyuncs.com",
        array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        ))
    );

   if($content->Code == 'OK'){
       return true;
    }else{
       return  false;
   }

}
