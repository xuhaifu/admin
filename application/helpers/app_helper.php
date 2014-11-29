<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 生成密码
 * 
 * @param string $password 密码
 * @param string $salt 密钥
 * @return string 加密后的密码
 */
function generate_passwrod($password, $salt = '')
{
    return md5($salt . md5($password) . $salt);
}

function get_image_url($image_path)
{
    static $current_url;
    if(empty($current_url)) {
        $CI = & get_instance();
        $image_url_list = $CI->config->item('img_url_list');
        $random = array_rand($image_url_list);
        $current_url = $image_url_list[$random];
    }
    if(! empty($image_path)) {
        return $current_url.$image_path;
    }
    return "";
}