<?php
namespace ZhuyuanYishi\Controller;
use Components\TemrController;
class PatientController extends TemrController{
    
	 public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
	public function showPatientList()
	{    
		if(empty($_GET["page"]))
			$current_page_number = 1;
		else
			$current_page_number = $_GET["page"];
		$one_page_amount = 10;
		
		$user_department  = $_SESSION["user_department"];
		$yiyuan_id = $_SESSION["yiyuan_id"];
		
		if(!empty($_GET["keyword"])&&($_GET["keyword"]!=="quanbu"&&$_GET["keyword"]!=="全部"))
		{
			$keyword = $_GET["keyword"];
		
			//如果为管理员模式，可能为医院名称：
			if($_SESSION["user_type"]=="管理员")
			{
				$yiyuan_info = M("yiyuan_info")->where("yiyuan_name like '%$keyword%'")->find();
				if(!empty($yiyuan_info["yiyuan_id"]))
				{
					$search_yiyuan_id = $yiyuan_info["yiyuan_id"];
				}
			}
			$search_condition = "and (xingming like '%$keyword%' or xingbie like '%$keyword%' or zhuyuan_id like '%$keyword%' or zhuyuan_chuanghao like '%$keyword%' or nianling like '%$keyword%' or ruyuan_zhenduan like '%$keyword%' or yiyuan_id = '$search_yiyuan_id') ";
			if($_GET["keyword"]!=="quanbu")
				$this->assign("keyword",$keyword);
		}
		else
		{
			// 非病案室医生，默认不显示归档状态患者
			if($_SESSION["user_department"]!="病案室病区")
			{
				$search_condition = " and guidang_zhuangtai='未归档'";
			}
			
			$this->assign("keyword","quanbu");
		
		}
			
		if(empty($_GET["selection_condition"])&&empty($_GET["keyword"])&&$_SESSION["user_type"]!=="管理员"&&$_SESSION["user_department"]!=="病案室病区")
		{
			$search_condition .= " and zhuyuanyishi_id = '".$_SESSION["user_id"]."'";
		}
		else
		{
			$selection_condition = $_GET["selection_condition"];
			$this->assign("selection_condition",$selection_condition);
		}
		
		if(!empty($_GET["guidang_zhuangtai"]))
		{
			$search_condition .= " and guidang_zhuangtai = '".$_GET["guidang_zhuangtai"]."'";
		}

		//查看患者权限范围筛选条件：
		if($_SESSION["user_type"]=="管理员")
			$area_search_condition = " zhuyuan_department like '%' and yiyuan_id like '%' ";
		else if($_SESSION["user_department"]=="病案室病区")
			$area_search_condition = " zhuyuan_department like '%' and yiyuan_id = '$yiyuan_id' ";
		else
			$area_search_condition = " zhuyuan_department = '$user_department' and yiyuan_id = '$yiyuan_id' ";

		
		$total_amount = M("zhuyuan_basic_info")->where($area_search_condition.$search_condition)->count();
        $total_page_number = ceil($total_amount/$one_page_amount);
		$search_result = M("zhuyuan_basic_info")->where($area_search_condition.$search_condition)->order("ruyuan_time DESC")->limit((($current_page_number-1)*$one_page_amount).",$one_page_amount")->select();
		$this->assign("current_page_number",$current_page_number);
		$this->assign("one_page_amount",$one_page_amount);
		$this->assign("total_amount",$total_amount);
		$this->assign("total_page_number",$total_page_number);

		// 临时处理空白问题    
		foreach ($search_result as $key => $one)
		{
			foreach ($one as $key2 => $value)
			{
				$search_result[$key][$key2] = trim($value);
			}
		}

            
		$this->assign("search_result",$search_result);
		
		
		//如果是管理员，展示全局情况
		if($_SESSION["user_type"]=="管理员")
		{
			//获取医院信息
			foreach($search_result as $key => $one_result)
			{
				$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$one_result["yiyuan_id"]."'")->find();
				$search_result[$key]["yiyuan_name"] = $yiyuan_info["yiyuan_name"];
			}
			$this->assign("search_result",$search_result);
			$this->display("showPatientListWholeArea");
		}
		else if($_SESSION["user_department"]=="病案室病区")
		{       
			$this->assign("search_result",$search_result);
			$this->display("showPatientListBinganshi");   
		}
		else{

			  $this->display();

		}		
	}
        	//显示患者住院状态概况
	public function showPatientZhuyuanDetail()
	{
		
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$yiyuan_id = $_GET["yiyuan_id"];
		if(empty($_GET["zhuyuan_id"])||empty($_GET["yiyuan_id"]))
		{
			$this->assign('system_info','错误：E0003，未获取正确的住院信息，请联系管理员重新登录。');
			$this->display("Home:System:showError");
			exit();
		}
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();

		if(empty($zhuyuan_basic_info))
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();
		}
		
		if(empty($zhuyuan_basic_info))
		{
			$this->assign('system_info','错误：E0004，住院号请求错误，请联系管理员重新登录。');
			$this->display("showError");
			exit();
		}
		//取得登陆帐户的住院医生的id对责任医生进行判断
		$user_id = $_SESSION['user_id'];
		$this->assign("user_id",$user_id);
		
		// 临时处理空白问题
		foreach ($zhuyuan_basic_info as $key => $value)
		{
			$zhuyuan_basic_info[$key] = trim($value);
		}

		$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
		
		$this->display();
	}
        // 更新归档状态
	public function updateGuidangZhuangtai()
	{
		$zhuyuan_id = $_POST["zhuyuan_id"];
		$guidang_zhuangtai = $_POST["guidang_zhuangtai"];

		if(empty($zhuyuan_id))
		{
			// TODO error
			echo "error";
			exit();
		}

		if(empty($guidang_zhuangtai))
		{
			// TODO error
			echo "error";
			exit();
		}

		$result = M("zhuyuan_basic_info")->where("zhuyuan_id='$zhuyuan_id'")->save($_POST);

		if($result !== false)
		{
			echo "success";
		}
		else
		{
			echo "error";
		}
	}
        
	//显示有效患者列表（即书写了病历的患者）
	public function showValuedPatientList()
	{
		if(empty($_GET["page"]))
			$current_page_number = 1;
		else
			$current_page_number = $_GET["page"];
		$one_page_amount = 10;
		
		$user_department  = $_SESSION["user_department"];
		$yiyuan_id = $_SESSION["yiyuan_id"];
		
		if(!empty($_GET["keyword"])&&$_GET["keyword"]!=="全部")
		{
			$keyword = $_GET["keyword"];
			
			//如果为管理员模式，可能为医院名称：
			if($_SESSION["user_type"]=="管理员")
			{
				$yiyuan_info = M("yiyuan_info")->where("yiyuan_name like '%$keyword%'")->find();
				if(!empty($yiyuan_info["yiyuan_id"]))
				{
					$search_yiyuan_id = $yiyuan_info["yiyuan_id"];
				}
			}
			$search_condition = "and (yiyuan_id = '$search_yiyuan_id') ";
			if($_GET["keyword"]!=="全部")
				$this->assign("keyword",$keyword);
		}
		else
		{
			$search_condition = "";
			$this->assign("keyword","全部");
		}
		
		//查看患者权限范围筛选条件：
		if($_SESSION["user_type"]=="管理员")
			$area_search_condition = "yiyuan_id like '%' ";
		else
			$area_search_condition = "yiyuan_id = '$yiyuan_id' ";
		
		//分页预览
		$total_amount = M("zhuyuan_bingli")->where($area_search_condition.$search_condition)->count();
		$total_page_number = ceil($total_amount/$one_page_amount);
		$search_result = M("zhuyuan_bingli")->where($area_search_condition.$search_condition)->order("jilu_time DESC")->limit((($current_page_number-1)*$one_page_amount).",$one_page_amount")->select();
		
		$this->assign("search_result",$search_result);
		$this->assign("current_page_number",$current_page_number);
		$this->assign("one_page_amount",$one_page_amount);
		$this->assign("total_amount",$total_amount);
		$this->assign("total_page_number",$total_page_number);
		
		//如果是管理员，展示全局情况
		if($_SESSION["user_type"]=="管理员")
		{
			//获取医院信息
			foreach($search_result as $key => $one_result)
			{
				$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$one_result["yiyuan_id"]."'")->find();
				//获取具体患者信息：
				$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id = '".$one_result["zhuyuan_id"]."' and yiyuan_id = '".$one_result["yiyuan_id"]."'")->find();
				$search_result[$key]["xingming"] = $zhuyuan_basic_info["xingming"];
				$search_result[$key]["nianling"] = $zhuyuan_basic_info["nianling"];
				$search_result[$key]["xingbie"] = $zhuyuan_basic_info["xingbie"];
				$search_result[$key]["zhuyuan_chuanghao"] = $zhuyuan_basic_info["zhuyuan_chuanghao"];
				$search_result[$key]["zhuangtai"] = $zhuyuan_basic_info["zhuangtai"];
				$search_result[$key]["ruyuan_zhenduan"] = $zhuyuan_basic_info["ruyuan_zhenduan"];
				$search_result[$key]["yiyuan_name"] = $yiyuan_info["yiyuan_name"];
			}
			$this->assign("search_result",$search_result);
			$this->display("showPatientListValued");
		}
		else
			$this->display();
	}
        //显示患者住院状态概况
	public function showPatientZhuyuanDetailUrl()
	{
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$yiyuan_id = $_GET["yiyuan_id"];
		
		if(empty($_GET["zhuyuan_id"])||empty($_GET["yiyuan_id"]))
		{
			$this->assign('system_info','错误：E0003，未获取正确的住院信息，请联系管理员重新登录。');
			$this->display("System:showError");
			exit();
		}
		
		//两种方式进行判断zhuyuan_basic_info表,一个是按zhuyuan_id一个是按zhuyuan_unique_code
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();
		if(empty($zhuyuan_basic_info))
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();
		}
		
		if(empty($zhuyuan_basic_info))
		{
			$this->assign('system_info','错误：E0004，住院号请求错误，请联系管理员重新登录。');
			$this->display("System:showError");
			exit();
		}

		//取得登陆帐户的住院医生的id对责任医生进行判断
		$user_id = $_SESSION['user_id'];
		$this->assign("user_id",$user_id);
		
		$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
		
		$this->display();
	}

	
}
?>