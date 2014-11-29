<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 验证码
 */
class Verify_code extends MY_Controller
{
    private $sys_captcha_key = 'sys_capthcha_code';

    public function index()
    {
        $this->load->library('captcha');
        $this->captcha->doimg();
        $this->session->set_userdata($this->sys_captcha_key, strtolower($this->captcha->getCode()));
        $this->captcha->outPut();
    }

    public function check()
    {
        $rtn = 'false';
        $captcha = $this->input->post('captcha');
        if(strtolower($captcha) == $this->session->userdata($this->sys_captcha_key)) {
            $rtn = 'true';
        }
        echo $rtn;
    }
}