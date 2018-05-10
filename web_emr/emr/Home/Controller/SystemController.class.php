<?php

namespace Home\Controller;
use Think\Controller;
class SystemController extends Controller{
	 public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
	public function index()
	{ 
       
		$this->showLogin();
	}
	
	public function showLogin()
	{   
                  
		$this->display("showLogin");
	}
	

    public function checkLogin()
	{
    		//信息完整性校验
                //如果账号密码正确有空 则返回错误信息
               
		if(empty($_POST['user_login_password_emr'])||empty($_POST['user_login_name_emr']))
		{
                          //有空 fasle
			$authority_state = false;
		}
		else
		{        
                           //非空 true
			$authority_state = true;
		}
  
		if($authority_state == false)
		{   
			$authority_state = false;//无必要
			echo '{  "message": "您的身份验证信息不完整" , "result" : "false" , "zhuyuan_bingqu" : "'.$zhuyuan_bingqu.'"}';
			exit();
		}
                //检查登录表的信息是否匹配
		$user_search_result = M("yiyuan_user")->where("  user_login_password_emr ='".$_POST['user_login_password_emr']." ' and user_login_name_emr = '".$_POST['user_login_name_emr']."' ")->select();
		//判断查询结果表是否空
		if(empty($user_search_result))
		{
			$authority_state = false;//为空 $authority_state状态置为false
			//返回错误信息
			echo '{ "message": "您的用户名和密码不匹配" , "result" : "false" , "zhuyuan_bingqu" : "'.$zhuyuan_bingqu.'"}';
			exit();
		}
		else
		{
                    //不为空则 查询成功  状态置为true
			$authority_state = true;
                        //开启session  
			session_start();
	 		session_regenerate_id();//更改session_id   ?问题应该先设置 session_id  在更改session
                        
			$sessionID = session_id();//设置sessiion_id  相当于给服务器分配了一个Cookie值
			//服务器存放session 内容
                        
			$_SESSION['server_url'] = C("WEB_HOST");
			$_SESSION['user_id'] = $user_search_result[0]['user_id'];
			$_SESSION['yiyuan_id'] = $user_search_result[0]['yiyuan_id'];
			$_SESSION['user_name'] = $user_search_result[0]['user_name'];
                        //查找医院信息
			$yiyuan_department_info = M("yiyuan_department_info")->where("yiyuan_id='".$user_search_result[0]['yiyuan_id']."' and bingqu_name='".$user_search_result[0]['user_department']."'")->find();
			$_SESSION['user_department_id'] = $yiyuan_department_info['bingqu_id'];
			if($_SESSION['user_department_id'] == null)
			{
				$_SESSION['user_department_id'] = "";
			}
			$_SESSION['user_department'] = $user_search_result[0]['user_department'];
			$_SESSION['user_department_position'] = $user_search_result[0]['user_department_position'];
			$_SESSION['user_kebie'] = $user_search_result[0]['user_kebie'];
			$_SESSION['user_kebie_position'] = $user_search_result[0]['user_kebie_position'];
			$_SESSION['user_type'] = $user_search_result[0]['user_type'];
			$_SESSION['login_state'] = "true";
			
			//优化调整医生所在病区：
			$_SESSION['user_department'] = $_SESSION['user_department'];
			//补充所在医院中文名称
			$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$_SESSION['yiyuan_id']."'")->find();
			$_SESSION["yiyuan_name"] = $yiyuan_info["yiyuan_name"];
			echo '{ "message": "验证通过:)" , "result" : "true" , "zhuyuan_bingqu" : "'.$_SESSION['user_department'].'"}';
			
		}
	}
	
	public function showSystemChecked()
	{
	  
	
		//如果病区选择为门诊，就进入门诊系统：
		if($_SESSION['user_department']=="门诊"){  
			$this->display("showSystemMenzhen");
			exit();
        }else if($_GET["zhuyuan_id"]!="" && $_GET["yiyuan_id"])
		{
			$zhuyuan_id = $_GET["zhuyuan_id"];
			$yiyuan_id = $_GET["yiyuan_id"];
			$this->showSystemUrl($zhuyuan_id,$yiyuan_id);
		}else{
				$this->display("showSystem");
		}
		
	}
	
	//通过用户名密码直接显示系统主界面       
	public function showSystem()
	{
            
            
		$authority_state = false;
		if(empty($_GET['user_id']))
		{
			$authority_state = false;
		}
		else
		{
			$authority_state = true;
		}
		if($authority_state == false)
		{
			$this->assign('system_info','错误：E0001，您的身份验证信息不完整，请联系管理员重新登录。');
			$this->display("System:showError");
			exit();
		}
		$user_search_result = M("yiyuan_user")->where(" user_id ='".$_GET['user_id']."'")->select();
		if(empty($user_search_result))
		{
			$authority_state = false;
			$this->assign('system_info','错误：E0002，您的身份验证信息匹配有错误，请联系管理员重新登录。');
			$this->display("System:showError");
			exit();
		}
		else
		{
			$authority_state = true;

			session_start();
	 		session_regenerate_id();
			$sessionID = session_id();

			$_SESSION['server_url'] = C("WEB_HOST");
			$_SESSION['user_id'] = $user_search_result[0]['user_id'];
			$_SESSION['yiyuan_id'] = $user_search_result[0]['yiyuan_id'];
			$_SESSION['user_name'] = $user_search_result[0]['user_name'];
			$_SESSION['user_department'] = $user_search_result[0]['user_department'];
			$_SESSION['user_department_position'] = $user_search_result[0]['user_department_position'];
			$_SESSION['user_kebie'] = $user_search_result[0]['user_kebie'];
			$_SESSION['user_kebie_position'] = $user_search_result[0]['user_kebie_position'];
			$_SESSION['user_type'] = $user_search_result[0]['user_type'];
			$_SESSION['login_state'] = "true";
			
			//补充所在医院中文名称
			$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$_SESSION['yiyuan_id']."'")->find();
			$_SESSION["yiyuan_name"] = $yiyuan_info["yiyuan_name"];
			
			if(!empty($_GET['zhuyuan_bingqu']))
			{
				$bingqu_name = M("yiyuan_department_info")->field("bingqu_name")->where("bingqu_id = '".$_GET['zhuyuan_bingqu']."'")->find();
				$_SESSION["user_department"] = $bingqu_name["bingqu_name"];
			}
                   
			if(!empty($_GET['menzhen_id']))
			{
				$this->assign("menzhen_id",$_GET['menzhen_id']);
			}
			if($_GET["user_type"]=="menzhen")
			{
				$this->display("showSystemMenzhen");
			}
			elseif(!empty($_GET["zhuyuan_id"]) && !empty($_GET["yiyuan_id"]))
			{
				$zhuyuan_id = $_GET["zhuyuan_id"];
				$yiyuan_id = $_GET["yiyuan_id"];
				$this->showSystemUrl($zhuyuan_id,$yiyuan_id);
			}
			else
			{
				$this->display();
			}
		}
	}

	//退出登录
	public function logout()
	{
		session_destroy();
		$this->showRight("退出系统成功。");
	}

	public function showErrorWithRedirct($system_info,$redirect_url,$redirect_time)
	{
		$this->assign("system_info",$system_info);
		$this->display("showError");
		//页面跳转：
			echo'
			<script language="javascript">
				var i=1;
				var t;
				function showTimer()
				{
					if(i==0)
					{
						window.location.href="'.$redirect_url.'";
						window.clearInterval(t);
					}
					else
					{
						i = i - 1 ;
					}
				}
				t = window.setInterval(showTimer,'.$redirect_time.'); 
				</script>
			';
			exit();
	}

	public function showRight($system_info="操作正确")
	{
		$this->assign("system_info",$system_info);
		$this->display("showRight");
		exit();
	}
	
	public function showRightWithRedirct($system_info,$redirect_url,$redirect_time)
	{
		$this->assign("system_info",$system_info);
		$this->display("showRight");
		//页面跳转：
			echo'
			<script language="javascript">
				var i=1;
				var t;
				function showTimer()
				{
					if(i==0)
					{
						window.location.href="'.$redirect_url.'";
						window.clearInterval(t);
					}
					else
					{
						i = i - 1 ;
					}
				}
				t = window.setInterval(showTimer,'.$redirect_time.'); 
				</script>
			';
			exit();
	}
	
	//远程医疗调用病历内容
	public function showSystemUrl($zhuyuan_id,$yiyuan_id)
	{
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();
		if(empty($zhuyuan_basic_info))
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();
		}
		$this->assign("zhuyuan_id",$zhuyuan_id);
		$this->assign("yiyuan_id",$yiyuan_id);
		$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
		$this->display("showSystemUrl");
	}

}
