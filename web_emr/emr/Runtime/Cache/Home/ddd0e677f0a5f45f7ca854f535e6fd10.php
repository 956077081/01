<?php if (!defined('THINK_PATH')) exit();?>﻿<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/TiantanMathml.css'/>
	<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binglijilu.css" />
		
	<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/Lodop_jquery_plugin.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/printer_control.js" ></script>
	<title>病历打印</title>
</head>

<body>
<div class="page">
	<br />
	<?php if($bingli_info['bingli_type']=='住院病案首页'): ?><link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binganshouye.css" />
	<?php else: ?>
		<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binglijilu.css" /><?php endif; ?>
	<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/printer_control.css"/>
	<div id="bingli_content" name="content">
		<?php echo ($bingli_info["content"]); ?>
	</div>
</div>
<script type="text/javascript">
		$(function(){
			server_url = "<?php echo (C("WEB_HOST")); ?>";
			action_url = "http://<?php echo (C("WEB_HOST")); ?>/web_emr/Home/BingliEditor/saveBingli";
			zhuyuan_id = "<?php echo ($bingli_info["zhuyuan_id"]); ?>";
			bingli_type = "<?php echo ($bingli_info["bingli_type"]); ?>";
			yiyuan_id = "<?php echo ($bingli_info["yiyuan_id"]); ?>";
			document_name = "<?php echo ($bingli_info["bingli_type"]); ?>";
			document_id = "<?php echo ($bingli_info["zhuyuan_id"]); ?>";
			document_relate_table = "<?php echo ($bingli_info["bingli_type"]); ?>";
			bingli_document_title = "<?php echo (C("hospital_sub_city")); echo (session('yiyuan_name')); ?>病历记录";
			user_department = "<?php echo (session('user_department')); ?>";
			zhuyuan_chuanghao = "<?php echo ($zhuyuan_basic_info["zhuyuan_chuanghao"]); ?>";
			patient_xingming = "<?php echo ($zhuyuan_basic_info["xingming"]); ?>";
			controlPrinterByLodop();
		});
</script>
</body>
</html>