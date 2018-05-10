<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<title>辅助检查列表</title>
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/themes/base/jquery.ui.all.css"/>
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/list_view.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/tiantan_ui.css" media="all" />
	<style type="text/css">
		/*隐藏已取消的检验*/
		#list_table_canceled {
			display: none;
		}
		#table_title_canceled:hover {
			background-color: #DFD;
			cursor: pointer;
		}
	</style>
</head>

<body>
<form method="get" action="/web_emr/ZhuyuanYishi/Jiancha/showList/" id="format">
	<div class="list_title" style='width:100%;'>
		<?php echo ($view_info); ?>
	</div>
<table border="0" cellpadding="0" cellspacing="0" class="title_table">
		<tr>
			<td width="30%">检查时间</td>
			<td width="50%">检查名称</td>
			<td width="20%"></td>
		</tr>
		<tr>
			<td>
				<input type="text" name="jiancha_time" value="<?php echo ($jiancha_time); ?>" class="search_input_type" />
			</td>
			<td>
				<input type="text" name="jiancha_mingcheng" value="<?php echo ($jiancha_mingcheng); ?>" class="search_input_type" />
			</td>
			<td>
				<input type="hidden" name="zhuyuan_id" value="<?php echo ($zhuyuan_id); ?>"/>
				<input type="submit" value="筛选" class="search_button" style="margin-right:20px; float:right;"/>
			</td>
		</tr>
</table>
</form>
<table border="0" cellpadding="0" cellspacing="0" class="content_table_custome_width">
	<?php if(is_array($jiancha_result)): $i = 0; $__LIST__ = $jiancha_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jiancha_result): $mod = ($i % 2 );++$i;?><tr class="list_content" href="/web_emr/ZhuyuanYishi/Jiancha/showReport/jiancha_id/<?php echo ($jiancha_result["id"]); ?>">
				<td width="30%"><?php echo ($jiancha_result["jiancha_time"]); ?></td>
				<td width="70%"><?php echo ($jiancha_result["jiancha_mingcheng"]); ?></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<table id="nav_table" class="title_table">
	<tr class="without_event">
		<td width="60%">
			<input type="button" id="first_page" value="首页" class="search_button"/>&nbsp;&nbsp;
			<?php if($page_number > 1): ?><input type="button" id="previous_page" value="前页" class="search_button"/>&nbsp;&nbsp;<?php endif; ?>
			第
			<select name="menu1" onchange="MM_jumpMenu('self',this,0)" target="_blank">
			<?php if($page_number != 1): ?><option value="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>/page_number/<?php echo ($page_number); ?>"><?php echo ($page_number); ?></option><?php endif; ?>
			<?php
 for ($i = 1; $i <= $page_amount; $i++) { ?>
					<option value="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>/page_number/<?php echo $i; ?>"><?php echo $i; ?></option>;
				<?php
 } ?>
			</select>
			页&nbsp;&nbsp;
			
			<?php if(($page_number < $page_amount)): ?><input type="button" id="next_page" value="后页" class="search_button"/>&nbsp;&nbsp;<?php endif; ?>
			<input type="button" id="last_page" value="末页" class="search_button"/>&nbsp;&nbsp;
		</td>
		<td colspan="2">
			每页显示<?php echo ($one_page_amount); ?>条/共<?php echo ($total_amount); ?>条
		</td>
	</tr>
</table>
<script type="text/javascript" src="/web_emr/Public/js/jquery-1.7.2.js" ></script>
<script>
	server_url = "<?php echo (C("WEB_HOST")); ?>";
	user_number = "<?php echo ($_SESSION["user_number"]); ?>";
	user_name = "<?php echo ($_SESSION["user_name"]); ?>";
	$(function(){
		try
		{parent.loadingEnd();}
		catch(ex)
		{}
		
		//鼠标经过样式变化处
		$(".list_content").hover(
			function () {
				$(this).addClass("tr_hover");
			},
			function () {
				$(this).removeClass("tr_hover");
			}
		)
		//超链接无虚线框处
		$("a").focus(
			function () {
				$(this).blur(); //得到焦点与失去焦点效果一致
			}
		)
	})
	
	$(".list_content").click(function (a) {
			window.location.href = $(this).attr("href");
	})
	
	$("#jiancha_keshi_name").change(function(){
		var zhi = $(this).val();
		if(zhi != '')
		{
			window.location.href="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>/jiancha_keshi_name/"+zhi;
		}
		else
		{
			window.location.href="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>";
		}
	});
	
	function MM_jumpMenu(targ,selObj,restore)
	{ //v3.0
	  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	  if (restore) selObj.selectedIndex=0;
	}
	$("#first_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>";
	});
	$("#previous_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>/page_number/<?php echo ($page_number-1); ?>";
	});
	$("#next_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>/page_number/<?php echo ($page_number+1); ?>";
	});
	$("#last_page").click(function(){
		window.location.href="/web_emr/ZhuyuanYishi/Jiancha/showList<?php echo ($url_condition); ?>/page_number/<?php echo ($page_amount); ?>";
	});
</script>

</body>
</html>