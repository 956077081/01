<?php
namespace Home\Controller;
use Think\Controller;
use Model\UserModel;
class IndexController extends Controller {
    public function index(){
        $this->display();
     }
     public function register(){
         if(IS_POST){
             $model=new UserModel();
             $data=$model->create();
             
   
             var_dump($data);
         }
         $this->display();
     }
}