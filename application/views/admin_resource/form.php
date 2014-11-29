<?php $this->load->view('base/header');?>

<script src="<?php echo base_url('assets/lib/validate/jquery.validate.min.js');?>"></script>
<script src="<?php echo base_url('assets/lib/validate/messages_zh.js');?>"></script>
<script type="text/javascript">
$().ready(function(){
    $("#form").validate({
        rules:{
            'data[title]' : {'required' : true},
            'data[pid]' : {'required' : true},
            'data[level]' : {'required' : true},
            'data[permission_type]' : {'required' : true},
            'data[stat]' : {'required' : true}
        }
    });
});
</script>
<div class="content">
    <div class="header"><h1 class="page-title">资源管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">资源管理 <span class="divider">/</span></li>
        <li class="active"><?php if($action == 'edit') : ?>资源编辑<?php else : ?>资源新增<?php endif;?></li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
        <div class="well">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">请填写资料</a></li>
            </ul>    
            <div class="tab-content">
                <div class="tab-pane active in" id="home">
                <form id="form" method="post" action="<?php echo create_url('resource/verify');?>">
                <div class="control-group">
                    <label class="control-label" for="title"><?php echo $column['title'];?><sup class="red">*</sup></label>
                    <div class="controls">
                        <input type="text" name="data[title]" id="title" class="input-xlarge" value="<?php echo isset($data['title']) ? $data['title'] : NULL;?>">
                    </div>
                </div>
                <!-- 
                <div class="control-group">
                    <label class="control-label" for="url_directory"><?php echo $column['url_directory'];?></label>
                    <div class="controls">
                        <input type="text" name="data[url_directory]" id="url_directory" class="input-xlarge" value="<?php echo isset($data['url_directory']) ? $data['url_directory'] : NULL;?>">
                    </div>
                </div>
                 -->
                <div class="control-group">
                    <label class="control-label" for="url_class"><?php echo $column['url_class'];?></label>
                    <div class="controls">
                        <input type="text" name="data[url_class]" id="url_class" class="input-xlarge" value="<?php echo isset($data['url_class']) ? $data['url_class'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="url_method"><?php echo $column['url_method'];?></label>
                    <div class="controls">
                        <input type="text" name="data[url_method]" id="url_method" class="input-xlarge" value="<?php echo isset($data['url_method']) ? $data['url_method'] : NULL;?>">
                    </div>
                </div>
                <!-- 
                <div class="control-group">
                    <label class="control-label" for="url_param"><?php echo $column['url_param'];?></label>
                    <div class="controls">
                        <input type="text" name="data[url_param]" id="url_param" class="input-xlarge" value="<?php echo isset($data['url_param']) ? $data['url_param'] : NULL;?>">
                    </div>
                </div>
                 -->
                <div class="control-group">
                    <label class="control-label" for="pid"><?php echo $column['pid'];?><sup class="red">*</sup></label>
                    <div class="controls">
                        <input type="text" name="data[pid]" id="pid" class="input-xlarge" value="<?php echo isset($data['pid']) ? $data['pid'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="level"><?php echo $column['level'];?></label>
                    <div class="controls">
                        <select name="data[level]">
                            <?php foreach(lang_setting('admin_resource_level') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['level']) && $data['level'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="permission_type"><?php echo $column['permission_type'];?></label>
                    <div class="controls">
                        <select name="data[permission_type]">
                            <?php foreach(lang_setting('admin_resource_permission_type') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['permission_type']) && $data['permission_type'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="sort"><?php echo $column['sort'];?></label>
                    <div class="controls">
                        <input type="text" name="data[sort]" id="sort" class="input-xlarge" value="<?php echo isset($data['sort']) ? $data['sort'] : NULL;?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="stat"><?php echo $column['stat'];?></label>
                    <div class="controls">
                        <select name="data[stat]">
                            <?php foreach(lang_setting('admin_resource_stat') as $key => $val) : ?>
                            <option value="<?php echo $key;?>" <?php if(isset($data['stat']) && $data['stat'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                        <label class="control-label"></label>
                        <input type="hidden" name="_captcha" value="<?php echo isset($captcha) ? $captcha : NULL;?>">
                        <input type="hidden" name="_action" value="<?php echo $action;?>">
                        <input type="hidden" name="resource_id" value="<?php echo isset($data['resource_id']) ? $data['resource_id'] : NULL;?>">
                        <div class="controls"><button type="submit" class="btn btn-primary"><strong>提交</strong></button></div>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>