<?php $this->load->view('base/header');?>

<script src="<?php echo base_url('assets/lib/validate/jquery.validate.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/validate/messages_zh.js');?>"></script>
<script type="text/javascript">
$().ready(function(){
    $("#form").validate({
        rules:{
            'data[role_name]' : {'required' : true}
        }
    });
});
</script>
<div class="content">
    <div class="header"><h1 class="page-title">角色管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">角色管理 <span class="divider">/</span></li>
        <li class="active"><?php if($action == 'edit') : ?>角色编辑<?php else : ?>角色新增<?php endif;?></li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
        <div class="well">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">请填写资料</a></li>
            </ul>    
            <div class="tab-content">
                <div class="tab-pane active in" id="home">
                <form id="form" method="post" action="<?php echo create_url('role/verify');?>">
                <div class="control-group">
                    <label class="control-label" for="role_name"><?php echo $column['role_name'];?><sup class="red">*</sup></label>
                    <div class="controls">
                        <input type="text" name="data[role_name]" id="role_name" class="input-xlarge" value="<?php echo isset($data['role_name']) ? $data['role_name'] : NULL;?>">
                    </div>
                </div>
                <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                <div class="control-group">
                    <label class="control-label" for="source_id"><?php echo $column['source_id'];?></label>
                    <div class="controls">
                        <select name="data[source_id]">
                            <?php foreach(lang_setting('admin_source_id') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['source_id']) && $data['source_id'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <?php endif;?>
                <div class="control-group">
                    <label class="control-label" for="remark"><?php echo $column['remark'];?></label>
                    <div class="controls">
                        <input type="text" name="data[remark]" id="remark" class="input-xlarge" value="<?php echo isset($data['remark']) ? $data['remark'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="stat"><?php echo $column['stat'];?></label>
                    <div class="controls">
                        <select name="data[stat]">
                            <?php foreach(lang_setting('admin_role_stat') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['stat']) && $data['stat'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                        <label class="control-label"></label>
                        <input type="hidden" name="_captcha" value="<?php echo isset($captcha) ? $captcha : NULL;?>">
                        <input type="hidden" name="_action" value="<?php echo $action;?>">
                        <input type="hidden" name="role_id" value="<?php echo isset($data['role_id']) ? $data['role_id'] : NULL;?>">
                        <div class="controls"><button type="submit" class="btn btn-primary"><strong>提交</strong></button></div>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>