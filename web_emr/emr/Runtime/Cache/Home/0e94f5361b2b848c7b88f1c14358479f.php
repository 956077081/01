<?php if (!defined('THINK_PATH')) exit();?><html >

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>操作错误-信息提示</title>
	<link rel="stylesheet" type="text/css" href="/web_emr/Public/css/system_info.css" media="all" />
</head>

<body>
	<div class="page">
		<table class="show_info">
			<tr>
				<td width="120px" class="icon"><img class="icon" src="/web_emr/Public/css/images/operation_error.gif" /></td>
				<td class="content"><?php echo ($system_info); ?></td>
			</tr>
		</table>
				  <a id="href" href="#"></a>
    <br />
    等待时间： <b id="wait">3</b>
	</div>
	</div>

</body>
   <script type="text/javascript">
   
 
   function countInstances(mainStr, subStr)
  {
    var count = 0;
    var offset = 0;
    do
    {
      offset = mainStr.indexOf(subStr, offset);
      if(offset != -1)
      {
        count++;
        offset += subStr.length;
      }
    }while(offset != -1)
    return count;
  }

	type="<?php echo ($_SERVER["HTTP_REFERER"]); ?>";
    mainurl="<?php echo ($_SESSION["server_url"]); ?>";
	num=countInstances(type,"/");

       (function(){
    var wait = document.getElementById('wait');
    var interval = setInterval(function(){
		var time = --wait.innerHTML;
		if(time <= 0) {
			location.href ="<?php echo ($_SERVER["HTTP_REFERER"]); ?>";
		    clearInterval(interval);
    };
    }, 400);
    })();
	

  
    </script>
</html>