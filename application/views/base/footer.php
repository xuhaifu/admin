<?php $this->load->view('base/copyright');?>
<script type="text/javascript">
$().ready(function(){
    var sys_page_uid = '<?php echo $sys_page_uid;?>';
    $("#" + sys_page_uid).addClass('active');
});
</script>
</body>
</html>