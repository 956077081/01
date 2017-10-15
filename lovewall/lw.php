<?php 
$conn=mysql_connect("localhost",'root','');
mysql_query("use new");
mysql_query(" set names  utf8",$conn);
$sql='select * from lovewall';
$sourse=mysql_query($sql);
$date=array();
while($row=mysql_fetch_assoc($sourse)){
      $date[]=$row;
}
if(!empty($_POST)){
	$top=mt_rand(0,419);//产生随机数 0到419的整数
	$left=mt_rand(0,700);
    $arr=array('o1.gif','o2.gif','o3.gif');
    $pi=$arr[array_rand($arr)];//数组中随机选取其中一个
	$sql="insert into lovewall(addressee, content,sender,rtop,rleft,pic) values ('$_POST[addressee]','$_POST[content]','$_POST[sender]',$top,$left,'$pi') ";
	if(mysql_query($sql)){
		$ref=$_SERVER['HTTP_REFERER'];//跳转到上个页面
		header("Location:$ref");
		
	}else{
      $er= mysql_error();
      
	}
}
require('./01.html');

 ?>