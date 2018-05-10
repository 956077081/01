<?php
namespace Home\Controller;
use Think\Controller;
class BingliEditorController extends Controller{
	 public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
	public function showBingli()
	{      //入院记录
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$bingli_type = $_GET["bingli_type"];
		$yiyuan_id = $_GET["yiyuan_id"];  
	
		//校验信息完整性
		if(empty($zhuyuan_id)||empty($bingli_type)||empty($yiyuan_id))
		{
			$this->assign('system_info','错误：E4001，无法获取您的病历信息，请联系管理员。');
			$this->display("System:showError");
			exit();
		}
	
		//取得住院id
		//对住院id进行判断
		$zhuyuan_basic_info_multy = M("zhuyuan_basic_info")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '$zhuyuan_id'")->find();
		$zhuyuan_basic_info_code = M("zhuyuan_basic_info")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_unique_code = '$zhuyuan_id'")->find();
	
       if(!empty($zhuyuan_basic_info_multy))
		{        //再本医院的数据查找该病例类型
			$bingli_info = M("zhuyuan_bingli")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '$zhuyuan_id' and bingli_type='$bingli_type'")->find();
                   
            if(empty($bingli_info))
			{
				//如果为空就添加 
                            //按名称查找  
				$bingli_muban_content = M("date_bingli_muban")->where("mingcheng = '$bingli_type'")->find();
                                $bingli_info["zhuyuan_id"] = $zhuyuan_id;
				$bingli_info["yiyuan_id"] = $yiyuan_id;
				$bingli_info["bingli_type"] = $bingli_type;
				
                //如果是病案首页就查找历史信息：
                if($bingli_type=="住院病案首页")
				{
					//先获取patient_id
					$patient_basic_info = M("zhuyuan_basic_info")->field("patient_id")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '$zhuyuan_id'")->find();
					$patient_id = $patient_basic_info["patient_id"];
					//再获得最近的一次住院病历信息：
					$history_binganshouye_info = M()->query("select a.content from zhuyuan_bingli as a,zhuyuan_basic_info as b where a.bingli_type = '住院病案首页' and a.zhuyuan_id = b.zhuyuan_id and a.yiyuan_id = b.yiyuan_id and b.patient_id = '$patient_id' order by b.ruyuan_time DESC limit 0,1");
					   
                    if(!empty($history_binganshouye_info))
					{
						//开始进行信息替换：
						//首先截取出来上次病案首页的有效信息：
						$last_binganshouye_patient_info = substr($history_binganshouye_info[0]["content"],strpos($history_binganshouye_info[0]["content"],"<table id=\"2\""),strpos($history_binganshouye_info[0]["content"],"<table id=\"7\"")-strpos($history_binganshouye_info[0]["content"],"<table id=\"2\""));
						//再拆分本次病历模板的有效信
						$binganshouye_muban_start_info = substr($bingli_muban_content["content"],0,strpos($bingli_muban_content["content"],"<table id=\"2\""));
						$binganshouye_muban_end_info = substr($bingli_muban_content["content"],strpos($bingli_muban_content["content"],"<table id=\"7\""));
						//最后拼装此次病历模板
						$bingli_muban_content["content"] = $binganshouye_muban_start_info.$last_binganshouye_patient_info.$binganshouye_muban_end_info;
					}
				}
                                
				$bingli_info["content"] = $bingli_muban_content["content"];
                              
			}
                      
			$bingli_info["content"] = $this->bingliInfoProcess($yiyuan_id,$zhuyuan_id,$bingli_info["content"]);                        
			$this->assign("bingli_info",$bingli_info);
                        
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '$zhuyuan_id'")->find();
			$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
			//获取浏览器类型
			if(strpos($_SERVER["HTTP_USER_AGENT"],"IE")!==false)
				$this->assign("browser_type","ie");
			//非责任医师只能编辑：
			if($zhuyuan_basic_info["zhuyuanyishi_id"]!=$_SESSION["user_id"])
				$this->assign("editable","no");
                        $this->display();
		}
		elseif(!empty($zhuyuan_basic_info_code))
		{
			$bingli_info = M("zhuyuan_bingli")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '".$zhuyuan_basic_info_code["zhuyuan_id"]."' and bingli_type='$bingli_type'")->find();
			if(empty($bingli_info))
			{
				//如果为空就添加
				$bingli_muban_content = M("date_bingli_muban")->where("mingcheng = '$bingli_type'")->find();
				$bingli_info["zhuyuan_id"] = $zhuyuan_id;
				$bingli_info["yiyuan_id"] = $yiyuan_id;
				$bingli_info["bingli_type"] = $bingli_type;
				//如果是病案首页就查找历史信息：
				if($bingli_type=="住院病案首页")
				{
					//先获取patient_id
					$patient_basic_info = M("zhuyuan_basic_info")->field("patient_id")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '$zhuyuan_id'")->find();
					$patient_id = $patient_basic_info["patient_id"];
					//再获得最近的一次住院病历信息：
					$history_binganshouye_info = M()->query("select a.content from zhuyuan_bingli as a,zhuyuan_basic_info as b where a.bingli_type = '住院病案首页' and a.zhuyuan_id = b.zhuyuan_id and a.yiyuan_id = b.yiyuan_id and b.patient_id = '$patient_id' order by b.ruyuan_time DESC limit 0,1");
					if(!empty($history_binganshouye_info))
					{
						//开始进行信息替换：
						//首先截取出来上次病案首页的有效信息：
						$last_binganshouye_patient_info = substr($history_binganshouye_info[0]["content"],strpos($history_binganshouye_info[0]["content"],"<table id=\"2\""),strpos($history_binganshouye_info[0]["content"],"<table id=\"7\"")-strpos($history_binganshouye_info[0]["content"],"<table id=\"2\""));
						//再拆分本次病历模板的有效信息
						$binganshouye_muban_start_info = substr($bingli_muban_content["content"],0,strpos($bingli_muban_content["content"],"<table id=\"2\""));
						$binganshouye_muban_end_info = substr($bingli_muban_content["content"],strpos($bingli_muban_content["content"],"<table id=\"7\""));
						//最后拼装此次病历模板
						$bingli_muban_content["content"] = $binganshouye_muban_start_info.$last_binganshouye_patient_info.$binganshouye_muban_end_info;
					}
				}
				$bingli_info["content"] = $bingli_muban_content["content"];
			}
                   
			$bingli_info["content"] = $this->bingliInfoProcess($yiyuan_id,$zhuyuan_id,$bingli_info["content"]);
			$this->assign("bingli_info",$bingli_info);
			
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_unique_code = '$zhuyuan_id'")->find();
			$this->assign("zhuyuan_basic_info",$zhuyuan_basic_info);
			
			//获取浏览器类型
			if(strpos($_SERVER["HTTP_USER_AGENT"],"IE")!==false)
				$this->assign("browser_type","ie");
			//非责任医师只能编辑：
			if($zhuyuan_basic_info["zhuyuanyishi_id"]!=$_SESSION["user_id"])
				$this->assign("editable","no");
			$this->display();
		}
		else
		{
			$this->assign('system_info','错误：E4001，无法获取您的病历信息，请联系管理员。');
			$this->display("System:showError");
			exit();
		}
	}
	public function bingliInfoProcess($yiyuan_id,$zhuyuan_id,$bingli_info_content)
	{
		//获取患者基本信息
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id = '$zhuyuan_id'")->find();
		//判断是否有患者基本信息
		if(empty($zhuyuan_basic_info))
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("yiyuan_id = '$yiyuan_id' and zhuyuan_unique_code = '$zhuyuan_id'")->find();
		}
		
		$patient_basic_info = M("patient_basic_info")->where("patient_id = '".$zhuyuan_basic_info["patient_id"]."'")->find();

		//获取医院基本信息
		$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '$yiyuan_id'")->find();
		if(empty($patient_basic_info))
		{
			//如果patient_basic_info为空时，下面的内容为空
			$patient_basic_info["error_info"] = "no patient info";
			$patient_basic_info["jiguan"] =  '';
			$patient_basic_info["minzu"] =  '';
			$patient_basic_info["chushengdi"] =  '';
			$patient_basic_info["shenfenzheng_hao"] =  '';
			$patient_basic_info["shengri"] =  '';
		}
 		$tag_info = array_merge($zhuyuan_basic_info,$patient_basic_info);
		$tag_info = array_merge($yiyuan_info,$tag_info);
		//dump($tag_info);
		foreach($tag_info as $key => $one_info)
		{
			$bingli_info_content = str_replace("{\$".$key."}",$one_info,$bingli_info_content);
		}
		
		//特定词汇替换
		$find = array("卫生院","中西医结合","医院","中医","专科");
		$yiyuan_location_area_name = str_replace($find,"",$yiyuan_info["yiyuan_name"]);
		$bingli_info_content = str_replace("{\$current_time}",date("Y-m-d H:i"),$bingli_info_content);
		$bingli_info_content = str_replace("{\$current_address}",C("hospital_city").C("hospital_sub_city").$yiyuan_location_area_name,$bingli_info_content);
		$bingli_info_content = str_replace("{\$current_address_code}",C("hospital_location_code"),$bingli_info_content);
		
		//分页处理
		//$bingli_info_content = str_replace("<center><h3>病程记录</h3></center>","<center style='page-break-before:always'><h3>病程记录</h3></center>",$bingli_info_content);
		//$bingli_info_content = str_replace("<center style='page-break-after:always'><h3>病程记录</h3></center>","<center style='page-break-before:always'><h3>病程记录</h3></center>",$bingli_info_content);
		//
		return $bingli_info_content;
	}
        public function addOneBingliShu()
	{
          
        
		$binglishu["bingli_type"] = $_POST["muban_bingli_type"];	
		$binglishu["zhuyuan_id"] = $_POST["zhuyuan_id"];
		$binglishu["yiyuan_id"] = $_POST["yiyuan_id"];
		$muban_mingcheng = $_POST["muban_mingcheng"];	
		
		$zhuyuan_bingli_del = M("zhuyuan_bingli")->where("zhuyuan_id = '".$binglishu["zhuyuan_id"]."' and bingli_type = '".$binglishu["bingli_type"]."' and yiyuan_id = '".$binglishu["yiyuan_id"]."'")->select();
		if($zhuyuan_bingli_del)
		{
			$zhuyuan_bingli_del = M("zhuyuan_bingli")->where("id = '".$zhuyuan_bingli_del['id']."'")->save($binglishu);
		}
		else
		{
			//胆囊炎
			$date_bingli_muban = M("date_bingli_muban")->where("muban_bingli_type = '".$binglishu["bingli_type"]."' and keyword_index  like  '%$muban_mingcheng%'")->find();
			if($date_bingli_muban)
				$binglishu["content"] = $date_bingli_muban["content"];
			
			$zhuyuan_bingli_del = M("zhuyuan_bingli")->add($binglishu);
		}
	}
        //删除病历树一条内容
	public function deleteOneMubanTree()
	{
		$yiyuan_id = $_POST["yiyuan_id"];
		$bingli_type = $_POST["bingli_type"];
		$zhuyuan_id = $_POST["zhuyuan_id"];
		$del_name = $_POST["del_name"];
		$muban_id = $_POST["muban_id"];
		
		if($del_name=='ruyuan_jilu')
		{
			$zhuyuan_bingli_del = M("zhuyuan_bingli")->where("zhuyuan_id = '$zhuyuan_id' and bingli_type = '$bingli_type' and yiyuan_id = '$yiyuan_id'")->delete();
			
			if($zhuyuan_bingli_del)
			{	
				echo 'success';
			}
			else
			{
				echo 'error';
			}
		}
		elseif($del_name=='ruyuan_jilu_muban')
		{
			$user_id=$_SESSION['user_id'];
			$zhuyuan_bingli_del = M("date_bingli_muban")->where("muban_id = '$muban_id' and muban_bingli_type = '$bingli_type' and suoshu_user_id='$user_id'" )->delete();
			if($zhuyuan_bingli_del)
			{
				echo 'success';
			}
			else
			{
				echo 'error';
			}
		}
	}
        //获取关键词提示信息
	public function getAutoSelectOptions()
	{
		if(empty($_GET["keyword"]))
		{
			echo "none";
		}
		else
		{
			$keyword = $_GET["keyword"];
			$keyword_optionts_info = M("data_selection_options")->where("options_index like '$keyword'")->select();
			$return_value = "";
			foreach($keyword_optionts_info as $key => $one_options)
			{
				$return_value .= "<div class='one_option' options_index=".$one_options["options_index"]." options_second_index=".$one_options["options_second_index"]."  options_content=".$one_options["options_content"]." options_second_content=".$one_options["options_second_content"].">".$one_options["options_content"]." ".$one_options["options_second_content"]."</div>";
			}
			if(empty($return_value))
			{
				echo "none";
			}
			else
			{
				echo $return_value;
			}
		}
	}
        
	//获取病历树信息
	public function getBingliTree()
	{
           
             
		$return_result = "[";
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$yiyuan_id = $_GET["yiyuan_id"];
//                
//                
//                $zhuyuan_id = 3000;
//		$yiyuan_id = 2000;
////                
//                
		if(empty($zhuyuan_id)||empty($yiyuan_id))
		{
			exit();
		}
		$bingli_info = M("zhuyuan_bingli")->field("bingli_type,second_mingcheng")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id = '$yiyuan_id' and bingli_type !='住院病案首页'")->order("bingli_type ASC")->select();
//		
//                var_dump($bingli_info)."<br>";
                
                foreach($bingli_info as $key => $one_bingli_info)
		{
			$return_result = $return_result.'{"second_mingcheng":"'.$one_bingli_info["second_mingcheng"].'","bingli_type":"'.$one_bingli_info["bingli_type"].'"},';
		}
		//根据模板内容做智能补全：
		//判断是否有入院记录
            
          
		if(strpos($_SESSION["user_department"],"产科")===false)
		{
			if(strpos($return_result,"入院记录")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"?????????? ??????? ????????","bingli_type":"入院记录"},';
			}
			if(strpos($return_result,"首次入院记录")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"?????????? ??????? ????????","bingli_type":"首次入院记录"},';
			}
			if(strpos($return_result,"病程记录")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"???????? ??????? ????????","bingli_type":"病程记录"},';
			}
			if(strpos($return_result,"出院记录")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"??????????? ?????? ?????????","bingli_type":"出院记录"},';
			}
		}
		else
		{
			if(strpos($return_result,"产科出院小结")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"","bingli_type":"产科出院小结"},';
			}
			if(strpos($return_result,"产科病史记录")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"","bingli_type":"产科病史记录"},';
			}
			if(strpos($return_result,"待(临)产记录（一）")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"","bingli_type":"待(临)产记录（一）"},';
			}
			if(strpos($return_result,"临产记录（二）")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"","bingli_type":"临产记录（二）"},';
			}
			if(strpos($return_result,"临产附页")===false)
			{
				$return_result = $return_result.'{"second_mingcheng":"","bingli_type":"临产附页"},';
			}
		}
		$return_result = substr($return_result,0,strlen($return_result)-1);
		$return_result .= "]";
		echo $return_result;
	}
        
	public function getMubanTree()
	{

		$return_result = "[";
		$muban_id = $_GET["muban_id"];
		if(empty($muban_id))
		{
			exit();
		}
		$bingli_info = M("date_bingli_muban")->field("muban_bingli_type,second_mingcheng")->where("muban_id = '$muban_id'")->order("id ASC")->select();
		foreach($bingli_info as $key => $one_bingli_info)
		{
			$return_result = $return_result.'{"second_mingcheng":"'.$one_bingli_info["second_mingcheng"].'","bingli_type":"'.$one_bingli_info["muban_bingli_type"].'"},';
		}

		$return_result = substr($return_result,0,strlen($return_result)-1);
		$return_result .= "]";
		echo $return_result;
	
	}
        

	public function previewMubanBingli()
	{	
		
		$this->showMubanBingli();
	}
    public function printBingli()
	{
		$this->showBingli();
	}
        	
	public function showMubanBingliPreview()
	{
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$yiyuan_id = $_GET["yiyuan_id"];
		$muban_id = $_GET["muban_id"];
		$muban_bingli_type = $_GET["muban_bingli_type"];
		//校验信息完整性
		if(empty($muban_id)||empty($muban_bingli_type)||empty($zhuyuan_id)||empty($yiyuan_id))
		{
			$this->assign('system_info','错误：E4001，无法获取您的病历信息，请联系管理员。');
			$this->display("System:showError");
			exit();
		}
		
		$bingli_info = M("date_bingli_muban")->where("muban_id = '$muban_id' and muban_bingli_type='$muban_bingli_type'")->find();
		$bingli_info["content"] = $this->bingliInfoProcess($yiyuan_id,$zhuyuan_id,$bingli_info["content"]);
		
		$this->assign("bingli_info",$bingli_info);

		$this->display("previewBingli");
	}
	public function showMubanBingli()
	{          
		$this->assign("show_back",$_GET["show_back"]);
		$muban_id = $_GET["muban_id"];
		$muban_bingli_type = $_GET["muban_bingli_type"];
		
		//校验信息完整性
		if(empty($muban_id)||empty($muban_bingli_type))
		{
			$this->assign('system_info','错误：E4001，无法获取您的病历信息，请联系管理员。');
			$this->display("System:showError");
			exit();
		}
		$bingli_info = M("date_bingli_muban")->where("muban_id = '$muban_id' and muban_bingli_type='$muban_bingli_type'")->find();
	
		if(empty($bingli_info["content"]))
		{
			$bingli_info["content"] = "当前模板内容为空";
		}
		
		$this->assign("bingli_info",$bingli_info);
		
		//判断是否有编辑权限：
		$user_id  = $_SESSION["user_id"];
		$yiyuan_id  = $_SESSION["yiyuan_id"];
		
		$user_department  = $_SESSION["user_department"];
		$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '$yiyuan_id' and bingqu_name='$user_department'")->find();
		$user_department_id = $muban_department_info["bingqu_id"];
	
		if($_SESSION["user_type"]=="管理员")
			$user_manage_auth="true";
		else if($user_department_id==$bingli_info["suoshu_department_id"] or $user_id==$bingli_info["suoshu_user_id"])
		{
			
			$user_manage_auth="true";
		}
		if($user_manage_auth=="true")
			$this->display();
		else
			$this->display("printMubanBingli");
	}
	public function saveBingli()
	{
		$zhuyuan_id = $_POST["zhuyuan_id"];
		$bingli_type = $_POST["bingli_type"];
		$yiyuan_id = $_POST["yiyuan_id"];
	
		//进行错误数据清除
		foreach($_POST as $key => $one_info)
		{
			$_POST[$key] = trim($_POST[$key]);
			
			//清除word特殊字符：
			//$_POST[$key] = preg_replace("/<\!--\[if(?:.|[\r\n])*?\[endif\]--\>/", "", $_POST[$key]);
			
			//清除所有a标签
			$_POST[$key] = preg_replace("/<a(?:.|[\r\n])*?\>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<\/a>/", "", $_POST[$key]);

			//清除所有无用空行
			$_POST[$key] = preg_replace("/<o:p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<\/o:p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<p><br><\/p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<br><\/p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<p><\/p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<p><u><\/u><\/p>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<div><br><\/div>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<br><\/div>/", "", $_POST[$key]);
			$_POST[$key] = preg_replace("/<div><\/div>/", "", $_POST[$key]);
			
						//优化常见错别字：
			$_POST[$key] = preg_replace("/医生鉴名/", "医生签名", $_POST[$key]);
			$_POST[$key] = preg_replace("/医生签字/", "医生签名", $_POST[$key]);
			if($bingli_type!=="住院病案首页")
			{
				$_POST[$key] = str_replace("&nbsp; ","<span style='min-width:10px;display:inline-block;height:20px'></span><span style='min-width:10px;display:inline-block;height:20px'></span>",$_POST[$key]);
				$_POST[$key] = str_replace("&nbsp;","<span style='min-width:10px;display:inline-block;height:20px'></span>",$_POST[$key]);
			}
			else
			{
				$_POST[$key] = str_replace('<span style="min-width:10px;display:inline-block;height:20px"></span>',"&nbsp;",$_POST[$key]);
			}
		}
		
		//先查询是否存在
		$bingli_count = M('zhuyuan_bingli')->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id='$zhuyuan_id' and bingli_type='$bingli_type'")->count();

		if($bingli_count==0)
			$bingshi_save_result = M('zhuyuan_bingli')->add($_POST);
		else
			$bingshi_save_result = M('zhuyuan_bingli')->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id='$zhuyuan_id' and bingli_type='$bingli_type'")->save($_POST);

		if($bingshi_save_result!==false)
		{
			echo '{ "message": "保存成功！" , "result" : "true" }';
		}
		else
		{
			echo '{ "message": "更新失败，请重新尝试或者联系管理员！" , "result" : "false" }';
		}
	}
    public function saveMubanBingli()
	{ 
		
		$muban_id=$_POST['muban_id'];
		$muban_bingli_type=$_POST['bingli_type'];

		//校验信息完整性
		if(empty($muban_id)||empty($muban_bingli_type))
		{
			echo '{ "message": "错误，E4003，更新失败，无法获取模板信息，请重新尝试或者联系管理员！" , "result" : "false" }';
			exit();
		}
		

		$bingli_count = M('date_bingli_muban')->where("muban_id='$muban_id' and muban_bingli_type='$muban_bingli_type'")->count();

		if($bingli_count==0)
		{
			echo '{ "message": "错误，E4003，更新失败，无法获取模板信息，请重新尝试或者联系管理员！" , "result" : "false" }';
			exit();
		}
		else
		{	
		
			//$bingshi_save_result = M('date_bingli_muban')->where("muban_id='$muban_id' and muban_bingli_type='$muban_bingli_type'")->save($_POST);
			$bingshi_save_result = M('date_bingli_muban')->where("muban_id='$muban_id' and muban_bingli_type='$muban_bingli_type'");
			$data['content']=$_POST['content'];
			$bingshi_save_result=$bingshi_save_result->save($data);

		}
	
	
		if($bingshi_save_result!==false)
		{
			echo '{ "message": "保存成功！" , "result" : "true" }';
		}
		else
		{
			echo '{ "message": "更新失败，请重新尝试或者联系管理员！" , "result" : "false" }';
		}
	}
	
	
}
?>