<?php 
require("./lib/init.php");

$er='';//错误信息返回

if(!empty($_POST)){
	$username=trim($_POST['username']);
	$password=trim($_POST['password']);
	if(empty($username)||empty($password)){
	    $er="账号或密码不能为空";
	}else{
		//将密码转md5 格式加salt 的暗文
		$sql="select salt from user where name='$username'";
		$salt=mSelRow($sql)['salt'];
		$word=md5($password.$salt);
		$sql="select name,password from user where name='$username' and password='$word' ";
		 $user=mSelRow($sql);
	   	if($user){//登录成功 添加登录时间
		   	$uptime=time();
		   	//加密的COOKIE码
		   	//的相当于COOKIE登录的账号和密码
		   	setcookie('name',$username);
		   	setcookie('code',Codemd5($username));
		   	$uplastime=" update user set lastlogin=$uptime where name='$username'";
		   	mQuery($uplastime);
		 	header("Location:index.php");
		 	exit();
	    }else{
	    	 $er="账号或密码错误";
	    }
	}

}
require('./view/front/login.html');
 ?>