<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">角色管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">角色管理</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
    <form id="form_search" name="form_search" action="" method="GET" style="margin-bottom:0px">
        <div class="btn-toolbar">
            <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
            <input type="hidden" name="sortby" id="sortby" value="<?php echo isset($search['sortby']) ? $search['sortby'] : '';?>" />
            <input type="hidden" name="asc" id="asc" value="<?php echo isset($search['asc']) ? $search['asc'] : 0;?>" />
        </div>
    </form>
    <form id="form" name="form" method="post" action="<?php echo create_url('admin/dispatcher', array('id' => $id));?>">
    <div class="btn-toolbar">
        <button type="submit" class="btn btn-primary"><strong>提交</strong></button>
        <button type="reset" class="btn btn-primary"><strong>重置</strong></button>
    </div>
    <div class="block">
        <a href="#list" class="block-heading" data-toggle="collapse">为 <?php echo htmlspecialchars($selected_user['nickname']);?> 分配角色</a>
        <div id="list" class="block-body collapse in">
            <table class="table table-striped table-hover">
                <thead>
                    <th><input type="checkbox" id="select_all"/></th>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <th><span class="tbsort" id="s_role_id"><?php echo $column['role_id'];?></span></th>
                    <?php endif;?>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <th><span class="tbsort" id="s_source_id"><?php echo $column['source_id'];?></span></th>
                    <?php endif;?>
                    <th><span class="tbsort" id="s_role_name"><?php echo $column['role_name'];?></span></th>
                    <th><span class="tbsort" id="s_stat"><?php echo $column['stat'];?></span></th>
                    <th><span class="tbsort" id="s_created_ts"><?php echo $column['created_ts'];?></span></th>
                </thead>
                <tbody>
                <?php foreach($data as $row) :?>
                <tr>
                    <td><input type="checkbox" name="role[]" value="<?php echo $row['role_id'];?>" <?php if(isset($row['selected']) && $row['selected']) : ?>checked="checked"<?php endif;?> /></td>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <td><a href="<?php echo create_url('role/detail', array('id' => $row['role_id']));?>" target="_blank"><?php echo $row['role_id'];?></a></td>
                    <?php endif;?>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <td><?php echo lang_setting('admin_source_id', $row['source_id']);?></td>
                    <?php endif;?>
                    <td><a href="<?php echo create_url('role/dispatcher', array('rid' => $row['role_id']));?>" target="_blank"><?php echo htmlspecialchars($row['role_name']);?></a></td>
                    <td><?php echo lang_setting('admin_role_stat', $row['stat']);?></td>
                    <td><?php echo $row['created_ts'];?></td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table> 
            <div class="control-group">
                <button type="submit" class="btn btn-primary"><strong>提交</strong></button>
                <button type="reset" class="btn btn-primary"><strong>重置</strong></button>
            </div>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
$().ready(function(){
    $('.tbsort').click(function(){
        if($('#sortby').val() == $(this).attr('id').substring(2)) {
            $('#asc').val(1 ^ $('#asc').val());
        } else {
            $('#asc').val(0);
        }
        $('#sortby').val($(this).attr('id').substring(2));
        document.form_search.submit();
    });
    if($('#sortby').val()) {
        if(parseInt($('#asc').val())) {
            $('#s_' + $('#sortby').val()).append(' <i class="icon-caret-up"></i>');
        } else {
            $('#s_' + $('#sortby').val()).append(' <i class="icon-caret-down"></i>');
        }
    }
    $("#select_all").click(function(){
        $("input[type=checkbox]").prop("checked", this.checked);
    });
});
</script>

<?php $this->load->view('base/footer');?>