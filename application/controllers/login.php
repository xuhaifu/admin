<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 管理员登录
 *
 */
class Login extends MY_Controller
{
    /**
     * 管理员登录
     */
    public function index()
    {
        if($this->helper->isLogin()) {
            $this->helper->redirect(array(''));
        }
        if($this->input->post()) {
            if($this->session->userdata('sys_capthcha_code') != strtolower($this->input->post('verify_code', true))) {
                $this->error('验证码错误');
            }
            $username = $this->input->post('username', true);
            $password = $this->input->post('password');
            $this->load->service('user_service');
            if($this->input->post('login_type') == 'admin') {
                $admin = $this->user_service->login($username, $password);
                if($admin === false) {
                    $this->error('用户名或密码错误');
                }
                $this->session->unset_userdata('sys_capthcha_code');
                if($admin['stat'] != 1) {
                    $this->error('您的账户已被限制登录，请联系管理员');
                }
                $this->session->set_userdata('sys_user', array_merge($admin, array('is_login' => true)));
                $this->helper->redirect(array(''));
            }
        }
        $this->load->view('base/login');
    }

    /**
     * 管理员退出
     */
    public function logout()
    {
        $this->session->sess_destroy();
        $this->helper->redirect(array('login'));
    }

    /**
     * 登录页面错误提示
     * @param string $msg
     */
    private function error($msg)
    {
        echo $this->load->view('base/login', array(
            'message' => $msg,
            'post' => $this->input->post()
        ), true);
        exit;
    }
}