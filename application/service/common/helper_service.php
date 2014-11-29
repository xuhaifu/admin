<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 应用公用服务
 *
 */
class Helper_service extends MY_Service
{
    public $user;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->user = $this->session->userdata('sys_user');
        $this->load->helper(array('app', 'url', 'string', 'array', 'language', 'date'));
        $this->load->vars('sys_user', $this->user);
        $this->load->vars('sys_class', $this->router->fetch_class());
        $this->load->vars('sys_action', $this->router->fetch_method());
        $this->load->vars('sys_page_uid', $this->router->fetch_directory().'_'.$this->router->fetch_class().'_'.$this->router->fetch_method());
        if(! in_array($this->router->fetch_class(), array('login', 'verify_code'))) {
            $this->hasLogin();
        }
    }
    /**
     * 提示信息
     * 
     * @param string $msg 提示信息
     * @param string $url 跳转URL
     * @param string $url_title 跳转标题
     */
    public function msg($msg, $url = NULL, $url_title = NULL)
    {
        echo $this->load->view('base/msg', array(
            'message' => $msg,
            'url' => $url,
            'url_title' => $url_title,
        ), true);
        exit;
    }

    /**
     * 跳转
     * @param array $route 键0为路由名，之后为参数
     * @param number $http_response_code
     */
    public function redirect($route = '',  $http_response_code = 302)
    {
        if(is_array($route)) {
            $route = create_url($route[0], array_slice($route, 1));
        }
        header("Location: ".$route, TRUE, $http_response_code);
        exit;
    }

    /**
     * 判断用户是否登录
     * @return boolean
     */
    public function isLogin()
    {
        if(isset($this->user['is_login']) && $this->user['is_login'] === true) {
            return true;
        }
        return false;
    }

    /**
     * 登录检测，权限判断
     */
    public function hasLogin() {
        if(! $this->isLogin()) {
            $this->redirect(create_url('login', array('url' => get_current_url())));
        }
        $this->load->vars('sys_menu_main', array());
        $this->load->vars('sys_menu_minor', array());
        $this->load->service('rbac_service');
        if($this->rbac_service->init($this->user) === false) {
            $this->msg('无权限！');
        }
        $this->load->service('menu_service');
        $this->menu_service->init($this->rbac_service);
        $this->load->vars('sys_rbac_service', $this->rbac_service);
        $this->load->vars('sys_menu_main', $this->menu_service->getMainMenuList());
        $this->load->vars('sys_menu_minor', $this->menu_service->getMinorMenuList($this->router->fetch_directory(), $this->router->fetch_class(), $this->router->fetch_method()));
        $pages = $this->rbac_service->getDefaultPage($this->router->fetch_directory(), $this->router->fetch_class(), $this->router->fetch_method());
        if($pages === false) {
            if(isset($this->user['artisan_id']) && $this->user['artisan_id']){
                $this->redirect('artisan/order');
            }
            $this->msg('无权限！');
        }
        if($pages !== true && is_array($pages)) {
            $this->redirect($pages['url']);
            //$this->msg('无权限访问 '.$pages['select_title'], $pages['url'], $pages['title']);
        }
        if(! $this->rbac_service->authorityCheck($this->router->fetch_directory(), $this->router->fetch_class(), $this->router->fetch_method())) {
            $this->msg('无权限！');
        }
    }
}

/* End of file helper_service.php */
/* Location: ./application/service/helper_service.php */