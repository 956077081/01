<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8.0" />
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/jianyan_baogao.css" media="all" />
	<title>检验结果报告</title>
</head>
		<div class="one_page_jianyan_jiancha_baogao" style="width:100%">
			<div class="head_title" style="width:800px">
				<div class="sub_title_report"><?php echo ($jiancha_info["jiancha_mingcheng"]); ?>检验报告单</div>
			</div>
			<ul class="jiancha_info" style="width:800px">
				<li>姓名:<span><?php echo ($zhuyuan_basic_info["xingming"]); ?></span></li>
				<li>性别:<span><?php echo ($zhuyuan_basic_info["xingbie"]); ?></span></li>
				<li>年龄:<span><?php echo ($zhuyuan_basic_info["nianling"]); ?></span></li>
				<li>类别:<span><?php echo ($jiancha_info["zhixing_type"]); ?></span></li>
				<li><?php echo ($jiancha_info["zhixing_type"]); ?>号:<span><?php echo ($zhuyuan_basic_info["zhuyuan_id"]); ?></span></li>
				
			</ul>
		<br />
		<br />
		<table id="3" border="1" cellpadding="0" cellspacing="0" class="baogao_table">
						<tr class="baogao_table_title">
							<td width="30" align="center">序号</td>
							<td width="120" align="left">代号</td>
							<td width="150" align="left">中文名称</td>
							<td width="130"	align="center">检查结果</td>
							<td align="center">提示</td>
							<td  align="center">参考值</td>
							<td	align="left">单位</td>
						</tr>
						<?php if(is_array($report_result)): $number = 0; $__LIST__ = $report_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$report_result): $mod = ($number % 2 );++$number; if($report_result['yichang_tag']=='1'): ?><tr style="background-color:#FF8080;" class="jiancha_result_one_row" result_id="<?php echo ($report_result["id"]); ?>">
							<?php else: ?>
							<tr><?php endif; ?>
								<td name="shujuxiang"><?php echo ($number); ?></td>
								<td><?php echo ($report_result["yingwen_mingcheng"]); ?></td>
								<td><?php echo ($report_result["zhongwen_mingcheng"]); ?></td>
								<td align="center"><?php echo ($report_result["jiancha_result_dingxing"]); echo ($report_result["jiancha_result_dingliang"]); ?></td>
								<td align="center"	class="jieguo_shuoming"><?php echo ($report_result["shuoming"]); ?></td>
								<td align="center"><?php echo ($report_result["cankaozhi_zonghe"]); ?></td>
								<td><?php echo ($report_result["danwei"]); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						<tr>
							<td colspan="7">备注:<?php echo ($report_result["beizhu"]); ?></td>
						</tr>
				</table>
			 <ul class="jiancha_info">
				<li>检验时间:<span><?php echo ($jiancha_info["jiancha_time"]); ?></span></li>
				<li>检验者:<span><?php echo ($jiancha_info["jiancha_zhe_name"]); ?></span></li>
				<li>核对者:<span><?php echo ($jiancha_info["hedui_zhe_name"]); ?></span></li>
			</ul>
			<div style="clear:both;"></div>
			 <div class="shuoming">*此份检查报告只对此份标本负责，在核对者签名后有效</div>
				<div class="jianyan_jiancha"></div>
	</div>
<style type="text/css">
	.one_page_jianyan_jiancha_baogao{
		margin-bottom:230px;
	}
	.jianyan_jiancha {
		width:90%;
		height:260px;
		margin:0px;
		padding:0px;
		position:fixed;
		top:260px;
		border-top:5px solid #015079;
		overflow:auto;
		z-index:100;
		display:none;
		border:2px solid #015079;
		background-color:#279bce;
		-moz-border-radius:10px;
		-webkit-border-radius:10px;
		border-radius:10px;
	}
	ul.jiancha_info{margin:0px;padding:0;width:100%}
	ul.jiancha_info li{list-style-type:none;float:left;margin-left:10px;height:30px;}
	ul.jiancha_info li span{border-bottom:1px solid #000;padding:0 5px;}
</style>
</body>
</html>