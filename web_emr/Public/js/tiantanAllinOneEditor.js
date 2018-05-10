/**************************************************
*  Created:  2014-04-01
*  Info:整合了多种文档读写功能的全能编辑器
*  @Tiantanhehe (C)2011-3011 Tiantanhehe
*  @Author Dongjie <dongjie@tiantanhehe.com>
*  @Version 0.1
*  @Updated History:  
***************************************************/

//说明：伴随初始化文档打开编辑器
//参数：
//			parent_div:父容器，初始化在哪里
//			document_url:文档路径，包含文档全称
//			document_type:文档类型
function initialEditorWithDocument(parient_div_name,document_url,document_type)
{
	//1. 根据文件名后缀与传入的文档类型判断是否统一：
	// 1)获取文档后缀名
	dot_pos = document_url.lastIndexOf(".");
	document_url_gotted_type = document_url.substr(dot_pos+1);
	if(document_url_gotted_type!=document_type)
	{
		alert("您加载了错误的文件类型，请联系管理员，或者重新尝试。");
		return false;
	}
	
	//如果为office类型，则加载使用点聚控件
	if(document_type=="doc"||document_type=="ppt"||document_type=="xls")
	{
		var Sys = {};
		var ua = navigator.userAgent.toLowerCase();
		var s;

			(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
			(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
			(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
			(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
			(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
			if (Sys.ie){
				var editor_description = ""
				editor_description += "<object id='tiantan_dianju_editor' height='100%' width='100%' classid='clsid:E77E049B-23FC-4DB8-B756-60529A35FAD5' codebase='./WebOffice.cab#version=7,0,0,0'>"
				editor_description += "<param name='_ExtentX' value='6350'><param name='_ExtentY' value='6350'>"
				editor_description += "</object>"
				$("[name='"+parient_div_name+"']").html(editor_description);
			} 
		
			if (Sys.firefox || Sys.chrome){
				var editor_description = ""
				editor_description += "<object id='tiantan_dianju_editor' TYPE='application/x-itst-activex'  clsid='{E77E049B-23FC-4DB8-B756-60529A35FAD5}' event_NotifyCtrlReady='NotifyCtrlReady' progid='' height='100%' width='100%' codeBase='Weboffice.cab#version=7,0,0,0' >"
				editor_description += "<param name='_ExtentX' value='6350'><param name='_ExtentY' value='6350'>"
				editor_description += "</OBJECT>"
				$("[name='"+parient_div_name+"']").html(editor_description);
			}
			document.all.tiantan_dianju_editor.ShowToolBar = 1;
			document.all.tiantan_dianju_editor.OptionFlag |= 0x0080;
			document.all.tiantan_dianju_editor.LoadOriginalFile(document_url,document_type);
			document.all.tiantan_dianju_editor.HideMenuArea("hideall","","","");
			//获取患者信息：
			$.ajaxSetup({
				async: false
			});
			$.getJSON( 'http://'+server_url+'/web_emr/Home/Data/getPatientInfoForReplace',{"zhuyuan_id":zhuyuan_id}, function( data){
				document.all.tiantan_dianju_editor.ReplaceText("【姓名】", data.xingming, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【性别】", data.xingbie, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【年龄】", data.nianling, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【籍贯】", data.jiguan, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【入院日期】", data.ruyuan_riqi_time, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【入院时间】", data.ruyuan_riqi_time, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【住院号】", data.zhuyuan_id, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【病床号】", data.bingchuang_hao, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【床号】", data.bingchuang_hao, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【联系电话】", data.juzhu_dianhua, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【现住址】", data.juzhu_dizhi, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【诊断】", data.zhenduan, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【主诊断】", data.zhu_zhenduan, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【住院病区】", data.zhuyuan_bingqu, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【科别】", data.zhuyuan_kebie, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【主诉】", data.zhusu, 1);
				document.all.tiantan_dianju_editor.ReplaceText("【今日】", data.today, 1);
			});
	}
	if(document_type=="pdf")
	{
		var editor_description = "";
		editor_description += "<iframe frameborder='0' id='openpdf' scrolling='yes' name='openpdf' hspace='0' height='100%' width='100%'></iframe>";
		$("[name='"+parient_div_name+"']").html(editor_description);
		window.open(document_url,'openpdf');
	}
	
	window.onbeforeunload = function(event){
		document.all.tiantan_dianju_editor.CloseDoc(0);
	}
}
