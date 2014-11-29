<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">角色管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">角色管理  <span class="divider">/</span></li>
        <li class="active">角色详情</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
        <div class="btn-toolbar">
            <a href="<?php echo create_url('role/create');?>"><button class="btn btn-primary"><i class="icon-plus"></i> 新增</button></a>
            <a href="<?php echo create_url('role/edit', array('id' => $data['role_id']));?>" style="margin-left:5px"><button class="btn btn-primary"><i class="icon-edit"></i> 编辑</button></a>
        </div>
        <div class="block">
            <a href="#detail" class="block-heading" data-toggle="collapse">角色管理列表</a>
            <div id="detail" class="block-body collapse in">
            <table class="table table-striped">
                <tr>
                    <td><?php echo $column['role_id'];?></td>
                    <td><?php echo $data['role_id'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['role_name'];?></td>
                    <td><?php echo htmlspecialchars($data['role_name']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['source_id'];?></td>
                    <td><?php echo lang_setting('admin_source_id', $data['source_id']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['remark'];?></td>
                    <td><?php echo htmlspecialchars($data['remark']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['stat'];?></td>
                    <td><?php echo lang_setting('admin_role_stat', $data['stat']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['created_ts'];?></td>
                    <td><?php echo $data['created_ts'];?></td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>