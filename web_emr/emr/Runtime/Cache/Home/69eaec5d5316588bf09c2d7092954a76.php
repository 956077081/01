<?php if (!defined('THINK_PATH')) exit();?><html>

<head>
		<meta type="Content-Type" content="text/html; charset=utf-8" />
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
					</div>
			</div>
			<div class="left_info_block">
				<div class="sub_title"><img src="/web_emr/Public/css/images/zhuyuanxinxi/jibenxinxi.png" height="20" style="vertical-align:top">模板管理</div>
					<?php if($user_manage_auth == 'true'): ?><div class="info_block_area">
							<div class="info_block">删除此模板 <?php echo ($muban_info["mingcheng"]); ?>-<?php echo ($muban_info["muban_bingli_type"]); ?> ：&nbsp;<input type="button" id="delete_muban" class="submit_button" value=" 删 除 " /></div>
						</div><?php endif; ?>
						<div class="info_block_area">
							<div class="info_block">复制此模板 <?php echo ($muban_info["mingcheng"]); ?>-<?php echo ($muban_info["muban_bingli_type"]); ?> ：&nbsp;<input type="button" class="search_button" id="fuzhi_muban" value="复制模板"/></div>
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

	$(function(){
		$("#delete_muban").click(function(){
			if (confirm('是否确认进行此删除操作？'))
				window.location.href =  "http://"+server_url+"/web_emr/Common/MubanGuanli/deleteOneMuban/muban_id/"+muban_id+"/muban_bingli_type/<?php echo ($muban_info["muban_bingli_type"]); ?>/muban_leixing/<?php echo ($muban_info["muban_leixing"]); ?>/muban_kebie/<?php echo ($muban_info["muban_kebie"]); ?>";
		});
		
		$("#fuzhi_muban").click(function(){
			art.dialog({
				id:"fuzhimuban_dialog",
				title:"复制模板",
				content:'<form class="add_form" method="post" action="/web_emr/Home/MubanGuanli/fuzhiMubanBingli">'+
									'<table>'+
										'<tr>'+
											'<td style="text-align:right">请输入新模板名称：</td>'+
											'<td><input name="mingcheng" value=""/></td>'+
										'</tr>'+
										'<tr>'+
											'<td style="text-align:right">请选择所增加的模板的类型：</td>'+
											'<td>'+
												'<select name="muban_leixing"  class="select_name">'+
													'<option value="科室模板">科室模板</option>'+
													'<option value="个人模板">个人模板</option>'+
												'</select>'+
											'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style="text-align:right">请选择模板的科别：</td>'+
											'<td>'+
												'<select name="muban_kebie"  class="select_name">'+
													'<option value="'+muban_kebie+'">'+muban_kebie+'</option>'+
												'</select>'+
											'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style="text-align:right">复制类型：</td>'+
											'<td>'+
												'<select name="muban_bingli_type"  class="select_name">'+
													'<option value="'+muban_bingli_type+'">'+muban_bingli_type+'</option>'+
													'<option value="批量复制">批量复制</option>'+
												'</select>'+
											'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style="text-align:right">请输入模板别称(维语名字等)：</td>'+
											'<td><input name="second_mingcheng" value=""/></td>'+
										'</tr>'+
										'<tr>'+
											'<td colspan="2">'+
												'<input type="hidden" name="yiyuan_id" value="'+yiyuan_id+'">'+
												'<input type="hidden" name="muban_id" value="'+muban_id+'">'+
												'<input type="submit" class="submit_button" value="复 制" />'+
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
						art.dialog.list['fuzhimuban_dialog'].close();
					});
				}
			});
		});
		
	});
</script>
</body>
</html>