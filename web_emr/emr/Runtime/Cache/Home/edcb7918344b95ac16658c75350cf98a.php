<?php if (!defined('THINK_PATH')) exit();?><html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name=”viewport” content=”width=device-width,target-densitydpi=high-dpi,initial-scale=0.8, minimum-scale=0.8, maximum-scale=1.0, user-scalable=no”/>
	<title><?php echo (C("software_title")); ?></title>
	<link href="/web_emr/Public/css/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="login_box">
		<form class="ajax_form" id="login_form" name="login_form" method="post" action="/web_emr/System/checkLogin">
			<div class="input_area">
				<input class="user_name_input" type="text" name="user_login_name_emr" id="user_login_name_emr"/>
				<input class="user_password_input" type="password" name="user_login_password_emr" id="user_login_password_emr"/>
				<div class="login_tips">
				</div>
			</div>
			<div class="control_area">
				<input type="submit" name="button" class="large_login_button" value=""/>
			</div>
		</form>
	</div>
	<div class="login_info">
		欢迎使用在线电子病历系统。
	</div>
</body>

<script language="javascript" type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
<script language="javascript" type="text/javascript" src="/web_emr/Public/js/jquery.form.js" ></script>
<script language="javascript" type="text/javascript" src='/web_emr/Public/js/login.js' ></script>
<script>
   
		$(function(){
			$("#user_login_name_emr").live("keydown",function(e){
				console.log(e.keyCode);
				if(e.keyCode=="13")
				{
					e.preventDefault();
					$("#user_login_password_emr").focus();
				}
			});
		})
</script>
</html>