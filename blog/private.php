<?php
require("./lib/init.php");
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
$user=reuser($_COOKIE['name']);
$sql="select email, password,salt from user where user_id=$user[user_id]";
$pr=mSelRow($sql);
$pr['nick']=$user['nick'];
//得到用户的信息
$er='';
if(empty($_POST)){
	require('./view/admin/private.html');
} else{
	$password=$_POST['password'];
    $password2=$_POST['password2'];
	if($password!=$password2){
		$er='密码输入不一致1';
	}else{
		   
			if(strlen(trim($password))<6&&$password!='12345'){// 对于要修改的密码长度小于6时
				 
				   $er="密码长度必须不小6";
				
			}else{//密码大于等于6时和 小于6  等于12345
					$word=$pr['password'];//若没改变还是原来的密码
					if($password!='12345'){	
					//说明修改了密码
					$word=md5($password.$pr['salt']);
					}
					//录入图片
		
					$setpic='';
			    	if($_FILES['prpic']['size']!=0){//是否更改头像
			    	
				      	  $picadd=imgAdd('prpic','image',100,100);//upload/2017/10/25/s6e9lr.jpg
					      if($picadd){//判断是否存储成功
					      	$setpic=", pripic='".$picadd."'";
					      }else{
					        $er='图像上传失败';
					      }
			         }
			         $sql="update user set nick='$_POST[nick]',email='$_POST[email]',password='$word' $setpic where user_id= $user[user_id] ";
			         if(mQuery($sql)){
			         	$er="成功";

			         }else{
			         	message("失败！");
			         }
			}
	   }
    require(Root.'/view/admin/private.html');
}

?>