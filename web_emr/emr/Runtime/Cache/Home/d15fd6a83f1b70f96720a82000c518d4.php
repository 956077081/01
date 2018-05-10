<?php if (!defined('THINK_PATH')) exit();?><meta charset="UTF-8"><meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<html >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/themes/base/jquery.ui.all.css"/>
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/teditor.css'/>
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/web_system.css" />
	<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery-ui-1.8.16.custom.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.ui.position.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/jquery.form.js" ></script>
	<script type="text/javascript" src="/web_emr/Public/js/tiantan_ui.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/web_form_fully.js"></script>
	<script type="text/javascript" src="/web_emr/Public/js/Lodop_jquery_plugin.js"></script>
	<script language="javascript" type="text/javascript" src="/web_emr/Public/js/artDialog/artDialog.js?skin=aero" ></script>
	<title><?php echo (C("software_title")); ?></title>

	<style>
		.img_button_del{
			background-image:url(/web_emr/Public/css/images/btn_del.png);
			background-repeat:no-repeat;
			background-position:0px 2px;
			text-align:right;
			cursor:pointer;
		}

		.img_button_copy{
			background-image:url(/web_emr/Public/css/images/btn_copy.png);
			background-repeat:no-repeat;
			background-position:0px 2px;
			text-align:right;
			cursor:pointer;
		}
	</style>
</head>
<body>
<div class="head" >
	<span id="logo"></span>
	<span class="function_button" id="show_patient"><div>&nbsp;</div><div>患者列表</div></span>
	<span class="function_button" id="muban_manage"><div>&nbsp;</div><div>管理模板</div></span>
	<span class="function_button" id="logout"><div>&nbsp;</div><div>退出</div></span>
	<span class="function_button" id="print"><div>&nbsp;</div><div>打印</div></span>
	<span class="function_button" id="preview"><div>&nbsp;</div><div>预览</div></span>
	<span class="function_button" id="user_info">当前用户：<?php echo ($_SESSION["user_name"]); ?><br />当前医院：<?php echo ($_SESSION["yiyuan_name"]); ?></span>
</div>
<!-- container -->
<div class="container">
	<!-- leftMenu -->
	<div class="left_menu">
		<div class="nav_info" id="nav_info">请选择患者来书写病历</div>
		<li id="bingli_tree">
		</li>
	</div>

	<!-- rightCon -->
	<div class="right_content" id="right_content">
			<iframe frameborder="0" id="conframe" scrolling="yes" class="conframe" name="conframe" hspace="0" height="100%" width="100%"  src="/web_emr/ZhuyuanYishi/Patient/showPatientList/suoyoubingren/suoyou"></iframe>
	</div>
</div>
<!-- container over-->
<!-- footer -->
	<div class="footer" id="footer">
			<iframe frameborder="0" class="printer_conframe" id="printer_conframe" scrolling="yes" name="printer_conframe" hspace="0" height="100%" width="100%"  src=""></iframe>
	</div>
	<div class="loading"></div>
	<div class="system_tips"></div>
<!-- footer over -->
<script>
  
	function getSelects(types) {
		var st ='';
		if(types==null || types=="") {
		}else {
			var fs = types.split(",");
			for(var i=0; i<fs.length; i++) {
				st += '<option value="'+fs[i]+'">'+fs[i]+'</option>';
			}
		}
		return st;
	}
	$(function(){
		server_url = "<?php echo (C("WEB_HOST")); ?>";
		yiyuan_id = "<?php echo ($_SESSION["yiyuan_id"]); ?>";
		user_id = "<?php echo ($_SESSION["user_id"]); ?>";
		user_name = "<?php echo ($_SESSION["user_name"]); ?>";
		user_type = "<?php echo ($_SESSION["user_type"]); ?>";
		user_department = "<?php echo ($_SESSION["user_department"]); ?>";
		user_department_position = "<?php echo ($_SESSION["user_department_position"]); ?>";
		current_yiyuan_id = "<?php echo ($_SESSION["yiyuan_id"]); ?>";
		var muban_bingli_type = "<?php echo (C("muban_bingli_type")); ?>";
		//判断浏览器类型以及插件安装情况：
		var Sys = {};
		var ua = navigator.userAgent.toLowerCase();
               
		var s;
		(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
		(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
		(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
		(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
		(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
		if(Sys.firefox)
		{  
                
		}
		else
		{
			//$(".system_tips").append("<h3>●您正在使用的浏览器可能不能完整支持在线电子病历程序运行，请更换火狐(Firefox)浏览器，<a href='http://"+server_url+"/download/Firefox.exe'>点此下载</a>。</h3>");
			//$(".system_tips").show();
		}
		//检测lodop插件
		try
		{
                     
                     
                
			var LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
			if ((LODOP!=null)&&(typeof(LODOP.VERSION)!="undefined"))
			{       
                            
                             
				$(".system_tips").append("<h3>●你的计算机尚未安装打印控件，将会影响您使用打印功能，请安装打印控件，<a href='http://"+server_url+"/download/install_lodop32.exe'>点此下载</a>。</h3>");
				//$(".system_tips").show();
			}
		}
		catch(err)
		{           
				$(".system_tips").append("<h3>●你的计算机尚未安装打印控件，将会影响您使用打印功能，请安装打印控件，<a href='http://"+server_url+"/download/install_lodop32.exe'>点此下载</a>。</h3>");
				//$(".system_tips").show();
		}

		
		$("#show_patient").click(function(){
			$("#nav_info").html("请选择患者");
			$("#bingli_tree").html("");
			$("#conframe").attr("src","/web_emr/ZhuyuanYishi/Patient/showPatientList");
		});
		
		$("#muban_manage").click(function(){
			$("#nav_info").html("请选择模板");
			$("#bingli_tree").html("");
			
			$("#conframe").attr("src","/web_emr/Home/MubanGuanli/showMubanList");
		});
		
		$("#logout").click(function(){
			window.location.href = "http://"+server_url+"/web_emr/Home/System/logout";
		});
		var bingli_types = getSelects(muban_bingli_type);
		$("#add_bingli").live("click",function(){
			//病历树
			
		
			if(current_content_url.indexOf("showPatientZhuyuanDetail") != -1)
			{
				art.dialog({
					id:"zengjiamuban_dialog",
					title:"增加病历",
					content:'<form class="add_form" method="post" action="/web_emr/Home/BingliEditor/addOneBingliShu">'+
								'<table>'+
											'<tr>'+
												'<td style="text-align:right">请选择模板的文档类型：</td>'+
												'<td>'+
													'<select id="bingli_type" name="muban_bingli_type"  class="select_name">'+
														bingli_types+'<option value="手动输入">手动输入</option>' +
													'</select>'+
													'<input type="text" id="moban_type_bl" style="display:none" />'+
												'</td>'+
											'</tr>'+
											'<tr>'+
												'<td style="text-align:right">请选择或输入模板名称</td>'+
											'<td><input name="mingcheng" value=""/></td>'+ 
											'</tr>'+
											'<tr>'+
												'<td colspan="2">'+
													'<input type="hidden" name="muban_id" value="">'+
													'<input type="hidden" name="zhuyuan_id" value="'+current_zhuyuan_id+'">'+
													'<input type="hidden" name="yiyuan_id" value="'+yiyuan_id+'">'+
													'<input type="submit_button" id="add_bingli_shu" class="submit_button" value="增 加" />'+
													'<input type="button" id="cancel_add" class="submit_button" value="取 消" />'+
												'</td>'+
											'</tr>'+
										'</table></form>',
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
						$("#bingli_type").change(function(){
							var muban_bingli_type = $("#bingli_type").val();
							if(muban_bingli_type=="手动输入") {
								$("#moban_type_bl").show();
							}else {
								$("#moban_type_bl").hide();
							}
						});
						muban_bingli_type = $("#bingli_type").val();
						$("#add_bingli_shu").click(function(){
							var muban_bingli_type = $("#bingli_type").val();
							var moban_type = $("#moban_type_bl").val();
							if(muban_bingli_type=="手动输入") {
								if(moban_type==null || moban_type=="") {
									alert("请输入模板的文档类型！");
									return;
								}else {
									$("#bingli_type").prepend("<option value='"+moban_type+"'>"+moban_type+"</option>"); 
									$("#bingli_type").val(moban_type);
								}
							}
							//提交表单
							$(".add_form").ajaxSubmit();
							
							//刷新页面
							current_patient_xingming=encodeURI(current_patient_xingming);
							current_patient_xingming=encodeURI(current_patient_xingming);
							$("#conframe").attr("src","http://"+server_url+"/web_emr/ZhuyuanYishi/Patient/showPatientZhuyuanDetail/zhuyuan_id/"+current_zhixing_id+"/yiyuan_id/"+current_bingli_yiyuan_id+"/xingming/"+current_patient_xingming);
							//关闭弹出框
							art.dialog.list['zengjiamuban_dialog'].close();
						});
					}
				});
			}
			//模板树
			if(current_content_url.indexOf("showMubanDetail") != -1 || current_content_url.indexOf("showMubanBingli") != -1)
			{
				current_muban_mingcheng=decodeURI(current_muban_mingcheng);
				current_muban_leixing=decodeURI(current_muban_leixing);
				current_muban_kebie=decodeURI(current_muban_kebie);
				current_muban_bingli_type=decodeURI(current_muban_bingli_type);
				art.dialog({
					id:"zengjiamuban_dialog",
					title:"增加模板",
					content:'<form class="add_form_muban" method="post" action="/web_emr/Home/MubanGuanli/addMubanBingli">'+
										'<table>'+
											'<tr>'+
												'<td style="text-align:right">请输入模板名称：</td>'+
												'<td><input disabled="disabled" name="mingcheng" id="mingcheng" value="'+current_muban_mingcheng+'"/></td>'+
											'</tr>'+
											'<tr>'+
												'<td style="text-align:right">请选择所增加的模板的类型：</td>'+
												'<td>'+
													'<select name="muban_leixing"  id="muban_leixing_name" class="select_name" disabled="disabled">'+
														'<option value="'+current_muban_leixing+'">'+current_muban_leixing+'</option>'+
														'<option value="公共模板">公共模板</option>'+
														'<option value="科室模板">科室模板</option>'+
														'<option value="个人模板">个人模板</option>'+
													'</select>'+
												'</td>'+
											'</tr>'+
											'<tr>'+
												'<td style="text-align:right">请选择模板的科别：</td>'+
												'<td>'+
													'<select name="muban_kebie" id="muban_kebie_name" class="select_name" disabled="disabled">'+
														'<option value="'+current_muban_kebie+'">'+current_muban_kebie+'</option>'+
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
												'<td style="text-align:right">请选择模板的文档类型：</td>'+
												'<td>'+
													'<select name="muban_bingli_type"  id="muban_bingli_type" class="select_name">'+
														'<option value="'+current_muban_bingli_type+'">'+current_muban_bingli_type+'</option>'+
														bingli_types+ '<option value="手动输入">手动输入</option>' +
													'</select>'+
													'<input type="text" id="moban_type" style="display:none" />'+
												'</td>'+
											'</tr>'+
											'<tr>'+
												'<td style="text-align:right">请输入模板别称(维语名字等)：</td>'+
												'<td><input id="second_mingcheng" name="second_mingcheng" value=""/></td>'+
											'</tr>'+
											'<tr>'+
												'<td style="text-align:right"><input type="checkbox" id="if_user_default_format" name="if_user_default_format" value="true" >是否使用通用模板格式：</input></td>'+
												'<td></td>'+
											'</tr>'+
											'<tr>'+
												'<td colspan="2">'+
													'<input type="hidden" name="muban_id" value="'+current_muban_id+'">'+
													'<input type="hidden" name="yiyuan_id" value="'+yiyuan_id+'">'+
													'<input type="hidden" name="mingcheng" value="'+current_muban_mingcheng+'">'+
													'<input type="hidden" name="muban_leixing" value="'+current_muban_leixing+'">'+
													'<input type="hidden" name="muban_kebie" value="'+current_muban_kebie+'">'+
													'<input type="submit_button" id="cancel_add_muban" class="submit_button" value="增 加" />'+
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
						$("#muban_bingli_type").change(function(){
							var muban_bingli_type = $("#muban_bingli_type").val();
							if(muban_bingli_type=="手动输入") {
								$("#moban_type").show();
							}else {
								$("#moban_type").hide();
							}
						});
						$("#cancel_add_muban").click(function(){
							var muban_leixing_name = $("#muban_leixing_name").val();
							var muban_kebie_name = $("#muban_kebie_name").val();
							var muban_bingli_type = $("#muban_bingli_type").val();
							var second_mingcheng = $("#second_mingcheng").val();
							var if_user_default_format = $("#if_user_default_format").val();
							var mingcheng = $("#mingcheng").val();
							var moban_type = $("#moban_type").val();
							if(muban_bingli_type=="手动输入") {
								if(moban_type==null || moban_type=="") {
									alert("请输入模板的文档类型！");
									return;
								}else {
									$("#muban_bingli_type").prepend("<option value='"+moban_type+"'>"+moban_type+"</option>"); 
									$("#muban_bingli_type").val(moban_type);
								}
							}
						
							//进行ajax提交
							$(".add_form_muban").ajaxSubmit();
							//提交完成之后进行刷新页面
									mingcheng=encodeURI(mingcheng);
								mingcheng=encodeURI(mingcheng);
								muban_kebie_name=encodeURI(muban_kebie_name);
								muban_kebie_name=encodeURI(muban_kebie_name);
								muban_bingli_type=encodeURI(muban_bingli_type);
								muban_bingli_type=encodeURI(muban_bingli_type);
								muban_leixing_name=encodeURI(muban_leixing_name);
								muban_leixing_name=encodeURI(muban_leixing_name);
								$("#conframe").attr("src","http://"+server_url+"/web_emr/MubanGuanli/showMubanDetailGroup/muban_id/"+current_muban_id+"/mingcheng/"+mingcheng+"/muban_leixing/"+muban_leixing_name+"/muban_kebie/"+muban_kebie_name+"/muban_bingli_type/"+muban_bingli_type);
							//关闭弹出框
							art.dialog.list['zengjiamuban_dialog'].close();
							
						});
						$("#cancel_add").click(function(){
							art.dialog.list['zengjiamuban_dialog'].close();
						});
					}
				});
			}
		});
		$("#bingli_tree li").live("mouseenter",function(e){
			$(this).children().next().next().addClass("img_button_del");
			$(this).children().next().next().next().addClass("img_button_copy");
			$(this).find(".delete_bingli_tree").css("display","block");
			$(this).find(".copy_bingli_tree").css("display","block");
		
		});
		$("#bingli_tree li").live("mouseleave",function(){
			$(this).find(".delete_bingli_tree").css("display","none");
			$(this).find(".copy_bingli_tree").css("display","none");
		});
		$(".delete_bingli_tree").live("click",function(e){
			var current_node = $(this);

			var yiyuan_id = $(this).attr("yiyuan_id");
			var bingli_type = $(this).attr("bingli_type");
			var zhuyuan_id = $(this).attr("zhuyuan_id");
			var del_name = $(this).attr("del_name");
			var muban_id = $(this).attr("muban_id");
			if(confirm("确认删除吗?"))
			{	
			
				$.post("http://"+server_url+"/web_emr/Home/BingliEditor/deleteOneMubanTree", {del_name:del_name,yiyuan_id:yiyuan_id,bingli_type:bingli_type,zhuyuan_id:zhuyuan_id,muban_id:muban_id}, function(data){
					data=data.replace(/[\r\n]/g,"");  
					data=data.replace(/\ +/g,""); 
					
					if(data=='success')
					{
						alert("删除成功");
						$(current_node).parent().css("display","none");
					}
					else
					{
						alert("删除失败，请尝试联系管理员！");
					}
				});
			}
		});

		$(".copy_bingli_tree").live("click",function(){
			var muban_kebie = current_muban_kebie;
			muban_kebie=decodeURI(muban_kebie);
			var muban_bingli_type = current_muban_bingli_type;
			muban_bingli_type=decodeURI(muban_bingli_type);
			
			var muban_id = current_muban_id;
			art.dialog({
				id:"fuzhimuban_dialog",
				title:"复制模板",
				content:'<form class="copy_form" method="post" action="/web_emr/Home/MubanGuanli/fuzhiMubanBingli">'+
									'<table>'+
										'<tr>'+
											'<td style="text-align:right">请输入新模板名称：</td>'+
											'<td><input id="mingcheng" name="mingcheng" value=""/></td>'+
										'</tr>'+
										'<tr>'+
											'<td style="text-align:right">请选择所增加的模板的类型：</td>'+
											'<td>'+
												'<select id="muban_leixing" name="muban_leixing"  class="select_name">'+
													'<option value="科室模板">科室模板</option>'+
													'<option value="个人模板">个人模板</option>'+
												'</select>'+
											'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style="text-align:right">请选择模板的科别：</td>'+
											'<td>'+
												'<select id="muban_kebie" name="muban_kebie"  class="select_name">'+
													'<option value="'+muban_kebie+'">'+muban_kebie+'</option>'+
												'</select>'+
											'</td>'+
										'</tr>'+
									
										'<input id="muban_bingli_type" type="hidden" name="muban_bingli_type" value="'+muban_bingli_type+'"/>'+
										'<tr>'+
											'<td style="text-align:right">请输入模板别称(维语名字等)：</td>'+
											'<td><input id="second_mingcheng" name="second_mingcheng" value=""/></td>'+
										'</tr>'+
										'<tr>'+
											'<td colspan="2">'+
												'<input type="hidden" name="yiyuan_id" value="'+yiyuan_id+'">'+
												'<input type="hidden" name="muban_id" value="'+muban_id+'">'+
												'<input type="button" class="submit_button" id="fuzhi_muban_submit" value="复 制" />'+
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

					$("#fuzhi_muban_submit").click(function(){
							var muban_leixing = $("#muban_leixing").val();
							var muban_kebie = $("#muban_kebie").val();
							var muban_bingli_type = $("#muban_bingli_type").val();
							var second_mingcheng = $("#second_mingcheng").val();
							var mingcheng = $("#mingcheng").val();

							//进行ajax提交
							$.post("/web_emr/Home/MubanGuanli/fuzhiMubanBingliAjax",{yiyuan_id:yiyuan_id,muban_leixing:muban_leixing,muban_bingli_type:muban_bingli_type,muban_kebie:muban_kebie,mingcheng:mingcheng,muban_id:muban_id,second_mingcheng:second_mingcheng},function(data){
							data=data.replace(/[\r\n]/g,"");  
							data=data.replace(/\ +/g,""); 
					
								if(data == "success")
								{
									alert("复制成功！");
								}
								else
								{
									alert("复制失败，请尝试联系管理员！");
								}
							});
							
							//提交完成之后进行刷新页面
							// $("#conframe").attr("src","http://"+server_url+"/web_emr/MubanGuanli/showMubanDetail/muban_id/"+current_muban_id+"/mingcheng/"+mingcheng+"/muban_leixing/"+muban_leixing_name+"/muban_kebie/"+muban_kebie_name+"/muban_bingli_type/"+muban_bingli_type);
							
							//关闭弹出框
							art.dialog.list['fuzhimuban_dialog'].close();
							
						});

					$("#cancel_add").click(function(){
						art.dialog.list['fuzhimuban_dialog'].close();
					});
				}
			});
		});
		
	})
</script>
</body>
</html>