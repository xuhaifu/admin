<?php $this->load->view('base/header');?>
<div class="content" <?php if(empty($sys_menu_minor)) : ?>style="margin:0px;border:0px;"<?php endif;?>>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="http-error">
                <h1>O~!</h1>
                <p class="info"><?php echo $message;?></p>
                <?php if(! empty($url)) :?>
                <h2>返回 <a href="<?php echo create_site_url($url);?>">
                <?php if(! empty($url_title)) :?>
                <?php echo $url_title;?>
                <?php else :?>
                <?php echo create_site_url($url);?>
                <?php endif;?>
                </a>
                <?php else :?>
                <h2><a href="javascript:history.go(-1);">返回</a></h2>
                <?php endif;?>
            </div>
        <div>
    </div>
</div></div></div>
<?php $this->load->view('base/footer');?>