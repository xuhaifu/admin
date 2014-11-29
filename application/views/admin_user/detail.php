<?php $this->load->view('base/header');?>

<div class="content">
    <div class="header"><h1 class="page-title">我的信息</h1></div>
    <ul class="breadcrumb">
        <li><a href="<?php echo create_url('');?>">首页</a> <span class="divider">/</span></li>
        <li class="active">我的信息  <span class="divider">/</span></li> 
        <li class="active">资料详情</li> 
        <li class="pull-right"><a href="javascript:history.go(-1);">返回</a></li>
    </ul>
    <div class="container-fluid">
        <div class="btn-toolbar">
            <a href="<?php echo create_url('admin/edit', array('id' => $data['user_id']));?>" style="margin-left:5px"><button class="btn btn-primary"><i class="icon-edit"></i> 编辑</button></a>
        </div>
        <div class="block">
            <a href="#detail" class="block-heading" data-toggle="collapse">信息详情</a>
            <div id="detail" class="block-body collapse in">
            <table class="table table-striped">
                <tr>
                    <td><?php echo $column['username'];?></td>
                    <td><?php echo $data['username'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['nickname'];?></td>
                    <td><?php echo htmlspecialchars($data['nickname']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['email'];?></td>
                    <td><?php echo htmlspecialchars($data['email']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['mobile'];?></td>
                    <td><?php echo htmlspecialchars($data['mobile']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['source_id'];?></td>
                    <td><?php echo lang_setting('admin_source_id', $data['source_id']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['department'];?></td>
                    <td><?php echo htmlspecialchars($data['department']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['stat'];?></td>
                    <td><?php echo lang_setting('admin_user_stat', $data['stat']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['is_admin'];?></td>
                    <td><?php echo lang_setting('admin_user_is_admin', $data['is_admin']);?></td>
                </tr>
                <tr>
                    <td><?php echo $column['created_ts'];?></td>
                    <td><?php echo $data['created_ts'];?></td>
                </tr>
                <tr>
                    <td><?php echo $column['last_logined_ts'];?></td>
                    <td><?php echo $data['last_logined_ts'];?></td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('base/footer');?>