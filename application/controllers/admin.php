<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 管理员信息
 *
 */
class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_user_model');
    }

    /**
     * 管理员信息
     */
    public function index()
    {
        $id = NULL;
        if($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator()) {
            $id = intval($this->input->get('id'));
        }
        if($id < 1) {
            $id = $this->helper->user['user_id'];
        }
        $data = $this->admin_user_model->findByPk($id);
        if(isset($data['is_admin']) && $data['is_admin'] > $this->helper->user['is_admin']) {
            $this->helper->msg('无权限');
        }
        if(! $this->rbac_service->isSuperAdministrator()) {
            if($data['source_id'] != $this->helper->user['source_id']) {
                $this->helper->msg('无权限');
            }
        }
        if(empty($data)) {
            $this->helper->msg('该记录不存在或已被删除');
        }
        $this->load->view('admin_user/detail', array(
                'data' => $data,
                'column' => $this->admin_user_model->attributes(),
        ));
    }

    /**
     * 管理员列表
     */
    public function lists()
    {
        //管理员操作只有超管和系统管理员可操作
        if(! ($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator())) {
            $this->helper->msg('无权限');
        }
        $this->load->service('common/pagination_service');
        $search = $this->input->get();
        $param = array();
        if(isset($search['id']) && $search['id'] != '') {
            $param['user_id'] = $search['id'];
        }
        if($this->rbac_service->isAdministrator()) {
            $param['source_id'] = $this->helper->user['source_id'];
            $param['is_admin < '] = 2;
        } elseif ($this->rbac_service->isSuperAdministrator()) {
            if(isset($search['source']) && $search['source'] != '') {
                $param['source_id'] = $search['source'];
            }
        }
        if(isset($search['nickname']) && $search['nickname'] != '') {
            $param['nickname LIKE '] = '%'.$search['nickname'].'%';
        }
        $sortby = NULL;
        if(! $this->input->get('sortby')) {
            $sortby = 'is_admin DESC';
        }
        $page_data = $this->pagination_service->handle($this->admin_user_model, $param, $sortby, 'admin/lists');
        $this->load->view('admin_user/list', array(
                'pagination' => $page_data['pagination'],
                'data' => $page_data['data'],
                'search' => $search,
                'column' => $this->admin_user_model->attributes(),
        ));
    }

    /**
     * 创建管理员
     */
    public function create()
    {
        //管理员操作只有超管和系统管理员可操作
        if(! ($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator())) {
            $this->helper->msg('无权限');
        }
        $admin_list = lang_setting('admin_user_is_admin');
        if($this->rbac_service->isAdministrator()) {
            foreach($admin_list as $key => $val) {
                if($key > $this->helper->user['is_admin']) {
                    unset($admin_list[$key]);
                }
            }
        }
        $captcha = random_string();
        $this->session->set_userdata('captcha_create', $captcha);
        $this->load->view('admin_user/form', array(
                'action' => 'create',
                'captcha' => $captcha,
                'column' => $this->admin_user_model->attributes(),
                'admin_list' => $admin_list,
        ));
    }

    /**
     * 编辑管理员
     */
    public function edit()
    {
        $id = NULL;
        if($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator()) {
            $id = intval($this->input->get('id'));
        }
        if($id < 1) {
            $id = $this->helper->user['user_id'];
        }
        $data = $this->admin_user_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('该记录不存在或已被删除');
        }
        $admin_list = lang_setting('admin_user_is_admin');
        if(! $this->rbac_service->isSuperAdministrator()) {
            if($data['is_admin'] > $this->helper->user['is_admin'] || $data['source_id'] != $this->helper->user['source_id']) {
                $this->helper->msg('无权限');
            }
            foreach($admin_list as $key => $val) {
                if($key > $this->helper->user['is_admin']) {
                    unset($admin_list[$key]);
                }
            }
        }
        foreach($data as $key => $val) {
            $data[$key] = htmlspecialchars($val);
        }
        $this->load->view('admin_user/form', array(
                'action' => 'edit',
                'data' => $data,
                'column' => $this->admin_user_model->attributes(),
                'admin_list' => $admin_list,
        ));
    }

    /**
     * 新增、编辑校验页面
     */
    public function verify()
    {
        if(! ($post = $this->input->post())) {
            $this->helper->redirect(array('admin/lists'));
        }

        $action = $this->input->post('_action');
        $data = $post['data'];

        $attributes = $this->admin_user_model->attributes();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("data[nickname]", $attributes['nickname'], 'required');
        if($this->form_validation->run() == FALSE) {
            $this->helper->msg($this->form_validation->error_string('<span>', '</span><BR>'));
            return false;
        }

        if($action == 'create') {
            //管理员操作只有超管和系统管理员可操作
            if(! ($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator())) {
                $this->helper->msg('无权限');
            }
            if(! preg_match("/^[a-z][_a-z0-9]{2,49}$/i", $data['username'])) {
                $this->helper->msg('只允许数字、字母、下划线，字母打头，长度3-50');
            }
            if(strlen($data['password']) < 6 || strlen($data['password']) > 50) {
                $this->helper->msg('密码长度错误');
            }
            $captcha_verify = $this->session->userdata('captcha_create');
            if(empty($captcha_verify) || $post['_captcha'] != $captcha_verify) {
                $this->helper->msg('页面已过期');
            }
            $this->verify_create($data);
        } elseif ($action == 'edit') {
            if(empty($data['password'])) {
                unset($data['password']);
            } else {
                if(strlen($data['password']) < 6 || strlen($data['password']) > 50) {
                    $this->helper->msg('密码长度错误');
                }
                $salt = random_string();
                $data['password'] = generate_passwrod($data['password'], $salt);
                $data['salt'] = $salt;
            }
            if($this->rbac_service->isNormal()) {
                if(isset($data['is_admin'])) {
                    unset($data['is_admin']);
                }
                $pk = $this->helper->user['user_id'];
            } else {
                $pk = intval($post['user_id']);
            }
            if($pk <= 0) {
                $this->helper->msg('参数错误');
            }
            if(! $this->rbac_service->isSuperAdministrator()) {
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
        if($this->rbac_service->isAdministrator()) {
            $data['source_id'] = $this->helper->user['source_id'];
            if($data['is_admin'] > $this->helper->user['is_admin']) {
                $this->helper->msg('权限分配错误');
            }
        }
        $salt = random_string();
        $data['password'] = generate_passwrod($data['password'], $salt);
        $data['salt'] = $salt;
        if($id = $this->admin_user_model->save($data)) {
            $this->session->unset_userdata('captcha_create');
            $this->helper->msg('提交成功', array('admin', array('id' => $id)), '详细页面');
        } else {
            $this->helper->msg('提交失败');
        }
    }

    /**
     * 处理编辑请求
     */
    private function verify_edit($pk, $data)
    {
        $pk_data = $this->admin_user_model->findByPk($pk);
        if(empty($pk_data)) {
            $this->helper->msg('参数错误');
        }
        $msg = '更新成功';
        if($this->rbac_service->isAdministrator()) {
            if($pk_data['is_admin'] > $this->helper->user['is_admin'] || $pk_data['source_id'] != $this->helper->user['source_id']) {
                $this->helper->msg('无权限');
            }
            if($data['is_admin'] > $this->helper->user['is_admin']) {
                unset($data['is_admin']);
            }
        }
        if($this->rbac_service->isSuperAdministrator()) {
            if(isset($data['source_id']) && $data['source_id'] != $pk_data['source_id']) {
                $this->load->model('admin_role_user_model');
                $this->admin_role_user_model->deleteAll(array(
                    'user_id' => $pk,
                ));
                $msg = '更新成功，渠道已变更';
            }
        }
        if($this->admin_user_model->updateByPk($pk, $data)) {
            $this->helper->msg($msg, array('admin', array('id' => $pk)), '详细页面');
        } else {
            $this->helper->msg('更新失败');
        }
    }

    /**
     * 删除管理员
     */
    public function del()
    {
        //管理员操作只有超管和系统管理员可操作
        if(! ($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator())) {
            $this->helper->msg('无权限');
        }
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $data = $this->admin_user_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('参数错误');
        }
        if($this->rbac_service->isAdministrator()) {
            if($data['source_id'] != $this->helper->user['source_id'] || $this->helper->user['is_admin'] < $data['is_admin']) {
                $this->helper->msg('无权限');
            }
        }
        if($this->admin_user_model->deleteByPk($id)) {
            $this->helper->msg('删除成功', 'admin/lists', '列表页面');
        } else {
            $this->helper->msg('该记录不存在或已被删除');
        }
    }

    /**
     * 角色分配
     */
    public function dispatcher()
    {
        if(! ($this->rbac_service->isSuperAdministrator() | $this->rbac_service->isAdministrator())) {
            $this->helper->msg('无权限');
        }
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $user = $this->admin_user_model->findByPk($id);
        if(empty($user)) {
            $this->helper->msg('参数错误');
        }
        if($user['is_admin'] > 0) {
            $this->helper->msg('不用给该用户分配角色');
        }
        if($this->rbac_service->isAdministrator() && $this->helper->user['source_id'] != $user['source_id']) {
            $this->helper->msg('无权限');
        }
        $this->load->model('admin_role_user_model');
        $user_select_role = array();
        $user_role = $this->admin_role_user_model->findAll(array('user_id' => $user['user_id']));
        if(is_array($user_role) && count($user_role) > 0) {
            foreach($user_role as $val) {
                $user_select_role[] = $val['role_id'];
            }
        }
        if(isset($_POST) && isset($_POST['role'])) {
            $post_role = $this->input->post('role');
            $user_role_add = array_diff($post_role, $user_select_role);
            $user_role_sub = array_diff($user_select_role, $post_role);
            if(count($user_role_add) > 0) {
                $user_role_add_arr = array();
                foreach($user_role_add as $val) {
                    $user_role_add_arr[] = array(
                        'role_id' => $val,
                        'user_id' => $user['user_id'],
                    );
                }
                $this->admin_role_user_model->insertData($user_role_add_arr);
            }
            if(count($user_role_sub) > 0) {
                foreach($user_role_sub as $val) {
                    $this->admin_role_user_model->deleteAll(array('role_id' => $val, 'user_id' => $user['user_id']));
                }
            }
            $this->helper->msg('角色分配成功', array('admin/dispatcher', array('id' => $id)), '角色分配页面');
        
        }
        $this->load->model('admin_role_model');
        $search = $this->input->get();
        $sortby = NULL;
        if(isset($search['sortby']) && in_array($search['sortby'], array_keys($this->admin_role_model->attributes()))) {
            $sortby = $search['sortby'];
            $sort_type = isset($search['asc']) && $search['asc'] ? 'ASC' : 'DESC';
            $sortby .= ' ' . $sort_type;
        }
        $role_list = $this->admin_role_model->findAll(array(
            'source_id' => $user['source_id'],
            'stat' => 1,
        ), NULL, NULL, $sortby);
        if(empty($role_list)) {
            $this->helper->msg('还没有创建角色，请先创建角色', 'role/create', '创建角色');
        }

        foreach($role_list as $key => $val) {
            if(in_array($val['role_id'], $user_select_role)) {
                $role_list[$key]['selected'] = true;
            }
        }
        $this->load->view('admin_user/dispatcher', array(
                'data' => $role_list,
                'selected_user' => $user,
                'id' => $id,
                'search' => $search,
                'column' => $this->admin_role_model->attributes(),
        ));
    }
}