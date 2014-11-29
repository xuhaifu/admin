<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 渠道最大权限控制模型
 * 
 */
class Admin_source_resource_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'source_resource_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'admin_source_resource';
    }

    /**
     * 字段
     */
    public function attributes()
    {
        return array(
            'source_resource_id' => '编号',
            'source_id' => '渠道ID',
            'resource_id' => '资源ID',
        );
    }
}

/* End of file admin_source_resource_model.php */
/* Location: ./application/models/admin_source_resource_model.php */