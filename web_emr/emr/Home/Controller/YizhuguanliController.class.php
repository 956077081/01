<?php

namespace Home\Controller;
use Components\TemrController;

class YizhuguanliController extends TemrController{
   
    public function _empty(){
        echo '<meta charset=utf-8 />';
        echo "<h1>",'非法操作....',"<h1>";
    }
   public function showChangqi($mode = "whole_mode")
	{
     
     
		//获取医院ID
		if(!empty($_GET['yiyuan_id']))
		{
			$yiyuan_id = $_GET['yiyuan_id'];
		}
		if(empty($yiyuan_id))
		{
			$yiyuan_id = $_SESSION['yiyuan_id'];
		}
		if(empty($yiyuan_id))
		{
			$yiyuan_id = "%";
		}
		if((empty($_GET['zhuyuan_id'])||$_GET['zhuyuan_id']==" ")&&(empty($_GET['muqin_zhuyuan_id'])||$_GET['muqin_zhuyuan_id']==" "))
		{
				$this->assign('system_info','请输入正确的住院号');
				$this->display("System:showError");
				exit();
		}
		if(!empty($_GET['zhuyuan_id']))
		{
			$user_account = M("yiyuan_user")->where("user_id='".$_SESSION["user_id"]."'")->select();
			$supervisor_authority = "false";
		
			//没用
			/*if(strpos($user_account[0]["user_skill"],"修改医嘱")!==false)
			{
				$supervisor_authority = "true";
			}*/
			$this->assign("supervisor_authority",$supervisor_authority);
			$zhuyuan_id = $_GET["zhuyuan_id"];
			//获取病人基本信息:
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id' and yiyuan_id like '$yiyuan_id'")->select(); 
			if(empty($zhuyuan_basic_info))
			{
				$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_unique_code like '$zhuyuan_id'  and yiyuan_id like '$yiyuan_id'")->select(); 
			}
			$zhuyuan_unique_code = $zhuyuan_basic_info[0]["zhuyuan_unique_code"];
				 
			if(empty($zhuyuan_basic_info))
			{	
					//没用
					/*//再去尝试查询新生儿信息：
					$zhuyuan_basic_info = M("zhuyuan_xinshenger_info")->where("zhuyuan_id like '$zhuyuan_id' ")->select();

					//获取母亲信息
					$muqin_basic_info = M("zhuyuan_basic_info")->field('zhuyuan_department,zhuyuan_chuanghao,special_info')->where("zhuyuan_id like '".$zhuyuan_basic_info[0]['muqin_zhuyuan_id']."'  and yiyuan_id like '$yiyuan_id'")->select(); 
					if($zhuyuan_basic_info==false)
					{*/
							$this->assign('system_info','这个住院患者不存在，请查询后重新尝试');
							$this->display("System:showError");
						
					/* }
					$zhuyuan_id = $zhuyuan_basic_info[0]["zhuyuan_id"];

					// 第几个孩子
					$children_number = substr($zhuyuan_id, strlen($zhuyuan_id)-1);

					$zhuyuan_basic_info[0]["xingming"] = $zhuyuan_basic_info[0]["muqin_xingming"]."之".$children_number.$zhuyuan_basic_info[0]["xingbie"];
					$zhuyuan_basic_info[0]["xingming"] = str_replace("男","子",$zhuyuan_basic_info[0]["xingming"]);
					$zhuyuan_basic_info[0]["xingming"] = str_replace("女","女",$zhuyuan_basic_info[0]["xingming"]);

					if($muqin_basic_info[0]["special_info"] == "孕妇")
					{
						$zhuyuan_basic_info[0]["xingming"] = str_replace("1","",$zhuyuan_basic_info[0]["xingming"]);
					}
					else if($muqin_basic_info[0]["special_info"] == "双胞胎孕妇"||$muqin_basic_info[0]["special_info"] == "多胞胎孕妇")
					{
						$zhuyuan_basic_info[0]["xingming"] = str_replace("1","长",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("2","次",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("3","三",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("4","四",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("5","五",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("6","六",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("7","七",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("8","八",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("9","九",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("10","十",$zhuyuan_basic_info[0]["xingming"]);
					}
					else
					{
						// TODO
					}
					
					$zhuyuan_basic_info[0]["zhuyuan_department"] = $muqin_basic_info[0]['zhuyuan_department'];		
						
				
					$shijian = time();
					$shengri = strtotime($zhuyuan_basic_info[0]['fenmian_shijian']);
					if(!empty($shengri))
					{
						$riling = ceil(($shijian-$shengri)/86400)."天";
					}
					else
					{
						$riling = "未知";
					}
					$zhuyuan_basic_info[0]["nianling"] = $riling;	
					$this->assign('riling',$riling);

					$this->assign('muqin_bingchuang_hao',$muqin_basic_info[0]["zhuyuan_chuanghao"]);
					$this->assign('zhuyuan_basic_info',$zhuyuan_basic_info[0]);
					$this->assign('patient_basic_info',$zhuyuan_basic_info[0]);
			*/}
			else
			{   
				$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$zhuyuan_basic_info[0]["patient_id"]."' ")->select();
				
				$this->assign('bingchuang_hao',$zhuyuan_basic_info[0]["zhuyuan_chuanghao"]);
				$this->assign('zhuyuan_basic_info',$zhuyuan_basic_info[0]);
				$this->assign('patient_basic_info',$patient_basic_info[0]);
			}
				
		}
	
		/*//新生儿信息：
		else if(!empty($_GET['muqin_zhuyuan_id']))
		{
			$muqin_zhuyuan_id = $_GET["muqin_zhuyuan_id"];
			$zhuyuan_basic_info = M("zhuyuan_xinshenger_info")->where("muqin_zhuyuan_id like '$muqin_zhuyuan_id' ")->select();
			if($zhuyuan_basic_info==false)
			{
					$this->assign('system_info','这个住院患者不存在，请查询后重新尝试');
					$this->display("System:showError");
					exit();
			}
			$zhuyuan_id = $zhuyuan_basic_info[0]["zhuyuan_id"];
			$zhuyuan_basic_info[0]["xingming"] = $zhuyuan_basic_info[0]["muqin_xingming"]."之".$zhuyuan_basic_info[0]["xingbie"];
			$zhuyuan_basic_info[0]["xingming"] = str_replace("男","子",$zhuyuan_basic_info[0]["xingming"]);
			$zhuyuan_basic_info[0]["xingming"] = str_replace("女","女",$zhuyuan_basic_info[0]["xingming"]);
			$this->assign('bingchuang_hao',$zhuyuan_basic_info[0]["xiuyangshi"]);
			$this->assign('zhuyuan_basic_info',$zhuyuan_basic_info[0]);
			$this->assign('patient_basic_info',$zhuyuan_basic_info[0]);
			
		}
		else
		{
			$this->assign('system_info','这个住院患者不存在，请查询后重新尝试');
			$this->display("System:showError");
			exit();
		}
          */      
             

		$yishi_name = $_SESSION["user_name"];
		
		if(empty($_GET['state'])||$_GET['state']==" "||$_GET['state']=="all")
		{
			$state = "%";
                     
		}
		else
		{        
			$temp_state = explode("_",$_GET['state']);
                      
			if(count($temp_state) == 1)
			{
				$state = $_GET['state'];
			}
		}
		
		if(empty($_GET['page'])||$_GET['page']==" ")
		{
			$page = "1";
		}
		else
		{
			$page = $_GET['page'];
		}
	
		if($_SESSION["agent_type"]=="pad")
		{
			$page_number = 50;
		}
		else
			$page_number = 17;

		//获取当前病人的所有医嘱
		if(count($temp_state) == 2)
		{
			$zongtiaoshu = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_unique_code' and (state like '$temp_state[0]' or state like '$temp_state[1]') and state != '已删除'")->order("start_time asc")->count();
		}
		else
		{
			$zongtiaoshu = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_unique_code' and state like '$state' and state != '已删除' ")->order("start_time asc")->count();
		}
	
	$yeshu = ceil($zongtiaoshu/$page_number);
		if(count($temp_state) == 2)
		{
			$changqi_yizhu_temp = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_unique_code' and (state like '$temp_state[0]' or state like '$temp_state[1]') and state != '已删除' ")->order("start_time asc,cast(zuhao as signed) asc,id asc")->page($page.",".$page_number)->select();
		}
		else
		{
			$changqi_yizhu_temp = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_unique_code' and state like '$state' and state != '已删除' ")->order("start_time asc,cast(zuhao as signed) asc,id asc")->page($page.",".$page_number)->select();
		}
               
          
		if(count($changqi_yizhu_temp)>=1)
		{
			for($count=0;$count<count($changqi_yizhu_temp);$count++)
			{
				$changqi_yizhu[$count] = $changqi_yizhu_temp[$count];
				//判断医嘱长度
				$content_str_length = strlen($changqi_yizhu[$count]['content']);
				//判断医嘱在组内位置：
				$zuhe_count = 0;
				$zuhe_number = 0;
				$one_zuhe_changqi_yizhu = M("zhuyuan_yizhu_changqi")->field("id")->where("zuhao like '".$changqi_yizhu_temp[$count]['zuhao']."'")->order("start_time asc,id asc")->select();
                                
                 foreach($one_zuhe_changqi_yizhu as $one_changqi_yizhu)
				{
					$zuhe_count++;
					
					if($one_changqi_yizhu["id"] == $changqi_yizhu_temp[$count]["id"])
					{
						$zuhe_number = $zuhe_count;
					}
				}
                           
				//根据组内位置进行判断
				if($zuhe_number==1 && $zuhe_count>1)
				{
					$changqi_yizhu[$count]['islast'] = 'first';
				}
				else if($zuhe_number == $zuhe_count && $zuhe_number > 1)
				{
					$changqi_yizhu[$count]['islast'] = 'last';
				}
				else if($zuhe_number != $zuhe_count && $zuhe_number > 1)
				{
					$changqi_yizhu[$count]['islast'] = 'middle';
				}
				else
					$changqi_yizhu[$count]['islast'] = 'alone';
				
                                
                          
				if($content_str_length <= 45)
				{
					if($changqi_yizhu[$count]['islast']=='first')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left"><table width="250" border="0"><tr><td style="border:0px;">┏<span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
					else if($changqi_yizhu[$count]['islast']=='middle')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left"><table width="250" border="0"><tr><td style="border:0px;">┃<span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
					else if($changqi_yizhu[$count]['islast']=='last')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left"><table width="250" border="0"><tr><td style="border:0px;">┗<span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
					else
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left"><table width="250" border="0"><tr><td style="border:0px;"><span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
				}
				else
				{
					if($changqi_yizhu[$count]['islast']=='first')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left" style="font-size:11px;"><table width="250" border="0"><tr><td style="border:0px;">┏<span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
					else if($changqi_yizhu[$count]['islast']=='middle')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left" style="font-size:11px;"><table width="250" border="0"><tr><td style="border:0px;">┃<span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
					else if($changqi_yizhu[$count]['islast']=='last')
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left" style="font-size:11px;"><table width="250" border="0"><tr><td style="border:0px;">┗<span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
					else
						$changqi_yizhu[$count]['content_show'] = '<span class="float_left" style="font-size:11px;"><table width="250" border="0"><tr><td style="border:0px;"><span name="content">'.$changqi_yizhu[$count]['content'].'</span></td></tr></table></span>';
				}
				
				//看是否追加显示每次用量
				if($changqi_yizhu[$count]['yongfa_type']=='其它'||$changqi_yizhu[$count]['yongfa_type']=='输液'||$changqi_yizhu[$count]['yongfa_type']=='西药中成药'||$changqi_yizhu[$count]['yongfa_type']=='中草药'||$changqi_yizhu[$count]['yongfa_type']=='药品')
				{
                             
					$changqi_yizhu[$count]['content_show'] =  '<div class="float_left_full_width">'.$changqi_yizhu[$count]['content_show'].'<span class="float_right"><span name="ciliang">'.$changqi_yizhu[$count]['ciliang'].'</span><span name="shiyong_danwei">'.$changqi_yizhu[$count]['shiyong_danwei']."</span></span>";
					
				}
				
				
				//看是否追加显示频率和用法
				if($changqi_yizhu[$count]['yongfa_type']=='诊疗项目'||$changqi_yizhu[$count]['yongfa_type']=='检查项目'||$changqi_yizhu[$count]['yongfa_type']=='输液'||$changqi_yizhu[$count]['yongfa_type']=='西药中成药'||$changqi_yizhu[$count]['yongfa_type']=='中草药'||$changqi_yizhu[$count]['yongfa_type']=='诊疗'||$changqi_yizhu[$count]['yongfa_type']=='药品')
				{	
					if($changqi_yizhu[$count]['islast'] == 'alone'||$changqi_yizhu[$count]['islast'] == 'last')
					{
					
						$changqi_yizhu[$count]['content_show'] =  $changqi_yizhu[$count]['content_show'].'<span class="fix_right">'.$changqi_yizhu[$count]['yongfa'];
						if($changqi_yizhu[$count]['yongfa']!="处置"&&$changqi_yizhu[$count]['yongfa']!="小时计费")
						{	
							$changqi_yizhu[$count]['content_show'] .= "&nbsp&nbsp".$changqi_yizhu[$count]['pinlv']."</div>";                  
						}else{
							
							$changqi_yizhu[$count]['content_show'] .= "</span></div>";
						}
					}
					else
					{
						$changqi_yizhu[$count]['content_show'] .= "</div>";
					}
				}
				
				//如果为医嘱整理信息，就居中显示：
				if($changqi_yizhu[$count]['yongfa_type']=='医嘱整理')
				{
					$changqi_yizhu[$count]['content_show'] = "<div class='yizhuzhengli_info'>".$changqi_yizhu[$count]['content']."</div>";
				}
				
				//设置为双签名格式：
				if($changqi_yizhu[$count]['start_zhiye_yishi_name']!=$changqi_yizhu[$count]['start_yishi_name']&&!empty($changqi_yizhu[$count]['start_zhiye_yishi_name']))
				{
					$changqi_yizhu[$count]['start_yishi_name'] = $changqi_yizhu[$count]['start_yishi_name']."-".$changqi_yizhu[$count]['start_zhiye_yishi_name']; 
				}
				if($changqi_yizhu[$count]['stop_zhiye_yishi_name']!=$changqi_yizhu[$count]['stop_yishi_name']&&!empty($changqi_yizhu[$count]['stop_zhiye_yishi_name']))
				{
					$changqi_yizhu[$count]['stop_yishi_name'] = $changqi_yizhu[$count]['stop_yishi_name']."-".$changqi_yizhu[$count]['stop_zhiye_yishi_name']; 
				}
			}
                        		

		}
		$fenye_count = 0;
		foreach($changqi_yizhu as $fenye)
		{
			
			$changqi_yizhu_fenye[$fenye_count/$page_number][$fenye_count%$page_number] = $fenye;
			$fenye_count++;

		if($mode=="print")
		{
			//补齐分页内容显示为空表格：
			//先创建一条空白的医嘱
//			foreach($changqi_yizhu_fenye[0][0] as $key => $fenye)
//			{
//				//$blank_fenye[$key] = "-";
//			}
			$blank_fenye["islast"] = "true";
			$blank_fenye["start_time"] = "&nbsp<br />&nbsp";
			//$blank_fenye["content_show"] = "-";
			for($last_count=0;$last_count< $page_number;$last_count++)
			{
				if($changqi_yizhu_fenye[$last_count/$page_number][$last_count]==null)
				{
					$changqi_yizhu_fenye[$last_count/$page_number][$last_count] = $blank_fenye;
				}
			}
		}
		}
		
		foreach($changqi_yizhu_fenye as $key => $one)
		{
			$changqi_yizhu_fenye[$key] = $this->signatureProceed($changqi_yizhu_fenye[$key],"start_yishi_name",0);
			$changqi_yizhu_fenye[$key] = $this->signatureProceed($changqi_yizhu_fenye[$key],"start_hushi_name",0);
			$changqi_yizhu_fenye[$key] = $this->signatureProceed($changqi_yizhu_fenye[$key],"stop_yishi_name",0);
			$changqi_yizhu_fenye[$key] = $this->signatureProceed($changqi_yizhu_fenye[$key],"stop_hushi_name",0);
		}
               
		$this->assign('yishi_name',$yishi_name);
		$this->assign('changqi_yizhu',$changqi_yizhu);
		
		$this->assign('changqi_yizhu_fenye',$changqi_yizhu_fenye);
		$this->assign('changqi_yizhu_shuliang',count($changqi_yizhu));
		$this->assign('page',$page);
		$this->assign('yeshu',$yeshu);
		$this->assign('page_tiaoshu',$page_number);
		$this->assign('zongtiaoshu',$zongtiaoshu);
		$this->assign('current_date',date('Y-m-d H:i'));
		if($state=="%")
			$state = "all";
		$this->assign('state',$state);
		
		$last_yizhu_date = M()->query("select start_time from zhuyuan_yizhu_changqi where id = (select max(id) from zhuyuan_yizhu_changqi where zhuyuan_id = '$zhuyuan_id' and state!='已删除')");
		if(count($last_yizhu_date)==0||empty($last_yizhu_date[0]['start_time']))
		{
			$this->assign('last_yizhu_date',date('Y-m-d H:i',time()));
		}
		else
		{
			$this->assign('last_yizhu_date',$last_yizhu_date[0]['start_time']);
		}
		
		//计算总页数：
		if(count($temp_state) == 2)
		{
			$changqi_yizhu_page_amount = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_id' and (state like '$temp_state[0]' or state like '$temp_state[1]') and state != '已删除' ")->count();
		}
		else
		{
			$changqi_yizhu_page_amount = M("zhuyuan_yizhu_changqi")->where("zhuyuan_id like '$zhuyuan_id' and state like '$state' and state != '已删除' ")->count();
		}
		$changqi_yizhu_page_amount = ceil($changqi_yizhu_page_amount/$page_number);
		if($changqi_yizhu_page_amount==0)
			$changqi_yizhu_page_amount = 1;
		$this->assign('page_amount',$changqi_yizhu_page_amount);
			$this->display();
	}
	public function signatureProceed($data,$index,$flag)
	{

		$dir = "./Public/yiyuan_user_qianming/";

		if($flag==1)
		{
			foreach($data as $key => $one)
			{
				$prekey = $key - 1;
				$aftkey = $key + 1;
				$index_print = $index."_print";
				if($data[$key][$index]==$data[$prekey][$index] && $data[$key][$index]!="" && $data[$key][$index]==$data[$aftkey][$index])
				{
					$data[$key][$index_print] = "";
				}
				else
				{
					$data[$key][$index_print] = $data[$key][$index];
				}

				// 如果手摸签名文件不存在则显示为空，不要显示叉子
				$filename=iconv("utf-8","gbk",$data[$key][$index].".jpg"); 
				if(!file_exists($dir.$filename)&&!file_exists($dir.$data[$key][$index].".jpg"))
				{
					$data[$key][$index_print] = "";
				}
			}
		}
		else
		{
			foreach($data as $key => $one)
			{
				$index_print = $index."_print";
				$data[$key][$index_print] = $data[$key][$index];

				$filename=iconv("utf-8","gbk",$data[$key][$index].".jpg");
				if(!file_exists($dir.$filename)&&!file_exists($dir.$data[$key][$index].".jpg"))
				{
					$data[$key][$index_print] = "";
				}
			}
		}
		return $data;
	}
        public function showLinshi($mode = "whole_mode")
	{
		//获取医院ID
		if(!empty($_GET['yiyuan_id']))
		{
			$yiyuan_id = $_GET['yiyuan_id'];
		}
		if(empty($yiyuan_id))
		{
			$yiyuan_id = $_SESSION['yiyuan_id'];
		}
		if(empty($yiyuan_id))
		{
			$yiyuan_id = "%";
		}
		if((empty($_GET['zhuyuan_id'])||$_GET['zhuyuan_id']==" ")&&(empty($_GET['muqin_zhuyuan_id'])||$_GET['muqin_zhuyuan_id']==" "))
		{
				$this->assign('system_info','请输入正确的住院号');
				$this->display("System:showError");
				exit();
		}
		
		if(!empty($_GET['zhuyuan_id']))
		{
			$user_account = M("yiyuan_user")->where("user_id='".$_SESSION["user_id"]."'")->select();
			$supervisor_authority = "false";
			
			$this->assign("supervisor_authority",$supervisor_authority);
			$zhuyuan_id = $_GET["zhuyuan_id"];
			//获取病人基本信息:
			$zhuyuan_basic_info = M("zhuyuan_basic_info")->where("zhuyuan_id like '$zhuyuan_id' and yiyuan_id like '$yiyuan_id'")->select();
			$zhuyuan_unique_code = $zhuyuan_basic_info[0]["zhuyuan_unique_code"];
			if($zhuyuan_basic_info==false)
			{
					/*//再去尝试查询新生儿信息：
					$zhuyuan_basic_info = M("zhuyuan_xinshenger_info")->where("zhuyuan_id like '$zhuyuan_id' ")->select();

					//获取母亲信息
					$muqin_basic_info = M("zhuyuan_basic_info")->field('zhuyuan_department,zhuyuan_chuanghao,special_info')->where("zhuyuan_id like '".$zhuyuan_basic_info[0]['muqin_zhuyuan_id']."' ")->select(); 
					*/
					/*if($zhuyuan_basic_info==false)
					{*/
							$this->assign('system_info','这个住院患者不存在，请查询后重新尝试');
							$this->display("System:showError");
							/*exit();
					}*/
					/*$zhuyuan_id = $zhuyuan_basic_info[0]["zhuyuan_id"];

					// 第几个孩子
					$children_number = substr($zhuyuan_id, strlen($zhuyuan_id)-1);

					$zhuyuan_basic_info[0]["xingming"] = $zhuyuan_basic_info[0]["muqin_xingming"]."之".$children_number.$zhuyuan_basic_info[0]["xingbie"];
					$zhuyuan_basic_info[0]["xingming"] = str_replace("男","子",$zhuyuan_basic_info[0]["xingming"]);
					$zhuyuan_basic_info[0]["xingming"] = str_replace("女","女",$zhuyuan_basic_info[0]["xingming"]);
					*///*
					/*if($muqin_basic_info[0]["special_info"] == "孕妇")
					{
						$zhuyuan_basic_info[0]["xingming"] = str_replace("1","",$zhuyuan_basic_info[0]["xingming"]);
					}
					else if($muqin_basic_info[0]["special_info"] == "双胞胎孕妇"||$muqin_basic_info[0]["special_info"] == "多胞胎孕妇")
					{
						$zhuyuan_basic_info[0]["xingming"] = str_replace("1","长",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("2","次",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("3","三",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("4","四",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("5","五",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("6","六",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("7","七",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("8","八",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("9","九",$zhuyuan_basic_info[0]["xingming"]);
						$zhuyuan_basic_info[0]["xingming"] = str_replace("10","十",$zhuyuan_basic_info[0]["xingming"]);
					}
					else
					{
						// TODO
					}
						*/
					/*$zhuyuan_basic_info[0]["zhuyuan_department"] = $muqin_basic_info[0]['zhuyuan_department'];		
					$shijian = time();
					$shengri = strtotime($zhuyuan_basic_info[0]['fenmian_shijian']);

					// $riling = ceil(($shijian-$shengri)/86400);
					if(!empty($shengri))
					{
						$riling = ceil(($shijian-$shengri)/86400)."天";
					}
					else
					{
						$riling = "未知";
					}

					$this->assign('riling',$riling);
					$this->assign('muqin_bingchuang_hao',$muqin_basic_info[0]["zhuyuan_chuanghao"]);
					$this->assign('zhuyuan_basic_info',$zhuyuan_basic_info[0]);
					$this->assign('patient_basic_info',$zhuyuan_basic_info[0]);*/
			}
			else
			{
				$patient_basic_info = M("patient_basic_info")->where("patient_id like '".$zhuyuan_basic_info[0]["patient_id"]."' ")->select();
				$this->assign('bingchuang_hao',$zhuyuan_basic_info[0]["zhuyuan_chuanghao"]);
				$this->assign('zhuyuan_basic_info',$zhuyuan_basic_info[0]);
				$this->assign('patient_basic_info',$patient_basic_info[0]);
			}
		}
		/*//新生儿信息：
		else if(!empty($_GET['muqin_zhuyuan_id']))
		{
			$muqin_zhuyuan_id = $_GET["muqin_zhuyuan_id"];
			$zhuyuan_basic_info = M("zhuyuan_xinshenger_info")->where("muqin_zhuyuan_id like '$muqin_zhuyuan_id' ")->select();
			if($zhuyuan_basic_info==false)
			{
					$this->assign('system_info','这个住院患者不存在，请查询后重新尝试');
					$this->display("System:showError");
					exit();
			}
			$zhuyuan_id = $zhuyuan_basic_info[0]["zhuyuan_id"];
			$zhuyuan_basic_info[0]["xingming"] = $zhuyuan_basic_info[0]["muqin_xingming"]."之".$zhuyuan_basic_info[0]["xingbie"];
			$zhuyuan_basic_info[0]["xingming"] = str_replace("男","子",$zhuyuan_basic_info[0]["xingming"]);
			$zhuyuan_basic_info[0]["xingming"] = str_replace("女","女",$zhuyuan_basic_info[0]["xingming"]);
			$this->assign('bingchuang_hao',$zhuyuan_basic_info[0]["xiuyangshi"]);
			$this->assign('zhuyuan_basic_info',$zhuyuan_basic_info[0]);
			$this->assign('patient_basic_info',$zhuyuan_basic_info[0]);
		}*/
		else
		{
			$this->assign('system_info','这个住院患者不存在，请查询后重新尝试');
			$this->display("System:showError");
			exit();
		}
		
		$yiyuan_user = M("yiyuan_user")->where("user_id='".$_SESSION["user_id"]."' and user_type='医生'")->select();
		$yishi_type = array('主任医师','副主任医师','主治医师','执业医师','科主任');
		if(in_array($yiyuan_user['0']['user_kebie_position'],$yishi_type))
		{
			$this->assign('user_zhiye_bianma',$yiyuan_user['0']['user_zhiye_bianma']);
		}
		
		$yishi_name = $_SESSION["user_name"];
		if(empty($_GET['state'])||$_GET['state']==" ")
		{
			$state = "%";
		}
		else
		{
			$temp_state = explode("_",$_GET['state']);
			if(count($temp_state) == 1)
			{
				$state = $_GET['state'];
			}
		}

		if(empty($_GET['page'])||$_GET['page']==" ")
		{
			$page = "1";
		}
		else
		{
			$page = $_GET['page'];
		}
		
		if($_SESSION["agent_type"]=="pad")
		{
			$page_number = 50;
		}
		else
			$page_number = 17;
		
		if(count($temp_state) == 2)
		{
			$zongtiaoshu = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id like '$zhuyuan_unique_code' and (state like '$temp_state[0]' or state like '$temp_state[1]') and state != '已删除' ")->order("xiada_time asc")->count();
		}
		else
		{
			$zongtiaoshu = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id like '$zhuyuan_unique_code' and state like '$state' and state != '已删除' ")->order("xiada_time asc")->count();
		}
		
		$yeshu = ceil($zongtiaoshu/$page_number);
		//获取当前病人的所有医嘱
		if(count($temp_state) == 2)
		{
			$linshi_yizhu_temp = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id like '$zhuyuan_unique_code' and (state like '$temp_state[0]' or state like '$temp_state[1]') and state != '已删除' ")->order("xiada_time asc,cast(zuhao as signed) asc")->page($page.','.$page_number)->select();
		}
		else
		{
			$linshi_yizhu_temp = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id like '$zhuyuan_unique_code' and state like '$state' and state != '已删除' ")->order("xiada_time asc,cast(zuhao as signed) asc")->page($page.','.$page_number)->select();
		}
		
	

		if(count($linshi_yizhu_temp)>=1)
		{
			for($count=0;$count<count($linshi_yizhu_temp);$count++)
			{
				/*$jieguo = '';
				$jieguoall = M('zhuyuan_yaowu_guomin')->where("`id`='{$linshi_yizhu_temp[$count][relate_zhixing_id]}' ")->find();
				if($jieguoall['jiancha_jieguo'] != '')
				{
					$jieguo = '（'.$jieguoall['jiancha_jieguo'].'）';
				}*/

				$linshi_yizhu[$count] = $linshi_yizhu_temp[$count];
				$linshi_yizhu[$count] = str_replace("（+）","<font color='red'>（+）</font>",$linshi_yizhu[$count]);
				if(!empty($linshi_yizhu[$count]['zuhao']))
				{
					//判断医嘱在组内位置：
					$zuhe_count = 0;
					$zuhe_number = 0;
					$one_zuhe_linshi_yizhu = M("zhuyuan_yizhu_linshi")->field("id")->where("zuhao like '".$linshi_yizhu_temp[$count]['zuhao']."' and state not in ( '已删除','取消执行')")->order("xiada_time asc,id asc")->select();
					
					foreach($one_zuhe_linshi_yizhu as $one_linshi_yizhu)
					{
						$zuhe_count++;
						if($one_linshi_yizhu["id"] == $linshi_yizhu_temp[$count]["id"])
						{
							$zuhe_number = $zuhe_count;
						}
					}
			
					//根据组内位置进行判断
					if($zuhe_number==1 && $zuhe_count>1)
					{
						$linshi_yizhu[$count]['islast'] = 'first';
					}
					else if($zuhe_number == $zuhe_count && $zuhe_number > 1)
					{
						$linshi_yizhu[$count]['islast'] = 'last';
					}
					else if($zuhe_number != $zuhe_count && $zuhe_number > 1)
					{
						$linshi_yizhu[$count]['islast'] = 'middle';
					}
					else
						$linshi_yizhu[$count]['islast'] = 'alone';
						 
					if($linshi_yizhu[$count]['islast'] == "first")
							$linshi_yizhu[$count]['content_show'] = '<span class="float_left">'.'┏<span name="show_content">'.$linshi_yizhu[$count]['content'].$jieguo.'</span></span>';
					else if($linshi_yizhu[$count]['islast'] == "middle")
							$linshi_yizhu[$count]['content_show'] = '<span class="float_left">'.'┃<span name="show_content">'.$linshi_yizhu[$count]['content'].$jieguo.'</span></span>';
					else if($linshi_yizhu[$count]['islast'] == "last")
							$linshi_yizhu[$count]['content_show'] = '<span class="float_left">'.'┗<span name="show_content">'.$linshi_yizhu[$count]['content'].$jieguo.'</span></span>';
					else
							$linshi_yizhu[$count]['content_show'] = '<span class="float_left">'.'<span name="show_content">'.$linshi_yizhu[$count]['content'].$jieguo.'</span></span>';
				}
				else
				{
					$linshi_yizhu[$count]['islast'] = 'alone';
					$linshi_yizhu[$count]['content_show'] = '<span class="float_left" name="show_content">'.$linshi_yizhu[$count]['content'].$jieguo.'</span>';
				}
				
				//看是否追加显示此次用量
				if($linshi_yizhu[$count]['yongfa_type']=='输液'||$linshi_yizhu[$count]['yongfa_type']=='西药中成药'||$linshi_yizhu[$count]['yongfa_type']=='中草药'||$linshi_yizhu[$count]['yongfa_type']=='药品')
				{
					if(!$linshi_yizhu[$count]['is_cydy'])
					{
						$linshi_yizhu[$count]['content_show'] =  '<div class="float_left_full_width">'.$linshi_yizhu[$count]['content_show'].'<span class="float_right"><span name="ciliang">'.$linshi_yizhu[$count]['ciliang'].'</span><span name="shiyong_danwei">'.$linshi_yizhu[$count]['shiyong_danwei']."</span></span></div>";
					}
					//如果是出院带药，按下列格式显示
					else
					{
						$linshi_yizhu[$count]['content_show'] =  '<div class="float_left_full_width">'.$linshi_yizhu[$count]['content_show'].'<span class="float_right"><span name="shuliang">'.$linshi_yizhu[$count]['shuliang'].'</span><span name="lingshou_danwei">'.$linshi_yizhu[$count]['lingshou_danwei']."</span></span></div>";
					}
				
				}
				
				if($linshi_yizhu[$count]['yongfa_type']=='检查'||$linshi_yizhu[$count]['yongfa_type']=='检查项目'||$linshi_yizhu[$count]['yongfa_type']=='诊疗项目'||$linshi_yizhu[$count]['yongfa_type']=='诊疗')
				{
					if($linshi_yizhu[$count]['yongfa']!="处置"&&$linshi_yizhu[$count]['yongfa']!="小时计费")
						$linshi_yizhu[$count]['content_show'] =  '<div class="float_left_full_width">'.$linshi_yizhu[$count]['content_show'].'<span class="float_right">'.$linshi_yizhu[$count]['yongfa']."</span></div>";
					else
						$linshi_yizhu[$count]['content_show'] =  '<div class="float_left_full_width">'.$linshi_yizhu[$count]['content_show'].'</div>';
				}
				
				//看是否追加显示频率和用法
				if($linshi_yizhu[$count]['yongfa_type']=='输液'||$linshi_yizhu[$count]['yongfa_type']=='西药中成药'||$linshi_yizhu[$count]['yongfa_type']=='中草药'||$linshi_yizhu[$count]['yongfa_type']=='药品')
				{
					if($linshi_yizhu[$count]['islast'] == 'alone'||$linshi_yizhu[$count]['islast'] == 'last'||$count == 0)
					{
						if($linshi_yizhu[$count]['yongfa_type']=='输液'||$linshi_yizhu[$count]['yongfa_type']=='西药中成药'||$linshi_yizhu[$count]['yongfa_type']=='中草药'||$linshi_yizhu[$count]['yongfa_type']=='药品')
						if(!$linshi_yizhu[$count]['is_cydy'])
						{
						 $linshi_yizhu[$count]['content_show'] =  $linshi_yizhu[$count]['content_show'].'<div class="fix_right">'.
							$linshi_yizhu[$count]['pinlv'];
						}
						//如果是出院带药，按下列格式显示
						else
						{
								$linshi_yizhu[$count]['content_show'] =  $linshi_yizhu[$count]['content_show'].'<div class="fix_right">'.$linshi_yizhu[$count]['ciliang'].$linshi_yizhu[$count]['shiyong_danwei']."&nbsp&nbsp".
							$linshi_yizhu[$count]['pinlv'];
						}
						
						if($linshi_yizhu[$count]['yongfa']!="处置"&&$linshi_yizhu[$count]['yongfa']!="小时计费")
							$linshi_yizhu[$count]['content_show'] .= "&nbsp&nbsp".$linshi_yizhu[$count]['yongfa']."</div>";
						else
							$linshi_yizhu[$count]['content_show'] .= "</div>";
					}
				}
				
				//如果为医嘱整理信息，就居中显示：
				if($linshi_yizhu[$count]['yongfa_type']=='医嘱整理')
				{
					$linshi_yizhu[$count]['content_show'] = "<div class='yizhuzhengli_info'>".$linshi_yizhu[$count]['content']."</div>";
				}
				
				//设置为双签名格式：
				if($linshi_yizhu[$count]['xiada_zhiye_yishi_name']!=$linshi_yizhu[$count]['xiada_yishi_name']&&!empty($linshi_yizhu[$count]['xiada_zhiye_yishi_name']))
				{
					$linshi_yizhu[$count]['xiada_yishi_name'] = $linshi_yizhu[$count]['xiada_yishi_name']."-".$linshi_yizhu[$count]['xiada_zhiye_yishi_name']; 
				}
			}
		}
		$this->assign('zhuyuan_id',$zhuyuan_id);

		$fenye_count = 0;
		foreach($linshi_yizhu as $fenye)
		{
			//$jieguo = M('zhuyuan_yaowu_guomin')->where("`id`='{$fenye[relate_zhixing_id]}' ")->find();
			//$fenye['jieguo'] = $jieguo['jiancha_jieguo'];
			$linshi_yizhu_fenye[$fenye_count/$page_number][$fenye_count%$page_number] = $fenye;
			$fenye_count++;
		}
		if($mode=="print")
		{
			//补齐分页内容显示为空表格：
			//先创建一条空白的医嘱
			foreach($linshi_yizhu_fenye[0][0] as $key => $fenye)
			{
				//$blank_fenye[$key] = "-";
			}
			$blank_fenye["islast"] = "true";
			$blank_fenye["xiada_time"] = "&nbsp<br />&nbsp";
			//$blank_fenye["content_show"] = "-";
			for($last_count=0;$last_count< $page_number;$last_count++)
			{
				if($linshi_yizhu_fenye[$last_count/$page_number][$last_count]==null)
				{
					$linshi_yizhu_fenye[$last_count/$page_number][$last_count] = $blank_fenye;
				}
			}
		}

		if(!empty($linshi_yizhu))
		{
			$week_begin = strtotime($linshi_yizhu['0']['xiada_time']);
			$week_end = strtotime($linshi_yizhu[count($linshi_yizhu)-1]['xiada_time']);
		}

		foreach($linshi_yizhu_fenye as $key => $one)
		{
			$linshi_yizhu_fenye[$key] = $this->signatureProceed($linshi_yizhu_fenye[$key],"xiada_yishi_name",0);
			$linshi_yizhu_fenye[$key] = $this->signatureProceed($linshi_yizhu_fenye[$key],"zhixing_name",0);
		}
		$this->assign('yishi_name',$yishi_name);
		$this->assign('linshi_yizhu_fenye',$linshi_yizhu_fenye);
		$this->assign('linshi_yizhu',$linshi_yizhu);
		$this->assign('page',$page);
		$this->assign('yeshu',$yeshu);
		$this->assign('page_tiaoshu',$page_number);
		$this->assign('zongtiaoshu',$zongtiaoshu);
		$this->assign('current_date',date('Y-m-d H:i'));
		if($state=="%")
			$state = "all";
		$this->assign('state',$state);
		$last_yizhu_date = M()->query("select xiada_time from zhuyuan_yizhu_linshi where id = (select max(id) from zhuyuan_yizhu_linshi where zhuyuan_id = '$zhuyuan_id' and state!='已删除')");
		if(count($last_yizhu_date)==0||empty($last_yizhu_date[0]['xiada_time']))
		{
			$this->assign('last_yizhu_date',date('Y-m-d H:i'));
		}
		else
		{
			$this->assign('last_yizhu_date',$last_yizhu_date[0]['xiada_time']);
		}
		//计算总页数：
		if(count($temp_state) == 2)
		{
			$linshi_yizhu_page_amount = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id like '$zhuyuan_id' and (state like '$temp_state[0]' or state like '$temp_state[1]') and state != '已删除' ")->count();
		}
		else
		{
			$linshi_yizhu_page_amount = M("zhuyuan_yizhu_linshi")->where("zhuyuan_id like '$zhuyuan_id' and state like '$state' and state != '已删除' ")->count();
		}
		$linshi_yizhu_page_amount = ceil($linshi_yizhu_page_amount/$page_number);
		if($linshi_yizhu_page_amount==0)
			$linshi_yizhu_page_amount = 1;
		$this->assign('page_amount',$linshi_yizhu_page_amount);
		$this->display();
	}
}
