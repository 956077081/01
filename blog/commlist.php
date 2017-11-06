<?php 
require("./lib/init.php");
    /** 分页*/
    if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
    }
     $user= reuser($_COOKIE['name']);//返回用户的信息
	$count="select count(*) as num 
	from art ,comment 
	where art.art_id=comment.art_id and art.user_id=$user[user_id]";
     $data=partPage($count,6);//每页的信息条数
     $lim=$data['lim'];
     $page=$data['page'];
     $sql="SELECT comment_id, comment.art_id, comment.content, comment.pubtime, comment.nick,ip ,title 
     FROM comment , art 
     WHERE comment.art_id = art.art_id and art.user_id=$user[user_id]
     ORDER BY pubtime DESC ".$lim;
     $comm=mSelAll($sql);

require(Root."./view/admin/commlist.html");
 ?>