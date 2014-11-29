<!DOCTYPE html>
<html lang="en">
<head>
<title>后台管理系统</title>
<meta charset="utf-8">
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo base_url('assets/lib/bootstrap/css/bootstrap.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/default/theme.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/lib/font-awesome/css/font-awesome.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/other.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/datepicker.css');?>" />
<script src="<?php echo base_url('assets/lib/jquery/jquery-1.8.1.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/bootstrap/js/bootbox.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery-ui.js');?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap-datepicker.js');?>"></script>
<!--[if lt IE 9]>
      <script src="<?php echo base_url('assets/js/html5.js');?>"></script>
<![endif]-->
</head>

<!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
<!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
<!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
<!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<body class=""><!--<![endif]-->
<div class="navbar" >
    <?php $this->load->view('base/navbar');?>
</div>

<?php $this->load->view('base/sidebar');?>