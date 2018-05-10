<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/list_view.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
	<title>患者列表</title>
	<style type="text/css">
		.yiguidang {
			background:#E1E1E1;
		}
	</style>
</head>
<body>
	<div class="list_title">
		<div class="list_title_span" style="margin-right:20px;">&nbsp;&nbsp;患&nbsp;&nbsp;者&nbsp;&nbsp;列&nbsp;&nbsp;表&nbsp;&nbsp;</div>
		<div class="search_menu">
			<form method="get" action="/web_emr/ZhuyuanYishi/Patient/showPatientList">
				<input type="text" name="keyword" value="<?php if($keyword=="quanbu"){echo "全部";}else {echo $keyword;}?>" style="width:200px"/>
				<input type="submit" class="search_button" value="查找"/>
				<input type="button" class="search_button" id="suoyou_patient" value="全部患者"/>
				<input type="button" class="search_button" id="my_patient" value="我的患者"/>
			</form>
		</div>
	</div>
	<table border="0" cellpadding="0" cellspacing="0" class="title_table">
	<tr>
		<td width="20%"><br />住院号</td>
		<td width="15%"><br />病床号</td>
		<td width="20%"><br />患者姓名</td>
		<td width="10%"><br />年龄</td>
		<td width="10%"><br />性别</td>
		<td width="10%"><br />诊断</td>
		<td width="5%"><br />住院状态</td>
	</tr>
	</table>

	<table border="0" cellpadding="0" cellspacing="0" class="content_table">
		<?php if(is_array($search_result)): $i = 0; $__LIST__ = $search_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$search_result): $mod = ($i % 2 );++$i; if($search_result['guidang_zhuangtai']!='未归档' and $search_result['zhuangtai']!='未归档   ' ): ?><tr class="list_content yiguidang"  zhuyuan_id="<?php echo ($search_result["zhuyuan_id"]); ?>" href="/web_emr/ZhuyuanYishi/Patient/showPatientZhuyuanDetail/zhuyuan_id/<?php echo ($search_result["zhuyuan_id"]); ?>/yiyuan_id/<?php echo ($search_result["yiyuan_id"]); ?>/xingming/<?php echo ($search_result["xingming"]); ?>/zhuangtai/<?php echo ($search_result["zhuangtai"]); ?>/special_info/<?php echo ($search_result["special_info"]); ?>">
						<td style="width:20%"><?php echo ($search_result["zhuyuan_id"]); ?></td>
						<td style="width:15%"><?php echo ($search_result["zhuyuan_chuanghao"]); ?></td>
						<td style="width:20%"><?php echo ($search_result["xingming"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["nianling"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["xingbie"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["ruyuan_zhenduan"]); ?></td>
						<td style="width:5%"><?php echo ($search_result["zhuangtai"]); ?></td>
				</tr>
			<?php elseif($search_result['zhuangtai']!='住院中' and $search_result['zhuangtai']!='住院中   '): ?>
				<tr class="list_content zhuangtai_yichuyuan"  zhuyuan_id="<?php echo ($search_result["zhuyuan_id"]); ?>" href="/web_emr/ZhuyuanYishi/Patient/showPatientZhuyuanDetail/zhuyuan_id/<?php echo ($search_result["zhuyuan_id"]); ?>/yiyuan_id/<?php echo ($search_result["yiyuan_id"]); ?>/xingming/<?php echo ($search_result["xingming"]); ?>/zhuangtai/<?php echo ($search_result["zhuangtai"]); ?>/special_info/<?php echo ($search_result["special_info"]); ?>">
						<td style="width:20%"><?php echo ($search_result["zhuyuan_id"]); ?></td>
						<td style="width:15%"><?php echo ($search_result["zhuyuan_chuanghao"]); ?></td>
						<td style="width:20%"><?php echo ($search_result["xingming"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["nianling"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["xingbie"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["ruyuan_zhenduan"]); ?></td>
						<td style="width:5%"><?php echo ($search_result["zhuangtai"]); ?></td>
				</tr>
			<?php else: ?>
				<tr class="list_content"  zhuyuan_id="<?php echo ($search_result["zhuyuan_id"]); ?>" href="/web_emr/ZhuyuanYishi/Patient/showPatientZhuyuanDetail/zhuyuan_id/<?php echo ($search_result["zhuyuan_id"]); ?>/yiyuan_id/<?php echo ($search_result["yiyuan_id"]); ?>/xingming/<?php echo ($search_result["xingming"]); ?>/zhuangtai/<?php echo ($search_result["zhuangtai"]); ?>/special_info/<?php echo ($search_result["special_info"]); ?>">
						<td style="width:20%"><?php echo ($search_result["zhuyuan_id"]); ?></td>
						<td style="width:15%"><?php echo ($search_result["zhuyuan_chuanghao"]); ?></td>
						<td style="width:20%"><?php echo ($search_result["xingming"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["nianling"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["xingbie"]); ?></td>
						<td style="width:10%"><?php echo ($search_result["ruyuan_zhenduan"]); ?></td>
						<td style="width:5%"><?php echo ($search_result["zhuangtai"]); ?></td>
				</tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</table>
		
	<table id="nav_table" class="title_table">
		<tr class="without_event">
			<td style=" width:400px;">
				<input type="button" id="first_page" value="首页" class="button_medium"/>&nbsp;&nbsp;
				<?php if($current_page_number > 1): ?><input type="button" id="previous_page" value="前页" class="button_medium"/>&nbsp;&nbsp;<?php endif; ?>
				第
				<select name="menu1" onchange="MM_jumpMenu('self',this,0)" target="_blank">
				<?php if($page != 1): ?><option value="/web_emr/ZhuyuanYishi/Patient/showPatientList/page/<?php echo ($current_page_number); ?>/keyword/<?php echo ($keyword); ?>/selection_condition/<?php echo ($selection_condition); ?>"><?php echo ($current_page_number); ?></option><?php endif; ?>
				<?php
 for ($i = 1; $i <= $total_page_number; $i++) { ?>
							 	<option value="/web_emr/ZhuyuanYishi/Patient/showPatientList/page/<?php echo $i; ?>/keyword/<?php echo ($keyword); ?>/selection_condition/<?php echo ($selection_condition); ?>"><?php echo $i; ?></option>;
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
	keyword=encodeURI("<?php echo ($keyword); ?>");
	keyword=encodeURI(keyword);
	selection_condition=encodeURI("<?php echo ($selection_condition); ?>");
	selection_condition=encodeURI(selection_condition);
	$("#first_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Patient/showPatientList/keyword/"+keyword+"/selection_condition/"+selection_condition;

	});
	$("#previous_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Patient/showPatientList/page/<?php echo ($current_page_number-1); ?>/keyword/"+keyword+"/selection_condition/"+selection_condition;;
	});
	$("#next_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Patient/showPatientList/page/<?php echo ($current_page_number+1); ?>/keyword/"+keyword+"/selection_condition/"+selection_condition;;
	});
	$("#last_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Patient/showPatientList/page/<?php echo ($total_page_number); ?>/keyword/"+keyword+"/selection_condition/"+selection_condition;
	});
	
	$("#suoyou_patient").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Patient/showPatientList/selection_condition/suoyou_patient";
	});
	
	$("#my_patient").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Patient/showPatientList";
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
		if(!$(this).is(".yiguidang"))
		{

			parent.last_conframe_content = window.location.href;
			s=encodeURI($(this).attr("href"));
			s=encodeURI(s);
			window.location.href = s;
			parent.current_conframe_content = $(this).attr("href");
			parent.current_zhuyuan_id = $(this).attr("zhuyuan_id");
		}
	});
</script>
</body>
</html>