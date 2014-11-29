<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户管理模型
 * 
 */
class Admin_user_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'user_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'admin_user';
    }

    /**
     * 字段
     */
    public function attributes()
    {
        return array(
            'user_id' => '编号',
            'username' => '用户名',
            'password' => '密码',
            'salt' => '密钥',
            'nickname' => '昵称',
            'email' => '邮箱',
            'mobile' => '手机',
            'source_id' => '渠道',
            'department' => '部门',
            'stat' => '状态',
            'is_admin' => '管理员',
            'created_ts' => '创建时间',
            'last_logined_ts' => '上次登录时间',
        );
    }

    /**
     * 获取普通用户权限
     * 
     * @param int $user_id
     */
    public function getUserAuthority($user_id)
    {
        $sql = "SELECT arr.resource_id FROM `admin_role_user` aru
            LEFT JOIN `admin_role` ar ON aru.role_id = ar.role_id AND ar.stat = 1 
            LEFT JOIN `admin_role_resource` arr ON ar.role_id = arr.role_id
            WHERE aru.user_id = ? ";
        $user_resource = $this->query($sql, array($user_id));
        if(is_array($user_resource) && count($user_resource) > 0) {
            $user_resource = array_field_select($user_resource, 'resource_id', true);
        } else {
            $user_resource = array();
        }
        return $user_resource;
    }
}

/* End of file admin_user_model.php */
/* Location: ./application/models/admin_user_model.php */