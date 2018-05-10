<?php if (!defined('THINK_PATH')) exit();?>﻿<html >

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/TiantanMathml.css'/>
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/teditor.css'/>
	<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/printer_control.css"/>
		
	<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
	<title>编辑模板病历</title>
</head>

<body>
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
<script type="text/javascript">
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	action_url = "http://<?php echo (C("WEB_HOST")); ?>/web_emr/Home/BingliEditor/saveMubanBingli";
	zhuyuan_id = "<?php echo ($bingli_info["muban_id"]); ?>";
	bingli_type = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	muban_bingli_type = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	muban_leixing = "<?php echo ($bingli_info["muban_leixing"]); ?>";
	muban_kebie = "<?php echo ($bingli_info["muban_kebie"]); ?>";
	mingcheng = "<?php echo ($bingli_info["mingcheng"]); ?>";
	yiyuan_id = "<?php echo ($bingli_info["yiyuan_id"]); ?>";
	document_name = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	document_relate_table = "<?php echo ($bingli_info["muban_bingli_type"]); ?>";
	patient_xingming = "<?php echo ($bingli_info["mingcheng"]); ?>";
	mingcheng=encodeURI(mingcheng);
	mingcheng=encodeURI(mingcheng);
	muban_kebie=encodeURI(muban_kebie);
	muban_kebie=encodeURI(muban_kebie);
		muban_bingli_type=encodeURI(muban_bingli_type);
	muban_bingli_type=encodeURI(muban_bingli_type);
		muban_leixing=encodeURI(muban_leixing);
	muban_leixing=encodeURI(muban_leixing);
	
	$(function(){
		$("#view_muban_detail").click(function(){
		
			address="http://"+server_url+"/web_emr/MubanGuanli/showMubanDetail/muban_id/"+zhuyuan_id+"/mingcheng/"+mingcheng+"/muban_leixing/"+muban_leixing+"/muban_bingli_type/"+muban_bingli_type+"/muban_kebie/ "+muban_kebie;
			window.location.href=address;
		});
	});
</script>
</div>
</body>
</html>