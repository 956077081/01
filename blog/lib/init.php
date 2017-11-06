<?php
define("Root", dirname(__DIR__));//设置绝对路径F:\warmpsever\wamp\www\blog  name=Root
require(Root."./lib/sql.php");
require(Root."./remessage.php");
require(Root."./lib/method.php");
header("Content_type:text/html;charset=utf-8");//设置字符集
$_GET=mAddlashes($_GET);//转义GET参数
$_POST=mAddlashes($_POST);//转义POST参数
$_COOKIE=mAddlashes($_COOKIE);//转义COOKIE参数
?>