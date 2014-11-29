<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 资源管理模型
 * 
 */
class Admin_resource_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'resource_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'admin_resource';
    }

    /**
     * 字段
     */
    public function attributes()
    {
        return array(
            'resource_id' => '编号',
            'title' => '名称',
            'url_directory' => '控制器目录',
            'url_class' => '控制器类名',
            'url_method' => '控制器方法',
            'url_param' => '参数',
            'pid' => '父级编号',
            'level' => '级别 ',
            'permission_type' => '是否控制',
            'sort' => '排序',
            'stat' => '状态',
            'created_ts' => '创建时间',
        );
    }
}

/* End of file admin_resource_model.php */
/* Location: ./application/models/admin_resource_model.php */