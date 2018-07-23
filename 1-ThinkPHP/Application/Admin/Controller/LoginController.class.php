<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller{
    public function login(){
        if(IS_POST){
            $obj=new \Think\Verify();
            if($obj->check(I('post.captcha','','trim')))
            {
                $data['mg_name']=I('post.admin_user');
                $data['mg_pwd']=I('post.admin_psd','',  mysql_real_escape_string);
                $row=M('manager')->where($data)->find();
                if($row){
                    session('mg_id',$row['mg_id']);
                    $this->redirect('Manager/index');
                }else {
                        $this->error('用户名或密码错误', U('login'), 4);
                    }
            }else{
                $this->error('验证码错误', U('login'), 4);
            }            
        }
        $this->display();
    }
    public function verifyImg(){
        $config=array(
            'imageW'    =>  120,
            'imageH'    =>40,
            'fontSize'  =>15,
            'length'    =>4,
            'fontttf'   =>'4.ttf'
        );
        $obj=new \Think\Verify($config);
        $obj->entry();
    }
}