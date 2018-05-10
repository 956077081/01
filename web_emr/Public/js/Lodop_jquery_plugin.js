// JavaScript Document
var LODOP = new Array();
//var print_page_percent = "80%";//16K
var print_page_percent = "89%";//A4

//标记护理记录
var have_hulijilu_qianming = false;

/**
 +------------------------------------------------------------------------------
 * jQuery Plug-in
 +------------------------------------------------------------------------------
 */
;(function(){
	//添加对象方法
	$.fn.extend({
		/**
		 +------------------------------------------------------------------------------
		 * 增加 Html 打印项
		 +------------------------------------------------------------------------------
		 */
		"creatHtmPrint" : function(options){
			options = $.extend({
				is_new_LODOP:false,
				object_id:"LODOP_OB",
				embed_id:"LODOP_EM",
				lodop_id:0,
				top:"8mm",
				left:"22mm",
				width:"RightMargin:12mm",
				height:"BottomMargin:8mm",
				print_page_percent:print_page_percent,
				print_setting_name:null
			},options);
			
			LODOP=getLodop();
			if(options.is_new_LODOP)
			{
				LODOP.PRINT_INIT(options.print_setting_name);
			}
			LODOP.ADD_PRINT_HTM(options.top,options.left,options.width,options.height,$(this).html());
			LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT",options.print_page_percent);
			
			return this;
		}
	});
	//添加全局函数
	$.extend({
		/**
		 +------------------------------------------------------------------------------
		 * 导入 Lodop Object
		 +------------------------------------------------------------------------------
		 */
		"importLodop" : function(options){
			options = $.extend({
				object_id:"LODOP_OB",
				embed_id:"LODOP_EM",
				importLabel:"body",
				width:0,
				height:0,
				caption:"",
				border:"",
				color:""
			},options);
			
			var lodop_object = "<object id='"+options.object_id+"' classid='clsid:2105C259-1E0C-4534-8141-A753534CB4CA' width='"+options.width+"' height='"+options.height+"'>";
			
			lodop_object += (options.caption.length > 0) ? "<param name='Caption' value='"+options.caption+"'>" : "";
			lodop_object += (options.border.length > 0) ? "<param name='Border' value='"+options.border+"'>" : "";
			lodop_object += (options.color.length > 0) ? "<param name='Color' value='"+options.color+"'>" : "";
				
			if(options.embed_id.length > 0){
				lodop_object += "<embed id='"+options.embed_id+"' type='application/x-print-lodop' width='"+options.width+"' height='"+options.height+"'"+
								((options.border.length > 0) ? ("border='"+options.border+"'") : "")+
								((options.color.length > 0) ? ("color='"+options.color+"'") : "")+
								"></embed>";
			}
			lodop_object += "</object>";
			
			if($("#"+options.object_id).size() < 1 || $("#"+options.embed_id).size() < 1)
			{
				if(have_hulijilu_qianming==true)
					$(options.importLabel).after(lodop_object);
				else
					$(options.importLabel).after(lodop_object);
			}
		},
		/**
		 +------------------------------------------------------------------------------
		 * 检查是否已安装 Lodop 控件
		 +------------------------------------------------------------------------------
		 */
		"checkIsInstall" : function(options){
			options = $.extend({
				object_id:"LODOP_OB",
				embed_id:"LODOP_EM",
				lodop_id:0
			},options);
			
			try{
				LODOP=getLodop();
				if (LODOP.VERSION)
					return true;
				else
					return false;
			}catch(err){
				alert("Error:本机未安装打印控件或需要升级!请联系管理员。");
				return false;
			}
		},
		/**
		 +------------------------------------------------------------------------------
		 * 增加 URL 打印项
		 +------------------------------------------------------------------------------
		 */
		"creatUrlPrint" : function(options){
			options = $.extend({
				is_new_LODOP:false,
				object_id:"LODOP_OB",
				embed_id:"LODOP_EM",
				lodop_id:0,
				top:"10mm",
				left:"22mm",  
				width:"RightMargin:12mm",
				height:"BottomMargin:14mm",
				print_page_percent:print_page_percent,
				print_setting_name:null,
				print_url:""
			},options);
			
			LODOP=getLodop();
			if(options.is_new_LODOP)
			{
				LODOP.PRINT_INIT(options.print_setting_name);
			}
			if(options.print_url != "")
			{
				LODOP.ADD_PRINT_URL(options.top,options.left,options.width,options.height,options.print_url);
				LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT",options.print_page_percent);
			}
			else
			{
				alert("打印页面URL错误!");
			}
		},
		/**
		 +------------------------------------------------------------------------------
		 * 增加 条形码 打印项
		 +------------------------------------------------------------------------------
		 */
		"creatBarcodePrint" : function(options){
			options = $.extend({  
				is_new_LODOP:false,
				object_id:"LODOP_OB",
				embed_id:"LODOP_EM",
				lodop_id:0,
				top:"2mm",  
				left:"190mm",  
				width:"45mm",
				height:"16mm",
				code_type:"128A",
				code_value:"000000000000",
				is_show_chart:false,
				print_setting_name:null
			},options);
			
			LODOP=document.getElementById(options.object_id);
			if(options.is_new_LODOP)
			{
				LODOP.PRINT_INIT(options.print_setting_name);
			}
			LODOP.ADD_PRINT_BARCODE(options.top,options.left,options.width,options.height,options.code_type,options.code_value);
			if(options.is_show_chart)
			{
				LODOP.SHOW_CHART();
			}
		},
		"setPrintStyle":function(options){
			options = $.extend({
				lodop_id:0,
				FontName:"",
				FontSize:"",
				FontColor:"",
				Bold:"",
				Alignment:""
			},options);
			
			for(var prop in options)
			{
				if(options[prop].length > 0 && prop != "lodop_id")
				{
					LODOP.SET_PRINT_STYLE(prop,options[prop]);
				}
			}
		},
		"addYehao":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:2,
				FontSize:13.5,
				content:"",
				top:"1130",
				left:"100mm",
				width:"RightMargin:103mm",
				height:"BottomMargin:0"
			},options);
			
			if(page_number!="0")
			{
			}
			else
			{
				LODOP.ADD_PRINT_TEXT(options.top,options.left,options.width,options.height,"第#页");
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			}
				
				LODOP.SET_PRINT_STYLEA(0,"FontSize", options.FontSize);
		},
		"addYehaoLimitedPage":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:2,
				FontSize:13.5,
				content:"",
				top:"1130",
				left:"100mm",
				width:"RightMargin:103mm",
				height:"BottomMargin:0"
			},options);
			
			if(page_number!="0")
			{
			}
			else
			{
				LODOP.ADD_PRINT_TEXT(options.top,options.left,options.width,options.height,"第#页");
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				
				var limit_page = ""+options.start_page;
				
				for(count=options.start_page-1;count>0;count--)
				{
					limit_page = limit_page+","+count;
				}
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);

			}
				
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.FontSize);
		},
		"addYemei":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:1,
				bingli_document_title:document_name,
				user_department:"",
				zhuyuan_chuanghao:"",
				patient_xingming:"",
				zhuyuan_id:"",
				main_title_size:18,
				sub_title_size:12,
				main_title_top:"8mm",
				sub_title_top:"18mm",
				left:"25mm",
				lineWidth:"210mm",
				lineTop:"25mm"
			},options);
		
		
			kongge='';
			for (i=0; i<(28-document_name.length)/2; i++)
			{
				kongge+='　';
			}
			LODOP.ADD_PRINT_TEXT(options.main_title_top,"25mm","820mm","10mm",kongge+document_name);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.main_title_size);
			LODOP.SET_PRINT_STYLEA(0,"Bold", "1");

			LODOP.ADD_PRINT_TEXT(options.sub_title_top,"25mm","35mm","10mm","科别:"+options.user_department);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
			LODOP.SET_PRINT_STYLEA(0,"Bold", "0");

			LODOP.ADD_PRINT_TEXT(options.sub_title_top,"62mm","35mm","10mm","床号:"+options.zhuyuan_chuanghao);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
			LODOP.SET_PRINT_STYLEA(0,"Bold", "0");

			LODOP.ADD_PRINT_TEXT(options.sub_title_top,"90mm","79mm","10mm","姓名:"+options.patient_xingming);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
			LODOP.SET_PRINT_STYLEA(0,"Bold", "0");

			LODOP.ADD_PRINT_TEXT(options.sub_title_top,"171mm","45mm","10mm","住院号:"+options.zhuyuan_id);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
			LODOP.SET_PRINT_STYLEA(0,"Bold", "0");
			
			LODOP.ADD_PRINT_LINE(options.lineTop,options.left,options.lineTop,options.lineWidth,0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
		},
		"addYemeiLimitedPage":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:1,
				bingli_document_title:"",
				user_department:"",
				zhuyuan_chuanghao:"",
				patient_xingming:"",
				zhuyuan_id:"",
				main_title_size:18,
				sub_title_size:12,
				main_title_top:"8mm",
				sub_title_top:"18mm",
				left:"25mm",
				lineWidth:"210mm",
				lineTop:"25mm"
			},options);
			
			if(page_number!="0")
			{
			}
			else
			{
				var limit_page = ""+options.start_page;
				
				for(count=options.start_page-1;count>0;count--)
				{
					limit_page = limit_page+","+count;
				}
				
				LODOP.ADD_PRINT_TEXT(options.main_title_top,"75mm","120mm","10mm",options.bingli_document_title);
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"FontSize", options.main_title_size);
				LODOP.SET_PRINT_STYLEA(0,"Bold", "1");
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);

				LODOP.ADD_PRINT_TEXT(options.sub_title_top,"25mm","35mm","10mm","科别:"+options.user_department);
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
				LODOP.SET_PRINT_STYLEA(0,"Bold", "0");
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);

				LODOP.ADD_PRINT_TEXT(options.sub_title_top,"62mm","35mm","10mm","床号:"+options.zhuyuan_chuanghao);
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
				LODOP.SET_PRINT_STYLEA(0,"Bold", "0");
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);

				LODOP.ADD_PRINT_TEXT(options.sub_title_top,"90mm","79mm","10mm","姓名:"+options.patient_xingming);
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
				LODOP.SET_PRINT_STYLEA(0,"Bold", "0");
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);

				LODOP.ADD_PRINT_TEXT(options.sub_title_top,"171mm","45mm","10mm","住院号:"+options.zhuyuan_id);
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"FontSize", options.sub_title_size);
				LODOP.SET_PRINT_STYLEA(0,"Bold", "0");
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);
				
				LODOP.ADD_PRINT_LINE(options.lineTop,options.left,options.lineTop,options.lineWidth,0,1);
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"PageUnIndex",limit_page);
			}
		},
		"addHuliJiluYemei":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:1,
				FontSize:12,
				hospital_name:"",
				title:"",
				patient_name:"",
				bingqu:"",
				chuanghao:"",
				zhuyuanhao:"",
				width:"RightMargin:0mm",
				height:"5mm"
			},options);
			
			//计算医院名称长度，调整居中效果:
			var str_length = options.hospital_name.length;
			var left_pos = (23.5-str_length)/2*10.5;
			//医院名称
			LODOP.ADD_PRINT_TEXT("11mm",left_pos+"mm",options.width,options.height,options.hospital_name);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 26);
			LODOP.SET_PRINT_STYLEA(0,"Bold", 1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			//档案名称
			LODOP.ADD_PRINT_TEXT("23mm","85mm",options.width,options.height,options.title);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 22);
			LODOP.SET_PRINT_STYLEA(0,"Bold", 1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);

			/*LODOP.ADD_PRINT_TEXT("35mm","20mm",options.width,options.height,
			"姓名:"+options.patient_name);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);

			LODOP.ADD_PRINT_TEXT("35mm","70mm",options.width,options.height,
			"性别:"+options.patient_xingbie);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("35mm","100mm",options.width,options.height,
			"年龄:"+options.patient_nianling+"");
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("35mm","130mm",options.width,options.height,
			"病区:"+options.bingqu);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("35mm","170mm",options.width,options.height,
			"病床号:"+options.chuanghao);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("42mm","20mm",options.width,options.height,
			"住院号:"+options.zhuyuanhao);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("42mm","70mm",options.width,options.height,
			"诊断:"+options.zhenduan_info);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 14);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			//绘制表头表格:
			var img_label = "<img width='750px' height='130px' style='position:absolute;' transcolor='#FFFFFF' src='/tiantan_emr/Public/runtime_image/hulijilu_table_title.jpg'/>";
			LODOP.ADD_PRINT_IMAGE("51.5mm","14mm","750px","130px",img_label);*/
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
		},
		"addYizhuYemei":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:1,
				FontSize:12,
				hospital_name:"",
				title:"",
				patient_name:"",
				patient_xingbie:"",
				patient_nianling:"",
				bingqu:"",
				chuanghao:"",
				zhuyuanhao:"",
				width:"RightMargin:0mm",
				height:"5mm"
			},options);
			
			//计算医院名称长度，调整居中效果:
			var str_length = options.hospital_name.length;
			var left_pos = (23.5-str_length)/2*10.5;
			
			LODOP.ADD_PRINT_TEXT("21mm",left_pos+"mm",options.width,options.height,options.hospital_name);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 26);
			LODOP.SET_PRINT_STYLEA(0,"Bold", 1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("33mm","85mm",options.width,options.height,options.title);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 22);
			LODOP.SET_PRINT_STYLEA(0,"Bold", 1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("45mm","13mm",options.width,options.height,
			"姓名:"+options.patient_name);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 12);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_LINE("50.5mm","23mm","50.5mm","43mm",0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("45mm","46mm",options.width,options.height,			
			"性别:"+options.patient_xingbie);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 12);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_LINE("50.5mm","52mm","50.5mm","60mm",0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("45mm","64mm",options.width,options.height,
			"年龄:"+options.patient_nianling);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 12);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_LINE("50.5mm","74mm","50.5mm","88mm",0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("45mm","91mm",options.width,options.height,


			"科室:"+options.bingqu);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 12);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_LINE("50.5mm","101mm","50.5mm","125mm",0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("45mm","128mm",options.width,options.height,
			"病室床号:"+options.chuanghao);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 12);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_LINE("50.5mm","147mm","50.5mm","163.5mm",0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_TEXT("45mm","166mm",options.width,options.height,
			"住院号:"+options.zhuyuanhao);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", 12);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			
			LODOP.ADD_PRINT_LINE("50.5mm","181mm","50.5mm","208mm",0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			

			//LODOP.ADD_PRINT_LINE(options.lineTop,options.left,options.lineTop,options.lineWidth,0,1);
			//LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			//LODOP.ADD_PRINT_LINE(options.lineTop,options.left,options.lineTop,options.lineWidth,0,1);
			//LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
		},
		"addYejiao":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:1,
				FontSize:12,
				content:"",
				top:"1146",
				left:"22mm",
				width:"RightMargin:0mm",
				height:"BottomMargin:10mm",
				lineWidth:"RightMargin:22mm",
				lineTop:"1162"
			},options);
			LODOP.ADD_PRINT_TEXT(options.top,options.left,options.width,options.height,options.content);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			LODOP.SET_PRINT_STYLEA(0,"FontSize", options.FontSize);
			LODOP.ADD_PRINT_LINE(options.lineTop,options.left,options.lineTop,options.lineWidth,0,1);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
			if(page_number!="0")
			{
				LODOP.ADD_PRINT_TEXT("1166","113mm",options.width,options.height,"第"+page_number+"页");
				LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
				LODOP.SET_PRINT_STYLEA(0,"FontSize", "13.5");
			}
		},
		"addImageYemei":function(options){
			options = $.extend({
				lodop_id:0,
				ItemType:1,
				FontSize:12,
				img_url:"",
				top:"20",
				left:"700",
				width:"RightMargin:0mm",
				height:"1200"
			},options);
			
			var img_label = "<img style='position:absolute;' transcolor='#FFFFFF' src='" + options.img_url + "' />";
			LODOP.ADD_PRINT_IMAGE(options.top,options.left,options.width,options.height,img_label);
			LODOP.SET_PRINT_STYLEA(0,"ItemType", options.ItemType);
		}
	});
})(jQuery);

/**
 +------------------------------------------------------------------------------
 * LodopFuncs.js
 +------------------------------------------------------------------------------
 */

var CreatedOKLodop7766=null;

//====判断是否需要安装CLodop云打印服务器:====
function needCLodop(){
     return true;
	 try
	 {
		 var ua = navigator.userAgent;
	
		 if (ua.match(/Windows\sPhone/i) != null) return true;
		 if (ua.match(/iPhone|iPod/i) != null) return true;
		 if (ua.match(/Android/i) != null) return true;
		 if (ua.match(/Edge\D?\d+/i) != null) return true;
		 if (ua.match(/Chrome/i) != null) return true;
		
		 var verTrident = ua.match(/Trident\D?\d+/i);
		 var verIE = ua.match(/MSIE\D?\d+/i);
		 var verOPR = ua.match(/OPR\D?\d+/i);
		 var verFF = ua.match(/Firefox\D?\d+/i);
		 var x64 = ua.match(/x64/i);
		 if ((verTrident == null) && (verIE == null) && (x64 !== null))
			 return true;
		 else
		 if (verFF !== null)
		 {
			 verFF = verFF[0].match(/\d+/);
			 if ((verFF[0] >= 42) || (x64 !== null)) return true;
		 }
		 else
		 if (verOPR !== null)
		 {
			 verOPR = verOPR[0].match(/\d+/);
			 if (verOPR[0] >= 32) return true;
		 }
		 else
		 if ((verTrident == null) && (verIE == null))
		 {
			 var verChrome = ua.match(/Chrome\D?\d+/i);
			 if (verChrome !== null)
			 {
				 verChrome = verChrome[0].match(/\d+/);
				 if (verChrome[0] >= 42) return true;
			 };
		 };
		 return false;
	 }
	 catch (err)
	 {
		 return true;
	 };
};

//====页面引用CLodop云打印必须的JS文件：====
if (needCLodop()) {
	var head = document.head || document.getElementsByTagName("head")[0] || document.documentElement;
	var oscript = document.createElement("script");
	oscript.src ="http://localhost:8000/CLodopfuncs.js?priority=1";
	head.insertBefore( oscript,head.firstChild );

	//引用双端口(8000和18000）避免其中某个被占用：
	oscript = document.createElement("script");
	oscript.src ="http://localhost:18000/CLodopfuncs.js?priority=0";
	head.insertBefore( oscript,head.firstChild );
};

//====获取LODOP对象的主过程：====
function getLodop(oOBJECT,oEMBED){
     var strHtmInstall="<br><font color='#FF00FF'>打印控件未安装!点击这里<a href='http://113.10.155.131/install_lodop32.zip' target='_self'>执行安装</a>,安装后请刷新页面或重新进入。</font>";
    var strHtmUpdate="<br><font color='#FF00FF'>打印控件需要升级!点击这里<a href='http://113.10.155.131/install_lodop32.zip' target='_self'>执行升级</a>,升级后请重新进入。</font>";
    var strHtm64_Install="<br><font color='#FF00FF'>打印控件未安装!点击这里<a href='http://113.10.155.131/install_lodop64.zip' target='_self'>执行安装</a>,安装后请刷新页面或重新进入。</font>";
    var strHtm64_Update="<br><font color='#FF00FF'>打印控件需要升级!点击这里<a href='http://113.10.155.131/install_lodop64.zip' target='_self'>执行升级</a>,升级后请重新进入。</font>";
    var strHtmFireFox="<br><br><font color='#FF00FF'>（注意：如曾安装过Lodop旧版附件npActiveXPLugin,请在【工具】->【附加组件】->【扩展】中先卸它）</font>";
    var strHtmChrome="<br><br><font color='#FF00FF'>(如果此前正常，仅因浏览器升级或重安装而出问题，需重新执行以上安装）</font>";
    var strCLodopInstall="<br><font color='#FF00FF'>CLodop云打印服务(localhost本地)未安装启动!点击这里<a href='CLodopPrint_Setup_for_Win32NT.zip' target='_self'>执行安装</a>,安装后请刷新页面。</font>";
    var strCLodopUpdate="<br><font color='#FF00FF'>CLodop云打印服务需升级!点击这里<a href='CLodopPrint_Setup_for_Win32NT.zip' target='_self'>执行升级</a>,升级后请刷新页面。</font>";
    var LODOP;
    try{
        var isIE = (navigator.userAgent.indexOf('MSIE')>=0) || (navigator.userAgent.indexOf('Trident')>=0);
        if (needCLodop()) {
            try{ LODOP=getCLodop();} catch(err) {};

			
	    if (!LODOP && document.readyState!=="complete") {
			//alert("C-Lodop没准备好，请稍后再试！"); 
			return;
		}
            if (!LODOP) {
		 if (isIE) document.write(strCLodopInstall); else
		 document.documentElement.innerHTML=strCLodopInstall+document.documentElement.innerHTML;
                 return;
            } else {

	         if (CLODOP.CVERSION<"2.0.9.0") { 
			if (isIE) document.write(strCLodopUpdate); else
			document.documentElement.innerHTML=strCLodopUpdate+document.documentElement.innerHTML;
		 };
		 if (oEMBED && oEMBED.parentNode) oEMBED.parentNode.removeChild(oEMBED);
		 if (oOBJECT && oOBJECT.parentNode) oOBJECT.parentNode.removeChild(oOBJECT);	
	    };
        } else {
            var is64IE  = isIE && (navigator.userAgent.indexOf('x64')>=0);
            //=====如果页面有Lodop就直接使用，没有则新建:==========
            if (oOBJECT!=undefined || oEMBED!=undefined) {
                if (isIE) LODOP=oOBJECT; else  LODOP=oEMBED;
            } else if (CreatedOKLodop7766==null){
                LODOP=document.createElement("object");
                LODOP.setAttribute("width",0);
                LODOP.setAttribute("height",0);
                LODOP.setAttribute("style","position:absolute;left:0px;top:-100px;width:0px;height:0px;");
                if (isIE) LODOP.setAttribute("classid","clsid:2105C259-1E0C-4534-8141-A753534CB4CA");
                else LODOP.setAttribute("type","application/x-print-lodop");
                document.documentElement.appendChild(LODOP);
                CreatedOKLodop7766=LODOP;
             } else LODOP=CreatedOKLodop7766;
            //=====Lodop插件未安装时提示下载地址:==========
            if ((LODOP==null)||(typeof(LODOP.VERSION)=="undefined")) {
                 if (navigator.userAgent.indexOf('Chrome')>=0)
                     document.documentElement.innerHTML=strHtmChrome+document.documentElement.innerHTML;
                 if (navigator.userAgent.indexOf('Firefox')>=0)
                     document.documentElement.innerHTML=strHtmFireFox+document.documentElement.innerHTML;
                 if (is64IE) document.write(strHtm64_Install); else
                 if (isIE)   document.write(strHtmInstall);    else
                     document.documentElement.innerHTML=strHtmInstall+document.documentElement.innerHTML;
                 return LODOP;
            };
        };
        if (LODOP.VERSION<"6.2.1.5") {
            if (needCLodop())
            document.documentElement.innerHTML=strCLodopUpdate+document.documentElement.innerHTML; else
            if (is64IE) document.write(strHtm64_Update); else
            if (isIE) document.write(strHtmUpdate); else
            document.documentElement.innerHTML=strHtmUpdate+document.documentElement.innerHTML;
            return LODOP;
        };
        //===如下空白位置适合调用统一功能(如注册语句、语言选择等):===
        LODOP.SET_LICENSES("","13528A153BAEE3A0254B9507DCDE2839","","");
        //===========================================================
        return LODOP;
    } catch(err) {alert("getLodop出错:"+err);};
};

/**
 +------------------------------------------------------------------------------
 * 直接打印
 +------------------------------------------------------------------------------
 * 不经打印预览的直接打印
 +------------------------------------------------------------------------------
 */
function printerPrint(){
	var lodop_id;
	
	if(arguments.length < 1)
		lodop_id = 0
	else if(typeof(arguments[0]) != "number")
		lodop_id = 0;
	else
		lodop_id = arguments[0];
		
	if(typeof(pageDesign)=="function")
	{
		pageDesign();
	}
	else
	{
		alert("页面未设置打印！");
	}

	if($(LODOP).size() > 0)
	{
		if(part_print_start_page!==0&&part_print_mode==true)
		{
			LODOP.SET_PRINT_MODE ("PRINT_START_PAGE",part_print_start_page);
		}
		/*
		暂时取消对打印页数的控制
			
			if(end_page!=0)
				LODOP.SET_PRINT_MODE ("PRINT_END_PAGE",end_page);
		*/
		if(document_type.toLowerCase().indexOf("peiyaoka")!=-1||document_type.toLowerCase().indexOf("shuyeka")!=-1||document_type.toLowerCase().indexOf("yizhuzhixing")!=-1)
			LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","65%");
			
		LODOP.PRINTA();
		//if(document_type.toLowerCase().indexOf("tijian")==-1&&document_type.toLowerCase().indexOf("xiangmu")==-1&&document_type.toLowerCase().indexOf("chufang")==-1&&document_type.toLowerCase().indexOf("tiwen")==-1&&document_type.toLowerCase().indexOf("zhikong")==-1&&document_type.toLowerCase().indexOf("binganshouye")==-1)
			//setDaYinJiLu(zhuyuan_id,server_url,document_type);
		setDaYinJiLu(document_id,document_relate_table,$(".page").height(),0,0,0,"memory");
		setDaYinState(document_id,document_relate_table,"已经打印");
	}
}

/**
 +------------------------------------------------------------------------------
 * 打印预览
 +------------------------------------------------------------------------------
 * 打印预览输出页
 +------------------------------------------------------------------------------
 */
function printerPreview(){
	
	var lodop_id;
	if(arguments.length < 1)
		lodop_id = 0
	else if(typeof(arguments[0]) != "number")
		lodop_id = 0;
	else
		lodop_id = arguments[0];
		
	if(typeof(pageDesign)=="function")
	{
		pageDesign();
	}
	else
	{
		alert("页面未设置打印！");
	}
	
	if($(LODOP).size() > 0)
	{
		LODOP.SET_PREVIEW_WINDOW(0, 0, 0, 0, 0, "预览查看.开始打印");
		if(part_print_start_page!==0&&part_print_mode==true)
		{
			LODOP.SET_PRINT_MODE ("PRINT_START_PAGE",part_print_start_page);
		}
		/*
		暂时取消对打印页数的控制
		LODOP.SET_PRINT_MODE ("PRINT_START_PAGE",start_page);
		if(end_page!=0)
			LODOP.SET_PRINT_MODE ("PRINT_END_PAGE",end_page);
		*/
		LODOP.SET_SHOW_MODE ("LANDSCAPE_DEFROTATED",1);
		LODOP.SET_SHOW_MODE ("HIDE_SBUTTIN_PREVIEW",1);
		//LODOP.SET_SHOW_MODE ("HIDE_PBUTTIN_PREVIEW",1);
		if(document_type.toLowerCase().indexOf("peiyaoka")!=-1||document_type.toLowerCase().indexOf("shuyeka")!=-1||document_type.toLowerCase().indexOf("yizhuchuli")!=-1)
			LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","65%");
			
		LODOP.PREVIEW();
		setDaYinJiLu(document_id,document_relate_table,$(".page").height(),0,0,0,"memory");
	}
}

/**
 +------------------------------------------------------------------------------
 * 打印维护
 +------------------------------------------------------------------------------
 * 对整页的打印布局和打印风格进行界面维护，它与打印设计的区别是不具有打印项增删功能
 +------------------------------------------------------------------------------
 */
function printerSetup()
{
	var lodop_id;
	if(arguments.length < 1)
		lodop_id = 0
	else if(typeof(arguments[0]) != "number")
		lodop_id = 0;
	else
		lodop_id = arguments[0];
		
	if(typeof(pageDesign)=="function")
	{
		pageDesign();
	}
	else
	{
		alert("页面未设置打印！");
	}
		
	if($(LODOP).size() > 0)
	{
		LODOP.PRINT_SETUP();
	}
}

/**
 +------------------------------------------------------------------------------
 * 打印设计
 +------------------------------------------------------------------------------
 * 对整页的打印布局和打印风格进行界面设计，它与打印维护的区别是具有打印项增删功能
 +------------------------------------------------------------------------------
 */
function printerDesign()
{
	var lodop_id;
	if(arguments.length < 1)
		lodop_id = 0
	else if(typeof(arguments[0]) != "number")
		lodop_id = 0;
	else
		lodop_id = arguments[0];
		
	if(typeof(pageDesign)=="function")
	{
		pageDesign();
	}
	else
	{
		alert("页面未设置打印！");
	}
		
	if($(LODOP).size() > 0)
		LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW","true");
		LODOP.PRINT_DESIGN();
}

function setPageSizeAndOrient(orient, pageName, lodop_id)
{
	/**
		* intOrient：
		* 打印方向及纸张类型，数字型，
		* 1---纵(正)向打印，固定纸张； 
		* 2---横向打印，固定纸张； 
		* 3---纵(正)向打印，宽度固定，高度按打印内容的高度自适应；
		* 0(或其它)----打印方向由操作者自行选择或按打印机缺省设置；
	*/
	if(!arguments[0]) orient = 1;
	if(!arguments[1]) pageName = "A4";
	if(!arguments[2]) lodop_id = 0;
	
	var pageWidth = 0;
	var pageHeight = 0;
	if(pageName=="16k")
		{
			pageWidth = 1850;
			pageHeight = 2600;
			LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","80%");
		}
	LODOP.SET_PRINT_PAGESIZE(orient,pageWidth,pageHeight,pageName);;

}

function setPrinterByName(printer_name, lodop_id)
{
	 if(!arguments[0]) printer_name = "A4";
	 if(!arguments[1]) lodop_id = 0;
	 
	 var printer_id = -1;
	
	var printer_count = LODOP.GET_PRINTER_COUNT();
	var temp_printer_name = " ";
	for(var i = 0; i < printer_count; i++)
	{
		temp_printer_name = LODOP.GET_PRINTER_NAME(i);
		if(temp_printer_name.indexOf(printer_name) > -1)
		{
			printer_id = i;
			LODOP.SET_PRINTER_INDEX(printer_id);
			break;
		}
	}
	
}

function setBackgroundImg(img_url, lodop_id, top, left)
{
	if(!arguments[0]) img_url = "";
	if(!arguments[1]) lodop_id = 0;
	if(!arguments[2]) top = "10mm";
	if(!arguments[3]) left = "18mm";
	
	var img_label = "<img src='" + img_url + "' />";
	LODOP.ADD_PRINT_SETUP_BKIMG(img_label);
	LODOP.SET_SHOW_MODE("BKIMG_TOP",top);
	LODOP.SET_SHOW_MODE("BKIMG_LEFT",left);
	LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW","true");
}

function addImage(img_url,lodop_id)
{
	var img_label = "<img src='" + img_url + "' />";
	if(document_type.toLowerCase().indexOf("showsancedan")!=-1)
		LODOP.ADD_PRINT_IMAGE(20,50,800,1200,img_label);
	else if(document_type.toLowerCase().indexOf("showzhikongtu")!=-1)
		LODOP.ADD_PRINT_IMAGE(64,10,1200,750,img_label);
	else
		LODOP.ADD_PRINT_IMAGE(50,94,750,1200,img_label);
}

function calculatePageRange(start_pos,end_pos,page_height)
{
	if(!arguments[0]) start_pos = 0;
	if(!arguments[1]) end_pos = 0;
	if(!arguments[2]) page_height = 1;
	
	start_page = parseInt(start_pos/page_height+1);
	end_page = parseInt(end_pos/page_height+1);
}
