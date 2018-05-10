<?php if (!defined('THINK_PATH')) exit();?><html >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/web_system.css" />
	<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/tiantan_ui.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/web_form_fully.js"></script>
	<title><?php echo (C("software_title")); ?></title>
</head>
<body>
<!-- container -->
<div class="container">
	<!-- leftMenu -->
	<div class="left_menu">
		<li>
			<span class="function_button" id="print"><br />打印</span>
		</li>
		<li>
			<span class="function_button" id="preview"><br />预览</span>
		</li>
	</div>	

	<!-- rightCon -->
	<div class="right_content" id="right_content">
			<iframe frameborder="0" id="conframe" scrolling="yes" class="conframe" name="conframe" hspace="0" height="100%" width="100%"  src=''></iframe>
	</div>

</div>
<!-- container over-->
<!-- footer -->
	<div class="footer" id="footer">
			<iframe frameborder="0" class="printer_conframe" id="printer_conframe" scrolling="yes" name="printer_conframe" hspace="0" height="100%" width="100%"  src="/web_emr/Home/BingliEditor/printBingli"></iframe>
	</div>
	<div class="loading"></div>
<!-- footer over -->
<script>
   
	$(function(){
		server_url = "<?php echo (C("WEB_HOST")); ?>";
		user_id = "<?php echo ($_SESSION["user_id"]); ?>";
		user_name = "<?php echo ($_SESSION["user_name"]); ?>";
		user_type = "<?php echo ($_SESSION["user_type"]); ?>";
		user_department = "<?php echo ($_SESSION["user_department"]); ?>";
		user_department_position = "<?php echo ($_SESSION["user_department_position"]); ?>";
		current_yiyuan_id = "<?php echo ($_SESSION["yiyuan_id"]); ?>";
		url="/web_emr/Home/BingliEditor/showBingli/zhuyuan_id/3000/bingli_type/门诊病历/yiyuan_id/2000"
        url=encodeURI(url);
		url=encodeURI(url);
		$("#conframe").attr("src",url);
		$("#show_patient").click(function(){
			$("#conframe").attr("src","/web_emr/MenzhenYishi/Patient/showPatientList/suoyoubingren/suoyou");
		});
	})
</script>
</body>
</html>