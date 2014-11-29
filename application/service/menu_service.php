<?php
/**
 * 菜单管理
 *
 */
class Menu_service extends MY_Service
{
    /**
     * Rbac_service
     * 
     * @var object
     */
    private $rbac;

    /**
     * 菜单数组
     * @var array
     */
    public $menu;

    /**
     * 初始化菜单
     * @param Rbac_service $rbac
     */
    public function init(Rbac_service $rbac)
    {
        $this->rbac = $rbac;
        $main_menu = array_field_group($this->rbac->admin_resource, 'level');
        $main_menu = isset($main_menu[1]) ? $main_menu[1] : array();
        $menu = array();
        foreach($main_menu as $val) {
            $menu[] = array(
                'id' => $val['resource_id'],
                'title' => $val['title'],
                'url' => create_url($val['url_directory'].'/'.$val['url_class'].'/'.$val['url_method'], json_decode($val['url_param'], true)),
            );
        }
        $this->menu = $menu;
    }

    /**
     * 获取主菜单列表
     * @return multitype:
     */
    public function getMainMenuList()
    {
       return $this->menu;
    }

    /**
     * 获取二级菜单列表
     * @param string $directory
     * @param string $class
     * @param string $method
     * @return array
     */
    public function getMinorMenuList($directory, $class, $method)
    {
        foreach($this->rbac->admin_resource as $val) {
            if($val['url_directory'] == $directory
                && $val['url_class'] == $class
                && $val['url_method'] == $method
                && $val['level'] >= 3) {
                $select_third_id = $val['resource_id'];
                $select_parent_id = $val['pid'];
                break;
            }
        }
        if(! isset($select_third_id) || empty($select_third_id)) {
            return array();
        }
        $menu = $this->rbac->admin_resource[$select_parent_id];
        $select_second_id = $menu['resource_id'];
        $select_first_id = $menu['pid'];
        $this->load->vars('sys_select_first_id', $select_first_id);
        $menu_second = $menu_second_detail = $level_3 = array();
        foreach($this->rbac->admin_resource as $val) {
            if($val['pid'] == $select_first_id && $val['level'] == 2) {
                if($this->rbac->isSuperAdministrator() || $val['stat'] == 1) {
                    $menu_second[$val['resource_id']] = array(
                        'id' => $val['resource_id'],
                        'title' => $val['title'],
                        'sub' => array(),
                    );
                }
                continue;
            }
            if($val['level'] == 3) {
                $level_3[] = $val;
            }
            
        }
        $second_keys = array_keys($menu_second);
        foreach($level_3 as $val) {
            if(in_array($val['pid'], $second_keys)) {
                if($this->rbac->isSuperAdministrator() || $val['stat'] == 1) {
                    $menu_second_detail[] = $val;
                }
                continue;
            }
        }
        foreach($menu_second_detail as $val) {
            $menu_second[$val['pid']]['sub'][] = array(
                'id' => $val['resource_id'],
                'url_uid' => $val['url_directory'].'_'.$val['url_class'].'_'.$val['url_method'],
                'title' => $val['title'],
                'url' => create_url($val['url_directory'].'/'.$val['url_class'].'/'.$val['url_method'], json_decode($val['url_param'], true)),
            );
        }
        foreach($menu_second as $key => $val) {
            if(empty($val['sub'])) {
                unset($menu_second[$key]);
            }
        }
        return $menu_second;
    }
}