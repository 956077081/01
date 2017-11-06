
<?php
require("conn.php");
require("./lib/init.php");
$cat['cat_id']=$_GET['cat_id'];
//$_GET传参所出现的问题为  参数列表会出现 地址栏上 导致 可以人为的传递错误参数
if (!is_numeric($cat['cat_id'])) {
	message("栏目不合法！");
	exit();
}
//栏目存在不能删
$sql="select  cat_id from cat where  cat_id= $cat[cat_id]";
$rs= mysql_query($sql);
$ar=mysql_fetch_assoc($rs);//有则返回 数组 没有返回false
if(!$ar){
	message('栏目不存在！');
	exit();
 }
//栏目有文章不能删
 $sql="select cat_id from art where cat_id=$cat[cat_id]";
 $rs= mysql_query($sql);
 $ar=mysql_fetch_assoc($rs);
 if($ar){
 	message("栏目中含有文章，无法删除！");
 	exit();
 }

 $sql="delete from cat where cat_id=$cat[cat_id]";
 $bo= mysql_query($sql);
 if($bo){
   //跳转到上个页面
	$ref=$_SERVER['HTTP_REFERER'];//跳转到上个页面
	header("Location:$ref");
 } else {
 	message("删除失败！");
 }
?>