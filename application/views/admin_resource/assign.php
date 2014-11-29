<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">资源管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">资源管理 <span class="divider">/</span></li>
        <li class="active">最大权限分配</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
    <form id="form_search" name="form_search" action="" method="GET" style="margin-bottom:0px">
        <input type="hidden" name="id" value="<?php echo $source_id;?>" />
        <input type="hidden" name="sortby" id="sortby" value="<?php echo isset($search['sortby']) ? $search['sortby'] : '';?>" />
        <input type="hidden" name="asc" id="asc" value="<?php echo isset($search['asc']) ? $search['asc'] : 0;?>" />
    </form>
    <form id="form" name="form" method="post" action="<?php echo create_url('resource/assign', array('id' => $source_id));?>">
    <div class="btn-toolbar">
        <button type="submit" class="btn btn-primary"><strong>提交</strong></button>
        <button type="reset" class="btn btn-primary"><strong>重置</strong></button>
    </div>
    <div class="block">
        <a href="#list" class="block-heading" data-toggle="collapse">资源列表(<?php echo lang_setting('admin_source_id', $source_id);?>)</a>
        <div id="list" class="block-body collapse in">
            <table class="table table-striped table-hover">
                <thead>
                    <th><input type="checkbox" id="select_all"/></th>
                    <th><span class="tbsort" id="s_resource_id"><?php echo $column['resource_id'];?></span></th>
                    <th><span class="tbsort" id="s_title"><?php echo $column['title'];?></span></th>
                    <th><span class="tbsort" id="s_level"><?php echo $column['level'];?></span></th>
                    <th><span class="tbsort" id="s_url_class"><?php echo $column['url_class'];?></span></th>
                    <th><span class="tbsort" id="s_url_method"><?php echo $column['url_method'];?></span></th>
                    <th><span class="tbsort" id="s_pid"><?php echo $column['pid'];?></span></th>
                    <th><span class="tbsort" id="s_permission_type"><?php echo $column['permission_type'];?></span></th>
                    <th><span class="tbsort" id="s_sort"><?php echo $column['sort'];?></span></th>
                    <th><span class="tbsort" id="s_stat"><?php echo $column['stat'];?></span></th>
                    <th><span class="tbsort" id="s_created_ts"><?php echo $column['created_ts'];?></span></th>
                </thead>
                <tbody>
                <?php foreach($data as $row) :?>
                <tr>
                    <td><input type="checkbox" name="resource[]" value="<?php echo $row['resource_id'];?>" <?php if(isset($row['selected']) && $row['selected']) : ?>checked="checked"<?php endif;?> /></td>
                    <td><a href="<?php echo create_url('resource/detail', array('id' => $row['resource_id']));?>" target="_blank"><?php echo $row['resource_id'];?></a></td>
                    <td><?php echo htmlspecialchars($row['title']);?></td>
                    <td><?php echo lang_setting('admin_resource_level', $row['level']);?></td>
                    <td><?php echo htmlspecialchars($row['url_class']);?></td>
                    <td><?php echo htmlspecialchars($row['url_method']);?></td>
                    <td><?php echo $row['pid'];?></td>
                    <td><?php echo lang_setting('admin_resource_permission_type', $row['permission_type']);?></td>
                    <td><?php echo $row['sort'];?></td>
                    <td><?php echo lang_setting('admin_resource_stat', $row['stat']);?></td>
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
    </form>
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