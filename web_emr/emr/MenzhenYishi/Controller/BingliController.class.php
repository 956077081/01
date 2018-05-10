<?php
namespace MenzhenYishi\Controller;
USE Components\TemrController;
class BingliController extends TemrController{
	 public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
   public function showBiaozhunBingli()
	{
		$menzhen_id = $_GET["menzhen_id"];
		$menzhen_basic_info = M("menzhen_basic_info")->where("menzhen_unique_code like '$menzhen_id'")->select();
		$menzhen_bingli_temp = M("menzhen_bingli_temp")->where("menzhen_id like '$menzhen_id'")->select();
		$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$menzhen_basic_info[0]["patient_id"]."'")->select();
		$this->assign("menzhen_basic_info",$menzhen_basic_info[0]);
		$this->assign("patient_basic_info",$patient_basic_info[0]);
		$this->assign("menzhen_bingli_temp",$menzhen_bingli_temp[0]);
		$this->display();
	}
	public function printBiaozhunBingli()
	{
		$menzhen_id = $_GET["menzhen_id"];
		$menzhen_basic_info = M("menzhen_basic_info")->where("menzhen_unique_code like '$menzhen_id'")->select();
		$menzhen_bingli_temp = M("menzhen_bingli_temp")->where("menzhen_id like '$menzhen_id'")->select();
		$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$menzhen_basic_info[0]["patient_id"]."'")->select();
		$this->assign("menzhen_basic_info",$menzhen_basic_info[0]);
		$this->assign("patient_basic_info",$patient_basic_info[0]);
		$this->assign("menzhen_bingli_temp",$menzhen_bingli_temp[0]);
		$this->display();
	}
	public function updateBiaozhunBingli()
	{
		//预处理一下头部标签：
		foreach($_POST as $key => $one_info)
		{
			while(true)
			{
				$temp_div_content = $_POST[$key];
				$_POST[$key] = preg_replace("/<span (?!style=\"background-color)(?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)<\/span>/","$1",$_POST[$key]);
				if($temp_div_content==$_POST[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $_POST[$key];
				$_POST[$key] = preg_replace("/<span(?:[^<])*?\>(((?!<\/span>).)*<span id=\"formula\"><span id=\"fenshi\"><span id=\"before\">((?!<\/span>).)*<\/span><span id=\"up\">((?!<\/span>).)*<\/span><span id=\"spliter\">((?!<\/span>).)*<\/span><span id=\"down\">((?!<\/span>).)*<\/span><span id=\"after\">((?!<\/span>).)*<\/span><\/span><\/span>.*)<\/span>/","$1",$_POST[$key]);
				if($temp_div_content == $_POST[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $_POST[$key];
				$_POST[$key] = preg_replace("/<b><\/b>/","",$_POST[$key]);
				$_POST[$key] = preg_replace("/<p><\/p>/","",$_POST[$key]);
				if($temp_div_content == $_POST[$key])
				{
					break;
				}
			}
			$_POST[$key] = preg_replace("/<b>\S*<\/b>\:/", "", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<strong>\S*<\/strong>\:/", "", $_POST[$key], 1);
			//去除所有的空白p标签
			$_POST[$key] = preg_replace("/<p><br><\/p>/", "", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<br><\/p>/", "</p>", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<p><\/p>/", "", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<div><br><\/div>/", "", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<br><\/div>/", "</div>", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<div><\/div>/", "", $_POST[$key], 1);
			$_POST[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px; text-indent: \d{2}px;\">/", "<span>", $_POST[$key]);
			$_POST[$key] = preg_replace("/<span style=\"font-size: \d{2}pt\">/", "<span>", $_POST[$key]);
			$_POST[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px;\">/", "<span>", $_POST[$key]);
			$_POST[$key] = preg_replace("/<font size=\"\d*\">/", "<font>", $_POST[$key]);
			$_POST[$key] = preg_replace("/<font face=\"\S*\">/", "<font>", $_POST[$key]);
			$_POST[$key] = preg_replace("/font-size:(.*?); line-height:(.*?); text-align:(.*?); text-indent:(.*?); /", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<span>(.*?)<\/span>/", "$1", $_POST[$key]);
			$_POST[$key] = preg_replace("/<br>/", "", $_POST[$key], 1);
			//保留上下标的内容;
			if(preg_match("/<span style=\"vertical-align: top; font-size: 12px;(.*?)\">/",$_POST[$key]))
			{
				$_POST[$key] = preg_replace("/<span style=\"vertical-align: top; font-size: 12px;\">(.*?)<\/span>/", "<sup>$1</sup>", $_POST[$key]);
			}
			if(preg_match("/<span style=\"vertical-align: bottom; font-size: 12px;(.*?)\">/",$_POST[$key]))
			{
				$_POST[$key] = preg_replace("/<span style=\"vertical-align: bottom; font-size: 12px;\">(.*?)<\/span>/", "<sub>$1</sub>", $_POST[$key]);
			}
			if(preg_match("/<u style=\"line-height: 1.5;\">/",$_POST[$key]))
			{
				$_POST[$key] = preg_replace("/<u style=\"line-height: 1.5;\">(.*?)<\/u>/", "$1", $_POST[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/",$_POST[$key]))
			{
				$_POST[$key] = preg_replace("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/","$1",$_POST[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/",$_POST[$key]))
			{
				$_POST[$key] = preg_replace("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/","$1",$_POST[$key]);
			}
			if(preg_match("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/",$_POST[$key]))
			{
				$_POST[$key]= preg_replace("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/","",$_POST[$key]);
			}
			$_POST[$key] = preg_replace("/<p><u><\/u><\/p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<div(?:.|[\r\n])*?>/","<div>", $_POST[$key]);
			$_POST[$key] = preg_replace("/<a(?:.|[\r\n])*?>/","", $_POST[$key]);
			$_POST[$key] = preg_replace("/<\/a>/","", $_POST[$key]);
			/*
			$_POST[$key] = preg_replace("/<font(?:.|[\r\n])*?>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<\/font>\"/", "", $_POST[$key]);
			*/
			$_POST[$key] = preg_replace("/<p(?:.|[\r\n])*?>/","<p>", $_POST[$key]);
			$_POST[$key] = preg_replace("/<o:p>/","", $_POST[$key]);
			$_POST[$key] = preg_replace("/<\/o:p>/","", $_POST[$key]);
			$_POST[$key] = preg_replace("/<p><\/span><\/p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<p><\/p>/", "", $_POST[$key]);
			//增加关键词标签
			$TextProcessingEngine = A('Common/TextProcessingEngine');
			$_POST[$key] = $TextProcessingEngine->analyseText($_POST[$key]);
		}
		$_POST['menzhen_id'] = $_POST['zhuyuan_id'];
		$_POST['flag'] = "save";
		//先查询是否存在
		$zhuyuan_bingshi_count = M('menzhen_bingli_temp')->where("menzhen_id like '".$_POST['menzhen_id']."'")->count();
		if($zhuyuan_bingshi_count==0)
			$zhuyuan_bingshi_save_result = M('menzhen_bingli_temp')->add($_POST);
		else
			$zhuyuan_bingshi_save_result = M('menzhen_bingli_temp')->where("menzhen_id like '".$_POST['menzhen_id']."'")->save($_POST);

		if($zhuyuan_ruyuantigejiancha_save_result!==false || $zhuyuan_bingshi_save_result!==false)
		{
			echo '{ "message": "保存成功！" , "result" : "true" }';
		}
		else
		{
			echo '{ "message": "更新失败，请重新尝试或者联系管理员！" , "result" : "false" }';
		}
	}
}
