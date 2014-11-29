<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 获取进程执行的个数
 * 
 * @param string $process_name
 * @return boolean|number
 */
function process_exists($process_name)
{
    if(! function_exists('exec')) {
        return false;
    }
    $cmd = 'ps -ef | grep "'.$process_name.'" | grep -v "grep" | wc -l';
    $cnt = exec($cmd);

    return $cnt;
}