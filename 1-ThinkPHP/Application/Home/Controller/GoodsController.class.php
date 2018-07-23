<?php
namespace Home\Controller;
use Think\Controller;
class GoodsController extends Controller{
    public function index(){
        $this->display();
    }
    public function showlist(){
        //var_dump(get_defined_constants(true));
        echo '<meta charset=utf-8/>';
        echo '当前请求地址:'.__SELF__.'<BR>';
        echo '当前分组：'.__MODULE__.'<BR>';
        echo '当前控制器'.__CONTROLLER__.'<br>';
        echo '当前方法：'.__ACTION__;
        //$this->display();
    }
}