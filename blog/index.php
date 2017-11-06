<?php

require("./lib/init.php");
//是否有登录记录
if(!exceed()){
	//返回登录界面
    header("Location:login.php");
    exit();
}
$user=reuser($_COOKIE['name']);
	if(isset($_GET['page'])){//判断 get(page)是否存在
	     $p=$_GET['page'];
	     if(!is_numeric($p)){//传值是否为 整数
		  message('错误类型！');
	     }
    }else {
	      $p=1;//默认的为当前 第一页
    }
	$count="select count(*) as num from art ";
	if(isset($_GET['cat_id'])){//当进入某个栏目时  计算文章数目
		$count="select count(*) as num from art where cat_id=".$_GET['cat_id'];
	}
	$artnum=mSelRow($count)['num'];
	$cnt=6;//每页显示条数
	$page=getPage($artnum,$cnt,$p);
	$lim="limit ".($p-1)*$cnt.','.$cnt;
	//查找和自己栏目相同的文章
    $sqlcat="select catname,cat_id from cat where user_id=$user[user_id]";
    $cats=mSelAll($sqlcat);
   $sqlart="select art_id, title, pubtime,catname,comm,content,pic,art.nick from art,cat where cat.cat_id=art.cat_id order by art_id DESC ".$lim;

if(isset($_GET['catname'])){//判断是否存在 在进入固定栏目时  显示
    $sqlart="select art_id,title, pubtime,catname,comm,content,pic,art.nick from art,cat 
    where cat.cat_id=art.cat_id and cat.catname='$_GET[catname]' 
    order by art_id DESC ".$lim;
    
}
$art=mSelAll($sqlart);//页面显示文章
//查找标签
$seltag="select tag from tag where art_id=";
require("./view/front/index.html");
?>		