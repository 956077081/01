<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<title>长期医嘱管理</title>

	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/yizhuguanli_view.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
	<link type="text/css" rel="stylesheet" href='/web_emr/Public/css/printer_control.css'/> 

	<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
	<style type="text/css">
		#nav_table select {width:auto;}
		.title_table {
			width:100%;
			line-height:2;
			border:none;
			color:#06C;
			font-size:16px;
			font-weight:bold;
			border-collapse:collapse;
			background-color:#d8e5ff;
		}
		.title_table tr td {
			padding:5px 0 5px 0;
			text-align:center;
			vertical-align:middle;
			color:#333;
			width:14%;
		}
		.page{width:100%}
		.fix_right {width:auto;float:right;line-height:20px;}
		.float_right {width:auto;float:right;}
	</style>
</head>

<body>
	<div class="top_piaofu" style="position:absolute" >
		<input type="button" name="yizhu_type_changyi_kaishizhixing" class="selected_button" value="长期医嘱" data-role="none"/>
		<input type="button" name="yizhu_type_linshi" class="quick_menu_button" value="临时医嘱" data-role="none"/>
		<span style="float:right;">
			<input type="button" name="yizhu_type_changqi_tingzhizhixing" class="quick_menu_button" value="停止执行"  data-role="none"/>
			<input type="button" name="yizhu_type_changqi_kaishizhixing" class="quick_menu_button" value="开始执行"  data-role="none"/>
			<input type="button" name="yizhu_type_changqi_all" class="quick_menu_button" value="所有医嘱"  data-role="none"/>
		</span>
	</div>
	<div class="page">
	<div class="head_title">
		<div class="main_title"><?php echo (C("hospital_name")); ?></div>
		<div class="sub_yizhu_title">长期医嘱单</div>
	</div>
	<table border="0" cellpadding="0" cellspacing="0" class="head_table">
		<tr>
			<td width="7%" class="info_title">姓名:</td>
			<td width="7%" class="info_area"><?php echo ($zhuyuan_basic_info["xingming"]); ?></td>
			<td width="7%" class="info_title">性别:</td>
			<td width="4%" class="info_area"><?php echo ($zhuyuan_basic_info["xingbie"]); ?></td>
			<td width="7%" class="info_title">年龄:</td>
			<td width="7%" class="info_area"><?php echo ($zhuyuan_basic_info["nianling"]); ?></td>
			<td width="7%" class="info_title">科室:</td>
			<td width="12%" class="info_area"><?php echo ($zhuyuan_basic_info["zhuyuan_department"]); ?></td>
			<td width="10%" class="info_title">病室床号:</td>
			<td width="11%" class="info_area"><?php if($muqin_bingchuang_hao !='' ): echo ($muqin_bingchuang_hao); else: echo ($bingchuang_hao); endif; ?></td>
			<td width="8%" class="info_title">住院号:</td>
			<td width="12%" class="info_area" name="zhuyuan_id"><?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?></td>
			<td class="info_title"></td>
		</tr>
	</table>
	<?php if(is_array($changqi_yizhu_fenye)): $count = 0; $__LIST__ = $changqi_yizhu_fenye;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$changqi_yizhu_fenye): $mod = ($count % 2 );++$count;?><table border="0" cellpadding="0" cellspacing="0" class="content_head_table"  <?php if($count > 1): ?>style="margin-top:25px"<?php endif; ?>>
			<tr height="25px">
				<td width="13%">起始</td>
				<td width="12%" rowspan="2" class="double_line">医生</td>
				<td width="40%" rowspan="2"  class="long_content">长期医嘱</td>
				<td width="13%">停止</td>
				<td width="12%" rowspan="2" class="double_line">医生</td>
			</tr>
			<tr height="25px">
				<td>年月日时分</td>
				<td>年月日时分</td>
			</tr>
		</table>
	
		<table border="0" cellpadding="0" cellspacing="0" class="content_table">
			<?php if(is_array($changqi_yizhu_fenye)): $i = 0; $__LIST__ = $changqi_yizhu_fenye;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$changqi_yizhu): $mod = ($i % 2 );++$i;?><tr height="50px" <?php if($changqi_yizhu['islast']=='first' or $changqi_yizhu['islast']=='middle'): ?>class="editable_partly"<?php else: ?>class="editable"<?php endif; ?> <?php if($changqi_yizhu['state']=='停止执行'): ?>style="background-color:#00AEAE"<?php endif; ?> id="<?php echo ($changqi_yizhu["id"]); ?>" name="<?php echo ($changqi_yizhu["zuhao"]); ?>">
				<td width="13%" name="start_time" <?php if($changqi_yizhu['islast']=='first' or $changqi_yizhu['islast']=='middle'): ?>class="groupmate"<?php endif; ?>><?php echo ($changqi_yizhu["start_time"]); ?></td>
				<td width="12%"  name="start_yishi_name" class="qianming <?php if($changqi_yizhu['islast']=='first' or $changqi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>"><?php echo ($changqi_yizhu["start_yishi_name"]); ?></td>
				<td width="40%" class="long_content <?php if($changqi_yizhu['islast']=='first' or $changqi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>">
					<?php echo ($changqi_yizhu["content_show"]); ?>
					<span class="hide" name="content"><?php echo ($changqi_yizhu["content"]); ?></span>
					<span class="hide" name="ciliang"><?php echo ($changqi_yizhu["ciliang"]); ?></span>
					<span class="hide" name="shiyong_danwei"><?php echo ($changqi_yizhu["shiyong_danwei"]); ?></span>
					<span class="hide" name="pinlv"><?php echo ($changqi_yizhu["pinlv"]); ?></span>
					<span class="hide" name="yongfa"><?php echo ($changqi_yizhu["yongfa"]); ?></span>
					<span class="hide" name="yongfa_type"><?php echo ($changqi_yizhu["yongfa_type"]); ?></span>
					<span class="hide" name="zhouqi"><?php echo ($changqi_yizhu["zhouqi"]); ?></span>
					<span class="hide" name="zhixing_keshi"><?php echo ($changqi_yizhu["zhixing_keshi"]); ?></span>
					<span class="hide" name="shifou_jiaji"><?php echo ($changqi_yizhu["shifou_jiaji"]); ?></span>
				</td>
				<td width="13%" name="stop_time" <?php if($changqi_yizhu['islast']=='first' or $changqi_yizhu['islast']=='middle'): ?>class="groupmate"<?php endif; ?>><?php echo ($changqi_yizhu["stop_time"]); ?></td>
				<td width="12%"  name="stop_yishi_name" class="qianming <?php if($changqi_yizhu['islast']=='first' or $changqi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>"><?php echo ($changqi_yizhu["stop_yishi_name"]); ?></td>

				<input name="shenhe_doctor_zhiyezhengshubianma" type="hidden" value="<?php echo ($changqi_yizhu["shenhe_doctor_zhiyezhengshubianma"]); ?>" />
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table><?php endforeach; endif; else: echo "" ;endif; ?>
	<table id="nav_table" class="title_table">
		<tr class="without_event">
			<td style=" width:400px;">
				<input type="button" id="first_page" value="首页" class="search_button" data-role="none"/>&nbsp;&nbsp;
				<?php if($page > 1): ?><input type="button" id="previous_page" value="前页" class="search_button" data-role="none"/>&nbsp;&nbsp;<?php endif; ?>
				第
				<select name="menu1" onchange="MM_jumpMenu('self',this,0)" target="_blank" data-role="none">
				<?php if($page != 1): ?><option value="/web_emr/Home/Yizhuguanli/showChangqi/state/<?php echo ($state); ?>/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($page); ?>"><?php echo ($page); ?></option><?php endif; ?>
				<?php
 for ($i = 1; $i <= $yeshu; $i++) { ?>
							 	<option value="/web_emr/Home/Yizhuguanli/showChangqi/state/<?php echo ($state); ?>/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo $i; ?>"><?php echo $i; ?></option>;
					<?php
 } ?>
				</select>
				页&nbsp;&nbsp;
				
				<?php if(($page < $yeshu)): ?><input type="button" id="next_page" value="后页" class="search_button" data-role="none"/>&nbsp;&nbsp;<?php endif; ?>
				<input type="button" id="last_page" value="末页" class="search_button" data-role="none"/>&nbsp;&nbsp;
			</td>
			<td style=" width:200px;">
				每页显示<?php echo ($page_tiaoshu); ?>条/共<?php echo ($zongtiaoshu); ?>条
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	$(function(){
		server_url = "<?php echo (C("WEB_HOST")); ?>";
		zhuyuan_id = "<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>";
		yiyuan_id = "<?php echo ($zhuyuan_basic_info["yiyuan_id"]); ?>";
		state = "<?php echo ($state); ?>";
		//关闭加载条
		try{parent.loadingEnd();}catch(ex){}
		
		//医嘱页面跳转:
		$("[name='yizhu_type_changyi']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showChangqi/state/开始执行/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		$("[name='yizhu_type_linshi']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		//不同医嘱类型的分类筛选：
		$("[name='yizhu_type_changqi_tingzhizhixing']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showChangqi/state/停止执行/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		$("[name='yizhu_type_changqi_kaishizhixing']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showChangqi/state/开始执行/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		$("[name='yizhu_type_changqi_all']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showChangqi/state/all/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		//设置医嘱状态按钮的高亮：
		if(state=="all"||state=="")
			state = "所有医嘱";
		$("[value='"+state+"']").attr("class","selected_button");
	});
	function MM_jumpMenu(targ,selObj,restore)
	{ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	$("#first_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showChangqi/state/<?php echo ($state); ?>/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>";
	});
	$("#previous_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showChangqi/state/<?php echo ($state); ?>/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($page-1); ?>";
	});
	$("#next_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showChangqi/state/<?php echo ($state); ?>/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($page+1); ?>";
	});
	$("#last_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showChangqi/state/<?php echo ($state); ?>/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($yeshu); ?>";
	});

</script>
</body>
</html>