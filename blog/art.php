<?php
require("./lib/init.php");
//是否有登录记录
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}

//检查 $_GET['art_id']   是否存在
if(!isset($_GET['art_id'])){
     exit();
}
 $art_id=$_GET['art_id'];

//地址栏的合法性
 $er="";//当 内容为 空时 返回信息
if(!is_numeric($_GET['art_id']) ){
     message("栏目不合法！");
 }
//查找 文章
$sql="select art_id from art where art_id= $art_id";
$art=mSelRow($sql);
if(!$art){
	message("文章不存在！");
}
$sql="select art_id,title,pubtime,comm,catname,content,pic,art.nick from art ,cat where art.cat_id=cat.cat_id and art_id= $art_id order by pubtime ASC ";
 $user=reuser($_COOKIE['name']);
$art=mSelAll($sql);
if(!empty($_POST)){      
  $comm['content']=trim($_POST['content']);
  $comm['pubtime']=time();
  $comm['art_id']=$art_id;
  $comm['user_id']=$user['user_id'];
  $comm['nick']=$user['nick'];
  //将得到的IP转为数字型ip2long($ip)  有应为转换为带负号的数字 而数据库为 无符号的 UNsign 格式  再使用 sprintf('%u',ip2long($ip) )  转为 无符号型的格式数字
  $ip=getIP();//得到IP
  $unIp=sprintf('%u',ip2long($ip));
    $comm['ip']=$unIp;
  if($comm['content']==null){
    //为空时插入失败  返回错误给 html 页面
      $er="内容不能为空";    
  }else{
     if (mInsert('comment',$comm)){
     	//为该cat  的comm数加1
     	   $sql=" update art set comm=comm+1 where art_id= $art_id ";
         mQuery($sql);  
  	    //成功时  跳转到上个页面
  	     $re=$_SERVER['HTTP_REFERER'];
  	     header("Location:$re");
     }
  } 
}
//按buptime的  升序行排列
$sql="select nick,pubtime,content from  comment where art_id=$art_id order by pubtime ASC";
$comment=mSelAll($sql);
require("./view/front/art.html");
?>