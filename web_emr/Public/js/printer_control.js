/**************************************************
*  Created:  2012-11-01
*  Info:天坦打印控制器
*  @Tiantanhehe (C)2011-3011 Tiantanhehe
*  @Author DongJie <dongjie@tiantanhehe.com>
*  @Author LiHui <LiHui@tiantanhehe.com>
*  @Version 3.0
*  @Updated History:  
***************************************************/

var background_url = "";
var img_yemei_url ="";
var server_url = "";
var zhuyuan_id = "";
var patient_xingming = "";
var document_name = "";
var start_page = 1;
var end_page = 1;
var document_type = "";
var document_id = "";
var document_relate_table = "";
var document_name = "";
var searchString = "";
var page_type = "";
var printer_type = "";
var print_direction = "";
var page_left_dis = "22mm";
var page_top_dis = "8mm";
var page_bottom_dis = "BottomMargin:8mm";
var page_right_dis = "RightMargin:12mm";
var have_yemei_yejiao = false;
var have_yizhu_qianming = false;
var have_image = false;
var part_print_mode = true;
var part_print_start_page = 0;
var show_page_number = false;
var hospital_name = "";
var title = "";
var patient_name = "";
var bingqu = "";
var chuanghao = "";
var zhuyuanhao = "";

var page_size_adjust = 0;
var page_number = "0";

var new_content_count=0;

var hold_controller=true;
var initial_controller=true;

var yemei_mode = "v1";

function printControlInitial()
{
	current_url = window.location.href;
	var yizhu_item = "";
	var yizhu_type = "";
	if(current_url.indexOf("Yizhuguanli/showChangqi")!=-1)
	{
		if(operator_type=="yishi")
		{
			yizhu_item = '<input type="button" name="add_new" class="quick_menu_button" value="添加医嘱" />'+
						'<input type="button" name="start_all" class="quick_menu_button" value="开始所有" />'+
						'<input type="button" name="stop_all" class="quick_menu_button" value="停止所有" />'+
						'<input type="button" name="rebuilt_yizhu" class="quick_menu_button" value="重整所有" />'+
					'</select>';
		}
		else
		{
			yizhu_item = '<input type="button" name="start_all" class="quick_menu_button" value="所有正确" />'+
									'<input type="button" name="stop_all" class="quick_menu_button" value="所有停止" />';
		}
		yizhu_type = '<input type="button" name="yizhu_type_changyi" class="selected_button" value="长期医嘱" />'+
								 '<input type="button" name="yizhu_type_linshi" class="quick_menu_button" value="临时医嘱" />'+
								 ' 医嘱状态:<input type="button" name="yizhu_type_changqi_kaishizhixing" class="quick_menu_button" value="开始执行" />'+
								 '<input type="button" name="yizhu_type_changqi_xintianjia" class="quick_menu_button" value="新添加" />'+
								 '<input type="button" name="yizhu_type_changqi_daihedui" class="quick_menu_button" value="待核对" />'+
								 '<input type="button" name="yizhu_type_changqi_yizhuyouwu" class="quick_menu_button" value="医嘱有误" />'+
								 '<input type="button" name="yizhu_type_changqi_daitingzhiqueren" class="quick_menu_button" value="待停止确认" />'+
								 '<input type="button" name="yizhu_type_changqi_tingzhizhixing" class="quick_menu_button" value="停止执行" />';
		$("body").append(
			'<div class="top_piaofu_yizhu" >'+yizhu_item+'</div>'
		);
	}
	else if(current_url.indexOf("Yizhuguanli/showLinshi")!=-1)
	{
		if(operator_type=="yishi")
		{
			yizhu_item = '<input type="button" name="add_new" class="quick_menu_button" value="添加医嘱" />'+
						'<input type="button" name="kaishi_all" class="quick_menu_button" value="开始所有" />'+
						'<input type="button" name="add_supplement" class="quick_menu_button" value="补录医嘱" />';
		}
		else
		{
			yizhu_item = '<input type="button" name="right_all" class="quick_menu_button" value="所有正确" />'+
				   '<input type="button" name="zhixing_all" class="quick_menu_button" value="执行所有" />';
		}
		yizhu_type = '<input type="button" name="yizhu_type_changyi" class="quick_menu_button" value="长期医嘱" />'+
								 '<input type="button" name="yizhu_type_linshi" class="selected_button" value="临时医嘱" />'+
								 ' 医嘱状态:<input type="button" name="yizhu_type_linshi_zhixingwanbi" class="quick_menu_button" value="执行完毕" />'+
								 '<input type="button" name="yizhu_type_linshi_daihedui" class="quick_menu_button" value="待核对" />'+
								 '<input type="button" name="yizhu_type_linshi_yixiada" class="quick_menu_button" value="已下达" />'+
								 '<input type="button" name="yizhu_type_linshi_yizhuyouwu" class="quick_menu_button" value="医嘱有误" />'+
								 '<input type="button" name="yizhu_type_linshi_xintianjia" class="quick_menu_button" value="新添加" />';
								 ;
		$("body").append(
			'<div class="top_piaofu_yizhu" >'+yizhu_item+'</div>'
		);
	}
	
	if(action_url.search("saveMubanBingli")>0){
		
		$("body").append(
		'<div class="top_piaofu" >'+yizhu_type+'<div class="top_piaofu_b" id="jiacu" title="加粗"></div>'+
		'<div class="top_piaofu_b" id="shangbiao" title="上标"></div>'+
		'<div class="top_piaofu_b" id="xiabiao" title="下标"></div>'+
		'<div class="top_piaofu_b" id="xieti" title="斜体"></div>'+
		'<div class="top_piaofu_b" id="chexiao" title="撤销"></div>'+
		'<div class="top_piaofu_b" id="huifu" title="恢复"></div>'+
			'<form class="ajax_form" method="post" action="'+action_url+'" style="float:right; padding:0px;">'+
				'<input type="button" id="guifan_geshi" class="quick_menu_button" value="自动排版" />'+
				'<input type="button" id="show_print_selector" class="quick_menu_button" value="接续打印" />'+
				'<input type="submit" id="submit" class="quick_menu_button_special" value=" 保 存 " />'+
				'<input type="hidden" name="muban_id" value="'+zhuyuan_id+'" />'+
				'<input type="hidden" name="bingli_type" value="'+bingli_type+'"/>'+
				'<div id="sancedan_week_info"></div>'+
				'<div name="ajax_dynamic_content"></div>'+
			'</form>'+
		'</div>'+
		'<a href="#bottom" class="go_bottom" alt="点我,快速浏览页面最下面的内容" title="点我,快速浏览页面最下面的内容"></a>'+
		'<a href="#top" class="go_top" alt="点我,快速返回页面最顶端" title="点我,快速返回页面最顶端"></a>'
		);
	}else{
		
		$("body").append(
			'<div class="top_piaofu" >'+yizhu_type+'<div class="top_piaofu_b" id="jiacu" title="加粗"></div>'+
			'<div class="top_piaofu_b" id="shangbiao" title="上标"></div>'+
			'<div class="top_piaofu_b" id="xiabiao" title="下标"></div>'+
			'<div class="top_piaofu_b" id="xieti" title="斜体"></div>'+
			'<div class="top_piaofu_b" id="chexiao" title="撤销"></div>'+
			'<div class="top_piaofu_b" id="huifu" title="恢复"></div>'+
				'<form class="ajax_form" method="post" action="'+action_url+'" style="float:right; padding:0px;">'+					
					'<input type="button" id="guifan_geshi" class="quick_menu_button" value="自动排版" />'+
					'<input type="button" id="show_print_selector" class="quick_menu_button" value="接续打印" />'+
					'<input type="submit" id="submit" class="quick_menu_button_special" value=" 保 存 " />'+
					'<input type="hidden" name="zhuyuan_id" value="'+zhuyuan_id+'" />'+
					'<div id="sancedan_week_info"></div>'+
					'<div name="ajax_dynamic_content"></div>'+
				'</form>'+
			'</div>'+
			'<a href="#bottom" class="go_bottom" alt="点我,快速浏览页面最下面的内容" title="点我,快速浏览页面最下面的内容"></a>'+
			'<a href="#top" class="go_top" alt="点我,快速返回页面最顶端" title="点我,快速返回页面最顶端"></a>'
			);
	}
	
	//监听鼠标坐标，控制顶部菜单：
	if(initial_controller==false)
		$(".top_piaofu").hide();
	$(document).mousemove(function(e){
		e = window.event || e;
		if(e.clientY<100)
		{
			$(".top_piaofu").slideDown();
		}
		else
		{
			if(hold_controller==false)
				$(".top_piaofu").slideUp();
		}
	});
	//快速跳转控制：
	//根据浏览位置隐藏或者显示快速跳转按钮：
	$(".go_top").hide();
	$(window.document).scroll(function () {
		if($(window.document).scrollTop() < 100)
			$(".go_top").hide();
		else
			$(".go_top").show();
			
		if($(window.document).scrollTop() >= $(document).height()-$(window).height())
			$(".go_bottom").hide();
		else
			$(".go_bottom").show();
	});

	$(".page").append('<div id="print_cover_top" class="view_cover"></div>');
	$(".page").append('<div id="print_cover_left" class="view_cover"></div>');
	$(".page").append('<div id="print_cover_right" class="view_cover"></div>');
	$(".page").append('<div id="print_cover_bottom" class="view_cover"></div>');
	$(".page").append('<div id="print_selector">请拖拽此区域以圈选打印范围</div>');
	$("#print_selector").hide();
	setPrintCover(0,0,0,0,"normal");
	if(current_url.indexOf("Yizhuguanli/showChangqi")!=-1)
	{
		yizhuChangqiFuc();
	}
	else if(current_url.indexOf("Yizhuguanli/showLinshi")!=-1)
	{
		yizhuLinshiFuc();
	}
	$("#add_new_bingcheng").click(function(){
		var bingcheng_date = $("#current_time").val();
		new_content_count++;
		$(".page").append(
			'<div class="blank" ></div>'+
			'<table id="new" border="0" cellpadding="0" cellspacing="0" class="content_table_without_border" database_table_name="zhuyuan_bingcheng_jilu">'+
				'<tr>'+
					'<td width="180"> <input type="text" id="onebingcheng_new'+new_content_count+'_record_time" name="onebingcheng_new'+new_content_count+'_record_time" class="ajax_input" value="'+bingcheng_date+'"/></td>'+
					'<td bingcheng_sub_leibie="日常病程记录" chafang_doctor="" class="info_center_title bingcheng_title" huanzhe_jiashu_qianzi="不需要" class="info_center_title bingcheng_title" width="400" contenteditable="true"><span name="bingcheng_title"><span id="chafang_doctor" name="onebingcheng_new'+new_content_count+'_chafang_doctor" class="hidden_info" contenteditable="true"></span><span id="bingcheng_sub_leibie" name="onebingcheng_new'+new_content_count+'_bingcheng_sub_leibie" class="hidden_info" contenteditable="true">日常病程记录</span><span id="huanzhe_jiashu_qianzi" name="onebingcheng_new'+new_content_count+'_huanzhe_jiashu_qianzi" class="hidden_info" contenteditable="true">日常病程记录</span></span></td>'+
					'<td width="140" class="info_area" ></td>'+
				'</tr>'+
				'<tr>'+
					'<td colspan="3" class="info_block"><div name="onebingcheng_new'+new_content_count+'_content" contenteditable="true">'+
					'</td>'+
				'</tr>'+
			'</table>'+
			'<table id="2" border="0" cellpadding="0" cellspacing="0" class="content_table_without_border" database_table_name="zhuyuan_bingcheng_jilu">'+
				'<tr>'+
					'<td width="450px" ></td>'+
					'<td class="info_title" name="doctor_name"></td>'+
				'</tr>'+
			'</table>'+
			'</div>'
		);
		$('.ajax_input').datetimepicker({
			timeFormat: 'hh:mm',
			dateFormat: 'yy-mm-dd'
		});
		$('html, body').animate({scrollTop: $(document).height()}, 'fast');
		$('[contenteditable="true"]').addClass("editable");
		
		if($("title").html()=="死亡病例讨论记录")
		{
			$("[bingcheng_sub_leibie='日常病程记录'] #bingcheng_sub_leibie").html("死亡病例讨论记录");
			$("[bingcheng_sub_leibie='日常病程记录'] #bingcheng_sub_leibie").removeClass("hidden_info");
			$("[bingcheng_sub_leibie='日常病程记录']").attr("bingcheng_sub_leibie","死亡病例讨论记录");
		}
	});
	
	$("#show_print_selector").click(function(){
		if($(this).val()=="接续打印")
		{
			$(this).val("取消遮盖");
			var temp_top = 0;
			$.ajaxSettings.async = false;
			$.getJSON(
				"http://"+server_url+"/web_emr/Home/Data/getDayinJilu",
				{
					"document_id":document_id,
					"document_relate_table":document_relate_table,
					"print_type":"memory"
				},
				function(data){
					temp_top = data[0].part_print_top;
				}
			);
			$.ajaxSettings.async = true;
			$("#print_selector").show();
			var top_pos = $(document).scrollTop();
			if(temp_top!=0 && $(".page").height()>temp_top)
			{
				top_pos = temp_top;
				$(document).scrollTop(temp_top);
				$("#print_selector").css({'top':temp_top+"px"});
				var temp_height = $(".page").height()-temp_top;
				$("#print_selector").css({'height':temp_height+"px"});
			}
			var left_pos = $( "#print_selector" ).offset().left-$( ".page" ).offset().left;
			var width_pos = $( "#print_selector" ).width();
			var height_pos = $( "#print_selector" ).height();

			setPrintCover(top_pos,left_pos,width_pos,height_pos,"normal");
			
			//清空多余内容：
			//1. 隐藏显示时的边框
			$('[contenteditable="true"]').removeClass("editable");
			//2. 隐藏空的专科检查
			if($('[name="ruyuan_zhuanke_jiancha"]').text().length < 2)
			{
				$('#ruyuan_zhuanke_jiancha_title').hide();
				$('#ruyuan_zhuanke_jiancha_content').hide();
			}
			
			//计算打印区域的宽度：
			$( "#print_selector" ).resizable({
				maxWidth: $(".page").width(),
				maxHeight: $(".page").height(),
				helper: "ui-resizable-helper",
				handles:"n, e, s, w, ne, se, sw, nw",
				stop: function( event, ui ){
				var top_pos = $( "#print_selector" ).offset().top-$( ".page" ).offset().top;
				var left_pos = $( "#print_selector" ).offset().left-$( ".page" ).offset().left;
				var width_pos = $( "#print_selector" ).width();
				var height_pos = $( "#print_selector" ).height();
				setPrintCover(top_pos,left_pos,width_pos,height_pos,"normal");
				}
			});
			$( "#print_selector" ).draggable({
				containment: "parent",
				stop: function( event, ui ){
				var top_pos = ui.position.top;
				var left_pos = ui.position.left;
				var width_pos = $( "#print_selector" ).width();
				var height_pos = $( "#print_selector" ).height();
				setPrintCover(top_pos,left_pos,width_pos,height_pos,"normal");
				}
			});
		}
		else
		{
			$(this).val("接续打印");
			$("#print_selector").hide();
			hidePrintCover();
			$('[contenteditable="true"]').addClass("editable");
			$('#ruyuan_zhuanke_jiancha_title').show();
			$('#ruyuan_zhuanke_jiancha_content').show();
		}
	});
	
	$(".delete_one_bingcheng").click(function(){
		if (confirm('是否确认进行删除操作？'))
		{
			var post_param = {};
			post_param['bingcheng_id'] = $(this).attr("bingcheng_id");
			$.post("http://"+server_url+"/web_emr/ZhuyuanYishi/Bingli/deleteOneBingcheng", post_param,function(data){
				window.setTimeout(auto_close,2000); 
				if(data.result=="true"){
					$("body").qtip({
								content: data.message,
								style: {
									  'font-size': 30,
									  border: {
										 width: 5,
										 radius: 5
									  },
									  padding: 10, 
									  textAlign: 'center',
									  name: 'green',
									  width: { max: 600 }
							   },
								position: {
											  corner: {
												 target: 'topMiddle',
												 tooltip: 'topMiddle'
											  }
										   },
								show: {
										  delay:0,
										  solo:true,
										  when:false,
										  ready: true
									   },
							   hide: {
										  delay:500,
										  when: {event: 'click'}
									   }
							}); 
				}
				else{
					$("body").qtip({
							    content: data.message,
								style: {
										  'font-size': 30,
										  border: {
													 width: 5,
													 radius: 5
										  		  },
										  padding: 10, 
										  textAlign: 'center',
										  name: 'red',
										  width: { max: 600 }
							   			},
								position: {
											  corner: {
														 target: 'topMiddle',
														 tooltip: 'topMiddle'
											  }
										   },
								show: {
										  delay:0,
										  solo:true,
										  when:false,
										  ready: true
									   },
							     hide: {
										  delay:500,
										  when: {event: 'unfocus'}
									    }
						});
						
				}
			}, "json");
			//进行删除操作：
			$("[div_bingcheng_id='"+$(this).attr("bingcheng_id")+"']").remove();
		}
		else
			return false;
	});
}

function setPrintCover(top_pos,left_pos,width_pos,height_pos,print_type)
{
	if(top_pos!=0||left_pos!=0||width_pos!=0||height_pos!=0)
	{
		var page_width = $(".page").width();
		var page_height = $(".page").height();
		
		$("#print_cover_top").css({'display':'block','top':0,'left':0,'width':page_width,"height":top_pos});
		$("#print_cover_left").css({'display':'block','top':top_pos,'left':0,'width':left_pos,"height":height_pos+24});
		$("#print_cover_right").css({'display':'block','top':top_pos,'left':left_pos+width_pos+24,'width':page_width-width_pos-left_pos-24,"height":height_pos+24});
		$("#print_cover_bottom").css({'display':'block','top':top_pos+height_pos+24,'left':0,'width':page_width,"height":page_height-top_pos-height_pos-24});
		
		$("#print_selector").css({'top':top_pos});
		

	}
	
	//ajax上传打印记录；
	setDaYinJiLu(document_id,document_relate_table,top_pos,left_pos,width_pos,height_pos,print_type);
}

function setPrintCoverPrint(top_pos,left_pos,width_pos,height_pos)
{
	if(top_pos!=0||left_pos!=0||width_pos!=0||height_pos!=0)
	{
		top_pos = parseInt(top_pos);
		left_pos = parseInt(left_pos);
		width_pos = parseInt(width_pos);
		height_pos = parseInt(height_pos);

		var page_width = $(".page").width();
		var page_height = $(".page").height();

		//计算从第几页开始续打的:
		var first_page_height = 0;
		var pageHeight = 1080;
		var current_url_temp = window.location.href;
		if(current_url_temp.toLowerCase().indexOf("bingcheng")!=-1)
		{
			first_page_height = 60;
			pageHeight = 1080;
		}
		else if(current_url_temp.toLowerCase().indexOf("ruyuanjilu")!=-1)
		{
			first_page_height = 80;
			pageHeight = 1080;
		}
		else
		{
			first_page_height = 0;
			pageHeight = 1040;
		}

		part_print_start_page = parseInt((top_pos+first_page_height)/pageHeight)+1;

		//遮罩高度修正：
		var top_refine = -0;
		if(current_url_temp.toLowerCase().indexOf("bingcheng")!=-1)
		{
			top_refine = 5;
		}
		var hieght_refine = 23; 
		var left_refine = -0;
		var width_refine = 23;
		//alert(top_refine);
		$("#print_cover_top").css({'display':'block','top':0,'left':0,'width':page_width,"height":top_pos+top_refine});
		$("#print_cover_left").css({'display':'block','top':top_pos+top_refine,'left':0,'width':left_pos,"height":height_pos+hieght_refine});
		$("#print_cover_right").css({'display':'block','top':top_pos+top_refine,'left':left_pos+width_pos+width_refine,'width':page_width-width_pos-width_refine-left_pos,"height":height_pos+hieght_refine});
		$("#print_cover_bottom").css({'display':'block','top':top_pos+top_refine+height_pos+hieght_refine,'left':0,'width':page_width,"height":page_height-(top_pos+top_refine)-(height_pos+hieght_refine)});
		
		$('td').each(function() {
			//判断此标签是否会落在遮罩范围内
			var top_distance = $(this).offset().top - top_pos - top_refine - height_pos - hieght_refine;
			var bottom_distance = $(this).offset().top+$(this).height() - top_pos;
			//顶部在遮罩区域下
			if(top_distance>0)
			{
				$(this).css("color","white");
				$(this).css("border","white");
			}
			//底部在遮罩区域上
			if(bottom_distance<0)
			{
				$(this).css("color","white");
				$(this).css("border","white");
			}
		});
		
		$('p').each(function() {
			//判断此标签是否会落在遮罩范围内
			var top_distance = $(this).offset().top - top_pos - top_refine - height_pos - hieght_refine;
			var bottom_distance = $(this).offset().top+$(this).height() - top_pos;
			//顶部在遮罩区域下
			if(top_distance>0)
			{
				$(this).css("color","white");
				$(this).css("border","white");
			}
			//底部在遮罩区域上
			if(bottom_distance<0)
			{
				$(this).css("color","white");
				$(this).css("border","white");
			}
		});
	}
}

function hidePrintCover()
{
	$("#print_cover_top").hide();
	$("#print_cover_left").hide();
	$("#print_cover_right").hide();
	$("#print_cover_bottom").hide();
	//ajax上传打印记录；
	setDaYinJiLu(document_id,document_relate_table,"0","0","0","0","normal");
}

function showPrintCover()
{
	$(".page").append('<div id="print_cover_top" class="print_cover"></div>');
	$(".page").append('<div id="print_cover_left" class="print_cover"></div>');
	$(".page").append('<div id="print_cover_right" class="print_cover"></div>');
	$(".page").append('<div id="print_cover_bottom" class="print_cover"></div>');
	getDaYinJiLu(document_id,document_relate_table);
}

function controlPrinterByLodop()
{
	//调用部分打印函数
	if(document_id!=""&&document_relate_table!="")
		showPrintCover();

	var page_hieght = 1;
	
	if(document_relate_table == "住院病案首页")
	{
		page_left_dis = "12mm";
		page_top_dis = "20px";
		page_bottom_dis = "300mm";
		page_right_dis = "210mm";
		page_height = 1200;
		printer_type = "16k";
		page_type = "16k";
		print_direction = "1";
	}
	else
	{
		page_left_dis = "15mm";
		page_top_dis = "110px";
		page_bottom_dis = "265mm";
		page_right_dis = "210mm";
		page_height = 1200;
		printer_type = "16k";
		page_type = "16k";
		print_direction = "1";
	}

	$.importLodop();
}

//页面设计函数，控制页眉页脚、打印格式、打印方式等功能
function pageDesign()
{
	if($.checkIsInstall())
	{
		if(have_image==true)
			$("body").parent().creatHtmPrint({is_new_LODOP:true,top:page_top_dis,left:page_left_dis,width:page_right_dis,height:page_bottom_dis});
		else
			$.creatUrlPrint({is_new_LODOP:true,top:page_top_dis,left:page_left_dis,width:page_right_dis,height:page_bottom_dis,print_url:window.location.href});
		
		//再次调用部分打印函数以更新页眉页脚参数
		if(document_relate_table == "住院病案首页")
		{
			
		}
		else
		{
			if(document_id!=""&&document_relate_table!="")
				showPrintCover();
			if(part_print_mode==false )
				yemeiyejiaoBingli(bingli_document_title,user_department,zhuyuan_chuanghao,patient_xingming,zhuyuan_id);
			else if(document_name!="")
				yemeiyejiaoBingliPrintMode(bingli_document_title,user_department,zhuyuan_chuanghao,patient_xingming,zhuyuan_id);
		}
		setPageSizeAndOrient(print_direction, page_type, 0);
	}
}

//增加病历的页眉页脚
function yemeiyejiaoBingli(bingli_document_title,user_department,zhuyuan_chuanghao,patient_xingming,zhuyuan_id){
	//添加页眉
	$.addYemei({bingli_document_title:bingli_document_title,user_department:user_department,zhuyuan_chuanghao:zhuyuan_chuanghao,patient_xingming:patient_xingming,zhuyuan_id:zhuyuan_id});
	
	//添加页号
	$.addYehao();
}

//增加病历的页眉页脚-局部打印状态
function yemeiyejiaoBingliPrintMode(bingli_document_title,user_department,zhuyuan_chuanghao,patient_xingming,zhuyuan_id){
	//添加页眉
	$.addYemeiLimitedPage({bingli_document_title:bingli_document_title,user_department:user_department,zhuyuan_chuanghao:zhuyuan_chuanghao,patient_xingming:patient_xingming,zhuyuan_id:zhuyuan_id,start_page:(part_print_start_page)});
	
	//添加页号
	$.addYehaoLimitedPage({start_page:(part_print_start_page)});
}

//得到打印记录
function getDaYinJiLu(document_id,document_relate_table)
{
	$.ajaxSettings.async = false;
	$.getJSON(
		"http://"+server_url+"/web_emr/Home/Data/getDayinJilu",
		{
			"document_id":document_id,
			"document_relate_table":document_relate_table
		},
		function(data){

			setPrintCoverPrint(data[0].part_print_top,data[0].part_print_left,data[0].part_print_width,data[0].part_print_height);
			if(data[0].part_print_top==0&&data[0].part_print_left==0&&data[0].part_print_width==0&&data[0].part_print_height==0)
				part_print_mode = false;
			else
				part_print_mode = true;
		}
	);
}

//设置打印记录
function setDaYinJiLu(document_id,document_relate_table,part_print_top,part_print_left,part_print_width,part_print_height,print_type)
{
	$.get(
		"http://"+server_url+"/web_emr/Home/Data/setDayinJilu",
		{
			"document_id":document_id,
			"document_relate_table":document_relate_table,
			"part_print_top":part_print_top,
			"part_print_left":part_print_left,
			"part_print_width":part_print_width,
			"part_print_height":part_print_height,
			"print_type":print_type
		}, 
		function(data){
		}
	);
}