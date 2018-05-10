<?php if (!defined('THINK_PATH')) exit();?>﻿<html >

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
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
	<script type="text/javascript" src="/web_emr/Public/js/printer_control.js?version=6" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/artDialog/artDialog.js?skin=blue" ></script>
	<title>编辑模板病历</title>
</head>

<body>
<br/>
<div class="top_manager_menu">
		当前模板名称:<?php echo ($bingli_info["mingcheng"]); ?> 
		<?php if(empty($show_back)): ?><input type="button" id="view_muban_detail" class="quick_menu_button" value=" 返 回 " />
		<?php else: ?>
			<input type="button"  class="submit_button" id="close" value=" 返 回 " /><?php endif; ?>
</div>
<div class="page">
	<?php if($bingli_info['bingli_type']=='住院病案首页'): ?><link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binganshouye.css" />
	<?php else: ?>
		<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binglijilu.css" /><?php endif; ?>
	<div id="bingli_content" name="content" contenteditable="true" style="min-height:500px;">
		<?php echo ($bingli_info["content"]); ?>
	</div>
</div>
<script type="text/javascript">
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	action_url = "http://<?php echo (C("WEB_HOST")); ?>/web_emr/Home/BingliEditor/saveMubanBingli";
	zhuyuan_id = "<?php echo ($bingli_info["muban_id"]); ?>";
	muban_id="<?php echo ($bingli_info["muban_id"]); ?>";
	
	bingli_type = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	
	muban_bingli_type = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	muban_leixing = "<?php echo ($bingli_info["muban_leixing"]); ?>";
	muban_kebie = "<?php echo ($bingli_info["muban_kebie"]); ?>";
	mingcheng = "<?php echo ($bingli_info["mingcheng"]); ?>";
	yiyuan_id = "<?php echo ($bingli_info["yiyuan_id"]); ?>";
	document_name = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	document_relate_table = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	patient_xingming = "<?php echo ($bingli_info["mingcheng"]); ?>";
	
	$(function(){
		$("#view_muban_detail").click(function(){
				
				var s="http://"+server_url+"/web_emr/MubanGuanli/showMubanDetailGroup/muban_id/"+zhuyuan_id+"/mingcheng/"+mingcheng+"/muban_leixing/"+muban_leixing+"/muban_kebie/"+muban_kebie+"/muban_bingli_type/"+muban_bingli_type;
				s=encodeURI(s);
				s=encodeURI(s);
				window.location.href=s
		});

	//病历编辑器各种参数
		revise = "<?php echo (C("revise_mode")); ?>";
		multi_media_engine = "<?php echo (C("multi_media_engine")); ?>";
		show_page_number ="<?php echo (C("multi_media_engine")); ?>";
		is_auto_save = "<?php echo (C("is_auto_save")); ?>";
		auto_save_interval = <?php echo (C("auto_save_interval")); ?>;
		printControlInitial();
	});
</script>

<?php if($browser_type == 'ie'): ?><script type="text/javascript" src="/web_emr/Public/js/ajax_editor_ie.js" ></script>
<?php else: ?>
	<script type="text/javascript" src="/web_emr/Public/js/ajax_editor.js" ></script><?php endif; ?>
</body>
</html>