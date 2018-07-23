<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <form name="form1" method="post" action="/index.php/Admin/Role/distribute/role_id/1">
    <dl>
    <?php if(is_array($info1)): foreach($info1 as $key=>$vo1): ?><dt>
        	<?php if(in_array($vo1['auth_id'],$role_auth_id_array)): ?><input type="checkbox" name="auth[]" value="<?php echo ($vo1["auth_id"]); ?>" checked="checked" />
            <?php else: ?>
            	<input type="checkbox" name="auth[]" value="<?php echo ($vo1["auth_id"]); ?>" /><?php endif; ?>
        	<?php echo ($vo1["auth_name"]); ?>
        </dt>
        
        <?php if(is_array($info2)): foreach($info2 as $key=>$vo2): if($vo2['auth_pid'] == $vo1['auth_id']): ?><dd>
                <?php if(in_array($vo2['auth_id'],$role_auth_id_array)): ?><input type="checkbox" name="auth[]" value="<?php echo ($vo2["auth_id"]); ?>" checked='checked' />
                <?php else: ?>
                	<input type="checkbox" name="auth[]" value="<?php echo ($vo2["auth_id"]); ?>" /><?php endif; ?>
                <?php echo ($vo2["auth_name"]); ?>
                
            </dd><?php endif; endforeach; endif; endforeach; endif; ?>
    </dl>
    <input type="submit" name="button" id="button" value="提交">
    </form>
    </body>
</html>