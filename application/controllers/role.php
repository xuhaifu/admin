<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 角色管理
 *
 */
class Role extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        //角色管理只有超管和系统管理员可操作
        if(! ($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator())) {
            $this->helper->msg('无权限');
        }
        $this->load->model('admin_role_model');
    }

    /**
     * 超管可查看本渠道角色
     * 系统管理员可查看所有
     */
    public function index()
    {
        $this->load->service('common/pagination_service');
        $search = $this->input->get();
        $param = array();
        if(isset($search['id']) && $search['id'] != '') {
            $param['role_id'] = $search['id'];
        }
        if(isset($search['name']) && $search['name'] != '') {
            $param['role_name LIKE'] = '%'.$search['name'].'%';
        }
        if($this->rbac_service->isSuperAdministrator()) {
            if(isset($search['source_id']) && ! in_array($search['source_id'], array('', '-1'))) {
                $param['source_id'] = $search['source_id'];
            }
        } else {
            $param['source_id'] = $this->helper->user['source_id'];
        }
        $page_data = $this->pagination_service->handle($this->admin_role_model, $param, NULL, 'role');
        $this->load->view('admin_role/list', array(
                'pagination' => $page_data['pagination'],
                'data' => $page_data['data'],
                'search' => $search,
                'column' => $this->admin_role_model->attributes(),
        ));
    }

    /**
     * 创建角色
     */
    public function create()
    {
        $captcha = random_string();
        $this->session->set_userdata('captcha_create', $captcha);
        $this->load->view('admin_role/form', array(
                'action' => 'create',
                'captcha' => $captcha,
                'column' => $this->admin_role_model->attributes(),
        ));
    }

    /**
     * 编辑角色
     */
    public function edit()
    {
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $data = $this->admin_role_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('该记录不存在或已被删除');
        }
        //超级管理员只能编辑自己渠道来源的角色
        if($this->rbac_service->isAdministrator() && $data['source_id'] != $this->helper->user['source_id']) {
            $this->helper->msg('无权限');
        }
        foreach($data as $key => $val) {
            $data[$key] = htmlspecialchars($val);
        }
        $this->load->view('admin_role/form', array(
                'action' => 'edit',
                'data' => $data,
                'column' => $this->admin_role_model->attributes(),
        ));
    }

    /**
     * 新增、编辑校验页面
     */
    public function verify()
    {
        if(! ($post = $this->input->post())) {
            $this->helper->redirect(array('role'));
        }

        $action = $this->input->post('_action');
        $data = $post['data'];

        $attributes = $this->admin_role_model->attributes();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("data[role_name]", $attributes['role_name'], 'required');
        if($this->form_validation->run() == FALSE) {
            $this->helper->msg($this->form_validation->error_string('<span>', '</span><BR>'));
            return false;
        }

        if($action == 'create') {
            $captcha_verify = $this->session->userdata('captcha_create');
            if(empty($captcha_verify) || $post['_captcha'] != $captcha_verify) {
                $this->helper->msg('页面已过期');
            }
            if($this->rbac_service->isAdministrator()) {
                $data['source_id'] = $this->helper->user['source_id'];
            }
            $this->verify_create($data);
        } elseif ($action == 'edit') {
            $pk = $post['role_id'];
            if($this->rbac_service->isAdministrator()) {
                if(isset($data['source_id'])) {
                    unset($data['source_id']);
                }
            }
            $this->verify_edit($pk, $data);
        } else {
            $this->helper->msg('非法操作');
        }
    }

    /**
     * 处理新增请求
     */
    private function verify_create($data)
    {
        if($id = $this->admin_role_model->save($data)) {
            $this->session->unset_userdata('captcha_create');
            $this->helper->msg('提交成功', 'role', '列表页面');
        } else {
            $this->helper->msg('提交失败');
        }
    }

    /**
     * 处理编辑请求
     */
    private function verify_edit($pk, $data)
    {
        $data_source = $this->admin_role_model->findByPk($pk);
        //超级管理员只能编辑自己渠道的角色
        if($this->rbac_service->isAdministrator() && $data_source['source_id'] != $this->helper->user['source_id']) {
            $this->helper->msg('无权限');
        }
        if($this->admin_role_model->updateByPk($pk, $data)) {
            $this->helper->msg('更新成功', array('role/detail', array('id' => $pk)), '详细页面');
        } else {
            $this->helper->msg('更新失败');
        }
    }

    /**
     * 角色详细页面
     */
    public function detail()
    {
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $data = $this->admin_role_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('该记录不存在或已被删除');
        }
        //超级管理员只能查看自己渠道的角色
        if($this->rbac_service->isAdministrator() && $data['source_id'] != $this->helper->user['source_id']) {
            $this->helper->msg('无权限');
        }
        $this->load->view('admin_role/detail', array(
                'data' => $data,
                'column' => $this->admin_role_model->attributes(),
        ));
    }

    /**
     * 角色删除页面
     */
    public function del()
    {
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $data = $this->admin_role_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('参数错误');
        }
        //超级管理员只能删除自己渠道来源的角色
        if($this->rbac_service->isAdministrator() && $data['source_id'] != $this->helper->user['source_id']) {
            $this->helper->msg('无权限');
        }
        if($this->admin_role_model->deleteByPk($id)) {
            //删除给角色分配的权限
            $this->load->model('admin_role_resource_model');
            $this->admin_role_resource_model->deleteAll(array(
                'role_id' => $id,
            ));
            //删除属于该角色的用户关系
            $this->load->model('admin_role_user_model');
            $this->admin_role_user_model->deleteAll(array(
                'role_id' => $id,
            ));
            $this->helper->msg('删除成功', 'role', '列表页面');
        } else {
            $this->helper->msg('删除失败');
        }
    }

    /**
     * 给角色分配权限
     */
    public function dispatcher()
    {
        $rid = intval($this->input->get('rid'));
        $current_role = $this->admin_role_model->findByPk($rid);
        if(empty($current_role)) {
            $this->helper->msg('参数错误');
        }
        if($this->rbac_service->isAdministrator() && $current_role['source_id'] != $this->helper->user['source_id']) {
            $this->helper->msg('无权限');
        }
        $this->load->model('admin_role_resource_model');
        $resource_selected = $this->admin_role_resource_model->findAll(array('role_id' => $rid));
        $selected = array();
        if(is_array($resource_selected) && count($resource_selected) > 0) {
            foreach($resource_selected as $val) {
                $selected[] = $val['resource_id'];
            }
        }
        if(isset($_POST) && isset($_POST['role'])) {
            $role = $_POST['role'];
            $role_add = array_diff($role, $selected);
            $role_sub = array_diff($selected, $role);
            if(count($role_add) > 0) {
                $role_add_arr = array();
                foreach($role_add as $val) {
                    $role_add_arr[] = array(
                        'role_id' => $rid,
                        'resource_id' => $val,
                    );
                }
                $this->admin_role_resource_model->insertData($role_add_arr);
            }
            if(count($role_sub) > 0) {
                foreach($role_sub as $val) {
                    $this->admin_role_resource_model->deleteAll(array('role_id' => $rid, 'resource_id' => $val));
                }
            }
            $this->helper->msg('权限分配成功', 'role', '角色列表页面');
        }
        
        $resource_1 = $resource_2 = $resource_3 = array();
        $this->load->model('admin_source_resource_model');
        $admin_source_resource = $this->admin_source_resource_model->findAll(array('source_id' => $current_role['source_id']));
        if(empty($admin_source_resource)) {
            if($this->rbac_service->isSuperAdministrator()) {
                $this->helper->msg('请先分配最大权限', array('resource/assign', array('id' => $current_role['source_id'])), '分配最大权限页面');
            } else {
                $this->helper->msg('请联系系统管理员分配权限');
            }
        }
        $role_resource_list = array();
        foreach($admin_source_resource as $val) {
            $role_resource_list[$val['resource_id']] = $this->rbac_service->admin_resource[$val['resource_id']];
        }
        foreach($role_resource_list as $key => $val) {
            if($val['level'] == 1) {
                $resource_1[$key] = array(
                    'resource_id' => $val['resource_id'],
                    'title' => $val['title'],
                    'url' => create_url($val['url_directory'].'/'.$val['url_class'].'/'.$val['url_method'], json_decode($val['url_param'], true)),
                    'pid' => $val['pid'],
                );
            } elseif($val['level'] == 2) {
                $resource_2[$key] = array(
                    'resource_id' => $val['resource_id'],
                    'title' => $val['title'],
                    'pid' => $val['pid'],
                );
            } elseif($val['level'] >= 3) {
                if($val['permission_type'] != 1) {
                    continue;
                }
                $resource_3[$key] = array(
                    'resource_id' => $val['resource_id'],
                    'title' => $val['title'],
                    'url' => create_url($val['url_directory'].'/'.$val['url_class'].'/'.$val['url_method'], json_decode($val['url_param'], true)),
                    'pid' => $val['pid'],
                );
            }
        }
        foreach($resource_2 as $key_2 => $val_2) {
            foreach($resource_1 as $key_1 => $val_1) {
                if($val_2['pid'] == $val_1['resource_id']) {
                    $resource_1[$key_1]['sub'][] = $val_2['resource_id'];
                }
            }
        }
        foreach($resource_1 as $key => $val) {
            if(in_array($val['resource_id'], $selected)) {
                $resource_1[$key]['selected'] = true;
            }
            if(isset($val['sub']) && is_array($val['sub'])) {
                foreach($resource_3 as $key_3 => $val_3) {
                    foreach($val['sub'] as $val_sub) {
                        if($val_sub == $val_3['pid']) {
                            if(in_array($val_3['resource_id'], $selected)) {
                                $resource_3[$key_3]['selected'] = true;
                            }
                            $resource_1[$key]['sub_nav'][] = $resource_3[$key_3];
                            break;
                        }
                    }
                }
            }
        }
        $this->load->view('admin_role/dispatcher', array(
            'resource' => $resource_1,
            'rid' => $rid,
            'current_role' => $current_role,
        ));
    }
}