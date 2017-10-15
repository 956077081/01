<?php 
require("./connect.php");
//得到id  值
$id=$_GET['id'];
//由ｉｄ　找到　该数组的全部数据
$sql="select * from msg1 where id=$id";
if(empty($_POST)){
	$reason= mysql_query($sql);
	$arr=mysql_fetch_assoc($reason);
	require("./update.html");
}else{
    //修改参数
    $sql="update msg1 set name='$_POST[name]' ,email='$_POST[email]',content='$_POST[content]' where id=$id";
    echo $sql;
    if(mysql_query($sql)){
    	 header("Location: show.php");
    }else{
          echo  mysql_error();
          echo "失败！";
    }
}
 
 ?>