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
<script src="<?php echo base_url('assets/lib/jquery/jquery-1.8.1.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/bootstrap/js/bootbox.min.js');?>"></script>
<!--[if lt IE 9]>
      <script src="<?php echo base_url('assets/js/html5.js');?>"></script>
<![endif]-->
</head>

<!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
<!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
<!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
<!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<body class="simple_body"><!--<![endif]-->
<div class="navbar">
    <div class="navbar-inner">
        <a class="brand" onclick="javascript:void(0);"><span class="second">后台管理系统</span></a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid" id="mainContent">
    <div class="dialog">
    <?php if(isset($message) && $message) : ?>
    <div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><?php echo $message;?></div>
    <?php endif;?>
        <div class="block">
            <div class="block-body" >
                <form name="login" method="post" action="">
                    <label>账号</label>
                    <input type="text" class="span12" name="username" value="" required="true" autofocus="true" autocomplete="off">
                    <label>密码</label>
                    <input type="password" class="span12" name="password" value="" required="true">
                    <label>验证码</label>
                    <input type="text" name="verify_code" class="span4" placeholder="输入验证码" autocomplete="off" required="required">
                    <img title="验证码" id="verify_code" src="<?php echo create_url('verify_code');?>"style="vertical-align:top; cursor:pointer; margin-top:-10px;">
                    <input type="hidden" name="login_type" class='login_type' value="admin">
                    <input type="submit" class="btn btn-primary pull-right" name="loginSubmit" value="登入">
                </form>
            </div>
            
        </div>
    </div>
    </div>
</div>
<?php $this->load->view('base/copyright');?>
<script language="JavaScript" type="text/javascript">
    function auto_footer(){
     var infoHeight = document.getElementById("mainContent").scrollHeight;
     var bottomHeight = document.getElementById("footer").scrollHeight;
     var allHeight = document.documentElement.clientHeight;
     
     var footer = document.getElementById("footer");
     if((infoHeight + bottomHeight) < allHeight - 20){
      footer.style.position = "absolute";
      footer.style.bottom = "0";
     }else{
      footer.style.position = "";
      footer.style.bottom = "";
     } 
     
     setTimeout(function(){auto_footer();},10);
    }
    auto_footer();
    $().ready(function(){
        $("#verify_code").click(function(){
            $(this).attr('src', '<?php echo create_url('verify_code');?>?' + Math.random());
        });
        $("#admin_btn").css("font-weight","bold").css("font-size",'20px');
        $("#admin_btn").click(function(){
            $("#admin_btn").css("font-weight","bold").css("font-size",'20px');
            $("#artisan_btn").css("font-weight","normal").css("font-size",'14px');
            $(".login_type").attr("value",'admin');
        });
        $("#artisan_btn").click(function(){
            $("#admin_btn").css("font-weight","normal").css("font-size",'14px');
            $("#artisan_btn").css("font-weight","bold").css("font-size",'20px');
            $(".login_type").attr("value",'artisan');
        });
    });
</script>
</body>
</html>