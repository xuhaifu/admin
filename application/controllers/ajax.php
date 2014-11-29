<?php
class Ajax extends MY_Controller
{
    public function check_username()
    {
        $data = $this->input->get('data');
        if(! isset($data['username'])) {
            echo 'false';exit;
        }
        if(! preg_match("/^[a-z][_a-z0-9]{2,49}$/i", $data['username'])) {
            echo 'false';exit;
        }
        $this->load->model('admin_user_model');
        if($this->admin_user_model->findByAttributes(array('username' => $data['username']))) {
            echo 'false';exit;
        }
        echo 'true';exit;
    }
}