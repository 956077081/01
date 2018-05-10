<?php
namespace Components;
use Think\Controller;
class TemrController extends Controller
{
	protected function _initialize()
	{
		$user_number = $_SESSION["user_number"];
		$user_info = M("yiyuan_user")->field("last_login_ip")->where("user_number = '$user_number' ")->find();
		$last_login_ip = $this->getIP();
		if(!empty($user_info['last_login_ip']) && !empty($last_login_ip))
		{
			if($last_login_ip != $user_info["last_login_ip"])
			{
				echo '<script type="text/javascript">if(confirm("您的帐号已经在别处登录，请重新登录.")){window.open("/web_emr/System/logout/","_parent");};</script>';
			}
		}
		if (C('url_debug_mode')==false)
		{
			if($_SESSION["login_state"]!="true")
			{
				$act = ACTION_NAME;
				$condition = array('showReport','printReport','showShenqingdan','printShenqingdan');
				if (!in_array($act,$condition))
				{
					$this->redirect("Common/System/showLogin");
					exit(0);
				}
			}
		}
           
	}
      
	public function getIP()
	{ 
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) 
		{
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
		}
		else if (@$_SERVER["HTTP_CLIENT_IP"]) 
		{
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		else if (@$_SERVER["REMOTE_ADDR"]) 
		{
			$ip = $_SERVER["REMOTE_ADDR"];
		}
		else if (@getenv("HTTP_X_FORWARDED_FOR"))
		{
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}
		else if (@getenv("HTTP_CLIENT_IP")) 
		{
			$ip = getenv("HTTP_CLIENT_IP"); 
		}
		else if (@getenv("REMOTE_ADDR")) 
		{
			$ip = getenv("REMOTE_ADDR"); 
		}
		else 
		{
			$ip = "Unknown";
		}
		return $ip; 
	}
}
