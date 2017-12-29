<meta charset="utf-8">
<?php
	
	require("./lib/init.php");
	if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
 $user=reuser($_COOKIE['name']);
	/**
	* 分页设置
	*/
	 $count="select count(*) as num 
	 from cat where user_id=$user[user_id]";
	 $datas=partPage($count,8);//每页的信息条数
	 $lim=$datas['lim'];
	 $page=$datas['page'];
    
	$sql="select * from cat where user_id= $user[user_id] ".$lim;
	$data=mSelAll($sql);
	require('./view/admin/catlist.html');
?>