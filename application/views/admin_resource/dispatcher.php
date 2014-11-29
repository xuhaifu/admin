<?php $this->load->view('base/header');?>

<script src="<?php echo base_url('assets/lib/validate/jquery.validate.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/validate/messages_zh.js');?>"></script>
<script type="text/javascript">
$().ready(function(){
    $("#form").validate({
        errorPlacement: function(error, element) {
                if(element.attr("name") == 'id') {
            error.insertAfter("#source_msg");
            } else {
                error.insertAfter(element);
            }
        },
        rules:{
            'id' : {'required' : true}
        },
        messages: {
            'id' : {
                'required' : '请选择渠道'
            },
        }
    });
});
</script>
<div class="content">
    <div class="header"><h1 class="page-title">资源管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">资源管理 <span class="divider">/</span></li>
        <li class="active">最大权限分配</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">

        <div class="block">
            <a href="#detail" class="block-heading" data-toggle="collapse">权限分配</a>
            <form id="form" method="get" action="<?php echo create_url('resource/assign');?>">
            <div id="detail" class="block-body collapse in">
                <div>
                    <ul class="role_ul">
                    <?php foreach($source as $key => $val) : ?>
                        <li class="role_2"><input type="radio" name="id" value="<?php echo $key;?>" /><?php echo $val;?></li>
                    <?php endforeach;?>
                    </ul>
                </div>
                <BR>
                <div class="control-group" style="margin-left:25px;margin-top:20px;">
                    <label id="source_msg"></label>
                    <div class="controls"><button type="submit" class="btn btn-primary"><strong>提交</strong></button></div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>