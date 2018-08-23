<?php

namespace Library\TencentIMSdk;
require_once dirname(__FILE__) . '/TimRestApi.php';
define('IMSdkAppId','1400093807');
define('IMAccountType', '27085');
define('IMPrivateKeyPath', dirname(__FILE__) . '/private_key');
$signature = '';
if(is_64bit()){
    if(PATH_SEPARATOR==':'){
        $signature = "signature/linux-signature64";
    }else{
        $signature = "signature\\windows-signature64.exe";
    }
}else{
    if(PATH_SEPARATOR==':')
    {
        $signature = "signature/linux-signature32";
    }else{
        $signature = "signature\\windows-signature32.exe";
    }
}
define('IMSignature',dirname(__FILE__) . '/'.$signature);

class IMSdk
{

    /**
     * @param $identifier
     * @return \TimRestAPI
     */
    public static function createAndInitRestAPI($identifier)
    {
        $api = createRestAPI();
        $api->init(IMSdkAppId, $identifier);
        return $api;
    }
    public static function createIdentifier($userId)
    {
        if(APP_DEBUG)
        {
            return md5($userId.'dev');
        }
        else
        {
            return md5($userId.'production');
        }
    }
    public static function createGroupName($username, $username2)
    {
        return $username . '、' .$username2. '的微聊';
    }

}