<?php
function curl_json_post($url, $data_str)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json;charset=utf-8'));
    $data = curl_exec($ch);
    if($errno = curl_errno($ch)) {
        throw new Exception(curl_error($ch), $errno);
    }
    curl_close($ch);
    return $data;
}

/**
 * 发送请求
 * @param unknown $url 请求的url
 * @return mixed
 */
function curl_get($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);//设置超时时间
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    if (preg_match("/^https:\/\//", $url)) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   //不对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);    //从证书中检查SSL加密算法是否存在
    }
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data  = curl_exec($curl);
    if($errno = curl_errno($curl)) {
        throw new Exception(curl_error($curl), $errno);
    }
    curl_close($curl);
    return $data;
}