<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit(0);

ini_set('memory_limit', -1);

/**
 * Crontab 脚本公用控制
 */
class Crontab_service extends MY_Service
{
    public $crontab;

    public function __construct()
    {
        if(! $this->input->is_cli_request()) {
            exit('Access Deny.');
        }
        $this->load->config('crontab', true);
        $this->crontab = $this->config->item('crontab');
        $this->load->helper(array('shell', 'date'));
        if(is_linux()) {
            $this->isRunningCheck();
        }
    }

    /**
     * 记录日志
     * @param string $msg
     * @param string $file_prefix
     */
    public function log($msg, $file_prefix = NULL)
    {
        $this->benchmark->mark('cron_execution_time_end');
        $this->load->library('log');
        if($file_prefix) {
            $this->log->filename = $file_prefix;
        }
        if(is_array($msg)) {
            $msg = implode("||", $msg);
        }
        $execute_start_point = 'total_execution_time_start';
        if(isset($this->benchmark->marker['cron_execution_time_start'])) {
            $execute_start_point = 'cron_execution_time_start';
        }
        $msg .= ' time: '.$this->benchmark->elapsed_time($execute_start_point, 'cron_execution_time_end');
        $this->log->write($msg);
    }

    /**
     * 检测进程是否存在，若存在则不启动
     */
    public function isRunningCheck()
    {
        $script_uid = implode(" ", $_SERVER['argv']);
        if(($num = process_exists($script_uid)) >  1) {
            $this->log($script_uid.' is running('.$num.')');
            exit;
        }
    }
}

/* End of file crontab_service.php */
/* Location: ./application/service/crontab_service.php */