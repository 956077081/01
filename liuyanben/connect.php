<?php //连接类

   //设置字符集
 header("Content-type:text/html;charset='utf-8'");
$conn=mysql_connect('localhost','root','');
    mysql_query("set name utf-8",$conn);
    mysql_query("use new",$conn);

 ?>