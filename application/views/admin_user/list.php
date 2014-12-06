<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">管理员列表</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">管理员列表</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid" style='overflow: hiddrn;'>
    <form id="form_search" name="form_search" action="" method="GET" style="margin-bottom:0px">
        <div style="float:left;margin-right:5px">
            <label>昵称</label>
            <input type="text" name="nickname" value="<?php echo isset($search['nickname']) ? $search['nickname'] : NULL;?>" placeholder="管理员昵称"> 
        </div>
        <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
        <div style="float:left;margin-right:5px">
            <label>渠道</label>
            <select name="source">
                <option value="">请选择</option>
                <?php foreach(lang_setting('admin_source_id') as $key => $val) : ?>
                <option value="<?php echo $key;?>" <?php if(isset($search['source']) && $search['source'] != '' && $search['source'] == $key) : ?>selected="selected"<?php endif;?>><?php echo $val;?></option>
                <?php endforeach;?>
            </select>
        </div>
        <?php endif;?>
        <div class="btn-toolbar">
            <input type="hidden" name="sortby" id="sortby" value="<?php echo isset($search['sortby']) ? $search['sortby'] : '';?>" />
            <input type="hidden" name="asc" id="asc" value="<?php echo isset($search['asc']) ? $search['asc'] : 0;?>" />
            <button id="btn_search" type="submit" class="btn btn-primary" style="margin-top:25px;"><strong>检索</strong></button>
        </div>
    </form>
    <div class="btn-toolbar">
        <a href="<?php echo create_url('admin/create');?>"><button class="btn btn-primary"><i class="icon-plus"></i> 新增</button></a>
    </div>
    <div class="block" style='clear:both'; >
        <a href="#list" class="block-heading" data-toggle="collapse">管理员列表</a>
        <div id="list" class="block-body collapse in">
            <table class="table table-striped table-hover">
                <thead>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <th><span class="tbsort" id="s_user_id"><?php echo $column['user_id'];?></span></th>
                    <?php endif;?>
                    <th><span class="tbsort" id="s_username"><?php echo $column['username'];?></span></th>
                    <th><span class="tbsort" id="s_nickname"><?php echo $column['nickname'];?></span></th>
                    <th><span class="tbsort" id="s_email"><?php echo $column['email'];?></span></th>
                    <th><span class="tbsort" id="s_mobile"><?php echo $column['mobile'];?></span></th>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <th><span class="tbsort" id="s_source_id"><?php echo $column['source_id'];?></span></th>
                    <?php endif;?>
                    <th><span class="tbsort" id="s_department"><?php echo $column['department'];?></span></th>
                    <th><span class="tbsort" id="s_stat"><?php echo $column['stat'];?></span></th>
                    <th><span class="tbsort" id="s_is_admin"><?php echo $column['is_admin'];?></span></th>
                    <th><span class="tbsort" id="s_last_logined_ts"><?php echo $column['last_logined_ts'];?></span></th>
                    <th>操作</th>
                </thead>
                <tbody>
                <?php foreach($data as $row) :?>
                <tr>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <td><a href="<?php echo create_url('admin', array('id' => $row['user_id']));?>"><?php echo $row['user_id'];?></a></td>
                    <?php endif;?>
                    <td><a href="<?php echo create_url('admin', array('id' => $row['user_id']));?>"><?php echo $row['username'];?></a></td>
                    <td><?php echo htmlspecialchars($row['nickname']);?></td>
                    <td><?php echo htmlspecialchars($row['email']);?></td>
                    <td><?php echo htmlspecialchars($row['mobile']);?></td>
                    <?php if($sys_rbac_service->isSuperAdministrator()) : ?>
                    <td><?php echo lang_setting('admin_source_id', $row['source_id']);?></td>
                    <?php endif;?>
                    <td><?php echo htmlspecialchars($row['department']);?></td>
                    <td><?php echo lang_setting('admin_user_stat', $row['stat']);?></td>
                    <td><?php echo lang_setting('admin_user_is_admin', $row['is_admin']);?></td>
                    <td><?php echo $row['last_logined_ts'];?></td>
                    <td>
                        <a class="icon-edit" href="<?php echo create_url('admin/edit', array('id' => $row['user_id']));?>" title="编辑">编辑</a>&nbsp;&nbsp;
                        <?php if($row['is_admin'] != 2) : ?>
                        <a data-toggle="modal" class="icon-remove" href="<?php echo create_url('admin/del', array('id' => $row['user_id']));?>#myModal">删除</a>
                        <?php endif;?>
                        <?php if($row['is_admin'] == 0) : ?>
                        <a class="icon-shopping-cart" href="<?php echo create_url('admin/dispatcher', array('id' => $row['user_id']));?>">角色分配</a>
                        <?php endif;?>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table> 
            <?php echo $pagination;?>
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