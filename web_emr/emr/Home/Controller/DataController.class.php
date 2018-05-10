<?php
namespace Home\Controller;
USE Components\TemrController;
class DataController extends TemrController{

	 public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
//页面标题
	public function viewInfo($zhuyuan_id,$yiyuan_id) 
	{
		//$zhuyuan_id = $_GET['zhuyuan_id'];
		//查询住院的基本信息
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id = '$zhuyuan_id' and yiyuan_id ='$yiyuan_id'")->find();
		if(empty($zhuyuan_basic_info))
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code = '$zhuyuan_id'")->find();
		}
		if(empty($zhuyuan_basic_info))
		{
			$this->assign('system_info','请输入一个患者的住院号');
			$this->display("System:showError");
			exit();
		}
		return $html = '<div class="list_title_span" style="width:100%;"><span style="font-weight:normal">姓名:</span>'.$zhuyuan_basic_info['xingming'].' | <span style="font-weight:normal">性别:</span>'.$zhuyuan_basic_info['xingbie'].' | <span style="font-weight:normal">年龄:</span>'.$zhuyuan_basic_info['nianling'].' | <span style="font-weight:normal">病历号:</span>'.$zhuyuan_basic_info['zhuyuan_id'].' | <span style="font-weight:normal">床号:</span>'.$zhuyuan_basic_info['zhuyuan_chuanghao'].'  </div>';
	}
/*
*删除照片和录音
*/

	public function delMediaData()
	{
		$_POST["url"] = str_replace('/tiantan_emr/', './', $_POST["url"]);
		if(file_exists($_POST["url"]))
		{
			if (unlink($_POST["url"]))
			{
				echo ("删除成功");
			}
			else

			{
				echo ("删除失败");
			}
		}else{
			echo $_POST["url"]+" 文件不存在";
		}
		M("zhuyuan_bingli_media")->where("id = '".$_POST["id"]."'")->delete();
	}
	//////
	public function getTreeview()
	{
		echo "[";
		if(array_key_exists( 'id',$_REQUEST))
		{
			$pid=$_REQUEST['id'];
		}
		if ($pid==null || $pid=="")
			$pid = "0";
		if(array_key_exists('table',$_GET))
		{
			$table_name = $_GET['table'];
			$data = M("$table_name")->where("pid like '$pid'")->select();
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				if($data[$i]['sub_direct_number']!=0)
					echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',  code:'".$data[$i]['code']."',  pinyin_index:'".$data[$i]['pinyin_index']."', yingwen_mingcheng:'".$data[$i]['yingwen_mingcheng']."', shuoming:'".$data[$i]['shuoming']."', isParent:true},";
				else
					echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',  code:'".$data[$i]['code']."',  pinyin_index:'".$data[$i]['pinyin_index']."', yingwen_mingcheng:'".$data[$i]['yingwen_mingcheng']."', shuoming:'".$data[$i]['shuoming']."', isParent:false},";
			}
				if($data[sizeof($data)-1]['sub_direct_number']!=0)
					echo "{ id:'".$data[sizeof($data)-1]['id']."',pid:'".$data[sizeof($data)-1]['pid']."', zhongwen_mingcheng:'".$data[sizeof($data)-1]['zhongwen_mingcheng']."', code:'".$data[sizeof($data)-1]['code']."',  pinyin_index:'".$data[sizeof($data)-1]['pinyin_index']."', yingwen_mingcheng:'".$data[$i]['yingwen_mingcheng']."', shuoming:'".$data[$i]['shuoming']."', isParent:true}";
				else
					echo "{ id:'".$data[sizeof($data)-1]['id']."',pid:'".$data[sizeof($data)-1]['pid']."', zhongwen_mingcheng:'".$data[sizeof($data)-1]['zhongwen_mingcheng']."', code:'".$data[sizeof($data)-1]['code']."',  pinyin_index:'".$data[sizeof($data)-1]['pinyin_index']."', yingwen_mingcheng:'".$data[$i]['yingwen_mingcheng']."', shuoming:'".$data[$i]['shuoming']."', isParent:false}";
		}
		echo "]";
	}

	public function getTemplateTreeview()
	{
		echo "[";
		if(array_key_exists( 'id',$_REQUEST))
		{
			$pid=$_REQUEST['id'];
		}
		if ($pid==null || $pid=="")
		{
			if(array_key_exists( 'initial_pid',$_REQUEST))
				$pid = $_REQUEST['initial_pid'];
			else
				$pid = "0";
		}

		if(array_key_exists('table',$_GET))
		{
			$table_name = $_GET['table'];
			$data = M("$table_name")->where("pid like '$pid'")->select();
			$i = 0;
			$ShujuyuanString = A("Home/ShujuyuanString");
			for($i=0;$i<sizeof($data)-1;$i++)
			{

				$data[$i]['shuoming'] = str_replace(array("
", "\r", "\t"), "",$data[$i]['shuoming']) ;

				if($data[$i]['sub_direct_number']!=0)
					echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',shuoming:'".$data[$i]['shuoming']."',isParent:true},";
				else
					echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',shuoming:'".$data[$i]['shuoming']."',isParent:false},";
			}
			$data[$i]['shuoming'] = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]['shuoming']));
			$data[$i]['shuoming'] = str_replace(array("
", "\r", "\t"), "",$data[$i]['shuoming']) ;

			if($data[$i]['sub_direct_number']!=0)
				echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',shuoming:'".$data[$i]['shuoming']."',isParent:true}";
			else
				echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',shuoming:'".$data[$i]['shuoming']."',isParent:false}";
		}
		echo "]";
	}

	public function getAddress()
	{
		if(array_key_exists( 'id',$_REQUEST))
		{
			$pid=$_REQUEST['id'];
		}
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'keyword',$_REQUEST))
		{
			$keyword=$_REQUEST['keyword'];
		}
		if(array_key_exists( 'ifempty',$_REQUEST))
		{
			$ifempty=$_REQUEST['ifempty'];
		}
		echo "<ul>";
		if ($pid==null || $pid=="")
		{
			$pid = "0";
		}
		$result=="false";
		if($ifempty==1)
		{
			$data = M("data_address")->where("code like '$pid'")->select();
			if(!$data)
			{
				$result=="true";
			}
		}
		if($ifempty==0 || $result=="true")
		{
			$data = M("data_address")->where("pid like '$pid' and keyword_shuoming like '%$keyword%'")->select();
		}
		
		for($i=0;$i<sizeof($data);$i++)
		{
			if($data[$i]["zhongwen_mingcheng"]=="")
				$data[$i]["zhongwen_mingcheng"] = "无子地址";
			if($data[$i]["sub_total_number"]==0)
			{
				echo "<li class='".$type."' id='".$data[$i]["id"]."' mingcheng='".$data[$i]["zhongwen_mingcheng"]."' bianma='".$data[$i]["code"]."' parent_id='".$data[$i]["pid"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
			else
			{
				echo "<li class='".$type."' id='".$data[$i]["id"]."' mingcheng='".$data[$i]["zhongwen_mingcheng"]."' bianma='".$data[$i]["code"]."' parent_id='".$data[$i]["pid"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
		}
		echo "</ul>";
	}

	//得到临床路径列表
	public function getLinchuangLujing()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_GET['type'];
		}
		if(array_key_exists( 'bingzong_name',$_REQUEST))
		{
			$bingzong_name=$_GET['bingzong_name'];
			$bingzong_name_search_state = str_replace(" ","%",$bingzong_name);
		}
		echo "<ul>";
		if ($type==null || $type=="")
		{
			$type = "0";
		}
		if($bingzong_name==""){
			$linchuang_lujing_info_a = M("zhuyuan_linchuanglujing")->where("type like '$type' and moban_yishi like '".$_SESSION["user_number"]."'")->order("zhongwen_mingcheng DESC")->select();
			foreach($linchuang_lujing_info_a as $key => $one)
			{
				$linchuang_lujing_info_a[$key]["suoshu_keshi"] = "我的模版";
			}
			$linchuang_lujing_info_c = M("zhuyuan_linchuanglujing")->where("type like '$type' and muban_type like '科室模板' and suoshu_keshi like '".$_SESSION["user_department"]."'")->order("zhongwen_mingcheng DESC")->select();
			foreach($linchuang_lujing_info_c as $key => $one)
			{
				$linchuang_lujing_info_c[$key]["suoshu_keshi"] = "科室模版";
			}
			$linchuang_lujing_info_b = M("zhuyuan_linchuanglujing")->where("type like '$type' and (muban_type='' or muban_type='公共模板')")->order("suoshu_keshi DESC,zhongwen_mingcheng DESC")->select();
		}
		else
		{
			$linchuang_lujing_info_a = M("zhuyuan_linchuanglujing")->where("type like '$type' and (zhongwen_mingcheng like '%$bingzong_name_search_state%' or yishi_name like '%$bingzong_name_search_state%') and moban_yishi like '".$_SESSION["user_number"]."'")->order("zhongwen_mingcheng DESC")->select();
			foreach($linchuang_lujing_info_a as $key => $one)
			{
				$linchuang_lujing_info_a[$key]["suoshu_keshi"] = "我的模版";
			}
			$linchuang_lujing_info_c = M("zhuyuan_linchuanglujing")->where("type like '$type' and (zhongwen_mingcheng like '%$bingzong_name_search_state%' or yishi_name like '%$bingzong_name_search_state%') and muban_type like '科室模板' and suoshu_keshi like '".$_SESSION["user_department"]."'")->order("zhongwen_mingcheng DESC")->select();
			foreach($linchuang_lujing_info_c as $key => $one)
			{
				$linchuang_lujing_info_c[$key]["suoshu_keshi"] = "科室模版";
			}
			$linchuang_lujing_info_b = M("zhuyuan_linchuanglujing")->where("type like '$type' and (zhongwen_mingcheng like '%$bingzong_name_search_state%' or yishi_name like '%$bingzong_name_search_state%') and (muban_type='' or muban_type='公共模板')")->order("suoshu_keshi DESC,zhongwen_mingcheng DESC")->select();
		}
			
		$linchuang_lujing_info = Array();
		
		if(!empty($linchuang_lujing_info_a))
			$linchuang_lujing_info = $linchuang_lujing_info_a;
		if(!empty($linchuang_lujing_info_b))
			$linchuang_lujing_info = array_merge($linchuang_lujing_info,$linchuang_lujing_info_b);
		if(!empty($linchuang_lujing_info_c))
			$linchuang_lujing_info = array_merge($linchuang_lujing_info,$linchuang_lujing_info_c);
			
		echo "<li>快速查询：
				<input type='' name='bingzong_name' value='".$bingzong_name."'/>
				<input type='button' name='bingzong_name_btn' value='查询' class='search_button'/>
				<input type='button' name='bingzong_name_btn_all' value='显示全部' class='search_button'/>
			  </li>";
		$temp_keshi = "its null about it";
		for($i=0;$i<sizeof($linchuang_lujing_info);$i++)
		{
			if($linchuang_lujing_info[$i]["suoshu_keshi"]!=$temp_keshi)
			{
				$keshi = $linchuang_lujing_info[$i]["suoshu_keshi"];
				if($linchuang_lujing_info[$i]["suoshu_keshi"]=="")
				{
					$keshi = "其他";
				}
				echo '<li class="keshi" keshi_name="'.$keshi.'"><div class="list_title"><div class="list_title_span">'.$keshi.'</div></div></li>';
			}
			if($keshi=="我的模版")
			{
				echo "<li class='bingzhong' style='display:list-item;' keshi_item='".$keshi."' zhongwen_mingcheng='".$linchuang_lujing_info[$i]["zhongwen_mingcheng"]."' zhuyuan_id='".$linchuang_lujing_info[$i]["zhuyuan_id"]."' lujing_id='".$linchuang_lujing_info[$i]["id"]."' jibing_bianma='".$linchuang_lujing_info[$i]["jibing_id"]."'>".$linchuang_lujing_info[$i]["zhongwen_mingcheng"]."<input type='button' style='float:right;' value='预览' class='search_button'/><span style='float:right;'>".$linchuang_lujing_info[$i]["yishi_name"]."</span></li>";
			}
			else
			{
				echo "<li class='bingzhong' style='display:none;' keshi_item='".$keshi."' zhongwen_mingcheng='".$linchuang_lujing_info[$i]["zhongwen_mingcheng"]."' zhuyuan_id='".$linchuang_lujing_info[$i]["zhuyuan_id"]."' lujing_id='".$linchuang_lujing_info[$i]["id"]."' jibing_bianma='".$linchuang_lujing_info[$i]["jibing_id"]."'>".$linchuang_lujing_info[$i]["zhongwen_mingcheng"]."<input type='button' style='float:right;' value='预览' class='search_button'/><span style='float:right;'>".$linchuang_lujing_info[$i]["yishi_name"]."</span></li>";
			}
			
			$temp_keshi = $linchuang_lujing_info[$i]["suoshu_keshi"];
		}
		
		if(sizeof($linchuang_lujing_info)==0)
			echo "<li>当前系统中还没有添加任何临床路径:)</li>";
			
		echo "</ul>";
		
	}
	
	//设置用户的临床路径（路径号、诊断、病历模板）
	public function setLinchuangLujing()
	{
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'shifou_shiyong_muban',$_REQUEST))
		{
			$shifou_shiyong_muban=$_REQUEST['shifou_shiyong_muban'];
		}
		if(array_key_exists( 'shifou_fugai',$_REQUEST))
		{
			$shifou_fugai=$_REQUEST['shifou_fugai'];
		}
		
		if($zhuyuan_id=="")
		{
			echo "请选择一个患者后再执行临床路径设置功能";
		}
		else
		{
			$linchuang_lujing_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->select();
			$muban_zhuyuan_id = $linchuang_lujing_info[0]["zhuyuan_id"];
			$zhongwen_mingcheng = $linchuang_lujing_info[0]["zhongwen_mingcheng"];
			$type =  $linchuang_lujing_info[0]["type"];
			$jibing_id = $linchuang_lujing_info[0]["jibing_id"];
			
			if(C("zhenduan_control")=="auto_copy")
			{
				//同步添加各种初步诊断：
				//各种西医诊断：
				$zhenduan_xiyi_list = M("zhenduan_xiyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhixing_type like '住院' and zhenduan_type like '入院确定诊断'")->select();
				foreach($zhenduan_xiyi_list as $key => $one_zhenduan)
				{
					$one_new_zhenduan_info = array(
						doctor_id => $_SESSION["user_number"],
						doctor_name => $_SESSION["user_name"],
						zhixing_id => $zhuyuan_id,
						zhixing_type => "住院",
						zhenduan_time => date('Y-m-d H:i:s'),
						zhenduan_mingcheng => $one_zhenduan["zhenduan_mingcheng"],
						zhenduan_type => "入院初步诊断",
						zhenduan_bianhao => $one_zhenduan["zhenduan_bianhao"],
						zhenduan_jieguo => $one_zhenduan["zhenduan_jieguo"],
						zhenduan_sub_type => $one_zhenduan["zhenduan_sub_type"],
						ruyuan_bingqing => $one_zhenduan["ruyuan_bingqing"],
						other_info => $one_zhenduan["other_info"]
					);
					$zhenduan_add_result = M("zhenduan_xiyi")->add($one_new_zhenduan_info);
					
					//再添加并发病变
					$zhenduan_xiyi_sub_list = M("zhenduan_xiyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhenduan_relate_ID like '".$one_zhenduan["id"]."' and zhenduan_sub_type like '并发病变'")->select();
					foreach($zhenduan_xiyi_sub_list as $key => $one_sub_zhenduan)
					{
						$one_new_sub_zhenduan_info = array(
							doctor_id => $_SESSION["user_number"],
							doctor_name => $_SESSION["user_name"],
							zhixing_id => $zhuyuan_id,
							zhixing_type => "住院",
							zhenduan_time => date('Y-m-d H:i:s'),
							zhenduan_mingcheng => $one_sub_zhenduan["zhenduan_mingcheng"],
							zhenduan_type => "入院初步诊断",
							zhenduan_bianhao => $one_sub_zhenduan["zhenduan_bianhao"],
							zhenduan_jieguo => $one_sub_zhenduan["zhenduan_jieguo"],
							zhenduan_sub_type => $one_sub_zhenduan["zhenduan_sub_type"],
							zhenduan_relate_ID => $zhenduan_add_result,
							zhenduan_relate => $one_zhenduan["zhenduan_mingcheng"],
							ruyuan_bingqing => $one_sub_zhenduan["ruyuan_bingqing"],
							other_info => $one_sub_zhenduan["other_info"]
						);
						$zhenduan_sub_add_result = M("zhenduan_xiyi")->add($one_new_sub_zhenduan_info);
					}
				}
				
				//各种中医诊断：
				$zhenduan_zhongyi_list = M("zhenduan_zhongyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhixing_type like '住院' and zhenduan_type like '入院确定诊断'")->select();
				foreach($zhenduan_zhongyi_list as $key=>$one_zhenduan)
				{
					$one_new_zhenduan_info = array(
						doctor_id => $_SESSION["user_number"],
						doctor_name => $_SESSION["user_name"],
						zhixing_id => $zhuyuan_id,
						zhixing_type => "住院",
						zhenduan_time => date('Y-m-d H:i:s'),
						zhenduan_mingcheng => $one_zhenduan["zhenduan_mingcheng"],
						zhenduan_type => "入院初步诊断",
						zhenduan_bianhao => $one_zhenduan["zhenduan_bianhao"],
						zhenduan_jieguo => $one_zhenduan["zhenduan_jieguo"],
						zhenduan_sub_type => $one_zhenduan["zhenduan_sub_type"],
						ruyuan_bingqing => $one_zhenduan["ruyuan_bingqing"],
						other_info => $one_zhenduan["other_info"]
					);
					$zhenduan_add_result = M("zhenduan_zhongyi")->add($one_new_zhenduan_info);
					
					//再添加中医病症
					$zhenduan_zhongyi_sub_list = M("zhenduan_zhongyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhenduan_relate_ID like '".$one_zhenduan["id"]."' and zhenduan_sub_type like '中医病症'")->select();
					foreach($zhenduan_zhongyi_sub_list as $key => $one_sub_zhenduan)
					{
						$one_new_sub_zhenduan_info = array(
							doctor_id => $_SESSION["user_number"],
							doctor_name => $_SESSION["user_name"],
							zhixing_id => $zhuyuan_id,
							zhixing_type => "住院",
							zhenduan_time => date('Y-m-d H:i:s'),
							zhenduan_mingcheng => $one_sub_zhenduan["zhenduan_mingcheng"],
							zhenduan_type => "入院初步诊断",
							zhenduan_bianhao => $one_sub_zhenduan["zhenduan_bianhao"],
							zhenduan_jieguo => $one_sub_zhenduan["zhenduan_jieguo"],
							zhenduan_sub_type => $one_sub_zhenduan["zhenduan_sub_type"],
							zhenduan_relate_ID => $zhenduan_add_result,
							zhenduan_relate => $one_zhenduan["zhenduan_mingcheng"],
							ruyuan_bingqing => $one_sub_zhenduan["ruyuan_bingqing"],
							other_info => $one_sub_zhenduan["other_info"]
						);
						$zhenduan_sub_add_result = M("zhenduan_zhongyi")->add($one_new_sub_zhenduan_info);
					}
				}

			}
			
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			//同步临床路径信息：
			$zhuyuan_basic_info[0]["linchuanglujing"] = $type;
			$zhuyuan_basic_info[0]["jibing_id"] = $jibing_id;
			$zhuyuan_basic_info[0]["linchuanglujing_buzhou"] = 1;
			if($type=="中医")
				$zhiliao_leibie = "中西医结合治疗";
			else
				$zhiliao_leibie = "西医治疗";
			$zhuyuan_basic_info[0]["zhiliao_leibie"] = $zhiliao_leibie;
			$list = M("zhuyuan_basic_info")->save($zhuyuan_basic_info[0]);
			
			//通过传递进来的参数判断是否需要使用临床路径模板
			if($shifou_shiyong_muban=="true")
			{
				//1. 快速插入入院病史记录模板：
					//先获取模板内容：
					$muban_info = M("zhuyuan_bingshi")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
					//进行患者信息替换：
					$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
					$bingshi_info = array(
						"zhuyuan_id" => $zhuyuan_id,
						"zhusu" => $muban_info[0]["zhusu"],
						"xianbingshi" => $muban_info[0]["xianbingshi"],
						"jiwangshi" => $muban_info[0]["jiwangshi"],
						"gerenshi" => $muban_info[0]["gerenshi"],
						"hunyushi" => $muban_info[0]["hunyushi"],
						"yuejingshi" => $muban_info[0]["yuejingshi"],
						"jibenjiancha" => $muban_info[0]["jibenjiancha"],
						"yibanqingkuang" => $muban_info[0]["yibanqingkuang"],
						"jiazushi" => $muban_info[0]["jiazushi"]
					);
					//先判断是否已经编辑过病史信息了：
					$original_bingshi_info = M("zhuyuan_bingshi")->where("zhuyuan_id = '$zhuyuan_id'")->count();
					if($original_bingshi_info!=0)
					{
						$zhuyuan_bingshi_result = M("zhuyuan_bingshi")->where("zhuyuan_id = '$zhuyuan_id'")->save($bingshi_info);
					}
					else
					{
						$zhuyuan_bingshi_result = M("zhuyuan_bingshi")->add($bingshi_info);
					}
					
				//2. 快速插入入院辅助检查模板：
					//先获取模板内容：
					$muban_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
					//进行患者信息替换：
					$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
					$zhuyuan_ruyuantigejiancha_info = array(
						"zhuyuan_id" => $zhuyuan_id,
						"jibenjiancha" => $muban_info[0]["jibenjiancha"],
						"yibanqingkuang" => $muban_info[0]["yibanqingkuang"],
						"pifu_nianmo_linbajie" => $muban_info[0]["pifu_nianmo_linbajie"],
						"toumianbu" => $muban_info[0]["toumianbu"],
						"jingbu" => $muban_info[0]["jingbu"],
						"xiongbu" => $muban_info[0]["xiongbu"],
						"fubu" => $muban_info[0]["fubu"],
						"xinzang" => $muban_info[0]["xinzang"],
						"gangmenshengzhi" => $muban_info[0]["gangmenshengzhi"],
						"jizhusizhi" => $muban_info[0]["jizhusizhi"],
						"shenjingxitong" => $muban_info[0]["shenjingxitong"],
						"other" => $muban_info[0]["other"]
					);
					//先判断是否已经编辑过入院体格检查信息了：
					$original_ruyuantigejiancha_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->count();
					if($original_ruyuantigejiancha_info!=0)
					{
						$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_ruyuantigejiancha_info);
					}
					else
					{
						$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->add($zhuyuan_ruyuantigejiancha_info);
					}
					
				//3. 快速插入首次病程记录模板：
					//先获取模板内容：
					$muban_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
					//进行患者信息替换：
					$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
					$zhuyuan_bingchengjilushouci_info = array(
						"zhuyuan_id" => $zhuyuan_id,
						"doctor_id" => $_SESSION["user_number"],
						"doctor_name" => $_SESSION["user_name"],
						"gaishu" => $muban_info[0]["gaishu"],
						"binglitedian" => $muban_info[0]["binglitedian"],
						"zhongyibianzhengyiju" => $muban_info[0]["zhongyibianzhengyiju"],
						"xiyibianzhengyiju" => $muban_info[0]["xiyibianzhengyiju"],
						"zhongyijianbiezhenduan" => $muban_info[0]["zhongyijianbiezhenduan"],
						"xiyijianbiezhenduan" => $muban_info[0]["xiyijianbiezhenduan"],
						"zhenliaojihua" => $muban_info[0]["zhenliaojihua"],
						"jikecuoshi" => $muban_info[0]["jikecuoshi"]
					);
					//先判断是否已经编辑过首次病程记录信息了：
					$original_bingchengjilushouci_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id = '$zhuyuan_id'")->count();
					if($original_bingchengjilushouci_info!=0)
					{
						$zhuyuan_bingchengjilushouci_result = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_bingchengjilushouci_info);
					}
					else
					{
						$zhuyuan_bingchengjilushouci_result = M("zhuyuan_bingchengjilushouci")->add($zhuyuan_bingchengjilushouci_info);
					}
					
				//4. 快速插入病程记录模板：
					//先获取模板内容：
					$muban_info = M("zhuyuan_bingchengjilu")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
					foreach($muban_info as $one_info)
					{
						//进行患者信息替换
						$one_info = $this->patientInfoReplace($one_info,$muban_zhuyuan_id,$zhuyuan_id);
						$one_zhuyuan_bingchengjilu_info = array(
							"zhuyuan_id" => $zhuyuan_id,
							"doctor_id" => $_SESSION["user_number"],
							"doctor_name" => $_SESSION["user_name"],
							"record_time" => date('Y-m-d H:i:s'),
							"bingcheng_sub_leibie" =>$one_info["bingcheng_sub_leibie"],
							"content" =>$one_info["content"],
							"chafang_doctor" =>$_SESSION["user_name"],
							"bingcheng_leibie" =>$one_info["bingcheng_leibie"],
							"huanzhe_jiashu_qianzi" =>$one_info["huanzhe_jiashu_qianzi"]
						);
						$zhuyuan_bingchengjilu_result = M("zhuyuan_bingchengjilu")->add($one_zhuyuan_bingchengjilu_info);
					}
				
				}
			}
	}
	
	//获取临床路径的具体内容信息（医嘱、处方、检查 医患沟通记录 其它文书）
	public function getLinchuanglujingContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if ($lujing_id==null || $lujing_id=="")
		{
			$lujing_id = "0";
		}
		
		$server_url = $_SESSION["server_url"];
		
		//1. 得到路径对应的参考病历号：
		$dianxing_bingli_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->find();
		$dianxing_bingli_zhuyuan_id = $dianxing_bingli_info['zhuyuan_id'];
		$jibing_id = $dianxing_bingli_info['jibing_id'];
		
		//2. 开始输出入院、出院以及病程记录，医嘱、处方、检查模板信息：
			echo '<form class="linchuang_lujing_content_form" id="linchuang_lujing_content_form" method="post" action="http://'.$server_url.'/tiantan_emr/Home/Data/setLinchuanglujingContent">';
				echo "<input type='hidden' name='zhuyuan_id' value='$zhuyuan_id' />";
				echo '<table width="100%">';
				echo "<input type='hidden' name='type' value='".$type."' ></input>";
				echo "<input type='hidden' name='lujing_id' value='".$lujing_id."' ></input>";
				echo "<input type='hidden' name='day' value='".$day."' ></input>";
				echo "当前参考病历名称：".$dianxing_bingli_info["zhongwen_mingcheng"];
				//1.1. 输出入院记录
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "请选择需要快速使用的【病历模板】：";
						echo "</td>";
					echo "</tr>";
					echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='ruyuan_jilu' value='true'>入院记录</input>";
						echo "</td>";
					echo "</tr>";
					echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='shouci_bingcheng_jilu' value='true'>首次病程记录</input>";
						echo "</td>";
					echo "</tr>";
					if(C("meiri_bingcheng_muban")=="on")
					{
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='meiri_bincheng_jilu' value='true'>每日病程记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					//5.1. 出院信息
					//1）查询出院信息内容
					$zhuyuan_chuyuan_info = M("zhuyuan_basic_info")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id'")->select();
					
					if($zhuyuan_chuyuan_info[0]['zhuangtai']=="已出院")
					{
						// 1.已出院 
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>出院记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=='24小时内出院')
					{
						// 2.24小时内出院记录
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>24小时内出院记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="自动出院")
					{
						// 3.自动出院记录 
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>".出院记录."</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="24小时内自动出院")
					{
						// 4.24小时内自动出院记录 
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>24小时内自动出院记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="死亡记录")
					{
						// 5.死亡记录
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>".死亡记录 ."</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="24小时内死亡记录")
					{
						// 6.24小时内死亡记录
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>24小时内死亡记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					else
					{}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
					
				//2.1. 输出医嘱信息
					//1） 得到参考病历长期医嘱
					$changqi_yizhu_info = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id' and yongfa_type !='检查项目' and yongfa_type !='输液' and yongfa_type !='中草药' and yongfa_type !='西药中成药'")->select();
					//2） 得到参考病历临时医嘱
					$lingshi_yizhu_info = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id' and yongfa_type !='输液' and yongfa_type !='中草药' and yongfa_type !='西药中成药'")->select();
				$lingshi_yizhu_count = '';
				$lingshi_yizhu_ture = '';
				if(count($changqi_yizhu_info)<1 && count($lingshi_yizhu_info)<1)
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "当前参考病历还没有添加典型医嘱:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "请选择需要快速添加的【长期医嘱】：";
						echo "</td>";
					echo "</tr>";
					foreach($changqi_yizhu_info as $one_changqi_yizhu)
					{
						echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='changqi_yizhu_list[]' value='".$one_changqi_yizhu["id"]."' >".$one_changqi_yizhu["content"]."</input>";
						echo "</td>";
						echo "</tr>";
					}
					
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "请选择需要快速添加的【临时医嘱】：";
						echo "</td>";
					echo "</tr>";
					foreach($lingshi_yizhu_info as $one_linshi_yizhu)
					{
						$lingshi_yizhu_count .= $one_linshi_yizhu['yizhu_id']."|";
						echo '<tr>';
						echo "<td>";

							echo "<input yizhu_id = '".$one_linshi_yizhu['yizhu_id']."' type='checkbox' name='linshi_yizhu_list[]' value='".$one_linshi_yizhu["id"]."' >".$one_linshi_yizhu["content"]."</input>";
						
						echo "</td>";
						echo "</tr>";
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";	
				}
				$yizhu_info = explode("|",$lingshi_yizhu_count);
				array_pop($yizhu_info);
				
				//4.2. 输出处方信息
					//1） 得到参考病历处方信息
				$zhuyuan_chufang_info = M("zhuyuan_chufang")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id'")->select();
				if(count($zhuyuan_chufang_info)<1)
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "当前参考病历还没有添加典型处方:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "请选择需要快速添加的【处方】：";
						echo "</td>";
					echo "</tr>";
					foreach($zhuyuan_chufang_info as $key=>$one_chufang)
					{
							$chufang_zhenduan_info = "";
							$key = $key+1;
							echo '<tr>';
							//1) 得到主要诊断和主症
							$relate_zhenduan_id_array = explode("+",$one_chufang["relate_zhenduan_id"]);
							foreach($relate_zhenduan_id_array as $one_zhenduan_id)
							{
								$one_zhenduan_id_info_array = explode("-",$one_zhenduan_id);
								if($one_zhenduan_id_info_array[0]=="xiyi")
								{
									$zhenduan_info_search_result = M("zhenduan_xiyi")->where("id='".$one_zhenduan_id_info_array[1]."'")->find();
									if(!empty($zhenduan_info_search_result["zhenduan_mingcheng"]))
										$chufang_zhenduan_info .= $zhenduan_info_search_result["zhenduan_mingcheng"]."|";
								}
								if($one_zhenduan_id_info_array[0]=="zhongyi")
								{
									$zhenduan_info_search_result = M("zhenduan_zhongyi")->where("id='".$one_zhenduan_id_info_array[1]."'")->find();
									if(!empty($zhenduan_info_search_result["zhenduan_mingcheng"]))
										$chufang_zhenduan_info .= $zhenduan_info_search_result["zhenduan_mingcheng"]."|";
								}
							}
							//2) 取得药品详细名称内容
							$zhuyuan_yaoping_info = M("zhuyuan_chufang_detail")->where("chufang_id=$one_chufang[id]")->select();
							$zhuyuan_yaoping_info_count = M("zhuyuan_chufang_detail")->where("chufang_id=$one_chufang[id]")->count();
							
							$yaoping_str = "";
							for($i=0; $i < $zhuyuan_yaoping_info_count; $i++)
							{
								$yaoping_str .= $zhuyuan_yaoping_info[$i]["yaopin_mingcheng"]."(".$zhuyuan_yaoping_info[$i]["ciliang"].$zhuyuan_yaoping_info[$i]["shiyong_danwei"].")";
								if(($i+1)%4==0 && $one_chufang["type"]=="中草药")
								{
									$yaoping_str .= "<br/>";
								}
								else if($one_chufang["type"]!="中草药")
								{
									$yaoping_str .= "<br/>";
								}
								else
								{
									$yaoping_str .= " ";
								}
							}
							
							if($yaoping_str=="")
							{
								$yaoping_str = "此处方为空:)";
							}
							if(empty($chufang_zhenduan_info))
								$chufang_zhenduan_info = "处方";
							echo "<td class='chufang_info'>";
								echo "<input type='checkbox' name='chufang_list[]' value='".$one_chufang["id"]."'>".$key."、".$chufang_zhenduan_info."【".$one_chufang['type']."处方】</input>";
								echo "<a class='detail_info' detail_info='".$yaoping_str."'>
										[详细信息]
								  </a>";
							echo "</td>";
							
							echo "</tr>";
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				}
				
				//4.3. 输出检查信息
					//1） 得到参考病历辅助检查信息
					$zhuyuan_fuzhujiancha_info = M("zhuyuan_fuzhujiancha")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id'")->select();
				if(count($zhuyuan_fuzhujiancha_info)<1)
				{
					echo '<tr>';
						echo "<td class='content_type' style='border-bottom:dashed 1px #a5cafc;'>";
							echo "当前参考病历还没有添加典型辅助检查:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo '<tr>';
						echo "<td class='content_type' style=''>";
							echo "请选择需要快速添加的【辅助检查】：";
						echo "</td>";
					echo "</tr>";
					foreach($zhuyuan_fuzhujiancha_info as $one_zhuyuan_fuzhujiancha)
					{
						/*if(in_array($one_zhuyuan_fuzhujiancha['id'],$yizhu_info))
						{
							echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='jiancha_list[]' value='".$one_zhuyuan_fuzhujiancha["id"]."' checked='true'>".$one_zhuyuan_fuzhujiancha["jiancha_mingcheng"]."</input>";
							echo "</td>";
							echo "</tr>";
						}
						else
						{
							echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='jiancha_list[]' value='".$one_zhuyuan_fuzhujiancha["id"]."' >".$one_zhuyuan_fuzhujiancha["jiancha_mingcheng"]."</input>";
							echo "</td>";
							echo "</tr>";
						}*/
						echo '<tr>';
							echo "<td>";
								echo "<input fuzhu_id='".$one_zhuyuan_fuzhujiancha['id']."' type='checkbox' name='jiancha_list[]' value='".$one_zhuyuan_fuzhujiancha["id"]."' >".$one_zhuyuan_fuzhujiancha["jiancha_mingcheng"]."</input>";
							echo "</td>";
						echo "</tr>";
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				}

			
				//输出医患沟通记录
				$yihuangoutong_list = M("zhuyuan_yihuangoutongjilu")->where(" zhuyuan_id = '".$dianxing_bingli_zhuyuan_id."' ")->select();

				if(count($yihuangoutong_list)<1)
				{
					echo '<tr>';
						echo "<td class='content_type' style='border-bottom:dashed 1px #a5cafc;'>";
							echo "当前参考病历还没有添加典型医患沟通记录:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo '<tr>';
						echo "<td class='content_type' style=''>";
							echo "请选择需要快速添加的【医患沟通记录】：";
						echo "</td>";
					echo "</tr>";
					foreach($yihuangoutong_list as $one_yihuangoutong)
					{
						
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='yihuan_list[]' value='".$one_yihuangoutong["id"]."' >".$one_yihuangoutong["place"]."</input>";
								echo "<a class='detail_info' detail_info='".$one_yihuangoutong['content']."'>
										[详细信息]
								  </a>";
							echo "</td>";
						echo "</tr>";
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				}


				//输出其它文书
				$wenshu_list = M("zhuyuan_other_document")->order('id desc')->where(" zhuyuan_id = '".$dianxing_bingli_zhuyuan_id."' ")->select();

				if(count($wenshu_list)<1)
				{
					echo '<tr>';
						echo "<td class='content_type' style='border-bottom:dashed 1px #a5cafc;'>";
							echo "当前参考病历还没有添加典型其它文书:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo '<tr>';
						echo "<td class='content_type' style=''>";
							echo "请选择需要快速添加的【其它文书】：";
						echo "</td>";
					echo "</tr>";
					foreach($wenshu_list as $one_wenshu_list)
					{
						
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='wenshu_list[]' value='".$one_wenshu_list["id"]."' >".'['.$one_wenshu_list["category"].']&nbsp;&nbsp;&nbsp;&nbsp;'.$one_wenshu_list["zhongwen_mingcheng"]."</input>";
								
							echo "</td>";
						echo "</tr>";
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				}
				
				echo "</table>";
			echo "</form>";
	}
	
	//设置临床路径的具体内容信息（医嘱、处方、检查）
	public function setLinchuanglujingContent()
	{
	
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if(array_key_exists( 'chuyuan_type',$_REQUEST))
		{
			$chuyuan_type=$_REQUEST['chuyuan_type'];
		}
		if(array_key_exists( 'shifou_fugai',$_REQUEST))
		{
			$shifou_fugai=$_REQUEST['shifou_fugai'];
		}

		if($zhuyuan_id!="")
		{
			//根据住院ID获取patient_id
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->find();
			$patient_id = $zhuyuan_basic_info["patient_id"];
		
			// 1. 入院记录
			$linchuang_lujing_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->select();
			$muban_zhuyuan_id = $linchuang_lujing_info[0]["zhuyuan_id"];
			$zhongwen_mingcheng = $linchuang_lujing_info[0]["zhongwen_mingcheng"];
			$type =  $linchuang_lujing_info[0]["type"];
			$jibing_id = $linchuang_lujing_info[0]["id"];
			if(C("zhenduan_control")=="auto_copy")
			{
				//同步添加各种初步诊断：
				//各种西医诊断：
				$zhenduan_xiyi_list = M("zhenduan_xiyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhixing_type like '住院' and zhenduan_type like '入院确定诊断'")->select();
				foreach($zhenduan_xiyi_list as $key => $one_zhenduan)
				{
					if($one_zhenduan["zhenduan_bianhao"]=="tiantan")
						$one_zhenduan["zhenduan_bianhao"] = " ";
					$one_new_zhenduan_info = array(
						doctor_id => $_SESSION["user_number"],
						doctor_name => $_SESSION["user_name"],
						zhixing_id => $zhuyuan_id,
						zhixing_type => "住院",
						zhenduan_time => date('Y-m-d H:i:s'),
						zhenduan_mingcheng => $one_zhenduan["zhenduan_mingcheng"],
						zhenduan_type => "入院初步诊断",
						zhenduan_bianhao => $one_zhenduan["zhenduan_bianhao"],
						zhenduan_jieguo => $one_zhenduan["zhenduan_jieguo"],
						zhenduan_sub_type => $one_zhenduan["zhenduan_sub_type"],
						ruyuan_bingqing => $one_zhenduan["ruyuan_bingqing"],
						other_info => $one_zhenduan["other_info"]
					);
					$zhenduan_add_result = M("zhenduan_xiyi")->add($one_new_zhenduan_info);
					
					//再添加并发病变
					$zhenduan_xiyi_sub_list = M("zhenduan_xiyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhenduan_relate_ID like '".$one_zhenduan["id"]."' and zhenduan_sub_type like '并发病变'")->select();
					foreach($zhenduan_xiyi_sub_list as $key => $one_sub_zhenduan)
					{
					if($one_sub_zhenduan["zhenduan_bianhao"]=="tiantan")
						$one_sub_zhenduan["zhenduan_bianhao"] = " ";
						
						$one_new_sub_zhenduan_info = array(
							doctor_id => $_SESSION["user_number"],
							doctor_name => $_SESSION["user_name"],
							zhixing_id => $zhuyuan_id,
							zhixing_type => "住院",
							zhenduan_time => date('Y-m-d H:i:s'),
							zhenduan_mingcheng => $one_sub_zhenduan["zhenduan_mingcheng"],
							zhenduan_type => "入院初步诊断",
							zhenduan_bianhao => $one_sub_zhenduan["zhenduan_bianhao"],
							zhenduan_jieguo => $one_sub_zhenduan["zhenduan_jieguo"],
							zhenduan_sub_type => $one_sub_zhenduan["zhenduan_sub_type"],
							zhenduan_relate_ID => $zhenduan_add_result,
							zhenduan_relate => $one_zhenduan["zhenduan_mingcheng"],
							ruyuan_bingqing => $one_sub_zhenduan["ruyuan_bingqing"],
							other_info => $one_sub_zhenduan["other_info"]
						);
						$zhenduan_sub_add_result = M("zhenduan_xiyi")->add($one_new_sub_zhenduan_info);
					}
				}
				
				//各种中医诊断：
				$zhenduan_zhongyi_list = M("zhenduan_zhongyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhixing_type like '住院' and zhenduan_type like '入院确定诊断'")->select();
				foreach($zhenduan_zhongyi_list as $key=>$one_zhenduan)
				{
					if($one_zhenduan["zhenduan_bianhao"]=="tiantan")
						$one_sub_zhenduan["zhenduan_bianhao"] = " ";
						
					$one_new_zhenduan_info = array(
						doctor_id => $_SESSION["user_number"],
						doctor_name => $_SESSION["user_name"],
						zhixing_id => $zhuyuan_id,
						zhixing_type => "住院",
						zhenduan_time => date('Y-m-d H:i:s'),
						zhenduan_mingcheng => $one_zhenduan["zhenduan_mingcheng"],
						zhenduan_type => "入院初步诊断",
						zhenduan_bianhao => $one_zhenduan["zhenduan_bianhao"],
						zhenduan_jieguo => $one_zhenduan["zhenduan_jieguo"],
						zhenduan_sub_type => $one_zhenduan["zhenduan_sub_type"],
						ruyuan_bingqing => $one_zhenduan["ruyuan_bingqing"],
						other_info => $one_zhenduan["other_info"]
					);
					$zhenduan_add_result = M("zhenduan_zhongyi")->add($one_new_zhenduan_info);
					
					//再添加中医病症
					$zhenduan_zhongyi_sub_list = M("zhenduan_zhongyi")->where("zhixing_id like '$muban_zhuyuan_id' and zhenduan_relate_ID like '".$one_zhenduan["id"]."' and zhenduan_sub_type like '中医病症'")->select();
					foreach($zhenduan_zhongyi_sub_list as $key => $one_sub_zhenduan)
					{
					if($one_sub_zhenduan["zhenduan_bianhao"]=="tiantan")
						$one_sub_zhenduan["zhenduan_bianhao"] = " ";
						
						$one_new_sub_zhenduan_info = array(
							doctor_id => $_SESSION["user_number"],
							doctor_name => $_SESSION["user_name"],
							zhixing_id => $zhuyuan_id,
							zhixing_type => "住院",
							zhenduan_time => date('Y-m-d H:i:s'),
							zhenduan_mingcheng => $one_sub_zhenduan["zhenduan_mingcheng"],
							zhenduan_type => "入院初步诊断",
							zhenduan_bianhao => $one_sub_zhenduan["zhenduan_bianhao"],
							zhenduan_jieguo => $one_sub_zhenduan["zhenduan_jieguo"],
							zhenduan_sub_type => $one_sub_zhenduan["zhenduan_sub_type"],
							zhenduan_relate_ID => $zhenduan_add_result,
							zhenduan_relate => $one_zhenduan["zhenduan_mingcheng"],
							ruyuan_bingqing => $one_sub_zhenduan["ruyuan_bingqing"],
							other_info => $one_sub_zhenduan["other_info"]
						);
						$zhenduan_sub_add_result = M("zhenduan_zhongyi")->add($one_new_sub_zhenduan_info);
					}
				}
			}
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			//同步临床路径信息：
			$zhuyuan_basic_info[0]["linchuanglujing"] = $type;
			$zhuyuan_basic_info[0]["jibing_id"] = $jibing_id;
			$zhuyuan_basic_info[0]["linchuanglujing_buzhou"] = 1;
			if($type=="中医")
				$zhiliao_leibie = "中西医结合治疗";
			else
				$zhiliao_leibie = "西医治疗";
			$zhuyuan_basic_info[0]["zhiliao_leibie"] = $zhiliao_leibie;
			$list = M("zhuyuan_basic_info")->save($zhuyuan_basic_info[0]);
			//通过传递进来的参数判断是否需要使用临床路径模板
			if($_POST['ruyuan_jilu']=='true')
			{
			//1. 快速插入入院病史记录模板：
				//先获取模板内容：
				$muban_info = M("zhuyuan_bingshi")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
				//进行患者信息替换：
				$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
				
					//增加关键词标签
				$TextProcessingEngine = A('Home/TextProcessingEngine');
				$muban_info[0]['zhusu'] = $TextProcessingEngine->analyseText($muban_info[0]['zhusu']);
				$muban_info[0]['xianbingshi'] = $TextProcessingEngine->analyseText($muban_info[0]['xianbingshi']);
				$muban_info[0]['jiwangshi'] = $TextProcessingEngine->analyseText($muban_info[0]['jiwangshi']);
				$muban_info[0]['gerenshi'] = $TextProcessingEngine->analyseText($muban_info[0]['gerenshi']);
				$muban_info[0]['yuejingshi'] = $TextProcessingEngine->analyseText($muban_info[0]['yuejingshi']);
				$muban_info[0]['hunyushi'] = $TextProcessingEngine->analyseText($muban_info[0]['hunyushi']);
				$muban_info[0]['jiazushi'] = $TextProcessingEngine->analyseText($muban_info[0]['jiazushi']);
				$bingshi_info = array(
					"zhuyuan_id" => $zhuyuan_id,
					"zhusu" => $muban_info[0]["zhusu"],
					"xianbingshi" => $muban_info[0]["xianbingshi"],
					"jiwangshi" => $muban_info[0]["jiwangshi"],
					"gerenshi" => $muban_info[0]["gerenshi"],
					"hunyushi" => $muban_info[0]["hunyushi"],
					"yuejingshi" => $muban_info[0]["yuejingshi"],
					"jibenjiancha" => $muban_info[0]["jibenjiancha"],
					"yibanqingkuang" => $muban_info[0]["yibanqingkuang"],
					"jiazushi" => $muban_info[0]["jiazushi"],
					"bingshicaiji_riqi_time" => date('Y-m-d H:i')
				);
				//先判断是否已经编辑过病史信息了：
				$original_bingshi_info = M("zhuyuan_bingshi")->where("zhuyuan_id = '$zhuyuan_id'")->find();
				if($original_bingshi_info!==null)
				{
					foreach($bingshi_info as $key => $one_info)
					{
						if($key!="zhuyuan_id"&&($original_bingshi_info[$key]!=""&&$original_bingshi_info[$key]!="<p><br></p>")&&$shifou_fugai=="false")
							$bingshi_info[$key] = $original_bingshi_info[$key];
					}
					$zhuyuan_bingshi_result = M("zhuyuan_bingshi")->where("zhuyuan_id = '$zhuyuan_id'")->save($bingshi_info);
				}
				else
				{
					$zhuyuan_bingshi_result = M("zhuyuan_bingshi")->add($bingshi_info);
				}
			//2. 快速插入入院辅助检查模板：
				//先获取模板内容：
				$muban_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
				//进行患者信息替换：
				$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
				$zhuyuan_ruyuantigejiancha_info = array(
					"zhuyuan_id" => $zhuyuan_id,
					"jibenjiancha" => $muban_info[0]["jibenjiancha"],
					"yibanqingkuang" => $muban_info[0]["yibanqingkuang"],
					"pifu_nianmo_linbajie" => $muban_info[0]["pifu_nianmo_linbajie"],
					"toumianbu" => $muban_info[0]["toumianbu"],
					"jingbu" => $muban_info[0]["jingbu"],
					"xiongbu" => $muban_info[0]["xiongbu"],
					"fubu" => $muban_info[0]["fubu"],
					"xinzang" => $muban_info[0]["xinzang"],
					"gangmenshengzhi" => $muban_info[0]["gangmenshengzhi"],
					"jizhusizhi" => $muban_info[0]["jizhusizhi"],
					"shenjingxitong" => $muban_info[0]["shenjingxitong"],
					"other" => $muban_info[0]["other"]
				);
				//先判断是否已经编辑过入院体格检查信息了：
				$original_ruyuantigejiancha_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->find();
				if($original_ruyuantigejiancha_info!==null)
				{
					foreach($zhuyuan_ruyuantigejiancha_info as $key => $one_info)
					{
						if($key!="zhuyuan_id"&&($original_ruyuantigejiancha_info[$key]!=""&&$original_ruyuantigejiancha_info[$key]!="<p><br></p>")&&$shifou_fugai=="false")
							$zhuyuan_ruyuantigejiancha_info[$key] = $original_ruyuantigejiancha_info[$key];
					}
					$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_ruyuantigejiancha_info);
				}
				else
				{
					$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->add($zhuyuan_ruyuantigejiancha_info);
				}
			//3. 快速插入入院专科检查模板、辅助检查模版、中医望闻问切模板：
				//先获取模板内容：
				$muban_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
				//进行患者信息替换：
				$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
				$zhuyuan_ruyuantigejiancha_info = "";
				$zhuyuan_ruyuantigejiancha_info = array(
					"zhuyuan_id" => $zhuyuan_id,
					"ruyuan_fuzhu_jiancha" => $muban_info[0]["ruyuan_fuzhu_jiancha"],
					"ruyuan_zhuanke_jiancha" => $muban_info[0]["ruyuan_zhuanke_jiancha"],
					"ruyuan_zhongyi_jiancha" => $muban_info[0]["ruyuan_zhongyi_jiancha"]
				);
				//先判断是否已经编辑过入院体格检查信息了：
				$original_ruyuantigejiancha_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->find();
				if($original_ruyuantigejiancha_info!==null)
				{
					foreach($zhuyuan_ruyuantigejiancha_info as $key => $one_info)
					{
						if($key!="zhuyuan_id"&&($original_ruyuantigejiancha_info[$key]!=""&&$original_ruyuantigejiancha_info[$key]!="<p><br></p>")&&$shifou_fugai=="false")
							$zhuyuan_ruyuantigejiancha_info[$key] = $original_ruyuantigejiancha_info[$key];
					}
					$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_ruyuantigejiancha_info);
				}
				else
				{
					$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->add($zhuyuan_ruyuantigejiancha_info);
				}
			}
			if($_POST["shouci_bingcheng_jilu"]=="true")
			{
			//3. 快速插入首次病程记录模板：
				//先获取模板内容：
				$muban_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
				//进行患者信息替换：
				$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
				$zhuyuan_bingchengjilushouci_info = array(
					"zhuyuan_id" => $zhuyuan_id,
					"gaishu" => $muban_info[0]["gaishu"],
					"binglitedian" => $muban_info[0]["binglitedian"],
					"zhongyibianzhengyiju" => $muban_info[0]["zhongyibianzhengyiju"],
					"xiyibianzhengyiju" => $muban_info[0]["xiyibianzhengyiju"],
					"zhongyijianbiezhenduan" => $muban_info[0]["zhongyijianbiezhenduan"],
					"xiyijianbiezhenduan" => $muban_info[0]["xiyijianbiezhenduan"],
					"zhenliaojihua" => $muban_info[0]["zhenliaojihua"],
					"jikecuoshi" => $muban_info[0]["jikecuoshi"],
					"record_time" => date('Y-m-d H:i'),
					"huanzhe_jiashu_qianzi" => "需要"
				);
				//先判断是否已经编辑过首次病程记录信息了：
				$original_bingchengjilushouci_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id = '$zhuyuan_id'")->find();
				if($original_bingchengjilushouci_info!==null)
				{
					foreach($zhuyuan_bingchengjilushouci_info as $key => $one_info)
					{
						if($key!="zhuyuan_id"&&($original_bingchengjilushouci_info[$key]!=""&&$original_bingchengjilushouci_info[$key]!="<p><br></p>")&&$shifou_fugai=="false")
							$zhuyuan_bingchengjilushouci_info[$key] = $original_bingchengjilushouci_info[$key];
					}
					$zhuyuan_bingchengjilushouci_result = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_bingchengjilushouci_info);
				}
				else
				{
					$zhuyuan_bingchengjilushouci_result = M("zhuyuan_bingchengjilushouci")->add($zhuyuan_bingchengjilushouci_info);
				}
			}	
			if($_POST["meiri_bincheng_jilu"]=="true")
			{
			//4. 快速插入病程记录模板：
				//先获取模板内容：
				$muban_info = M("zhuyuan_bingchengjilu")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
				foreach($muban_info as $one_info)
				{
					//进行患者信息替换
					$one_info = $this->patientInfoReplace($one_info,$muban_zhuyuan_id,$zhuyuan_id);
					$one_zhuyuan_bingchengjilu_info = array(
						"zhuyuan_id" => $zhuyuan_id,
						"doctor_id" => $_SESSION["user_number"],
						"doctor_name" => $_SESSION["user_name"],
						"record_time" => date('Y-m-d H:i'),
						"bingcheng_sub_leibie" =>$one_info["bingcheng_sub_leibie"],
						"content" =>$one_info["content"],
						"chafang_doctor" =>$_SESSION["user_name"],
						"bingcheng_leibie" =>$one_info["bingcheng_leibie"],
						"huanzhe_jiashu_qianzi" =>$one_info["huanzhe_jiashu_qianzi"]
					);
					$zhuyuan_bingchengjilu_result = M("zhuyuan_bingchengjilu")->add($one_zhuyuan_bingchengjilu_info);
				}
			}
			//出院记录的写入
			if($_POST['chuyuan_type']=='true')
			{
				$linchuang_lujing_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->select();
				$muban_zhuyuan_id = $linchuang_lujing_info[0]["zhuyuan_id"];

				$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
				if($zhuyuan_basic_info[0]['zhuangtai']=="已出院")
				{
					$chuyuan_talbe_name = "zhuyuan_chuyuan_jilu";
				}
				elseif($zhuyuan_basic_info[0]['zhuangtai']=='24小时内出院')
				{
					$chuyuan_talbe_name = "zhuyuan_short_ruchuyuan_jilu";
				}
				elseif($zhuyuan_basic_info[0]['zhuangtai']=="自动出院")
				{
					$chuyuan_talbe_name = "zhuyuan_zidong_chuyuan_jilu";
				}
				elseif($zhuyuan_basic_info[0]['zhuangtai']=="24小时内自动出院")
				{
					$chuyuan_talbe_name = "zhuyuan_short_zidong_ruchuyuan_jilu";
				}
				elseif($zhuyuan_basic_info[0]['zhuangtai']=="死亡记录")
				{
					$chuyuan_talbe_name = "zhuyuan_siwang_chuyuan_jilu";
				}
				elseif($zhuyuan_basic_info[0]['zhuangtai']=="24小时内死亡记录")
				{
					$chuyuan_talbe_name = "zhuyuan_short_siwang_ruchuyuan_jilu";
				}
				else
				{
					$chuyuan_talbe_name = "";
				}
				
				if($chuyuan_talbe_name!="")
				{
					$zhuyuan_chuyuan_jilu_info = M($chuyuan_talbe_name)->where("zhuyuan_id like '$muban_zhuyuan_id'")->find();

					if($zhuyuan_chuyuan_jilu_info!=null)
					{
						$zhuyuan_chuyuan_jilu_info["zhuyuan_id"] = $zhuyuan_id;
						$zhuyuan_chuyuan_jilu_info["doctor_id"] = $_SESSION["user_number"];
						$zhuyuan_chuyuan_jilu_info["doctor_name"] = $_SESSION["user_name"];
						$zhuyuan_chuyuan_jilu_info["record_time"] = date('Y-m-d H:i');
						unset($zhuyuan_chuyuan_jilu_info["id"]);
						foreach($zhuyuan_chuyuan_jilu_info as $key=>$one_info)
						{
							if($one_info=="")
								unset($zhuyuan_chuyuan_jilu_info[$key]);
						}

						//先判断是否已经编辑过出院信息了：
						$original_chuyuan_info = M($chuyuan_talbe_name)->where("zhuyuan_id = '$zhuyuan_id'")->find();
						if($original_chuyuan_info!=null)
						{
							M($chuyuan_talbe_name)->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_chuyuan_jilu_info);
						}
						else
						{
							M($chuyuan_talbe_name)->add($zhuyuan_chuyuan_jilu_info);
						}						
					}
				}
			}
			//1. 取出医嘱信息，并复制
			//先把模板组号清零
			$last_muban_zuhao = -1;
			$last_new_zuhao = -2;
			foreach($_POST["changqi_yizhu_list"] as $key => $one_changqi_yizhu_id)
			{
				$temp_info = M("zhuyuan_yizhu_changqi")->where("id='$one_changqi_yizhu_id'")->find();
				unset($temp_info["id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				$temp_info["start_time"] = date('Y-m-d H:i');
				$temp_info["start_yishi_id"] = $_SESSION["user_number"];
				$temp_info["start_yishi_name"] = $_SESSION["user_name"];
				$temp_info["state"] = "已添加";
				unset($temp_info["start_hushi_name"]);
				unset($temp_info["stop_time"]);
				unset($temp_info["stop_yishi_name"]);
				unset($temp_info["stop_hushi_name"]);
				unset($temp_info["zhixing_count"]);
				unset($temp_info["zhixing_history"]);
				unset($temp_info["start_zhiye_yishi_name"]);
				unset($temp_info["stop_zhiye_yishi_name"]);
				
				//获取医嘱总组号信息，以更新最新的组号：
				$zuhao_info["content"] = $this->microTimeStamp();
				$current_muban_zuhao = $temp_info["zuhao"];
				$new_zuhao = $zuhao_info["content"]+1;
				//如果当前模板组合和上一个模板组号相同，就是同组医嘱
				if($current_muban_zuhao==$last_muban_zuhao)
					$temp_info["zuhao"] = $last_new_zuhao;
				else
				{
					$temp_info["zuhao"] = $new_zuhao;
					$last_new_zuhao = $new_zuhao;
				}
				
				//添加新的医嘱
				$add_result = M("zhuyuan_yizhu_changqi")->add($temp_info);
				
				if($current_muban_zuhao!=$last_muban_zuhao)
				{
					$zuhao_info["content"] = $new_zuhao;
					//更新组号信息
					if($add_result!==false)
					{
						$add_result = M("yiyuan_info")->save($zuhao_info);
					}
				}
				
				//最后保留当前更新模板组号，以下个循环进行对比
				$last_muban_zuhao = $current_muban_zuhao;
			}
			//先把模板组号清零
			$last_muban_zuhao = -1;
			$last_new_zuhao = -2;
			foreach($_POST["linshi_yizhu_list"] as $one_linshi_yizhu_id)
			{
				$temp_info = M("zhuyuan_yizhu_linshi")->where("id='$one_linshi_yizhu_id'")->find();
				unset($temp_info["id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				$temp_info["xiada_time"] = date('Y-m-d H:i');
				$temp_info["xiada_yishi_id"] = $_SESSION["user_number"];
				$temp_info["xiada_yishi_name"] = $_SESSION["user_name"];
				unset($temp_info["zhixing_time"]);
				unset($temp_info["zhixing_name"]);
				unset($temp_info["zhixing_id"]);
				unset($temp_info["zhixing_count"]);
				unset($temp_info["zhixing_history"]);
				unset($temp_info["xiada_zhiye_yishi_name"]);

				$temp_info["state"] = "已添加";
				//获取医嘱组号：
				$zuhao_info["content"] = $this->microTimeStamp();
				$current_muban_zuhao = $temp_info["zuhao"];
				$new_zuhao = $zuhao_info["content"]+1;
				
				//如果当前模板组合和上一个模板组号相同，就是同组医嘱
				if($current_muban_zuhao==$last_muban_zuhao)
					$temp_info["zuhao"] = $last_new_zuhao;
				else
				{
					$temp_info["zuhao"] = $new_zuhao;
					$last_new_zuhao = $new_zuhao;
				}
				
				//添加新的医嘱
				$add_result = M("zhuyuan_yizhu_linshi")->add($temp_info);
				
				if($current_muban_zuhao!=$last_muban_zuhao)
				{
					$zuhao_info["content"] = $new_zuhao;
					//更新组号信息
					if($add_result!==false)
					{
						$add_result = M("yiyuan_info")->save($zuhao_info);
					}
				}
				
				//最后保留当前更新模板组号，以下个循环进行对比
				$last_muban_zuhao = $current_muban_zuhao;
			}
			
			//2. 取出检查信息，并复制
			foreach($_POST["jiancha_list"] as $one_jiancha_id)
			{
				$temp_info = M("zhuyuan_fuzhujiancha")->where("id='$one_jiancha_id'")->find();
				unset($temp_info["id"]);
				unset($temp_info["relate_zhenduan_id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				$temp_info["patient_id"] = $patient_id;
				$temp_info["songjian_time"] = date('Y-m-d H:i');
				$temp_info["songjian_doctor_id"] = $_SESSION["user_number"];
				$temp_info["songjian_doctor_name"] = $_SESSION["user_name"];
				$temp_info["songjian_keshi"] = $_SESSION["user_department"];
				$temp_info["patient_id"] = $patient_id;
				$temp_info["jiancha_zhuangtai"] = "已申请";
				M("zhuyuan_fuzhujiancha")->add($temp_info);
			}
			
			//3. 取出处方信息，并复制
			//先把处方大组号清零
			$last_muban_zuhao = -1;
			$last_new_zuhao = -2;
			foreach($_POST["chufang_list"] as $one_chufang_id)
			{
				$temp_info = M("zhuyuan_chufang")->where("id='$one_chufang_id'")->find();
				$current_muban_chufang_id = $temp_info["id"];
				unset($temp_info["id"]);
				unset($temp_info["relate_zhenduan_id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				$temp_info["kaili_time"] = date('Y-m-d H:i');
				$temp_info["kaili_yishi_id"] = $_SESSION["user_number"];
				$temp_info["kaili_yishi_name"] = $_SESSION["user_name"];
				$temp_info["state"] = "新添加";
				
				//获取大处方组号：
				$zuhao_info = M("yiyuan_info")->where("name='dachufanghao'")->find();
				$current_muban_zuhao = $temp_info["dachufanghao"];
				$new_zuhao = $zuhao_info["content"]+1;
				
				if($current_muban_zuhao==$last_muban_zuhao)
				{
					$temp_info["dachufanghao"] = $last_new_zuhao;
				}
				else
				{
					$temp_info["dachufanghao"] = $new_zuhao;
					$last_new_zuhao = $new_zuhao;
				}
				
				$new_chufang_id = M("zhuyuan_chufang")->add($temp_info);
				
				if($current_muban_zuhao!=$last_muban_zuhao)
				{
					$zuhao_info["content"] = $new_zuhao;
					//更新组号信息
					if($new_chufang_id!==false)
					{
						$add_result = M("yiyuan_info")->save($zuhao_info);
					}
				}
				
				if($new_chufang_id!==false)
				{
					//复制处方具体信息：
					$chufang_detail_info = M("zhuyuan_chufang_detail")->where("chufang_id='$current_muban_chufang_id'")->select();
					foreach($chufang_detail_info as $one_yaopin_info)
					{
						unset($one_yaopin_info["id"]);
						$one_yaopin_info["chufang_id"] = $new_chufang_id;
						$one_yaopin_info["dachufanghao"] = $temp_info["dachufanghao"];
						$chufang_detail_add_result = M("zhuyuan_chufang_detail")->add($one_yaopin_info);
					}
				}
				
				$last_muban_zuhao = $current_muban_zuhao;
			}

		
			//快速插入医患沟通记录
			$yihuan_id = isset ( $_POST['yihuan_list'] ) ? $_POST['yihuan_list'] : '';
			if(!empty($yihuan_id))
			{
				$yihuan_where = implode(',',$yihuan_id);
				$yihuan_list=M('zhuyuan_yihuangoutongjilu')->where(' id in ('.$yihuan_where.') ')->select();
				$yihuan_num=M('zhuyuan_yihuangoutongjilu')->where(" zhuyuan_id='".$zhuyuan_id."' ")->count();
				if($yihuan_num>0)
					$i=$yihuan_num+1;	
				else
					$i=1;
				foreach ($yihuan_list as $key => $val ) 
				{
					$data = array(
						'zhuyuan_id'=>$zhuyuan_id,
						'content'=>$val['content'],
						'time'=>date('Y-m-d H:i:s'),
						'place'=>$val['place'],
						'number'=>$i,
						'doctor_id'=>$_SESSION['user_number'],
						'doctor_name'=>$_SESSION['user_name']
					);
					$i++;
					$yihuan_list=M('zhuyuan_yihuangoutongjilu')->add($data);
				}	
			}
		

			//快速插入查询其它文书
			$wenshu_id = isset ( $_POST['wenshu_list'] ) ? $_POST['wenshu_list'] : '';
			if(!empty($wenshu_id))
			{
				$wenshu_where = implode(',',$wenshu_id);
				$wenshu_list=M('zhuyuan_other_document')->where(' id in ('.$wenshu_where.') ')->select();
				foreach ($wenshu_list as $key => $val ) 
				{
					$data = array(
						'zhuyuan_id'=>$zhuyuan_id,
						'category'=>$val['category'],
						'data_document_id'=>$val['data_document_id'],
						'generate_time'=>date('Y-m-d H:i'),
						'generate_user_name'=>$_SESSION['user_name'],
						'document_type'=>$val['document_type'],
						'zhongwen_mingcheng'=>$val['zhongwen_mingcheng']
					);
					$yihuan_list=M('zhuyuan_other_document')->add($data);
				}
			}
			
		}
		else
		{
			$this->assign('system_info','请输入一个患者的住院号');
			$this->display("System:showError");
			exit();
		}
		$this->assign('system_info','临床路径已经更新！');
		$this->display("System:showRight");
		exit();
	}
	
	//获取典型处方
	public function getDianxingChufangContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if ($lujing_id==null || $lujing_id=="")
		{
			$lujing_id = "0";
		}
		
		$server_url = $_SESSION["server_url"];
		
		//1. 得到路径对应的参考病历号：
		$dianxing_bingli_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->find();
		$dianxing_bingli_zhuyuan_id = $dianxing_bingli_info['zhuyuan_id'];
		$jibing_id = $dianxing_bingli_info['jibing_id'];

			echo '<form class="linchuang_lujing_content_form" id="linchuang_lujing_content_form" method="post" action="http://'.$server_url.'/tiantan_emr/Home/Data/setDianxingChufangContent">';
				echo "<input type='hidden' name='zhuyuan_id' value='$zhuyuan_id' />";
				echo '<table width="100%">';
				echo "<input type='hidden' name='type' value='".$type."' ></input>";
				echo "<input type='hidden' name='lujing_id' value='".$lujing_id."' ></input>";
				echo "<input type='hidden' name='day' value='".$day."' ></input>";
				echo "当前参考病历名称：".$dianxing_bingli_info["zhongwen_mingcheng"];
				//1) 得到典型处方信息
				$zhuyuan_chufang_info = M("zhuyuan_chufang")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id'")->select();
				if(count($zhuyuan_chufang_info)<1)
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "当前参考病历还没有添加典型处方:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "请选择需要快速添加的【处方】：";
						echo "</td>";
					echo "</tr>";
					foreach($zhuyuan_chufang_info as $key=>$one_chufang)
					{
							$chufang_zhenduan_info = "";
							$key = $key+1;
							echo '<tr>';
							//1) 得到主要诊断和主症
							$relate_zhenduan_id_array = explode("+",$one_chufang["relate_zhenduan_id"]);
							foreach($relate_zhenduan_id_array as $one_zhenduan_id)
							{
								$one_zhenduan_id_info_array = explode("-",$one_zhenduan_id);
								if($one_zhenduan_id_info_array[0]=="xiyi")
								{
									$zhenduan_info_search_result = M("zhenduan_xiyi")->where("id='".$one_zhenduan_id_info_array[1]."'")->find();
									if(!empty($zhenduan_info_search_result["zhenduan_mingcheng"]))
										$chufang_zhenduan_info .= $zhenduan_info_search_result["zhenduan_mingcheng"]."|";
								}
								if($one_zhenduan_id_info_array[0]=="zhongyi")
								{
									$zhenduan_info_search_result = M("zhenduan_zhongyi")->where("id='".$one_zhenduan_id_info_array[1]."'")->find();
									if(!empty($zhenduan_info_search_result["zhenduan_mingcheng"]))
										$chufang_zhenduan_info .= $zhenduan_info_search_result["zhenduan_mingcheng"]."|";
								}
							}
							//2) 取得药品详细名称内容
							$zhuyuan_yaoping_info = M("zhuyuan_chufang_detail")->where("chufang_id=$one_chufang[id]")->select();
							$zhuyuan_yaoping_info_count = M("zhuyuan_chufang_detail")->where("chufang_id=$one_chufang[id]")->count();
							
							$yaoping_str = "";
							for($i=0; $i < $zhuyuan_yaoping_info_count; $i++)
							{
								$yaoping_str .= $zhuyuan_yaoping_info[$i]["yaopin_mingcheng"]."(".$zhuyuan_yaoping_info[$i]["ciliang"].$zhuyuan_yaoping_info[$i]["shiyong_danwei"].")";
								if(($i+1)%4==0 && $one_chufang["type"]=="中草药")
								{
									$yaoping_str .= "<br/>";
								}
								else if($one_chufang["type"]!="中草药")
								{
									$yaoping_str .= "<br/>";
								}
								else
								{
									$yaoping_str .= " ";
								}
							}
							
							if($yaoping_str=="")
							{
								$yaoping_str = "此处方为空:)";
							}
							if(empty($chufang_zhenduan_info))
								$chufang_zhenduan_info = "处方";
							echo "<td class='chufang_info' style='cursor:pointer'>";
								echo "<input type='checkbox' name='chufang_list[]' value='".$one_chufang["id"]."'>".$key."、".$chufang_zhenduan_info."【".$one_chufang['type']."处方】</input>";
								echo "<a class='detail_info' detail_info='".$yaoping_str."'>
										[详细信息]
								  </a>";
							echo "</td>";
							
							echo "</tr>";
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				}
				echo "</table>";
			echo "</form>";
	}
	
	//添加典型处方
	public function setDianxingChufangContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if(array_key_exists( 'chuyuan_type',$_REQUEST))
		{
			$chuyuan_type=$_REQUEST['chuyuan_type'];
		}
		
		if($zhuyuan_id!="")
		{
			//根据住院ID获取patient_id
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->find();
			$patient_id = $zhuyuan_basic_info["patient_id"];
		
			//取出处方信息，并复制
			//先把处方大组号清零
			$last_muban_zuhao = -1;
			$last_new_zuhao = -2;
			foreach($_POST["chufang_list"] as $one_chufang_id)
			{
				$temp_info = M("zhuyuan_chufang")->where("id='$one_chufang_id'")->find();
				$current_muban_chufang_id = $temp_info["id"];
				unset($temp_info["id"]);
				unset($temp_info["relate_zhenduan_id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				$temp_info["kaili_time"] = date('Y-m-d H:i');
				$temp_info["kaili_yishi_id"] = $_SESSION["user_number"];
				$temp_info["kaili_yishi_name"] = $_SESSION["user_name"];
				$temp_info["state"] = "新添加";
				
				//获取大处方组号：
				$zuhao_info = M("yiyuan_info")->where("name='dachufanghao'")->find();
				$current_muban_zuhao = $temp_info["dachufanghao"];
				$new_zuhao = $zuhao_info["content"]+1;
				
				if($current_muban_zuhao==$last_muban_zuhao)
				{
					$temp_info["dachufanghao"] = $last_new_zuhao;
				}
				else
				{
					$temp_info["dachufanghao"] = $new_zuhao;
					$last_new_zuhao = $new_zuhao;
				}
				
				$new_chufang_id = M("zhuyuan_chufang")->add($temp_info);
				
				if($current_muban_zuhao!=$last_muban_zuhao)
				{
					$zuhao_info["content"] = $new_zuhao;
					//更新组号信息
					if($new_chufang_id!==false)
					{
						$add_result = M("yiyuan_info")->save($zuhao_info);
					}
				}
				
				if($new_chufang_id!==false)
				{
					//复制处方具体信息：
					$chufang_detail_info = M("zhuyuan_chufang_detail")->where("chufang_id='$current_muban_chufang_id'")->select();
					foreach($chufang_detail_info as $one_yaopin_info)
					{
						unset($one_yaopin_info["id"]);
						$one_yaopin_info["chufang_id"] = $new_chufang_id;
						$one_yaopin_info["dachufanghao"] = $temp_info["dachufanghao"];
						$chufang_detail_add_result = M("zhuyuan_chufang_detail")->add($one_yaopin_info);
					}
				}
				
				$last_muban_zuhao = $current_muban_zuhao;
			}
		}
		else
		{
			$this->assign('system_info','请输入一个患者的住院号');
			$this->display("System:showError");
			exit();
		}
		$this->assign('system_info','典型处方快速添加成功，1秒钟后将会自动跳转回处方列表页。');
		$this->display("System:showRight");
		//页面跳转：
		echo'
		<script language="javascript">
			var i=1;
			var t;
			function showTimer(){
					if(i==0){
							window.location.href="http://'.$_SESSION["server_url"].'/tiantan_emr/Home/Chufangguanli/showList/zhuyuan_id/'.$zhuyuan_id.'";
							window.clearInterval(t);
					}else{
							i = i - 1 ;
					}
			}
			// 每隔一秒钟调用一次函数 showTimer()
			t = window.setInterval(showTimer,1000); 
			</script>
			';
		exit();
	}
	
	//获取典型长期医嘱
	public function getDianxingChangqiYizhuContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if ($lujing_id==null || $lujing_id=="")
		{
			$lujing_id = "0";
		}
		
		$server_url = $_SESSION["server_url"];
		
		//1. 得到路径对应的参考病历号：
		$dianxing_bingli_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->find();
		$dianxing_bingli_zhuyuan_id = $dianxing_bingli_info['zhuyuan_id'];
		$jibing_id = $dianxing_bingli_info['jibing_id'];
		
		//2. 开始输出入院、出院以及病程记录，医嘱、处方、检查模板信息：
			echo '<form class="linchuang_lujing_content_form" id="linchuang_lujing_content_form" method="post" action="http://'.$server_url.'/tiantan_emr/Home/Data/setDianxingChangqiYizhuContent">';
				echo "<input type='hidden' name='zhuyuan_id' value='$zhuyuan_id' />";
				echo '<table width="100%">';
				echo "<input type='hidden' name='type' value='".$type."' ></input>";
				echo "<input type='hidden' name='lujing_id' value='".$lujing_id."' ></input>";
				echo "<input type='hidden' name='day' value='".$day."' ></input>";
				echo "当前参考病历名称：".$dianxing_bingli_info["zhongwen_mingcheng"];
				//2.1. 输出医嘱信息
					//1） 得到参考病历长期医嘱
					$changqi_yizhu_info = M("zhuyuan_yizhu_changqi")->group('zuhao')->order("id")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id' and state!='已删除' and yongfa_type !='检查项目'")->select();
				if(count($changqi_yizhu_info)<1)
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "当前参考病历还没有添加典型长期医嘱:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					$current_time = date('Y-m-d H:i');
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "开始时间：";
							echo "<input type='text' action_type='datetime' name='start_time' class='Wdate' onclick='WdatePicker({skin:\"twoer\",dateFmt:\"yyyy-MM-dd HH:mm\",enableKeyboard:false})' value='$current_time'>";
					echo "</tr>";
					echo '<tr>';
						echo "</td>";
						echo "<td class='content_type'>";
							echo "请选择需要快速添加的【长期医嘱】：";
						echo "</td>";
					echo "</tr>";
					foreach($changqi_yizhu_info as $one_changqi_yizhu)
					{
							$changqi_yizhu_zu_info = M("zhuyuan_yizhu_changqi")->order("id")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id' and state!='已删除' and yongfa_type !='检查项目' and zuhao='".$one_changqi_yizhu['zuhao']."' ")->select();
							$num = count($changqi_yizhu_zu_info);
							if($num>1)
							{
								for ($i=0;$i<$num;$i++) 
								{
									echo '<tr>';
									echo "<td>";
									if($i=='0')
									{
										echo "<input type='checkbox' style='display:none;' class='zuhe' zuhao_val='zuhao_".$changqi_yizhu_zu_info[$i]["zuhao"]."' name='changqi_yizhu_list[]' value='".$changqi_yizhu_zu_info[$i]["id"]."' >".'　┏'.$changqi_yizhu_zu_info[$i]["content"].'　'.$changqi_yizhu_zu_info[$i]["ciliang"].$changqi_yizhu_zu_info[$i]["shiyong_danwei"]."</input>";	
										
									}
									else if($i==($num-1))
									{
										echo "<input type='checkbox' class='zuhe' zuhao_val='zuhao_".$changqi_yizhu_zu_info[$i]["zuhao"]."' name='changqi_yizhu_list[]' value='".$changqi_yizhu_zu_info[$i]["id"]."' >".'┗'.$changqi_yizhu_zu_info[$i]["content"].'　'.$changqi_yizhu_zu_info[$i]["ciliang"].$changqi_yizhu_zu_info[$i]["shiyong_danwei"].'　'.$changqi_yizhu_zu_info[$i]["yongfa"]."</input>";		
									}
									else
									{
										echo "<input type='checkbox' style='display:none;' class='zuhe' zuhao_val='zuhao_".$changqi_yizhu_zu_info[$i]["zuhao"]."' name='changqi_yizhu_list[]' value='".$changqi_yizhu_zu_info[$i]["id"]."' >".'　┃'.$changqi_yizhu_zu_info[$i]["content"].'　'.$changqi_yizhu_zu_info[$i]["ciliang"].$changqi_yizhu_zu_info[$i]["shiyong_danwei"]."</input>";	
									}
									echo "</td>";
									echo "</tr>";
								}
							}
							else
							{
								echo '<tr>';
								echo "<td>";
								echo "<input type='checkbox' name='changqi_yizhu_list[]' value='".$changqi_yizhu_zu_info[0]["id"]."' >".$changqi_yizhu_zu_info[0]["content"].'　'.$changqi_yizhu_zu_info[０]["ciliang"].$changqi_yizhu_zu_info[０]["shiyong_danwei"].'　'.$changqi_yizhu_zu_info[０]["yongfa"]."</input>";
								echo "</td>";
								echo "</tr>";
							}
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				}
				echo "</table>";
			echo "</form>";
	}
	
	//获取典型临时医嘱
	public function getDianxingLinshiYizhuContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if ($lujing_id==null || $lujing_id=="")
		{
			$lujing_id = "0";
		}
		
		$server_url = $_SESSION["server_url"];
		
		//1. 得到路径对应的参考病历号：
		$dianxing_bingli_info = M("zhuyuan_linchuanglujing")->where("id like '$lujing_id'")->find();
		$dianxing_bingli_zhuyuan_id = $dianxing_bingli_info['zhuyuan_id'];
		$jibing_id = $dianxing_bingli_info['jibing_id'];
		
		//2. 开始输出入院、出院以及病程记录，医嘱、处方、检查模板信息：
			echo '<form class="linchuang_lujing_content_form" id="linchuang_lujing_content_form" method="post" action="http://'.$server_url.'/tiantan_emr/Home/Data/setDianxingLinshiYizhuContent">';
				echo "<input type='hidden' name='zhuyuan_id' value='$zhuyuan_id' />";
				echo '<table width="100%">';
				echo "<input type='hidden' name='type' value='".$type."' ></input>";
				echo "<input type='hidden' name='lujing_id' value='".$lujing_id."' ></input>";
				echo "<input type='hidden' name='day' value='".$day."' ></input>";
				echo "当前参考病历名称：".$dianxing_bingli_info["zhongwen_mingcheng"];
					
				//2.1. 输出医嘱信息
					//2） 得到参考病历临时医嘱
					$lingshi_yizhu_info = M("zhuyuan_yizhu_linshi")->order('id')->group('zuhao')->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id' and state!='已删除' ")->select();
				$lingshi_yizhu_count = '';
				$lingshi_yizhu_ture = '';
				if(count($lingshi_yizhu_info)<1)
				{
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "当前参考病历还没有添加典型临时医嘱:)";
						echo "</td>";
					echo "</tr>";
				}
				else
				{
					$current_time = date('Y-m-d H:i');
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "下达时间：";
							echo "<input type='text' action_type='datetime' name='xiada_time' class='Wdate' onclick='WdatePicker({skin:\"twoer\",dateFmt:\"yyyy-MM-dd HH:mm\",enableKeyboard:false})' value='$current_time'>";
					echo "</tr>";
					echo '<tr>';
						echo "</td>";
						echo "<td class='content_type'>";
							echo "请选择需要快速添加的【临时医嘱】：";
						echo "</td>";
					echo "</tr>";
					foreach($lingshi_yizhu_info as $one_linshi_yizhu)
					{
						$lingshi_yizhu_count .= $one_linshi_yizhu['yizhu_id']."|";
						$linshi_yizhu_zu_info = M("zhuyuan_yizhu_linshi")->order("id")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id' and state!='已删除' and yongfa_type !='检查项目' and zuhao='".$one_linshi_yizhu['zuhao']."' ")->select();
							$num = count($linshi_yizhu_zu_info);
							if($num>1)
							{
								for ($i=0;$i<$num;$i++) 
								{
									echo '<tr>';
									echo "<td>";
									if($i=='0')
									{
										echo "<input style='display:none;' class='zuhe' zuhao_val='zuhao_".$linshi_yizhu_zu_info[$i]["zuhao"]."' yizhu_id = '".$linshi_yizhu_zu_info[$i]['yizhu_id']."' type='checkbox' name='linshi_yizhu_list[]' value='".$linshi_yizhu_zu_info[$i]["id"]."' >".'　┏'.$linshi_yizhu_zu_info[$i]["content"].'　'.$linshi_yizhu_zu_info[$i]["ciliang"].$linshi_yizhu_zu_info[$i]["shiyong_danwei"].'　'.$linshi_yizhu_zu_info[$i]["yongfa"]."</input>";	
									}
									else if($i==($num-1))
									{
										echo "<input class='zuhe' zuhao_val='zuhao_".$linshi_yizhu_zu_info[$i]["zuhao"]."' yizhu_id = '".$linshi_yizhu_zu_info[$i]['yizhu_id']."' type='checkbox' name='linshi_yizhu_list[]' value='".$linshi_yizhu_zu_info[$i]["id"]."' >".'┗'.$linshi_yizhu_zu_info[$i]["content"].'　'.$linshi_yizhu_zu_info[$i]["ciliang"].$linshi_yizhu_zu_info[$i]["shiyong_danwei"].'　'.$linshi_yizhu_zu_info[$i]["yongfa"]."</input>";
									}
									else
									{
										echo "<input style='display:none;' class='zuhe' zuhao_val='zuhao_".$linshi_yizhu_zu_info[$i]["zuhao"]."' yizhu_id = '".$linshi_yizhu_zu_info[$i]['yizhu_id']."' type='checkbox' name='linshi_yizhu_list[]' value='".$linshi_yizhu_zu_info[$i]["id"]."' >".'　┃'.$linshi_yizhu_zu_info[$i]["content"].'　'.$linshi_yizhu_zu_info[$i]["ciliang"].$linshi_yizhu_zu_info[$i]["shiyong_danwei"].'　'.$linshi_yizhu_zu_info[$i]["yongfa"]."</input>";
									}
									echo "</td>";
									echo "</tr>";
								}	
							}
							else
							{
								echo '<tr>';
								echo "<td>";
									echo "<input yizhu_id = '".$linshi_yizhu_zu_info[0]['yizhu_id']."' type='checkbox' name='linshi_yizhu_list[]' value='".$linshi_yizhu_zu_info[0]["id"]."' >".$linshi_yizhu_zu_info[0]["content"].'　'.$linshi_yizhu_zu_info[0]["ciliang"].$linshi_yizhu_zu_info[0]["shiyong_danwei"].'　'.$linshi_yizhu_zu_info[0]["yongfa"]."</input>";
								echo "</td>";
								echo "</tr>";
							}
						
					}
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";	
				}
				$yizhu_info = explode("|",$lingshi_yizhu_count);
				array_pop($yizhu_info);
				
				echo "</table>";
			echo "</form>";
	}
	
	public function setDianxingChangqiYizhuContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if(array_key_exists( 'chuyuan_type',$_REQUEST))
		{
			$chuyuan_type=$_REQUEST['chuyuan_type'];
		}
		if(array_key_exists( 'start_time',$_REQUEST))
		{
			$start_time=$_REQUEST['start_time'];
		}
		
		
		if($zhuyuan_id!="")
		{
			//根据住院ID获取patient_id
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->find();
			$patient_id = $zhuyuan_basic_info["patient_id"];
			
			//1. 取出医嘱信息，并复制
			//先把模板组号清零
			$last_muban_zuhao = -1;
			$last_new_zuhao = -2;
			foreach($_POST["changqi_yizhu_list"] as $key => $one_changqi_yizhu_id)
			{
				$temp_info = M("zhuyuan_yizhu_changqi")->where("id='$one_changqi_yizhu_id'")->find();
				unset($temp_info["id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				if(!empty($start_time))
					$temp_info["start_time"] = $start_time;
				else
					$temp_info["start_time"] = date('Y-m-d H:i');
				$temp_info["start_yishi_id"] = $_SESSION["user_number"];
				$temp_info["start_yishi_name"] = $_SESSION["user_name"];
				$temp_info["state"] = "已添加";
				unset($temp_info["start_hushi_name"]);
				unset($temp_info["stop_time"]);
				unset($temp_info["stop_yishi_name"]);
				unset($temp_info["stop_hushi_name"]);
				unset($temp_info["zhixing_count"]);
				unset($temp_info["zhixing_history"]);
				unset($temp_info["start_zhiye_yishi_name"]);
				unset($temp_info["stop_zhiye_yishi_name"]);
				
				//获取医嘱总组号信息，以更新最新的组号：
				$zuhao_info["content"] = $this->microTimeStamp();
				$current_muban_zuhao = $temp_info["zuhao"];
				$new_zuhao = $zuhao_info["content"]+1;
				//如果当前模板组合和上一个模板组号相同，就是同组医嘱
				if($current_muban_zuhao==$last_muban_zuhao)
					$temp_info["zuhao"] = $last_new_zuhao;
				else
				{
					$temp_info["zuhao"] = $new_zuhao;
					$last_new_zuhao = $new_zuhao;
				}
				
				//添加新的医嘱
				$add_result = M("zhuyuan_yizhu_changqi")->add($temp_info);
				
				if($current_muban_zuhao!=$last_muban_zuhao)
				{
					$zuhao_info["content"] = $new_zuhao;
					//更新组号信息
					if($add_result!==false)
					{
						$add_result = M("yiyuan_info")->save($zuhao_info);
					}
				}
				
				//最后保留当前更新模板组号，以下个循环进行对比
				$last_muban_zuhao = $current_muban_zuhao;
			}
		}
		else
		{
			$this->assign('system_info','请输入一个患者的住院号');
			$this->display("System:showError");
			exit();
		}
		$this->assign('system_info','典型长期医嘱快速添加成功，1秒钟后将会自动跳转回长期医嘱列表页。');
		$this->display("System:showRight");
		//页面跳转：
		echo'
		<script language="javascript">
			var i=1;
			var t;
			function showTimer(){
					if(i==0){
							window.location.href="http://'.$_SESSION["server_url"].'/tiantan_emr/Home/Yizhuguanli/showChangqi/zhuyuan_id/'.$zhuyuan_id.'";
							window.clearInterval(t);
					}else{
							i = i - 1 ;
					}
			}
			// 每隔一秒钟调用一次函数 showTimer()
			t = window.setInterval(showTimer,1000); 
			</script>
			';
		exit();
	}
	
	public function setDianxingLinshiYizhuContent()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if(array_key_exists( 'lujing_id',$_REQUEST))
		{
			$lujing_id=$_REQUEST['lujing_id'];
		}
		if(array_key_exists( 'day',$_REQUEST))
		{
			$day=$_REQUEST['day'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		if(array_key_exists( 'chuyuan_type',$_REQUEST))
		{
			$chuyuan_type=$_REQUEST['chuyuan_type'];
		}
		if(array_key_exists( 'xiada_time',$_REQUEST))
		{
			$xiada_time=$_REQUEST['xiada_time'];
		}
		if($zhuyuan_id!="")
		{
			//根据住院ID获取patient_id
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->find();
			$patient_id = $zhuyuan_basic_info["patient_id"];
			
			//1. 取出医嘱信息，并复制
			//先把模板组号清零
			$last_muban_zuhao = -1;
			$last_new_zuhao = -2;
			foreach($_POST["linshi_yizhu_list"] as $key => $one_linshi_yizhu_id)
			{
				$temp_info = M("zhuyuan_yizhu_linshi")->where("id='$one_linshi_yizhu_id'")->find();
				unset($temp_info["id"]);
				foreach($temp_info as $key => $one_temp_info)
				{
					if($one_temp_info=="")
						unset($temp_info[$key]);
				}
				$temp_info["zhuyuan_id"] = $zhuyuan_id;
				if(!empty($xiada_time))
					$temp_info["xiada_time"] = $xiada_time;
				else
					$temp_info["xiada_time"] = date('Y-m-d H:i');
				$temp_info["xiada_yishi_id"] = $_SESSION["user_number"];
				$temp_info["xiada_yishi_name"] = $_SESSION["user_name"];
				$temp_info["state"] = "已添加";
				unset($temp_info["zhixing_name"]);
				unset($temp_info["zhixing_id"]);
				unset($temp_info["zhixing_time"]);
				unset($temp_info["zhixing_doctor_name"]);
				unset($temp_info["zhixing_count"]);
				unset($temp_info["zhixing_history"]);
				unset($temp_info["xiada_zhiye_yishi_name"]);
				
				//获取医嘱总组号信息，以更新最新的组号：
				$zuhao_info["content"] = $this->microTimeStamp();
				$current_muban_zuhao = $temp_info["zuhao"];
				$new_zuhao = $zuhao_info["content"]+1;
				//如果当前模板组合和上一个模板组号相同，就是同组医嘱
				if($current_muban_zuhao==$last_muban_zuhao)
					$temp_info["zuhao"] = $last_new_zuhao;
				else
				{
					$temp_info["zuhao"] = $new_zuhao;
					$last_new_zuhao = $new_zuhao;
				}
				
				//添加新的医嘱
				$add_result = M("zhuyuan_yizhu_linshi")->add($temp_info);
				
				if($current_muban_zuhao!=$last_muban_zuhao)
				{
					$zuhao_info["content"] = $new_zuhao;
					//更新组号信息
					if($add_result!==false)
					{
						$add_result = M("yiyuan_info")->save($zuhao_info);
					}
				}
				
				//最后保留当前更新模板组号，以下个循环进行对比
				$last_muban_zuhao = $current_muban_zuhao;
			}
		}
		else
		{
			$this->assign('system_info','请输入一个患者的住院号');
			$this->display("System:showError");
			exit();
		}
		$this->assign('system_info','典型临时医嘱快速添加成功，1秒钟后将会自动跳转回临时医嘱列表页。');
		$this->display("System:showRight");
		//页面跳转：
		echo'
		<script language="javascript">
			var i=1;
			var t;
			function showTimer(){
					if(i==0){
							window.location.href="http://'.$_SESSION["server_url"].'/tiantan_emr/Home/Yizhuguanli/showLinshi/zhuyuan_id/'.$zhuyuan_id.'";
							window.clearInterval(t);
					}else{
							i = i - 1 ;
					}
			}
			// 每隔一秒钟调用一次函数 showTimer()
			t = window.setInterval(showTimer,1000); 
			</script>
			';
		exit();
	}
	
	public function getLishiBingli()
	{
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_GET['zhuyuan_id'];
		}
		echo "<ul>";
		if ($zhuyuan_id==null || $zhuyuan_id=="")
		{
			echo "<li>无法获取该患者病历号</li>";
		}
		else
		{
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			$patient_id = $zhuyuan_basic_info[0]["patient_id"];
			$patient_basic_info = M("patient_basic_info")->where("patient_id like '$patient_id'")->select();
			$zhuyuan_history = $patient_basic_info[0]["zhuyuan_history"];
			$zhuyuan_history_arr = explode("+",$zhuyuan_history);
			foreach($zhuyuan_history_arr as $key => $one)
			{
				if($one=="" || $one==$zhuyuan_id)
				{
					unset($zhuyuan_history_arr[$key]);
				}
			}
			foreach($zhuyuan_history_arr as $one)
			{
				$data = M("zhuyuan_basic_info")->where("zhuyuan_id like '$one'")->select();
				$data_yishi = M("zhuyuan_zongjie_info")->where("zhuyuan_id like '$one'")->select();
				$content = "病历号:".$one."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp责任医师:".$data_yishi[0]["zhuyuanyishi_name"]."<br/>入院时间:".$data[0]["ruyuan_riqi_time"]."&nbsp&nbsp&nbsp出院时间:".$data[0]["chuyuan_riqi_time"];
				echo "<li class='bingli' zhuyuan_id='".$one."'><input type='button' style='float:right;' value='预览' class='search_button'/>".$content."</li>";
			}
			if(sizeof($zhuyuan_history_arr)==0)
				echo "<li>该患者没有历史住院病历:)</li>";
		}
		echo "</ul>";
	}
	
	public function getLishiBingliContent()
	{
		if(array_key_exists( 'muban_zhuyuan_id',$_REQUEST))
		{
			$muban_zhuyuan_id=$_REQUEST['muban_zhuyuan_id'];
		}
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		$server_url = $_SESSION["server_url"];
		
		//2. 开始输出入院、出院以及病程记录，医嘱、处方、检查模板信息：
			echo '<form class="linchuang_lujing_content_form" id="linchuang_lujing_content_form" method="post" action="http://'.$server_url.'/tiantan_emr/Home/Data/setLishiBingli">';
				echo "<input type='hidden' name='zhuyuan_id' value='$zhuyuan_id' />";
				echo "<input type='hidden' name='muban_zhuyuan_id' value='$muban_zhuyuan_id' />";
				echo '<table width="100%">';
				//1.1. 输出入院记录
					echo '<tr>';
						echo "<td class='content_type'>";
							echo "请选择需要快速使用的【病历模板】：";
						echo "</td>";
					echo "</tr>";
					echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='ruyuan_jilu' value='true'>入院记录</input>";
						echo "</td>";
					echo "</tr>";
					echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='shouci_bingcheng_jilu' value='true'>首次病程记录</input>";
						echo "</td>";
					echo "</tr>";
					/*echo '<tr>';
						echo "<td>";
							echo "<input type='checkbox' name='meiri_bincheng_jilu' value='true'>每日病程记录</input>";
						echo "</td>";
					echo "</tr>";*/
					//5.1. 出院信息
					//1）查询出院信息内容
					/*$zhuyuan_chuyuan_info = M("zhuyuan_basic_info")->where("zhuyuan_id='$dianxing_bingli_zhuyuan_id'")->select();
					if($zhuyuan_chuyuan_info[0]['zhuangtai']=="已出院")
					{
						// 1.已出院 
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>出院记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=='24小时内出院')
					{
						// 2.24小时内出院记录
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>24小时内出院记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="自动出院")
					{
						// 3.自动出院记录 
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>".出院记录."</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="24小时内自动出院")
					{
						// 4.24小时内自动出院记录 
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>24小时内自动出院记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="死亡记录")
					{
						// 5.死亡记录
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>".死亡记录 ."</input>";
							echo "</td>";
						echo "</tr>";
					}
					elseif($zhuyuan_chuyuan_info[0]['zhuangtai']=="24小时内死亡记录")
					{
						// 6.24小时内死亡记录
						echo '<tr>';
							echo "<td>";
								echo "<input type='checkbox' name='chuyuan_type' value='true'>24小时内死亡记录</input>";
							echo "</td>";
						echo "</tr>";
					}
					else
					{}*/
					echo "<tr>";
						echo "<td style='border-top:dashed 1px #a5cafc;'></td>";
					echo "</tr>";
				echo "</table>";
			echo "</form>";
	}
	
	public function setLishiBingli()
	{
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_POST['zhuyuan_id'];
		}
		if(array_key_exists( 'muban_zhuyuan_id',$_REQUEST))
		{
			$muban_zhuyuan_id=$_POST['muban_zhuyuan_id'];
		}
		if($_POST['ruyuan_jilu']=='true')
		{
			$muban_info = M("zhuyuan_bingshi")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
			$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
			$bingshi_info = array(
				"zhuyuan_id" => $zhuyuan_id,
				"zhusu" => $muban_info[0]["zhusu"],
				"xianbingshi" => $muban_info[0]["xianbingshi"],
				"jiwangshi" => $muban_info[0]["jiwangshi"],
				"gerenshi" => $muban_info[0]["gerenshi"],
				"hunyushi" => $muban_info[0]["hunyushi"],
				"yuejingshi" => $muban_info[0]["yuejingshi"],
				"jibenjiancha" => $muban_info[0]["jibenjiancha"],
				"yibanqingkuang" => $muban_info[0]["yibanqingkuang"],
				"jiazushi" => $muban_info[0]["jiazushi"],
				"bingshicaiji_riqi_time" => date('Y-m-d H:i')
			);
			$original_bingshi_info = M("zhuyuan_bingshi")->where("zhuyuan_id = '$zhuyuan_id'")->find();
			if($original_bingshi_info!==null)
			{
				foreach($bingshi_info as $key => $one_info)
				{
					if($key!="zhuyuan_id"&&($original_bingshi_info[$key]!=""&&$original_bingshi_info[$key]!="<p><br></p>"))
						$bingshi_info[$key] = $original_bingshi_info[$key];
				}
				$zhuyuan_bingshi_result = M("zhuyuan_bingshi")->where("zhuyuan_id = '$zhuyuan_id'")->save($bingshi_info);
			}
			else
			{
				$zhuyuan_bingshi_result = M("zhuyuan_bingshi")->add($bingshi_info);
			}
			$muban_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
			$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
			$zhuyuan_ruyuantigejiancha_info = array(
				"zhuyuan_id" => $zhuyuan_id,
				"jibenjiancha" => $muban_info[0]["jibenjiancha"],
				"yibanqingkuang" => $muban_info[0]["yibanqingkuang"],
				"pifu_nianmo_linbajie" => $muban_info[0]["pifu_nianmo_linbajie"],
				"toumianbu" => $muban_info[0]["toumianbu"],
				"jingbu" => $muban_info[0]["jingbu"],
				"xiongbu" => $muban_info[0]["xiongbu"],
				"fubu" => $muban_info[0]["fubu"],
				"xinzang" => $muban_info[0]["xinzang"],
				"gangmenshengzhi" => $muban_info[0]["gangmenshengzhi"],
				"jizhusizhi" => $muban_info[0]["jizhusizhi"],
				"shenjingxitong" => $muban_info[0]["shenjingxitong"],
				"other" => $muban_info[0]["other"]
			);
			$original_ruyuantigejiancha_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->find();
			if($original_ruyuantigejiancha_info!==null)
			{
				foreach($zhuyuan_ruyuantigejiancha_info as $key => $one_info)
				{
					if($key!="zhuyuan_id"&&($original_ruyuantigejiancha_info[$key]!=""&&$original_ruyuantigejiancha_info[$key]!="<p><br></p>"))
						$zhuyuan_ruyuantigejiancha_info[$key] = $original_ruyuantigejiancha_info[$key];
				}
				$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_ruyuantigejiancha_info);
			}
			else
			{
				$zhuyuan_ruyuantigejiancha_result = M("zhuyuan_ruyuantigejiancha")->add($zhuyuan_ruyuantigejiancha_info);
			}
		}
		if($_POST["shouci_bingcheng_jilu"]=="true")
		{
			$muban_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id like '$muban_zhuyuan_id'")->select();
			$muban_info[0] = $this->patientInfoReplace($muban_info[0],$muban_zhuyuan_id,$zhuyuan_id);
			$zhuyuan_bingchengjilushouci_info = array(
				"zhuyuan_id" => $zhuyuan_id,
				"gaishu" => $muban_info[0]["gaishu"],
				"binglitedian" => $muban_info[0]["binglitedian"],
				"zhongyibianzhengyiju" => $muban_info[0]["zhongyibianzhengyiju"],
				"xiyibianzhengyiju" => $muban_info[0]["xiyibianzhengyiju"],
				"zhongyijianbiezhenduan" => $muban_info[0]["zhongyijianbiezhenduan"],
				"xiyijianbiezhenduan" => $muban_info[0]["xiyijianbiezhenduan"],
				"zhenliaojihua" => $muban_info[0]["zhenliaojihua"],
				"jikecuoshi" => $muban_info[0]["jikecuoshi"],
				"record_time" => date('Y-m-d H:i')
			);
			$original_bingchengjilushouci_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id = '$zhuyuan_id'")->find();
			if($original_bingchengjilushouci_info!==null)
			{
				foreach($zhuyuan_bingchengjilushouci_info as $key => $one_info)
				{
					if($key!="zhuyuan_id"&&($original_bingchengjilushouci_info[$key]!=""&&$original_bingchengjilushouci_info[$key]!="<p><br></p>"))
						$zhuyuan_bingchengjilushouci_info[$key] = $original_bingchengjilushouci_info[$key];
				}
				$zhuyuan_bingchengjilushouci_result = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id = '$zhuyuan_id'")->save($zhuyuan_bingchengjilushouci_info);
			}
			else
			{
				$zhuyuan_bingchengjilushouci_result = M("zhuyuan_bingchengjilushouci")->add($zhuyuan_bingchengjilushouci_info);
			}
		}
		$this->assign('system_info','历史病历导入成功！');
		$this->display("System:showRight");
		exit();
	}
	
	public function rebuiltYizhu()
	{
		if(array_key_exists( 'zhuyuan_id',$_REQUEST))
		{
			$zhuyuan_id=$_REQUEST['zhuyuan_id'];
		}
		$data = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_id' and state != '已删除' and state != '待停止确认' and state != '停止执行' and state != '停止待核对' and state != '已添加'")->order("start_time asc")->select();
		if(count($data)>=1)
		{
			echo '<table border="0" cellpadding="0" cellspacing="0" class="content_head_table" style="margin-top:25px">';
			echo '<tr height="25px">';
			echo '<td width="14%" rowspan="2">请选择</td>';
			echo '<td width="16%">起始</td>';
			echo '<td width="6%" rowspan="2" class="double_line">医生签名</td>';
			echo '<td width="6%" rowspan="2" class="double_line">护士签名</td>';
			echo '<td width="40%" rowspan="2"  class="long_content">长期医嘱</td>';
			echo '<td width="10%" rowspan="2">状态</td>';
			echo '</tr>';
			echo '<tr height="25px">';
			echo '<td>年月日时分</td>';
			echo '</tr>';
			echo '</table>';
			//下面是医嘱内容
			for($count=0;$count<count($data);$count++)
			{
				$changqi_yizhu[$count] = $data[$count];
				if($data[$count]['zuhao']!=$data[$count+1]['zuhao'])
				{
					$changqi_yizhu[$count]['islast'] = 'true';
					if($changqi_yizhu[$count-1]['islast']=='false')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left">'.'┗'.$changqi_yizhu[$count]['content'].'</span>';
					else
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left">'.$changqi_yizhu[$count]['content'].'</span>';
				}
				else
				{
					$changqi_yizhu[$count]['islast'] = 'false';
					if($count == 0 || $changqi_yizhu[$count-1]['islast'] == 'true')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left">'.'┏'.$changqi_yizhu[$count]['content'].'</span>';
					else
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left">'.'┃'.$changqi_yizhu[$count]['content'].'</span>';
				}
				//看是否追加显示每次用量
				if($changqi_yizhu[$count]['yongfa_type']=='检查项目'||$changqi_yizhu[$count]['yongfa_type']=='诊疗项目'||$changqi_yizhu[$count]['yongfa_type']=='检查项目'||$changqi_yizhu[$count]['yongfa_type']=='输液'||$changqi_yizhu[$count]['yongfa_type']=='西药中成药'||$changqi_yizhu[$count]['yongfa_type']=='中草药')
				{
					$changqi_yizhu[$count]['content_show'] =  '<div class="float_left_full_width">'.$changqi_yizhu[$count]['content_show'].'<span class="float_right">'.$changqi_yizhu[$count]['ciliang'].$changqi_yizhu[$count]['shiyong_danwei']."</span></div>";
				}
				
				//看是否追加显示频率和用法
				if($changqi_yizhu[$count]['yongfa_type']=='检查项目'||$changqi_yizhu[$count]['yongfa_type']=='诊疗项目'||$changqi_yizhu[$count]['yongfa_type']=='检查项目'||$changqi_yizhu[$count]['yongfa_type']=='输液'||$changqi_yizhu[$count]['yongfa_type']=='西药中成药'||$changqi_yizhu[$count]['yongfa_type']=='中草药')
				{
					if($changqi_yizhu[$count]['islast'] == 'true'||$count == 0)
					{
						$changqi_yizhu[$count]['content_show'] =  $changqi_yizhu[$count]['content_show'].'<div class="fix_right">'.$changqi_yizhu[$count]['pinlv'];
						if($changqi_yizhu[$count]['yongfa']!="处置"&&$changqi_yizhu[$count]['yongfa']!="小时计费")
							$changqi_yizhu[$count]['content_show'] .= "&nbsp&nbsp".$changqi_yizhu[$count]['yongfa']."</div>";
						else
							$changqi_yizhu[$count]['content_show'] .= "</div>";
					}
				}
			}
			echo '<table border="0" cellpadding="0" cellspacing="0" class="content_table">';
			for($count=0;$count<count($changqi_yizhu);$count++)
			{
				echo '<tr height="50px"  id="'.$changqi_yizhu[$count]["id"].'" name="'.$changqi_yizhu[$count]["zuhao"].'">';
				if($changqi_yizhu[$count-1]['zuhao']!=$changqi_yizhu[$count]['zuhao'])
				{
					echo '<td width="14%"><input type="checkbox" id="'.$changqi_yizhu[$count]['id'].'" class="yizhu_check" zuhao="'.$changqi_yizhu[$count]['zuhao'].'" checked="checked"/></td>';
					echo '<td width="16%" name="start_time">'.$changqi_yizhu[$count]['start_time'].'</td>';
					echo '<td width="6%"  name="start_yishi_name">'.$changqi_yizhu[$count]['start_yishi_name'].'</td>';
					echo '<td width="6%"  name="start_hushi_name">'.$changqi_yizhu[$count]['start_hushi_name'].'</td>';
					echo '<td width="40%" class="long_content">'.$changqi_yizhu[$count]['content_show'].'</td>';
					echo '<td width="10%">'.$changqi_yizhu[$count]['state'].'</td>';
				}
				else
				{
					echo '<td width="13%">"<input type="checkbox" id="'.$changqi_yizhu[$count]['id'].'" class="yizhu_check" zuhao="'.$changqi_yizhu[$count]['zuhao'].'" checked="checked" disabled="disabled"/></td>';
					echo '<td width="13%" name="start_time">'.$changqi_yizhu[$count]['start_time'].'</td>';
					echo '<td width="6%"  name="start_yishi_name">'.$changqi_yizhu[$count]['start_yishi_name'].'</td>';
					echo '<td width="6%"  name="start_hushi_name">'.$changqi_yizhu[$count]['start_hushi_name'].'</td>';
					echo '<td width="40%" class="long_content">'.$changqi_yizhu[$count]['content_show'].'</td>';
					echo '<td width="10%">'.$changqi_yizhu[$count]['state'].'</td>';
				}
				echo '</tr>';
			}
			echo '</table>';
		}
	}

	public function getFlatData()
	{
		if(array_key_exists( 'id',$_REQUEST))
		{
			$pid=$_REQUEST['id'];
		}
		if(array_key_exists( 'label_class',$_REQUEST))
		{
			$class=$_REQUEST['label_class'];
		}
		echo "<ul>";
		if ($pid==null || $pid=="")
		{
			$pid = "0";
		}
		$data = M("data_xiangmu")->where("pid like '$pid'")->select();
		$index_mingcheng = M("data_xiangmu")->where("id like '$pid'")->select();
		for($i=0;$i<sizeof($data);$i++)
		{
			if($data[$i]["zhongwen_mingcheng"]=="")
				$data[$i]["zhongwen_mingcheng"] = "无".$index_mingcheng[0]["zhongwen_mingcheng"];
			echo "<li class='".$class."' id='".$data[$i]["id"]."' mingcheng='".$data[$i]["zhongwen_mingcheng"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
		}
		echo "</ul>";
	}

	// 筛选麻醉方式
	public function searchMazuiFangshi()
	{
		$keyword = $_POST["keyword"];
		if(!empty($keyword))
		{
			$result_html = "<ul>";
			$result_list =  M("data_xiangmu")->where("pid=33000 and zhongwen_mingcheng like '%".$keyword."%'")->select();
			if(count($result_list)==0)
			{
				// $result_html = "抱歉，没有找到相关麻醉记录";
				// 若搜索结果为空则显示所有
				$result_list =  M("data_xiangmu")->where("pid=33000")->select();
				foreach ($result_list as $key => $result)
				{
					$result_html .= "<li class='mazui_item' id='".$result["id"]."' mingcheng='".$result["zhongwen_mingcheng"]."'>".$result["zhongwen_mingcheng"]."</li>";
				}
				$result_html .= "</ul>";
			}
			else
			{
				// 若搜索结果不为空高亮显示关键词
				$before_keyword = "";
				$after_keyword = "";
				foreach ($result_list as $key => $result)
				{
					$keyword_pos = stripos($result["zhongwen_mingcheng"], $keyword);
					$before_keyword = substr($result["zhongwen_mingcheng"], 0,$keyword_pos);
					$after_keyword = substr($result["zhongwen_mingcheng"], $keyword_pos+strlen($keyword));
					$result_html .= "<li class='mazui_item' id='".$result["id"]."' mingcheng='".$result["zhongwen_mingcheng"]."'>".$before_keyword."<mazuikw>".$keyword."</mazuikw>".$after_keyword."</li>";
				}
				$result_html .= "</ul>";
			}			
		}
		else
		{
			$result_html = "";
		}
		echo $result_html;
	}

	/////
	public function getSelectDataJson()
	{
		$pid = $_GET['pid'];
		if ($pid==null || $pid=="")
			$request_keyword = "0";
		if($pid==1)
		{
			$data = M("data_xiangmu")->where("pid like '$pid' and other_info like '%检查%'")->select();
		}
		else
		{
			$data = M("data_xiangmu")->where("pid like '$pid'")->select();
		}
		echo'<option value="0">请选择</option>';
		foreach($data as $one)
		{
			echo '<option id="'.$one['id'].'" value="'.$one['zhongwen_mingcheng'].'|'.$one['id'].'|'.$one['relate_table_name'].'">'.$one['zhongwen_mingcheng'].'</option>';
		}
		echo'<option value="others">其它</option>';
	}
	
	public function getSelectDoctorJson()
	{
		$pid = $_GET['pid'];
		$zhixing_type = $_GET['zhixing_type'];
		if ($pid==null || $pid=="")
			$request_keyword = "0";
		$data = M("data_xiangmu")->where("pid like '$pid' and other_info like '%$zhixing_type%' and other_info like '%检验科检查%'")->select();
		//echo'<option value="0">请选择</option>';
		foreach($data as $one)
		{
			echo '<option id="'.$one['id'].'" value="'.$one['zhongwen_mingcheng'].'|'.$one['id'].'|'.$one['relate_table_name'].'">'.$one['zhongwen_mingcheng'].'</option>';
		}
		echo'<option value="others">其它</option>';
	}

	/////
	public function getXiangmuInfoJson()
	{
		$pid = $_GET['pid'];
		$request_keyword = $_GET['term'];
		if ($pid==null || $pid=="")
			$request_keyword = "0";
		$data = M("data_xiangmu")->where("pid like '$pid' and keyword_shuoming like '%$request_keyword%'")->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["other_info"].'", "desc":"编码:'.$data[$i]['pinyin_index'].' |'.$data[$i]['code'].'", "relate_xiangmu_info":"'.$data[$i]['id'].'|'.$data[$i]['relate_table_name'].'|'.$data[$i]['other_info'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["other_info"].'", "desc":"编码:'.$data[$i]['pinyin_index'].' |'.$data[$i]['code'].'", "relate_xiangmu_info":"'.$data[$i]['id'].'|'.$data[$i]['relate_table_name'].'|'.$data[$i]['other_info'].'"}';
		echo "]";
	}
	
	public function getZidingyiXiangmu()
	{
		$pid = $_GET['pid'];
		if($pid=="xiyao")
		{
			$request_keyword = $_GET['term'];
			if ($pid==null || $pid=="")
				$request_keyword = "0";
			$data = M("data_xiangmu")->where("(pid like '11000' or pid like '13000' or pid like '15100') and keyword_shuoming like '%$request_keyword%'")->select();
			echo "[";
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["other_info"].'", "desc":"编码:'.$data[$i]['pinyin_index'].' |'.$data[$i]['code'].'", "relate_xiangmu_info":"'.$data[$i]['id'].'|'.$data[$i]['relate_table_name'].'|'.$data[$i]['other_info'].'"},';
			}
			if(sizeof($data)>0)
				echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["other_info"].'", "desc":"编码:'.$data[$i]['pinyin_index'].' |'.$data[$i]['code'].'", "relate_xiangmu_info":"'.$data[$i]['id'].'|'.$data[$i]['relate_table_name'].'|'.$data[$i]['other_info'].'"}';
			echo "]";
		}
		
		
	}
	//
	public function getXiangmuName()
	{
		$request_keyword = $_GET['term'];
		$data = M("data_xiangmu")->field('id,zhongwen_mingcheng,other_info')->where("keyword_shuoming like '%$request_keyword%' and other_info like '%|keshi:%' ")->select();
		$jons_html = "[";
		foreach($data as $data_one)
		{
			$tishi = '';
			$other_info = explode("|",$data_one["other_info"]);
			$temp = '';
			foreach($other_info as $val)
			{
				$temp = explode(":",$val);
				if($temp['0'] == 'keshi')
				{
					$tishi .= '科室:'.$temp['1'].'|';
				}
				if($temp['0'] == 'mingcheng')
				{
					$tishi .= '项目:'.$temp['1'].'|';
				}
				if($temp['0'] == 'buwei')
				{
					$tishi .= '部位:'.$temp['1'].'|';
				}
			}
			$data_one["tishi"] = substr($tishi,0,-1);
			$jons_html .= '{"label":"'.$data_one["zhongwen_mingcheng"].'", "tishi":"'.$data_one["tishi"].'"},';
		};
		$jons_html = substr($jons_html,0,-1);
		$jons_html .= "]";
		echo $jons_html;
		/*echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			$tishi = '';
			$other_info = explode("|",$data[$i]["other_info"]);
			$temp = '';
			foreach($other_info as $val)
			{
				$temp = explode(":",$val);
				if($temp['0'] == 'keshi' || $temp['0'] == 'mingcheng' || $temp['0'] == 'buwei')
				{
					$tishi .= $temp['1'].'|';
				}
			}
			$data[$i]["tishi"] = $tishi;
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "tishi":"'.$data[$i]["tishi"].'"},';
		}
		if(sizeof($data)>0)
		{
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "tishi":"'.$data[$i]["tishi"].'"}';
		}
		echo "]";*/
	}
	//
	public function getXiangmuMessage()
	{
		$value = $_GET['value'];
		$return_data = array();
		$date = M('data_xiangmu')->where("zhongwen_mingcheng = '$value' ")->find();
		$return_data['jiancha_table_name'] = $date['relate_table_name'];
		$return_data['beizhu'] = $date['other_info'];
		//获取科室
		$keshi_array = explode("|",$date['other_info']);
		foreach($keshi_array as $val)
		{
			$qiege = explode(":",$val);
			if($qiege[0] == "keshi")
			{
				$return_keshi = M('data_xiangmu')->field('id,pid')->where("zhongwen_mingcheng = '$qiege[1]' ")->find();
				$return_data['keshi'] = $return_keshi['id'];
				$keshi_select = M('data_xiangmu')->field('id,zhongwen_mingcheng')->where("pid = '$return_keshi[pid]' and other_info like '%|辅助检查科室%'  ")->select();
				$keshi_select_html = '';
				foreach($keshi_select as $val)
				{
					if($val['id'] == $return_keshi['id'])
					{
						$keshi_select_html .= '<option id="'.$val['id'].'" value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|"  selected="selected">'.$val['zhongwen_mingcheng'].'</option>';
					}
					else
					{
						$keshi_select_html .= '<option id="'.$val['id'].'" value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|">'.$val['zhongwen_mingcheng'].'</option>';
					}
				}
				$return_data['keshi_select_html'] = $keshi_select_html;
			}
			if(!empty($return_keshi))
			{
				if($qiege[0] == "mingcheng")
				{
					$return_mingcheng = M('data_xiangmu')->field('id,pid')->where("zhongwen_mingcheng = '$qiege[1]' and pid = '$return_keshi[id]' ")->find();
					$mingcheng_select = M('data_xiangmu')->field('id,zhongwen_mingcheng')->where("pid = '$return_mingcheng[pid]' ")->select();
					$mingcheng_select_html = '';
					foreach($mingcheng_select as $val)
					{
						if($val['id'] == $return_mingcheng['id'])
						{
							$mingcheng_select_html .= '<option id="'.$val['id'].'" value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|"  selected="selected">'.$val['zhongwen_mingcheng'].'</option>';
						}
						else
						{
							$mingcheng_select_html .= '<option id="'.$val['id'].'" value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|">'.$val['zhongwen_mingcheng'].'</option>';
						}
					}
					$return_data['mingcheng_select_html'] = $mingcheng_select_html;
					$return_data['mingcheng'] = $return_mingcheng['id'];
				}
			}
			if(!empty($return_mingcheng))
			{
				if($qiege[0] == "buwei")
				{
					$return_buwei = M('data_xiangmu')->field('id,pid')->where("id = '$date[pid]' ")->find();
					$buwei_select = M('data_xiangmu')->field('id,zhongwen_mingcheng')->where("pid = '$return_buwei[pid]' ")->select();
					$buwei_select_html = '';
					foreach($buwei_select as $val)
					{
						if($val['id'] == $return_buwei['id'])
						{
							$buwei_select_html .= '<option id="'.$val['id'].'" value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|"  selected="selected">'.$val['zhongwen_mingcheng'].'</option>';
						}
						else
						{
							$buwei_select_html .= '<option id="'.$val['id'].'" value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|">'.$val['zhongwen_mingcheng'].'</option>';
						}
					}
					$return_data['buwei_select_html'] = $buwei_select_html;
					$return_data['buwei'] = $return_buwei['id'];
				}
			}
		}
		//获取默认值	
		$morenzhi_all = explode("|morenzhi:",$date['other_info']);
		if(!empty($morenzhi_all['1']))
		{
			$morenzhi = explode("|",$morenzhi_all['1']);
			$return_data['morenzhi'] = $morenzhi['0'];
		}
		echo json_encode($return_data);		
	}
	/////
	public function getDoctorNameJson()
	{
		$user_department_id = $_GET['user_department_id'];
		$user_kebie_position = $_GET['user_kebie_position'];
		$request_keyword = $_GET['term'];
		if ($user_department_id==null || $user_department_id=="")
			$request_keyword = "0";
		$data = M("yiyuan_user")->where("user_department_id like '%$user_department_id%' and user_name like '%$request_keyword%' and user_kebie_position like '%$user_kebie_position%'" )->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["user_name"].'", "desc":"姓名:'.$data[$i]['user_name'].' |科别:'.$data[$i]['user_kebie'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["user_name"].'", "desc":"姓名:'.$data[$i]['user_name'].' |科别:'.$data[$i]['user_kebie'].'"}';
		echo "]";
	}
	
	public function getDoctorJson()
	{
		$user_department = $_GET['user_department'];
		$request_keyword = $_GET['term'];
		if ($user_department=="")
			$request_keyword = "0";
		$data = M("yiyuan_user")->where("user_department like '$user_department'" )->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["user_name"].'", "desc":"姓名:'.$data[$i]['user_name'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["user_name"].'", "desc":"姓名:'.$data[$i]['user_name'].'"}';       
		echo "]";
	}

//获得主诊医师证书编码
	public function getZhiyeBianmaJson()
	{
		$user_department_id = $_GET['user_department_id'];
		$user_kebie_position = $_GET['user_kebie_position'];
		$request_keyword = $_GET['term'];
		 
		if ($user_department_id==null || $user_department_id=="")
			$request_keyword = "0";
		$data = M("yiyuan_user")->where("user_department_id like '%$user_department_id%' and user_zhiye_bianma like '%$request_keyword%' and user_kebie_position like '%$user_kebie_position%'" )->select();
	 
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			//echo '{"label":"'.$data[$i]["user_zhiye_bianma"].'", "desc":"'.$data[$i]['user_name'].'"},';
      echo '{"label":"'.$data[$i]["user_zhiye_bianma"].'", "desc":"'.$data[$i]['user_name'].'"},';			
		}
		if(sizeof($data)>0)
			//echo '{"label":"'.$data[$i]["user_zhiye_bianma"].'", "desc":"姓名:'.$data[$i]['user_name'].'"}';
		  echo '{"label":"'.$data[$i]["user_zhiye_bianma"].'", "desc":"'.$data[$i]['user_name'].'"}';
		echo "]";
	}

	/////
	public function getDataJson()
	{
		$request_keyword = strtolower($_GET["term"]);
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		if(array_key_exists( 'filed',$_GET)&&array_key_exists( 'table',$_GET))
		{
			$table_name = $_GET["table"];
			$keyword_filed = $_GET["filed"];
			$data = M($table_name)->where("keyword_shuoming like '%$request_keyword%'")->select();
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				echo '{"label":"'.$data[$i][$keyword_filed].'", "mingcheng":"'.$data[$i]['zhongwen_mingcheng'].'", "bianma":"'.$data[$i]['code'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |标准编码:'.$data[$i]['code'].' |检索码:'.$data[$i]['pinyin_index'].'"},';
			}
			if(sizeof($data)>0)
				echo '{"label":"'.$data[$i][$keyword_filed].'", "mingcheng":"'.$data[$i]['zhongwen_mingcheng'].'", "bianma":"'.$data[$i]['code'].'",  "desc":"'.$data[$i]['zhongwen_mingcheng'].' |标准编码:'.$data[$i]['code'].' |检索码:'.$data[$i]['pinyin_index'].'"}';
		}
		echo "]";
	}
	
	public function getBingliType()
	{
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		//由于没有定好数据库，暂时只是用array
		$bingli_type = array(
			"日常病程记录",
			"主任医师查房记录",
			"副主任医师查房记录",
			"主治医师查房记录",
			"专家医师查房记录",
			"会诊记录",
			"疑难病例讨论记录",
			"死亡病例讨论记录",
			"交班病程记录",
			"接班病程记录",
			"转出记录",
			"转入记录",
			"阶段小结",
			"出院小结",
			"值班病程记录",
			"抢救记录",
			"放弃抢救记录",
			"有创诊疗操作记录",
			"胸腔穿刺记录",
			"腹腔穿刺记录",
			"术前小结",
			"术前讨论记录",
			"手术记录",
			"术后记录",
			"谈话记录"
		);
		$count = 0;
		foreach($bingli_type as $one)
		{
			if(strpos($one,$request_keyword)!==false||$request_keyword=="chucuodezifu")
			{
				$data[$count] = $one;
				$count++;
			}
		}
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i].'", "desc":"'.$data[$i].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i].'", "desc":"'.$data[$i].'"}';
		echo "]";
	}
	
	public function getZhenduan()
	{
		$request_keyword = strtolower($_GET["term"]);
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		$data_xiyi = M("data_icd10")->where("keyword_shuoming like '%$request_keyword%'")->select();
		$data_zhongyi = M("data_zhongyibingzheng")->where("keyword_shuoming like '%$request_keyword%'")->select();
		if(empty($data_xiyi))
		{
			$data = $data_zhongyi;
		}
		else if(empty($data_zhongyi))
		{
			$data = $data_xiyi;
		}
		else
		{
			$data = array_merge($data_xiyi,$data_zhongyi);
		}
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]['zhongwen_mingcheng'].'", "mingcheng":"'.$data[$i]['zhongwen_mingcheng'].'", "bianma":"'.$data[$i]['code'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |标准编码:'.$data[$i]['code'].' |检索码:'.$data[$i]['pinyin_index'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]['zhongwen_mingcheng'].'", "mingcheng":"'.$data[$i]['zhongwen_mingcheng'].'", "bianma":"'.$data[$i]['code'].'",  "desc":"'.$data[$i]['zhongwen_mingcheng'].' |标准编码:'.$data[$i]['code'].' |检索码:'.$data[$i]['pinyin_index'].'"}';
		echo "]";
	}
	
	public function adminDataJson()
	{
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_GET["type"];
		}
		$request_keyword = strtolower($_GET["term"]);
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		echo "[";
		if($type=="kebie")
		{
			$data = M("data_xiangmu")->where("pid like '32000' and keyword_shuoming like '%$request_keyword%'")->select();
		}
		else
		{
			$data = M("data_xiangmu")->where("pid like '1' and zhongwen_mingcheng<>'患者体征' and keyword_shuoming like '%$request_keyword%'")->select();
		}
		
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "mingcheng":"'.$data[$i]['zhongwen_mingcheng'].'", "bianma":"'.$data[$i]['code'].'", "id":"'.$data[$i]['id'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |标准编码:'.$data[$i]['code'].' |检索码:'.$data[$i]['pinyin_index'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "mingcheng":"'.$data[$i]['zhongwen_mingcheng'].'", "bianma":"'.$data[$i]['code'].'", "id":"'.$data[$i]['id'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |标准编码:'.$data[$i]['code'].' |检索码:'.$data[$i]['pinyin_index'].'"}';
		echo "]";
	}

	public function getDataTemplateGoback()
	{
		$template_type = $_GET["template_type"];
		$ShujuyuanString = A('Home/ShujuyuanString');
		$pid = strtolower($_GET["pid"]);
		$patient_id = strtolower($_GET["patient_id"]);
		if ($pid==null || $pid=="")
			$pid = "0";
		//获取用户ID
		
		$user_number = $_SESSION['user_number'];
		$table_name = "data_template";
		if($pid=="0")
		{
			$father_category = M($table_name)->where("id like '0'")->select();
		}
		else
		{
			$current_category = M($table_name)->where("id like '$pid' ")->select();
			$father_category = M($table_name)->where("id like '".$current_category[0]['pid']."'")->select();
		}
		echo '<div class="template_list_title template_category_name" id="'.$father_category[0]['id'].'">'.$father_category[0]['zhongwen_mingcheng'].'</div>';
		echo '<div class="template_list">';
		echo "<ul>";
		if($template_type=="common")
		{
			$data = M($table_name)->where("pid like '".$father_category[0]['id']."'")->select();
			for($i=0;$i<sizeof($data);$i++)
			{
				if($data[$i]["shuoming"]=="")
					$data[$i]["shuoming"] = "空模板";
				if($data[$i]["zhongwen_mingcheng"]=="")
					$data[$i]["zhongwen_mingcheng"] = "未命名";
				if($data[$i]["sub_direct_number"]>0)
				{
					$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
					echo "<li class='template_category' patient_id = '".$patient_id."' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."' bingli='".$data[$i]["other_info"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
				}
				elseif($data[$i]["user_number"] == $user_number or $data[$i]["temp_type"] == '公共模板')
				{
					$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
					echo "<li class='template_content_name' patient_id = '".$patient_id."' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
				}
			}
		}
		else if($template_type=="bingli")
		{
			echo "<li class='template_category' type='ruyuanjilu'>入院记录</li>";
			echo "<li class='template_category' type='shoucibingcheng'>首次病程记录</li>";
			echo "<li class='template_category' type='bingchengjilu'>病程记录</li>";
		}
		echo "</ul>";
		echo "</div>";
	}

	public function getDataTemplateGobackForChangyong()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$pid = strtolower($_GET["pid"]);
		$patient_id = strtolower($_GET["patient_id"]);
		if ($pid==null || $pid=="")
			$pid = "11";
		$table_name = "data_template";
		if($pid=="11")
		{
			$father_category = M($table_name)->where("id like '11'")->select();
		}
		else
		{
			$current_category = M($table_name)->where("id like '$pid'")->select();
			$father_category = M($table_name)->where("id like '".$current_category[0]['pid']."'")->select();
		}
		echo '<div class="template_list_title template_category_name" id="'.$father_category[0]['id'].'">'.$father_category[0]['zhongwen_mingcheng'].'</div>';
		echo '<div class="template_list">';
		echo "<ul>";
		$data = M($table_name)->where("pid like '".$father_category[0]['id']."'")->select();
		for($i=0;$i<sizeof($data);$i++)
		{
			if($data[$i]["shuoming"]=="")
				$data[$i]["shuoming"] = "空模板";
			if($data[$i]["zhongwen_mingcheng"]=="")
				$data[$i]["zhongwen_mingcheng"] = "未命名";
			if($data[$i]["sub_direct_number"]>0)
			{
				$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
				echo "<li class='template_category' patient_id = '".$patient_id."' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
			else
			{
				$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
				echo "<li class='template_content_name' patient_id = '".$patient_id."' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
		}
		echo "</ul>";
		echo "</div>";
	}

	/////
	public function getDataTemplate()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$pid = strtolower($_GET["pid"]);
		echo "<ul>";

		if($pid==null || $pid=="")
			$pid = "11";
		$table_name = "data_template";
		$user_number = $_SESSION['user_number'];
		$data = M($table_name)->where("pid like '$pid' ")->select();
		for($i=0;$i<sizeof($data);$i++)
		{
			if($data[$i]["shuoming"]=="")
				$data[$i]["shuoming"] = "空模板";
			if($data[$i]["zhongwen_mingcheng"]=="")
				$data[$i]["zhongwen_mingcheng"] = "未命名";
			if($data[$i]["sub_direct_number"]>0)
			{
				$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
				echo "<li class='template_category' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."' pid='".$data[$i]["pid"]."' template_type='".$data[$i]["temp_type"]."' bingli='".$data[$i]["other_info"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
			elseif($user_number == $data[$i]["doctor_id"] or '公共模板' == $data[$i]["temp_type"])
			{
				$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
				echo "<li class='template_content_name' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."' pid='".$data[$i]["pid"]."' template_type='".$data[$i]["temp_type"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
		}
		echo "</ul>";
	}

	//获取模板类型
	public function getDataTemplateType()
	{
		//获取用户ID
		$user_number = $_SESSION['user_number'];
		//判断模板类型
		$pid = intval($_GET["pid"]);
		$data = M('data_template')->field('temp_type')->where("id='{$pid}' ")->find();
		$data['temp_type'];
		//判断个人权限
		$geren = M('yiyuan_user')->field('user_kebie_position')->where("user_number='{$user_number}' ")->find();
		$zhiweizu = array('科主任','主任医师','副主任医师','主治医师');
		if(in_array($geren['user_kebie_position'],$zhiweizu))
		{
			echo $data['temp_type'].'-1';
		}
		else
		{
			echo $data['temp_type'].'-0';
		}
	}

	//为常用信息取模板
	public function getDataTemplateForChangyong()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$pid = strtolower($_GET["pid"]);
		$patient_id = strtolower($_GET["patient_id"]);
		echo "<ul>";
		if ($pid==null || $pid=="")
			$pid = "11";
		$table_name = "data_template";
		$user_number = $_SESSION['user_number'];
		$data = M($table_name)->where("pid like '$pid'")->select();
		for($i=0;$i<sizeof($data);$i++)
		{
			if($data[$i]["shuoming"]=="")
				$data[$i]["shuoming"] = "空模板";
			if($data[$i]["zhongwen_mingcheng"]=="")
				$data[$i]["zhongwen_mingcheng"] = "未命名";
			if($data[$i]["sub_direct_number"]>0)
			{
				$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
				echo "<li class='template_category' patient_id='".$patient_id."' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
			elseif($user_number == $data[$i]["user_number"] or '公共模板' == $data[$i]["temp_type"])
			{
				$temp_content = str_replace("\"","'",$ShujuyuanString->keywordSearch($data[$i]["shuoming"]));
				echo "<li class='template_content_name' patient_id='".$patient_id."' template_content=\" ".$temp_content."\" id='".$data[$i]["id"]."'>".$data[$i]["zhongwen_mingcheng"]."</li>";
			}
		}
		echo "</ul>";
	}
	
	public function getDataRecord()
	{
		echo "<ul>";
		if(array_key_exists( 'type',$_REQUEST))
		{
			$type=$_REQUEST['type'];
		}
		if ($type==null || $type=="")
			$type = "all";
		if(!empty($_GET["zhuyuan_id"]))
		{
			$zhuyuan_id = $_GET["zhuyuan_id"];
		}
		if($type=="all")
		{
			echo "<li class='template_category' type='ruyuanjilu'>入院记录</li>";
			echo "<li class='template_category' type='shoucibingcheng'>首次病程记录</li>";
			echo "<li class='template_category' type='bingchengjilu'>病程记录</li>";
		}
		if($type=="ruyuanjilu")
		{
			$zhuyuan_bingshi_index =  M("data_bingli")->where("pid like '2'")->select();
			$zhuyuan_bingshi = M("zhuyuan_bingshi")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			$zhuyuan_ruyuantigejiancha_index =  M("data_bingli")->where("pid like '10'")->select();
			$zhuyuan_ruyuantigejiancha = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			$count = 0;
			foreach($zhuyuan_bingshi_index as $zhuyuan_bingshi_index_one)
			{
				$index = $zhuyuan_bingshi_index_one["yingwen_mingcheng"];
				if($zhuyuan_bingshi[0][$index]!="")
				{
					$zhuyuan_bingshi_name[$count]["yingwen_mingcheng"] = $zhuyuan_bingshi_index_one["yingwen_mingcheng"];
					$zhuyuan_bingshi_name[$count]["zhongwen_mingcheng"] = $zhuyuan_bingshi_index_one["zhongwen_mingcheng"];
					$count++;
				}
			}
			for($i=0;$i<$count-1;$i++)
			{
				echo "<li class='template_content_name' type='zhuyuan_bingshi' index='".$zhuyuan_bingshi_name[$i]["yingwen_mingcheng"]."'>".$zhuyuan_bingshi_name[$i]["zhongwen_mingcheng"]."|".$zhuyuan_bingshi[0]["bingshicaiji_riqi_time"]."</li>";
			}
			if($count>0)
				echo "<li class='template_content_name' type='zhuyuan_bingshi' index='".$zhuyuan_bingshi_name[$count-1]["yingwen_mingcheng"]."'>".$zhuyuan_bingshi_name[$count-1]["zhongwen_mingcheng"]."|".$zhuyuan_bingshi[0]["bingshicaiji_riqi_time"]."</li>";
			
			$count = 0;
			foreach($zhuyuan_ruyuantigejiancha_index as $zhuyuan_ruyuantigejiancha_index_one)
			{
				$index = $zhuyuan_ruyuantigejiancha_index_one["yingwen_mingcheng"];
				if($zhuyuan_ruyuantigejiancha[0][$index]!="")
				{
					$zhuyuan_ruyuantigejiancha_name[$count]["yingwen_mingcheng"] = $zhuyuan_ruyuantigejiancha_index_one["yingwen_mingcheng"];
					$zhuyuan_ruyuantigejiancha_name[$count]["zhongwen_mingcheng"] = $zhuyuan_ruyuantigejiancha_index_one["zhongwen_mingcheng"];
					$count++;
				}
			}
			for($i=0;$i<$count-1;$i++)
			{
				echo "<li class='template_content_name' type='zhuyuan_ruyuantigejiancha' index='".$zhuyuan_ruyuantigejiancha_name[$i]["yingwen_mingcheng"]."'>".$zhuyuan_ruyuantigejiancha_name[$i]["zhongwen_mingcheng"]."|".$zhuyuan_bingshi[0]["bingshicaiji_riqi_time"]."</li>";
			}
			if($count>0)
				echo "<li class='template_content_name' type='zhuyuan_ruyuantigejiancha' index='".$zhuyuan_ruyuantigejiancha_name[$count-1]["yingwen_mingcheng"]."'>".$zhuyuan_ruyuantigejiancha_name[$i]["zhongwen_mingcheng"]."|".$zhuyuan_bingshi[0]["bingshicaiji_riqi_time"]."</li>";
		}
		if($type=="shoucibingcheng")
		{
			$zhuyuan_bingchengjilushouci_index = M("data_bingli")->where("pid like '24'")->select();
			$zhuyuan_bingchengjilushouci = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			$count = 0;
			foreach($zhuyuan_bingchengjilushouci_index as $zhuyuan_bingchengjilushouci_index_one)
			{
				$index = $zhuyuan_bingchengjilushouci_index_one["yingwen_mingcheng"];
				if($zhuyuan_bingchengjilushouci[0][$index]!="")
				{
					$zhuyuan_bingchengjilushouci_name[$count]["yingwen_mingcheng"] = $zhuyuan_bingchengjilushouci_index_one["yingwen_mingcheng"];
					$zhuyuan_bingchengjilushouci_name[$count]["zhongwen_mingcheng"] = $zhuyuan_bingchengjilushouci_index_one["zhongwen_mingcheng"];
					$count++;
				}
			}
			for($i=0;$i<$count-1;$i++)
			{
				echo "<li class='template_content_name' type='zhuyuan_bingchengjilushouci' index='".$zhuyuan_bingchengjilushouci_name[$i]["yingwen_mingcheng"]."'>".$zhuyuan_bingchengjilushouci_name[$i]["zhongwen_mingcheng"]."|".$zhuyuan_bingchengjilushouci[0]["record_time"]."</li>";
			}
			if($count>0)
				echo "<li class='template_content_name' type='zhuyuan_bingchengjilushouci' index='".$zhuyuan_bingchengjilushouci_name[$count-1]["yingwen_mingcheng"]."'>".$zhuyuan_bingchengjilushouci_name[$i]["zhongwen_mingcheng"]."|".$zhuyuan_bingchengjilushouci[0]["record_time"]."</li>";
		}
		if($type=="bingchengjilu")
		{
			$zhuyuan_bingchengjilu = M("zhuyuan_bingchengjilu")->where("zhuyuan_id like '$zhuyuan_id'")->select();
			for($i=0;$i<sizeof($zhuyuan_bingchengjilu)-1;$i++)
			{
				echo "<li class='template_content_name' type='zhuyuan_bingchengjilu' index='".$zhuyuan_bingchengjilu[$i]["id"]."'>".$zhuyuan_bingchengjilu[$i]["record_time"]."|".$zhuyuan_bingchengjilu[$i]["bingcheng_sub_leibie"]."|".$zhuyuan_bingchengjilu[$i]["chafang_doctor"]."</li>";
			}
			echo "<li class='template_content_name' type='zhuyuan_bingchengjilu' index='".$zhuyuan_bingchengjilu[sizeof($zhuyuan_bingchengjilu)-1]["id"]."'>".$zhuyuan_bingchengjilu[sizeof($zhuyuan_bingchengjilu)-1]["record_time"]."|".$zhuyuan_bingchengjilu[sizeof($zhuyuan_bingchengjilu)-1]["bingcheng_sub_leibie"]."|".$zhuyuan_bingchengjilu[sizeof($zhuyuan_bingchengjilu)-1]["chafang_doctor"]."</li>";
		}
		echo "</ul>";
	}
	
	
	public function getDataRecordContent()
	{
		if(!empty($_GET["table_name"]))
		{
			$table_name = $_GET["table_name"];
		}
		if(!empty($_GET["zhuyuan_id"]))
		{
			$zhuyuan_id = $_GET["zhuyuan_id"];
		}
		if(!empty($_GET["index"]))
		{
			$index = $_GET["index"];
		}
		if($table_name=="zhuyuan_bingchengjilu")
		{
			$data = M($table_name)->where("id like '$index'")->select();
			echo $data[0]["content"];
		}
		else
		{
			$data = M($table_name)->where("zhuyuan_id like '$zhuyuan_id'")->select();
			echo $data[0][$index];
		}
	}

	//获取患者基本信息
	public function getPatientInfo()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$table_name = strtolower($_GET["table_name"]);
		$shuxing = strtolower($_GET["shuxing"]);
		$data = M($table_name)->where("patient_id like '$patient_id'")->select();
		$data = $data[0][$shuxing];
		if($data!="")
		{
			echo $data;
		}
		else
		{
			$zhuyuan_id = $patient_id;
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->find();
			$data = M($table_name)->where("patient_id like '".$zhuyuan_basic_info["patient_id"]."'")->select();
			$data = $data[0][$shuxing];
			if($data!="")
			{
				echo $data;
			}
			else
			{
				echo "该患者暂无此信息！";
			}
		}
	}

	//取中草药处方
	public function getChufangForZcy()
	{
		if(C('session_state')== true )
		{
			
			if($_SESSION['login_state']!='true')
			{
				$this->assign('system_info','您当前处在未登录状态，请登录！');
				$this->display('System:showError');
				exit(0);
			}
		}
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$table_name = "zhuyuan_chufang";
		$chufangInfo=M($table_name)->where("zhuyuan_id like '$zhuyuan_id' and type like '中草药'")->select();

		echo "<ul>";
		if(count($chufangInfo)==0)
		{
			echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='"."没有处方信息，请返回"."' id='110201'>"."没有处方信息，请返回"."</li>";
		}
		else
		{
			for($i=0;$i<count($chufangInfo);$i++)
			{
				$chufang_id = $chufangInfo[$i]["id"];
	
				$chufang_detail = M("zhuyuan_chufang_detail")->where("chufang_id like '".$chufang_id."'")->select();
				$patient_chufang = "";
				for($j=0;$j<count($chufang_detail);$j++)
				{
					if(($j+1) % 4==0){
					$temp="       ".$chufang_detail[$j]["yaopin_mingcheng"]." ".$chufang_detail[$j]["ciliang"].$chufang_detail[$j]["shiyong_danwei"]."&nbsp&nbsp"."<br/>";
				  }else{
	
				  $temp="       ".$chufang_detail[$j]["yaopin_mingcheng"]." ".$chufang_detail[$j]["ciliang"].$chufang_detail[$j]["shiyong_danwei"]."&nbsp&nbsp";
				}
					$patient_chufang .= $temp;
				}
				$patient_chufang = "<p>"."<center>".$patient_chufang."<br/>".$chufangInfo[$i]["shuliang"]."剂"." ".$chufangInfo[$i]["pinlv"]." ".$chufangInfo[$i]["yongfa"]."</center>"."</p><br />";
				echo "<li class='template_content_name' patient_chufang='".$patient_chufang."' dachufanghao='".$chufangInfo[$i]["dachufanghao"]."' template_content='".$patient_chufang."' id='110201'>".$chufangInfo[$i]["kaili_yishi_name"]." ".$chufangInfo[$i]["kaili_time"]."</li>";
			}
		}
		echo "</ul>";
	}

	//取西药及中成药处方
	public function getChufangForXyandZcy()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$table_name = "zhuyuan_chufang";
		$chufangInfo=M($table_name)->where("zhuyuan_id like '$zhuyuan_id' and type like '西药及中成药'")->select();
		echo "<ul>";
		if(count($chufangInfo)==0)
		{
				echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='"."没有处方信息，请返回"."' id='110201'>"."没有处方信息，请返回"."</li>";
		}else
			{
				for($i=0;$i<count($chufangInfo);$i++)
				{

				$chufang_id = $chufangInfo[$i]["id"];

				$chufang_detail = M("zhuyuan_chufang_detail")->where("chufang_id like '".$chufang_id."'")->select();
				$patient_chufang = "";
				for($j=0;$j<count($chufang_detail);$j++)
				{
				  $temp="     ".$chufang_detail[$j]["yaopin_mingcheng"]."  ".$chufang_detail[$j]["ciliang"].$chufang_detail[$j]["shiyong_danwei"];
					$patient_chufang .= $temp;
				}
				$patient_chufang = $patient_chufang."<br/>";
				echo "<li class='template_content_name' patient_chufang='".$patient_chufang."' dachufanghao='".$chufangInfo[$i]["dachufanghao"]."' template_content='".$patient_chufang."' id='110201'>".$chufangInfo[$i]["kaili_yishi_name"]." ".$chufangInfo[$i]["kaili_time"]."</li>";
				}
			}
		echo "</ul>";
	}

	//组合处方
	public function getChufangForZh()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$table_name = "zhuyuan_chufang";
		$chufangInfo=M($table_name)->where("zhuyuan_id like '$zhuyuan_id' and type like '组合'")->select();

		echo "<ul>";
		if(count($chufangInfo)==0)
			{
				echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='"."没有处方信息，请返回"."' id='110201'>"."没有处方信息，请返回"."</li>";
			}else
				{
					for($i=0;$i<count($chufangInfo);$i++)
					{

					$chufang_id = $chufangInfo[$i]["id"];

					$chufang_detail = M("zhuyuan_chufang_detail")->where("chufang_id like '".$chufang_id."'")->select();

					$patient_chufang = "";

					for($j=0;$j<count($chufang_detail);$j++)
					{
					  $temp=$chufang_detail[$j]["zuhao"]." ".$chufang_detail[$j]["yaopin_mingcheng"]."  ".$chufang_detail[$j]["ciliang"].$chufang_detail[$j]["shiyong_danwei"]."<br/>";
						$patient_chufang .= $temp;
					}

					$patient_chufang = $patient_chufang."<br/>";

					echo "<li class='template_content_name' patient_chufang='".$patient_chufang."' dachufanghao='".$chufangInfo[$i]["dachufanghao"]."' template_content='".$patient_chufang."' id='110201'>".$chufangInfo[$i]["kaili_yishi_name"]." ".$chufangInfo[$i]["kaili_time"]."</li>";
				  }
				}


		echo "</ul>";
	}

	//给所有检查按时间分类
	public function getJianchaTime()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$current_mulu_id = strtolower($_GET["current_mulu_id"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$jiancha_info = M("zhuyuan_fuzhujiancha")->where("zhuyuan_id like '$zhuyuan_id'")->select();
		for($i=0;$i<count($jiancha_info);$i++)
		{
			$jiancha_timeinfo[$i] = $jiancha_info[$i]["songjian_time"];
			$jiancha_timeinfo[$i] = substr($jiancha_timeinfo[$i],0,10);
		}
		$jiancha_timeinfo = array_unique($jiancha_timeinfo);
		echo "<ul>";
		if($jiancha_timeinfo!=false)
		{
			for($i=0;$i<count($jiancha_timeinfo);$i++)
			{
				$jiancha_timeinfo_shijianchuo = explode("-",$jiancha_timeinfo[$i]);
				$jiancha_time = mktime(0,0,0,$jiancha_timeinfo_shijianchuo[1],$jiancha_timeinfo_shijianchuo[2],$jiancha_timeinfo_shijianchuo[0]);//入院的时间戳
				echo "<li class='template_category' patient_id='".$patient_id."' date='".date("Y/m/d",$jiancha_time)."' template_content='".$patient_chufang."' id='110301'>".date("Y/m/d",$jiancha_time)."</li>";
			}
		}
		else
		{
			echo "<li class='template_content_name' patient_id='".$patient_id."'>此项类别下还没有任何信息。</li>";
		}
		echo "</ul>";
	}

	//根据日期取异常的检查结果
	public function getJianchaByDate()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$xuanze_time = strtolower($_GET["xuanze_time"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$table_name = "zhuyuan_fuzhujiancha";
		$temp = explode("/",$xuanze_time);
		$xuanze_time_shijianchuo = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);//被选择时间的时间戳
		$jiancha_time = date("Y-m-d",$xuanze_time_shijianchuo);
		$jiancha_info = M($table_name)->where("zhuyuan_id like '$zhuyuan_id' and songjian_time like '%$jiancha_time%'")->select();
		echo "<ul>";
		for($i=0;$i<count($jiancha_info);$i++)
		{
			if(strpos("ceshi".$jiancha_info[$i]["jiancha_table_name"],"yingxiang") != false)
			{
				$jiancha_detail_info = split("|",$jiancha_info[$i]["beizhu"]);
				$jiancha_info[$i]["jiancha_mingcheng"] .= " ".$jiancha_info[$i]["beizhu"];
			}

			$songjian_info = split(" ",$jiancha_info[$i]["songjian_time"]);
			$songjian_riqi = $songjian_info[0];

			if($jiancha_info[$i]["jiancha_jieguo"]=="")
				$jiancha_info[$i]["jiancha_jieguo"] = "此项检查还没有得到检查结果。";
			echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='".$songjian_riqi." ".$jiancha_info[$i]["jiancha_mingcheng"]."检查结果：".$jiancha_info[$i][jiancha_jieguo]."' id='11030101'>".$jiancha_info[$i]["jiancha_mingcheng"]."</li>";
		}
		if(count($jiancha_info)==0)
		{
			echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='"."这一天没有进行过检查。"."' id='11030101'>"."这一天没有进行过检查。"."</li>";
		}
		echo "</ul>";
	}

	public function getTizhengByDate()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$xuanze_time = strtolower($_GET["xuanze_time"]);
		$table_name = "zhuyuan_tizheng";
		$temp = explode("/",$xuanze_time);
		$xuanze_time_shijianchuo = mktime(0,0,0,$temp[1],$temp[2],$temp[0]);//被选择时间的时间戳
		$jiancha_time = date("Y-m-d",$xuanze_time_shijianchuo);
		$jiancha_info = M($table_name)->where("patient_id like '$patient_id' and jiancha_time like '%$jiancha_time%'")->select();
		echo "<ul>";
		for($i=0;$i<count($jiancha_info);$i++)
		{
			$jiancha_jieguo=$jiancha_info[$i][jiancha_value].$jiancha_info[$i][jiancha_danwei];
			echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='".$jiancha_jieguo."' id='11050101'>".$jiancha_info[$i]["jiancha_doctor_name"]." ".$jiancha_info[$i]["jiancha_type"]."</li>";
		}
		if(count($jiancha_info)==0)
		{
			echo	"<li class='template_content_name' patient_id = '".$patient_id."'  template_content='"."这一天没有检查，请返回"."' id='11050101'>"."这一天没有检查，请返回"."</li>";
		}
		echo "</ul>";
	}

	//取血常规检查结果,作废，暂留
	public function getXuechanggui()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$table_name = "zhuyuan_fuzhujiancha";
		$jiancha_info = M($table_name)->where("zhuyuan_id like '$zhuyuan_id' and jiancha_mingcheng like '血常规' and jiancha_zhuangtai like '检查完毕'")->select();
		echo "<ul>";
		for($i=0;$i<count($jiancha_info);$i++)
		{
			$table_name = $jiancha_info[$i]["jiancha_table_name"];
			$jiancha_id = $jiancha_info[$i]["id"];
			$jiancha_info_detail = M($table_name)->where("jianyan_id like '$jiancha_id'")->select();
			$temp = "血常规： "."WBC".$jiancha_info_detail[$i]["___WBC"]."*10^9/L， "."NEU%".$jiancha_info_detail[$i]["NEU"]."%， CRP:".$jiancha_info_detail[$i]["CRP"]."mg/L";
			echo	"<li class='template_content_name'   template_content='".$temp."' id='110301'>".$jiancha_info[$i]["songjian_doctor_name"]." ".$jiancha_info[$i]["songjian_time"]."</li>";
		}
		echo "</ul>";
	}

	//取CT检查结果
	public function getCT()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		$table_name = "zhuyuan_fuzhujiancha";
		$jiancha_info = M($table_name)->where("zhuyuan_id like '$zhuyuan_id' and jiancha_mingcheng like 'CT' and jiancha_zhuangtai like '检查完毕'")->select();
		echo "<ul>";
		for($i=0;$i<count($jiancha_info);$i++)
		{
			$table_name = $jiancha_info[$i]["jiancha_table_name"];
			$jiancha_id = $jiancha_info[$i]["id"];
			$jiancha_info_detail = M($table_name)->where("jianyan_id like '$jiancha_id'")->select();
			$temp = $jiancha_info_detail[$i]["jiancha_buwei"]."CT： ".$jiancha_info_detail[$i]["yingxiang_miaoshu"];
			echo	"<li class='template_content_name'   template_content='".$temp."' id='110302'>".$jiancha_info[$i]["songjian_doctor_name"]." ".$jiancha_info[$i]["songjian_time"]."</li>";
		}
		if(count($jiancha_info)==0)
		{
			echo	"<li class='template_content_name'   template_content='暂无CT检查结果' id='110302'>"."暂无CT检查结果"."</li>";
		}
		echo "</ul>";
	}

	public function getTizheng()
	{
		echo '0.25|0.5|0.385|0.4|0.5|0.3|0.2|0.24|0.35|0.6|0.5';
	}

	//取患者的长期医嘱信息和临时医嘱信息
	public function getYizhu()
	{
		$ShujuyuanString = A("Home/ShujuyuanString");
		$patient_id = strtolower($_GET["patient_id"]);
		$isChangqi = strtolower($_GET["shuxing"]);
		echo "<ul>";
		$table_name = "zhuyuan_basic_info";
		$zhuyuanInfo = M($table_name)->where("patient_id like '$patient_id'")->select();
		$zhuyuan_id=$zhuyuanInfo[0]["zhuyuan_id"];
		//取患者的长期医嘱信息
		if($isChangqi=='changqi')
		{
			$table_name = "zhuyuan_yizhu_changqi";
			$changqi_yizhu_temp = M($table_name)->where("zhuyuan_id like '$zhuyuan_id'")->order("start_time asc")->select();
			if(count($changqi_yizhu_temp)>=1)
			{
				for($count=0;$count<count($changqi_yizhu_temp);$count++)
				{
					$changqi_yizhu[$count] = $changqi_yizhu_temp[$count];
					if($changqi_yizhu_temp[$count]['zuhao']!=$changqi_yizhu_temp[$count+1]['zuhao'])
					{
						$changqi_yizhu[$count]['islast'] = 'true';
						if($changqi_yizhu[$count-1]['islast']=='false')
							$changqi_yizhu[$count]['content'] = '┗'.$changqi_yizhu[$count]['content'];
					}
					else
					{
						$changqi_yizhu[$count]['islast'] = 'false';
						if($count == 0 || $changqi_yizhu[$count-1]['islast'] == 'true')
							$changqi_yizhu[$count]['content'] = '┏'.$changqi_yizhu[$count]['content'];
						else
							$changqi_yizhu[$count]['content'] = '┃'.$changqi_yizhu[$count]['content'];
					}
				}
			}
			for($i=0;$i<sizeof($changqi_yizhu);$i++)
			{
				if($changqi_yizhu[$i]["stop_time"]=="")
				$changqi_yizhu[$i]["stop_time"] = "还未停止";
				$patient_changqiyizhu .= $changqi_yizhu[$i]["content"]." "." ".$changqi_yizhu[$i]["ciliang"]."* "." ".$changqi_yizhu[$i]["shiyong_danwei"]." ".$changqi_yizhu[$i]["pinlv"]." ".$changqi_yizhu[$i]["yongfa"]." "."开始时间：".$changqi_yizhu[$i]["start_time"]." "."停止时间：".$changqi_yizhu[$i]["stop_time"]."<br/><br/>";
			}
			if($patient_changqiyizhu!="")
			{
				$patient_changqiyizhu ="<b>长期医嘱：</b><br/>".$patient_changqiyizhu;
			}
			else
			{
				$patient_changqiyizhu ="<b>长期医嘱：此患者没有暂无长期医嘱</b><br/>";
			}
			echo $patient_changqiyizhu;
		}
		//取患者的临时医嘱信息
		if($isChangqi=='linshi')
		{
		$table_name = "zhuyuan_yizhu_linshi";
		$linshi_yizhu_temp = M($table_name)->where("zhuyuan_id like '$zhuyuan_id'")->order("xiada_time asc")->select();
		if(count($linshi_yizhu_temp)>=1)
		{
			for($count=0;$count<count($linshi_yizhu_temp);$count++)
			{
				$linshi_yizhu[$count] = $linshi_yizhu_temp[$count];
				if($linshi_yizhu[$count]['zuhao'] > 0) {
					if($linshi_yizhu_temp[$count]['zuhao']!=$linshi_yizhu_temp[$count+1]['zuhao']){
						$linshi_yizhu[$count]['islast'] = 'true';
						if($linshi_yizhu[$count-1]['islast']=='false')
							$linshi_yizhu[$count]['content'] = '┗'.$linshi_yizhu[$count]['content'];
					}else {
						$linshi_yizhu[$count]['islast'] = 'false';
							if($count == 0 || $linshi_yizhu[$count-1]['islast'] == 'true')
								$linshi_yizhu[$count]['content'] = '┏'.$linshi_yizhu[$count]['content'];
							else
								$linshi_yizhu[$count]['content'] = '┃'.$linshi_yizhu[$count]['content'];

					}
				}else {
					$linshi_yizhu[$count]['islast'] = 'true';
				}
			}
		}
		for($i=0;$i<sizeof($linshi_yizhu);$i++)
		{
			if($linshi_yizhu[$i]["zhixing_time"]=="")
			$linshi_yizhu[$i]["zhixing_time"] = "还未执行";
			$patient_linshiyizhu .= $linshi_yizhu[$i]["content"]." "." ".$linshi_yizhu[$i]["ciliang"]."* "." ".$linshi_yizhu[$i]["shiyong_danwei"]." ".$linshi_yizhu[$i]["yongfa"]." "."下达时间：".$linshi_yizhu[$i]["xiada_time"]." "."执行时间：".$linshi_yizhu[$i]["zhixing_time"]."<br/><br/>";
		}
		if($patient_linshiyizhu!="")
		{
			$patient_linshiyizhu ="<b>临时医嘱：</b><br/>".$patient_linshiyizhu;
		}
		else
		{
			$patient_linshiyizhu ="<b>临时医嘱：此患者没有暂无长期医嘱</b><br/>";
		}
		echo $patient_linshiyizhu;
	 }
		echo "</ul>";
	}

	public function getDataPatientID()
	{
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_REQUEST["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
			$data = M("patient_basic_info")->where("patient_id like '%$request_keyword%'")->select();
			$data_dianhua=M("patient_contact_info")->where("patient_id like '%$request_keyword%'")->select();
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				$latest_tijian_id[$i]=date('Ymd').substr($data[$i]["patient_id"],-4);
				$new_zhuyuan_id_temp = explode("-",$data[$i]["latest_zhuyuan_id"]);
				$zhuyuan_cishu = $new_zhuyuan_id_temp[1] + 1;
				echo '{"label":"'.$data[$i]["patient_id"].'", "new_zhuyuan_id":"'.$new_zhuyuan_id_temp[0].'", "zhuyuan_cishu":"'.$zhuyuan_cishu.'","latest_tijian_id":"'.$latest_tijian_id[$i].'", "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'","lianxi_dianhua":"'.$data_dianhua[$i]["lianxi_dianhua"].'", "zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"},';
			}
			if(sizeof($data)>0)
			{
				$latest_tijian_id[$i]=date('Ymd').substr($data[$i]["patient_id"],-4);
				$new_zhuyuan_id_temp = explode("-",$data[$i]["latest_zhuyuan_id"]);
				$zhuyuan_cishu = $new_zhuyuan_id_temp[1] + 1;
				echo '{"label":"'.$data[$i]["patient_id"].'", "new_zhuyuan_id":"'.$new_zhuyuan_id_temp[0].'", "zhuyuan_cishu":"'.$zhuyuan_cishu.'","latest_tijian_id":"'.$latest_tijian_id[$i].'",  "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'","lianxi_dianhua":"'.$data_dianhua[$i]["lianxi_dianhua"].'","zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"}';
			}
		//echo '{ "id": "Hirundo rustica", "label": "Barn Swallow", "value": "Barn Swallow" }, { "id": "Cecropis daurica", "label": "Red-rumped Swallow", "value": "Red-rumped Swallow" }';
		echo "]";
	}
	
	public function getArtPatientInfo()
	{
		echo "[";
		$type = $_GET['type'];
		if($type=="")
		{
			$type = "男";
		}
		$request_keyword = $_GET['term'];
		if($type=="男")
		{
			$data = M("patient_basic_info")->where("concat(xingming,patient_id) like '%".$request_keyword."%' and zhouqi_history<>'' and xingbie='".$type."'")->select();
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				echo '{"label":"'.$data[$i]["xingming"].'", "shengri":"'.$data[$i]["shengri"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |出生日期:'.$data[$i]['shengri'].'|身份证:'.$data[$i]['patient_id'].'"},';
			}
			if(sizeof($data)>0)
			{
				echo '{"label":"'.$data[$i]["xingming"].'", "shengri":"'.$data[$i]["shengri"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |出生日期:'.$data[$i]['shengri'].'|身份证:'.$data[$i]['patient_id'].'"}';
			}
		}
		else if($type=="女")
		{
			$data = M("patient_basic_info")->where("concat(xingming,patient_id) like '%".$request_keyword."%' and zhouqi_history<>'' and xingbie='".$type."'")->select();
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				$latest_zhouqi_id = $data[$i]["latest_zhouqi_id"];
				$male_patient = M("patient_basic_info")->where("latest_zhouqi_id='".$latest_zhouqi_id."' and xingbie='男'")->select();
				echo '{"label":"'.$data[$i]["xingming"].'", "latest_zhouqi_id":"'.$data[$i]["latest_zhouqi_id"].'", "shengri":"'.$data[$i]["shengri"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'", "xingming_male":"'.$male_patient[0]["xingming"].'", "patient_id_male":"'.$male_patient[0]["patient_id"].'", "shengri_male":"'.$male_patient[0]["shengri"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |出生日期:'.$data[$i]['shengri'].'|身份证:'.$data[$i]['patient_id'].'"},';
			}
			if(sizeof($data)>0)
			{
				$latest_zhouqi_id = $data[$i]["latest_zhouqi_id"];
				$male_patient = M("patient_basic_info")->where("latest_zhouqi_id='".$latest_zhouqi_id."' and xingbie='男'")->select();
				echo '{"label":"'.$data[$i]["xingming"].'", "latest_zhouqi_id":"'.$data[$i]["latest_zhouqi_id"].'", "shengri":"'.$data[$i]["shengri"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'", "xingming_male":"'.$male_patient[0]["xingming"].'", "patient_id_male":"'.$male_patient[0]["patient_id"].'", "shengri_male":"'.$male_patient[0]["shengri"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |出生日期:'.$data[$i]['shengri'].'|身份证:'.$data[$i]['patient_id'].'"}';
			}
		}
		echo "]";
	}
	
	public function getDataPatientInfo()
	{
		echo "[";
		$zhixing_type = $_GET['zhixing_type'];
		$request_keyword = $_GET['term'];
		if ($zhixing_type=="")
			$request_keyword = "bucunzai";
		if($zhixing_type=="住院")
		{
			$data_id = M("zhuyuan_basic_info")->where("zhuyuan_id like '%$request_keyword%'")->select();
			for($id_count=0;$id_count<count($data_id);$id_count++)
			{
				$data_temp = M("patient_basic_info")->where("patient_id like '".$data_id[$id_count]['patient_id']."'")->select();
				$data[$id_count] = $data_temp[0];
			}
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				echo '{"label":"'.$data[$i]["latest_zhuyuan_id"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'", "zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"},';
			}
			if(sizeof($data)>0)
			{
				echo '{"label":"'.$data[$i]["latest_zhuyuan_id"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'", "zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"}';
			}
			echo "]";
		}
		if($zhixing_type=="门诊")
		{
			$data_id = M("menzhen_basic_info")->where("menzhen_id like '%$request_keyword%'")->select();
			for($id_count=0;$id_count<count($data_id);$id_count++)
			{
				$data_temp = M("patient_basic_info")->where("patient_id like '".$data_id[$id_count]['patient_id']."'")->select();
				$data[$id_count] = $data_temp[0];
			}
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				echo '{"label":"'.$data[$i]["latest_menzhen_id"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'", "zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"},';
			}
			if(sizeof($data)>0)
			{
				echo '{"label":"'.$data[$i]["latest_menzhen_id"].'", "patient_id":"'.$data[$i]["patient_id"].'", "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'", "zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"}';
			}
			echo "]";
		}
		
	}

	/////
	public function getDataZhuyuanID()
	{
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_REQUEST["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		$data = M("zhuyuan_basic_info")->where("zhuyuan_id like '%$request_keyword%'")->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$data[$i]['patient_id']."'")->select();
			$new_zhuyuan_id_temp = explode("-",$patient_basic_info[0]["latest_zhuyuan_id"]);
			echo '{"label":"'.$new_zhuyuan_id_temp[0].'", "patient_id":"'.$patient_basic_info[0]["patient_id"].'", "xingming":"'.$patient_basic_info[0]["xingming"].'","xingbie":"'.$data[$i]["xingbie"].'","zhuangtai":"'.$patient_basic_info[0]["zhuangtai"].'", "desc":"已有住院号：'.$data[$i]['zhuyuan_id'].' |入院日期:'.$data[$i]['ruyuan_riqi_time'].' |状态:'.$data[$i]['zhuangtai'].'"},';
		}
		if(sizeof($data)>0)
		{
			$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$data[$i]['patient_id']."'")->select();
			$new_zhuyuan_id_temp = explode("-",$patient_basic_info[0]["latest_zhuyuan_id"]);
			echo '{"label":"'.$new_zhuyuan_id_temp[0].'", "patient_id":"'.$patient_basic_info[0]["patient_id"].'", "xingming":"'.$patient_basic_info[0]["xingming"].'","xingbie":"'.$data[$i]["xingbie"].'","zhuangtai":"'.$patient_basic_info[0]["zhuangtai"].'", "desc":"已有住院号：'.$data[$i]['zhuyuan_id'].' |入院日期:'.$data[$i]['ruyuan_riqi_time'].' |状态:'.$data[$i]['zhuangtai'].'"}';
		}
		//echo '{ "id": "Hirundo rustica", "label": "Barn Swallow", "value": "Barn Swallow" }, { "id": "Cecropis daurica", "label": "Red-rumped Swallow", "value": "Red-rumped Swallow" }';
		echo "]";
	}

	/////
	public function getDataComplateZhuyuanID()
	{
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_REQUEST["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		$data = M("zhuyuan_basic_info")->where("zhuyuan_id like '%$request_keyword%'")->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$data[$i]['patient_id']."'")->select();
			echo '{"label":"'.$data[$i]['zhuyuan_id'].'", "patient_id":"'.$patient_basic_info[0]["patient_id"].'", "xingming":"'.$patient_basic_info[0]["xingming"].'","xingbie":"'.$data[$i]["xingbie"].'","zhuangtai":"'.$patient_basic_info[0]["zhuangtai"].'", "desc":"已有住院号：'.$data[$i]['zhuyuan_id'].' |入院日期:'.$data[$i]['ruyuan_riqi_time'].' |状态:'.$data[$i]['zhuangtai'].'"},';
		}
		if(sizeof($data)>0)
		{
			$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$data[$i]['patient_id']."'")->select();
			echo '{"label":"'.$data[$i]['zhuyuan_id'].'", "patient_id":"'.$patient_basic_info[0]["patient_id"].'", "xingming":"'.$patient_basic_info[0]["xingming"].'","xingbie":"'.$data[$i]["xingbie"].'","zhuangtai":"'.$patient_basic_info[0]["zhuangtai"].'", "desc":"已有住院号：'.$data[$i]['zhuyuan_id'].' |入院日期:'.$data[$i]['ruyuan_riqi_time'].' |状态:'.$data[$i]['zhuangtai'].'"}';
		}
		//echo '{ "id": "Hirundo rustica", "label": "Barn Swallow", "value": "Barn Swallow" }, { "id": "Cecropis daurica", "label": "Red-rumped Swallow", "value": "Red-rumped Swallow" }';
		echo "]";
	}

	/////
	public function getDataTijianID()
	{
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_REQUEST["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "2011";
		else
			$request_keyword = "%".$request_keyword."%";
		$data = M("tijian_zongjian")->where("tijian_id like '%$request_keyword%'")->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["tijian_id"].'", "desc":"已有体检号：'.$data[$i]['tijian_id'].' |体检日期:'.$data[$i]['examine_time'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["tijian_id"].'", "desc":"已有体检号：'.$data[$i]['tijian_id'].' |体检日期:'.$data[$i]['examine_time'].'"}';
	//echo '{ "id": "Hirundo rustica", "label": "Barn Swallow", "value": "Barn Swallow" }, { "id": "Cecropis daurica", "label": "Red-rumped Swallow", "value": "Red-rumped Swallow" }';
		echo "]";
	}
	//jquery查询所有住院用户
	public function getZhuYuanId()
	{
		if(C('session_state')== true )
		{
			
			if($_SESSION['login_state']!='true')
			{
				$this->assign('system_info','您当前处在未登录状态，请登录！');
				$this->display('System:showError');
				exit(0);
			}
		}
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_REQUEST["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		$data = M("zhuyuan_basic_info")->where("`zhuangtai`='住院中' AND zhuyuan_id like '%$request_keyword%'")->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"desc":"'.$data[$i]['zhuyuan_id'].'"},';
		}
		if(sizeof($data)>0)
		{
			echo '{"desc":"'.$data[$i]['zhuyuan_id'].'"}';
		}
		echo "]";
	}
	////
	public function getDataYishiListJson()
	{
		echo "[";
		$request_keyword = strtolower($_GET["term"]);
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		$request_keyword = "%".$request_keyword."%";
		if(array_key_exists('user_department',$_REQUEST))
		{
			$user_department=$_REQUEST["user_department"];
		}
		if ($user_department==null || $user_department=="")
			$user_department = "%";
			
		if(array_key_exists('user_type',$_REQUEST))
		{
			$user_type=$_REQUEST["user_type"];
		}
		if ($user_type==null || $user_type=="")
			$user_type = "%";

		if(array_key_exists('user_department_position',$_REQUEST))
		{
			$user_department_position=$_REQUEST["user_department_position"];
		}
		if ($user_department_position==null || $user_department_position=="")
			$user_department_position = "%";
			
		if(array_key_exists('user_kebie_position',$_REQUEST))
		{
			$user_kebie_position=$_REQUEST["user_kebie_position"];
		}
		if ($user_kebie_position==null || $user_kebie_position=="")
			$user_kebie_position = "%";

		if(array_key_exists('user_skill',$_REQUEST))
		{
			$user_skill=$_REQUEST["user_skill"];
		}
		if ($user_skill==null || $user_skill=="")
			$user_skill = "%";


		$data = M("yiyuan_user")->where("user_type like '%$user_type%' and user_kebie_position like '%$user_kebie_position%' and user_department like '%$user_department%' and user_department_position like '%$user_department_position%' and user_skill like '%$user_skill%' and concat(user_number,user_name) like '$request_keyword'")->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["user_name"].'", "desc":"'.$data[$i]['user_name'].'|'.$data[$i]['user_number'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["user_name"].'", "desc":"'.$data[$i]['user_name'].'|'.$data[$i]['user_number'].'"}';
		//echo '{ "id": "Hirundo rustica", "label": "Barn Swallow", "value": "Barn Swallow" }, { "id": "Cecropis daurica", "label": "Red-rumped Swallow", "value": "Red-rumped Swallow" }';
		echo "]";
	}

	/////
	public function getDataYizhuOneJson()
	{
		$request_keyword = strtolower($_GET["term"]);
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		$table_name = "data_yizhu";

		//是否可被筛选
		$selectable = " and other_info NOT like '%select_disable%'";
		$data = M($table_name)->where("keyword_shuoming like '$request_keyword' and type like 'oneyizhu'".$selectable)->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"id":"'.$data[$i]['id'].'","label":"'.$data[$i]['zhongwen_mingcheng'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |说明:'.$data[$i]['shuoming'].'","yongfa_type":"'.$data[$i]['yongfa_type'].'","shiyong_danwei":"'.$data[$i]['shiyong_danwei'].'","zhixing_keshi":"'.$data[$i]['zhixing_keshi'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"id":"'.$data[$i]['id'].'","label":"'.$data[$i]['zhongwen_mingcheng'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |说明:'.$data[$i]['shuoming'].'","yongfa_type":"'.$data[$i]['yongfa_type'].'","shiyong_danwei":"'.$data[$i]['shiyong_danwei'].'","zhixing_keshi":"'.$data[$i]['zhixing_keshi'].'"}';
		echo "]";
	}

	/////
	public function getDataYizhuMultiJson()
	{
		$request_keyword = strtolower($_GET["term"]);
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
		$table_name = "data_yizhu";

		//是否可被筛选
		$selectable = " and other_info NOT like '%select_disable%'";
		$data = M($table_name)->where("keyword_shuoming like '%$request_keyword%' and type like 'multiyizhu'".$selectable)->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"id":"'.$data[$i]['id'].'","label":"'.$data[$i]['zhongwen_mingcheng'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |说明:'.$data[$i]['shuoming'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"id":"'.$data[$i]['id'].'","label":"'.$data[$i]['zhongwen_mingcheng'].'", "desc":"'.$data[$i]['zhongwen_mingcheng'].' |说明:'.$data[$i]['shuoming'].'"}';
		echo "]";
	}

	/////
	public function getDataYaopinJson()
	{
		$request_keyword = strtolower($_GET["term"]);
		$pid = strtolower($_GET["pid"]);
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_GET["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";

		if ($pid==null || $pid=="")
			$pid = "0";
		else
			$pid = $pid;
		$table_name = "data_yaopin";
		$data = M($table_name)->where("pid = '$pid' and keyword_shuoming like '$request_keyword' and type != 'category' ")->select();
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"id":"'.$data[$i]['id'].'","yaojileixing":"'.$data[$i]['yaojileixing'].'","lingshou_danwei":"'.$data[$i]['lingshou_danwei'].'","measure_unit":"'.$data[$i]['measure_unit'].'","jiliang":"'.$data[$i]['jiliang'].'","shiyong_danwei":"'.$data[$i]['shiyong_danwei'].'","danjia":"'.$data[$i]['danjia'].'","kucun":"'.$data[$i]['kucun'].'","zhixingkeshi":"'.$data[$i]['zhixingkeshi'].'","fufeifangshi":"'.$data[$i]['fufeifangshi'].'","guige":"'.$data[$i]['guige'].'","guige_number":"'.$data[$i]['guige_number'].'","type":"'.$data[$i]['type'].'","label":"'.$data[$i]['zhongwen_mingcheng'].'", "desc":"'.'规格:'.$data[$i]['guige'].'|付费方式:'.$data[$i]['fufeifangshi'].'<br />单价:'.$data[$i]['danjia'].'元|库存:'.$data[$i]['kucun'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"id":"'.$data[$i]['id'].'","yaojileixing":"'.$data[$i]['yaojileixing'].'","lingshou_danwei":"'.$data[$i]['lingshou_danwei'].'","measure_unit":"'.$data[$i]['measure_unit'].'","jiliang":"'.$data[$i]['jiliang'].'","shiyong_danwei":"'.$data[$i]['shiyong_danwei'].'","danjia":"'.$data[$i]['danjia'].'","kucun":"'.$data[$i]['kucun'].'","zhixingkeshi":"'.$data[$i]['zhixingkeshi'].'","fufeifangshi":"'.$data[$i]['fufeifangshi'].'","guige":"'.$data[$i]['guige'].'","guige_number":"'.$data[$i]['guige_number'].'","type":"'.$data[$i]['type'].'","label":"'.$data[$i]['zhongwen_mingcheng'].'", "desc":"'.'规格:'.$data[$i]['guige'].'|付费方式:'.$data[$i]['fufeifangshi'].'<br />单价:'.$data[$i]['danjia'].'元|库存:'.$data[$i]['kucun'].'"}';
		echo "]";
	}

	/////
	public function getXiangmuInfoByYaopinNameJson()
	{
		$yaopin_mingcheng = strtolower($_GET["yaopin_mingcheng"]);

		if ($yaopin_mingcheng==null || $yaopin_mingcheng=="")
			$yaopin_mingcheng = "0";

		if(!empty($_GET['table_name']))
			$table_name = $_GET['table_name'];
		else
			$table_name = "data_yaopin";
		$data = M($table_name)->where("zhongwen_mingcheng like '$yaopin_mingcheng' and type != 'category' ")->select();

		$zhusheleiString = '注射液|注射剂|吸入剂|吸入粉剂喷雾剂|喷鼻剂|吸入粉剂|小针|气雾剂|溶液剂（吸入）|粉剂|粉针|粉针剂';

		$pid = '11000';
		if(strpos($zhusheleiString,$data[0]['yaojileixing']))
			$pid = '13000';

		$request_keyword = $_GET['term'];

		$data = M("data_xiangmu")->where("pid like '$pid' and keyword_shuoming like '%$request_keyword%'")->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["other_info"].'", "desc":"编码:'.$data[$i]['pinyin_index'].' |'.$data[$i]['code'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["other_info"].'", "desc":"编码:'.$data[$i]['pinyin_index'].' |'.$data[$i]['code'].'"}';
		echo "]";
	}

	/////
	public function showTree()
	{
		$this->assign('table_name',$_GET['table_name']);
		$_GET["server"] = str_replace("@","/",$_GET["server"]) ;
		$this->assign('server',$_GET['server']);
		$this->display();
	}

	/////
	public function getTestDataJson()
	{
		$request_keyword = strtolower($_GET["term"]);
		echo "[";
		echo "{'message':'数据更新成功！'}";
		echo "]";
	}

	public function getZhuyuanYishiInfo()
	{
		$zhuyuan_id = $_GET["term"];
		$data = M("zhuyuan_zongjie_info")->where("zhuyuan_id like '$zhuyuan_id'")->select();
		if(sizeof($data)>0)
			echo '{"kezhuren_id":"'.$data[0]['kezhuren_id'].'","kezhuren_name":"'.$data[0]['kezhuren_name'].'","zhurenyishi_id":"'.$data[0]['zhurenyishi_id'].'","zhurenyishi_name":"'.$data[0]['zhurenyishi_name'].'","zhuzhiyishi_id":"'.$data[0]['zhuzhiyishi_id'].'","zhuzhiyishi_name":"'.$data[0]['zhuzhiyishi_name'].'","zhuyuanyishi_id":"'.$data[0]['zhuyuanyishi_id'].'","zhuyuanyishi_name":"'.$data[0]['zhuyuanyishi_name'].'"}';
		else
			echo '{"system_info":"医师信息获取失败！"}';
	}

	public function getJiaobanInfo()
	{
		$user_department_position = $_SESSION["user_department_position"];
		$user_department = $_SESSION['user_department'];
		$user_type = $_SESSION['user_type'];
		$data = M("yiyuan_user")->where("user_type like '$user_type' and user_department like '$user_department'")->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"user_number":"'.$data[$i]['user_number'].'","user_name":"'.$data[$i]['user_name'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"user_number":"'.$data[$i]['user_number'].'","user_name":"'.$data[$i]['user_name'].'"}';
		echo "]";
	}

	/*
			"document_id":document_id,
			"document_type":document_type,
			"document_relate_table":document_relate_table,
			"part_print_top":part_print_top,
			"part_print_left":part_print_left,
			"part_print_width":part_print_width,
			"part_print_height":part_print_height
	*/
	public function setDayinJilu()
	{
		if($_GET["print_type"]=="normal")
		{
			$_GET["document_relate_table"] = $_GET["document_relate_table"];
		}
		else if($_GET["print_type"]=="memory")
		{
			$_GET["document_relate_table"] = $_GET["document_relate_table"]."|memory";
		}
		if(empty($_GET["document_id"])!=true&&empty($_GET["document_relate_table"])!=true)
		{
			$dayinjilu_table = M("dayin_jilu")->where("document_id like '".$_GET["document_id"]."' and document_relate_table like '".$_GET["document_relate_table"]."'")->select();
			if($dayinjilu_table==false)
			{
				$dayinjilu_table_added_result = M("dayin_jilu")->add($_GET);
			}
			else
			{
				$_GET["id"] = $dayinjilu_table[0]["id"];
				$dayinjilu_table_result = M("dayin_jilu")->save($_GET);
			}
		}

		echo $dayinjilu_table_result;
	}

	public function setDayinState()
	{
		if(empty($_GET["document_id"])!=true&&empty($_GET["document_relate_table"])!=true)
		{
			$dayinjilu_table = M("dayin_jilu")->where("document_id like '".$_GET["document_id"]."' and document_relate_table like '".$_GET["document_relate_table"]."'")->select();
			if($dayinjilu_table==false)
			{
				$dayinjilu_table_added_result = M("dayin_jilu")->add($_GET);
			}
			else
			{
				$_GET["id"] = $dayinjilu_table[0]["id"];
				$dayinjilu_table_result = M("dayin_jilu")->save($_GET);
			}
			//更新打印快捷查询字段：
			$_GET["id"] = $_GET["document_id"];
			$dayinjilu_table_result = M($_GET["document_relate_table"])->save($_GET);
		}

		echo $dayinjilu_table_result;
	}

	/////
	public function getDayinJilu()
	{
		if($_GET["print_type"]=="memory")
		{
			if(empty($_GET["document_id"])!=true&&empty($_GET["document_relate_table"])!=true)
			{
				$dayinjilu_table = M("dayin_jilu")->where("document_id like '".$_GET["document_id"]."' and document_relate_table like '".$_GET["document_relate_table"]."|memory'")->select();
				if($dayinjilu_table!==false)
				{
					echo "[";
					echo '{"part_print_top":"'.$dayinjilu_table[0]["part_print_top"].'","part_print_left":"'.$dayinjilu_table[0]["part_print_left"].'","part_print_width":"'.$dayinjilu_table[0]["part_print_width"].'","part_print_height":"'.$dayinjilu_table[0]["part_print_height"].'"}';
					echo "]";
				}
				else
				{
					echo "[";
					echo '{"part_print_top":"0"}';
					echo "]";
				}
			}
			else
			{
				echo "[";
				echo "]";
			}
		}
		else
		{
			if(empty($_GET["document_id"])!=true&&empty($_GET["document_relate_table"])!=true)
			{
				$dayinjilu_table = M("dayin_jilu")->where("document_id like '".$_GET["document_id"]."' and document_relate_table like '".$_GET["document_relate_table"]."'")->select();
				if($dayinjilu_table!==false)
				{
					echo "[";
					echo '{"part_print_top":"'.$dayinjilu_table[0]["part_print_top"].'","part_print_left":"'.$dayinjilu_table[0]["part_print_left"].'","part_print_width":"'.$dayinjilu_table[0]["part_print_width"].'","part_print_height":"'.$dayinjilu_table[0]["part_print_height"].'"}';
					echo "]";
				}
				else
				{
					echo "[";
					echo "]";
				}
			}
			else
			{
				echo "[";
				echo "]";
			}
		}
	}

	// 获取药品使用单位
	public function getYaopinShiyongDanwei()
	{
		$data_yaopin_table = M('data_yaopin');
		$shiyong_danwei = $data_yaopin_table->where('shiyong_danwei != "" AND shiyong_danwei != "-"')->field('shiyong_danwei',true)->group('shiyong_danwei')->select();

		echo "[";
		for($i=0;$i<sizeof($shiyong_danwei)-1;$i++)
		{
			echo '{"label":"'.$shiyong_danwei[$i]['shiyong_danwei'].'"},';
		}
		if(sizeof($shiyong_danwei)>0)
		{
			echo '{"label":"'.$shiyong_danwei[$i]['shiyong_danwei'].'"}]';
		}
		else
		{
			echo '{}]';
		}
	}

	// 获取药品零售单位
	public function getYaopinLingshouDanwei()
	{
		$data_yaopin_table = M('data_yaopin');
		$lingshou_danwei = $data_yaopin_table->where('lingshou_danwei != "" AND lingshou_danwei != "-"')->field('lingshou_danwei',true)->group('lingshou_danwei')->select();

		echo "[";
		for($i=0;$i<sizeof($lingshou_danwei)-1;$i++)
		{
			echo '{"label":"'.$lingshou_danwei[$i]['lingshou_danwei'].'"},';
		}
		if(sizeof($lingshou_danwei)>0)
		{
			echo '{"label":"'.$lingshou_danwei[$i]['lingshou_danwei'].'"}]';
		}
		else
		{
			echo '{}]';
		}
	}
	
	//根据yaopin_id判断是否为自带药品
	public function isZidaiYaopin()
	{

		//dump($_REQUEST);
		$data_yaopin_table = M('data_yaopin');
		$yaopin_mingcheng = $data_yaopin_table->where('id ='.$_REQUEST['yid'])->getField('zhongwen_mingcheng');
		if(!ereg(".*自带.*", $yaopin_mingcheng))
		{
			echo 'false';
		}
		else
		{
			echo 'true';
		}
	}

	public function getDataXingming()
	{
		echo "[";
		if(array_key_exists( 'term',$_REQUEST))
		{
			$request_keyword=$_REQUEST["term"];
		}
		if ($request_keyword==null || $request_keyword=="")
			$request_keyword = "chucuodezifu";
		else
			$request_keyword = "%".$request_keyword."%";
			$data = M("patient_basic_info")->where("xingming like '%$request_keyword%'")->select();
			$data_dianhua=M("patient_contact_info")->where("patient_id like '%$request_keyword%'")->select();
			for($i=0;$i<sizeof($data)-1;$i++)
			{
				$latest_tijian_id[$i]=date('Ymd').substr($data[$i]["patient_id"],-4);
				$new_zhuyuan_id_temp = explode("-",$data[$i]["latest_zhuyuan_id"]);
				echo '{"label":"'.$data[$i]["patient_id"].'", "new_zhuyuan_id":"'.$new_zhuyuan_id_temp[0].'","latest_tijian_id":"'.$latest_tijian_id[$i].'", "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'","lianxi_dianhua":"'.$data_dianhua[$i]["lianxi_dianhua"].'", "zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"},';
			}
			if(sizeof($data)>0)
			{
				$latest_tijian_id[$i]=date('Ymd').substr($data[$i]["patient_id"],-4);
				$new_zhuyuan_id_temp = explode("-",$data[$i]["latest_zhuyuan_id"]);
				echo '{"label":"'.$data[$i]["patient_id"].'", "new_zhuyuan_id":"'.$new_zhuyuan_id_temp[0].'","latest_tijian_id":"'.$latest_tijian_id[$i].'",  "xingming":"'.$data[$i]["xingming"].'","nianling":"'.$data[$i]["nianling"].'","xingbie":"'.$data[$i]["xingbie"].'","lianxi_dianhua":"'.$data_dianhua[$i]["lianxi_dianhua"].'","zhuangtai":"'.$data[$i]["zhuangtai"].'", "desc":" 姓名:'.$data[$i]['xingming'].' |性别:'.$data[$i]['xingbie'].'|状态:'.$data[$i]['zhuangtai'].'"}';
			}
		//echo '{ "id": "Hirundo rustica", "label": "Barn Swallow", "value": "Barn Swallow" }, { "id": "Cecropis daurica", "label": "Red-rumped Swallow", "value": "Red-rumped Swallow" }';
		echo "]";
	}

	//根据yaopin_id获取药品信息
	public function getYaopinByID()
	{
		$data_yaopin_table = M('data_yaopin');
		$yaopin = $data_yaopin_table->where('id ='.$_REQUEST['yid'])->select();
		if($yaopin !== false)
		{
			echo '[';
			foreach ($yaopin[0] as $key => $val) {
			 	 echo '{"'.$key.'":"'.$val.'"},';
			}
			echo '{}]';
		}
		else
		{
		 	echo "[]";
		}
	}
	
	public function getChufangZhenduan()
	{
		if(array_key_exists( 'zhixing_id',$_REQUEST))
		{
			$zhixing_id = $_REQUEST["zhixing_id"];
		}
		if(array_key_exists( 'zhenduan_type',$_REQUEST))
		{
			$zhenduan_type = $_REQUEST["zhenduan_type"];
		}
		if(array_key_exists( 'chufang_id',$_REQUEST))
		{
			$chufang_id = $_REQUEST["chufang_id"];
		}
		$zhuyuan_chufang_info = M("zhuyuan_chufang")->where("id like '$chufang_id' ")->select();
		/*
		"门诊诊断"=>1
		"入院初步诊断"=>2
		"入院确定诊断"=>3
		"入院修正诊断"=>4
		"补充诊断"=>5
		"出院诊断"=>6
		*/
		$zhenduan_info_check = explode("|",$zhuyuan_chufang_info[0]["relate_zhenduan_id"]);
		$zhenduan_xiyi_info = explode(":",$zhenduan_info_check[0]);
		$zhenduan_zhongyi_info = explode(":",$zhenduan_info_check[1]);
		$zhenduan_xiyi_id = explode("+",$zhenduan_xiyi_info[1]);
		$zhenduan_zhongyi_id = explode("+",$zhenduan_zhongyi_info[1]);
		$zhenduan_xiyi = M("zhenduan_xiyi")->where("zhixing_id like '$zhixing_id' and zhixing_type like '住院' and zhenduan_type like '$zhenduan_type'")->select();
		$zhenduan_zhongyi = M("zhenduan_zhongyi")->where("zhixing_id like '$zhixing_id' and zhixing_type like '住院' and zhenduan_type like '$zhenduan_type'")->select();
		foreach($zhenduan_xiyi as $zhenduan_xiyi_one)
		{
			$checked = "false";
			foreach($zhenduan_xiyi_id as $zhenduan_xiyi_id_one)
			{
				if($zhenduan_xiyi_one["id"]==$zhenduan_xiyi_id_one)
				{
					$checked = "true";
					break;
				}
			}
			if($checked=="true")
			{
				echo "<input type='checkbox' checked='checked' name='zhenduan_xiyi[]' value='".$zhenduan_xiyi_one["id"]."' />".$zhenduan_xiyi_one["zhenduan_mingcheng"]."<br />";
			}
			else
			{
				echo "<input type='checkbox' name='zhenduan_xiyi[]' value='".$zhenduan_xiyi_one["id"]."' />".$zhenduan_xiyi_one["zhenduan_mingcheng"]."<br />";
			}
		}
		foreach($zhenduan_zhongyi as $zhenduan_zhongyi_one)
		{
			foreach($zhenduan_zhongyi_id as $zhenduan_zhongyi_id_one)
			{
				if($zhenduan_zhongyi_one["id"]==$zhenduan_zhongyi_id_one)
				{
					$checked = "true";
					break;
				}
			}
			if($checked=="true")
			{
				echo "<input type='checkbox' checked='checked' name='zhenduan_zhongyi[]' value='".$zhenduan_zhongyi_one["id"]."' />".$zhenduan_zhongyi_one["zhenduan_mingcheng"]."<br />";
			}
			else
			{
				echo "<input type='checkbox' name='zhenduan_zhongyi[]' value='".$zhenduan_zhongyi_one["id"]."' />".$zhenduan_zhongyi_one["zhenduan_mingcheng"]."<br />";
			}
		}
	}
	
	public function getJianchaZhenduan()
	{
		if(array_key_exists( 'zhixing_id',$_REQUEST))
		{
			$zhixing_id = $_REQUEST["zhixing_id"];
		}
		if(array_key_exists( 'zhenduan_type',$_REQUEST))
		{
			$zhenduan_type = $_REQUEST["zhenduan_type"];
		}
		if(array_key_exists( 'jiancha_id',$_REQUEST))
		{
			$chufang_id = $_REQUEST["jiancha_id"];
		}
		$zhuyuan_jiancha_info = M("zhuyuan_fuzhujiancha")->where("id like '$jiancha_id' ")->select();
		/*
		"门诊诊断"=>1
		"入院初步诊断"=>2
		"入院确定诊断"=>3
		"入院修正诊断"=>4
		"补充诊断"=>5
		"出院诊断"=>6
		*/
		$zhenduan_info_check = explode("|",$zhuyuan_jiancha_info[0]["relate_zhenduan_id"]);
		$zhenduan_xiyi_info = explode(":",$zhenduan_info_check[0]);
		$zhenduan_zhongyi_info = explode(":",$zhenduan_info_check[1]);
		$zhenduan_xiyi_id = explode("+",$zhenduan_xiyi_info[1]);
		$zhenduan_zhongyi_id = explode("+",$zhenduan_zhongyi_info[1]);
		$zhenduan_xiyi = M("zhenduan_xiyi")->where("zhixing_id like '$zhixing_id' and zhixing_type like '住院' and zhenduan_type like '$zhenduan_type'")->select();
		$zhenduan_zhongyi = M("zhenduan_zhongyi")->where("zhixing_id like '$zhixing_id' and zhixing_type like '住院' and zhenduan_type like '$zhenduan_type'")->select();
		foreach($zhenduan_xiyi as $zhenduan_xiyi_one)
		{
			$checked = "false";
			foreach($zhenduan_xiyi_id as $zhenduan_xiyi_id_one)
			{
				if($zhenduan_xiyi_one["id"]==$zhenduan_xiyi_id_one)
				{
					$checked = "true";
					break;
				}
			}
			if($checked=="true")
			{
				echo "<input type='checkbox' checked='checked' name='zhenduan_xiyi[]' value='".$zhenduan_xiyi_one["id"]."' />".$zhenduan_xiyi_one["zhenduan_mingcheng"]."<br />";
			}
			else
			{
				echo "<input type='checkbox' name='zhenduan_xiyi[]' value='".$zhenduan_xiyi_one["id"]."' />".$zhenduan_xiyi_one["zhenduan_mingcheng"]."<br />";
			}
		}
		foreach($zhenduan_zhongyi as $zhenduan_zhongyi_one)
		{
			foreach($zhenduan_zhongyi_id as $zhenduan_zhongyi_id_one)
			{
				if($zhenduan_zhongyi_one["id"]==$zhenduan_zhongyi_id_one)
				{
					$checked = "true";
					break;
				}
			}
			if($checked=="true")
			{
				echo "<input type='checkbox' checked='checked' name='zhenduan_zhongyi[]' value='".$zhenduan_zhongyi_one["id"]."' />".$zhenduan_zhongyi_one["zhenduan_mingcheng"]."<br />";
			}
			else
			{
				echo "<input type='checkbox' name='zhenduan_zhongyi[]' value='".$zhenduan_zhongyi_one["id"]."' />".$zhenduan_zhongyi_one["zhenduan_mingcheng"]."<br />";
			}
		}
	}
	
	public function getServerState()
	{
		echo "normal";
	}
	
	public function getServerVersion()
	{
		$yiyuan_news = M("yiyuan_news")->where("id = '0'")->find();
		echo $yiyuan_news["content"];
	}

	public function getUpdateFilesLocation()
	{
		$yiyuan_news = M("yiyuan_news")->where("id = '1'")->find();
		echo $yiyuan_news["content"];
	}
	

	public function getUpdateNews()
	{
		$yiyuan_news = M("yiyuan_news")->where("id = '2'")->find();
		echo $yiyuan_news["content"];
	}

	public function getJianchaMoren()
	{
		$name = $_GET['name'];
		$list = M('data_xiangmu')->field('other_info')->where("`zhongwen_mingcheng` = '$name' ")->find();
		$zhi = explode('|',$list['other_info']);
		foreach($zhi as $val)
		{
			if(stristr($val,"morenzhi:"))
			{
				$zhiname = explode(':',$val);
			}
		}
		if(!empty($zhiname[1]))
		{
			echo $zhiname[1];
		}
		else
		{
			echo 0;
		}
	}

	public function getTreeview_yaopin()
	{
		echo "[";
		if(array_key_exists( 'id',$_REQUEST))
		{
			$pid=$_REQUEST['id'];
		}
		if ($pid==null || $pid=="")
			$pid = "0";
		if(array_key_exists('table',$_GET))
		{
			$table_name = $_GET['table'];
			$data = M($table_name)->where("pid like '".$pid."'")->select();
			if(!empty($data))
			{
				for($i=0;$i<sizeof($data)-1;$i++)
				{
					if($data[$i]['sub_direct_number']!=0)
						echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',  cengyong_mingcheng:'".$data[$i]['cengyong_mingcheng']."',  shangpin_mingcheng:'".$data[$i]['shangpin_mingcheng']."', yingwen_mingcheng:'".$data[$i]['yingwen_mingcheng']."', pinyin_index:'".$data[$i]['pinyin_index']."', isParent:true,chengfen:'".$data[$i]['chengfen']."',xingzhuang:'".$data[$i]['xingzhuang']."',gongneng:'".$data[$i]['gongneng']."',shiyingzheng:'".$data[$i]['shiyingzheng']."',guige:'".$data[$i]['guige']."',yongfayongliang:'".$data[$i]['yongfayongliang']."',buliangfanying:'".$data[$i]['buliangfanying']."',jinji:'".$data[$i]['jinji']."',zhuyi:'".$data[$i]['zhuyi']."',yunfuburu:'".$data[$i]['yunfuburu']."',ertong:'".$data[$i]['ertong']."',laonian:'".$data[$i]['laonian']."',xianghu:'".$data[$i]['xianghu']."',guoliang:'".$data[$i]['guoliang']."',linchuang:'".$data[$i]['linchuang']."',yaoli:'".$data[$i]['yaoli']."',yaodai:'".$data[$i]['yaodai']."',zhucang:'".$data[$i]['zhucang']."',baozhuang:'".$data[$i]['baozhuang']."',youxiaoqi:'".$data[$i]['youxiaoqi']."',zhixingbiaozhun:'".$data[$i]['zhixingbiaozhun']."',pizhunwenhao:'".$data[$i]['pizhunwenhao']."',shengchanchangjia:'".$data[$i]['shengchanchangjia']."',shengchandizhi:'".$data[$i]['shengchandizhi']."',youzheng:'".$data[$i]['youzheng']."',dianhua:'".$data[$i]['dianhua']."',chuanzhen:'".$data[$i]['chuanzhen']."',wangzhi:'".$data[$i]['wangzhi']."',shengchanriqi:'".$data[$i]['shengchanriqi']."',guoqiriqi:'".$data[$i]['guoqiriqi']."',shengchanpihao:'".$data[$i]['shengchanpihao']."',kucun:'".$data[$i]['kucun']."',danjia:'".$data[$i]['danjia']."',jixing:'".$data[$i]['jixing']."',shuoming:'".$data[$i]['shuoming']."',youjian:'".$data[$i]['youjian']."',type:'".$data[$i]['type']."'},";
					else
						echo "{ id:'".$data[$i]['id']."',pid:'".$data[$i]['pid']."', zhongwen_mingcheng:'".$data[$i]['zhongwen_mingcheng']."',  cengyong_mingcheng:'".$data[$i]['cengyong_mingcheng']."',  shangpin_mingcheng:'".$data[$i]['shangpin_mingcheng']."', yingwen_mingcheng:'".$data[$i]['yingwen_mingcheng']."', pinyin_index:'".$data[$i]['pinyin_index']."', isParent:false,chengfen:'".$data[$i]['chengfen']."',xingzhuang:'".$data[$i]['xingzhuang']."',gongneng:'".$data[$i]['gongneng']."',shiyingzheng:'".$data[$i]['shiyingzheng']."',guige:'".$data[$i]['guige']."',yongfayongliang:'".$data[$i]['yongfayongliang']."',buliangfanying:'".$data[$i]['buliangfanying']."',jinji:'".$data[$i]['jinji']."',zhuyi:'".$data[$i]['zhuyi']."',yunfuburu:'".$data[$i]['yunfuburu']."',ertong:'".$data[$i]['ertong']."',laonian:'".$data[$i]['laonian']."',xianghu:'".$data[$i]['xianghu']."',guoliang:'".$data[$i]['guoliang']."',linchuang:'".$data[$i]['linchuang']."',yaoli:'".$data[$i]['yaoli']."',yaodai:'".$data[$i]['yaodai']."',zhucang:'".$data[$i]['zhucang']."',baozhuang:'".$data[$i]['baozhuang']."',youxiaoqi:'".$data[$i]['youxiaoqi']."',zhixingbiaozhun:'".$data[$i]['zhixingbiaozhun']."',pizhunwenhao:'".$data[$i]['pizhunwenhao']."',shengchanchangjia:'".$data[$i]['shengchanchangjia']."',shengchandizhi:'".$data[$i]['shengchandizhi']."',youzheng:'".$data[$i]['youzheng']."',dianhua:'".$data[$i]['dianhua']."',chuanzhen:'".$data[$i]['chuanzhen']."',wangzhi:'".$data[$i]['wangzhi']."',shengchanriqi:'".$data[$i]['shengchanriqi']."',guoqiriqi:'".$data[$i]['guoqiriqi']."',shengchanpihao:'".$data[$i]['shengchanpihao']."',kucun:'".$data[$i]['kucun']."',danjia:'".$data[$i]['danjia']."',jixing:'".$data[$i]['jixing']."',shuoming:'".$data[$i]['shuoming']."',youjian:'".$data[$i]['youjian']."',type:'".$data[$i]['type']."'},";
				}
					if($data[sizeof($data)-1]['sub_direct_number']!=0)
						echo "{ id:'".$data[sizeof($data)-1]['id']."',pid:'".$data[sizeof($data)-1]['pid']."', zhongwen_mingcheng:'".$data[sizeof($data)-1]['zhongwen_mingcheng']."', cengyong_mingcheng:'".$data[sizeof($data)-1]['cengyong_mingcheng']."',  shangpin_mingcheng:'".$data[sizeof($data)-1]['shangpin_mingcheng']."', yingwen_mingcheng:'".$data[sizeof($data)-1]['yingwen_mingcheng']."', pinyin_index:'".$data[sizeof($data)-1]['pinyin_index']."', isParent:true,chengfen:'".$data[sizeof($data)-1]['chengfen']."',xingzhuang:'".$data[sizeof($data)-1]['xingzhuang']."',gongneng:'".$data[sizeof($data)-1]['gongneng']."',shiyingzheng:'".$data[sizeof($data)-1]['shiyingzheng']."',guige:'".$data[sizeof($data)-1]['guige']."',yongfayongliang:'".$data[sizeof($data)-1]['yongfayongliang']."',buliangfanying:'".$data[sizeof($data)-1]['buliangfanying']."',jinji:'".$data[sizeof($data)-1]['jinji']."',zhuyi:'".$data[sizeof($data)-1]['zhuyi']."',yunfuburu:'".$data[sizeof($data)-1]['yunfuburu']."',ertong:'".$data[sizeof($data)-1]['ertong']."',laonian:'".$data[sizeof($data)-1]['laonian']."',xianghu:'".$data[sizeof($data)-1]['xianghu']."',guoliang:'".$data[sizeof($data)-1]['guoliang']."',linchuang:'".$data[sizeof($data)-1]['linchuang']."',yaoli:'".$data[sizeof($data)-1]['yaoli']."',yaodai:'".$data[sizeof($data)-1]['yaodai']."',zhucang:'".$data[sizeof($data)-1]['zhucang']."',baozhuang:'".$data[sizeof($data)-1]['baozhuang']."',youxiaoqi:'".$data[sizeof($data)-1]['youxiaoqi']."',zhixingbiaozhun:'".$data[sizeof($data)-1]['zhixingbiaozhun']."',pizhunwenhao:'".$data[sizeof($data)-1]['pizhunwenhao']."',shengchanchangjia:'".$data[sizeof($data)-1]['shengchanchangjia']."',shengchandizhi:'".$data[sizeof($data)-1]['shengchandizhi']."',youzheng:'".$data[sizeof($data)-1]['youzheng']."',dianhua:'".$data[sizeof($data)-1]['dianhua']."',chuanzhen:'".$data[sizeof($data)-1]['chuanzhen']."',wangzhi:'".$data[sizeof($data)-1]['wangzhi']."',shengchanriqi:'".$data[sizeof($data)-1]['shengchanriqi']."',guoqiriqi:'".$data[sizeof($data)-1]['guoqiriqi']."',shengchanpihao:'".$data[sizeof($data)-1]['shengchanpihao']."',kucun:'".$data[sizeof($data)-1]['kucun']."',danjia:'".$data[sizeof($data)-1]['danjia']."',jixing:'".$data[sizeof($data)-1]['jixing']."',shuoming:'".$data[sizeof($data)-1]['shuoming']."',youjian:'".$data[sizeof($data)-1]['youjian']."',type:'".$data[sizeof($data)-1]['type']."'}";
					else
						echo "{ id:'".$data[sizeof($data)-1]['id']."',pid:'".$data[sizeof($data)-1]['pid']."', zhongwen_mingcheng:'".$data[sizeof($data)-1]['zhongwen_mingcheng']."', cengyong_mingcheng:'".$data[sizeof($data)-1]['cengyong_mingcheng']."',  shangpin_mingcheng:'".$data[sizeof($data)-1]['shangpin_mingcheng']."', yingwen_mingcheng:'".$data[sizeof($data)-1]['yingwen_mingcheng']."', pinyin_index:'".$data[sizeof($data)-1]['pinyin_index']."', isParent:false,chengfen:'".$data[sizeof($data)-1]['chengfen']."',xingzhuang:'".$data[sizeof($data)-1]['xingzhuang']."',gongneng:'".$data[sizeof($data)-1]['gongneng']."',shiyingzheng:'".$data[sizeof($data)-1]['shiyingzheng']."',guige:'".$data[sizeof($data)-1]['guige']."',yongfayongliang:'".$data[sizeof($data)-1]['yongfayongliang']."',buliangfanying:'".$data[sizeof($data)-1]['buliangfanying']."',jinji:'".$data[sizeof($data)-1]['jinji']."',zhuyi:'".$data[sizeof($data)-1]['zhuyi']."',yunfuburu:'".$data[sizeof($data)-1]['yunfuburu']."',ertong:'".$data[sizeof($data)-1]['ertong']."',laonian:'".$data[sizeof($data)-1]['laonian']."',xianghu:'".$data[sizeof($data)-1]['xianghu']."',guoliang:'".$data[sizeof($data)-1]['guoliang']."',linchuang:'".$data[sizeof($data)-1]['linchuang']."',yaoli:'".$data[sizeof($data)-1]['yaoli']."',yaodai:'".$data[sizeof($data)-1]['yaodai']."',zhucang:'".$data[sizeof($data)-1]['zhucang']."',baozhuang:'".$data[sizeof($data)-1]['baozhuang']."',youxiaoqi:'".$data[sizeof($data)-1]['youxiaoqi']."',zhixingbiaozhun:'".$data[sizeof($data)-1]['zhixingbiaozhun']."',pizhunwenhao:'".$data[sizeof($data)-1]['pizhunwenhao']."',shengchanchangjia:'".$data[sizeof($data)-1]['shengchanchangjia']."',shengchandizhi:'".$data[sizeof($data)-1]['shengchandizhi']."',youzheng:'".$data[sizeof($data)-1]['youzheng']."',dianhua:'".$data[sizeof($data)-1]['dianhua']."',chuanzhen:'".$data[sizeof($data)-1]['chuanzhen']."',wangzhi:'".$data[sizeof($data)-1]['wangzhi']."',shengchanriqi:'".$data[sizeof($data)-1]['shengchanriqi']."',guoqiriqi:'".$data[sizeof($data)-1]['guoqiriqi']."',shengchanpihao:'".$data[sizeof($data)-1]['shengchanpihao']."',kucun:'".$data[sizeof($data)-1]['kucun']."',danjia:'".$data[sizeof($data)-1]['danjia']."',jixing:'".$data[sizeof($data)-1]['jixing']."',shuoming:'".$data[sizeof($data)-1]['shuoming']."',youjian:'".$data[sizeof($data)-1]['youjian']."',type:'".$data[sizeof($data)-1]['type']."'}";
			}
		}
		echo "]";
	}
	
	public function patientInfoReplace($original_info,$original_zhuyuan_id,$new_zhuyuan_id)
	{
		$replaced_info = $original_info;
		
		//先进行关键词级别替换：
		$new_zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$new_zhuyuan_id'")->find();
		$new_patient_basic_info = M("patient_basic_info")->where("patient_id like '".$new_zhuyuan_basic_info["patient_id"]."'")->find();
			$nianling = A('Home/Data');
			$new_patient_basic_info["nianling"] = $nianling->patientNianling($new_zhuyuan_basic_info['ruyuan_riqi_time'],$new_zhuyuan_basic_info['patient_id']);
		$new_patient_contact_info = M("patient_contact_info")->where("patient_id like '".$new_zhuyuan_basic_info["patient_id"]."'")->find();
		$zhenduan_xiyi_zhuyao_info = M("zhenduan_xiyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '主要诊断' and zhixing_type != '住院_删除'")->find();
		$zhenduan_xiyi_ciyao_info = M("zhenduan_xiyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '次要诊断' and zhixing_type != '住院_删除'")->find();
		$zhenduan_zhongyi_zhubing_info = M("zhenduan_zhongyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '主要诊断' and zhixing_type != '住院_删除'")->find();
		$zhenduan_zhongyi_zhuzheng_info = M("zhenduan_zhongyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '中医病症' and zhixing_type != '住院_删除'")->find();
		$new_zhuyuan_zhenduan = $zhenduan_xiyi_zhuyao_info["zhenduan_mingcheng"];
		$new_zhuyuan_zhu_zhenduan = $zhenduan_xiyi_zhuyao_info["zhenduan_mingcheng"];
		if(!empty($zhenduan_xiyi_ciyao_info["zhenduan_mingcheng"]))
			$new_zhuyuan_zhenduan .= "、".$zhenduan_xiyi_ciyao_info["zhenduan_mingcheng"];
		if(!empty($zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"]))
			$new_zhuyuan_zhenduan .= "、".$zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"];
		if(!empty($zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"]))
			$new_zhuyuan_zhu_zhenduan .= "、".$zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"];
		if(!empty($zhenduan_zhongyi_zhuzheng_info["zhenduan_mingcheng"]))
			$new_zhuyuan_zhenduan .= "、".$zhenduan_zhongyi_zhuzheng_info["zhenduan_mingcheng"];
		foreach($replaced_info as $key => $one_info)
		{
			$replaced_info[$key] = str_replace("【姓名】",$new_patient_basic_info["xingming"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【年龄】",$new_patient_basic_info["nianling"]."",$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【性别】",$new_patient_basic_info["xingbie"]."性",$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【籍贯】",$new_patient_basic_info["jiguan"]."籍",$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【入院日期】",$new_zhuyuan_basic_info["ruyuan_riqi_time"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【入院时间】",$new_zhuyuan_basic_info["ruyuan_riqi_time"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【住院号】",$new_zhuyuan_basic_info["zhuyuan_id"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【病床号】",$new_zhuyuan_basic_info["bingchuang_hao"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【床号】",$new_zhuyuan_basic_info["bingchuang_hao"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【联系电话】",$new_patient_contact_info["juzhu_dianhua"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【现住址】",$new_patient_contact_info["juzhu_dizhi"],$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【诊断】",$new_zhuyuan_zhenduan,$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【主诊断】",$new_zhuyuan_zhu_zhenduan,$replaced_info[$key]);
			$replaced_info[$key] = str_replace("【住院病区】",$new_zhuyuan_basic_info["zhuyuan_bingqu"],$replaced_info[$key]);
		}
		
		//再进行内容级别替换：
		$original_zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$original_zhuyuan_id'")->find();
		if(!empty($original_zhuyuan_basic_info))
		{
			$original_patient_basic_info = M("patient_basic_info")->where("patient_id like '".$original_zhuyuan_basic_info["patient_id"]."'")->find();
				$nianling = A('Home/Data');
				$original_patient_basic_info["nianling"] = $nianling->patientNianling($original_zhuyuan_basic_info['ruyuan_riqi_time'],$original_zhuyuan_basic_info['patient_id']);
			$original_patient_contact_info = M("patient_contact_info")->where("patient_id like '".$original_zhuyuan_basic_info["patient_id"]."'")->find();
			foreach($replaced_info as $key => $one_info)
			{
				if(!empty($original_patient_basic_info["xingming"]))
					$replaced_info[$key] = str_replace($original_patient_basic_info["xingming"],$new_patient_basic_info["xingming"],$replaced_info[$key]);
				if(!empty($original_patient_basic_info["nianling"]))
					$replaced_info[$key] = str_replace($original_patient_basic_info["nianling"],$new_patient_basic_info["nianling"],$replaced_info[$key]);
				if(!empty($original_patient_basic_info["xingbie"]))
					$replaced_info[$key] = str_replace($original_patient_basic_info["xingbie"]."性",$new_patient_basic_info["xingbie"]."性",$replaced_info[$key]);
				if(!empty($original_patient_basic_info["jiguan"]))
					$replaced_info[$key] = str_replace($original_patient_basic_info["jiguan"]."籍",$new_patient_basic_info["jiguan"]."籍",$replaced_info[$key]);
				if(!empty($original_zhuyuan_basic_info["ruyuan_riqi_time"]))
					$replaced_info[$key] = str_replace($original_zhuyuan_basic_info["ruyuan_riqi_time"],$new_zhuyuan_basic_info["ruyuan_riqi_time"],$replaced_info[$key]);
				if(!empty($original_zhuyuan_basic_info["zhuyuan_id"]))
					$replaced_info[$key] = str_replace($original_zhuyuan_basic_info["zhuyuan_id"],$new_zhuyuan_basic_info["zhuyuan_id"],$replaced_info[$key]);
				if(!empty($original_zhuyuan_basic_info["bingchuang_hao"]))
					$replaced_info[$key] = str_replace($original_zhuyuan_basic_info["bingchuang_hao"],$new_zhuyuan_basic_info["bingchuang_hao"],$replaced_info[$key]);
				if(!empty($original_patient_contact_info["juzhu_dianhua"]))
					$replaced_info[$key] = str_replace($original_patient_contact_info["juzhu_dianhua"],$new_patient_contact_info["juzhu_dianhua"],$replaced_info[$key]);
				if(!empty($original_patient_contact_info["juzhu_dizhi"]))
					$replaced_info[$key] = str_replace($original_patient_contact_info["juzhu_dizhi"],$new_patient_contact_info["juzhu_dizhi"],$replaced_info[$key]);
				if(!empty($original_zhuyuan_basic_info["zhuyuan_bingqu"]))
					$replaced_info[$key] = str_replace($original_zhuyuan_basic_info["zhuyuan_bingqu"],$new_zhuyuan_basic_info["zhuyuan_bingqu"],$replaced_info[$key]);
			}
		}
		return $replaced_info;
	}

	public function getPatientInfoForReplace()
	{
		$new_zhuyuan_id = $_REQUEST["zhuyuan_id"];
		$new_zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$new_zhuyuan_id'")->find();
		if(!empty($new_zhuyuan_basic_info))
		{
			$new_patient_basic_info = M("patient_basic_info")->where("patient_id like '".$new_zhuyuan_basic_info["patient_id"]."'")->find();
				$nianling = A('Home/Data');
				$new_patient_basic_info["nianling"] = $nianling->patientNianling($new_zhuyuan_basic_info['ruyuan_riqi_time'],$new_zhuyuan_basic_info['patient_id']);
			$new_patient_contact_info = M("patient_contact_info")->where("patient_id like '".$new_zhuyuan_basic_info["patient_id"]."'")->find();
			$new_zhuyuan_bingshi_info = M("zhuyuan_bingshi")->where("zhuyuan_id like '$new_zhuyuan_id'")->find();
			$zhenduan_xiyi_zhuyao_info = M("zhenduan_xiyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '主要诊断' and zhixing_type != '住院_删除'")->find();
			$zhenduan_xiyi_ciyao_info = M("zhenduan_xiyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '次要诊断' and zhixing_type != '住院_删除'")->find();
			$zhenduan_zhongyi_zhubing_info = M("zhenduan_zhongyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '主要诊断' and zhixing_type != '住院_删除'")->find();
			$zhenduan_zhongyi_zhuzheng_info = M("zhenduan_zhongyi")->where("zhixing_id like '$new_zhuyuan_id' and zhenduan_sub_type like '中医病症' and zhixing_type != '住院_删除'")->find();
			$new_zhuyuan_zhenduan = $zhenduan_xiyi_zhuyao_info["zhenduan_mingcheng"];
			$new_zhuyuan_zhu_zhenduan = $zhenduan_xiyi_zhuyao_info["zhenduan_mingcheng"];
			if(!empty($zhenduan_xiyi_ciyao_info["zhenduan_mingcheng"]))
				$new_zhuyuan_zhenduan .= "、".$zhenduan_xiyi_ciyao_info["zhenduan_mingcheng"];
			if(!empty($zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"]))
				$new_zhuyuan_zhenduan .= "、".$zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"];
			if(!empty($zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"]))
				$new_zhuyuan_zhu_zhenduan .= "、".$zhenduan_zhongyi_zhubing_info["zhenduan_mingcheng"];
			if(!empty($zhenduan_zhongyi_zhuzheng_info["zhenduan_mingcheng"]))
				$new_zhuyuan_zhenduan .= "、".$zhenduan_zhongyi_zhuzheng_info["zhenduan_mingcheng"];
				
			echo '{"xingming":"'.$new_patient_basic_info["xingming"].'",
						 "nianling":"'.$new_patient_basic_info["nianling"].'",
						 "xingbie":"'.$new_patient_basic_info["xingbie"].'性",
						 "jiguan":"'.$new_patient_basic_info["jiguan"].'籍",
						 "ruyuan_riqi_time":"'.$new_zhuyuan_basic_info["ruyuan_riqi_time"].'",
						 "zhuyuan_id":"'.$new_zhuyuan_basic_info["zhuyuan_id"].'",
						 "bingchuang_hao":"'.$new_zhuyuan_basic_info["bingchuang_hao"].'",
						 "juzhu_dianhua":"'.$new_patient_contact_info["juzhu_dianhua"].'",
						 "juzhu_dizhi":"'.$new_patient_contact_info["juzhu_dizhi"].'",
						 "zhenduan":"'.$new_zhuyuan_zhenduan.'",
						 "zhu_zhenduan":"'.$new_zhuyuan_zhu_zhenduan.'",
						 "zhuyuan_bingqu":"'.$new_zhuyuan_basic_info["zhuyuan_bingqu"].'",
						 "zhuyuan_kebie":"'.$new_zhuyuan_basic_info["zhuyuan_kebie"].'",
						 "zhusu":"'.strip_tags($new_zhuyuan_bingshi_info["zhusu"]).'",
						 "today":"'.date('Y-m-d').'"
						}';
		}
	}

	public function getPatient()
	{
		$patient_id = $_GET['patient_id'];
		$patient = M("zhuyuan_basic_info")->where("patient_id = '$patient_id' ")->find();
		if(!empty($patient))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}

	//同步整合
	public function bingChengTongBu()
	{
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$name_array = explode("|",$_GET['name']);
		$fanhuizhi = '';
		foreach($name_array as $val)
		{
			//查询规则
			$biaoqian = M('data_rule')->where("database_table_field = '$val' ")->find();
			//把需要替换的标签筛选出来
			$temp_array = explode("】",$biaoqian['database_table_rule']);
			$tag_array = array();
			foreach($temp_array as $s)
			{
				$ziduan_array = explode("【",$s);
				if($ziduan_array[1] != '')
				{
					$tag_array[] = '【'.$ziduan_array[1].'】';
				}
			}
			//读取规则
			$duqu_zhi = $biaoqian['database_table_rule'];
			//把标签循环替换成需要的值
			foreach($tag_array as $biaoqian_zhi)
			{
				$tihuan = M('data_tag')->where("tag = '$biaoqian_zhi' ")->find();
				
				if($tihuan['database_table'] == 'patient_basic_info')
				{
					$tiaojian = "latest_zhuyuan_id = '$zhuyuan_id' ";
				}
				else
				{
					$tiaojian = "zhuyuan_id = '$zhuyuan_id' ";
				}
				$tihuan_ziduan = M("$tihuan[database_table]")->field("$tihuan[database_table_field]")->where($tiaojian)->find();
				
				if($tihuan['database_table_field'] == 'nianling' and $val == 'binglitedian')
				{
					if($tihuan_ziduan['nianling'] >= 60)
					{
						$tihuan_ziduan['nianling'] = '老年';
					}
					elseif($tihuan_ziduan['nianling'] >= 40 and $tihuan_ziduan['nianling'] < 60)
					{
						$tihuan_ziduan['nianling'] = '中年';
					}
					elseif($tihuan_ziduan['nianling'] < 40)
					{
						$tihuan_ziduan['nianling'] = '青年';
					}
				}
				if($tihuan['database_table_field'] == 'jiguan')
				{
					$temp_jiguan = explode("|",$tihuan_ziduan['jiguan']);
					$tihuan_ziduan['jiguan'] = $temp_jiguan['0'];
				}
				$duqu_zhi = str_replace($biaoqian_zhi,$tihuan_ziduan[$tihuan[database_table_field]],$duqu_zhi);
			}
			$fanhuizhi .= $val.'tiantan_@@@'.$duqu_zhi.'tiantan_###';
		}
		echo $fanhuizhi;
	}
	
	//导入字段
	public function daoru()
	{
		$patient_basic_info = M('zhuyuan_bingshi')->find();
		$model = M('data_tag');
		foreach($patient_basic_info as $key => $k)
		{
			$shuju = array(
				'tag' => $key,
				'database_table' => 'zhuyuan_bingshi',
				'database_table_field' => $key,
			);
			if($model->add($shuju))
			{
				echo $key.'--'.$k.'--添加成功<hr />';
			}
			else
			{
				echo $key.'--'.$k.'--添加失败<hr />';
			}
			
		}
		//print_r($patient_basic_info);
	}
	
	
	/////
	public function xiaobianqueSearch()
	{
		$x = new XiaobianqueAction();
		$x->search();
	}
	
	
	//小扁鹊里面查询data开头的库点击连接的时候打开需要的页面
	public function openDataUrl()
	{
		$table_name = $_GET['table'];
		$table_id = $_GET['id'];
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$data_one = M($table_name)->where("id = '$table_id' ")->find();
	 
		//增加关键词标签
		$TextProcessingEngine = A('Home/TextProcessingEngine');
		$data_one['content'] = $TextProcessingEngine->analyseText($data_one['content']);
		if(!empty($data_one['shuoming']))
		{
			//增加关键词标签
			$TextProcessingEngine = A('Home/TextProcessingEngine');
			$data_one['shuoming'] = $TextProcessingEngine->analyseText($data_one['shuoming']);
		}
		if(!empty($zhuyuan_id))
			$data_one = $this->patientInfoReplace($data_one,"tiantanheheopendata",$zhuyuan_id);
			
		$this->assign('data_one',$data_one);
		if($table_name=="data_jibing_knowledge")
		{
			$this->display("data_jibing");
		}
		if($table_name=="data_yaopin_detail_info")
		{
			$data_yaopin = M('data_yaopin_detail_info')->where("id = '$table_id' ")->find();
			$this->assign('data_yaopin',$data_yaopin);
			$this->display("data_yaopin");
		}
		if($table_name=="data_jijiu_knowledge")
		{
			$this->display("data_jijiu");
		}
		if($table_name=="data_nursing_knowledge")
		{
			$this->display("data_huli");
		}
		if($table_name=="data_zhongyi_knowledge")
		{
			$this->display("data_zhongyi");
		}
		if($table_name=="data_xiangmu")
		{
			$this->display("data_xiangmu");
		}
		if($table_name=="data_yingxiang_knowledge")
		{
			$this->display("data_yingxiang");
		}
		if($table_name=="data_icd10")
		{
			$this->display("data_icd10");
		}
		if($table_name=="data_zhongyibingzheng")
		{
			$this->display("data_icd10");
		}
		if($table_name=="data_shoushucaozuo")
		{
			$this->display("data_icd10");
		}
		if($table_name=="data_sunshangzhongdu")
		{
			$this->display("data_icd10");
		}
		if($table_name=="data_xingtaixue")
		{
			$this->display("data_icd10");
		}
		if($table_name=="data_jianyan_knowledge")
		{
			$this->display("data_jianyan");
		}
		if($table_name=="zhuyuan_fuzhujiancha")
		{
			$jiancha_id = $_GET['id'];
			$jiancha_info = M("zhuyuan_fuzhujiancha")->where("id like '$jiancha_id' ")->find();
			if(empty($jiancha_info))
			{
				exit();
			}
	
			if($jiancha_info["jiancha_keshi_name"] == "放射科" || $jiancha_info["jiancha_keshi_name"] == "超声科" || $jiancha_info["jiancha_keshi_name"] == "超声"  || $jiancha_info["jiancha_keshi_name"] == "放射"  || $jiancha_info["jiancha_keshi_name"] == "CT"  || $jiancha_info["jiancha_keshi_name"] == "CR")
			{
				$jieguo_baogao.= file_get_contents("http://".$_SESSION["server_url"]."/tiantan_emr/Yingxiang/Xiangmu/showReport/zhixing_id/".$jiancha_info['zhuyuan_id']."/jiancha_id/".$jiancha_info['id']."/zhixing_type/住院/embedded_mode/true");
			}
			if($jiancha_info["jiancha_keshi_name"] == "检验科")
			{
				$jieguo_baogao.= file_get_contents("http://".$_SESSION["server_url"]."/tiantan_emr/Jianyan/Xiangmu/showReport/zhixing_id/".$jiancha_info['zhuyuan_id']."/jiancha_id/".$jiancha_info['id']."/zhixing_type/住院/embedded_mode/true");
			}
			$this->assign("jieguo_baogao",$jieguo_baogao);
			$this->display("fuzhujiancha");
		}
		if($table_name=="zhuyuan_bingli_media")
		{
			$this->display("zhuyuan_bingli_media");
		}
		if($table_name=="zhuyuan_chufang_detail")
		{
			$chufang_id = $_GET['chufang_id'];
			
			$data = M($table_name)->where("chufang_id = '$chufang_id' ")->select();
			
			if(!empty($data) && count($data) > 0)
			{
				if($data[0]['type'] == "中草药")
				{
					$this->assign('data',$data);
					$this->display("zhuyuan_chufang_zhongcaoyao");
				}else{
					$this->assign('data',$data);
					$this->display($table_name);
				}
			}
			
		}
		
		if($table_name=="zhuyuan_yizhu_changqi" || $table_name=="zhuyuan_yizhu_linshi")
		{
			$zuhao = $_GET['zuhao'];
			$data = M($table_name)->where("zuhao = '$zuhao' ")->select();
			$count = 0;
			
			foreach($data as  $key => & $value) 
			{
				if(count($data) == 1)
					$value['flag'] ="onlyone" ;
				else
				{
					if($count == 0)
						$value['flag'] ="first" ;
						
					else if($count == count($data)-1 )
						$value['flag'] ="last" ;
					else
						$value['flag'] ="middle" ;
				}
				$count++;
			}
			$this->assign('data',$data);
			$this->display($table_name);
		}
		
		if($table_name=="data_template")
		{
			$this->display("data_template");
		}
	}
	//质控列表显示
	public function getBingliWentiFn()
	{
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$tiaoshu = $_GET['tiaoshu'];
		$BingliJiancha = A("Zhikong/BingliJiancha");
		$zhikongList = $BingliJiancha->getOneBingliWentiFn($zhuyuan_id,$tiaoshu);
		$return_html = '';
		foreach($zhikongList as $one)
		{
			$return_html .= '<dt><a href="/tiantan_emr/Zhikong/BingliPingfen/showPingfen/zhuyuan_id/'.$zhuyuan_id.'" target="_self">'.$one['wenti_miaoshu'].'</a></dt>';
		}
		echo $return_html;
	}
	//获取医生的所有质控列表显示
	public function getDoctorWentiFn()
	{
		$user_number = $_GET['user_number'];
		$tiaoshu = $_GET['tiaoshu'];
		$BingliJiancha = A("Zhikong/BingliJiancha");
		$zhikongList = $BingliJiancha->getOneDoctorWentiFn($user_number,$tiaoshu);
		$return_html = '';
		foreach($zhikongList as $one)
		{
			$return_html .= '<dt><a href="/tiantan_emr/Zhikong/BingliPingfen/showPingfen/zhuyuan_id/'.$one['zhuyuan_id'].'" target="_self">'.$one['wenti_miaoshu'].'</a></dt>';
		}
		echo $return_html;
	}

	//获取单挑科室信息
	public function getOneKeshiType()
	{
		$zhi = explode("|",$_GET['zhi']);
		$keshi = M('data_xiangmu')->field('id,other_info')->where("id = '$zhi[1]' and other_info like '%|无%' ")->find();
		if(empty($keshi))
		{
			echo '无';
		}
		else
		{
			echo '有';
		}
	}
	//获取检查部位
	public function getKeshiType()
	{
		$zhi = explode("|",$_GET['zhi']);
		$xiangmu = M('data_xiangmu')->field('id,pid,zhongwen_mingcheng')->where("pid = '$zhi[1]' ")->select();
		$html = '';
		if(!empty($xiangmu))
		{
			$html .= '<select width="150px" class="select_type" action_type="jump_yaowuguomin" name="jiancha[]" reg="[^0]" right_message="录入正确" error_message="此项为必填项，请进行选择" jump="jump"><option value="0">请选择</option>';
			foreach($xiangmu as $val)
			{
				$html .= '<option value="'.$val['zhongwen_mingcheng'].'">'.$val['zhongwen_mingcheng'].'</option>';
			}
			$html .= '</select>';
		}
		echo $html;
	}
	//获取检查部位
	public function getJianchaBuwei()
	{
		$zhi = explode("|",$_GET['zhi']);
		$buwei = M('data_xiangmu')->field('id,pid,zhongwen_mingcheng')->where("pid = '$zhi[1]' ")->select();
		$html = '';
		if(!empty($buwei))
		{
			$html .= '<option value="0">请选择</option>';
			foreach($buwei as $val)
			{
				$html .= '<option value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|">'.$val['zhongwen_mingcheng'].'</option>';
			}
		}		
		echo $html;
	}
	//获取检查项目
	public function getJianchaXiangmu()
	{
		$zhi = explode("|",$_GET['zhi']);
		$xiangmu = M('data_xiangmu')->field('id,pid,zhongwen_mingcheng')->where("pid = '$zhi[1]' ")->select();
		$xiangmu_html = '';
		if(!empty($xiangmu))
		{
			$xiangmu_html .= '<option value="0">请选择</option>';
			foreach($xiangmu as $val)
			{
				$xiangmu_html .= '<option value="'.$val['zhongwen_mingcheng'].'|'.$val['id'].'|">'.$val['zhongwen_mingcheng'].'</option>';
			}
		}
		
		echo $xiangmu_html;
	}
	//获取检查具体项目
	public function getJianchaJutiXiangmu()
	{
		$zhi = explode("|",$_GET['zhi']);
		$xiangmu = M('data_xiangmu')->field('id,pid,zhongwen_mingcheng')->where("pid = '$zhi[1]' ")->select();
		$xiangmu_html = '';
		if(!empty($xiangmu))
		{
			$xiangmu_html .= '<select width="150px" class="select_type" action_type="jump_yaowuguomin" name="jiancha[]" reg="[^0]" right_message="录入正确" error_message="此项为必填项，请进行选择" jump="jump"><option value="0">请选择</option>';
			foreach($xiangmu as $val)
			{
				$xiangmu_html .= '<option value="'.$val['zhongwen_mingcheng'].'">'.$val['zhongwen_mingcheng'].'</option>';
			}
			$xiangmu_html .= '</select>';
		}
		
		echo $xiangmu_html;
	}
	//获取项目的默认值
	public function getXiangmuMorenzhi()
	{
		$zhi = $_GET['zhi'];
		$xiangmu = M('data_xiangmu')->field('id,other_info,relate_table_name')->where("zhongwen_mingcheng = '$zhi' ")->find();
		$morenzhi = explode("morenzhi:",$xiangmu['other_info']);
		if(count($morenzhi) > 1)
		{
			$morenvalue = explode("|",$morenzhi['1']);
		}
		
		echo $morenvalue['0'].'#'.$xiangmu['relate_table_name'].'#'.$xiangmu['other_info'];
	}

	//匹配关键词
	public function matchKeyword()
	{
		$content = $_POST['content'];
		$guize = M('data_guize')->select();
		$position = 0;
		$max_postion = 0;
		$keyword = "";
		foreach($guize as $val)
		{
			$position = strripos($content,$val['keyword']);
			if($position)
			{
				if($position>$max_postion)
				{
					$max_postion = $position;
					$keyword = $val['keyword'];
				}
			}
		}
		echo $keyword;
	}
	//查找一条药品信息
	public function getOneDataYaopin()
	{
		$yaopin_id = $_GET['yaopin_id'];
		$data_yaopin_one = M('data_yaopin')->field('yaojileixing')->where("id = '$yaopin_id' ")->find();
		echo $data_yaopin_one['yaojileixing'];
	}
	//获取当前医师的所有住院病人
	public function getAllYishiPatient()
	{
		$type = $_GET['type'];
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$user_number = $_SESSION['user_number'];
		// $zhuyuan_zongjie_info = M('zhuyuan_zongjie_info')->field('zhuyuan_id')->where("kezhuren_id  = '$user_number' or zhurenyishi_id  = '$user_number' or zhuzhiyishi_id  = '$user_number' or zhuyuanyishi_id  = '$user_number' or jinxiuyishi_id  = '$user_number' or shixiyishi_id  = '$user_number' or yanjiusheng_shixiyishi_id  = '$user_number' ")->select();
		// 主治医师只显示“我的病人”
		$zhuyuan_zongjie_info = M('zhuyuan_zongjie_info')->field('zhuyuan_id')->where("kezhuren_id  = '$user_number' or zhurenyishi_id  = '$user_number' or zhuyuanyishi_id  = '$user_number' or jinxiuyishi_id  = '$user_number' or shixiyishi_id  = '$user_number' or yanjiusheng_shixiyishi_id  = '$user_number' ")->select();
		$in_value = '';
		foreach($zhuyuan_zongjie_info as $val)
		{
			$in_value .= ",'zhuyuan_id'";
		}
		$in_value = substr ($in_value,1);
		$model = M();
		$sql = "select zhuyuan_id,bingchuang_hao from `zhuyuan_basic_info` where zhuyuan_id IN ($in_value) and zhuangtai = '住院中' order by bingchuang_hao ASC ";
		$patient_list = $model->query($sql);
		//查询这个病人的病房
		if(!empty($zhuyuan_id))
		{
			$huanzhe_bingchuang = M('zhuyuan_basic_info')->field('bingchuang_hao')->where("zhuyuan_id = '$zhuyuan_id' ")->find();
		}
		$bingfang = array();
		$huanzhe_list = array();
		//$return_html = '<div style=" min-height:35px; width:100%;background-color:#FFF;"><input name="sousuo" type="text" style="height:18px; width:60%;margin-top:5px; margin-left:5%;" /><input name="" value="搜索" type="button" style="margin-left:3%;height:25px;width:23%;" /></div>';
		if(!empty($huanzhe_bingchuang))
		{
			$bingchuang = explode("-",$huanzhe_bingchuang['bingchuang_hao']);
			$return_html = '<a href="#'.$bingchuang['0'].'"><input name="" type="hidden" id="hidden_button" /></a>';
		}
		else
		{
			$return_html = '';
		}
		
		foreach($patient_list as $val)
		{
			$temp_bingfang = explode("-",$val['bingchuang_hao']);
			if(!in_array($temp_bingfang['0'],$bingfang))
			{
				$bingfang[] = $temp_bingfang['0'];
				$return_html .= '<div style="width:90%;line-height:30px; font-weight:600;margin-top:10px; margin-left:5%;background-color:#279bce; color:#fff;text-indent:5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;" ><a name="'.$temp_bingfang['0'].'">'.$temp_bingfang['0'].'病室</a></div>';
			}
			$patient_xingming = M('patient_basic_info')->field('xingming,xingbie')->where("latest_zhuyuan_id = '$val[zhuyuan_id]' ")->find();
			if($patient_xingming['xingbie'] == '男')
			{
				$xingbie_image = 'male_head.png';
			}
			else
			{
				$xingbie_image = 'female_head.png';
			}
			if($type == 'bingchengjilu')
			{
				$return_html .= '<a href="/tiantan_emr/ZhuyuanYishi/BingchengJilu/showView/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" class="left_zhuyuan_id" id="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'add_jiancha')
			{
				$return_html .= '<a href="/tiantan_emr/ZhuyuanYishi/Jiancha/showAddJiancha/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'addchufang')
			{
				$return_html .= '<a href="/tiantan_emr/Home/Chufangguanli/showList/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'add_tizhengjilu')
			{
				$return_html .= '<a href="/tiantan_emr/Home/TiwenJiludan/showAddTiwendan/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'add_ruyuanjilu')
			{
				$return_html .= '<a href="/tiantan_emr/ZhuyuanYishi/RuyuanJilu/showView/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'add_new_yizhu')
			{
				$return_html .= '<a href="/tiantan_emr/Home/Yizhuguanli/showChangqi/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['zhuyuan_id'].'" >';
			}
			if($zhuyuan_id == $val['zhuyuan_id'])
			{
				$return_html .= '<div class="left_menu_div" style="padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;background-color:#D2942C;color:#ffffff;" zhuyuanid="'.$val['zhuyuan_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$patient_xingming['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px;">'.$val['bingchuang_hao'].'|'.$val['zhuyuan_id'].'</div></div></a>';
			}
			else
			{
				$return_html .= '<div class="left_menu_div" style="background-color:#FFF; padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;" zhuyuanid="'.$val['zhuyuan_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$patient_xingming['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px;">'.$val['bingchuang_hao'].'|'.$val['zhuyuan_id'].'</div></div></a>';
			}
		}
		echo $return_html;
	}
	public function getAllHushiPatient()
	{
		$type = $_GET['type'];
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$user_department = $_SESSION['user_department'];
		$model = M();
		$sql = "select a.zhuyuan_id,a.bingchuang_hao,a.zhuyuan_bingqu,b.xingming from `zhuyuan_basic_info` a,`patient_basic_info` b where a.zhuyuan_bingqu = '$user_department' and a.zhuangtai = '住院中' and a.patient_id=b.patient_id order by a.bingchuang_hao ASC ";
		$patient_list = $model->query($sql);
		$huanzhe_list = array();
		//$return_html = '<div style=" min-height:35px; width:100%;background-color:#FFF;"><input name="sousuo" type="text" style="height:18px; width:60%;margin-top:5px; margin-left:5%;" /><input name="" value="搜索" type="button" style="margin-left:3%;height:25px;width:23%;" /></div><a id="yincang_div_a"><input name="" type="hidden" id="yincang_div" /></a>';
		//查询这个病人的病房
		if(!empty($zhuyuan_id))
		{
			$huanzhe_bingchuang = M('zhuyuan_basic_info')->field('bingchuang_hao')->where("zhuyuan_id = '$zhuyuan_id' ")->find();
		}
		if(!empty($huanzhe_bingchuang))
		{
			$bingchuang = explode("-",$huanzhe_bingchuang['bingchuang_hao']);
			$return_html = '<a href="#'.$bingchuang['0'].'"><input name="" type="hidden" id="hidden_button" /></a>';
		}
		else
		{
			$return_html = '';
		}
		foreach($patient_list as $val)
		{
			$temp_bingfang = explode("-",$val['bingchuang_hao']);
			if(!in_array($temp_bingfang['0'],$bingfang))
			{
				$bingfang[] = $temp_bingfang['0'];
				$return_html .= '<div style="width:90%;line-height:30px; font-weight:600;margin-top:10px; margin-left:5%;background-color:#279bce; color:#fff;text-indent:5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;"><a name="'.$temp_bingfang['0'].'">'.$temp_bingfang['0'].'病室</a></div>';
			}
			$patient_xingming = M('patient_basic_info')->field('xingming,xingbie')->where("latest_zhuyuan_id = '$val[zhuyuan_id]' ")->find();
			if($patient_xingming['xingbie'] == '男')
			{
				$xingbie_image = 'male_head.png';
			}
			else
			{
				$xingbie_image = 'female_head.png';
			}
			if($type == 'add_tizhengjilu')
			{
				$return_html .= '<a href="/tiantan_emr/Home/TiwenJiludan/showAddTiwendan/zhuyuan_id/'.$val['zhuyuan_id'].'" state="opened" style="text-decoration:none;color:#000;" target="conframe" class="left_zhuyuan_id" zhuyuanid="'.$val['zhuyuan_id'].'" name="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'show_sancedan')
			{
				$return_html .= '<a href="/tiantan_emr/Home/TiwenJiludan/showList/zhuyuan_id/'.$val['zhuyuan_id'].'" state="opened" style="text-decoration:none;color:#000;" target="conframe" class="left_zhuyuan_id" zhuyuanid="'.$val['zhuyuan_id'].'" name="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'show_yizhu_hushi')
			{
				$return_html .= '<a href="/tiantan_emr/Home/Yizhuguanli/showChangqi/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" class="left_zhuyuan_id" zhuyuanid="'.$val['zhuyuan_id'].'" name="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'show_hulijilu')
			{
				$return_html .= '<a href="/tiantan_emr/HuliJilu/showList/zhuyuan_id/'.$val['zhuyuan_id'].'#bottom" state="opened" style="text-decoration:none;color:#000;" target="conframe" class="left_zhuyuan_id" zhuyuanid="'.$val['zhuyuan_id'].'" name="'.$val['zhuyuan_id'].'" >';
			}
			if($type == 'yizhuzhixing')
			{
				$temp_url = "";
				if(!empty($val['xingming']) || $val['xingming']!="")
				{
					$temp_url .= "/xingming/".$val['xingming'];
				}
				if(!empty($val['bingchuang_hao']) || $val['bingchuang_hao']!="")
				{
					$temp_url .= "/chuanghao/".$val['bingchuang_hao'];
				}
				$return_html .= '<a href="/tiantan_emr/Home/Yizhuguanli/showYizhuZhixing/user_department/'.$val['zhuyuan_bingqu'].$temp_url.'/binglihao/'.$val['zhuyuan_id'].'#bottom" state="opened" style="text-decoration:none;color:#000;" target="conframe" class="left_zhuyuan_id" zhuyuanid="'.$val['zhuyuan_id'].'" name="'.$val['zhuyuan_id'].'" >';
			}
			if($zhuyuan_id == $val['zhuyuan_id'])
			{
				$return_html .= '<div class="left_menu_div" style="padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;background-color:#D2942C;color:#ffffff;" zhuyuanid="'.$val['zhuyuan_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$patient_xingming['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px;">'.$val['bingchuang_hao'].'|'.$val['zhuyuan_id'].'</div></div></a>';
			}
			else
			{
				$return_html .= '<div state="closed" class="left_menu_div" style="background-color:#FFF; padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;" zhuyuanid="'.$val['zhuyuan_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$patient_xingming['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px; ">'.$val['bingchuang_hao'].'|'.$val['zhuyuan_id'].'</div></div></a>';
			}
		}
		echo $return_html;
	}
	//获取当前医师的所有门诊病人
	public function getAllMenzhenYishiPatient()
	{
		$huanzhe_type = $_GET["huanzhe_type"];
		$user_number = $_SESSION['user_number'];
		$model = M();
		if($huanzhe_type=="已诊")
		{
			$sql = "select a.*,b.* from menzhen_basic_info as a,patient_basic_info as b,menzhen_yuzhen_wenjuan as c where a.patient_id = b.patient_id and a.menzhen_id=c.menzhen_id group by a.menzhen_id";
		}
		else if($huanzhe_type=="待诊")
		{
			$sql = "select a.*,b.* from menzhen_basic_info as a,patient_basic_info as b where a.patient_id = b.patient_id and a.menzhen_id not in (select menzhen_id from menzhen_yuzhen_wenjuan)";
		}
		else
		{
			$sql = "select a.*,b.* from menzhen_basic_info as a,patient_basic_info as b where a.patient_id = b.patient_id ";
		}
		$menzhen_basic_info = $model->query($sql);
		$return_html = '';
		foreach($menzhen_basic_info as $val)
		{
			if($val['xingbie'] == '男')
			{
				$xingbie_image = 'male_head.png';
			}
			else
			{
				$xingbie_image = 'female_head.png';
			}

			$return_html .= '<a href="/tiantan_emr/MenzhenYishi/Patient/showPatientMenzhenHistory/patient_id/'.$val['patient_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['menzhen_id'].'" >';
			$return_html .= '<div class="left_menu_div" style="background-color:#FFF; padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;" menzhenid="'.$val['menzhen_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$val['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px;">'.$val['menzhen_id'].'</div></div></a>';
		}
		echo $return_html;
	}
	
	//为左侧菜单快速获取患者列表
	public function getPatientListForQuickMenu()
	{
		$function_url = $_GET['function_url'];
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$user_number = $_SESSION['user_number'];

		$model = M();
		$sql = "select a.zhuyuan_id,a.bingchuang_hao from zhuyuan_basic_info as a,zhuyuan_zongjie_info as b where a.zhuangtai = '住院中' and a.zhuyuan_id = b.zhuyuan_id and a.zhuyuan_bingqu = '".$_SESSION["user_department"]."' order by cast(a.bingchuang_hao as signed) ASC";
		$patient_list = $model->query($sql);
		
		foreach($patient_list as $val)
		{
			$temp_bingfang = explode("-",$val['bingchuang_hao']);
			if(!in_array($temp_bingfang['0'],$bingfang))
			{
				$bingfang[] = $temp_bingfang['0'];
				$return_html .= '<div style="width:90%;line-height:30px; font-weight:600;margin-top:10px; margin-left:5%;background-color:#279bce; color:#fff;text-indent:5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;" ><a name="'.$temp_bingfang['0'].'">'.$temp_bingfang['0'].'病室</a></div>';
			}
			$patient_xingming = M('patient_basic_info')->field('xingming,xingbie')->where("latest_zhuyuan_id = '$val[zhuyuan_id]' ")->find();
			if($patient_xingming['xingbie'] == '男')
			{
				$xingbie_image = 'male_head.png';
			}
			else
			{
				$xingbie_image = 'female_head.png';
			}
			
			$return_html .= '<a href="'.$function_url.'/zhuyuan_id/'.$val['zhuyuan_id'].'" style="text-decoration:none;color:#000;" target="conframe" id="'.$val['zhuyuan_id'].'" >';
			//当前患者高亮
			if($zhuyuan_id == $val['zhuyuan_id'])
			{
				$return_html .= '<div class="left_menu_div left_quick_menu_hidden_postion" style="padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;background-color:#D2942C;color:#ffffff;" zhuyuanid="'.$val['zhuyuan_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$patient_xingming['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px;">'.$val['bingchuang_hao'].'|'.$val['zhuyuan_id'].'</div></div></a>';
			}
			else
				$return_html .= '<div class="left_menu_div" style="padding-top:5px; margin-top:10px; margin-left:5%; min-height:55px; width:90%; overflow:hidden;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius:5px;background-color:white;color:black;" zhuyuanid="'.$val['zhuyuan_id'].'"><img src="/tiantan_emr/Public/css/images/'.$xingbie_image.'" alt="" width="44" height="48" style="float:left;"><div style="float:left;margin-top:5px;margin-left:5px;">'.$patient_xingming['xingming'].'</div><div style="float:left;margin-top:10px;margin-left:5px; font-size:12px;word-wrap:break-word;width:100px;">'.$val['bingchuang_hao'].'|'.$val['zhuyuan_id'].'</div></div></a>';
		}
		echo $return_html;
	}
	
	//设置护理级别
	public function setHuliJibie()
	{
		//住院id号
		$zhuyuan_id = $_GET['id'];
		//护理级别类型
		$type = $_GET['type'];
		$date['hulijibie'] = $type;
		$zhuyuan_basic_info_con = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->save($date);
		if(!empty($zhuyuan_basic_info_con))
		{
			//添加长期医嘱
			$zuhao = $this->microTimeStamp();
			$zuhao++;
			$data_yizhu_info = M("data_yizhu")->where("zhongwen_mingcheng like '".$type."'")->select();
			$one["id"]="";
			$one['yizhu_id'] = $data_yizhu_info[0]['id'];
			$one['zhuyuan_id'] = $zhuyuan_id;
			$one['content'] = $type;
			$one["start_time"]=date('Y-m-d H:i');
			$one["start_yishi_name"]= $_SESSION['user_name'];
			$one['ciliang'] = "0";
			$one["zhixing_keshi"] = "病区";
			$one["shiyong_danwei"] = "天";
			$one["zhouqi"]="";
			$one["shifou_jiaji"]="";
			$one["state"]="已添加";
			$one['yongfa_type'] = "护理";
			$one["zuhao"]=$zuhao;
			$zhuyuan_yizhu_changqi_info = M("zhuyuan_yizhu_changqi")->add($one);
			if(!empty($zhuyuan_yizhu_changqi_info))
			{
				echo "success";
			}
			else
			{
				echo "false";
			}
		}
		else
		{
			echo "false";
		}
	}
	//设置护理级别Json
	public function setHuliJibieJson()
	{
		//住院id号
		$zhuyuan_id = $_GET['id'];
		//护理级别类型
		$type = $_GET['type'];
		$date['hulijibie'] = $type;
		$zhuyuan_basic_info_con = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->save($date);
		if(!empty($zhuyuan_basic_info_con))
		{
			//添加长期医嘱
			$zuhao = $this->microTimeStamp();
			$zuhao++;
			$data_yizhu_info = M("data_yizhu")->where("zhongwen_mingcheng like '".$type."'")->select();
			$one["id"]="";
			$one['yizhu_id'] = $data_yizhu_info[0]['id'];
			$one['zhuyuan_id'] = $zhuyuan_id;
			$one['content'] = $type;
			$one["start_time"]=date('Y-m-d H:i');
			$one["start_yishi_name"]= $_SESSION['user_name'];
			$one['ciliang'] = "0";
			$one["zhixing_keshi"] = "病区";
			$one["shiyong_danwei"] = "天";
			$one["zhouqi"]="";
			$one["shifou_jiaji"]="";
			$one["state"]="已添加";
			$one['yongfa_type'] = "护理";
			$one["zuhao"]=$zuhao;
			$zhuyuan_yizhu_changqi_info = M("zhuyuan_yizhu_changqi")->add($one);
			if(!empty($zhuyuan_yizhu_changqi_info))
			{
				$result["ret"] = "success";
				echo $_GET['callback']."(".json_encode($result).")";
			}
			else
			{
				$result["ret"] = "error";
				echo $_GET['callback']."(".json_encode($result).")";
			}
		}
		else
		{
			$result["ret"] = "error";
			echo $_GET['callback']."(".json_encode($result).")";
		}
	}
	//刷新SESSION
	public function shuaxinSession()
	{
		$session = $_SESSION['user_number'];
		if(empty($session))
		{
			echo 'no';
		}
		else
		{
			$_SESSION['user_number'] = $session;
			echo 'yes';
		}
	}
	//
	public function updateLogin()
	{
		$login_password = trim($_POST['login_password']);
		$user_number = trim($_POST['user_number']);
		$user_search_result = M("yiyuan_user")->where("login_password like '$login_password' and user_number like '$user_number' ")->find();
		if(!empty($user_search_result))
		{
			$_SESSION['server_url'] = C("WEB_HOST");
			$_SESSION['user_number'] = $user_search_result['user_number'];
			$_SESSION['user_name'] = $user_search_result['user_name'];
			$_SESSION['user_department'] = $user_search_result['user_department'];
			$_SESSION['user_department_id'] = $user_search_result['user_department_id'];
			$_SESSION['user_department_position'] = $user_search_result['user_department_position'];
			$_SESSION['user_type'] = $user_search_result['user_type'];
			$_SESSION['user_kebie'] = $user_search_result['user_kebie'];
			$_SESSION['login_state'] = "true";
			echo 'ok';
		}
		else
		{
			echo 'no';
		}
	}
	//判断这项检查是否新农合或者医保范围内的检查
	public function isYibaoNonghe()
	{
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$dataid = $_GET['dataid'];
		$zhuyuan_basic_info = M('zhuyuan_basic_info')->where("zhuyuan_id like '$zhuyuan_id'")->find();
		if(strpos($zhuyuan_basic_info['yiliaofukuanfangshi'],"保险"))
		{
			$data_xiangmu = M('data_xiangmu')->field('other_info')->where("id = '$dataid' ")->find();
			if(strpos($data_xiangmu['other_info'],"yibao") or strpos($data_xiangmu['other_info'],"nonghe"))
			{
				echo 'yes';
			}
			else
			{
				echo 'no';
			}
		}
	}
	
	public function updatePic()
	{
		$date_time = strtotime("now");
		$date_time_format = date('Y-m-d H:i:s',$date_time);
		$data = $_POST["data"];
		$zhuyuan_id = $_POST["zhuyuan_id"];
		$label = $_POST["label"];
		$png_data = base64_decode($data);
		if(!file_exists("./Uploads/"))
		{
			mkdir("./Uploads/",0777); 
		}
		$dir = "./Uploads/".$zhuyuan_id."/";
		if(!file_exists($testdir))
		{
			mkdir($dir,0777); 
		}
		$file = $dir.$label.$date_time.".jpeg";
		$handle = fopen($file, 'w');
		fwrite($handle, $png_data);
		$file_url_save = "/tiantan_emr".substr($file,1);
		$add_info = array(
			"zhuyuan_id" => $zhuyuan_id,
			"editor_label" => $label,
			"type" => "image",
			"url" => $file_url_save,
			"record_time" => $date_time_format
		);
		$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->add($add_info);
		if($zhuyuan_bingli_media!==false)
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
	}
	
	public function readPic()
	{
		$read_type = $_GET["read_type"];
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$editor_label = $_GET["focus_name"];
		if($read_type=="count")
		{
			$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->where("zhuyuan_id like '$zhuyuan_id' and editor_label like '$editor_label' and type='image'")->select();
			$data_count = count($zhuyuan_bingli_media);
			$data_temp = "";
			foreach($zhuyuan_bingli_media as $one)
			{
				$data_temp .= $one["record_time"]."|";
			}
			if($data_temp!="")
			{
				$data_temp = substr($data_temp,0,-1);
			}
			echo $data_count."@".$data_temp;
		}
		else
		{
			$read_number = $_GET["read_number"];
			$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->where("zhuyuan_id like '$zhuyuan_id' and editor_label like '$editor_label' and type='image'")->select();
			$file = ".".substr($zhuyuan_bingli_media[$read_number]["url"],12);
			$png_data = file_get_contents($file);
			$data = base64_encode($png_data);
			echo $data;
		}
	}

	public function readAudio()
	{
		$read_type = $_GET["read_type"];
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$editor_label = $_GET["focus_name"];
		if($read_type=="count")
		{
			$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->where("zhuyuan_id like '$zhuyuan_id' and editor_label like '$editor_label' and type='audio'")->select();
			$data_count = count($zhuyuan_bingli_media);
			$data_temp = "";
			foreach($zhuyuan_bingli_media as $one)
			{
				$data_temp .= $one["record_time"]."|";
			}
			if($data_temp!="")
			{
				$data_temp = substr($data_temp,0,-1);
			}
			echo $data_count."@".$data_temp;
		}
		else
		{
			$read_number = $_GET["read_number"];
			$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->where("zhuyuan_id like '$zhuyuan_id' and editor_label like '$editor_label' and type='audio'")->select();
			// $file = ".".substr($zhuyuan_bingli_media[$read_number]["url"],12);
			// $png_data = file_get_contents($file);
			// $data = base64_encode($png_data);
			$data = $zhuyuan_bingli_media[$read_number]["url"];
			echo $data;
		}
	}
	
	public function updateAudio()
	{
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$label = $_GET["label"];
		$date_time = strtotime("now");
		$date_time_format = date('Y-m-d H:i:s',$date_time);
		if(!file_exists("./Uploads/"))
		{
			mkdir("./Uploads/",0777); 
		}
		$dir = "./Uploads/".$zhuyuan_id."/";
		if(!file_exists($testdir))
		{
			mkdir($dir,0777); 
		}
		$file = $dir.$label.$date_time.".amr";
		$file_url_save = "/tiantan_emr".substr($file,1);
		try{
				move_uploaded_file($_FILES["file"]["tmp_name"], $file);
				
				$add_info = array(
					"zhuyuan_id" => $zhuyuan_id,
					"editor_label" => $label,
					"type" => "audio",
					"url" => $file_url_save,
					"record_time" => $date_time_format
				);
				$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->add($add_info);
				if($zhuyuan_bingli_media!==false)
				{
					echo json_encode(array("msg"=>"录音成功"));
				}
				else
				{
					echo json_encode(array("msg"=>"录音失败。"));
				}
			}
			catch(Exception $e)
			{
				echo json_encode(array("msg"=>$file_url_save."录音失败".$result.$input));
			}
	}
	//获取院外批量检查的送检物
	public function getYuanwaiSongjianwu()
	{
		$name = $_GET["name"];
		$yuanwai_data = M('data_xiangmu')->field('other_info')->where("zhongwen_mingcheng = '$name' ")->find();
		if(!empty($yuanwai_data))
		{
			$other_info = explode("morenzhi:",$yuanwai_data["other_info"]);
			$morenzhi = explode("|",$other_info["1"]);
			echo $morenzhi["0"];
		}
		else
		{
			echo "0";
		}
	}
	
	public function getChuanghao()
	{
		if(!empty($_GET["zhuyuan_id"]))
		{
			$zhuyuan_id = $_GET["zhuyuan_id"];
		}
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->select();
		$bingchuang_hao = $zhuyuan_basic_info[0]["bingchuang_hao"];
		$zhiliao_leibie = $zhuyuan_basic_info[0]["zhiliao_leibie"];
		echo $bingchuang_hao."|".$zhiliao_leibie;
	}
	
	public function getXinshengerInfo()
	{
		if(!empty($_GET["zhuyuan_id"]))
		{
			$zhuyuan_id = $_GET["zhuyuan_id"];
		}
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id'")->select();
		$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$zhuyuan_basic_info[0]["patient_id"]."'")->select();
		$xinshenger = $patient_basic_info[0]["xinshenger"];
		echo $xinshenger;
	}
	
	//销毁session
	public function xiaohuiSession()
	{
		session_destroy();
		echo 'ok';
	}
	
	//获取毫秒级的时间戳
	public function microTimeStamp()
	{
		list($usec, $sec) = explode(" ", microtime());
		return (((float)$usec + (float)$sec)*10000);
	}

	//获取患者年龄
	public function patientNianling($ruyuan_riqi,$patient_id)
	{
		$patient_info = M("patient_basic_info")->field("nianling,shengri")->where("patient_id = '$patient_id'")->find();
		$zhuyuan_info = M("zhuyuan_basic_info")->field("nianling_zidongjisuan,special_info")->where("patient_id = '$patient_id'")->find();
		$database_nianling = $patient_info["nianling"];
		$nianling_zidongjisuan = $zhuyuan_info["nianling_zidongjisuan"];
		$shengri = $patient_info["shengri"];
		$shengri_info = explode(" ",$shengri);
		$shengri_nian = $shengri_info[0];
		if($ruyuan_riqi < "2014-02" || $nianling_zidongjisuan =="否")
		{
			if(!empty($database_nianling))
			{
				// if($zhuyuan_info["special_info"]=="新生儿")
				// {
				// 	$nianling = $database_nianling."天";
				// }
				// else
				// {
				// 	$nianling = $database_nianling."岁";
				// }
				$nianling = $database_nianling;
			}
			else
			{
				$nianling = "未知";
			}
		}
		else
		{
			if(!empty($ruyuan_riqi)&&!empty($shengri_nian))
			{
				//根据出生日期计算年龄：
				$nianling = intval((strtotime($ruyuan_riqi) - strtotime($shengri_nian))/86400/365);
				if($nianling==0)
				{
					// $nianling = intval((strtotime($ruyuan_riqi) - strtotime($shengri_nian))/86400)."天";
					$nianling = intval((strtotime($ruyuan_riqi) - strtotime($shengri_nian))/86400/30);
					// 一月以下xxx天
					if($nianling==0)
					{
						$nianling = intval((strtotime($ruyuan_riqi) - strtotime($shengri_nian))/86400)."天";
					}
					// 1月-1岁xxx月xxx天
					else
					{
						$nianling_month = $nianling;
						$niangling_day = intval((strtotime($ruyuan_riqi) - strtotime($shengri_nian))/86400)-$nianling_month*30;
						$nianling = $nianling_month."月".$niangling_day."天";
					}
				}
				// 1-3岁xxx月
				else if($nianling>0&&$nianling<3)
				{
					$nianling = intval((strtotime($ruyuan_riqi) - strtotime($shengri_nian))/86400/30);
					$nianling = $nianling."月";
				}
				else
				{
					$nianling = $nianling."岁";
				}
			}
			else
				$nianling = "0岁";
		}
		return $nianling;
		// return array(
		// 			"nianling"=>$nianling,
		// 			"is_xinshenger"=>$is_xinshenger
		// 		);
	}
	
	public function toAnalyseBingli()
	{
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$count = $_GET["count"];
		$zhuyuan_bingshi = array(
			'zhusu',
			'xianbingshi',
			'jiwangshi',
			'gerenshi',
			'yuejingshi',
			'hunyushi',
			'jiazushi'
		);
		$zhuyuan_ruyuantigejiancha = array(
			'jibenjiancha',
			'yibanqingkuang',
			'pifu_nianmo_linbajie',
			'toumianbu',
			'jingbu',
			'xiongbu',
			'fubu',
			'xinzang',
			'xinzang_kouzhen_leijian_er_you',
			'xinzang_kouzhen_leijian_er_zuo',
			'xinzang_kouzhen_leijian_san_you',
			'xinzang_kouzhen_leijian_san_zuo',
			'xinzang_kouzhen_leijian_si_you',
			'xinzang_kouzhen_leijian_si_zuo',
			'xinzang_kouzhen_leijian_wu_you',
			'xinzang_kouzhen_leijian_wu_zuo',
			'xinzang_kouzhen_suoguzhongxian_juli',
			'gangmenshengzhi',
			'jizhusizhi',
			'shenjingxitong',
			'other',
			'ruyuan_fuzhu_jiancha',
			'ruyuan_zhuanke_jiancha',
			'ruyuan_zhongyi_jiancha'
		);
		$zhuyuan_bingchengjilushouci = array(
			'gaishu',
			'binglitedian',
			'zhongyibianzhengyiju',
			'xiyibianzhengyiju',
			'zhongyijianbiezhenduan',
			'xiyijianbiezhenduan',
			'zhenliaojihua',
			'jikecuoshi'
		);
		$model = M();
		$index = "";
		foreach($zhuyuan_bingshi as $one)
		{
			$index .= $one.",";
		}
		$index = substr($index,0,-1);
		$sql = "select ".$index." from zhuyuan_bingshi where zhuyuan_id like '$zhuyuan_id'";
		$update_data = $model->query($sql);
		foreach($update_data as $key => $one_info)
		{
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)<\/span>/","$1",$update_data[$key]);
				if($temp_div_content==$update_data[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<span(?:[^<])*?\>(((?!<\/span>).)*<span id=\"formula\"><span id=\"fenshi\"><span id=\"before\">((?!<\/span>).)*<\/span><span id=\"up\">((?!<\/span>).)*<\/span><span id=\"spliter\">((?!<\/span>).)*<\/span><span id=\"down\">((?!<\/span>).)*<\/span><span id=\"after\">((?!<\/span>).)*<\/span><\/span><\/span>.*)<\/span>/","$1",$update_data[$key]);
				if($temp_div_content == $update_data[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<b><\/b>/","",$update_data[$key]);
				$update_data[$key] = preg_replace("/<p><\/p>/","",$update_data[$key]);
				if($temp_div_content == $update_data[$key])
				{
					break;
				}
			}
			$update_data[$key] = preg_replace("/<b>\S*<\/b>\:/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<strong>\S*<\/strong>\:/", "", $update_data[$key], 1);
			//去除所有的空白p标签
			$update_data[$key] = preg_replace("/<p><br><\/p>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<br><\/p>/", "</p>", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<p><\/p>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<div><br><\/div>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<br><\/div>/", "</div>", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<div><\/div>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px; text-indent: \d{2}px;\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}pt\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px;\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span>(.*?)<\/span>/", "$1", $update_data[$key]);
			//保留上下标的内容;
			if(preg_match("/<span style=\"vertical-align: top; font-size: 12px;(.*?)\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"vertical-align: top; font-size: 12px;\">(.*?)<\/span>/", "<sup>$1</sup>", $update_data[$key]);
			}
			if(preg_match("/<span style=\"vertical-align: bottom; font-size: 12px;(.*?)\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"vertical-align: bottom; font-size: 12px;\">(.*?)<\/span>/", "<sub>$1</sub>", $update_data[$key]);
			}
			if(preg_match("/<u style=\"line-height: 1.5;\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<u style=\"line-height: 1.5;\">(.*?)<\/u>/", "$1", $update_data[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/","$1",$update_data[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/","$1",$update_data[$key]);
			}
			if(preg_match("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/",$update_data[$key]))
			{
				$update_data[$key]= preg_replace("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/","",$update_data[$key]);
			}
			$update_data[$key] = preg_replace("/<p><u><\/u><\/p>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<div(?:.|[\r\n])*?>/","<div>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<a(?:.|[\r\n])*?>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/a>/","", $update_data[$key]);
			/*
			$update_data[$key] = preg_replace("/<font(?:.|[\r\n])*?>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/font>\"/", "", $update_data[$key]);
			*/
			$update_data[$key] = preg_replace("/<p(?:.|[\r\n])*?>/","<p>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<o:p>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/o:p>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<p><\/span><\/p>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<p><\/p>/", "", $update_data[$key]);
			//增加关键词标签
			$TextProcessingEngine = A('Home/TextProcessingEngine');
			$update_data[$key] = $TextProcessingEngine->analyseText($update_data[$key]);
		}
		$save_info = M("zhuyuan_bingshi")->where("zhuyuan_id like '$zhuyuan_id'")->save($update_data);
		$index = "";
		foreach($zhuyuan_ruyuantigejiancha as $one)
		{
			$index .= $one.",";
		}
		$index = substr($index,0,-1);
		$sql = "select ".$index." from zhuyuan_ruyuantigejiancha where zhuyuan_id like '$zhuyuan_id'";
		$update_data = $model->query($sql);
		foreach($update_data as $key => $one_info)
		{
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)<\/span>/","$1",$update_data[$key]);
				if($temp_div_content==$update_data[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<span(?:[^<])*?\>(((?!<\/span>).)*<span id=\"formula\"><span id=\"fenshi\"><span id=\"before\">((?!<\/span>).)*<\/span><span id=\"up\">((?!<\/span>).)*<\/span><span id=\"spliter\">((?!<\/span>).)*<\/span><span id=\"down\">((?!<\/span>).)*<\/span><span id=\"after\">((?!<\/span>).)*<\/span><\/span><\/span>.*)<\/span>/","$1",$update_data[$key]);
				if($temp_div_content == $update_data[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<b><\/b>/","",$update_data[$key]);
				$update_data[$key] = preg_replace("/<p><\/p>/","",$update_data[$key]);
				if($temp_div_content == $update_data[$key])
				{
					break;
				}
			}
			$update_data[$key] = preg_replace("/<b>\S*<\/b>\:/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<strong>\S*<\/strong>\:/", "", $update_data[$key], 1);
			//去除所有的空白p标签
			$update_data[$key] = preg_replace("/<p><br><\/p>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<br><\/p>/", "</p>", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<p><\/p>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<div><br><\/div>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<br><\/div>/", "</div>", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<div><\/div>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px; text-indent: \d{2}px;\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}pt\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px;\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span>(.*?)<\/span>/", "$1", $update_data[$key]);
			//保留上下标的内容;
			if(preg_match("/<span style=\"vertical-align: top; font-size: 12px;(.*?)\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"vertical-align: top; font-size: 12px;\">(.*?)<\/span>/", "<sup>$1</sup>", $update_data[$key]);
			}
			if(preg_match("/<span style=\"vertical-align: bottom; font-size: 12px;(.*?)\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"vertical-align: bottom; font-size: 12px;\">(.*?)<\/span>/", "<sub>$1</sub>", $update_data[$key]);
			}
			if(preg_match("/<u style=\"line-height: 1.5;\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<u style=\"line-height: 1.5;\">(.*?)<\/u>/", "$1", $update_data[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/","$1",$update_data[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/","$1",$update_data[$key]);
			}
			if(preg_match("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/",$update_data[$key]))
			{
				$update_data[$key]= preg_replace("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/","",$update_data[$key]);
			}
			$update_data[$key] = preg_replace("/<p><u><\/u><\/p>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<div(?:.|[\r\n])*?>/","<div>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<a(?:.|[\r\n])*?>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/a>/","", $update_data[$key]);
			/*
			$update_data[$key] = preg_replace("/<font(?:.|[\r\n])*?>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/font>\"/", "", $update_data[$key]);
			*/
			$update_data[$key] = preg_replace("/<p(?:.|[\r\n])*?>/","<p>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<o:p>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/o:p>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<p><\/span><\/p>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<p><\/p>/", "", $update_data[$key]);
			//增加关键词标签
			$TextProcessingEngine = A('Home/TextProcessingEngine');
			$update_data[$key] = $TextProcessingEngine->analyseText($update_data[$key]);
		}
		$save_info = M("zhuyuan_ruyuantigejiancha")->where("zhuyuan_id like '$zhuyuan_id'")->save($update_data);
		$index = "";
		foreach($zhuyuan_bingchengjilushouci as $one)
		{
			$index .= $one.",";
		}
		$index = substr($index,0,-1);
		$sql = "select ".$index." from zhuyuan_bingchengjilushouci where zhuyuan_id like '$zhuyuan_id'";
		$update_data = $model->query($sql);
		foreach($update_data as $key => $one_info)
		{
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)<\/span>/","$1",$update_data[$key]);
				if($temp_div_content==$update_data[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<span(?:[^<])*?\>(((?!<\/span>).)*<span id=\"formula\"><span id=\"fenshi\"><span id=\"before\">((?!<\/span>).)*<\/span><span id=\"up\">((?!<\/span>).)*<\/span><span id=\"spliter\">((?!<\/span>).)*<\/span><span id=\"down\">((?!<\/span>).)*<\/span><span id=\"after\">((?!<\/span>).)*<\/span><\/span><\/span>.*)<\/span>/","$1",$update_data[$key]);
				if($temp_div_content == $update_data[$key])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $update_data[$key];
				$update_data[$key] = preg_replace("/<b><\/b>/","",$update_data[$key]);
				$update_data[$key] = preg_replace("/<p><\/p>/","",$update_data[$key]);
				if($temp_div_content == $update_data[$key])
				{
					break;
				}
			}
			$update_data[$key] = preg_replace("/<b>\S*<\/b>\:/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<strong>\S*<\/strong>\:/", "", $update_data[$key], 1);
			//去除所有的空白p标签
			$update_data[$key] = preg_replace("/<p><br><\/p>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<br><\/p>/", "</p>", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<p><\/p>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<div><br><\/div>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<br><\/div>/", "</div>", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<div><\/div>/", "", $update_data[$key], 1);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px; text-indent: \d{2}px;\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}pt\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px;\">/", "<span>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<span>(.*?)<\/span>/", "$1", $update_data[$key]);
			//保留上下标的内容;
			if(preg_match("/<span style=\"vertical-align: top; font-size: 12px;(.*?)\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"vertical-align: top; font-size: 12px;\">(.*?)<\/span>/", "<sup>$1</sup>", $update_data[$key]);
			}
			if(preg_match("/<span style=\"vertical-align: bottom; font-size: 12px;(.*?)\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"vertical-align: bottom; font-size: 12px;\">(.*?)<\/span>/", "<sub>$1</sub>", $update_data[$key]);
			}
			if(preg_match("/<u style=\"line-height: 1.5;\">/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<u style=\"line-height: 1.5;\">(.*?)<\/u>/", "$1", $update_data[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/","$1",$update_data[$key]);
			}
			if(preg_match("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/",$update_data[$key]))
			{
				$update_data[$key] = preg_replace("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/","$1",$update_data[$key]);
			}
			if(preg_match("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/",$update_data[$key]))
			{
				$update_data[$key]= preg_replace("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/","",$update_data[$key]);
			}
			$update_data[$key] = preg_replace("/<p><u><\/u><\/p>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<div(?:.|[\r\n])*?>/","<div>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<a(?:.|[\r\n])*?>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/a>/","", $update_data[$key]);
			/*
			$update_data[$key] = preg_replace("/<font(?:.|[\r\n])*?>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/font>\"/", "", $update_data[$key]);
			*/
			$update_data[$key] = preg_replace("/<p(?:.|[\r\n])*?>/","<p>", $update_data[$key]);
			$update_data[$key] = preg_replace("/<o:p>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<\/o:p>/","", $update_data[$key]);
			$update_data[$key] = preg_replace("/<p><\/span><\/p>/", "", $update_data[$key]);
			$update_data[$key] = preg_replace("/<p><\/p>/", "", $update_data[$key]);
			//增加关键词标签
			$TextProcessingEngine = A('Home/TextProcessingEngine');
			$update_data[$key] = $TextProcessingEngine->analyseText($update_data[$key]);
		}
		$save_info = M("zhuyuan_bingchengjilushouci")->where("zhuyuan_id like '$zhuyuan_id'")->save($update_data);
		$zhuyuan_bingchengjilu = M("zhuyuan_bingchengjilu")->where("zhuyuan_id like '$zhuyuan_id'")->select();
		foreach($zhuyuan_bingchengjilu as $one)
		{
			while(true)
			{
				$temp_div_content = $one["content"];
				$one["content"] = preg_replace("/<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)<\/span>/","$1",$one["content"]);
				if($temp_div_content==$one["content"])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $one["content"];
				$one["content"] = preg_replace("/<span(?:[^<])*?\>(((?!<\/span>).)*<span id=\"formula\"><span id=\"fenshi\"><span id=\"before\">((?!<\/span>).)*<\/span><span id=\"up\">((?!<\/span>).)*<\/span><span id=\"spliter\">((?!<\/span>).)*<\/span><span id=\"down\">((?!<\/span>).)*<\/span><span id=\"after\">((?!<\/span>).)*<\/span><\/span><\/span>.*)<\/span>/","$1",$one["content"]);
				if($temp_div_content == $one["content"])
				{
					break;
				}
			}
			while(true)
			{
				$temp_div_content = $one["content"];
				$one["content"] = preg_replace("/<b><\/b>/","",$one["content"]);
				$one["content"] = preg_replace("/<p><\/p>/","",$one["content"]);
				if($temp_div_content == $one["content"])
				{
					break;
				}
			}
			$one["content"] = preg_replace("/<b>\S*<\/b>\:/", "", $one["content"], 1);
			$one["content"] = preg_replace("/<strong>\S*<\/strong>\:/", "", $one["content"], 1);
			//去除所有的空白p标签
			$one["content"] = preg_replace("/<p><br><\/p>/", "", $one["content"], 1);
			$one["content"] = preg_replace("/<br><\/p>/", "</p>", $one["content"], 1);
			$one["content"] = preg_replace("/<p><\/p>/", "", $one["content"], 1);
			$one["content"] = preg_replace("/<div><br><\/div>/", "", $one["content"], 1);
			$one["content"] = preg_replace("/<br><\/div>/", "</div>", $one["content"], 1);
			$one["content"] = preg_replace("/<div><\/div>/", "", $one["content"], 1);
			$one["content"] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px; text-indent: \d{2}px;\">/", "<span>", $one["content"]);
			$one["content"] = preg_replace("/<span style=\"font-size: \d{2}pt\">/", "<span>", $one["content"]);
			$one["content"] = preg_replace("/<span style=\"font-size: \d{2}px; line-height: \d{2}px;\">/", "<span>", $one["content"]);
			$one["content"] = preg_replace("/<span>(.*?)<\/span>/", "$1", $one["content"]);
			//保留上下标的内容;
			if(preg_match("/<span style=\"vertical-align: top; font-size: 12px;(.*?)\">/",$one["content"]))
			{
				$one["content"] = preg_replace("/<span style=\"vertical-align: top; font-size: 12px;\">(.*?)<\/span>/", "<sup>$1</sup>", $one["content"]);
			}
			if(preg_match("/<span style=\"vertical-align: bottom; font-size: 12px;(.*?)\">/",$one["content"]))
			{
				$one["content"] = preg_replace("/<span style=\"vertical-align: bottom; font-size: 12px;\">(.*?)<\/span>/", "<sub>$1</sub>", $one["content"]);
			}
			if(preg_match("/<u style=\"line-height: 1.5;\">/",$one["content"]))
			{
				$one["content"] = preg_replace("/<u style=\"line-height: 1.5;\">(.*?)<\/u>/", "$1", $one["content"]);
			}
			if(preg_match("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/",$one["content"]))
			{
				$one["content"] = preg_replace("/<span style=\"font-size: 12pt;\">(.*?)<\/span>/","$1",$one["content"]);
			}
			if(preg_match("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/",$one["content"]))
			{
				$one["content"] = preg_replace("/<span style=\"font-size: 12pt; line-height: 1.5;\">(.*?)<\/span>/","$1",$one["content"]);
			}
			if(preg_match("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/",$one["content"]))
			{
				$one["content"]= preg_replace("/<span lang=\"EN-US\" style=\"font-size:12.0pt\">/","",$one["content"]);
			}
			$one["content"] = preg_replace("/<p><u><\/u><\/p>/", "", $one["content"]);
			$one["content"] = preg_replace("/<div(?:.|[\r\n])*?>/","<div>", $one["content"]);
			$one["content"] = preg_replace("/<a(?:.|[\r\n])*?>/","", $one["content"]);
			$one["content"] = preg_replace("/<\/a>/","", $one["content"]);
			/*
			$one["content"] = preg_replace("/<font(?:.|[\r\n])*?>/", "", $one["content"]);
			$one["content"] = preg_replace("/<\/font>\"/", "", $one["content"]);
			*/
			$one["content"] = preg_replace("/<p(?:.|[\r\n])*?>/","<p>", $one["content"]);
			$one["content"] = preg_replace("/<o:p>/","", $one["content"]);
			$one["content"] = preg_replace("/<\/o:p>/","", $one["content"]);
			$one["content"] = preg_replace("/<p><\/span><\/p>/", "", $one["content"]);
			$one["content"] = preg_replace("/<p><\/p>/", "", $one["content"]);
			//增加关键词标签
			$TextProcessingEngine = A('Home/TextProcessingEngine');
			$one["content"] = $TextProcessingEngine->analyseText($one["content"]);
			$save_info = M("zhuyuan_bingchengjilu")->save($one);
		}
		echo "已完成".$count."份病历";
	}
	//判断这个住院ID的患者是否存在
	public function huanzheZhuyuanhaoIsNull()
	{
		$zhuyuan_id = $_GET['zhuyuan_id'];
		$zhuyuan_basic_info = M("zhuyuan_basic_info")->field("zhuyuan_id")->where("zhuyuan_id = '$zhuyuan_id' ")->find();
		if(empty($zhuyuan_basic_info))
		{
			echo "no";
		}
	}
	
	public function getAudioFile()
	{
		$zhuyuan_id = $_GET["zhuyuan_id"];
		$label = $_GET["label"];
		$date_time = strtotime("now");
		$date_time_format = date('Y-m-d H:i:s',$date_time);
		if(!file_exists("./Uploads/"))
		{
			mkdir("./Uploads/",0777); 
		}
		$dir = "./Uploads/".$zhuyuan_id."/";
		if(!file_exists($testdir))
		{
			mkdir($dir,0777); 
		}
		$file = $dir.$label.$date_time.".wav";
		$file_url_save = "/tiantan_emr".substr($file,1);
		try{
				$input = file_get_contents("php://input");  //这个是获取请求的InputStream，PHP下的写法
				$file = $dir.$label.$date_time.".wav";
				$handle = fopen($file, 'w');
				fwrite($handle, $input);
				$add_info = array(
					"zhuyuan_id" => $zhuyuan_id,
					"editor_label" => $label,
					"type" => "audio",
					"url" => $file_url_save,
					"record_time" => $date_time_format
				);
				$zhuyuan_bingli_media = M("zhuyuan_bingli_media")->add($add_info);
				if($zhuyuan_bingli_media!==false)
				{
					echo json_encode(array("msg"=>"录音成功"));
					
				}
				else
				{
					echo json_encode(array("msg"=>"录音失败。"));
				}
			}
			catch(Exception $e)
			{
				echo json_encode(array("msg"=>$file_url_save."录音失败".$result.$input));
			}
	}
	
	/////
	public function getOtherDocumentCategory()
	{
		$request_keyword = $_GET['term'];

		$data = M("data_other_documents")->where("category like '%$request_keyword%' group by category")->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"label":"'.$data[$i]["category"].'", "other_info":"'.$data[$i]["category"].'", "desc":"科室名称:'.$data[$i]['category'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"label":"'.$data[$i]["category"].'", "other_info":"'.$data[$i]["category"].'", "desc":"科室名称:'.$data[$i]['category'].'"}';
		echo "]";
	}
	
	/////
	public function getOtherDocumentZhongwenMingcheng()
	{
		$category = $_GET['category'];
		$request_keyword = $_GET['term'];

		$data = M("data_other_documents")->where("category like '%$category%' and (zhongwen_mingcheng like '%$request_keyword%' or other_info like '%$request_keyword%')")->select();
		echo "[";
		for($i=0;$i<sizeof($data)-1;$i++)
		{
			echo '{"id":"'.$data[$i]["id"].'", "category":"'.$data[$i]["category"].'", "zhongwen_mingcheng":"'.$data[$i]["zhongwen_mingcheng"].'","document_type":"'.$data[$i]["document_type"].'", "label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["zhongwen_mingcheng"].'", "desc":"所属科室:'.$data[$i]['category'].'"},';
		}
		if(sizeof($data)>0)
			echo '{"id":"'.$data[$i]["id"].'", "category":"'.$data[$i]["category"].'", "zhongwen_mingcheng":"'.$data[$i]["zhongwen_mingcheng"].'","document_type":"'.$data[$i]["document_type"].'", "label":"'.$data[$i]["zhongwen_mingcheng"].'", "other_info":"'.$data[$i]["zhongwen_mingcheng"].'", "desc":"所属科室:'.$data[$i]['category'].'"}';
		echo "]";
	}
	//左上角患者信息
	public function getPatientInfoJson()
	{
		$zhuyuan_id = $_GET['zhixing_id'];
		$zhuyuan_basic_info = M('zhuyuan_basic_info')->field('zhuangtai,patient_id,zhuyuan_id,ruyuan_qingkuang,hulijibie,bingchuang_hao,ruyuan_riqi_time')->where(" zhuyuan_id='$zhuyuan_id' ")->find();
		$patient_basic_info = M('patient_basic_info')->field('xingming,xingbie,nianling')->where(" patient_id='$zhuyuan_basic_info[patient_id]' ")->find();
		$patient_info=array_merge($patient_basic_info,$zhuyuan_basic_info);

		$patient_info['ruyuan_tianshu'] = ceil((time() -strtotime($zhuyuan_basic_info['ruyuan_riqi_time']))/3600/24)."天";

		$xindianjiance = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='$zhuyuan_id' and content like '%病危%' and state='开始执行'  ")->select();
		if(!empty($xindianjiance))
			$patient_info['bingwei']='bingwei';
		$xindianjiance = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='$zhuyuan_id' and content like '%病重%' and state='开始执行'  ")->select();
		if(!empty($xindianjiance))
			$patient_info['bingzhong']='bingzhong';
		$xindianjiance = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='$zhuyuan_id' and content like '%心电监护%' and state='开始执行' ")->select();
		if(!empty($xindianjiance))
			$patient_info['xindianjiance']='xindianjiance';
		$xindianjiance = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='$zhuyuan_id' and content like '%微量泵%' and state='开始执行'  ")->select();
		if(!empty($xindianjiance))
			$patient_info['weiliangbeng']='weiliangbeng';
		$xindianjiance = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='$zhuyuan_id' and content like '%呼吸机%' and state='开始执行'  ")->select();
		if(!empty($xindianjiance))
			$patient_info['huxiji']='huxiji';
		$touxiang_query_result = M("zhuyuan_bingli_media")->field("url, zhuyuan_id")->where(" zhuyuan_id='".$zhuyuan_id."' and editor_label = 'touxiang' and type='image' ")->order("id desc")->find();
		if(!empty($touxiang_query_result))
			$patient_info["touxiang_url"]=$touxiang_query_result["url"];

		$media_photo_query = M("zhuyuan_bingli_media")->where(" zhuyuan_id='".$zhuyuan_id."' and editor_label = 'jiaojieban' and type = 'audio'")->find();
		if(!empty($media_photo_query))
			$patient_info["media_photo_icon"]="true";

		$media_record_query = M("zhuyuan_bingli_media")->where(" zhuyuan_id='".$zhuyuan_id."' and editor_label = 'common' and type = 'audio'")->find();
		if(!empty($media_record_query))
			$patient_info["media_record"]="true";

		$media_picture =M("zhuyuan_bingli_media")->where(" zhuyuan_id='".$zhuyuan_id."' and type = 'image'")->find();
		if(!empty($media_picture)){
			$patient_info["media_picture"]="true";
		}

		echo json_encode($patient_info);
	}

	//时间轴视图
	public function timeView() 
	{
		$time_distance= $_SESSION['time_distance'];
		if(empty($time_distance))
			$time_distance=3600*12;
		$result_array =array();
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			echo json_encode($result_array);		
		//获取住院基本信息
		$zhuyuan_basic_info = M('zhuyuan_basic_info')->where(" zhuyuan_id='$zhuyuan_id' ")->find();
		$ruyuan_riqi_time = strtotime($zhuyuan_basic_info['ruyuan_riqi_time']);
		$chuyuan_riqi_time = strtotime($zhuyuan_basic_info['chuyuan_riqi_time']);
		if(empty($chuyuan_riqi_time))
			$chuyuan_riqi_time=time();
		//时间轴html信息
		$ruyuan_riqi_xiaoshi = substr($zhuyuan_basic_info['ruyuan_riqi_time'],11,2);

		//0 6 12 18
		$riqi = substr($zhuyuan_basic_info['ruyuan_riqi_time'],0,10);
		$shijianjiedian=array('0','12');
		if($ruyuan_riqi_xiaoshi>$shijianjiedian[0] and $ruyuan_riqi_xiaoshi<$shijianjiedian[1])
		{
			$time = $shijianjiedian[0].':00:00';
		}
		else
		{
			$time = $shijianjiedian[1].':00:00';
		}
		
		$kaishishijian=$riqi.' '.$time;
		$shijian_html='<span class=\'shijianwidth_shou\'>'.$riqi.'<br>'.$time.'</span>';
		$shijan_num = ceil(($chuyuan_riqi_time-$ruyuan_riqi_time)/$time_distance)+2;
		$shijian_html.='<div class="kuadu"><span class="kuadu_shijian" name="ri">6</span><span class="kuadu_shijian" name="moren">12</span><span class="kuadu_shijian" name="zhou">18</span><span class="kuadu_shijian" name="yue">24</span></div>';
		for ($i=1;$i<=$shijan_num;$i++)
		{
		
			$time = strtotime($kaishishijian)+$i*$time_distance;

			if($i<$shijan_num)
				$shijian_html.='<span class=\'shijianwidth\'>'.date('Y-m-d',$time).'<br>'.date('H:i:s',$time).'</span>';		
		}
		$result_array['shijian_html']=$shijian_html;
		$result_array['width']=$shijan_num*100+600;
		echo json_encode($result_array);
	}

	//获取时间轴视图的住院事件信息
	public function getZhuyuanEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='130118-3';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);
		
		$result_array[$result_count]["x_pos"] = (strtotime($Starttime_Onepx['ruyuan_riqi_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
		$result_array[$result_count]["y_pos"] = 15;
		foreach ($result_array as $one_result ) 
		{
			if(abs($one_result["x_pos"]-$result_array[$result_count]["x_pos"])<20&&($result_array[$result_count]["des"]!==$one_resul["des"]))
				$result_array[$result_count]["y_pos"] += 10;
		}
		$result_array[$result_count]["des"] = "入院:".$Starttime_Onepx['ruyuan_riqi_time'];
		$result_array[$result_count]["keyword"] = "事件:入院";
		$result_count++;
		
		//判断是不是出院  zhuyuan_history
		$zhuyuan_zhuangtai = M("patient_basic_info")->field('zhuangtai')->where("zhuyuan_history like `%".$zhuyuan_id."%` ")->find();
		if($zhuyuan_zhuangtai['zhuangtai']=='已出院')
		{
			$result_array[$result_count]["x_pos"] = (strtotime($Starttime_Onepx['chuyuan_riqi_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["y_pos"] = 15;
			foreach ($result_array as $one_result ) 
			{
				if(abs($one_result["x_pos"]-$result_array[$result_count]["x_pos"])<20&&($result_array[$result_count]["des"]!==$one_resul["des"]))
					$result_array[$result_count]["y_pos"] += 10;
			}
			$result_array[$result_count]["des"] = "出院:".$Starttime_Onepx['chuyuan_riqi_time'];
			$result_array[$result_count]["keyword"] = "事件:出院";
			$result_count++;	
		}

		
		
		$shoushu = M('zhiliao_shoushu')->where(" zhixing_id='".$zhuyuan_id."' ")->select();

		foreach ($shoushu as $key => $val ) 
		{

			$result_array[$result_count]["x_pos"] = (strtotime($val['shoushu_kaishi_riqi'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["y_pos"] = 15;
			foreach ($result_array as $one_result ) 
			{
				if(abs($one_result["x_pos"]-$result_array[$result_count]["x_pos"])<200&&($result_array[$result_count]["des"]!=$val['shoushu_mingcheng']))
					$result_array[$result_count]["y_pos"] += 10;
			}
			$result_array[$result_count]["des"] = $val['shoushu_mingcheng'];
			$result_array[$result_count]["keyword"] = "事件:".$val['shoushu_mingcheng'];
			$result_count++;
		}
		
		echo json_encode($result_array);
	}


	//获取时间轴视图的用药信息
	public function getYongyaoEventforTimeView() 
	{

		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='39401';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);
		$yongyao_linshi = M('zhuyuan_yizhu_linshi')->where(" zhuyuan_id='".$zhuyuan_id."' and state='执行完毕' and (yongfa_type='中草药' or yongfa_type='输液' or yongfa_type='西药中成药' or yongfa_type='西药' or yongfa_type='口服')")->group('zuhao')->select();
		$yongyao=array();
		
		$i=0;
		foreach ($yongyao_linshi as $key => $val ) 
		{
			$zuyao_list = M('zhuyuan_yizhu_linshi')->where(" zuhao='".$val['zuhao']."'")->select();
			$yongyao[$i]['start_time']=$zuyao_list[0]['xiada_time'];
			$yongyao[$i]['type']='linshi';
			$yongyao[$i]['state']='zhixingwanbi';
			if(empty($zuyao_list[0]['zhixing_time']))
				$yongyao[$i]['stop_time']=$zuyao_list[0]['xiada_time'];
			else		
				$yongyao[$i]['stop_time']=$zuyao_list[0]['zhixing_time'];
			if(count($zuyao_list)=='1')
			{
				$yongyao[$i]['content']=$zuyao_list[0]['content'];
			}
			else
			{
				$j=1;
				$des_str = '';
				foreach ($zuyao_list as $one_zuyao_list ) 
				{
					if($j==1)
						$des_str.= "[".$one_zuyao_list['content'].'';
					else if($j==count($zuyao_list))
						$des_str .= ' , '.$one_zuyao_list['content'].']';
					else
						$des_str .= ' , '.$one_zuyao_list['content'].'';
					$j++;
				}
				$yongyao[$i]['content'] =$des_str;
			}
			$i++;
		}

	
		$yongyao_changqi = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='".$zhuyuan_id."' and (yongfa_type='中草药' or yongfa_type='输液' or yongfa_type='西药中成药' or yongfa_type='西药' or yongfa_type='口服') and (state='停止执行' or state='开始执行') ")-> group('zuhao')->select();


		//$yongyao_changqi='';
		foreach ($yongyao_changqi as $key => $val ) 
		{
			$zuyao_list = M('zhuyuan_yizhu_changqi')->where(" zuhao='".$val['zuhao']."'")->select();
			$yongyao[$i]['start_time']=$zuyao_list[0]['start_time'];
			$yongyao[$i]['type']='changqi';
			if(empty($zuyao_list[0]['stop_time']))
			{
				$yongyao[$i]['stop_time']=date('Y-m-d H:i');
				$yongyao[$i]['state']='zhixingzhong';
			}
			else
			{
				$yongyao[$i]['stop_time']=$zuyao_list[0]['stop_time'];
				$yongyao[$i]['state']='zhixingwanbi';
			}
			if(count($zuyao_list)=='1')
			{
				$yongyao[$i]['content']=$zuyao_list[0]['content'];
			}
			else
			{
				$j=1;
				$des_str = '';
				foreach ($zuyao_list as $one_zuyao_list ) 
				{
					if($j==1)
						$des_str.= "[".$one_zuyao_list['content'].'';
					else if($j==count($zuyao_list))
						$des_str .= ' , '.$one_zuyao_list['content'].']';
					else
						$des_str .= ' , '.$one_zuyao_list['content'].'';
					$j++;
				}
				
				$yongyao[$i]['content'] =$des_str;
			}
			$i++;
		}
		

		
		$yongyao = $this->arraySortReset($yongyao,'start_time');
	
		foreach ($yongyao as $key => $val ) 
		{
			
			$result_array[$result_count]["y_pos"]=15;
			$result_array[$result_count]["x_pos_k"] = (strtotime($val['start_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["x_pos_j"] = (strtotime($val['stop_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["des"] =$val['content'];
			$result_array[$result_count]["keyword"] = "用药:".$val['content'];
			$result_array[$result_count]["type"] =$val['type'];
			$result_array[$result_count]["state"] =$val['state'];
			$result_count++;
		}
		
		for ($i=0;$i<count($result_array);$i++) 
		{
			if($i!=0)
			{
				$prelength = strlen($result_array[$i-1]['des'])*35/3;
				if($result_array[$i]["x_pos_k"]-($result_array[$i-1]['x_pos_j']+$prelength)<=0)
					$result_array[$i]["y_pos"] = $result_array[$i-1]["y_pos"]+20;
			}
			
		}

		echo json_encode($result_array);
	}
	//获取时间轴视图的辅助检查信息
	public function getFuzhujianchaEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='39401';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);
		$fuzhujiancha = M('zhuyuan_fuzhujiancha')->where(" zhuyuan_id='".$zhuyuan_id."' ")->select();
		for ($i=0;$i<count($fuzhujiancha);$i++) 
		{
			if(empty($fuzhujiancha[$i]['jiancha_time']))
				$fuzhujiancha[$i]['jiancha_time']=$fuzhujiancha[$i]['shenqing_time'];
		}

		$fuzhujiancha=$this->arraySortReset($fuzhujiancha,'jiancha_time');
		
		foreach ($fuzhujiancha as $key => $val ) 
		{
			$jianchan_time=trim($val['jiancha_time']);
			if(empty($jianchan_time))
				$jianchan_time=$val['shenqing_time'];
				//echo$jianchan_time=$val['songjian_time'];
			//判断检查时间是否小于入院时间
			$zhuyuan_basic_info = M('zhuyuan_basic_info')->where(" zhuyuan_id='".$zhuyuan_id."' ")->find();
			if($jianchan_time < $zhuyuan_basic_info['ruyuan_riqi_time'])
			{
				$jianchan_time_new = $zhuyuan_basic_info['ruyuan_riqi_time'];
			}
			else
			{
				$jianchan_time_new = $jianchan_time;
			}
			$result_array[$result_count]["y_pos"] = 15;	
			$result_array[$result_count]["x_pos"] = (strtotime($jianchan_time_new)-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["des"] =$val['jiancha_mingcheng'];
			$result_array[$result_count]["keyword"] = "辅助检查:".$val['jiancha_mingcheng']." ".date("Y-m-d H:i",strtotime($jianchan_time));
			$result_array[$result_count]["time"]=$jianchan_time;
			$result_array[$result_count]["x_pos_jieshu"]=$result_array[$result_count]["x_pos"]+strlen($result_array[$result_count]["des"])*35/3;
			foreach ($result_array as $one_result )
			{
				$next_length = strlen($result_array[$result_count]["des"])*20/3;
				$last_length = strlen($one_result["des"])*20/3;
				if($result_array[$result_count]["x_pos"]-$one_result["x_pos"]>=0&&$result_array[$result_count]["x_pos"]-$one_result["x_pos"]<$last_length&&($result_array[$result_count]["des"]!==$one_result["des"]))
					$result_array[$result_count]["y_pos"] += 14;
			}
			$result_count++;
		}
		for ($i=1;$i<count($result_array);$i++) 
		{
			for ($j=0;$j<$i;$j++) 
			{
				if(($result_array[$i]['y_pos']=$result_array[$j]['y_pos']) and ($result_array[$i]['x_pos']-$result_array[$j]['x_pos_jieshu']<=0))
					$result_array[$i]['y_pos']+=14;	
				else
					$result_array[$i]['y_pos']=14;
			}
		
		}
		echo json_encode($result_array);
	}
	//获取时间轴视图的护理记录信息
	public function getHulijiluEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='39401';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);
		$huli_linshi = M('zhuyuan_yizhu_linshi')->where(" zhuyuan_id='".$zhuyuan_id."' and yongfa_type='护理' and state='执行完毕' ")->select();
		$huli=array();
		$i=0;
		foreach ($huli_linshi as $key => $val ) 
		{
			$huli[$i]['start_time']=$val['zhixing_time'];
			$huli[$i]['stop_time']=$val['zhixing_time'];			
			$huli[$i]['content']=$val['content'];
			$i++;
		}
	
		$huli_changqi = M('zhuyuan_yizhu_changqi')->where(" zhuyuan_id='".$zhuyuan_id."' and yongfa_type='护理'")->select();
	
		foreach ($huli_changqi as $key => $val ) 
		{
			$huli[$i]['start_time']=$val['start_time'];
			if(empty($val['stop_time']))
			{
				$huli[$i]['stop_time']=date("Y-m-d H:i");
				$huli[$i]['state']='zhixingzhong';
			}
			else
			{
				$huli[$i]['stop_time']=$val['stop_time'];
				$huli[$i]['state']='zhixingwanbi';
			}
			$huli[$i]['content']=$val['content'];
			$i++;
		}
		foreach ($huli as $key => $val ) 
		{
			
			$result_array[$result_count]["y_pos"]=15;
			$result_array[$result_count]["x_pos_k"] = (strtotime($val['start_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["x_pos_j"] = (strtotime($val['stop_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["state"] =$val['state'];
			$result_array[$result_count]["des"] =$val['content'];
			$result_array[$result_count]["keyword"] = "护理:".$val['content'];
				
			$result_count++;
		}
		
		for ($i=0;$i<count($result_array);$i++) 
		{
			if($i!=0)
			{
				$prelength = strlen($result_array[$i-1]['des'])*14/3;
				if($result_array[$i]["x_pos_k"]-($result_array[$i-1]['x_pos_j']+$prelength)<=0)
					$result_array[$i]["y_pos"] = $result_array[$i-1]["y_pos"]+20;
			}
		}
		echo json_encode($result_array);
	}
	//获取时间轴视图的病历记录信息
	public function getBinglijiluEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='130118-3';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);

		//判断是不是出院  zhuyuan_history
		$zhuyuan_zhuangtai = M("patient_basic_info")->field('zhuangtai')->where("zhuyuan_history like `%".$zhuyuan_id."%` ")->find();
		
		$ruyuanjilu = M('zhuyuan_bingshi')->where(" zhuyuan_id='".$zhuyuan_id."' ")->find();
		$bingshicaiji_riqi_time = date("Y-m-d H:i",strtotime($ruyuanjilu['bingshicaiji_riqi_time']));
		$binglijilu[]=array('time'=>$bingshicaiji_riqi_time,'content'=>'入院记录');


		if($zhuyuan_zhuangtai['zhuangtai']=='已出院')
		{
		$chuyuan_riqi_time = date("Y-m-d H:i",strtotime($Starttime_Onepx['chuyuan_riqi_time']));
		$binglijilu[]=array('time'=>$chuyuan_riqi_time,'content'=>'出院记录');
		}
		$shoucheng = M('zhuyuan_bingchengjilushouci')->where(" zhuyuan_id='".$zhuyuan_id."' ")->find();
		
		$bingchengjilushouci_time = date("Y-m-d H:i",strtotime($shoucheng['record_time']));
		$binglijilu[]=array('time'=>$bingchengjilushouci_time,'content'=>'首次病程记录');

		$bigncheng = M('zhuyuan_bingchengjilu')->where(" zhuyuan_id='".$zhuyuan_id."' ")->select();


		
		foreach ($bigncheng as $key => $val ) 
		{
			$bingcheng_time = date("Y-m-d H:i",strtotime($val['record_time']));
			$binglijilu[]=array('time'=>$bingcheng_time,'content'=>$val['bingcheng_sub_leibie']);
		}
		
		$binglijilu = $this->arraySort($binglijilu,'time');
		foreach ($binglijilu as $key => $val ) 
		{
			$result_array[$result_count]["y_pos"] = 15;	
			$result_array[$result_count]["x_pos"] = (strtotime($val['time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["des"] =$val['content'];
			$result_array[$result_count]["keyword"] = "病历:".$val['content']." ".$val['time'];
			$result_count++;
		}
		
		for ($i=0;$i<count($result_array);$i++) 
		{
			if($i!=0)
			{
				$prelength = strlen($result_array[$i-1]['des'])*14/3;
				if($result_array[$i]["x_pos"]-($result_array[$i-1]['x_pos']+$prelength)<=0)
					$result_array[$i]["y_pos"] = $result_array[$i-1]["y_pos"]+14;
			}
		}
		echo json_encode($result_array);
	}
	//获取时间轴视图的体温信息
	public function getTiwenEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='130118-3';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);
		$tiwen = M('zhuyuan_tizheng')->where(" zhuyuan_id='".$zhuyuan_id."' and jiancha_type='体温' ")->order('jiancha_time asc')->select();
		foreach ($tiwen as $key => $val ) 
		{
			$result_array[$result_count]["y_pos"] = 60-($val['jiancha_value']-34)*8;
			$result_array[$result_count]["x_pos"] = (strtotime($val['jiancha_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["val"] = $val['jiancha_value'];
			$result_array[$result_count]["des"] = $val['jiancha_value'];
			$result_array[$result_count]["keyword"] = "none";
			$result_count++;
		}		
		echo json_encode($result_array);
	}
	//获取时间轴视图的脉搏信息
	public function getXinlvEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='130118-3';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);

		$tiwen = M('zhuyuan_tizheng')->where(" zhuyuan_id='".$zhuyuan_id."' and jiancha_type='脉搏' ")->order('jiancha_time asc')->select();
		foreach ($tiwen as $key => $val ) 
		{
			$result_array[$result_count]["y_pos"] = 60-($val['jiancha_value']-55);
			$result_array[$result_count]["x_pos"] = (strtotime($val['jiancha_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["val"] = $val['jiancha_value'];
			$result_array[$result_count]["des"] = $val['jiancha_value'];
			$result_array[$result_count]["keyword"] = "none";
			$result_count++;
		}
		
		echo json_encode($result_array);
	}
	//获取时间轴视图的血压信息
	public function getXueyaEventforTimeView() 
	{
		$result_array =array();
		$result_count = 0;
		$zhuyuan_id = $_POST['zhuyuan_id'];
		if(empty($zhuyuan_id))
			$zhuyuan_id='130118-3';
		$Starttime_Onepx = $this->getStarttimeOnepx($zhuyuan_id);

		$xueya = M('zhuyuan_tizheng')->where(" zhuyuan_id='".$zhuyuan_id."' and jiancha_fangshi='收缩压/舒张压' ")->select();
		
		$one_px=10;
		foreach ($xueya as $key => $val ) 
		{
			$ssy_szy=explode('/',$val['jiancha_value']);
			$result_array[$result_count]["y_pos_s"] = 30-$ssy_szy[0]/$one_px;	
			$result_array[$result_count]["y_pos_x"] = 30+$ssy_szy[1]/$one_px;	
			$result_array[$result_count]["x_pos"] = (strtotime($val['jiancha_time'])-$Starttime_Onepx['start_time'])*$Starttime_Onepx['one_second_pix'];
			$result_array[$result_count]["val"] = $ssy_szy[0];
			$result_array[$result_count]["des"] = $ssy_szy[1];
			$result_array[$result_count]["keyword"] = "none";
			$result_count++;
		}
		echo json_encode($result_array);
	}
	//设置时间跨度
	public function SetKuadu() 
	{
		echo $type = $_POST['type'];
		if($type=='ri')
		{
			$_SESSION['time_distance']=6*3600;
		}
		else if ($type=='zhou')
		{
			$_SESSION['time_distance']=18*3600;
		}
		else if ($type=='yue')
		{
			$_SESSION['time_distance']=24*3600;
		}
		else
		{
			$_SESSION['time_distance']=12*3600;
		}
	}
	//返回时间轴开始时间和每秒代表多少像素
	public function getStarttimeOnepx($zhuyuan_id) 
	{
		//设置日,周,月
		$time_distance = $_SESSION['time_distance'];
		if(empty($time_distance))
			$time_distance= 3600*12;

		//获取每像素多少秒
		$one_second_pix = 100/$time_distance;
		//获取入院 出院事件基本信息
		$zhuyuan_basic_info = M('zhuyuan_basic_info')->where(" zhuyuan_id='$zhuyuan_id' ")->find();

		//获取入院和出院时间
		$ruyuan_riqi_time = $zhuyuan_basic_info['ruyuan_riqi_time'];

		if(empty($zhuyuan_basic_info['chuyuan_riqi_time']))
			$chuyuan_riqi_time=date('Y-m-d H:i:s');
		else
			$chuyuan_riqi_time = $zhuyuan_basic_info['chuyuan_riqi_time'];


		
		$ruyuan_riqi_xiaoshi = substr($ruyuan_riqi_time,11,2);
		$riqi = substr($ruyuan_riqi_time,0,10);

		$shijianjiedian=array('0','12');
		if($ruyuan_riqi_xiaoshi>$shijianjiedian[0] and $ruyuan_riqi_xiaoshi<$shijianjiedian[1])
			$time = $shijianjiedian[0].'00:00';
		else
			$time = $shijianjiedian[1].'00:00';

		$kaishishijian=$riqi.' 00:00';
		$arr['time_distance']=$time_distance;
		$arr['start_time']= strtotime($kaishishijian);
		$arr['one_second_pix']= $one_second_pix ;
		$arr['chuyuan_riqi_time']= $chuyuan_riqi_time ;
		$arr['ruyuan_riqi_time']= $ruyuan_riqi_time ;
		return $arr;
	}
	/*
	*数组排序Key不变
	*/
	public function arraySort($arr,$keys,$type='asc')
	{
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v)
		{
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc')
		{
			asort($keysvalue);
		}
		else
		{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v)
		{
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}

	/*
	*数组排序Key重新排序
	*/
	public function arraySortReset($arr,$keys,$type='asc')
	{
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v)
		{
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc')
		{
			asort($keysvalue);
		}
		else
		{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v)
		{
			$new_array[$k] = $arr[$k];
		}
		while ($value = current($new_array)) 
		{
			$new_array_tmp[] = $value;
			next($new_array);	
		}
		return $new_array_tmp;
	}
	public function photoBianqian() 
	{
		echo "true";
	}
}

