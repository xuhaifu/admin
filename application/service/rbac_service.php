<?php
/**
 * 权限管理
 *
 */
class Rbac_service extends MY_Service
{

    /**
     * 用户信息
     * @var array
     */
    private $admin;

    /**
     * 用户权限列表
     * @var array
     */
    public $admin_resource;

    /**
     * 初始化操作，获取用户权限
     */
    public function init($admin_user)
    {
        $this->admin = $admin_user;
        $admin_user_resource = $this->loadUserResources($this->admin['user_id'], $this->admin['source_id']);
        if($admin_user_resource == false || empty($admin_user_resource)) {
            return false;
        }
        $this->admin_resource = $admin_user_resource;
        return true;
    }

    /**
     * 权限检测，系统管理员不检测
     *
     * @param string $directory
     * @param string $class_name
     * @param string $method_name
     * @return boolean
     */
    public function authorityCheck($directory, $class_name, $method_name)
    {
        if($this->isSuperAdministrator()) {
            return true;
        }
        foreach($this->admin_resource as $val) {
            if($val['url_directory'] == $directory
            && $val['url_class'] == $class_name
            && $val['url_method'] == $method_name
            && $val['level'] >= 3) {
                return true;
            }
        }
        return false;
    }

    /**
     * 普通管理员
     * @return boolean
     */
    public function isNormal()
    {
        if(isset($this->admin['is_admin']) && $this->admin['is_admin'] == 0) {
            return true;
        }
        return false;
    }

    /**
     * 管理员
     * @return boolean
     */
    public function isAdministrator()
    {
        if(isset($this->admin['is_admin']) && $this->admin['is_admin'] == 1) {
            return true;
        }
        return false;
    }

    /**
     * 超级管理员
     * @return boolean
     */
    public function isSuperAdministrator()
    {
        if(isset($this->admin['is_admin']) && $this->admin['is_admin'] == 2) {
            return true;
        }
        return false;
    }

    /**
     * 是否有铁人数据权限
     * @return boolean
     */
    public function isTeironAdministrator()
    {
        return $this->isSuperAdministrator();
    }

    /**
     * 加载资源并存放在SESSION中
     * @param int $user_id
     * @param int $source_id
     * @return array
     */
    public function loadUserResources($user_id, $source_id)
    {
        $this->load->service('cache_service');
        if($this->cache_service->isAllResourceCacheExists() && ($admin_user_resource = $this->session->userdata('sys_resouces'))) {
            return $admin_user_resource;
        }
        //取得所有排序好的所有系统资源
        $resources = $this->cache_service->getAllResourceList();
        //系统管理员直接返回所有资源
        if($this->isSuperAdministrator()) {
            $this->session->set_userdata('sys_resouces', $resources);
            return $resources;
        }
        //取得该渠道最大权限
        $admin_max_resource = $this->cache_service->getAdminSourceResource($source_id);
        if(! is_array($admin_max_resource) || empty($admin_max_resource)) {
            return false;
        }
        $admin_normal_resource = array();
        //管理员（1）不需要分配角色，直接取当前渠道对应最大权限
        if($this->isAdministrator()) {
            $admin_normal_resource = $admin_max_resource;
        } elseif ($this->isNormal()) {
            //普通用户取和当前渠道最大权限的交集
            $this->load->model('admin_user_model');
            $admin_normal_resource = $this->admin_user_model->getUserAuthority($user_id);
            $admin_normal_resource = array_intersect($admin_max_resource, $admin_normal_resource);
        }
        $admin_user_resource = array();
        foreach($resources as $key => $val) {
            if(! in_array($val['resource_id'], $admin_max_resource) || $val['stat'] != 1) {
                continue;
            }
            if(in_array($val['resource_id'], $admin_normal_resource) || $val['permission_type'] == 0) {
                $admin_user_resource[$val['resource_id']] = $val;
            }
        }
        if(empty($admin_user_resource)) {
            return false;
        }
        $this->session->set_userdata('sys_resouces', $admin_user_resource);
        return $admin_user_resource;
    }

    /**
     * 判断菜单是否有权限，无权限则推荐下一菜单
     */
    public function getDefaultPage($directory, $class_name, $method_name)
    {
        if($this->isSuperAdministrator()) {
            return true;
        }
        $this->load->service('cache_service');
        $resources = $this->cache_service->getAllResourceList();
        $select_title = NULL;
        foreach($resources as $val) {
            if($val['url_directory'] == $directory
                && $val['url_class'] == $class_name
                && $val['url_method'] == $method_name
                && $val['level'] >= 3) {
                $select_second_id = $val['pid'];
                $select_title = $val['title'];
                break;
            }
        }
        if(empty($select_second_id)) {
            return false;
        }
        $sibling = array();
        foreach($this->admin_resource as $val) {
            if($val['pid'] == $select_second_id && $val['level'] == 3) {
                $sibling[] = $val;
            }
            if($val['url_directory'] == $directory
                && $val['url_class'] == $class_name
                && $val['url_method'] == $method_name
                && $val['level'] >= 3) {
                return true;
            }
        }
        //没有找到当前二级菜单其他有权限菜单，则查找兄弟二级菜单
        if(empty($sibling) || ! isset($sibling[0])) {
            $first_id = $this->admin_resource[$select_second_id]['pid'];
            $second_list = array();
            foreach($this->admin_resource as $val) {
                if($first_id == $val['pid'] && $val['level'] == 2 && $select_second_id != $val['resource_id']) {
                    $second_list[] = $val['resource_id'];
                }
            }
            if(count($second_list) > 0) {
                $is_show_rec = false;
                foreach($this->admin_resource as $val) {
                    if(in_array($val['pid'], $second_list) && $val['level'] == 3) {
                        $is_show_rec = true;
                        $sibling[] = $val;
                        break;
                    }
                }
                if(!$is_show_rec) {
                    return false;
                }
            } else {
                return false;
            }
        }
        
        $second_title = $resources[$select_second_id]['title'];
        $first_title = $resources[$resources[$select_second_id]['pid']]['title'];
        return array(
            'url' => array($sibling[0]['url_directory'].'/'.$sibling[0]['url_class'].'/'.$sibling[0]['url_method'], json_decode($sibling[0]['url_param'], true)),
            'title' => $sibling[0]['title'],
            'select_title' => $first_title.'>'.$second_title.'>'.$select_title,
        );
    }
}