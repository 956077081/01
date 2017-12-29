<meta charset="utf-8">
<?php

//不同地方的  时间不同   东八区   时间 该 加 8小时
$maketime=8*60*60;
function getsqlreason($message){
    $filename=Root."./log/".date("Y-m-d").".txt";
   $message="******************************************\r\n".date('Y-m-d H:i:s ')." IP=".getIP()." \r\n".$message."\n******************************************"." \r\n";
   file_put_contents($filename,$message,FILE_APPEND);//文件名相同时 在文件继续添加内容  文件名不同时 新建 文件 
}
function getIP(){
	//$_SERVER  被禁止的情况下  可以通过getenv()函数来调用相关的功能
	static $IP=null;
	if($IP==null){
		if(getenv('REMOTE_ADDR')){//window系统下获得 地址 
			return $IP=getenv('REMOTE_ADDR');
		}else if(getenv('HTTP_CLIENT_IP')){//lis系统的地址
			return $IP=getenv('HTTP_CLIENT_IP');
		}else if (getenv('HTTP_X_FROWARDED_FOR')){//经过代理上网,IP就变成了HTTP_X_FORWARDED_FOR

			return $IP=getenv('HTTP_X_FROWARDED_FOR');
		}else {
            return '';
        }
	}
	return $IP;
}

/**
 * 分页
 * @param  int $num 文章总数
 * @param  int $cn  每页显示的最大个数
 * @param  int $cn 当前的页码 
 * @return  选择的页码
 */
function getPage($num,$cn,$cur){
    $max=ceil($num/$cn);//最大页数
    $left=max(1,$cur-3);//当前位置为2 2-2=0 取1  大于2 是  去当前-2
    $right=min($left+6,$max);//最右侧
    $left=max(1,$right-6);//当为8时 左侧缺一位
    //存储在数组中去
    $date=array();
     for($left;$left<=$right;$left++){
    	$_GET['page']=$left;
        //地址添加参数 在art后再接个get属性
    	$date[$left]=http_build_query($_GET);
    }
    return $date;
}
/**
 * [partPage description]
 * @param  String $count    计算总数的SQL  语句   
 * @param  int $onePageNum  每页显示的条数
 * @return 数组       返回给SQL使用的LIMIT 条件个 以及当前文章显示数
 */
function partPage($count,$onePageNum){

    if(isset($_GET['page'])){//判断 get(page)是否存在
        $p=$_GET['page'];
        if(!is_numeric($p)){//传值是否为 整数
          message('错误类型！');
        }
    }else {
        $p=1;//默认的为当前 第一页
    }    
    $artnum=mSelRow($count)['num'];
    //**************************
    $cnt=$onePageNum;//每页显示条数
    //***************************
    $page=getPage($artnum,$cnt,$p);
    $lim="limit ".($p-1)*$cnt.','.$cnt;
    $data['page']=$page;
    $data['lim']=$lim;
    return $data;
}
/**
 * 产生随机数
 * @param  int $num 随机数长度
 * @return String  随机数
 */
function shufstr($num){

    $str="abcdefghigklmnopqrstuvwxyz123456789";
    $shuf=str_shuffle($str);//打乱顺序
    $name=substr($shuf,0,$num);//截取6个当从临时文件中得到图片的文件名
    return $name;
}
/**
 * 图片添加
 * @param string  $file  file 标签名
 * @return boolean   true 存储成功  false  失败
 */
  function imgAdd($file,$dir='upload',$width=200,$height=200){
    $name=shufstr(6);
    $data=date("Y/m/d");//目录
    $ext=strrchr($_FILES[$file]['name'],'.');//截取文件类型
    $path=Root.'/'.$dir.'/'.$data;
    if(!is_dir($path)){
    //检查是否为目录
    mkdir($path,0777,true);//没有则创建目录  0777代表window下授予的所有访问权  true 
    }
    $s= $path.'/'.$name.$ext;//文件的绝对路经
    $get=$dir.'/'.$data.'/'.$name.$ext;
     
    if(move_uploaded_file($_FILES[$file]['tmp_name'],$s)){
       if(handleImage($s,$width,$height)){
            return  $get;
        }else{
            return false;
        }     
    }
    return false;
    
    //服务器下最大的传图大小upload_max_filesize = 2M   最大为2兆    
    //post_max_size = 8M  post 表单最大为  8M
    //max_execution_time = 30  脚本最大的执行时间为 30s 超过卡掉
        
}
/**
 * 缩略图
 * @param  String $big 大图地址
 * @return   boolean 是否处理成功
 */
function handleImage($big,$width=200,$height=200){

    $spic=imagecreatetruecolor($width,$height);
    //设置底版颜色
    $white=imagecolorallocate($spic,255,255,255);//创建白色底版
    imagefill($spic,0,0,$white);//添加
    //获取大图的信息
    list($bw,$bh,$btype)=getimagesize($big);
    //getimagesize() 返回的第三个int参数 的含义 
     //0=>'UNKNOWN',1=>'GIF',2=>'JPEG',3=>'PNG',4=>'SWF',5=>'PSD', 6=>'BMP',7=>'TIFF_II',8=>'TIFF_MM',9=>'JPC',10=>'JP2',11=>'JPX',12=>'JB2',13=>'SWC',14=>'IFF',15=>'WBMP',16=>'XBM',17=>'ICO',18=>'COUNT'  
     $arrtype=array(1=>'imagecreatefromgif',
        2=>'imagecreatefromjpeg',
        3=>'imagecreatefrompng'
        );        
     if(empty($arrtype[$btype])){//没有所传的资源类型时返回false
           return false;
     }
    
      //得到大图资源
     $bigart=$arrtype[$btype]($big);
     //计算缩略比
     $rate=min($width/$bw,$height/$bh);//缩放比
     $changWidth=$bw*$rate;//缩放后图片的宽度
     $changeHeight=$bh*$rate;//缩放后图片的高度
     //图片放在中间位置
    $bo=imagecopyresampled($spic,$bigart,($width-$changWidth)/2,($height-$changeHeight)/2,0,0,$changWidth,$changeHeight,$bw,$bh);
     imagepng($spic,$big);//得到大图
     imagedestroy($spic);
     imagedestroy($bigart);
     if($bo){
        return true;
     }

     return false;
    
}

/**
 *   浏览器上是否存储着$_COOKIE['name']
 * @return boolean true则直接登录false没有登录信息 
 */
function exceed(){
    //不存在返回false
    if(!isset($_COOKIE['code'])||!isset($_COOKIE['name']) ){
        return false;
    }
    //返回账号和密码是否满足编码格式
    return  $_COOKIE['code']===Codemd5($_COOKIE['name']);
}

/**
 * 对账号进行加密
 * @
 * @return  string 在拼接后的加密COOKIE['code']
 */
function Codemd5($name){
    //制作加密的COOKIE码
    $salt="/5a*s%a";//自定义COOKIE时给密码拼接的字符串
    return md5($name.$salt);
}
/**
 * 得到 用户的账号 和昵称
 * @param  String $username   账号
 * @return array  user_id 和nick 
 */
function reuser($username){
    $sql="select user_id,nick,pripic from user where name='$username'";
    $user=mSelRow($sql);
    return $user;
}
?> 