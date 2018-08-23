<?php
function renderMoney($value, $decimals=2)
{
    return number_format($value,$decimals);
}
function renderNumber($value, $decimals=2)
{
    return number_format($value, $decimals);
}

/**
 * 连续创建目录
 *
 * @param string $dir 目录字符串
 * @param int $mode 权限数字
 * @return boolean
 */
function makeDir($dir, $mode = 0777) {
    if (!$dir) return false;
    if(!file_exists($dir)) {
        return mkdir($dir,$mode,true);
    } else {
        return true;
    }

}


/**
 * 使用curl获取远程数据
 * @param  string $url url连接
 * @return string      获取到的数据
 */
function curl_get_contents($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //设置访问的url地址
    // curl_setopt($ch,CURLOPT_HEADER,1);               //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);        //设置 referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r=curl_exec($ch);
    curl_close($ch);
    $res = json_decode($r,true);
    return $res;
}

function curl_request($url, $params = array(), $method = 'GET', $timeout = 15, $extheaders = null, $extOptions = null){
    if(!function_exists('curl_init')) exit('需要开启curl');

    $method = strtoupper($method);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    switch($method)
    {
        case 'POST':
            curl_setopt($curl, CURLOPT_POST, TRUE);
            if(!empty($params))
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            }
            break;

        case 'DELETE':
        case 'GET':
            if($method == 'DELETE')
            {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }
            if(!empty($params))
            {
                $url = $url . (strpos($url, '?') ? '&' : '?') . (is_array($params) ? http_build_query($params) : $params);
            }
            break;
    }
    curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
    curl_setopt($curl, CURLOPT_URL, $url);
    if(!empty($extheaders))
    {
        curl_setopt($curl, CURLOPT_HTTPHEADER, (array)$extheaders);
    }
    if(!empty($extOptions)) {
        foreach($extOptions as $key => $value) curl_setopt($curl, $key, $value);
    }
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}