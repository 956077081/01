<?php
namespace Model;
use Think\Model;
class UserModel extends Model{
    protected $_map=array(
        'name'  =>  'username',
        'pwd'  =>  'password',
        'email'  =>  'user_email'
    );
    
    protected $_auto=array(
        array('nation','中国'),
        array('password','md5',1,'function'),
        array('username','getName',1,'callback'),
    );
    function getName($value){
        return '尊敬的:_'.$value;
    }
}