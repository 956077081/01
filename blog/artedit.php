<?php
//编辑
require("./lib/init.php");
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}

//防止意外打开
if(!isset($_GET['art_id'])){
     message("打开错误");
}
//判断 地址栏传值的  正确性
//是否为数字
$art_id=$_GET['art_id'];
if(!is_numeric($art_id)){
	message("栏目不合法！");
}
$sqlart="select * from art where art_id=$art_id";
if(!mQuery($sqlart)){
message("文章不存在！");
}

$sqlcat="select cat_id,catname from cat";
$arr=mSelAll($sqlcat);
$art=mSelRow($sqlart);
if(empty($_POST)){

	require(Root."./view/admin/artedit.html");
}else{
	 $art['cat_id']=$_POST['cat_id'];
     if(!is_numeric($art['cat_id'])){
     	message("栏目格式不正确！");

     }
     //标题的准确性
     $art['title']=trim($_POST['title']);//去掉空格
   
     if(empty($art['title'])){
     	message("标题不能为空！");
     
     }

     //内容的准确性
     $art['content']=trim($_POST['content']);
     if(empty($art['content'])){
     	message("内容不能为空！");
     }
     //插入表mUpdata($table,$data,$whtype,$whnum)
     $art['lastup']=time();//录入时间戳
    $art_id= $art['art_id'];	
     unset($art['art_id']);
     if(mUpdata('art',$art,'art_id',$art_id)){
      header("Location:./artlist.php");
       
    
     }else{
     	message("文章写入失败！");
     }

}

?>