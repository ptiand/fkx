<?php


namespace Common\Service;

use Common\Model\WeixinUserModel;
use Think\Log;

class WeixinService
{
    private $appId = '';
    private $appKey = '';
    private static $ins = null;
    //消息回复
    private $public_token = '';//公众号token
    private $postxml;
    private $encrypt_type;//加密类型
    private $encodingAesKey;//消息加密密钥
    private $_funcflag = false;

    public static function getInstance()
    {
        if(!self::$ins)
        {
            self::$ins = new WeixinService();
        }
        return self::$ins;
    }
    public function __construct()
    {
        $this->appId = C('WEIXIN_APP_ID');
        $this->appKey = C('WEIXIN_APP_SECRET');
        $this->public_token = C('WEIXIN_PUBLIC_TOKEN');
        $this->encodingAesKey = C('WEIXIN_ENCODINGAESKEY');
        
    }
    public function isWeixinBrowser()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
        {
            return true;
        } else {
            return false;
        }
    }

    public function getAccessToken()
    {
        $data = D('WeixinAccessToken')->findValidAccessToken();
        if($data)
        {
            return $data['accessToken'];
        }
        else
        {
            return $this->requestAccessToken();
        }
    }
    public function requestAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appKey}";
        $result = curl_request($url);
        $result = json_decode($result);
        
        if($result->access_token)
        {
            $data = array(
                'accessToken' => $result->access_token,
                'expiresIn' => $result->expires_in
            );
            D('WeixinAccessToken')->saveWeixinAccessToken($data);
            return $result->access_token;
        }
        else
        {
            return '';
        }
    }
    public function buildAuth2Url($encodedRedirectUrl)
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId
            .'&redirect_uri='.$encodedRedirectUrl.'&response_type=code&scope=snsapi_base&state=#wechat_redirect';
        return $url;
    }

    public function getUserInfo($code)
    {
        $atUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appKey.'&code='.$code.'&grant_type=authorization_code';
        $result = curl_request($atUrl);
        $result = json_decode($result);
        if($result->errcode != 0)
        {
            return array();
        }

        //$accessToken = $result->access_token;
        $openId = $result->openid;
        $userInfo = array(
            'openId' => $openId
        );
        //打开开发者模式后，才能后去到用户信息
//        $uiUrl = "https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken}&openid={$openId}&lang=zh_CN";
//        $result = curl_request($uiUrl);
//        $result = json_decode($result);
//        echo '根据accesstoken获取用户信息';
//        var_dump($result);
//        if($result->openid)
//        {
//            $userInfo['nickname'] = $result->nickname;
//            $userInfo['sex'] = $result->sex;
//            $userInfo['province'] = $result->province;
//            $userInfo['city'] = $result->city;
//            $userInfo['country'] = $result->country;
//            $userInfo['headimgurl'] = $result->headimgurl;
//            $userInfo['unionid'] = $result->unionid;
//        }
        return $userInfo;
    }

    public function getUserBaseInfo($openId)
    {
        $accessToken = $this->getAccessToken();
        if(!$accessToken)
            return null;
        $userInfoUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openId.'&lang=zh_CN';
        $result = curl_request($userInfoUrl);
        $result = json_decode($result);
        if($result->errcode && $result->errcode != 0)
        {
            return null;
        }
        else
        {
            return $result;
        }
    }

    /**
     * 删除菜单(认证后的订阅号可用)
     * @return boolean
     */
    public function deleteMenu(){
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$access_token; 
        $result = curl_request($url);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                return false;
            }
            return true;
        }
        return false;
    }
    /**
     * 创建菜单(认证后的订阅号可用)
     * @param array $data 菜单数组数据
     * type可以选择为以下几种，其中5-8除了收到菜单事件以外，还会单独收到对应类型的信息。
     * 1、click：点击推事件
     * 2、view：跳转URL
     * 3、scancode_push：扫码推事件
     * 4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框
     * 5、pic_sysphoto：弹出系统拍照发图
     * 6、pic_photo_or_album：弹出拍照或者相册发图
     * 7、pic_weixin：弹出微信相册发图器
     * 8、location_select：弹出地理位置选择器
     */
    public function createMenu(){
        $data = D('WeixinMenu')->getWxmenuList();
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $result = curl_request($url, json_encode($data, JSON_UNESCAPED_UNICODE), 'POST');
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 微信消息的校验
     * @param bool $return 是否返回
     */
    public function valid($return=false)
    {
        $encryptStr="";
        $postStr = file_get_contents("php://input");
        Log::record('接收到微信消息xmlData:'.$postStr,Log::INFO);
        $array = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->encrypt_type = isset($_GET["encrypt_type"]) ? $_GET["encrypt_type"]: '';
        //$this->encrypt_type = 'aes';//todo test data
        if ($this->encrypt_type == 'aes') { //aes加密
            $encryptStr = $array['Encrypt'];
            $pc = new Prpcrypt($this->encodingAesKey);
            $array = $pc->decrypt($encryptStr,$this->appId);
            if (!isset($array[0]) || ($array[0] != 0)) {
                if (!$return) {
                    die('decrypt error!');
                } else {
                    return false;
                }
            }
            $this->postxml = $array[1];
            if (!$this->appId)
                $this->appId = $array[2];//为了没有appid的订阅号。
        } else {
            $this->postxml = $postStr;
        }
        return true;
    }

    /**
     * 微信消息校验签名
     */
    public function checkSignature($str='')
    {
        $signature = isset($_GET["signature"])?$_GET["signature"]:'';
        $signature = isset($_GET["msg_signature"])?$_GET["msg_signature"]:$signature; //如果存在加密验证则用加密验证段
        $timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:'';
        $nonce = isset($_GET["nonce"])?$_GET["nonce"]:'';

        $public_token = $this->public_token;
        $tmpArr = array($public_token, $timestamp, $nonce,$str);
      // echo "<pre>"; var_dump($str,$tmpArr);die;
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取微信服务器发来的信息
     */
    public function getReceiveData($value='')
    {
        $postStr = !empty($this->postxml)?$this->postxml:file_get_contents("php://input");
        /**
         * 如果想模拟微信通信的话，把上面的代码注释掉，再把下面的代码取消注释，修改openid
         */
//      $postStr = '<xml>
//          <ToUserName><![CDATA[gh_ace6d96065f2]]></ToUserName>
//          <FromUserName><![CDATA[oEQ4Qv2tiOgckgq3Dr3v_Hr-iexQ]]></FromUserName>
//          <CreateTime>1471921127</CreateTime>
//          <MsgType><![CDATA[event]]></MsgType>
//          <Event><![CDATA[CLICK]]></Event>
//          <EventKey><![CDATA[getAccount]]></EventKey>
//          <Encrypt><![CDATA[Q24H/jTuGjED6mGWKY8PlEWOHBFM3M8RnabQ9GLDaGfNBBl4XZ2e5MVyyRRw5M+PkkxSzzQMEwn35U8H2sWlrmMIczUiARHDFKY4EH0Hw7TEjFKOJGjlgLbhbYhkYHiFDai4+sD8dYPmtTXB1Y4wzf9NbtTZC0wG0n/nkcqWx/TjKgOwu4d2UhCalHiRw4e9RVvWcqzcHrbjgtPbvj4nzXFSRfCki53fbrq2TLL3GJAdxjcqh3gTnv0VNHKEYFJKofiW1kmYMsJJJC2ISVBCpRjIfTx8eb1T15bWB624SQuF1C+euQE0+8aiG8m5lt2xLvVmgXyWqnA7F3FN9aRGwQODIEli1bvOUKIaC5ANR9s9vVolVwi195C6hdt5E+9oeXLZUciEyRLslz9PB9n2h1SqWbsjdR3QxZs+xxhNGWKGqS61dSv5U2slZam0PEE0F9o8GydpLXiikviUVjWswA==]]></Encrypt>
//      </xml>';
        
        if (!empty($postStr)) {
            $this->_receive = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return $this->_receive;
    }
    //消息文本回复
    public function newsTextReply($text = '',$return=false)
    {
        $FuncFlag = $this->_funcflag ? 1 : 0;
        $msg = array(
            'ToUserName' => $this->_receive['FromUserName'],
            'FromUserName'=>$this->_receive['ToUserName'],
            'MsgType'=>'text',
            'Content'=>$this->_auto_text_filter($text),
            'CreateTime'=>time(),
            'FuncFlag'=>$FuncFlag
        );
        $xmldata=  $this->xml_encode($msg);
        if ($this->encrypt_type == 'aes') { //如果来源消息为加密方式
            $pc = new Prpcrypt($this->encodingAesKey);
            $array = $pc->encrypt($xmldata, $this->appId);
            $ret = $array[0];
            if ($ret != 0) {
                if ($return) {
                    return false;
                }else{
                    die('encrypt err!');
                }
                
            }
            $timestamp = time();
            $nonce = rand(77,999)*rand(605,888)*rand(11,99);
            $encrypt = $array[1];
            $tmpArr = array($this->public_token, $timestamp, $nonce,$encrypt);//比普通公众平台多了一个加密的密文
            sort($tmpArr, SORT_STRING);
            $signature = implode($tmpArr);
            $signature = sha1($signature);
            $xmldata = $this->generate($encrypt, $signature, $timestamp, $nonce);
        }
        if ($return)
            return $xmldata;
        else
            echo $xmldata;
    }

    /**
     * xml格式加密，仅请求为加密方式时再用
     */
    private function generate($encrypt, $signature, $timestamp, $nonce)
    {
        //格式化加密信息
        $format = "<xml>
            <Encrypt><![CDATA[%s]]></Encrypt>
            <MsgSignature><![CDATA[%s]]></MsgSignature>
            <TimeStamp>%s</TimeStamp>
            <Nonce><![CDATA[%s]]></Nonce>
            </xml>";
        return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
    }
    /**
     * 过滤文字回复\r\n换行符
     * @param string $text
     * @return string|mixed
     */
    private function _auto_text_filter($text) {
        //if (!$this->_text_filter) return $text;
        return str_replace("\r\n", "\n", $text);
    }
    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id   数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
    */
    public function xml_encode($data, $root='xml', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml   = "<{$root}{$attr}>";
        $xml   .= self::data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }
    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    public static function data_to_xml($data) {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  ( is_array($val) || is_object($val)) ? self::data_to_xml($val)  : self::xmlSafeStr($val);
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }

    public static function xmlSafeStr($str)
    {
        return '<![CDATA['.preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/",'',$str).']]>';
    }

    /************************ 微信消息处理 *******************************/
    public function subscribe($openId)
    {
        $weixinUser = D('WeixinUser')->findWeixinUserByOpenid($openId);
        if(!$weixinUser)
        {
            $weixinUser = array(
                'openId' => $openId
            );
        }
        $weixinUser['subscribe'] = WeixinUserModel::$Subscribe['Yes'];
        $userInfo = $this->getUserBaseInfo($openId);
        Log::record('$userInfo:'.json_encode($userInfo),Log::ERR);
        //Log::record('$userInfo:'.$userInfo.'-------'.($userInfo?'true':'false'),Log::ERR);

        if($userInfo)
        {

            Log::record('$userInfo:'.gettype($userInfo).'     '.$userInfo->openid,Log::ERR);
            $weixinUser["openid"] = $userInfo->openid;
            $weixinUser["nickname"] = $userInfo->nickname;
            $weixinUser["sex"] = $userInfo->sex;
            $weixinUser["language"] = $userInfo->language;
            $weixinUser["city"] = $userInfo->city;
            $weixinUser["province"] = $userInfo->province;
            $weixinUser["country"] = $userInfo->country;
            $weixinUser["headimgurl"] = $userInfo->headimgurl;
            $weixinUser["subscribe_time"] = $userInfo->subscribe_time;
            $weixinUser["unionid"] = $userInfo->unionid;
            $weixinUser["remark"] = $userInfo->remark;
            $weixinUser["groupid"] = $userInfo->groupid;
        }
        else
        {
            Log::record('$userInfo:'.$userInfo,Log::ERR);
        }
        return D('WeixinUser')->saveWeixinUser($weixinUser);
    }
    public function unsubscribe($openId)
    {
        $weixinUser = D('WeixinUser')->findWeixinUserByOpenid($openId);
        if($weixinUser)
        {
            $weixinUser['subscribe'] = WeixinUserModel::$Subscribe['No'];
            return D('WeixinUser')->saveWeixinUser($weixinUser);
        }
        else
        {
            return false;
        }
    }
    public function reportLocation($openId, $longitude, $latitude)
    {
        $weixinUser = D('WeixinUser')->findWeixinUserByOpenid($openId);
        if($weixinUser)
        {
            $weixinUser['longitude'] = $longitude;
            $weixinUser['latitude'] = $latitude;
            return D('WeixinUser')->saveWeixinUser($weixinUser);
        }
        else
        {
            $weixinUser = array(
                'openId' => $openId,
                'subscribe' => WeixinUserModel::$Subscribe['Yes']
            );
            $userInfo = $this->getUserBaseInfo($openId);
            Log::record('$userInfo:'.json_encode($userInfo),Log::ERR);
            if($userInfo)
            {
                Log::record('$userInfo:'.gettype($userInfo).'     '.$userInfo->openid,Log::ERR);
                $weixinUser["openid"] = $userInfo->openid;
                $weixinUser["nickname"] = $userInfo->nickname;
                $weixinUser["sex"] = $userInfo->sex;
                $weixinUser["language"] = $userInfo->language;
                $weixinUser["city"] = $userInfo->city;
                $weixinUser["province"] = $userInfo->province;
                $weixinUser["country"] = $userInfo->country;
                $weixinUser["headimgurl"] = $userInfo->headimgurl;
                $weixinUser["subscribe_time"] = $userInfo->subscribe_time;
                $weixinUser["unionid"] = $userInfo->unionid;
                $weixinUser["remark"] = $userInfo->remark;
                $weixinUser["groupid"] = $userInfo->groupid;
            }
            else
            {
                Log::record('$userInfo:'.$userInfo,Log::ERR);
            }
            $weixinUser['longitude'] = $longitude;
            $weixinUser['latitude'] = $latitude;
            return D('WeixinUser')->saveWeixinUser($weixinUser);
        }
    }
}


/**
 * PKCS7Encoder class
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class PKCS7Encoder
{
    public static $block_size = 32;

    /**
     * 对需要加密的明文进行填充补位
     * @param $text 需要进行填充补位操作的明文
     * @return 补齐明文字符串
     */
    function encode($text)
    {
        $block_size = PKCS7Encoder::$block_size;
        $text_length = strlen($text);
        //计算需要填充的位数
        $amount_to_pad = PKCS7Encoder::$block_size - ($text_length % PKCS7Encoder::$block_size);
        if ($amount_to_pad == 0) {
            $amount_to_pad = PKCS7Encoder::block_size;
        }
        //获得补位所用的字符
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     * @param decrypted 解密后的明文
     * @return 删除填充补位后的明文
     */
    function decode($text)
    {

        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > PKCS7Encoder::$block_size) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

}

/**
 * Prpcrypt class
 *
 * 提供接收和推送给公众平台消息的加解密接口.
 */
class Prpcrypt
{
    public $key;

    function __construct($k) {
        $this->key = base64_decode($k . "=");
    }

    /**
     * 兼容老版本php构造函数，不能在 __construct() 方法前边，否则报错
     */
    function Prpcrypt($k)
    {
        $this->key = base64_decode($k . "=");
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @return string 加密后的密文
     */
    public function encrypt($text, $appid)
    {

        try {
            //获得16位随机字符串，填充到明文之前
            $random = $this->getRandomStr();//"aaaabbbbccccdddd";
            $text = $random . pack("N", strlen($text)) . $text . $appid;
            // 网络字节序
            $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            //使用自定义的填充方式对明文进行补位填充
            $pkc_encoder = new PKCS7Encoder;
            $text = $pkc_encoder->encode($text);
            mcrypt_generic_init($module, $this->key, $iv);
            //加密
            $encrypted = mcrypt_generic($module, $text);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);

            //          print(base64_encode($encrypted));
            //使用BASE64对加密后的字符串进行编码
            return array(ErrorCode::$OK, base64_encode($encrypted));
        } catch (Exception $e) {
            //print $e;
            return array(ErrorCode::$EncryptAESError, null);
        }
    }

    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return string 解密得到的明文
     */
    public function decrypt($encrypted, $appid)
    {

        try {
            //使用BASE64对需要解密的字符串进行解码
            $ciphertext_dec = base64_decode($encrypted);
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            mcrypt_generic_init($module, $this->key, $iv);
            //解密
            $decrypted = mdecrypt_generic($module, $ciphertext_dec);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
        } catch (Exception $e) {
            return array(ErrorCode::$DecryptAESError, null);
        }


        try {
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            //去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < 16)
                return "";
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_appid = substr($content, $xml_len + 4);
            if (!$appid)
                $appid = $from_appid;
            //如果传入的appid是空的，则认为是订阅号，使用数据中提取出来的appid
        } catch (Exception $e) {
            //print $e;
            return array(ErrorCode::$IllegalBuffer, null);
        }
        if ($from_appid != $appid)
            return array(ErrorCode::$ValidateAppidError, null);
        //不注释上边两行，避免传入appid是错误的情况
        return array(0, $xml_content, $from_appid); //增加appid，为了解决后面加密回复消息的时候没有appid的订阅号会无法回复

    }


    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    function getRandomStr()
    {

        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

}

/**
 * error code
 * 仅用作类内部使用，不用于官方API接口的errCode码
 */
class ErrorCode
{
    public static $OK = 0;
    public static $ValidateSignatureError = 40001;
    public static $ParseXmlError = 40002;
    public static $ComputeSignatureError = 40003;
    public static $IllegalAesKey = 40004;
    public static $ValidateAppidError = 40005;
    public static $EncryptAESError = 40006;
    public static $DecryptAESError = 40007;
    public static $IllegalBuffer = 40008;
    public static $EncodeBase64Error = 40009;
    public static $DecodeBase64Error = 40010;
    public static $GenReturnXmlError = 40011;
    public static $errCode=array(
            '0' => '处理成功',
            '40001' => '校验签名失败',
            '40002' => '解析xml失败',
            '40003' => '计算签名失败',
            '40004' => '不合法的AESKey',
            '40005' => '校验AppID失败',
            '40006' => 'AES加密失败',
            '40007' => 'AES解密失败',
            '40008' => '公众平台发送的xml不合法',
            '40009' => 'Base64编码失败',
            '40010' => 'Base64解码失败',
            '40011' => '公众帐号生成回包xml失败'
    );
    public static function getErrText($err) {
        if (isset(self::$errCode[$err])) {
            return self::$errCode[$err];
        }else {
            return false;
        };
    }
}