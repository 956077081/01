<?php if (!defined('THINK_PATH')) exit();?>﻿<div class="priview_page" style="height:300px;width:700px;overflow:auto">
	<br />
	<?php if($bingli_info['bingli_type']=='住院病案首页'): ?><link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binganshouye.css" />
	<?php else: ?>
		<link type="text/css" rel="stylesheet" href="/web_emr/Public/css/binglijilu.css" /><?php endif; ?>
	<div id="bingli_content" name="content">
		<?php echo ($bingli_info["content"]); ?>
	</div>
</div>