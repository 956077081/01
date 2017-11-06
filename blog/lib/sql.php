<meta charset="utf-8">
<?php
/**
 * 连接数据库
 * @return resource 或false
 */
function mConn(){
	static $conn=null;//防止多次调用打开数据库 给静态变量 重新创建时  若赋值为null  那该静态值将使用上次的 静态的值 
	if($conn===null){
	$conn=mysql_connect('localhost','root','');
	mysql_query('use blog',$conn);
	mysql_query('set names utf8',$conn);
    }
    return $conn;
}
/**
 * 执行sql 语句
 * @return resourse/bool
 */
function mQuery($sql){
     $re=mysql_query($sql,mConn());
     if(!$re){
     	$sql=$sql."\n".mysql_error();     
     }
       getsqlreason($sql);
	return $re;
}
/**
 * @param String sql语句  查询多行数据
 * @return $arr  二维数组  
 *  */
function mSelAll($sql){
	/*select cat from cat  */
	// $str="select ".$type." from ".$table;
	 $reason=mQuery($sql);
	 if($reason){
	 	$data=array();
	 	while($row=mysql_fetch_assoc($reason)) {
	 	 	$data[]=$row;
	 	}
	 	return $data;
	 }else{
	 	return false;
	 }
}
/**
 * 查询 一行数据  
 * @param String $sql 语句 
 * @return   $arr /false   返回一维数组或 false
 */
function mSelRow($sql){
   $reason=mQuery($sql);
   if($reason){
      return mysql_fetch_assoc($reason);
   }else{
   	 return false;
   } 
}

/**
 * [mInsert description]
 * @param  String  $table  表名
 * @param   二维数组 $data  键为属性 ，值所传的数据
 * @return boolean   添加成功返回true  否为为false
 */
function mInsert($table,$data){

	/*insert into 表名(属性1,属性2...) value('属性1','属性2'...) */
	$sql="insert into $table ( ";
	$sql.=implode(',',array_keys($data));
	$sql.=" ) values ( '".implode("','",array_values($data))."')";

	  return  mQuery($sql);


}

/**
 * [mUpdata description]
 * @param  String $table     表名
 * @param  二维数组 $data   存储要更改的属性 和值
 * @param  string  $whtype  where 条件属性
 * @param   string  $whnum  where 条件值
 * @return boolean 更新成功返回true 否则返回false
 */
function mUpdata($table,$data,$whtype,$whnum){
	/*updata 表名 set catname= '123'，num='23423' where  catnaem=" "*/
$sql="update $table set  ";
foreach ($data as $key => $value) {
	 $sql.=$key." = '".$value." ',";
}
	$sql=rtrim($sql,',')." where ".$whtype."= " .$whnum;
	
	 return mQuery($sql);
}

/**
 *  转义字符防止简单的sql注入  将敏感的字符串 加 反斜杠转义
 * @param  array $arr  原始字符串
 * @return $arr    转义后的 字符串
 */
function mAddlashes($arr){
	foreach ($arr as $key => $value) {
		if(is_string($value)){
			$arr[$key]=addslashes($value);
		}else if(is_array($value)){
			$arr[$key]=mAddlashes($value);
		}
	}
	return $arr;
}
?>
