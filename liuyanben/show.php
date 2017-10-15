<?php 
  require("./connect.php");
  
   $sql="select * from msg1";
   $reason=mysql_query($sql);
   $data=array();
   while($row=mysql_fetch_assoc($reason)){

   	    $data[]=$row;
   }
 
   //html 与php文件嵌套的方式
   //直接在hmtl文件 中    放入<?php  ...? >即可

   require("./meglist.html");
 ?>