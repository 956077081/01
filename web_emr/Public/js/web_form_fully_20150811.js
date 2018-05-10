//定义全局变量菜单信息
var main_menu_info=new Array();
var current_content_url="";
var current_zhixing_id="";
var current_patient_xingming="";
var current_patient_zhuangtai="";
var current_patient_special_info="";
var current_patient_id="";
var current_yisheng_yiyuan_id="";
var current_bingli_yiyuan_id="";
var current_muban_id="";
var current_muban_mingcheng = "";
var current_muban_leixing = "";
var current_muban_kebie = "";
var current_muban_bingli_type = "";
var user_number = "";
var quanxian = "";
var zhurenyishi_id = "";
var zhuzhiyishi_id = "";
var zhuyuanyishi_id = "";
var chuyuan_type = "";
var last_content_url="";
var chuanghao = "";
var temp_zhuyuan_id = '';

$(document).ready(function(){
	//初始化等待条位置:
	var left_pos = $("#conframe").offset().left+($("#conframe").width()/2);
	var top_pos = $("#conframe").offset().top+($("#conframe").height()/2);
	$(".loading").offset({top:top_pos,left:left_pos});

	$("#print").click(function(){
		document.getElementById('printer_conframe').contentWindow.printerPrint();
	});
	$("#preview").click(function(){
		document.getElementById('printer_conframe').contentWindow.printerPreview();
	});

	var iframe = document.getElementById("conframe");
	if(iframe.attachEvent){
		iframe.attachEvent("onload", function(){
			temp_current_content_url = document.getElementById('conframe').contentWindow.window.location.href;
			if(temp_current_content_url.indexOf("undefined")!=-1)
			{
				return false;
			}
			else
			{
				last_content_url = current_content_url;
				current_content_url = temp_current_content_url;
				analysisUrl(current_content_url);
			}
		});
	}
	else
	{
		iframe.onload = function(){
			temp_current_content_url = document.getElementById('conframe').contentWindow.window.location.href;
			if(temp_current_content_url.indexOf("undefined")!=-1)
			{
				return false;
			}
			else
			{
				last_content_url = current_content_url;
				current_content_url = temp_current_content_url;
				analysisUrl(current_content_url);
			}
		};
	}

	document.getElementById("conframe").onbeforeunload=function(){
		temp_current_content_url = document.getElementById('conframe').contentWindow.window.location.href;
		if(temp_current_content_url.indexOf("undefined")!=-1)
		{
			return false;
		}
	};

	document.getElementById("printer_conframe").onbeforeunload=function(){
		temp_current_content_url = document.getElementById('printer_conframe').contentWindow.window.location.href;
		if(temp_current_content_url.indexOf("undefined")!=-1)
		{
			return false;
		}
	};
});

function analysisUrl(current_content_url)
{
	var arrs = new Array();
	var url_names = current_content_url;
	var arrs = url_names.split("/");
	var lengths = arrs.length;
	var length_s = lengths-1;
	chuyuan_type = arrs[length_s];
	//变换打印地址：
	home_page = server_url +"/web_emr/";
	if (current_content_url.indexOf("tiantan_add_form") == -1 && (current_content_url.indexOf("/edit") == -1||current_content_url.indexOf("Jiancha/edit") != -1))
	{
		current_url = current_content_url;
		if (current_url.indexOf("Patient/showPatientZhuyuanDetail/") != -1)
		{
			current_zhixing_id = current_url.substring(current_url.indexOf("/zhuyuan_id") + 12);
		}
		
		if (current_url.indexOf("MubanGuanli/showMubanDetail/") != -1)
		{
			current_muban_id = current_url.substring(current_url.indexOf("/muban_id") + 10);
		}
		
		if (current_url.indexOf("zhuyuan_id") != -1 && this.current_zhuyuan_id == "000")
		{
			current_zhixing_id = current_url.substring(current_url.indexOf("/zhuyuan_id") + 12);
		}
		
		if (current_url.indexOf("Jiancha/edit") != -1 || current_url.indexOf("Jiancha/update") != -1)
		{
			if (current_url.indexOf("yingxiang_table") != -1)
			{
				if (current_url.indexOf("已申请") != -1 || current_url.indexOf("检查完毕") != -1)
					printer_url = "http://"+home_page + "/Yingxiang/Xiangmu/printShenqingdan/zhixing_type/住院/zhixing_id/" + current_zhixing_id + "/" + current_url.substring(current_url.indexOf("jiancha_id/")).replace("jiancha_id", "jianyan_id");
				else
					printer_url = "http://"+home_page + "/Yingxiang/Xiangmu/printReport/zhixing_type/住院/zhixing_id/" + current_zhixing_id + "/" + current_url.substring(current_url.indexOf("jiancha_id/")).replace("jiancha_id", "jianyan_id");
			}
			else if (current_url.indexOf("bingqu_tables") != -1)
			{
				if (current_url.indexOf("已申请") != -1 || current_url.indexOf("检查完毕") != -1)
					printer_url = "http://"+home_page + "/Jianyan/Xiangmu/printShenqingdan/zhixing_type/住院/zhixing_id/" + current_zhixing_id + "/" + current_url.substring(current_url.indexOf("jiancha_id/")).replace("jiancha_id", "jianyan_id");
				else
					printer_url = "http://"+home_page + "/Jianyan/Xiangmu/printReport/zhixing_type/住院/zhixing_id/" + current_zhixing_id + "/" + current_url.substring(current_url.indexOf("jiancha_id/")).replace("jiancha_id", "jianyan_id");
			}
			else
			{
				if (current_url.indexOf("已申请") != -1 || current_url.indexOf("检查完毕") != -1)
					printer_url = "http://"+home_page + "/Jianyan/Xiangmu/printShenqingdan/zhixing_type/住院/zhixing_id/" + current_zhixing_id + "/" + current_url.substring(current_url.indexOf("jiancha_id/")).replace("jiancha_id", "jianyan_id");
				else
					printer_url = "http://"+home_page + "/Jianyan/Xiangmu/printReport/zhixing_type/住院/zhixing_id/" + current_zhixing_id + "/" + current_url.substring(current_url.indexOf("jiancha_id/")).replace("jiancha_id", "jianyan_id");
			}
		}
		else
		{
			if (current_content_url.indexOf("View") != -1)
				printer_url = current_content_url.replace("View", "Print");
			else if (current_content_url.indexOf("TijianDaoyindan") != -1)
				printer_url = current_content_url;
			else if (current_content_url.indexOf("TiwenJiludan/showSancedan/") != -1)
				printer_url = current_content_url+"/mode/print";
			else if(current_content_url.toLowerCase().indexOf("print") == -1)
				printer_url = current_content_url.replace("show", "print");
			else
				printer_url = current_content_url;
		}
		
		if (current_content_url.indexOf("System/showUserLoginInfo") != -1)
		{
			printer_url = current_content_url;
		}

		$("#printer_conframe").attr("src",printer_url);
	}

	//如果因为住院ID发生变化
	if((current_content_url.indexOf("###")==-1 && current_content_url.indexOf("showPatientZhuyuanDetail")!=-1) || (current_content_url.indexOf("###")==-1 && current_content_url.indexOf("editPatientBasicInfo")!=-1) || (current_content_url.indexOf("editChuyuanInfo")!=-1 && last_content_url.indexOf("updateChuyuanInfo")!=-1))
	{
		//获取最新的住院ID
		if(current_content_url.indexOf("/",current_content_url.indexOf("zhuyuan_id/")+11)!=-1)
		{
			current_zhixing_id = current_content_url.substring(current_content_url.indexOf("zhuyuan_id/")+11,current_content_url.indexOf("/",current_content_url.indexOf("zhuyuan_id/")+11));
		}
		else if(current_content_url.indexOf("zhuyuan_id/")!=-1)
			current_zhixing_id = current_content_url.substring(current_content_url.indexOf("zhuyuan_id/")+11);
		
		//患者所在医院ID
		if(current_content_url.indexOf("/",current_content_url.indexOf("yiyuan_id/")+10)!=-1)
		{
			current_bingli_yiyuan_id = current_content_url.substring(current_content_url.indexOf("yiyuan_id/")+10,current_content_url.indexOf("/",current_content_url.indexOf("yiyuan_id/")+10));
		}
		else if(current_content_url.indexOf("yiyuan_id/")!=-1)
			current_bingli_yiyuan_id = current_content_url.substring(current_content_url.indexOf("yiyuan_id/")+10);
			
		//患者姓名
		if(current_content_url.indexOf("xingming/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("xingming/")+9)!=-1)
				current_patient_xingming = current_content_url.substring(current_content_url.indexOf("xingming/")+9,current_content_url.indexOf("/",current_content_url.indexOf("xingming/")+9));
			else
				current_patient_xingming = current_content_url.substring(current_content_url.indexOf("xingming/")+9);
		}
		current_patient_xingming = decodeURI(current_patient_xingming);

		//患者状态
		if(current_content_url.indexOf("zhuangtai/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("zhuangtai/")+10)!=-1)
				current_patient_zhuangtai = current_content_url.substring(current_content_url.indexOf("zhuangtai/")+10,current_content_url.indexOf("/",current_content_url.indexOf("zhuangtai/")+10));
			else
				current_patient_zhuangtai = current_content_url.substring(current_content_url.indexOf("zhuangtai/")+10);
		}
		current_patient_zhuangtai = decodeURI(current_patient_zhuangtai);
		
		//患者特殊属性
		if(current_content_url.indexOf("special_info/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("special_info/")+13)!=-1)
				current_patient_special_info = current_content_url.substring(current_content_url.indexOf("special_info/")+13,current_content_url.indexOf("/",current_content_url.indexOf("special_info/")+13));
			else
				current_patient_special_info = current_content_url.substring(current_content_url.indexOf("special_info/")+13);
		}
		//current_shifou_chanchengjilu
		current_patient_special_info = decodeURI(current_patient_special_info);
		if(user_type=="医生"||user_type=="管理员")
		{
			updateSubMenuByZhuyuanIDforYishi();
		}
	}
	
	//如果因为门诊ID发生变化
	if(current_content_url.indexOf("showZhongyiLiangbiaoList")!=-1 && current_content_url.indexOf("###")==-1)
	{
		//alert(current_content_url.indexOf("showZhongyiLiangbiaoList"));
		//获取最新的menzhen_id
		if(current_content_url.indexOf("/",current_content_url.indexOf("menzhen_id/")+11)!=-1)
		{
			current_zhixing_id = current_content_url.substring(current_content_url.indexOf("menzhen_id/")+11,current_content_url.indexOf("/",current_content_url.indexOf("menzhen_id/")+11));
		}
		else if(current_content_url.indexOf("menzhen_id/")!=-1)
			current_zhixing_id = current_content_url.substring(current_content_url.indexOf("menzhen_id/")+11);

		//患者姓名
		if(current_content_url.indexOf("xingming/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("xingming/")+9)!=-1)
				current_patient_xingming = current_content_url.substring(current_content_url.indexOf("xingming/")+9,current_content_url.indexOf("/",current_content_url.indexOf("xingming/")+9));
			else
				current_patient_xingming = current_content_url.substring(current_content_url.indexOf("xingming/")+9);
		}

		//患者身份证号
		if(current_content_url.indexOf("patient_id/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("patient_id/")+11)!=-1)
				current_patient_id = current_content_url.substring(current_content_url.indexOf("patient_id/")+11,current_content_url.indexOf("/",current_content_url.indexOf("patient_id/")+11));
			else
				current_patient_id = current_content_url.substring(current_content_url.indexOf("patient_id/")+11);
		}
		current_patient_xingming = decodeURI(current_patient_xingming);
		updateSubMenuByMenzhenIDforYishi();
	}

	//如果因为质控住院ID发生变化
	if(current_content_url.indexOf("showBingliDetail")!=-1)
	{
		if(current_content_url.indexOf("/",current_content_url.indexOf("zhuyuan_id/")+11)!=-1)
			current_zhixing_id = current_content_url.substring(current_content_url.indexOf("zhuyuan_id/")+11,current_content_url.indexOf("/",current_content_url.indexOf("zhuyuan_id/")+11));
		else if(current_content_url.indexOf("zhuyuan_id/")!=-1)
			current_zhixing_id = current_content_url.substring(current_content_url.indexOf("zhuyuan_id/")+11);

    if(current_content_url.indexOf("xingming/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("xingming/")+9)!=-1)
				current_patient_xingming = current_content_url.substring(current_content_url.indexOf("xingming/")+9,current_content_url.indexOf("/",current_content_url.indexOf("xingming/")+9));
			else
				current_patient_xingming = current_content_url.substring(current_content_url.indexOf("xingming/")+9);
		}
		
		current_patient_xingming = decodeURI(current_patient_xingming);
		
		//alert(current_zhixing_id)
		updateSubMenuByZhikongIDforZhikong();
	}
	
	//如果因为模板ID发生变化
	if(current_content_url.indexOf("showMubanDetail")!=-1 && current_content_url.indexOf("###")==-1)
	{
		//获取模板信息：
		current_muban_mingcheng = "";
		current_muban_leixing = "";
		current_muban_kebie = "";
		current_muban_bingli_type = "";
		
		//模板ID
		
		if(current_content_url.indexOf("muban_id/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("muban_id/")+9)!=-1)
				current_muban_id = current_content_url.substring(current_content_url.indexOf("muban_id/")+9,current_content_url.indexOf("/",current_content_url.indexOf("muban_id/")+9));
			else
				current_muban_id = current_content_url.substring(current_content_url.indexOf("muban_id/")+9);
		}

		//模板名称
		if(current_content_url.indexOf("mingcheng/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("mingcheng/")+10)!=-1)
				current_muban_mingcheng = current_content_url.substring(current_content_url.indexOf("mingcheng/")+10,current_content_url.indexOf("/",current_content_url.indexOf("mingcheng/")+10));
			else
				current_muban_mingcheng = current_content_url.substring(current_content_url.indexOf("mingcheng/")+10);
		}
		current_muban_mingcheng = UrlDecode(current_muban_mingcheng);
		//模板类型
		if(current_content_url.indexOf("muban_leixing/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("muban_leixing/")+14)!=-1)
				current_muban_leixing = current_content_url.substring(current_content_url.indexOf("muban_leixing/")+14,current_content_url.indexOf("/",current_content_url.indexOf("muban_leixing/")+14));
			else
				current_muban_leixing = current_content_url.substring(current_content_url.indexOf("muban_leixing/")+14);
		}
		current_muban_leixing = UrlDecode(current_muban_leixing);
		//模板科别
		if(current_content_url.indexOf("muban_kebie/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("muban_kebie/")+12)!=-1)
				current_muban_kebie = current_content_url.substring(current_content_url.indexOf("muban_kebie/")+12,current_content_url.indexOf("/",current_content_url.indexOf("muban_kebie/")+12));
			else
				current_muban_kebie = current_content_url.substring(current_content_url.indexOf("muban_kebie/")+12);
		}
		current_muban_kebie = UrlDecode(current_muban_kebie);
		//模板病历类型
		if(current_content_url.indexOf("muban_bingli_type/")!=-1)
		{
			if(current_content_url.indexOf("/",current_content_url.indexOf("muban_bingli_type/")+18)!=-1)
				current_muban_bingli_type = current_content_url.substring(current_content_url.indexOf("muban_bingli_type/")+18,current_content_url.indexOf("/",current_content_url.indexOf("muban_bingli_type/")+18));
			else
				current_muban_bingli_type = current_content_url.substring(current_content_url.indexOf("muban_bingli_type/")+18);
		}
		current_muban_bingli_type = UrlDecode(current_muban_bingli_type);
		updateSubMenuByMubanIDforYishi();
	}
}


function updateSubMenuByZhuyuanIDforYishi()
{
		$("#nav_info").html("当前患者："+current_patient_xingming);
		var temp_sub_menu_bingli = new Array();
		var temp_sub_menu_linchuang = new Array();
		var temp_sub_menu_huli = new Array();
		continue_number_bingli = 0;
		continue_number_linchuang = 0;
		continue_number_huli = 0;
		
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "查看医嘱";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "yizhu_guanli";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/Yizhuguanli/showChangqi/zhuyuan_id/"+current_zhixing_id+"/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;
		
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "辅助检查";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "fuzhujiancha";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/ZhuyuanYishi/Jiancha/showList/zhuyuan_id/"+current_zhixing_id+"/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;
		
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "住院信息总览";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "zhuyuan_xinxi_zonglan_v2";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/ZhuyuanYishi/Patient/showPatientZhuyuanDetail/zhuyuan_id/"+current_zhixing_id+"/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;

		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "病案首页";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "huanzhe_xinxi";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showBingli/zhuyuan_id/"+current_zhixing_id+"/bingli_type/住院病案首页/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;

		
		//以下为动态获取病历树信息：
		$.ajaxSetup({
			async: false
		});

		$.getJSON("http://"+server_url+"/web_emr/Common/BingliEditor/getBingliTree", {zhuyuan_id:current_zhixing_id,yiyuan_id:current_bingli_yiyuan_id}, function(data){
			for(var bingli_count=0;bingli_count<data.length;bingli_count++)
			{
				temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
				temp_sub_menu_linchuang[continue_number_linchuang][0] = data[bingli_count].bingli_type;
				temp_sub_menu_linchuang[continue_number_linchuang][1] = "ruyuan_jilu";
				temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showBingli/zhuyuan_id/"+current_zhixing_id+"/bingli_type/"+data[bingli_count].bingli_type+"/yiyuan_id/"+current_bingli_yiyuan_id;
				temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
				continue_number_linchuang++;
			}
		});
		$.ajaxSetup({
			async: false
		});

		/*
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "بالنىتسىغا ئېلىنىش خاتىرىسى(入院记录)";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "ruyuan_jilu";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showBingli/zhuyuan_id/"+current_zhixing_id+"/bingli_type/入院记录/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;
		
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "كېسەللىك جەريانى خاتىرىسى(病程记录)";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "bingcheng_jilu";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showBingli/zhuyuan_id/"+current_zhixing_id+"/bingli_type/病程记录/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang ++;

		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "بالنىتسىدىن چىققان خاتىرىلەش(出院记录)";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "chuyuan_jilu";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showBingli/zhuyuan_id/"+current_zhixing_id+"/bingli_type/出院记录/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;
		*/
		refreshSubMultiMenu(temp_sub_menu_bingli,temp_sub_menu_linchuang,temp_sub_menu_huli);
}

function updateSubMenuByMubanIDforYishi()
{
		$("#nav_info").html("当前模板套餐:"+current_muban_mingcheng+"<br />相关联模板：");
		var temp_sub_menu_bingli = new Array();
		var temp_sub_menu_linchuang = new Array();
		var temp_sub_menu_huli = new Array();
		continue_number_bingli = 0;
		continue_number_linchuang = 0;
		continue_number_huli = 0;
		
		/*
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "بالنىتسىغا ئېلىنىش خاتىرىسى(入院记录)";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "ruyuan_jilu";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showMubanBingli/muban_id/"+current_muban_id+"/muban_bingli_type/入院记录/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;
		
		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "كېسەللىك جەريانى خاتىرىسى(病程记录)";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "bingcheng_jilu";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showMubanBingli/muban_id/"+current_muban_id+"/muban_bingli_type/病程记录/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang ++;

		temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
			temp_sub_menu_linchuang[continue_number_linchuang][0] = "بالنىتسىدىن چىققان خاتىرىلەش(出院记录)";
			temp_sub_menu_linchuang[continue_number_linchuang][1] = "chuyuan_jilu";
			temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showMubanBingli/muban_id/"+current_muban_id+"/muban_bingli_type/出院记录/yiyuan_id/"+current_bingli_yiyuan_id;
			temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
		continue_number_linchuang++;
		*/
		
		//以下为动态获取模板树信息：
		$.ajaxSetup({
			async: false
		});
		$.getJSON("http://"+server_url+"/web_emr/Common/BingliEditor/getMubanTree", {muban_id:current_muban_id}, function(data){
			for(var bingli_count=0;bingli_count<data.length;bingli_count++)
			{
				temp_sub_menu_linchuang[continue_number_linchuang] = new Array();
				temp_sub_menu_linchuang[continue_number_linchuang][0] = data[bingli_count].bingli_type;
				temp_sub_menu_linchuang[continue_number_linchuang][1] = "ruyuan_jilu_muban";
				temp_sub_menu_linchuang[continue_number_linchuang][2] = "/web_emr/Common/BingliEditor/showMubanBingli/muban_id/"+current_muban_id+"/muban_bingli_type/"+data[bingli_count].bingli_type;
				temp_sub_menu_linchuang[continue_number_linchuang][3] = new Array();
				continue_number_linchuang++;
			}
		});
		$.ajaxSetup({
			async: false
		});
		
		refreshSubMultiMenu(temp_sub_menu_bingli,temp_sub_menu_linchuang,temp_sub_menu_huli);
}

function updateSubMenuByMenzhenIDforYishi()
{
		var temp_sub_menu = new Array();
		
		temp_sub_menu[0] = new Array();
			temp_sub_menu[0][0] = "1. 预诊";
			temp_sub_menu[0][1] = "data_yizhu";
			temp_sub_menu[0][2] = "/web_emr/MenzhenYishi/Bingli/showZhongyiLiangbiaoList/menzhen_id/"+current_zhixing_id;
			//temp_sub_menu[0][2] = "/web_emr/MenzhenYishi/Bingli/getWenjuan/type/预诊/menzhen_id/"+current_zhixing_id;
			temp_sub_menu[0][3] = new Array();
		
		temp_sub_menu[1] = new Array();
			temp_sub_menu[1][0] = "2. 诊断";
			temp_sub_menu[1][1] = "zhenduan";
			temp_sub_menu[1][2] = "/web_emr/MenzhenYishi/Zhenduan/showMenzhenZhenduan/zhixing_type/门诊/zhixing_id/"+current_zhixing_id;
			temp_sub_menu[1][3] = new Array();

		temp_sub_menu[2] = new Array();
			temp_sub_menu[2][0] = "3. 病历";
			temp_sub_menu[2][1] = "chakan_daoyindan";
			temp_sub_menu[2][2] = "/web_emr/MenzhenYishi/Bingli/biaozhunBingli/menzhen_id/"+current_zhixing_id;
			temp_sub_menu[2][3] = new Array();

		refreshSubMenu(temp_sub_menu);
}

function addTreeViewEvent()
{
	$("div.menu_link>li>a").click(function(){
		if($(this).parent().attr("state")=="closed")
		{
			$("li [state='opened']").each(function(){
				$(this).css("color","white");
				$(this).attr("state","closed");
				$(this).next().find("a").css("color","white");
				$(this).next().hide();
			});
			$(this).css("color","black");
			$(this).next().show();
			$(this).attr("state","opened");
		}
		else
		{
			$(this).next().hide();
			$(this).next().find("a").css("color","white");
		}
	});
	
	$("div.menu_link>li>ul>li>a").click(function(){
		$(this).parent().parent().find("a").css("color","white");
		$(this).css("color","black");
	});
	
	$("li a").click(function(){
		$("#conframe").attr("src","http://"+server_url+$(this).attr("iframe_url"));
		$(".loading").show();
		window.setTimeout(loadingEnd,1500);
	});
	$(".menu_link").find("a:first").attr("state","opened");
	$(".menu_link").find("a:first").css("color","black");
}

function refreshSubMenu(temp_sub_menu)
{
	//以下为循环生成树形目录
	//先清空树形菜单
	$("#bingli_tree").html("");
	//遍历数组追加子菜单信息
	for(var sub_menu_count=0;sub_menu_count<temp_sub_menu.length;sub_menu_count++)
	{
		//单级目录：
		if(temp_sub_menu[sub_menu_count][3].length<1)
		{
			$("#bingli_tree").append(
				'<li class="noSubMenu" state="closed"><b></b><a iframe_url="'+temp_sub_menu[sub_menu_count][2]+'" id="'+temp_sub_menu[sub_menu_count][1]+'">'+temp_sub_menu[sub_menu_count][0]+'</a></li>'
			);
			$.imgTitleButton(temp_sub_menu[sub_menu_count][1]);
		}
		//多级目录：
		else
		{
			var menu_link_content = '<li state="closed">';
			menu_link_content += '<span></span><a iframe_url="'+temp_sub_menu[sub_menu_count][2]+'" id="'+temp_sub_menu[sub_menu_count][1]+'">'+temp_sub_menu[sub_menu_count][0]+'</a>';
			menu_link_content += '<ul>';
			for(var sub_sub_menu_count=0;sub_sub_menu_count<temp_sub_menu[sub_menu_count][3].length;sub_sub_menu_count++)
			{
				menu_link_content += '<li><a iframe_url="'+temp_sub_menu[sub_menu_count][3][sub_sub_menu_count][2]+'" id="'+temp_sub_menu[sub_menu_count][3][sub_sub_menu_count][1]+'">'+temp_sub_menu[sub_menu_count][3][sub_sub_menu_count][0]+'</a></li>';
			}
			menu_link_content += '</ul>';
			menu_link_content += '</li>';
			$("#bingli_tree").append(menu_link_content);
			$.imgTitleButton(temp_sub_menu[sub_menu_count][1]);
		}
	}
	addTreeViewEvent();
}

function refreshSubMultiMenu(temp_sub_menu_bingli,temp_sub_menu_linchuang,temp_sub_menu_huli)
{
	//为临床菜单tab生成内容
	//先清空树形菜单
	$("#bingli_tree").html("");
	//遍历数组追加子菜单信息
	for(var sub_menu_linchuang_count=0;sub_menu_linchuang_count<temp_sub_menu_linchuang.length;sub_menu_linchuang_count++)
	{
		//单级目录：
		if(temp_sub_menu_linchuang[sub_menu_linchuang_count][3].length<1)
		{
			if(temp_sub_menu_linchuang[sub_menu_linchuang_count][1]=="ruyuan_jilu")
			{
				$("#bingli_tree").append(
					'<li class="noSubMenu" state="closed"><b></b><a iframe_url="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][2]+'" id="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][1]+'">'+temp_sub_menu_linchuang[sub_menu_linchuang_count][0]+'</a></li>'
				);
				$.imgTitleButton(temp_sub_menu_linchuang[sub_menu_linchuang_count][1]);
			}
			else if(temp_sub_menu_linchuang[sub_menu_linchuang_count][1]=="ruyuan_jilu_muban")
			{
				var str_html = '<li class="noSubMenuMuban" style="" state="closed">'+
									'<b></b>'+
									'<a iframe_url="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][2]+'" id="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][1]+'">'+temp_sub_menu_linchuang[sub_menu_linchuang_count][0]+'</a>'+
									'<div del_name="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][1]+'" bingli_type="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][0]+'" muban_id="'+current_muban_id+'" style="display:none;width:50px;float:right;margin-top:-28px;font-size:14px;" type="" class="delete_bingli_tree" operation_type="delete">删除</div>'+
									'<div copy_name="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][1]+'" bingli_type="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][0]+'" muban_id="'+current_muban_id+'" style="display:none;width:50px;float:right;margin-top:-8px;font-size:14px;" type="" class="copy_bingli_tree" operation_type="copy">复制</div>'+
								'</li>';
				$("#bingli_tree").append(str_html);
				$.imgTitleButton(temp_sub_menu_linchuang[sub_menu_linchuang_count][1]);
			}
		}
		//多级目录：
		else
		{
			var menu_link_content = '<li state="closed">';
			menu_link_content += '<span></span><a iframe_url="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][2]+'" id="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][1]+'">'+temp_sub_menu_linchuang[sub_menu_linchuang_count][0]+'</a>';
			menu_link_content += '<ul>';
			for(var sub_sub_menu_linchuang_count=0;sub_sub_menu_linchuang_count<temp_sub_menu_linchuang[sub_menu_linchuang_count][3].length;sub_sub_menu_linchuang_count++)
			{
				menu_link_content += '<li><a iframe_url="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][3][sub_sub_menu_linchuang_count][2]+'" id="'+temp_sub_menu_linchuang[sub_menu_linchuang_count][3][sub_sub_menu_linchuang_count][1]+'">'+temp_sub_menu_linchuang[sub_menu_linchuang_count][3][sub_sub_menu_linchuang_count][0]+'</a></li>';
			}
			menu_link_content += '</ul>';
			menu_link_content += '</li>';
			$("#bingli_tree").append(menu_link_content);
			$.imgTitleButton(temp_sub_menu_linchuang[sub_menu_linchuang_count][1]);
		}
	}

	if (current_url.indexOf("MubanGuanli/showMubanDetail") != -1)
	{
		$("#bingli_tree").append(
			'<li class="noSubMenu" id="last_li" state="closed" style="background-color:#FFF;border:0px;"><b></b><input type="button" class="bingli_add_button" id="add_bingli"/></li>'
		);
	}
	/*
	$("#bingli_tree").append(
		'<li class="noSubMenu" state="closed"><b></b><input type="button" class="bingli_add_button" id="add_bingli"/></li>'
	);*/
	addTreeViewEvent();
}

function loadingEnd()
{
	$(".loading").hide();
}

function loadingStart()
{
	$(".loading").show();
}

function getPrintUrl()
{
	return $('#printer_conframe').attr("src");
}

function setPrintUrl(url)
{
	$('#printer_conframe').attr("src",url);
}

function fPreview()
{
	document.getElementById('printer_conframe').contentWindow.printerPreview();
}

function fPrint()
{
	document.getElementById('printer_conframe').contentWindow.printerPrint();
}

function StringToAscii(str){  
   return str.charCodeAt(0).toString(16);  
}

function AsciiToString(asccode){  
   return String.fromCharCode(asccode);  
}

$(".left_menu_div").live("click",function(){
	current_zhixing_id = $(this).attr("zhuyuanid");
	$(".left_menu_div").css("background-color","#fff");
	$(".left_menu_div").css("color","#000000");
	$(".left_menu_div").attr("yanse","");
	var huanzhe_name = "当前患者：";
	huanzhe_name += $(this).find("div:first").html();
	var huanzhe_a = "<a href=\"/web_emr/ZhuyuanYishi/Patient/showPatientZhuyuanDetail/zhuyuan_id/"+current_zhixing_id+"/xingming/"+$(this).find("div:first").html()+"/zhuangtai/"+current_patient_zhuangtai+"\" target=\"conframe\" style=\"text-decoration:none;color:#000;\">"+huanzhe_name+"</a>";
	$(".left_menu_title").html(huanzhe_a);
	$(".left_menu_title").css("color","#000000");
	$(this).css("background-color","#D2942C");
	$(this).css("color","#FFFFFF");
	$(this).attr("yanse","Y");
	$(".loading").show();
});

$(".left_menu_div").live("mousemove",function(){
	var temp_beijing_color = $(this).css("background-color");
	var temp_zhuanhuan = RGBToHex(temp_beijing_color);
	if(temp_zhuanhuan == "#FFFFFF")
	{
		$(this).css("background-color","#D2942C");
		$(this).live("mouseout",function(){
			if($(this).attr("yanse") != 'Y')
			{
				$(this).css("background-color","#fff");
			}
		});
	}
});

function RGBToHex(rgb)
{
	var regexp = /^rgb\(([0-9]{0,3})\,\s([0-9]{0,3})\,\s([0-9]{0,3})\)/g;
	var re = rgb.replace(regexp, "$1 $2 $3").split(" ");//利用正则表达式去掉多余的部分
	var hexColor = "#";
	var hex = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
	for (var i = 0; i < 3; i++)
	{
		var r = null;
		var c = re[i];
		var hexAr = [];
		while (c > 16)
		{
			r = c % 16;
			c = (c / 16) >> 0;
			hexAr.push(hex[r]);
		}
		hexAr.push(hex[c]);
		hexColor += hexAr.reverse().join('');
	}
	return hexColor;
}

$(".left_menu_title a").live("mouseover",function(){
	$(this).css("color","#fff");
}).live("mouseout",function(){
	$(this).css("color","#000");
});

function UrlDecode(zipStr)
{  
     var uzipStr="";  
     for(var i=0;i<zipStr.length;i++){  
         var chr = zipStr.charAt(i);  
         if(chr == "+"){  
             uzipStr+=" ";  
         }else if(chr=="%"){  
             var asc = zipStr.substring(i+1,i+3);  
             if(parseInt("0x"+asc)>0x7f){  
                 uzipStr+=decodeURI("%"+asc.toString()+zipStr.substring(i+3,i+9).toString());  
                 i+=8;  
             }else{  
                 uzipStr+=AsciiToString(parseInt("0x"+asc));  
                 i+=2;  
             }  
         }else{  
             uzipStr+= chr;  
         }  
     }  
   
     return uzipStr;  
}  