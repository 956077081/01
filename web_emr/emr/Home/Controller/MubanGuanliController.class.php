<?php
namespace Home\Controller;
use Think\Controller;

class MubanGuanliController  extends Controller
{	
	public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
	 public function addMubanBingli()
	 {
	
		$yiyuan_id = $_POST["yiyuan_id"];
		$muban_leixing = $_POST["muban_leixing"];
		$muban_bingli_type = $_POST["muban_bingli_type"];
		$muban_kebie = $_POST["muban_kebie"];
		$mingcheng = $_POST["mingcheng"];
		if(empty($yiyuan_id)||empty($muban_leixing)||empty($muban_kebie)||empty($mingcheng)||empty($muban_bingli_type))
		{
			$this->assign('system_info','错误：E0001，您的模板信息不完整，请重新添加模板。');
			$this->display("System:showError");
			exit();
		}
		//批量模板添加功能：
		if($muban_bingli_type=="批量添加")
		{
			$muban_bingli_type_list[0] = "入院记录";
			$muban_bingli_type_list[1] = "首次入院记录";
			$muban_bingli_type_list[2] = "病程记录";
		}
		else
		{
			$muban_bingli_type_list[0] = $muban_bingli_type;
		}
		foreach($muban_bingli_type_list as $one_muban_bingli_type)
		{
			$new_muban_info = "";
			//组织新添模板信息：
			$new_muban_info["muban_leixing"] = $muban_leixing;
			$new_muban_info["muban_kebie"] = $muban_kebie;
			$new_muban_info["mingcheng"] = $mingcheng;
			$new_muban_info["keyword_index"] = $mingcheng;
			$new_muban_info["yiyuan_id"] = $yiyuan_id;
			$new_muban_info["content"] = "";
			$new_muban_info["muban_bingli_type"] = $one_muban_bingli_type;
			$new_muban_info["second_mingcheng"] = $_POST["second_mingcheng"];
			
			//查重：
			$muban_count = M("date_bingli_muban")->where("mingcheng = '$mingcheng' and muban_bingli_type = '$muban_bingli_type' and (yiyuan_id = '$yiyuan_id' or '$muban_leixing'='公共模板') and muban_kebie = '$muban_kebie'")->count();
			if($muban_count==0)
			{
				//获取muban_id或者生成muban_id:
				$muban_id_info = M("date_bingli_muban")->field("muban_id")->where("mingcheng = '$mingcheng' and (yiyuan_id = '$yiyuan_id' or '$muban_leixing'='公共模板') and muban_kebie = '$muban_kebie'")->find();
				if(!empty($muban_id_info["muban_id"]))
				{
					$new_muban_info["muban_id"] = $muban_id_info["muban_id"];
				}
				else
				{
					$new_muban_info["muban_id"] = strtotime(date("Y-m-d h:i:s"));
				}

					$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '".$_SESSION["yiyuan_id"]."' and bingqu_name='".$_SESSION["user_department"]."'")->find();
					$new_muban_info["suoshu_department_id"] = $muban_department_info["bingqu_id"];
				
					$new_muban_info["suoshu_user_id"] = $_SESSION["user_id"];
		
				if($_POST["if_user_default_format"]=="true")
				{
					$bingli_muban_content = M("date_bingli_muban")->where("mingcheng = '".$new_muban_info["muban_bingli_type"]."'")-> order ('id') ->find();
					if(!empty($bingli_muban_content["content"]))
						$new_muban_info["content"] = $bingli_muban_content["content"];
				}
				M("date_bingli_muban")->add($new_muban_info);
			}
			else
			{
				$this->assign('system_info','已经存在此模板。');
				$this->display("System:showError");
				exit();
			}
		}
		
		
		$this->assign('system_info','模板添加成功:)');
		$this->display("System:showRight");
		exit();
	}
	
	public function fuzhiMubanBingli()
	{
               
		$yiyuan_id = $_POST["yiyuan_id"];
		$muban_leixing = $_POST["muban_leixing"];
		$s = $_POST["muban_bingli_type"];
		$muban_bingli_type=urldecode($s);
		
		$muban_kebie = $_POST["muban_kebie"];
		$mingcheng = $_POST["mingcheng"];
		$muban_id = $_POST["muban_id"];
		if(empty($muban_id)||empty($yiyuan_id)||empty($muban_leixing)||empty($muban_kebie)||empty($mingcheng)||empty($muban_bingli_type))
		{
			$this->assign('system_info','错误：E0001，您的模板信息不完整，请重新添加模板。');
			$this->display("System:showError");
			exit();
		}
		//批量模板添加功能：
		if($muban_bingli_type=="批量复制")
		{
			$muban_bingli_type_list[0] = "入院记录";
			$muban_bingli_type_list[1] = "首次入院记录";
			$muban_bingli_type_list[2] = "病程记录";
		}
		else
		{
			$muban_bingli_type_list[0] = $muban_bingli_type;
		}
		foreach($muban_bingli_type_list as $one_muban_bingli_type)
		{
			$new_muban_info = "";
			//组织新添模板信息：
			$new_muban_info["muban_leixing"] = $muban_leixing;
			$new_muban_info["muban_kebie"] = $muban_kebie;
			$new_muban_info["mingcheng"] = $mingcheng;
			$new_muban_info["keyword_index"] = $mingcheng;
			$new_muban_info["yiyuan_id"] = $yiyuan_id;
			$new_muban_info["content"] = "";
			$new_muban_info["muban_bingli_type"] = $one_muban_bingli_type;
			$new_muban_info["second_mingcheng"] = $_POST["second_mingcheng"];
			
			//获取内容：
			$muban_content = M("date_bingli_muban")->where("muban_bingli_type = '$one_muban_bingli_type' and muban_id = '$muban_id'")->find();
			$new_muban_info["content"] = $muban_content["content"];
			//查重：
			$muban_count = M("date_bingli_muban")->where("mingcheng = '$mingcheng' and muban_bingli_type = '$muban_bingli_type' and (yiyuan_id = '$yiyuan_id' or '$muban_leixing'='公共模板') and muban_kebie = '$muban_kebie'")->count();
			if($muban_count==0)
			{
				//获取muban_id或者生成muban_id:
				$muban_id_info = M("date_bingli_muban")->field("muban_id")->where("mingcheng = '$mingcheng' and (yiyuan_id = '$yiyuan_id' or '$muban_leixing'='公共模板') and muban_kebie = '$muban_kebie'")->find();
				if(!empty($muban_id_info["muban_id"]))
				{
					$new_muban_info["muban_id"] = $muban_id_info["muban_id"];
				}
				else
				{
					$new_muban_info["muban_id"] = strtotime(date("Y-m-d h:i:s"));
				}
				//确定所属医师或者所属科室信息：
				if($muban_leixing=="科室模板")
				{
					$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '".$_SESSION["yiyuan_id"]."' and bingqu_name='".$_SESSION["user_department"]."'")->find();
					$new_muban_info["suoshu_department_id"] = $muban_department_info["bingqu_id"];
				}
				if($muban_leixing=="个人模板")
				{
					$new_muban_info["suoshu_user_id"] = $_SESSION["user_id"];
				}
				//为模板添加默认格式：
				if($_POST["if_user_default_format"]=="true")
				{
					$bingli_muban_content = M("date_bingli_muban")->where("mingcheng = '".$new_muban_info["muban_bingli_type"]."'")->find();
					if(!empty($bingli_muban_content["content"]))
						$new_muban_info["content"] = $bingli_muban_content["content"];
				}
				M("date_bingli_muban")->add($new_muban_info);
			}
			else
			{
				$this->assign('system_info','已经存在此模板。');
				$this->display("System:showError");
				exit();
			}
		}
		
		
		$this->assign('system_info','模板复制成功:)');
		$this->display("System:showRight");
		exit();
	}

	public function fuzhiMubanBingliAjax()
	{
          
		$yiyuan_id = $_POST["yiyuan_id"];
		$muban_leixing = $_POST["muban_leixing"];
		$muban_bingli_type = $_POST["muban_bingli_type"];
		$muban_kebie = $_POST["muban_kebie"];
		$mingcheng = $_POST["mingcheng"];
		$muban_id = $_POST["muban_id"];
		if(empty($muban_id)||empty($yiyuan_id)||empty($muban_leixing)||empty($muban_kebie)||empty($mingcheng)||empty($muban_bingli_type))
		{
			echo "error";
			exit();
		}
		//批量模板添加功能：
		if($muban_bingli_type=="批量复制")
		{
			$muban_bingli_type_list[0] = "入院记录";
			$muban_bingli_type_list[1] = "首次入院记录";
			$muban_bingli_type_list[2] = "病程记录";
		}
		else
		{
			$muban_bingli_type_list[0] = $muban_bingli_type;
		}
		foreach($muban_bingli_type_list as $one_muban_bingli_type)
		{
			$new_muban_info = "";
			//组织新添模板信息：
			$new_muban_info["muban_leixing"] = $muban_leixing;
			$new_muban_info["muban_kebie"] = $muban_kebie;
			$new_muban_info["mingcheng"] = $mingcheng;
			$new_muban_info["keyword_index"] = $mingcheng;
			$new_muban_info["yiyuan_id"] = $yiyuan_id;
			$new_muban_info["content"] = "";
			$new_muban_info["muban_bingli_type"] = $one_muban_bingli_type;
			$new_muban_info["second_mingcheng"] = $_POST["second_mingcheng"];
			
			//获取内容：
			$muban_content = M("date_bingli_muban")->where("muban_bingli_type = '$one_muban_bingli_type' and muban_id = '$muban_id'")->find();
			$new_muban_info["content"] = $muban_content["content"];
			//查重：
			$muban_count = M("date_bingli_muban")->where("mingcheng = '$mingcheng' and muban_bingli_type = '$muban_bingli_type' and (yiyuan_id = '$yiyuan_id' or '$muban_leixing'='公共模板') and muban_kebie = '$muban_kebie'")->count();
			if($muban_count==0)
			{
				//获取muban_id或者生成muban_id:
				$muban_id_info = M("date_bingli_muban")->field("muban_id")->where("mingcheng = '$mingcheng' and (yiyuan_id = '$yiyuan_id' or '$muban_leixing'='公共模板') and muban_kebie = '$muban_kebie'")->find();
				if(!empty($muban_id_info["muban_id"]))
				{
					$new_muban_info["muban_id"] = $muban_id_info["muban_id"];
				}
				else
				{
					$new_muban_info["muban_id"] = strtotime(date("Y-m-d h:i:s"));
				}
				//确定所属医师或者所属科室信息：
				// if($muban_leixing=="科室模板")
				// {
					$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '".$_SESSION["yiyuan_id"]."' and bingqu_name='".$_SESSION["user_department"]."'")->find();
					$new_muban_info["suoshu_department_id"] = $muban_department_info["bingqu_id"];
				// }
				// else
				// {
					$new_muban_info["suoshu_user_id"] = $_SESSION["user_id"];
				// }

				M("date_bingli_muban")->add($new_muban_info);
			}
			else
			{
				echo "error";
				exit();
			}
		}
		
		
		echo "success";
	}
	
	public  function getMubanList()
	{   
                
                
        
		$muban_kebie = $_GET["muban_kebie"];
		$user_id = $_GET["user_id"];
		$user_department = $_GET["user_department"];
		$yiyuan_id = $_GET["yiyuan_id"];
		$muban_search_keyword = $_GET["muban_search_keyword"];
      
            
                if(empty($muban_kebie)||empty($user_id)||empty($user_department)||empty($yiyuan_id))
		{
			echo "<ul><li>错误：E5002，您所请求的模板信息不完整，请重新登录系统在尝试获取模板。</li></ul>";
			exit();
		}
		
		if(!empty($muban_search_keyword))
		{
			$muban_keyword_sql = " and mingcheng like '%$muban_search_keyword%'";
		}
		
		echo "<ul>";

		$linchuang_lujing_info_a = M("date_bingli_muban")->where("muban_kebie = '$muban_kebie' and suoshu_user_id='$user_id' ".$muban_keyword_sql)->group("muban_id")->order("mingcheng DESC")->select();
		foreach($linchuang_lujing_info_a as $key => $one)
		{
			$linchuang_lujing_info_a[$key]["suoshu_keshi"] = "我的模版";
		}
		$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '$yiyuan_id' and bingqu_name='$user_department'")->find();
		$suoshu_department_id = $muban_department_info["bingqu_id"];
		$linchuang_lujing_info_b = M("date_bingli_muban")->where("muban_kebie = '$muban_kebie' and suoshu_department_id='$suoshu_department_id' ".$muban_keyword_sql)->group("muban_id")->order("mingcheng DESC")->select();
		foreach($linchuang_lujing_info_b as $key => $one)
		{
			$linchuang_lujing_info_b[$key]["suoshu_keshi"] = "科室模版";
		}
		$linchuang_lujing_info_c = M("date_bingli_muban")->where("muban_kebie like '$muban_kebie' ".$muban_keyword_sql)->group("muban_id")->order("mingcheng DESC")->select();
		
		$linchuang_lujing_info = Array();
		
		if(!empty($linchuang_lujing_info_a))
			$linchuang_lujing_info = $linchuang_lujing_info_a;
		if(!empty($linchuang_lujing_info_b))
			$linchuang_lujing_info = array_merge($linchuang_lujing_info,$linchuang_lujing_info_b);
		if(!empty($linchuang_lujing_info_c))
			$linchuang_lujing_info = array_merge($linchuang_lujing_info,$linchuang_lujing_info_c);

		echo "<li>快速查询：
				<input type='' name='muban_search_keyword' value='".$muban_search_keyword."'/>
				<input type='button' name='search_muban' value='查询' class='search_button'/>
				<input type='button' name='show_all_muban' value='显示全部' class='search_button'/>
			  </li>";
			  
		$temp_keshi = "its null about it";
              
		for($i=0;$i<sizeof($linchuang_lujing_info);$i++)
		{
			if($linchuang_lujing_info[$i]["suoshu_keshi"]!=$temp_keshi)
			{
				$keshi = $linchuang_lujing_info[$i]["suoshu_keshi"];
				if($linchuang_lujing_info[$i]["suoshu_keshi"]=="")
				{
					$keshi = "公共模板";
				}
				echo '<li class="keshi"  keshi_name="'.$keshi.'"><div class="list_title"><div class="list_title_span">'.$keshi.'</div></div></li>';
			}
			if($keshi=="我的模版"||$keshi=="公共模板")
			{
				echo "<li class='bingzhong' style='display:list-item;' keshi_item='".$keshi."' zhongwen_mingcheng='".$linchuang_lujing_info[$i]["mingcheng"]."' muban_id='".$linchuang_lujing_info[$i]["muban_id"]."'>".$linchuang_lujing_info[$i]["mingcheng"]."</li>";
			}
			else
			{
				echo "<li class='bingzhong' style='display:none;' keshi_item='".$keshi."' zhongwen_mingcheng='".$linchuang_lujing_info[$i]["mingcheng"]."' muban_id='".$linchuang_lujing_info[$i]["muban_id"]."'>".$linchuang_lujing_info[$i]["mingcheng"]."</li>";
			}
			
			$temp_keshi = $linchuang_lujing_info[$i]["suoshu_keshi"];
		}
		if(sizeof($linchuang_lujing_info)==0)
			echo "<li>当前系统中还没有添加任何此科别的模板:)</li>";
		echo "</ul>";
	}
	
	public function getMubanContent()
	{
		
		$muban_id = $_GET["muban_id"];
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$yiyuan_id = $_GET["yiyuan_id"];
		if(empty($muban_id)||empty($zhuyuan_id)||empty($yiyuan_id))
		{
			echo "<ul><li>错误：E5003，您所请求的模板信息不完整，请重新进行模板选择。</li></ul>";
			exit();
		}
		$muban_info = M("date_bingli_muban")->where("muban_id like '$muban_id'")->select();
	
		echo '<form class="linchuang_lujing_content_form" id="linchuang_lujing_content_form" method="post" action="http://'.C("WEB_HOST").'/web_emr/Home/MubanGuanli/setMuban">';
			echo "<input type='hidden' name='zhuyuan_id' value='$zhuyuan_id' />";
			echo "<input type='hidden' name='yiyuan_id' value='$yiyuan_id' />";
			echo "<input type='hidden' name='muban_id' value='".$muban_id."' ></input>";
			echo '<table width="100%">';
			echo "当前模板名称：".$muban_info[0]["mingcheng"];
				echo '<tr>';
					echo "<td class='content_type'>";
						echo "请选择需要快速使用的【病历模板】：";
					echo "</td>";
				echo "</tr>";
				foreach($muban_info as $key => $one_muban_info)
				{
					echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='muban_bingli_type_array[]' value='".$one_muban_info["muban_bingli_type"]."' checked='true'>".$one_muban_info["muban_bingli_type"]."</input><a class='bingli_preview' muban_id='$muban_id' muban_bingli_type='".$one_muban_info["muban_bingli_type"]."'>[预览]</a>";
						echo "</td>";
					echo "</tr>";
				}
			echo "</table>";
		echo "</form>";
	}
	
	public function setMuban()
	{

		$muban_id = $_POST["muban_id"];
		$zhuyuan_id = $_POST["zhuyuan_id"];
		$yiyuan_id = $_POST["yiyuan_id"];
		if(empty($muban_id)||empty($zhuyuan_id))
		{
			$this->assign('system_info','错误：E5003，模板设置出错，请重新设置模板或者联系管理员。');
			$this->display("System:showError");
			exit();
		}
		//获取模板内容：
		$muban_info = M("date_bingli_muban")->where("muban_id = '$muban_id'")->select();
		foreach($muban_info as $one_muban)
		{
			if(in_array($one_muban["muban_bingli_type"],$_POST["muban_bingli_type_array"]))
			{
				$bingli_type = $one_muban["muban_bingli_type"];
				$new_bingli_info["yiyuan_id"] = $yiyuan_id;
				$new_bingli_info["zhuyuan_id"] = $zhuyuan_id;
				$new_bingli_info["bingli_type"] = $one_muban["muban_bingli_type"];
				$new_bingli_info["second_mingcheng"] = $one_muban["second_mingcheng"];
				$new_bingli_info["content"] = $one_muban["content"];
				$new_bingli_info["jilu_yisheng_id"] = $_SESSION["user_id"];
				$new_bingli_info["jilu_yisheng_name"] = $_SESSION["user_name"];
				$new_bingli_info["jilu_time"] = date("Y-m-d H:i");
	
				$bingli_count = M('zhuyuan_bingli')->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id='$zhuyuan_id' and bingli_type='$bingli_type'")->count();
				if($bingli_count==0)
					$bingshi_save_result = M('zhuyuan_bingli')->add($new_bingli_info);
				else
					$bingshi_save_result = M('zhuyuan_bingli')->where("yiyuan_id = '$yiyuan_id' and zhuyuan_id='$zhuyuan_id' and bingli_type='$bingli_type'")->save($new_bingli_info);
			}
			else
			{
			}
		}
		
		if(!empty($muban_info))
		{
			$update_zhuyuan_basic_info["linchuanglujing_mingcheng"] = $muban_info[0]["mingcheng"]; 
			M("zhuyuan_basic_info")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id='$yiyuan_id'")->save($update_zhuyuan_basic_info);
			$this->assign('system_info','模板病历添加成功:)');
			$this->display("System:showRight");
			exit();
		}
	}
	
	public function showMubanList()
	{
	    
		//判断页数
		if(empty($_GET["page"]))
		{
			$current_page_number = 1;
		}
		else
		{
			$current_page_number = $_GET["page"];
		}
		$one_page_amount = 10;
		$user_id  = $_SESSION["user_id"];
		$yiyuan_id  = $_SESSION["yiyuan_id"];
		$user_department  = $_SESSION["user_department"];
		$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '$yiyuan_id' and bingqu_name='$user_department'")->find();
		$user_department_id = $muban_department_info["bingqu_id"];
		//对关键字进行判断
		if(!empty($_GET["keyword"])&&$_GET["keyword"]!=="全部")
		{
			$keyword = $_GET["keyword"];
			$search_condition = " and (mingcheng like '%$keyword%' or muban_kebie like '%$keyword%' or muban_bingli_type like '%$keyword%')";
			$this->assign("keyword",$keyword);
		}
		else
		{
			$search_condition = "";
			$this->assign("keyword","全部");
		}
		$mingcheng = $_GET["mingcheng"];
		$muban_leixing = $_GET["muban_leixing"];
		$muban_kebie = $_GET["muban_kebie"];
		$suoshu_yisheng = $_GET["suoshu_yisheng"];
		$suoshu_keshi = $_GET["suoshu_keshi"];
		$suoshu_yiyuan = $_GET["suoshu_yiyuan"];
		$condition = "1=1";
		$url_params = "";
		if(!empty($mingcheng))
		{	
			$condition .= " and mingcheng like '%$mingcheng%'";
			$url_params .= "/mingcheng/$mingcheng";
			$this->assign("mingcheng",$mingcheng);
		}
		
		if(!empty($muban_leixing))
		{
			$condition .= " and muban_leixing like '%$muban_leixing%'";
			if($muban_leixing == "科室模板" && $_SESSION["user_type"]!="管理员")
			{
				$condition .= " and suoshu_department_id = '$user_department_id' ";
			}
			else if($muban_leixing == "个人模板" && $_SESSION["user_type"]!="管理员")
			{
				$condition .= " and suoshu_user_id = '$user_id'";
			}
			$url_params .= "/muban_leixing/$muban_leixing";
			$this->assign("muban_leixing",$muban_leixing);
		}
		else if($_SESSION["user_type"]!="管理员")
		{
			$condition .= " and (suoshu_department_id = '$user_department_id' or suoshu_user_id = '$user_id' or muban_leixing = '公共模板' or  muban_leixing = '系统模板') ";
		}
	
		if(!empty($muban_kebie))
		{
			$condition .= " and muban_kebie like '%$muban_kebie%'";
			$url_params .= "/muban_kebie/$muban_kebie";
			$this->assign("muban_kebie",$muban_kebie);
		}
		if(!empty($suoshu_yisheng))
		{
			$yiyuan_user = M("yiyuan_user")->field("user_id")->where("user_name like '%$suoshu_yisheng%'")->select();
			if(!empty($yiyuan_user))
			{
				$condition .= " and (";
				for ($i = 0;$i < count($yiyuan_user);$i++)
				{
					if($i!=0)
					{
						$condition .= " or ";
					}
					$condition .= "suoshu_user_id = '".$yiyuan_user[$i]["user_id"]."'";
				}
				$condition .= ")";
			}
			else
			{
				$condition .= " and 1=2";
			}
			$url_params .= "/suoshu_yisheng/$suoshu_yisheng";
			$this->assign("suoshu_yisheng",$suoshu_yisheng);
		}
		if(!empty($suoshu_keshi))
		{
			$yiyuan_department_info = M("yiyuan_department_info")->field("bingqu_id")->where("bingqu_name like '%$suoshu_keshi%'")->select();
			if(!empty($yiyuan_department_info))
			{
				$condition .= " and (";
				for ($i = 0;$i < count($yiyuan_department_info);$i++)
				{
					if($i!=0)
					{
						$condition .= " or ";
					}
					$condition .= "suoshu_department_id = '".$yiyuan_department_info[$i]["bingqu_id"]."'";
				}
				$condition .= ")";
			}
			else
			{
				$condition .= " and 1=2";
			}
			$url_params .= "/suoshu_keshi/$suoshu_keshi";
			$this->assign("suoshu_keshi",$suoshu_keshi);
		}
		if(!empty($suoshu_yiyuan))
		{
			$yiyuan_info = M("yiyuan_info")->field("yiyuan_id")->where("yiyuan_name like '%$suoshu_yiyuan%'")->select();
			if(!empty($yiyuan_info))
			{
				$condition .= " and (";
				for ($i = 0;$i < count($yiyuan_info);$i++)
				{
					if($i!=0)
					{
						$condition .= " or ";
					}
					$condition .= "yiyuan_id = '".$yiyuan_info[$i]["yiyuan_id"]."'";
				}
				$condition .= ")";
			}
			else
			{
				$condition .= " and 1=2";
			}
			$url_params .= "/suoshu_yiyuan/$suoshu_yiyuan";
			$this->assign("suoshu_yiyuan",$suoshu_yiyuan);
		}
		
	
		$total_records = M("date_bingli_muban")->where($condition)->group("muban_id")->select();
		$total_amount = count($total_records);
		$total_page_number = ceil($total_amount/$one_page_amount);
         
		$search_result=M()->query("SELECT * 
                 from date_bingli_muban
                 where ".$condition." group by muban_id order by id DESC limit ".(($current_page_number-1)*$one_page_amount).",$one_page_amount");
         
		foreach($search_result as $key=>$one_result)
		{
			if(!empty($one_result["suoshu_user_id"]))
			{
				$user_info = M("yiyuan_user")->where("user_id = '".$one_result["suoshu_user_id"]."'")->find();
				$suoshu_user_name = $user_info["user_name"];
			}
			if(!empty($one_result["suoshu_department_id"]))
			{
				$department_info = M("yiyuan_department_info")->where("bingqu_id = '".$one_result["suoshu_department_id"]."'")->find();
				$suoshu_department_name = $department_info["bingqu_name"];
			}
			if(!empty($one_result["yiyuan_id"]))
			{
				$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$one_result["yiyuan_id"]."'")->find();
				$suoshu_yiyuan_name = $yiyuan_info["yiyuan_name"];
			}
			$search_result[$key]["suoshu_user_name"] =$suoshu_user_name;
			$search_result[$key]["suoshu_department_name"] = $suoshu_department_name;
			$search_result[$key]["suoshu_yiyuan_name"] = $suoshu_yiyuan_name;
			//修改的部分    初始化
			$suoshu_user_name="";
			$suoshu_department_name="";
			$suoshu_yiyuan_name="";
		}

		$this->assign("url_params",$url_params);
		$this->assign("search_result",$search_result);
		$this->assign("current_page_number",$current_page_number);
		$this->assign("one_page_amount",$one_page_amount);
		$this->assign("total_amount",$total_amount);
		$this->assign("total_page_number",$total_page_number);
		$this->display();
	}

	public function showMubanDetail()
	{
		
		
            
		if(empty($_GET["muban_id"]))
		{
			$this->assign('system_info','错误：E5003，您的模板信息不完整，请重新选择模板。');
			$this->display("System:showError");
			exit();
		}
		$muban_id = $_GET["muban_id"];
		$mingcheng = $_GET["mingcheng"];
		$muban_leixing = $_GET["muban_leixing"];
		$muban_kebie = $_GET["muban_kebie"];
		$muban_bingli_type = $_GET["muban_bingli_type"];
		
	$sql="muban_id = '$muban_id' and mingcheng = '$mingcheng' and muban_leixing = '$muban_leixing' and muban_kebie = '$muban_kebie' and muban_bingli_type = '$muban_bingli_type'";
		if(empty($muban_kebie)){
			$sql="muban_id = '$muban_id' and mingcheng = '$mingcheng' and muban_leixing = '$muban_leixing'  and muban_bingli_type = '$muban_bingli_type'";
		}else{
			$sql="muban_id = '$muban_id' and mingcheng = '$mingcheng' and muban_leixing = '$muban_leixing' and muban_kebie = '$muban_kebie' and muban_bingli_type = '$muban_bingli_type'";
		}
		
		$muban_info = M("date_bingli_muban")->where($sql)->select();
	
		$muban_info_detail = M("date_bingli_muban")->where("muban_id = '$muban_id'")->select();
		
		if(!empty($muban_info[0]["suoshu_user_id"]))
		{
			$user_info = M("yiyuan_user")->where("user_id = '".$muban_info[0]["suoshu_user_id"]."'")->find();
			$muban_info[0]["suoshu_user_name"] = $user_info["user_name"];
		}
		if(!empty($muban_info[0]["suoshu_department_id"]))
		{
			$department_info = M("yiyuan_department_info")->where("bingqu_id = '".$muban_info[0]["suoshu_department_id"]."'")->find();
			$muban_info[0]["suoshu_department_name"] = $department_info["bingqu_name"];
		}
		if(!empty($muban_info[0]["yiyuan_id"]))
		{
			$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$muban_info[0]["yiyuan_id"]."'")->find();
			$muban_info[0]["suoshu_yiyuan_name"] = $yiyuan_info["yiyuan_name"];
		}
			
		$this->assign("muban_info",$muban_info[0]);
		$this->assign("muban_info_detail",$muban_info_detail);
		
		//取得模板（muban_id）相同的id号
		foreach($muban_info_detail as $k=>$v)
		{
			$muban_info_id .= $v['id']."#|#";
		}
		$this->assign("muban_info_id",$muban_info_id);
		
		//获取模板管理权限：
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
		
		$this->assign("user_manage_auth",$user_manage_auth);
		
		$this->display();
	}

	// 显示一组模板详细信息(muban_id相同)
	public function showMubanDetailGroup()
	{

		if(empty($_GET["muban_id"]))
		{
			$this->assign('system_info','错误：E5003-2，您的模板信息不完整，请重新选择模板。');
			$this->display("System:showError");
			exit();
		}
		$muban_id = $_GET["muban_id"];
		
		$muban_info = M("date_bingli_muban")->where("muban_id = '$muban_id'")->select();
	
		if(!empty($muban_info[0]["suoshu_user_id"]))
		{
			$user_info = M("yiyuan_user")->where("user_id = '".$muban_info[0]["suoshu_user_id"]."'")->find();
			$muban_info[0]["suoshu_user_name"] = $user_info["user_name"];
		}
		if(!empty($muban_info[0]["suoshu_department_id"]))
		{
			$department_info = M("yiyuan_department_info")->where("bingqu_id = '".$muban_info[0]["suoshu_department_id"]."'")->find();
			$muban_info[0]["suoshu_department_name"] = $department_info["bingqu_name"];
		}
		if(!empty($muban_info[0]["yiyuan_id"]))
		{
			$yiyuan_info = M("yiyuan_info")->where("yiyuan_id = '".$muban_info[0]["yiyuan_id"]."'")->find();
			$muban_info[0]["suoshu_yiyuan_name"] = $yiyuan_info["yiyuan_name"];
		}
		
		$this->assign("muban_info",$muban_info[0]);
				
		//获取模板管理权限：
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
		
		$this->assign("user_manage_auth",$user_manage_auth);
		
		$this->display();
	}
	
	public function updateMubanBingli()
	{
      
		//取得post的值
		$muban_info_id = $_POST['muban_info_id'];
		$muban_leixing = $_POST['muban_leixing'];
		$muban_kebie = $_POST['muban_kebie'];
		$muban_bingli_type = $_POST['muban_bingli_type'];
		$second_mingcheng = $_POST['second_mingcheng'];
		$mingcheng = $_POST['mingcheng'];
		$muban_id = $_POST['muban_id'];
		$muban_id_info = $_POST['muban_id_info'];
		
		//分解相关联的muban_id相同的id
		$muban_info_id = explode("#|#",$muban_info_id);
		$muban_info_id = array_filter($muban_info_id);
		
		//要进行替换的数组值
		$muban_arr = array();
		$muban_arr['muban_leixing'] = $muban_leixing;
		if($muban_leixing=='个人模板')
		{
			$muban_arr['suoshu_user_id'] = $_SESSION['user_id'];
			$muban_arr["suoshu_department_id"] = "";
		}
		if($muban_leixing=='科室模板')
		{
			$muban_department_info = M("yiyuan_department_info")->where("yiyuan_id = '".$_SESSION["yiyuan_id"]."' and bingqu_name='".$_SESSION["user_department"]."'")->find();
			$muban_arr["suoshu_department_id"] = $muban_department_info["bingqu_id"];
			$muban_arr['suoshu_user_id'] = "";
		}
		$muban_arr['muban_kebie'] = $muban_kebie;
		$muban_arr['mingcheng'] = $mingcheng;
		$muban_arr['keyword_index'] = $mingcheng;
		
		//进行替换更新操作
		foreach($muban_info_id as $k=>$v)
		{
			if($v==$muban_id)
			{
				$muban_arr['second_mingcheng'] = $second_mingcheng;
				$muban_arr['muban_bingli_type'] = $muban_bingli_type;
				//为模板添加默认格式：
				if($_POST["if_user_default_format"]=="true")
				{
					$bingli_muban_content = M("date_bingli_muban")->where("mingcheng = '".$muban_arr["muban_bingli_type"]."'")->find();
					if(!empty($bingli_muban_content["content"]))
					{
						$muban_arr["content"] = $bingli_muban_content["content"];
					}
				}
				$muban_info = M("date_bingli_muban")->where("id = '$v'")->save($muban_arr);
				//把添加的second_mingcheng、muban_bingli_type两个数组进行删除
				if($_POST["if_user_default_format"]=="true")
				{
					array_pop($muban_arr);
				}
				array_pop($muban_arr);
				array_pop($muban_arr);
			}
			else
			{
				$muban_info = M("date_bingli_muban")->where("id = '$v'")->save($muban_arr);
			}
		}
		
		$this->assign('system_info',"修改成功");
		$this->display("System:showRight");
	
	}

	// 更新一组模板信息
	public function updateMubanBingliGroup()
	{
                
		//取得post的值
		$muban_leixing = $_POST['muban_leixing'];
		$muban_kebie = $_POST['muban_kebie'];
		$second_mingcheng = $_POST['second_mingcheng'];
		$mingcheng = $_POST['mingcheng'];
		$muban_id = $_POST['muban_id'];
		
		
		//要进行替换的数组值
		$muban_arr = array();
		$muban_arr['muban_leixing'] = $muban_leixing;
	
		$muban_arr['muban_kebie'] = $muban_kebie;
		$muban_arr['mingcheng'] = $mingcheng;
		$muban_arr['keyword_index'] = $mingcheng;
		
		//进行替换更新操作
		$muban_arr['second_mingcheng'] = $second_mingcheng;

		$muban_info = M("date_bingli_muban")->where("muban_id = '$muban_id'")->save($muban_arr);
		
		$this->assign('system_info',"修改成功");
		$this->display("System:showRight");
	
	}
	
	public function deleteOneMuban()
	{
           
		$muban_id = $_GET["muban_id"];
		$muban_bingli_type = $_GET["muban_bingli_type"];
		$muban_leixing = $_GET["muban_leixing"];
		$muban_kebie = $_GET["muban_kebie"];
		if(empty($muban_id)||empty($muban_bingli_type)||empty($muban_leixing)||empty($muban_kebie))
		{
			$this->assign('system_info','错误：E5004，您的模板信息不完整，请重新选择模板。');
			$this->display("System:showError");
			exit();
		}
		M("date_bingli_muban")->where("muban_id = '$muban_id' and muban_bingli_type='$muban_bingli_type' and muban_leixing='$muban_leixing' and muban_kebie='$muban_kebie'")->delete();
		
			$this->assign('system_info','模板删除操作成功:)');
			$this->display("System:showRight");
	}

	// 批量删除一组模板(muban_id相同）
	public function deleteGroup()
	{
		
		
	
		$muban_id = $_GET["muban_id"];
		if(empty($muban_id))
		{
			$this->assign('system_info','错误：E5005，您的模板信息不完整，请重新选择模板。');
			$this->display("System:showError");
			exit();
		}
		
		$s=M("date_bingli_muban")->where("muban_id = '$muban_id' ")->delete();
		if(!$s){
			$this->assign('system_info','模板删除操作失败！:)');
			$this->display("System:showError");	
		}else{
			$this->assign('system_info','模板删除操作成功:)');
			$this->display("System:showRight");	
		}
	
	}
	
	public function getAutocomplateMubanInfo()
	{
 
		echo "[";
		$request_keyword=$_GET["term"];
		//根据关键词获取模板列表
		$muban_info = M("date_bingli_muban")->where("mingcheng like '%$request_keyword%'")->limit(10)->select();
		foreach($muban_info as $key => $one_muban_info)
		{
			if($key!==(sizeof($muban_info)-1))
				echo '{"muban_id":"'.$one_muban_info["muban_id"].'","mingcheng":"'.$one_muban_info["mingcheng"].'"},';
			else
				echo '{"muban_id":"'.$one_muban_info["muban_id"].'","mingcheng":"'.$one_muban_info["mingcheng"].'"}';
		}
		echo "]";
	}
	
	public function addOneBingli()
	{
                
            
		$muban_id = $_POST["muban_id"];
		$muban_bingli_type = $_POST["muban_bingli_type"];
		$zhuyuan_id = $_POST["zhuyuan_id"];
		
		if(empty($muban_id)||empty($zhuyuan_id)||empty($muban_bingli_type))
		{
			echo '{ "message": "更新失败，请重新尝试或者联系管理员！" , "result" : "false" }';
			exit();
		}
		//查询到病历模板
		$muban_info = M("data_bingli_muban")->where("muban_id = '$muban_id' and muban_bingli_type='$muban_bingli_type'")->find();
		
		//为病历添加此模板
		$new_bingli_info["zhuyuan_id"] = $zhuyuan_id;
		$new_bingli_info["content"] = $muban_info["content"];
		$new_bingli_info["bingli_type"] = $muban_info["muban_bingli_type"];
		$new_bingli_info["yiyuan_id"] = $_SESSION["yiyuan_id"];
		$new_bingli_info["jilu_yisheng_id"] = $_SESSION["user_id"];
		$new_bingli_info["jilu_yisheng_name"] = $_SESSION["user_name"];
		$new_bingli_info["jilu_time"] = date("Y-m-d H:s");
		
		M("zhuyuan_bingli")->add($new_bingli_info);
		
		if(1)
		{
			echo '{ "message": "更新成功，请重新尝试或者联系管理员！" , "result" : "true" }';
			exit();
		}
	}
}
?>