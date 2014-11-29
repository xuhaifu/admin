<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 角色管理模型
 * 
 */
class Admin_role_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'role_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'admin_role';
    }

    /**
     * 字段
     */
    public function attributes()
    {
        return array(
            'role_id' => '编号',
            'role_name' => '角色名称',
            'source_id' => '渠道',
            'remark' => '备注',
            'stat' => '状态',
            'created_ts' => '创建时间',
        );
    }
}

/* End of file admin_role_model.php */
/* Location: ./application/models/admin_role_model.php */