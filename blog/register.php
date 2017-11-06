<?php 
require("./lib/init.php");
$er="";//错误信息
if(!empty($_POST)){
	$nick=trim($_POST['nick']);
	$name=trim($_POST['name']);
    $email=trim($_POST['email']);
	$password=trim($_POST['password']);
	$password1=trim($_POST['password1']);
	if(empty($nick)||empty($name)||empty($password)||empty($password1)){
		$er="内容不能为空";
	}else if($password!=$password1){
		$er='两次密码不一致';
	}
	else if(strlen($name)<6||strlen($password)<6){
		$er="账号或密码长度不得小于6位";
		}
	else{
		//查询该name 是否存在
		$sql="select name from user where name='$name'";
		if(mSelRow($sql)){
			$er="该用户已存在";
		}else{
			//密码加为暗文格式储存 在md5  转为暗文时 后面在加上随机为 八位 字符串
			//产生随机的8位字符串
			$salt=shufstr(8);
			//md5($password.$cas)
			$password=md5($password.$salt);
			$sql="insert into user(name,nick,email,password,salt) values ('$name','$nick','$email','$password','$salt')";
			if(mQuery($sql)){
				 $er="注册成功！";	 
			}else{
				$er="注册失败";	
			}
		}
	}	
}
require(Root.'/view/front/register.html');
 ?>