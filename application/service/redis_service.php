<?php
class Redis_service extends MY_Service
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('redis_proxy', NULL, 'cache_handler');
    }
}