<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 角色资源关系管理模型
 * 
 */
class Admin_role_resource_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'role_resource_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'admin_role_resource';
    }

    /**
     * 字段
     */
    public function attributes()
    {
        return array(
            'role_resource_id' => '编号',
            'role_id' => '角色ID',
            'resource_id' => '资源ID',
        );
    }
}

/* End of file admin_role_resource_model.php */
/* Location: ./application/models/admin_role_resource_model.php */