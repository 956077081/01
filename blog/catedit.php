<meta charset="utf-8">
<?php
require("./lib/init.php");
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
 $user= reuser($_COOKIE['name']);//返回用户的信息
//判断是否存在  防止意外打开
if(!isset($_GET['cat_id'])){
  message('打开错误');
}
  $cat_id=$_GET['cat_id'];
 if (!is_numeric($cat_id)) {
	  message("栏目不合法！");
}
require("conn.php");
//栏目判断栏目是否存在  防止传入随机的参数 以及数据库中含有cat_id  时修改了别人的 cat 
$sql="select  cat_id from cat where  cat_id= $cat_id  and user_id=$user[user_id]";
$rs= mysql_query($sql);
$ar=mysql_fetch_assoc($rs);//有则返回 数组 没有返回false
if(!$ar){
	 message('栏目不存在！');
	exit();
 }
 
if(empty($_POST)){
    $sql="select catname from cat where cat_id=$cat_id ";
    $re=mysql_query($sql,$conn);
    $cat=mysql_fetch_assoc($re);
    require("./view/admin/catedit.html");
}else{
     $sql="update cat set catname ='$_POST[catname]' where cat_id= $cat_id";
      if(mysql_query($sql,$conn)){
      	 
         header("Location:./catlist.php");
      }else{
      	message("栏目修改失败！");
      }
      $arr=mysql_fetch_assoc($rs);
    
}
?>