<?php 
require("./connect.php");
//得到 传来 的 id值  再根据 id 的只删除  数据
$id=$_GET['id'];
//“”可以识别变量   
$sql="delete from msg1 where id=$id";
if(!mysql_query($sql)){

	echo "删除失败！";
}else{
     header("Location: show.php");//跳转到 show.php页面
	//require("show.php");//功能 一样  require   只是在该页面 显示 其他 页面内容   地址 显示还是msdelete.php的地址
}


 ?>