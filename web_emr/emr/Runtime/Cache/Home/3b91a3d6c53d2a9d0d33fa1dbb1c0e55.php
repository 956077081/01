<?php if (!defined('THINK_PATH')) exit();?>
<html >
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/list_view.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
	<title>模板列表</title>
</head>
<body>
	<div class="list_title">
		<div class="list_title_span" style="margin-right:10px;">模板列表</div>
		<div class="search_menu">
			<input type="button" class="search_button" id="tianjia_muban" value="添加模板"/>
		</div>
	</div>
	
	<form method="get" action="/web_emr/Home/MubanGuanli/showMubanList">
		<table border="0" cellpadding="0" cellspacing="0" class="title_table">
			<tr>
				<td width="20%">名称</td>
				<td width="10%">模板类型</td>
				<td width="10%">模板科别</td>
				<td width="10%">所属医生</td>
				<td width="10%">所属科室</td>
				<td width="15%">所属医院</td>
				<td width="10%">操作</td>
			</tr>
			<tr>
				<td width="20%">
					<input type="text" name="mingcheng" value="<?php echo ($mingcheng); ?>"/>
				</td>
				<td width="10%">
					<select name="muban_leixing" id="">
						<?php if($muban_leixing != ''): ?><option value="<?php echo ($muban_leixing); ?>"><?php echo ($muban_leixing); ?></option><?php endif; ?>
						<option value="">全部</option>
						<option value="公共模板">公共模板</option>
						<option value="科室模板">科室模板</option>
						<option value="个人模板">个人模板</option>
					</select>
				</td>
				<td width="10%">
					<select name="muban_kebie" id="">
						<?php if($muban_kebie != ''): ?><option value="<?php echo ($muban_kebie); ?>"><?php echo ($muban_kebie); ?></option><?php endif; ?>
						<option value="">全部</option>
						<option value="内科">内科</option>
						<option value="外科">外科</option>
						<option value="妇科">妇科</option>
						<option value="儿科">儿科</option>
						<option value="中医科">中医科</option>
					</select>
				</td>
				<td width="10%">
					<input type="text" name="suoshu_yisheng" value="<?php echo ($suoshu_yisheng); ?>" />
				</td>
				<td width="10%">
					<input type="text" name="suoshu_keshi" value="<?php echo ($suoshu_keshi); ?>" />
				</td>
				<td width="15%">
					<input type="text" name="suoshu_yiyuan" value="<?php echo ($suoshu_yiyuan); ?>" />
				</td>
				<td width="10%">
					<input type="submit" class="search_button" value="筛选">
				</td>
			</tr>
		</table>
	</form>

	<table border="0" cellpadding="0" cellspacing="0" class="content_table">
		<?php if(is_array($search_result)): $i = 0; $__LIST__ = $search_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$search_result): $mod = ($i % 2 );++$i;?><tr class="list_content"   zhuyuan_id="<?php echo ($search_result["zhuyuan_id"]); ?>" href="/web_emr/Home/MubanGuanli/showMubanDetailGroup/muban_id/<?php echo ($search_result["muban_id"]); ?>/mingcheng/<?php echo ($search_result["mingcheng"]); ?>/muban_leixing/<?php echo ($search_result["muban_leixing"]); ?>/muban_kebie/<?php echo ($search_result["muban_kebie"]); ?>/muban_bingli_type/<?php echo ($search_result["muban_bingli_type"]); ?>"> 
					
					<td width="20%"><?php echo ($search_result["mingcheng"]); ?></td>
					<td width="10%"><?php echo ($search_result["muban_leixing"]); ?></td>
					<td width="10%"><?php echo ($search_result["muban_kebie"]); ?></td>
					<td width="10%"><?php echo ($search_result["suoshu_user_name"]); ?></td>
					<td width="10%"><?php echo ($search_result["suoshu_department_name"]); ?></td>
					<td width="15%"><?php echo ($search_result["suoshu_yiyuan_name"]); ?></td>
					<td width="10%">
						<?php if($search_result["suoshu_user_id"] == $_SESSION['user_id'] or $_SESSION['user_type'] == '管理员' ): ?><input muban_id="<?php echo ($search_result["muban_id"]); ?>" type="button" class="search_button delete_group" value="删除"><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</table>
		
	<table id="nav_table" class="title_table">
		<tr class="without_event">
			<td style=" width:400px;">
				<input type="button" id="first_page" value="首页" class="button_medium"/>&nbsp;&nbsp;
				<?php if($current_page_number > 1): ?><input type="button" id="previous_page" value="前页" class="button_medium"/>&nbsp;&nbsp;<?php endif; ?>
				第
				<select name="menu1" onChange="MM_jumpMenu('self',this,0)" target="_blank">
				<?php if($page != 1): ?><option value="/web_emr/Home/MubanGuanli/showMubanList/page/<?php echo ($current_page_number); echo ($url_params); ?>">
							<?php echo ($current_page_number); ?>
						</option><?php endif; ?>
				<?php
 for ($i = 1; $i <= $total_page_number; $i++) { ?>
						 	<option value="/web_emr/Home/MubanGuanli/showMubanList/page/<?php echo $i; echo ($url_params); ?>">
						 		<?php echo $i; ?>
						 	</option>;
					<?php
 } ?>
				</select>
				页&nbsp;&nbsp;
				
				<?php if(($current_page_number < $total_page_number)): ?><input type="button" id="next_page" value="后页" class="button_medium"/>&nbsp;&nbsp;<?php endif; ?>
				<input type="button" id="last_page" value="末页" class="button_medium"/>&nbsp;&nbsp;
			</td>
			<td style=" width:200px;">
				每页显示<?php echo ($one_page_amount); ?>条/共<?php echo ($total_amount); ?>条
			</td>
		</tr>
	</table>
</body>
<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
<script type="text/javascript" src="/web_emr/Public/js/jquery.form.js"></script>
<script type="text/javascript" src="/web_emr/Public/js/tiantan_ui.js"></script>
<script language="javascript" type="text/javascript" src="/web_emr/Public/js/artDialog/artDialog.js?skin=aero" ></script>
<script type="text/javascript">
   
 
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	yiyuan_id = "<?php echo (session('yiyuan_id')); ?>";
	var muban_bingli_type = "<?php echo (C("muban_bingli_type")); ?>";
	function getSelects(types) {
		var st ='<option value="批量添加">批量添加</option>';
		if(types==null || types=="") {
		}else {
			var fs = types.split(",");
			for(var i=0; i<fs.length; i++) {
				st += '<option value="'+fs[i]+'">'+fs[i]+'</option>';
			}
		}
		return st;
	}
		
	var bingli_types = getSelects(muban_bingli_type);
	//关闭加载条
	try{parent.loadingEnd();}catch(ex){}

	// 页面跳转
	function MM_jumpMenu(targ,selObj,restore)
	{ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	url_params=encodeURI("<?php echo ($url_params); ?>");
	url_params=encodeURI(url_params);
	if(url_params=="undefined"){
		url_params="";
	}
	$("#first_page").click(function(){
		window.location.href="/web_emr/Home/MubanGuanli/showMubanList/page/1"+url_params;

	});
	$("#previous_page").click(function(){
		window.location.href="/web_emr/Home/MubanGuanli/showMubanList/page/<?php echo ($current_page_number-1); ?>"+url_params;
	});
	$("#next_page").click(function(){
		window.location.href="/web_emr/Home/MubanGuanli/showMubanList/page/<?php echo ($current_page_number+1); ?>"+url_params;
	});
	$("#last_page").click(function(){
		window.location.href="/web_emr/Home/MubanGuanli/showMubanList/page/<?php echo ($total_page_number); ?>"+url_params;
	});
	
	$("#tianjia_muban").click(function(){
		var gonggong_muban_option = "";
		if("<?php echo (session('user_type')); ?>" == "管理员")
		{
			gonggong_muban_option += '<option value="公共模板">公共模板</option>';
		}
		art.dialog({
			id:"zengjiamuban_dialog",
			title:"增加模板",
			content:'<form class="add_form_muban" method="post" action="/web_emr/Home/MubanGuanli/addMubanBingli">'+
								'<table>'+
									'<tr>'+
										'<td style="text-align:right">请输入模板名称：</td>'+
										'<td><input name="mingcheng" value=""/></td>'+
									'</tr>'+
									'<tr>'+
										'<td style="text-align:right">请选择所增加的模板的类型：</td>'+
										'<td>'+
											'<select name="muban_leixing"  class="select_name">'+
												gonggong_muban_option+
												'<option value="科室模板">科室模板</option>'+
												'<option value="个人模板">个人模板</option>'+
											'</select>'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td style="text-align:right">请选择模板的科别：</td>'+
										'<td>'+
											'<select name="muban_kebie"  class="select_name">'+
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
											'<select id="muban_bingli_type" name="muban_bingli_type"  class="select_name">'+
												bingli_types+ '<option value="手动输入">手动输入</option>' +
											'</select>'+
											'<input type="text" id="moban_type" style="display:none" />'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td style="text-align:right">请输入模板别称(维语名字等)：</td>'+
										'<td><input name="second_mingcheng" value=""/></td>'+
									'</tr>'+
									'<tr>'+
										'<td style="text-align:right"><input type="checkbox" name="if_user_default_format" value="true" >是否使用通用模板格式：</input></td>'+
										'<td></td>'+
									'</tr>'+
									'<tr>'+
										'<td colspan="2">'+
											'<input type="hidden" name="yiyuan_id" value="'+yiyuan_id+'">'+
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
				$("body").eq(0).css("overflow","hidden");
				$("#cancel_add").click(function(){
					art.dialog.list['zengjiamuban_dialog'].close();
				});
				$("#cancel_add_muban").click(function(){
					var muban_bingli_type = $("#muban_bingli_type").val();
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
					$(".add_form_muban").submit();
				});
			}
		});
	});
	
	
	$(function(){
		//鼠标经过样式变化处
		$(".list_content").hover(
			function () {
			
				$(this).addClass("tr_hover");
			},
			function () {
				$(this).removeClass("tr_hover");
			}
		);
		//超链接无虚线框处
		$("a").focus(
			function () {
				$(this).blur(); //得到焦点与失去焦点效果一致
			}
		);
	})

	$(".list_content").click(function (e) {
	
			if(e.target.nodeName != "INPUT")
			{
				parent.last_conframe_content = window.location.href;
				s=$(this).attr("href");
				s=encodeURI(s);
				s=encodeURI(s);
				window.location.href=s;
				parent.current_conframe_content = $(this).attr("href");
				parent.current_zhuyuan_id = $(this).attr("zhuyuan_id");
			}
			else
			{
				if(confirm("确定要删除吗？"))
				{
					//删除该组模板
					var muban_id = $(this).find("input").attr("muban_id");
					window.location.href =  "http://"+server_url+"/web_emr/Home/MubanGuanli/deleteGroup/muban_id/"+muban_id;
				}
			}
			
	});
</script>

</html>