<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户角色关系管理模型
 * 
 */
class Admin_role_user_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'role_user_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'admin_role_user';
    }

    /**
     * 字段
     */
    public function attributes()
    {
        return array(
            'role_user_id' => '编号',
            'role_id' => '角色ID',
            'user_id' => '用户ID',
        );
    }
}

/* End of file admin_role_user_model.php */
/* Location: ./application/models/admin_role_user_model.php */