<?php
namespace ZhuyuanYishi\Controller;
use Think\Controller;
class JianchaController extends Controller{
	 public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
   public function showList()
	{
     
			
		//显示检查列表，需要以下参数：
		//zhuyuan_id,检查类别：入院检查、住院检查、出院检查
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$yiyuan_id = $_GET['yiyuan_id'];
		$url_condition = "";
		$sql_where ="";
		if(empty($zhuyuan_id))
		{
			$this->assign('system_info','请输入一个患者的检查号');
			   $this->display("Patient:showError");
			exit();
		}
		//获取头部信息
		$viewInfo = A('Home/Data');
		$view_info = $viewInfo->viewInfo($zhuyuan_id,$yiyuan_id);
		$this->assign('view_info',$view_info);

		$url_condition .= "/zhuyuan_id/".$zhuyuan_id;
		$this->assign('zhuyuan_id',$zhuyuan_id);
		
		if(!empty($_GET['jiancha_time']))
		{
			$jiancha_time = $_GET['jiancha_time'];
			$url_condition .= "/jiancha_time/".$jiancha_time;
		}

		if(!empty($_GET['jiancha_mingcheng']))
		{
			$jiancha_mingcheng = $_GET['jiancha_mingcheng'];
			$url_condition .= "/jiancha_mingcheng/".$jiancha_mingcheng;
		}
		
		//获取患者基本信息：
		//查询住院的基本信息
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id'")->find();
		
		if(empty($zhuyuan_basic_info))
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code = '$zhuyuan_id'")->find();
		}
		$zhuyuan_unique_code = $zhuyuan_basic_info["zhuyuan_unique_code"];
		$patient_basic_info = M("patient_basic_info")->where("patient_id = '".$zhuyuan_basic_info['patient_id']."' ")->find();
		$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
		$this->assign("patient_basic_info",$patient_basic_info);
		
		$query_model = M();
		//分页控制：
		if(!empty($_GET["page_number"]))
		{
			$page_number = $_GET["page_number"];
		}
		else
			$page_number = 1;
		
		$one_page_amount = 15;
		$start_number = ($page_number-1)*$one_page_amount;
		$sql_where = " where zhixing_id = '$zhuyuan_unique_code' and jiancha_time like '%$jiancha_time%' and jiancha_mingcheng like '%$jiancha_mingcheng%'";
	
		$total_amount = $query_model->query("select count(jiancha_mingcheng) from lis_jiancha_info".$sql_where);

		$total_amount = $total_amount[0]["count(jiancha_mingcheng)"];
		$page_amount = ceil($total_amount/$one_page_amount);
		$this->assign('total_amount',$total_amount);
		$this->assign('one_page_amount',$one_page_amount);
		$this->assign('page_number',$page_number);
		$this->assign('page_amount',$page_amount);
		
		$jiancha_result = $query_model->query("select * from lis_jiancha_info ".$sql_where." order by jiancha_time DESC LIMIT $start_number,$one_page_amount");   
		$this->assign('jiancha_result',$jiancha_result);
		$this->assign('url_condition',$url_condition);	
		$this->display();
	}
        public function showReport()
	{
		$jiancha_id = $_GET["jiancha_id"];
                
		if(empty($jiancha_id))
		{
			$this->assign('system_info','请输入一个正确的检查号');
			$this->display("System:showError");
			exit();
		}
		$jiancha_result_info = M("lis_jiancha_result")->where("jiancha_id = '$jiancha_id'")->select();
		$jiancha_info = M("lis_jiancha_info")->where("id = '$jiancha_id'")->find();
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code = '".$jiancha_info["zhixing_id"]."'")->find();
                $this->assign("jiancha_info",$jiancha_info);
		$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
		$this->assign("report_result",$jiancha_result_info);
             
		$this->display();
	}
	
    
}
?>