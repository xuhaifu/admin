<div class="navbar-inner">
    <a class="brand" href="<?php echo create_url('');?>"><span class="second">后台管理系统</span></a>
    <ul class="nav pull-right">
        <?php foreach($sys_menu_main as $val) : ?>
        <li id="fat-menu<?php echo $val['id'];?>" class="nvarbar_right_li" <?php if(isset($sys_select_first_id) && $sys_select_first_id == $val['id']) : ?>style="background:#444;"<?php endif;?>><a href="<?php echo $val['url'];?>" style="margin-top:8px;"><?php echo $val['title'];?></a></li>
        <?php endforeach;?>
        <li id="fat-menu" class="nvarbar_right_li"><a href="<?php echo create_url('login/logout');?>" style="margin-top:8px;"><?php echo htmlspecialchars($sys_user['nickname']);?> (<i class="icon-off"></i>)</a></li>
    </ul>
</div>