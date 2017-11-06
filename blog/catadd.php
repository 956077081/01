<?php
require("./lib/init.php");
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
if(empty($_POST)){
 	/*添加栏目*/
	include(Root."./view/admin/catadd.html");
} else {
    $conn=mConn();//连接数据库
    $cat['catname']=trim($_POST['catname']);
    if(empty($cat['catname'])){
    	message("栏目不能为空！");
    	exit();//不再执行
    }
    $user= reuser($_COOKIE['name']);//返回用户的信息
     $sql ="select catname from cat where catname= '$cat[catname]' and user_id=$user[user_id]";
     $temp=mysql_query($sql,$conn);
     $catname=mysql_fetch_assoc($temp);//有则返回数组 没有 返回false
     if(!empty($catname)){
     	message("该栏目已含有");
     	exit();
     }else{
        //插入catname 和user_id
     	$sql="insert into cat(catname,user_id) value ('$cat[catname]',$user[user_id]) ";
     	if(mysql_query($sql,$conn)){
            message("栏目创建成功！"); 
     	 }else{
     		message("栏目创建失败！");
     	}        

     }  
}
  
   
?>