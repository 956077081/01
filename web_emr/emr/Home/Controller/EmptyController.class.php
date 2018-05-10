<?php
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller {
	
	public function _empty(){
		header("Conteny-type=text/html,Charset=utf8");
		echo "<h1>","错误登录","</h1>";
	}
}