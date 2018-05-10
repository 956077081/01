<?php if (!defined('THINK_PATH')) exit();?><html >

<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/list_view.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/user_manager.css" media="all" />

		<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js"></script>
		<script type="text/javascript" src="/web_emr/Public/js/jquery.form.js"></script>
		<script type="text/javascript" src="/web_emr/Public/js/tiantan_ui.js"></script>
		<script language="javascript" type="text/javascript" src="/web_emr/Public/js/artDialog/artDialog.js?skin=aero" ></script>
</head>

<body>
	<div class="list_title">
		<div class="list_title_span">
			当前模板套餐:<?php echo ($muban_info["mingcheng"]); ?>
		</div>
	</div>
	<div class="overview">
			<div class="left_info_block">
				<div class="sub_title"><img src="/web_emr/Public/css/images/zhuyuanxinxi/jibenxinxi.png" height="20" style="vertical-align:top">基本信息</div>
					<div class="info_block_area">
						<div class="info_block">模板套餐名称：<?php echo ($muban_info["mingcheng"]); ?></div>
						<div class="info_block">模板类型：<?php echo ($muban_info["muban_leixing"]); ?></div>
						<div class="info_block">模板病历类型：<?php echo ($muban_info["muban_bingli_type"]); ?></div>
						<div class="info_block">模板科别：<?php echo ($muban_info["muban_kebie"]); ?></div>
						<div class="info_block">所属医生：<?php echo ($muban_info["suoshu_user_name"]); ?></div>
						<div class="info_block">所属科室：<?php echo ($muban_info["suoshu_department_name"]); ?></div>
						<div class="info_block">所属医院：<?php echo ($muban_info["suoshu_yiyuan_name"]); ?></div>
						<?php if($muban_info["suoshu_user_id"] == $_SESSION['user_id'] or $_SESSION['user_type'] == '管理员' ): ?><input type="button" id="xougai_muban" class="submit_button" value="修改信息" /><?php endif; ?>
					</div>
			</div>
	</div>
	</div>

<script type="text/javascript">
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	muban_id = "<?php echo ($muban_info["muban_id"]); ?>";
	muban_bingli_type = "<?php echo ($muban_info["muban_bingli_type"]); ?>";
	muban_kebie = "<?php echo ($muban_info["muban_kebie"]); ?>";
	user_id = "<?php echo ($_SESSION["user_id"]); ?>";
	user_department = "<?php echo ($_SESSION["user_department"]); ?>";
	yiyuan_id = "<?php echo ($_SESSION["yiyuan_id"]); ?>"

	$("#xougai_muban").click(function(){
		art.dialog({
			id:"zengjiamuban_dialog",
			title:"编辑模板",
			content:'<form class="add_form" method="post" action="/web_emr/Home/MubanGuanli/updateMubanBingliGroup">'+
								'<table>'+
									'<tr>'+
										'<td style="text-align:right">请输入模板名称：</td>'+
										'<td><input name="mingcheng" value="<?php echo ($muban_info["mingcheng"]); ?>"/></td>'+
									'</tr>'+
									'<tr>'+
										'<td style="text-align:right">请选择所增加的模板的类型：</td>'+
										'<td>'+
											'<select name="muban_leixing"  class="select_name">'+
												'<option value="<?php echo ($muban_info["muban_leixing"]); ?>"><?php echo ($muban_info["muban_leixing"]); ?></option>'+
												'<option value="公共模板">公共模板</option>'+
												'<option value="科室模板">科室模板</option>'+
												'<option value="个人模板">个人模板</option>'+
											'</select>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td style="text-align:right">请选择模板的科别：</td>'+
										'<td>'+
											'<select name="muban_kebie"  class="select_name">'+
												'<option value="<?php echo ($muban_info["muban_kebie"]); ?>"><?php echo ($muban_info["muban_kebie"]); ?></option>'+
												'<option value="内科">内科</option>'+
												'<option value="外科">外科</option>'+
												'<option value="妇科">妇科</option>'+
												'<option value="儿科">儿科</option>'+
												'<option value="中医科">中医科</option>'+
												'<option value="其它">其它</option>'+
											'</select>'+
										'</td>'+
									'</tr>'+
						
									'<tr>'+
										'<td style="text-align:right">请输入模板别称(维语名字等)：</td>'+
										'<td><input name="second_mingcheng" value="<?php echo ($muban_info["second_mingcheng"]); ?>"/></td>'+
									'</tr>'+
								
									'<tr>'+
										'<td colspan="2">'+
											'<input type="hidden" name="muban_id" value="<?php echo ($muban_info["muban_id"]); ?>" />'+
											'<input type="submit" class="submit_button" value="修 改" />'+
											'<input type="button" id="cancel_add" class="submit_button" value="取 消" />'+
										'</td>'+
									'</tr>'+
								'</table>'+
							'</form>',
			lock: true,
			padding:5,
			drag: false,
			resize: false,
			fixed: true,
			close:function(){
				$("body").eq(0).css("overflow","scroll");
			},
			init: function () {
				$("body").eq(0).css("overflow","hidden");
				$("#cancel_add").click(function(){
					art.dialog.list['zengjiamuban_dialog'].close();
				});
			}
		});
	});
	
</script>
</body>
</html>