<div class="sidebar-nav">
<?php foreach($sys_menu_minor as $val) : ?>
    <a href="#sidebar_menu_<?php echo $val['id']?>" class="nav-header collapsed" data-toggle="collapse"><i class="icon-th"></i><?php echo $val['title']?><i class="icon-chevron-up"></i></a>
    <ul id="sidebar_menu_<?php echo $val['id']?>" class="nav nav-list collapse in">
    <?php foreach($val['sub'] as $val_sub) : ?>
        <li id="<?php echo $val_sub['url_uid'];?>"><a href="<?php echo $val_sub['url'];?>"><?php echo $val_sub['title'];?></a></li>
    <?php endforeach;?>
    </ul>
<?php endforeach; ?>
</div>

