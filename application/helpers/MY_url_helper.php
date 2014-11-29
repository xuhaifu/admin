<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 创建URL，创建后的链接格式如 helper/code?check=1
 * 
 * @param string $route 路由地址
 * @param array $params 参数
 * @param string $ampersand 默认连接符
 * @return string
 */
function create_url($route, $params = array(), $ampersand = '&')
{
    $route = site_url($route);
    if(!empty($params)) {
        return $route.'?'.http_build_str($params, NULL, $ampersand);
    }
    return $route;
}

function create_site_url($site_arr)
{
    $route = '';$params = array();
    if(! is_array($site_arr)) {
        $route = $site_arr;
    } else {
        if(isset($site_arr[0]) && $site_arr[0]) {
            $route = $site_arr[0];
        }
        if(isset($site_arr[1]) && is_array($site_arr[1])) {
            $params = $site_arr[1];
        }
    }
    return create_url($route, $params);
}

/**
 * 获取当前页面地址，包含参数
 * @return string
 * @return 当前地址
 */
function get_current_url()
{
    $page_url = 'http://';
    if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $page_url = "https://";
    }
    if($_SERVER["SERVER_PORT"] != "80") {
        $page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $page_url;
}

if(! function_exists('http_build_str'))
{
    function http_build_str($query, $prefix='', $arg_separator='')
    {
        if (!is_array($query)) {
            return null;
        }
        if ($arg_separator == '') {
            $arg_separator = ini_get('arg_separator.output');
        }
        $args = array();
        foreach ($query as $key => $val) {
            $name = $prefix.$key;
            if (!is_numeric($name)) {
                if(is_array($val)){
                    http_build_str_inner($val, $name, $arg_separator, $args);
                }else{
                    $args[] = rawurlencode($name).'='.urlencode($val);
                }
            }
        }
        return implode($arg_separator, $args);
    }
}

if(! function_exists('http_build_str_inner'))
{
    function http_build_str_inner($query, $prefix, $arg_separator, &$args)
    {
        if (!is_array($query)) {
            return null;
        }
        foreach ($query as $key => $val) {
            $name = $prefix."[".$key."]";
            if (!is_numeric($name)) {
                if(is_array($val)){
                    http_build_str_inner($val, $name, $arg_separator, $args);
                }else{
                    $args[] = rawurlencode($name).'='.urlencode($val);
                }
            }
        }
    }
}