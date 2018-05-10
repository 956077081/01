<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/list_view.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
	<title>患者列表</title>
</head>
<body>
	<div class="list_title">
		<div class="list_title_span" style="margin-right:10px;">ئاغرىق، كېسەل جەدۋەل(患者列表)</div>
	</div>
	<table border="0" cellpadding="0" cellspacing="0" class="title_table">
	<tr>
		<td width="20%">كېسەلخانىدا يېتىپ نومۇر<br />门诊号</td>
		<td width="20%">كېسەل كارىۋىتى نومۇر<br />挂号时间</td>
		<td width="30%">ئاغرىق، كېسەل ئىسىم ۋە فامىلە<br />患者姓名</td>
		<td width="10%">ياش<br />年龄</td>
		<td width="10%">جىنسىي پەرق، جىنس<br />性别</td>
		</td>
	</tr>
	</table>

	<table border="0" cellpadding="0" cellspacing="0" class="content_table">
		<?php if(is_array($search_result)): $i = 0; $__LIST__ = $search_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$search_result): $mod = ($i % 2 );++$i;?><tr class="list_content"  zhuyuan_id="<?php echo ($search_result["zhuyuan_id"]); ?>" href="/web_emr/MenzhenYishi/Bingli/biaozhunBingli/menzhen_id/<?php echo ($search_result["zhuyuan_id"]); ?>">
						<td style="width:20%"><?php echo ($search_result["menzhen_id"]); ?></td>
						<td style="width:20%"><?php echo ($search_result["guahao_time"]); ?></td>
						<td style="width:30%"><?php echo ($search_result["xingming"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["nianling"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["xingbie"]); ?></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</table>
		
	<table id="nav_table" class="title_table">
		<tr class="without_event">
			<td style=" width:400px;">
				<input type="button" id="first_page" value="首页" class="search_button"/>&nbsp;&nbsp;
				<?php if($page > 1): ?><input type="button" id="previous_page" value="前页" class="search_button"/>&nbsp;&nbsp;<?php endif; ?>
				第
				<select name="menu1" onchange="MM_jumpMenu('self',this,0)" target="_blank">
				<?php if($page != 1): ?><option value="/web_emr/MenzhenYishi/Patient/showPatientList/page/<?php echo ($current_page_number); ?>"><?php echo ($current_page_number); ?></option><?php endif; ?>
				<?php
 for ($i = 1; $i <= $total_page_number; $i++) { ?>
							 	<option value="/web_emr/MenzhenYishi/Patient/showPatientList/page/<?php echo $i; ?>"><?php echo $i; ?></option>;
					<?php
 } ?>
				</select>
				页&nbsp;&nbsp;
				
				<?php if(($current_page_number < $total_page_number)): ?><input type="button" id="next_page" value="后页" class="search_button"/>&nbsp;&nbsp;<?php endif; ?>
				<input type="button" id="last_page" value="末页" class="search_button"/>&nbsp;&nbsp;
			</td>
			<td style=" width:200px;">
				每页显示<?php echo ($one_page_amount); ?>条/共<?php echo ($total_amount); ?>条
			</td>
		</tr>
	</table>
<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
<script type="text/javascript">
	server_url = "<?php echo (C("WEB_HOST")); ?>";

	//关闭加载条
	try{parent.loadingEnd();}catch(ex){}

	// 页面跳转
	function MM_jumpMenu(targ,selObj,restore)
	{ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	$("#first_page").click(function(){
		window.location.href="/web_emr/MenzhenYishi/Patient/showPatientList<?php echo ($lujin); ?>";
	});
	$("#previous_page").click(function(){
		window.location.href="/web_emr/MenzhenYishi/Patient/showPatientList<?php echo ($lujin); ?>/page/<?php echo ($page-1); ?>";
	});
	$("#next_page").click(function(){
		window.location.href="/web_emr/MenzhenYishi/Patient/showPatientList<?php echo ($lujin); ?>/page/<?php echo ($page+1); ?>";
	});
	$("#last_page").click(function(){
		window.location.href="/web_emr/MenzhenYishi/Patient/showPatientList<?php echo ($lujin); ?>/page/<?php echo ($yeshu); ?>";
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

	$(".list_content").click(function () {
			parent.last_conframe_content = window.location.href;
			window.location.href = $(this).attr("href");
			parent.current_conframe_content = $(this).attr("href");
			parent.current_zhuyuan_id = $(this).attr("zhuyuan_id");
	});
</script>
</body>
</html>