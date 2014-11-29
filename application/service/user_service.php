<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 会员服务
 *
 */
class User_service extends MY_Service
{
    /**
     * 检测用户是否登录成功
     * 
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $after_handle 登录成功后是否更新登录时间
     * @return boolean|array
     */
    public function login($username, $password, $after_handle = true)
    {
        $this->load->model('admin_user_model');
        $admin = $this->admin_user_model->findByAttributes(array('username' => $username));
        if(empty($admin) || $admin['password'] != $this->buildPassword($password, $admin['salt'])) {
            return false;
        }
        if($after_handle) {
            $this->admin_user_model->updateByPk($admin['user_id'], array(
                'last_logined_ts' => date("Y-m-d H:i:s")
            ));
        }
        return $admin;
    }

    /**
     * 生成加密之后的密码
     * 
     * @param string $password 密码
     * @param string $salt 加密KEY
     * @return string
     */
    public function buildPassword($password, $salt = '')
    {
        return md5($salt . md5($password) . $salt);
    }
}

/* End of file user_service.php */
/* Location: ./application/service/user_service.php */