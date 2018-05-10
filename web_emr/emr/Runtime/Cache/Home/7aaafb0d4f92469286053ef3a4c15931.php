<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<title>临时医嘱管理</title>

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

<body >
	<div class="top_piaofu" style="position:absolute">
		<input type="button" name="yizhu_type_changyi" class="quick_menu_button" value="长期医嘱"  data-role="none"/>
		<input type="button" name="yizhu_type_linshi" class="selected_button" value="临时医嘱"  data-role="none"/>
		<span style="float:right;">
			<input type="button" name="yizhu_type_linshi_zhixingwanbi" class="quick_menu_button" value="执行完毕"  data-role="none"/>
			<input type="button" name="yizhu_type_linshi_yixiada" class="quick_menu_button" value="已下达"  data-role="none"/>
			<input type="button" name="yizhu_type_linshi" class="quick_menu_button" value="所有医嘱"  data-role="none"/>
		</span>
	</div>
	<div class="page">
	<div class="head_title">
		<div class="main_title"><?php echo (C("hospital_name")); ?></div>
		<div class="sub_yizhu_title">临时医嘱单</div>
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

	<?php if(is_array($linshi_yizhu_fenye)): $count = 0; $__LIST__ = $linshi_yizhu_fenye;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$linshi_yizhu_fenye_sub): $mod = ($count % 2 );++$count;?><table border="0" cellpadding="0" cellspacing="0" class="content_head_table" <?php if($count > 1): ?>style="margin-top:25px"<?php endif; ?>>
			<tr height="50px">
				<td width="13.5%" >下达时间</td>
				<td width="45%" class="long_content">医嘱内容</td>
				<td width="9%" >医生<br />签名</td>
				<td width="13.5%" >执行时间</td>
				<td width="9%" >护士<br />签名</td>
			</tr>
		</table>
		
		<table border="0" cellpadding="0" cellspacing="0" class="content_table">
			<?php if(is_array($linshi_yizhu_fenye_sub)): $i = 0; $__LIST__ = $linshi_yizhu_fenye_sub;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$linshi_yizhu): $mod = ($i % 2 );++$i;?><tr height="50px" <?php if($linshi_yizhu['islast']=='first' or $linshi_yizhu['islast']=='middle'): ?>class="editable_partly"<?php else: ?>class="editable"<?php endif; ?> id="<?php echo ($linshi_yizhu["id"]); ?>" <?php if($linshi_yizhu['state']=='执行完毕'): ?>style="background-color:#008080"<?php endif; ?> name="<?php echo ($linshi_yizhu["zuhao"]); ?>" state="<?php echo ($linshi_yizhu["state"]); ?>">
					<td width="13.5%" <?php if($linshi_yizhu['islast']=='first' or $linshi_yizhu['islast']=='middle'): ?>class="groupmate"<?php endif; ?> name="xiada_time"><?php echo ($linshi_yizhu["xiada_time"]); ?></td>
					<td width="45%" class="long_content <?php if($linshi_yizhu['islast']=='first' or $linshi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>">
						<span name="show_content"><?php echo ($linshi_yizhu["content_show"]); echo ($linshi_yizhu["jieguo"]); ?></span>
						<span class="hide" name="content"><?php echo ($linshi_yizhu["content"]); ?></span>
						<span class="hide" name="shuliang"><?php echo ($linshi_yizhu["ciliang"]); ?></span>
						<span class="hide" name="lingshou_danwei"><?php echo ($linshi_yizhu["shiyong_danwei"]); ?></span>
						<span class="hide" name="ciliang"><?php echo ($linshi_yizhu["ciliang"]); ?></span>
						<span class="hide" name="shiyong_danwei"><?php echo ($linshi_yizhu["shiyong_danwei"]); ?></span>
						<span class="hide" name="pinlv"><?php echo ($linshi_yizhu["pinlv"]); ?></span>
						<span class="hide" name="yongfa"><?php echo ($linshi_yizhu["yongfa"]); ?></span>
						<span class="hide" name="yongfa_type"><?php echo ($linshi_yizhu["yongfa_type"]); ?></span>
						<span class="hide" name="zhixing_keshi"><?php echo ($linshi_yizhu["zhixing_keshi"]); ?></span>
						<span class="hide" name="shifou_jiaji"><?php echo ($linshi_yizhu["shifou_jiaji"]); ?></span>
					</td>
					<td width="9%"  name="xiada_yishi_name" class="qianming <?php if($linshi_yizhu['islast']=='first' or $linshi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>"><?php echo ($linshi_yizhu["xiada_yishi_name"]); ?></td>
					<td width="13.5%" name="zhixing_time" class="<?php if($linshi_yizhu['islast']=='first' or $linshi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>"><?php echo ($linshi_yizhu["zhixing_time"]); ?></td>
					<td width="9%"  name="zhixing_name" class="qianming <?php if($linshi_yizhu['islast']=='first' or $linshi_yizhu['islast']=='middle'): ?>groupmate<?php endif; ?>"><?php echo ($linshi_yizhu["zhixing_name"]); ?></td>
					<input name="doctor_zhengshubianma" type="hidden" value="<?php echo ($linshi_yizhu["doctor_zhengshubianma"]); ?>" />
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table><?php endforeach; endif; else: echo "" ;endif; ?>
	<table id="nav_table" class="title_table" data-role="none">
		<tr class="without_event">
			<td style=" width:400px;">
				<input type="button" id="first_page" value="首页" class="search_button"  data-role="none"/>&nbsp;&nbsp;
				<?php if($page > 1): ?><input type="button" id="previous_page" value="前页" class="search_button"  data-role="none"/>&nbsp;&nbsp;<?php endif; ?>
				第
				<select name="menu1" onchange="MM_jumpMenu('self',this,0)" target="_blank"  data-role="none">
				<?php if($page != 1): ?><option value="/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($page); ?>"><?php echo ($page); ?></option><?php endif; ?>
				<?php
 for ($i = 1; $i <= $yeshu; $i++) { ?>
							 	<option value="/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo $i; ?>"><?php echo $i; ?></option>;
					<?php
 } ?>
				</select>
				页&nbsp;&nbsp;
				
				<?php if(($page < $yeshu)): ?><input type="button" id="next_page" value="后页" class="search_button"  data-role="none"/>&nbsp;&nbsp;<?php endif; ?>
				<input type="button" id="last_page" value="末页" class="search_button"  data-role="none"/>&nbsp;&nbsp;
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
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showChangqi/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		$("[name='yizhu_type_linshi']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});

		$("[name='yizhu_type_linshi_zhixingwanbi']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showLinshi/state/执行完毕/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});
		$("[name='yizhu_type_linshi_yixiada']").click(function() {
			window.location.href = "http://"+server_url+"/web_emr/Home/Yizhuguanli/showLinshi/state/已下达/zhuyuan_id/"+zhuyuan_id+"/yiyuan_id/"+yiyuan_id;
		});

		//设置医嘱状态按钮的高亮：
		if(state=="all"||state=="")
			state = "所有医嘱";
		$("[value='"+state+"").attr("class","selected_button");
	});
	function MM_jumpMenu(targ,selObj,restore)
	{ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	$("#first_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>";
	});
	$("#previous_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($page-1); ?>";
	});
	$("#next_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($page+1); ?>";
	});
	$("#last_page").click(function(){
		window.location.href="/web_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/<?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?>/page/<?php echo ($yeshu); ?>";
	});
	
</script>
</body>
</html>