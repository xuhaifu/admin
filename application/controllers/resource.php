<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 资源管理
 * 资源只有系统管理员有操作权限
 */
class Resource extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        //只有超管有权限管理资源
        if(! $this->rbac_service->isSuperAdministrator() ) {
            $this->helper->msg('无权限');
        }
        $this->load->model('admin_resource_model');
    }

    /**
     * 资源列表
     */
    public function index()
    {
        $this->load->service('common/pagination_service');
        $search = $this->input->get();
        $param = array();
        if(isset($search['id']) && $search['id'] != '') {
            $param['resource_id'] = $search['id'];
        }
        if(isset($search['type']) && $search['type'] != '') {
            $param['permission_type'] = $search['type'];
        }
        if(isset($search['level']) && $search['level'] != '') {
            $param['level'] = $search['level'];
        }
        if(isset($search['class']) && $search['class'] != '') {
            $param['url_class'] = $search['class'];
        }
        if(isset($search['title']) && $search['title'] != '') {
            $param['title LIKE '] = '%'.$search['title'].'%';
        }
        $sortby = NULL;
        if(isset($search['sortby']) && in_array($search['sortby'], array_keys($this->admin_resource_model->attributes()))) {
            $sortby = $search['sortby'];
            $sort_type = isset($search['asc']) && $search['asc'] ? 'ASC' : 'DESC';
            $sortby .= ' ' . $sort_type;
        }
        $data = $this->admin_resource_model->findAll($param, NULL, NULL, $sortby);
        $this->load->view('admin_resource/list', array(
            'data' => $data,
            'search' => $search,
            'column' => $this->admin_resource_model->attributes(),
        ));
    }
    /**
     * 新增页面
     */
    public function create()
    {
        $captcha = random_string();
        $this->session->set_userdata('captcha_create', $captcha);
        $this->load->view('admin_resource/form', array(
            'action' => 'create',
            'captcha' => $captcha,
            'column' => $this->admin_resource_model->attributes(),
        ));
    }
    
    /**
     * 编辑页面
     */
    public function edit()
    {
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $data = $this->admin_resource_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('该记录不存在或已被删除');
        }
        foreach($data as $key => $val) {
            $data[$key] = htmlspecialchars($val);
        }
        $this->load->view('admin_resource/form', array(
            'action' => 'edit',
            'data' => $data,
            'column' => $this->admin_resource_model->attributes(),
        ));
    }
    
    /**
     * 新增、编辑校验页面
     */
    public function verify()
    {
        if(! ($post = $this->input->post())) {
            $this->helper->redirect(array('resource'));
        }
    
        $action = $this->input->post('_action');
        $data = $post['data'];
        $attributes = $this->admin_resource_model->attributes();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("data[title]", $attributes['title'], 'required');
        $this->form_validation->set_rules("data[pid]", $attributes['pid'], 'required');
        $this->form_validation->set_rules("data[level]", $attributes['level'], 'required');
        $this->form_validation->set_rules("data[permission_type]", $attributes['permission_type'], 'required');
        if($this->form_validation->run() == FALSE) {
            $this->helper->msg($this->form_validation->error_string('<span>', '</span><BR>'));
            return false;
        }
    
        if($action == 'create') {
            $captcha_verify = $this->session->userdata('captcha_create');
            if(empty($captcha_verify) || $post['_captcha'] != $captcha_verify) {
                $this->helper->msg('页面已过期');
            }
            $this->verify_create($data);
        } elseif ($action == 'edit') {
            $pk = $post['resource_id'];
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
        $data['stat'] = 1;
        if($id = $this->admin_resource_model->save($data)) {
            $this->session->unset_userdata('captcha_create');
            $this->load->service('cache_service');
            $this->cache_service->reloadAllResourceList();
            $this->helper->msg('提交成功', array('resource/detail', array('id' => $id)), '详细页面');
        } else {
            $this->helper->msg('提交失败');
        }
    }
    
    /**
     * 处理编辑请求
     */
    private function verify_edit($pk, $data)
    {
        if($this->admin_resource_model->updateByPk($pk, $data)) {
            $this->load->service('cache_service');
            $this->cache_service->reloadAllResourceList();
            $this->helper->msg('更新成功', 'resource', '资源列表页面');
        } else {
            $this->helper->msg('更新失败');
        }
    }
    
    /**
     * 详细页面
     */
    public function detail()
    {
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        $data = $this->admin_resource_model->findByPk($id);
        if(empty($data)) {
            $this->helper->msg('该记录不存在或已被删除');
        }
        $this->load->view('admin_resource/detail', array(
            'data' => $data,
            'column' => $this->admin_resource_model->attributes(),
        ));
    }
    
    /**
     * 删除页面
     */
    public function del()
    {
        $id = intval($this->input->get('id'));
        if($id < 1) {
            $this->helper->msg('参数错误');
        }
        if($this->admin_resource_model->deleteByPk($id)) {
            $this->load->model('admin_role_resource_model');
            $this->admin_role_resource_model->deleteAll(array(
                'resource_id' => $id,
            ));
            $this->load->model('admin_source_resource_model');
            $this->admin_source_resource_model->deleteAll(array(
                'resource_id' => $id,
            ));
            $this->load->service('cache_service');
            $this->cache_service->reloadAllResourceList();
            $this->helper->msg('删除成功', 'resource', '列表页面');
        } else {
            $this->helper->msg('该记录不存在或已被删除');
        }
    }

    /**
     * 给组分配最大权限
     */
    public function dispatcher()
    {
        $source = lang_setting('admin_source_id');
        $this->load->view('admin_resource/dispatcher', array(
            'source' => $source,
        ));
    }

    /**
     * 最大权限分配
     */
    public function assign()
    {
        $id = $this->input->get('id');
        if($id == '') {
            $this->helper->msg('参数错误');
        }
        $id = intval($id);
        if(! in_array($id, array_keys(lang_setting('admin_source_id')))) {
            $this->helper->msg('请选择渠道', 'resource/dispatcher', '权限分配');
        }
        $this->load->model('admin_source_resource_model');
        $select = $this->admin_source_resource_model->findAll(array(
            'source_id' => $id,
        ));
        $select_resource = array();
        if(is_array($select) && count($select) > 0) {
            foreach($select as $val) {
                $select_resource[] = $val['resource_id'];
            }
        }
        if(isset($_POST) && isset($_POST['resource'])) {
            $post_resource = $this->input->post('resource');
            $admin_resource_add = array_diff($post_resource, $select_resource);
            $admin_resource_sub = array_diff($select_resource, $post_resource);
            if(count($admin_resource_add) > 0) {
                $admin_resource_add_arr = array();
                foreach($admin_resource_add as $val) {
                    $admin_resource_add_arr[] = array(
                        'source_id' => $id,
                        'resource_id' => $val,
                    );
                }
                $this->admin_source_resource_model->insertData($admin_resource_add_arr);
            }
            if(count($admin_resource_sub) > 0) {
                foreach($admin_resource_sub as $val) {
                    $this->admin_source_resource_model->deleteAll(array('source_id' => $id, 'resource_id' => $val));
                }
            }
            //删除当前最大权限缓存
            $this->load->service('cache_service');
            $this->cache_service->reloadAdminSourceResource($id);
            $this->helper->msg('权限分配成功', array('resource/assign', array('id' => $id)), '权限分配页面');
        }
        $this->load->model('admin_resource_model');
        $search = $this->input->get();
        $sortby = NULL;
        if(isset($search['sortby']) && in_array($search['sortby'], array_keys($this->admin_resource_model->attributes()))) {
            $sortby = $search['sortby'];
            $sort_type = isset($search['asc']) && $search['asc'] ? 'ASC' : 'DESC';
            $sortby .= ' ' . $sort_type;
        }
        $resources = $this->admin_resource_model->findAll(array(), NULL, NULL, $sortby);
        foreach($resources as $key => $val) {
            if(in_array($val['resource_id'], $select_resource)) {
                $resources[$key]['selected'] = true;
            }
        }
        $this->load->view('admin_resource/assign', array(
            'source_id' => $id,
            'data' => $resources,
            'search' => $search,
            'column' => $this->admin_resource_model->attributes(),
        ));
    }
}