<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['per_page'] = 50;
$config['num_links'] = 4;
$config['use_page_numbers'] = TRUE;
$config['full_tag_open'] = '<div class="pagination"><ul>';
$config['full_tag_close'] = '</ul></div>';
$config['anchor_class'] = "";
$config['cur_tag_open'] = '<li class="active"><a>';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
$config['first_link'] = '首页';
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';
$config['last_link'] = '末页';
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';
$config['prev_link'] = '上一页';
$config['next_link'] = '下一页';
$config['page_query_string'] = true;
$config['query_string_segment'] = 'p';

/* End of file pagination.php */
/* Location: ./application/config/pagination.php */