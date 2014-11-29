<?php $this->load->view('base/header');?>

<script src="<?php echo base_url('assets/lib/validate/jquery.validate.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/validate/additional-methods.js');?>"></script>
<script src="<?php echo base_url('assets/lib/validate/messages_zh.js');?>"></script>
<script type="text/javascript">
$().ready(function(){
    $("#form").validate({
        rules:{
<?php if($action == 'create') : ?>
            'data[username]' : {'required' : true, 'minlength': 3, 'maxlength': 50, 'username':true, 'remote': "<?php echo create_url('ajax/check_username')?>"},
<?php endif;?>
            'data[nickname]' : {'required' : true},
<?php if($action == 'create') : ?>
            'data[password]' : {'required' : true, 'minlength': 6, 'maxlength': 50},
<?php else : ?>
            'data[password]' : {'minlength': 6, 'maxlength': 50},
<?php endif;?>
            'data[is_admin]' : {'required' : true}
        },
        messages: {
<?php if($action == 'create') : ?>
            'data[username]' : {
                'required' : '只允许数字、字母、下划线，字母打头，长度3-50',
                'remote' : '该用户名已存在'
             }
<?php endif;?>
        }
    });
});
</script>
<div class="content">
    <div class="header"><h1 class="page-title">管理员列表</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <?php if($action == 'edit') : ?>
        <li class="active">我的信息 <span class="divider">/</span></li>
        <li class="active">编辑信息</li>
        <?php else : ?>
        <li class="active">管理员列表 <span class="divider">/</span></li>
        <li class="active">新增管理员</li>
        <?php endif;?>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
        <div class="well">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">请填写资料</a></li>
            </ul>    
            <div class="tab-content">
                <div class="tab-pane active in" id="home">
                <form id="form" method="post" action="<?php echo create_url('admin/verify');?>">
                <?php if($action == 'create') : ?>
                <div class="control-group">
                    <label class="control-label" for="username"><?php echo $column['username'];?><sup class="red">*</sup></label>
                    <div class="controls">
                        <input type="text" name="data[username]" id="username" class="input-xlarge" value="<?php echo isset($data['username']) ? $data['username'] : NULL;?>">
                    </div>
                </div>
                <?php else : ?>
                <div class="control-group">
                    <label class="control-label" for="username"><?php echo $column['username'];?> ：<b><?php echo $data['username'];?></b></label>
                </div>
                <?php endif;?>
                <div class="control-group">
                    <label class="control-label" for="password"><?php echo $column['password'];?><sup class="red">*</sup></label>
                    <div class="controls">
                        <input type="password" name="data[password]" id="password" class="input-xlarge" value="">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="nickname"><?php echo $column['nickname'];?><sup class="red">*</sup></label>
                    <div class="controls">
                        <input type="text" name="data[nickname]" id="nickname" class="input-xlarge" value="<?php echo isset($data['nickname']) ? $data['nickname'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email"><?php echo $column['email'];?></label>
                    <div class="controls">
                        <input type="text" name="data[email]" id="email" class="input-xlarge" value="<?php echo isset($data['email']) ? $data['email'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="mobile"><?php echo $column['mobile'];?></label>
                    <div class="controls">
                        <input type="text" name="data[mobile]" id="mobile" class="input-xlarge" value="<?php echo isset($data['mobile']) ? $data['mobile'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="department"><?php echo $column['department'];?></label>
                    <div class="controls">
                        <input type="text" name="data[department]" id="department" class="input-xlarge" value="<?php echo isset($data['department']) ? $data['department'] : NULL;?>">
                    </div>
                </div>
                <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                <div class="control-group">
                    <label class="control-label" for="source_id"><?php echo $column['source_id'];?></label>
                    <div class="controls">
                        <select name="data[source_id]">
                            <?php foreach(lang_setting('admin_source_id') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['source_id']) && $key == $data['source_id']) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <?php endif;?>
                <?php if($sys_rbac_service->isSuperAdministrator() || $sys_rbac_service->isAdministrator()) : ?>
                <div class="control-group">
                    <label class="control-label" for="stat"><?php echo $column['stat'];?></label>
                    <div class="controls">
                        <select name="data[stat]">
                            <?php foreach(lang_setting('admin_user_stat') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['stat']) && $key == $data['stat']) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="is_admin"><?php echo $column['is_admin'];?></label>
                    <div class="controls">
                        <select name="data[is_admin]">
                            <?php foreach($admin_list as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['is_admin']) && $key == $data['is_admin']) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <?php endif;?>
                <div class="control-group">
                        <label class="control-label"></label>
                        <input type="hidden" name="_captcha" value="<?php echo isset($captcha) ? $captcha : NULL;?>">
                        <input type="hidden" name="_action" value="<?php echo $action;?>">
                        <input type="hidden" name="user_id" value="<?php echo isset($data['user_id']) ? $data['user_id'] : NULL;?>">
                        <div class="controls"><button type="submit" class="btn btn-primary"><strong>提交</strong></button></div>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>