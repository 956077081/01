<?php
require("./lib/init.php");
	if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
	$user=reuser($_COOKIE['name']);
	 $count="select count(*) as num 
	 from art,cat where art.cat_id=cat.cat_id and cat.user_id=$user[user_id]";
	 $data=partPage($count,5);//每页的信息条数
	 $lim=$data['lim'];
	 $page=$data['page'];
   //得到文章
   $sql="select art_id,title,pubtime,comm,catname,nick from art , cat where art.cat_id=cat.cat_id and art.user_id=$user[user_id] order by pubtime DESC ".$lim;
   $art=mSelAll($sql);
    if(empty($_POST)){
	require(Root."./view/admin/artlist.html");
   }
?>