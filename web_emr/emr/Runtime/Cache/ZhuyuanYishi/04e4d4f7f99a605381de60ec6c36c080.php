<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/list_view.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/user_manager.css" media="all" />
		<style type="text/css">
		.linchuanglujing {
			border: 1px solid #97a0b2;
			padding: 4px;
			height: 26px;
			font-size: 13px;
			width: 200px;
		}
		.hulijibie {
			border: 1px solid #97a0b2;
			padding: 4px;
			height: 26px;
			font-size: 13px;
			width: 200px;
		}
		.linchuanglujing_content {
			background:#fcfefa;
			width:650px;
			margin:0 auto;
			padding:5px;
			z-index:10;
			height:300px;
			margin:10px 0 10px 10px;
			background:#fff;
			border:1px solid #c5c5c3;
			overflow-y:scroll;
		}
		.linchuanglujing_item {
			background:#fcfefa;
			width:1000px;
			margin:0 auto;
			padding:5px;
			z-index:10;
			height:300px;
			margin:10px 0 10px 10px;
			background:#fff;
			border:1px solid #c5c5c3;
			overflow-y:scroll;
		}
		ul
		{
			padding:0px;
			padding-left:5px;
			padding-right:5px;
			margin:0px;
		}
		.linchuanglujing_content ul li {
			list-style:none;
			font-size:16px;
			cursor:pointer;
			border-bottom:1px dashed #ccc;
			padding-top:5px;
		}
		.linchuanglujing_content ul li:hover {
			background-color:#a5cafc;
			color:#0569fe;
		}
		.linchuang_lujing_content_form{
			width:100%;
			margin:2px;
		}
		.linchuang_lujing_content_form tr{
			height:20px;
			border-bottom:dashed 1px #a5cafc;
		}
		.linchuang_lujing_content_form .content_type{
			border-top:solid 2px #a5cafc;
			height:40px;
			vertical-align: bottom;
		}
		.lishi_bingli_content {
			background:#fcfefa;
			width:650px;
			margin:0 auto;
			padding:5px;
			z-index:10;
			height:300px;
			margin:10px 0 10px 10px;
			background:#fff;
			border:1px solid #c5c5c3;
			overflow-y:scroll;
		}
		.lishi_bingli_content ul li {
			list-style:none;
			line-height:1.2;
			font-size:16px;
			cursor:pointer;
			border-bottom:1px dashed #ccc;
			padding-top:2px;
			padding-buttom:2px;
			height:40px;
		}
		.lishi_bingli_content ul li:hover {
			background-color:#a5cafc;
			color:#0569fe;
		}
		.input_type_small {
			width:100px;
			border:1px solid #6ABEFD;
			height:20px;
			line-height:20px;
			margin-left:5px;
			padding-left:2px;
		}
		</style>
		<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js"></script>
		<script type="text/javascript" src="/web_emr/Public/js/jquery.form.js"></script>
		<script type="text/javascript" src="/web_emr/Public/js/tiantan_ui.js"></script>
		<script language="javascript" type="text/javascript" src="/web_emr/Public/js/artDialog/artDialog.js?skin=aero" ></script>
</head>

<body>
	<div class="list_title">
		<div class="list_title_span">
			当前患者:<?php echo ($zhuyuan_basic_info["xingming"]); ?>|<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>|<?php echo ($zhuyuan_basic_info["zhuangtai"]); ?>
		</div>
	</div>
	<div class="overview">
			<div class="left_info_block">
				<div class="sub_title"><img src="/web_emr/Public/css/images/zhuyuanxinxi/jibenxinxi.png" height="20" style="vertical-align:top">基本信息</div>
					<div class="info_block_area">
						<div class="info_block">年龄: <?php echo ($zhuyuan_basic_info["nianling"]); ?> 性别：<?php echo ($zhuyuan_basic_info["xingbie"]); ?></div>
						<div class="info_block">住院号：<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?> 病床号：<?php echo ($zhuyuan_basic_info["zhuyuan_chuanghao"]); ?></div>
						<div class="info_block">所在病区：<?php echo ($zhuyuan_basic_info["zhuyuan_department"]); ?> 住院状态：<?php echo ($zhuyuan_basic_info["zhuangtai"]); ?></div>
						<div class="info_block">入院日期：<?php echo ($zhuyuan_basic_info["ruyuan_time"]); ?></div>
						<?php if($zhuyuan_basic_info['zhuangtai']!='住院中'): ?><div class="info_block">出院日期：<?php echo ($zhuyuan_basic_info["chuyuan_time"]); ?></div><?php endif; ?>
						<div class="info_block">归档状态：<?php echo ($zhuyuan_basic_info["guidang_zhuangtai"]); ?></div>
					</div>
					
				<div class="sub_title"><img src="/web_emr/Public/css/images/zhuyuanxinxi/bingliguanli.png" height="20" style="vertical-align:top">病历管理：</div>
					<!-- 历史模板导入功能关闭 
					<div class="info_block_area" style="border-style:dashed;">
						ئىلگىرىكى كېسەللىك تارىخىنى كىرگۈزۈش
						<br />
						历史病历导入:&nbsp;<input type="button" id="lishi_bingli" class="submit_button" value="历史病历" />
					</div>
					-->
					<?php if(($zhuyuan_basic_info["zhuyuanyishi_id"] == $user_id) or ($zhuyuan_basic_info["zhuyuanyishi_id"] == '') or ($_SESSION["user_type"] == '管理员')): ?><div class="info_block_area" style="border-style:dashed;">
							选择模板:&nbsp;
							<select class="linchuanglujing" name="linchuanglujing" beforeunload="cancel">
								<option value="">请选择</option>
								<option value="内科">内科</option>
								<option value="外科">外科</option>
								<option value="妇科">妇科</option>
								<option value="儿科">儿科</option>
								<option value="中医科">中医科</option>
							</select>
						</div><?php endif; ?>
					<div class="info_block_area" style="border-style:dashed;">
						
						<br />
						当前使用模板名称:&nbsp;<?php echo ($zhuyuan_basic_info["linchuanglujing_mingcheng"]); ?>
					</div>
					<!--
					<div class="info_block_area" style="border-style:dashed;">
						بۇ كېسەللىك تارىخىنى ئۈلگىلىك كېسەللىك تارىخى قىلىش
						<br />
						快速复制此病历为模板病历:&nbsp;<input type="button" id="zengjia_muban" class="submit_button" value="增 加" />
					</div>
					-->
					<?php if(($zhuyuan_basic_info["zhuyuanyishi_id"] == $user_id) and ($zhuyuan_basic_info["zhuangtai"] == '已出院') and ($zhuyuan_basic_info["guidang_zhuangtai"] == '未归档')): ?><div id="guidang_block" class="info_block_area" style="border-style:dashed;">
							病历归档:&nbsp;<input type="button" class="submit_button guidang" value="提交归档" guidang_zhuangtai="归档中" />
						</div>
					<?php elseif(($zhuyuan_basic_info["zhuyuanyishi_id"] == $user_id) and ($zhuyuan_basic_info["guidang_zhuangtai"] == '归档中')): ?>
						<div id="guidang_block" class="info_block_area" style="border-style:dashed;">
							病历归档:&nbsp;<input type="button" class="submit_button guidang" value="撤销归档" guidang_zhuangtai="未归档" />
						</div>
					<?php elseif($_SESSION["yiyuan_id"] == $zhuyuan_basic_info["yiyuan_id"] and $_SESSION["user_department"] == '病案室病区' and $zhuyuan_basic_info["guidang_zhuangtai"] == '归档中'): ?>
						<div id="guidang_block" class="info_block_area" style="border-style:dashed;">
							病历归档:&nbsp;
							<input type="button" class="submit_button guidang" value="确认归档" guidang_zhuangtai="已归档" />
							<input type="button" class="submit_button guidang" value="退档" guidang_zhuangtai="未归档" />
						</div>
					<?php elseif($zhuyuan_basic_info["yiyuan_id"] == $_SESSION["yiyuan_id"] and $_SESSION["user_department"] == '病案室病区' and $zhuyuan_basic_info["guidang_zhuangtai"] == '已归档'): ?>
						<div id="guidang_block" class="info_block_area" style="border-style:dashed;">
							病历归档:&nbsp;
							<input type="button" class="submit_button guidang" value="取消归档" guidang_zhuangtai="未归档" />
						</div><?php endif; ?>
					
			</div>
	</div>

<script type="text/javascript">
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	zhuyuan_id = "<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>";
	user_id = "<?php echo ($_SESSION["user_id"]); ?>";
	user_department = "<?php echo ($_SESSION["user_department"]); ?>";
	patient_id = "<?php echo ($zhuyuan_basic_info["patient_id"]); ?>";
	yiyuan_id = "<?php echo ($zhuyuan_basic_info["yiyuan_id"]); ?>";

	$(function(){
		$(".guidang").click(function(){
			var guidang_zhuangtai = $(this).attr("guidang_zhuangtai");
			if(confirm("确认要进行该操作吗？"))
			{
				$.post("/web_emr/ZhuyuanYishi/Patient/updateGuidangZhuangtai",{zhuyuan_id:zhuyuan_id,guidang_zhuangtai:guidang_zhuangtai},function(data){
					if(data=="success")
					{
						alert("操作已成功!");
						window.location.reload();
					}
					else
					{
						alert("操作失败,请尝试联系管理员!")
					}
				});
			}
		});
	});

$("[name='linchuanglujing']").change(function(){

		var linchuanglujing = $(this);
		var selectnum = $("[name='linchuanglujing']")[0].selectedIndex;

			linchuang_type = linchuanglujing.val();
			//临床路径选择对话框
			art.dialog({
				id:"linchuanglujing_dialog",
				title:"选择"+linchuanglujing.val()+"科别的模板",
				content:'<div class="linchuanglujing_content">'+
						'</div>'+
						'<div class="linchuanglujing_confirm">'+
							'<input type="button"  class="submit_button" id="confirm" value=" 确 定 " />'+
							'<input type="button"  class="submit_button" id="cancel" value=" 取 消 " />'+
						'</div>',
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
					$("div.linchuanglujing_content").empty();
						
					$(".keshi").live('click',function(){
						var keshi_name = $(this).attr("keshi_name");
						$("[keshi_item='"+keshi_name+"']").each(function(){
							if($(this).css("display")=="list-item")
							{
								$(this).css("display","none");
							}
							else if($(this).css("display")=="none")
							{
								$(this).css("display","list-item");
							}
						});
					});
						
					$(".bingzhong").live('click',function(e){
						current_muban_id  = $(this).attr("muban_id");
						//获取模板详细信息并展示：
						if($("#backward").length == 0)
							$("#cancel").after('<input type="button"  class="submit_button" id="backward" value=" 返回 " />');
						$("#backward").click(function(){
							$("div.linchuanglujing_content").html("");
							muban_search_keyword = $("[name='muban_search_keyword']").val();
							yiyuan_id = "<?php echo ($zhuyuan_basic_info["yiyuan_id"]); ?>";
							$.get("http://"+server_url+"/web_emr/Home/MubanGuanli/getMubanList", {muban_kebie:linchuang_type,user_id:user_id,user_department:user_department,yiyuan_id:yiyuan_id,muban_search_keyword:muban_search_keyword}, function(data){
									$("div.linchuanglujing_content").empty();
								$(data).appendTo("div.linchuanglujing_content");
							});
							$(this).remove();
						});
						$("div.linchuanglujing_content").empty();
						$.get("http://"+server_url+"/web_emr/Home/MubanGuanli/getMubanContent", {muban_id:current_muban_id,zhuyuan_id:zhuyuan_id,yiyuan_id:yiyuan_id}, function(data){
								$("div.linchuanglujing_content").html("");
								$(data).appendTo("div.linchuanglujing_content");
								$('.bingli_preview').each(function() {
									$(this).click(function(){
										title_text = $(this).attr("muban_bingli_type");
										var dialog = art.dialog({id: 'preview_muban',title: title_text});
										shown_url = "http://"+server_url+"/web_emr/Home/BingliEditor/previewMubanBingli/show_back/notshow/muban_id/"+$(this).attr("muban_id")+"/muban_bingli_type/"+$(this).attr("muban_bingli_type");
										$.ajax({
											url: encodeURI(encodeURI(shown_url)),
											success: function (data) {
												
												preview_content = data+'<input type="button"  class="submit_button" id="close" value=" 关 闭 " />';
												dialog.content(preview_content);
												$("#close").click(function(){
													art.dialog.list['preview_muban'].close();
												});
											},
											cache: false
										});
									});
								});
						});
						$("#confirm").click(function(){
							if($("#linchuang_lujing_content_form").text()!="")
							{
								if(confirm("您当前可能有已经写好的病历，是否要直接覆盖？"))
								{
									$("#linchuang_lujing_content_form").append("<input name='shifou_fugai' value='true' />");
								}
								else
								{
									$("#linchuang_lujing_content_form").append("<input name='shifou_fugai' value='false' />");
								}
								$("#linchuang_lujing_content_form").submit();
							}
						});
						$("#cancel").click(function(){
							art.dialog.list['linchuanglujing_dialog'].close();
							$("[name='linchuanglujing']")[0].selectedIndex = 0;
						});
					});//$(".bingzhong").click(
						
					$("[name='search_muban']").live('click',function(){
						muban_search_keyword = $("[name='muban_search_keyword']").val();
						$.get("http://"+server_url+"/web_emr/Home/MubanGuanli/getMubanList", {muban_kebie:linchuang_type,user_id:user_id,user_department:user_department,yiyuan_id:yiyuan_id,muban_search_keyword:muban_search_keyword}, function(data){
							$("div.linchuanglujing_content").html("");
							$(data).appendTo("div.linchuanglujing_content");
						});
					});
						
					$("[name='show_all_muban']").live('click',function(){
						$.get("http://"+server_url+"/web_emr/Home/MubanGuanli/getMubanList", {muban_kebie:linchuang_type,user_id:user_id,user_department:user_department,yiyuan_id:yiyuan_id}, function(data){
							$("div.linchuanglujing_content").html("");
							$(data).appendTo("div.linchuanglujing_content");
						});
					});
						
					$.get("http://"+server_url+"/web_emr/Home/MubanGuanli/getMubanList", {muban_kebie:linchuang_type,user_id:user_id,user_department:user_department,yiyuan_id:yiyuan_id}, function(data){
						$("div.linchuanglujing_content").html("");
						$(data).appendTo("div.linchuanglujing_content");
					});//$.get getMubanList

					$("#confirm").click(function(){
						if($("#linchuang_lujing_content_form").text()!="")
						{
							if(confirm("您当前可能有已经写好的病历，是否要直接覆盖？"))
							{
								$("#linchuang_lujing_content_form").append("<input name='shifou_fugai' value='true' />");
							}
							else
							{
								$("#linchuang_lujing_content_form").append("<input name='shifou_fugai' value='false' />");
							}
							$("#linchuang_lujing_content_form").submit();
						}
						art.dialog.list['linchuanglujing_dialog'].close();
					});
					$("#cancel").click(function(){
						art.dialog.list['linchuanglujing_dialog'].close();
						$("[name='linchuanglujing']")[0].selectedIndex = 0;
					});
				}//init: function linchuanglujing_dialog
			});//art.dialog linchuanglujing_dialog
			$("[name='linchuanglujing']")[0].selectedIndex = 0;
});

$("#lishi_bingli").click(function(){
	art.dialog({
				id:"lishi_bingli_dialog",
				title:"选择要导入的病历",
				content:'<div class="lishi_bingli_content">'+
						'</div>'+
						'<div class="lishi_bingli_confirm">'+
							'<input type="button" class="submit_button" id="confirm" value=" 确 定 " />'+
							'<input type="button" class="submit_button" id="cancel" value=" 取 消 " />'+
						'</div>',
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
					var lishi_bingli_step = "first";
					readLishiBingli();
					$("#confirm").click(function(){
						if(lishi_bingli_step=="second")
						{
							$("#linchuang_lujing_content_form").submit();
							art.dialog.list['lishi_bingli_dialog'].close();
						}
					});
					$("#cancel").click(function(){
						art.dialog.list['lishi_bingli_dialog'].close();
					});
					function readLishiBingli()
					{
						$.get("http://"+server_url+"/web_emr/Home/Data/getLishiBingli", {zhuyuan_id:zhuyuan_id}, function(data){
							$("div.lishi_bingli_content").html(data);
							$(".bingli").click(function(e){
								if($(e.target).attr("class")=="bingli")
								{
									var muban_zhuyuan_id = $(e.target).attr("zhuyuan_id");
									$.get("http://"+server_url+"/web_emr/Home/Data/getLishiBingliContent", {zhuyuan_id:zhuyuan_id, muban_zhuyuan_id:muban_zhuyuan_id}, function(setdata){
										$("div.lishi_bingli_content").html(setdata);
										lishi_bingli_step = "second";
									})
								}
								else if($(e.target).attr("class")=="search_button")
								{
									window.parent.wenYiWen($(e.target).parent().attr("zhuyuan_id")+" 入院记录 首次病程");
								}
							});
						});
					}
				}
			});
});

$("#zengjia_muban").live("click",function(){
	art.dialog({
		id:"zengjiamuban_dialog",
		title:"增加模板",
		content:'<form class="add_form" method="post" action="/web_emr/Home/MubanGuanli/addMubanBingli">'+
							'<table>'+
								'<tr>'+
									'<td>请选择所增加的模板的类型：</td>'+
									'<td>'+
										'<select name="muban_leixing"  class="select_name">'+
											'<option value="公共模板">公共模板</option>'+
											'<option value="科室模板">科室模板</option>'+
											'<option value="个人模板">个人模板</option>'+
										'</select>'+
									'</td>'+
								'</tr>'+
								'<tr>'+
									'<td>请选择所增加的模板的科别：</td>'+
									'<td>'+
										'<select name="muban_kebie"  class="select_name">'+
											'<option value="内科">内科</option>'+
											'<option value="外科">外科</option>'+
											'<option value="妇科">妇科</option>'+
											'<option value="儿科">儿科</option>'+
											'<option value="中医科">中医科</option>'+
										'</select>'+
									'</td>'+
								'</tr>'+
								'<tr>'+
									'<td style="text-align:right">请选择模板名称：</td>'+
									'<td><input name="mingcheng" value=""/></td>'+
								'</tr>'+
								'<tr>'+
									'<td colspan="2">'+
										'<input type="hidden" name="zhuyuan_id" value="'+zhuyuan_id+'">'+
										'<input type="hidden" name="yiyuan_id" value="'+yiyuan_id+'">'+
										'<input type="submit" class="submit_button" value="增 加" />'+
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