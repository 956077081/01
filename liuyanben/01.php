<meta charset="utf-8">
<?php 
  //require("./index.html");//连接 一个 文件
  if( empty($_POST)){//判断 $_POST是否为空
       require("./index.html");
   }else{//连接数据库
    require("./connect.php");
    $sql="insert into msg1(name,email,content) value ('$_POST[name]','$_POST[email]','$_POST[content]')";
    echo $sql;
    //mysql_query($sql,$conn);
    if(!mysql_query($sql ,$conn)){
          echo mysql_error();
          exit();
    }else{

    	header("Location:./show.php");
    }
   }
 ?>