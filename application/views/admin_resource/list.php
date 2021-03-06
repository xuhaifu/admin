<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">资源管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">资源管理</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
    <form id="form_search" name="form_search" action="" method="GET" style="margin-bottom:0px">
        <div style="float:left;margin-right:5px">
            <label>名称</label>
            <input type="text" name="title" value="<?php echo isset($search['title']) ? $search['title'] : NULL;?>" placeholder="资源名称"> 
        </div>
        <div style="float:left;margin-right:5px">
            <label>控制器</label>
            <input type="text" name="class" value="<?php echo isset($search['class']) ? $search['class'] : NULL;?>" placeholder="类名"> 
        </div>
        <div style="float:left;margin-right:5px">
            <label>级别</label>
            <select name="level">
                <option value="">请选择</option>
                <?php foreach(lang_setting('admin_resource_level') as $key => $val) : ?>
                <option value="<?php echo $key;?>" <?php if(isset($search["level"]) && $search["level"] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div style="float:left;margin-right:5px">
            <label>控制状态</label>
            <select name="type">
                <option value="">请选择</option>
                <?php foreach(lang_setting('admin_resource_permission_type') as $key => $val) : ?>
                <option value="<?php echo $key;?>" <?php if(isset($search['type']) && $search['type'] != '' && $search['type'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="btn-toolbar">
            <input type="hidden" name="sortby" id="sortby" value="<?php echo isset($search['sortby']) ? $search['sortby'] : '';?>" />
            <input type="hidden" name="asc" id="asc" value="<?php echo isset($search['asc']) ? $search['asc'] : 0;?>" />
            <button id="btn_search" type="submit" class="btn btn-primary" style="margin-top:25px;"><strong>检索</strong></button>
        </div>
    </form>
    <div class="btn-toolbar">
        <a href="<?php echo create_url('resource/create');?>"><button class="btn btn-primary"><i class="icon-plus"></i> 新增</button></a>
        <a href="<?php echo create_url('resource/dispatcher');?>"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-shopping-cart"></i> 最大权限分配</button></a>
    </div>
    <div class="block">
        <a href="#list" class="block-heading" data-toggle="collapse">资源列表</a>
        <div id="list" class="block-body collapse in">
            <table class="table table-striped table-hover">
                <thead>
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
                    <th>操作</th>
                </thead>
                <tbody>
                <?php foreach($data as $row) :?>
                <tr>
                    <td><a href="<?php echo create_url('resource/detail', array('id' => $row['resource_id']));?>"><?php echo $row['resource_id'];?></a></td>
                    <td><?php echo htmlspecialchars($row['title']);?></td>
                    <td><?php echo lang_setting('admin_resource_level', $row['level']);?></td>
                    <td><?php echo htmlspecialchars($row['url_class']);?></td>
                    <td><?php echo htmlspecialchars($row['url_method']);?></td>
                    <td><?php echo $row['pid'];?></td>
                    <td><?php echo lang_setting('admin_resource_permission_type', $row['permission_type']);?></td>
                    <td><?php echo $row['sort'];?></td>
                    <td><?php echo lang_setting('admin_resource_stat', $row['stat']);?></td>
                    <td><?php echo $row['created_ts'];?></td>
                    <td>
                        <a class="icon-edit" href="<?php echo create_url('resource/edit', array('id' => $row['resource_id']));?>" title="编辑">编辑</a>&nbsp;&nbsp;
                        <a data-toggle="modal" class="icon-remove" href="<?php echo create_url('resource/del', array('id' => $row['resource_id']));?>#myModal" >删除</a>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table> 
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
$().ready(function(){
    $('.icon-remove').click(function(){
        var href=$(this).attr('href');
        bootbox.confirm('是否继续？', function(result) {
            if(result){
                location.replace(href);
           }
        });
    });
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
});
</script>

<?php $this->load->view('base/footer');?>