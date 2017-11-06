<?php
require("./lib/init.php");
//当有地址栏传值时先判断地址栏的数据正确性
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
//防止未从artlist.php打开 而直接打开此网页
if(!isset($_GET['art_id'])){
	message("打开错误");
}
//判断是否为数字
$art_id=$_GET['art_id'];
if(!is_numeric($art_id)){
     message("栏目不合法！");
}
//判断文章是否存在
//查找 文章
$sql="select art_id,pic,cat_id from art where art_id= $art_id";
$art=mSelRow($sql);
if(!$art){
	message("文章不存在！");
}
$sql="delete  from art where art_id=$art_id";//删除文章
$comdel="delete  from tag where art_id=$art_id";//删除标签
$celNum=" update cat set num=num-1 where cat_id=$art[cat_id]";//更新栏目文章数目
$celcomment="delete  from comment where art_id=$art_id";
if(mQuery($sql)){
	//删除图片
	unlink(Root.'./'.$art['pic']);
	//删除评论
	mQuery($celcomment);
	mQuery($celNum);//更改文章数目
	mQuery($comdel);//标签的删除
	
   $re=$_SERVER['HTTP_REFERER'];
   header("Location:$re");//跳转
  
}else{
	message("文章删除失败！");
}
?>