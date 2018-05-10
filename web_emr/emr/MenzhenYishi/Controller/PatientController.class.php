<?php
namespace MenzhenYishi\Controller;
use Components\TemrController;
class PatientController extends TemrController{
    public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }   
   //显示患者列表
	public function showPatientList()
	{
		if(empty($_GET["page"]))
			$current_page_number = 1;
		else
			$current_page_number = $_GET["page"];
		$one_page_amount = 10;
		
		$user_department  = $_SESSION["user_department"];
		
		//分页预览
		$total_amount = M("menzhen_basic_info")->where("menzhen_department = '$user_department'")->count();
		$total_page_number = ceil($total_amount/$one_page_amount);
		$search_result = M("menzhen_basic_info")->where("menzhen_department = '$user_department'  order by guahao_time DESC limit ".(($current_page_number-1)*$one_page_amount).",$one_page_amount")->select();
		
		$this->assign("search_result",$search_result);
		$this->assign("current_page_number",$current_page_number);
		$this->assign("one_page_amount",$one_page_amount);
		$this->assign("total_amount",$total_amount);
		$this->assign("total_page_number",$total_page_number);
		
		$this->display();
	}
}
