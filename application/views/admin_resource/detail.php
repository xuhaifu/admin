<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">资源管理</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">资源管理 <span class="divider">/</span></li>
        <li class="active">资源详情</li>
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
        <div class="btn-toolbar">
            <a href="<?php echo create_url('resource/create');?>"><button class="btn btn-primary"><i class="icon-plus"></i> 新增</button></a>
            <a href="<?php echo create_url('resource/edit', array('id' => $data['resource_id']));?>" style="margin-left:5px"><button class="btn btn-primary"><i class="icon-edit"></i> 编辑</button></a>
        </div>
        <div class="block">
            <a href="#detail" class="block-heading" data-toggle="collapse">资源详情</a>
            <div id="detail" class="block-body collapse in">
            <table class="table table-striped">
                <tr>
                    <td><?php echo $column['resource_id'];?></td>
                    <td><?php echo $data['resource_id'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['title'];?></td>
                    <td><?php echo htmlspecialchars($data['title']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['url_directory'];?></td>
                    <td><?php echo htmlspecialchars($data['url_directory']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['url_class'];?></td>
                    <td><?php echo htmlspecialchars($data['url_class']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['url_method'];?></td>
                    <td><?php echo htmlspecialchars($data['url_method']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['url_param'];?></td>
                    <td><?php echo $data['url_param'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['pid'];?></td>
                    <td><?php echo $data['pid'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['level'];?></td>
                    <td><?php echo lang_setting('admin_resource_level', $data['level']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['permission_type'];?></td>
                    <td><?php echo lang_setting('admin_resource_permission_type', $data['permission_type']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['sort'];?></td>
                    <td><?php echo $data['sort'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['stat'];?></td>
                    <td><?php echo lang_setting('admin_resource_stat', $data['stat']);?></td>
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