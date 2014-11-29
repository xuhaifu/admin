<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{
    public function __construct($script_type = NULL) 
    {
        parent::__construct();
        if($script_type == 'crontab') {
            $this->load->service('common/crontab_service', NULL, 'helper');
        } else {
            $this->load->service('common/helper_service', NULL, 'helper');
        }
        register_shutdown_function(array($this, 'shutdown'));
    }

    public function shutdown()
    {
        foreach(get_object_vars($this) as $key => $val) {
            if(substr($key, 0, 3) == 'db_' && is_object($this->{$key}) && method_exists($this->{$key}, 'close')) {
                $this->{$key}->close();
            }
            if(substr($key, 0, 5) == 'conn_'  && is_resource($this->{$key})) {
                $this->db->_close($val);
                unset($this->{$key});
            }
        }
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */