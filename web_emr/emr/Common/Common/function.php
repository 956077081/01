<?php

function jiemi()
{
	$jiancha = M('yiyuan_info')->where("`name` = 'password' ")->find();
	if(!empty($jiancha))
	{
		if(substr( sprintf("%o",fileperms(__FILE__) ) ,-4) != 555)
		{
			//chmod(__FILE__,555);		//设置只读权限
		}
		$quanxian = substr(sprintf("%o",fileperms(__FILE__)),-4);
		$size = filesize(__FILE__);		//获取文件的大小
		$zuihou = filemtime(__FILE__);		//获取最后一次修改时间
		$jiamima = md6($quanxian).md6('111').md6('111');

		$lujin = realpath(dirname(__FILE__).'/../').'\Lib\Action\TemrAction.class.php';
		$Tquanxian = substr(sprintf("%o",fileperms($lujin)),-4);
		$Tsize = filesize($lujin);
		$Tzuihou = filemtime($lujin);
		$jm = md6('222').md6('222').md6('222');
		$duibi = substr($jiancha['content'],-96);
		if($jm != $duibi)
		{
			return 0;
		}


		$temp = substr(base64_decode($jiancha['content']),0,32);
		if(!md5('tiantanhehe') == $temp)
		{
			return 0;
		}
		$jiancha = substr($jiancha['content'],0,-96);
		$temp2 = substr(substr(base64_decode($jiancha),32),-96);
		if($temp2 != $jiamima)
		{
			return 0;
		}
		$guoqi = base64_decode(substr(substr(base64_decode($jiancha),32),0,-96));
		$shijian = date('Y-m-d');
		if($guoqi >= $shijian)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}

//加密
function jiami($paw,$shijian,$quanxian,$size,$zuihou,$Tquanxian,$Tsize,$Tzuihou)
{
	if(substr(sprintf("%o",fileperms(__FILE__)),-4) != 555)
	{
		//chmod(__FILE__,555);		//设置只读权限
	}
	$path = __FILE__;	//文件的路径
	$lujin = realpath(dirname(__FILE__).'/../').'\Lib\Action\TemrAction.class.php';
	$paw = 'tiantanhehe';		//加密密码
	$shijian = '2013-07-05';	//到期时间
	$quanxian = substr(sprintf("%o",fileperms(__FILE__)),-4);
	$size = filesize(__FILE__);		//获取文件的大小
	$zuihou = filemtime(__FILE__);		//获取最后一次修改时间

	$Tquanxian = substr(sprintf("%o",fileperms($lujin)),-4);
	$Tsize = filesize($lujin);
	$Tzuihou = filemtime($lujin);
	$jiamima = md6($quanxian).md6('111').md6('111');
	$zifu = md5($paw).base64_encode($shijian).$jiamima;
	$str = base64_encode($zifu).md6('222').md6('222').md6('222');
	return $str;
}
//加密
function md6($paw)
{
	return substr_replace(md5($paw),'5702h4','15','6');
}


