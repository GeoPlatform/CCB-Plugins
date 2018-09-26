<?php
if(!defined('ABSPATH')) die();
?>
<div style="border-bottom: 1px solid #eeeeee;margin: -10px -12px 10px;padding: 10px;background: #f8f8f8">
<center><a href="https://www.wpdownloadmanager.com/?affid=shahriar&utm_source=<?php echo isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';?>&utm_campaign=wpdashboard&utm_term=wpdashboard" target="_blank"><img style='max-width:60%;' src='<?php echo plugins_url('/download-manager/assets/images/wpdm-logo.png'); ?>' /></a></center>
</div>
<div class="w3eden">

            <iframe src="//cdn.wpdownloadmanager.com/notice.php?wpdmvarsion=<?php echo WPDM_Version; ?>&origin=<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" style="height: 390px;width:100%;border:0px"></iframe>

</div>