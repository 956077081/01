<?php
require("./lib/init.php");
//是否有登录记录
if(!exceed()){
  //返回登录界面
    header("Location:login.php");
    exit();
}
 $user= reuser($_COOKIE['name']);//返回用户的信息
$sql="select * from cat where user_id=$user[user_id]";
$arr=mSelAll($sql);
if(empty($_POST)){
  if(empty($arr)){
    message('栏目为空，请先创建栏目');
  }
  require(Root."./view/admin/artadd.html");
}else{
	     //栏目的准确性  是否为数字
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
     //录入图片
     if($_FILES['pic']['size']!=0){
          $picadd=imgAdd('pic');//upload/2017/10/25/s6e9lr.jpg
          if($picadd){//判断是否存储成功
             $art['pic']=$picadd;
          }else{
            message("文件录入失败！");
          }
     }
     //插入表
     $art['pubtime']=time();//录入时间戳
     $art['user_id']=$user['user_id'];
     $art['nick']=$user['nick'];
     if(mInsert('art',$art)){ 
         //没添加一篇文章给 对应的 cat 的 num+1
         $sql=" update cat set num=num+1 where cat_id=$art[cat_id]";
         if(!mQuery($sql)){
             message("文章写入失败！");
         }
         if($_POST['tags']){
               //多个标签时以空格分隔
               $tags=$_POST['tags'];                   
               //将字符串转为数组
               $ar=explode(' ',$tags);//存储    
               // 得到最后一次输入的art_id
               $sql="select max(art_id) as art_id from art ";
               $art=mSelRow($sql);
               //添加失败时删除文章
                $del="delete from art where art_id=$art[art_id]";
               foreach ($ar as $value) {
                     $sql="insert into tag(art_id,tag) values($art[art_id],'$value')";
                     if(!mQuery($sql)){
                         mQuery($del);//便签加入失败时删除该文章
                         message("文章写入失败！");
                     }                                          
               }     
          }     
          //返回文章列表
         header("Location:./artlist.php");
     }else{
     	message("文章写入失败！");
     }
     
    } 

?>