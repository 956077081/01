<?php
require("./lib/init.php");
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
if(!isset($_GET['comment_id'])){
	message("该评论不存在");
}
//判断接受参数的正确性
$comment_id=$_GET['comment_id'];
if(!is_numeric($comment_id)){
	message("错误！");
}
//查找评论是否存在
$sql="select comment_id,art.art_id from comment,art where   comment_id=$comment_id and art.art_id=comment.art_id";
$thecomm=mSelRow($sql);
if(!$thecomm){	
	message("该评论不存在！");
	exit();
}
$sql="delete from comment where comment_id=$comment_id";
if(mQuery($sql)){
	//对应art表的数量减1
	$sql="update art set comm=comm-1 where art_id=$thecomm[art_id]";
	 mQuery($sql)
     $re=$_SERVER['HTTP_REFERER'];
     header("Location:$re");
}else{
	message("评论删除失败！");
}
     
?>