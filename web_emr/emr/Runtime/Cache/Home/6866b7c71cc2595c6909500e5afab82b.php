<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/themes/base/jquery.ui.all.css"/>
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/TiantanMathml.css'/>
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/teditor.css'/>
	<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/printer_control.css"/>
		
	<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery-ui-1.8.16.custom.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.form.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.scrollto.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.qtip-1.0.0-rc3.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/tiantan_ui.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/Lodop_jquery_plugin.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/printer_control.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/artDialog/artDialog.js?skin=blue" ></script>
	<title>病历编辑</title>
</head>

<body>
<div class="page">
	<br />
	<?php if($bingli_info['bingli_type']=='住院病案首页'): ?><link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binganshouye.css" />
	<?php else: ?>
		<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binglijilu.css" /><?php endif; ?>
	<div id="bingli_content" name="content" <?php if($editable != "no"): ?>contenteditable="true"<?php endif; ?> style="min-height:500px;">
		<?php echo ($bingli_info["content"]); ?>
	</div>
</div>
<div id="auto_slelect_options_area">
	
</div>
<script type="text/javascript">
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	action_url = "http://<?php echo (C("WEB_HOST")); ?>/web_emr/Home/BingliEditor/saveBingli";
	zhuyuan_id = "<?php echo ($bingli_info["zhuyuan_id"]); ?>";
	bingli_type = "<?php echo ($bingli_info["bingli_type"]); ?>";
	yiyuan_id = "<?php echo ($bingli_info["yiyuan_id"]); ?>";
	document_name = "<?php echo ($bingli_info["bingli_type"]); ?>";
	document_id = "<?php echo ($bingli_info["zhuyuan_id"]); ?>";
	document_relate_table = "<?php echo ($bingli_info["bingli_type"]); ?>";
	patient_xingming = "<?php echo ($zhuyuan_basic_info["xingming"]); ?>";
	var editable = "<?php echo ($editable); ?>";
		$(function(){
			if(editable!=null && editable=="no") {}
			else {
				//病历编辑器各种参数
				revise = "<?php echo (C("revise_mode")); ?>";
				multi_media_engine = "<?php echo (C("multi_media_engine")); ?>";
				show_page_number ="<?php echo (C("multi_media_engine")); ?>";
				is_auto_save = "<?php echo (C("is_auto_save")); ?>";
				auto_save_interval = <?php echo (C("auto_save_interval")); ?>;
				printControlInitial();
			}
		});
</script>
<?php if($browser_type == 'ie'): ?><script type="text/javascript" src="/web_emr/Public/js/ajax_editor_ie.js" ></script>
<?php else: ?>
	<script type="text/javascript" src="/web_emr/Public/js/ajax_editor.js" ></script><?php endif; ?>
</body>
</html>