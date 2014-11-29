<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">角色分配</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">角色管理 <span class="divider">/</span></li>
        <li class="active">角色分配</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">

        <div class="block">
            <a href="#detail" class="block-heading" data-toggle="collapse">角色分配(<?php echo htmlspecialchars($current_role['role_name']);?>)</a>
            <form id="form" method="post" action="<?php echo create_url('role/dispatcher', array('rid' => $rid));?>">
            <div id="detail" class="block-body collapse in">
                <ul class="role_ul">
                <?php foreach($resource as $val) : ?>
                    <li class="role_1"><input type="checkbox" name="role[]" value="<?php echo $val['resource_id'];?>" <?php if(isset($val['selected']) && $val['selected']) : ?>checked="checked"<?php endif;?>/> <?php echo $val['title'];?></li>
                    <?php if(isset($val['sub_nav']) && is_array($val['sub_nav'])) : ?>
                    <?php foreach($val['sub_nav'] as $val_sub) : ?>
                    <li class="role_2"><input type="checkbox" name="role[]" value="<?php echo $val_sub['resource_id'];?>" <?php if(isset($val_sub['selected']) && $val_sub['selected']) : ?>checked="checked"<?php endif;?>/> <?php echo $val_sub['title']?></li>
                    <?php endforeach;?>
                    <?php endif;?>
                <?php endforeach;?>
                </ul>
                <BR>
                <div class="control-group" style="margin-left:25px;margin-top:20px;">
                    <div class="controls"><button type="submit" class="btn btn-primary"><strong>提交</strong></button></div>
                </div>
            </div>
            
            </form>
            <br>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>