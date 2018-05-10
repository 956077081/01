/**************************************************
*  Created:  2013-09-01
*  Info:病历编辑器
*  @Tiantanhehe (C)2011-3011 Tiantanhehe
*  @Author Tianran <tianran@tiantanhehe.com>
*  @Version 1.0
*  @Updated History:  
***************************************************/

 /********************定时器相关参数**********************/
var timer = null;							//定时器id
var chinese_enter_check = null;		//检测是否按下回车的定时器
var interval_time = 500;					//定时器间隔时间
// var excute_times = 0;					//定时器已执行次数

var is_chinese = false;						//是否中文输入状态（keycode=299）
var is_keyup = true;						//是否回车键（中文输入时回车不触发keyup）
var keydown_times = 0;						//连续按键次数(按下不松开)

/********************光标定位相关变量********************/
var current_editor_id = null;				//当前所在div
var current_cursor_position = 0;			//当前光标相对div的位置
var current_selection = null;				//当前选中的selection，有可能是range（Range），也或者是光标/符号（Caret）
var current_anchorNode = null;				//当前选中节点
var current_anchorOffset = 0;				//当前光标相对选中节点的offset
var previous_position = 0;


/********************文本处理引擎*********************/
var current_tips = null;
var text_process_timer = null;
var tiantanee = null;
var keyword = null;

/********************三级医师相关参数*********************/
var is_revise = false;						//是否修订模式
var postil_number = 0;						//批注号（可做批注数量、id使用）

var zhuyuanyishi_id;						//重新声明页面中声明的变量，但不赋值
var user_department_position;
var user_name;
var user_number;
var revise;
var show_page_number;


/********************编辑器相关参数*********************/
var is_saved = true;						//是否已经保存
var is_auto_save = true;					//是否开启自动保存
var auto_save_interval = 60000;				
var auto_save_timer = null;					//自动保存定时器
var validate_flag = true;
var saved = true;
var multi_media_engine = "on";

/********************与录音有关的参数*********************/
var leftChannel = [];
var recorder = null;
var recording = false;
var recordingLength = 0;
var volume = null;
var audioInput = null;
var sampleRate = 44100;
var audioContext = null;
var context = null;
var outputElement = document.getElementById('output');
var outputString;
var inputSampleRate = 8000;

/********************表单相关事件的配置参数************/
var form_options={
	dataType: 'json',
	beforeSerialize:function validateForm(){
			if($('input:[name="zhuyuan_id"]').length==0)
				$("[name='ajax_dynamic_content']").append('<input type="hidden" name="zhuyuan_id"/>');
			$('input:[name="zhuyuan_id"]').val(zhuyuan_id);
			if($('input:[name="bingli_type"]').length==0)
				$("[name='ajax_dynamic_content']").append('<input type="hidden" name="bingli_type"/>');
			$('input:[name="bingli_type"]').val(bingli_type);
			if($('input:[name="yiyuan_id"]').length==0)
				$("[name='ajax_dynamic_content']").append('<input type="hidden" name="yiyuan_id"/>');
			$('input:[name="yiyuan_id"]').val(yiyuan_id);
		editor_order = 0;
		$('#bingli_content').each(function(){
			editor_order++;
			if($('input:[order_name="'+$(this).attr("name")+'_'+editor_order+'"]').length==0)
				$("[name='ajax_dynamic_content']").append('<input type="hidden" order_name="'+$(this).attr("name")+'_'+editor_order+'" name="'+$(this).attr("name")+'"/>');
			$('input:[order_name="'+$(this).attr("name")+'_'+editor_order+'"]').val($(this).html());
		});
	},
	success:function showResponse(data){
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
	}
};


$(function(){
	$("head").append("<script type=\"text/javascript\" src=\"/web_emr/Public/js/artDialog/plugins/iframeTools.js\"></script>");

	/********************初始化*********************/
	//边框样式控制
	$('[contenteditable="true"]').addClass("editable");
	
	//选中默认编辑器
	if($('[contenteditable="true"]:first').attr("name")!="")
	{
		current_editor_id = $('[contenteditable="true"]:first').attr("name");
	}
	//是否启动自动保存
	if(is_auto_save)
	{
		//开启
		auto_save_timer = window.setInterval(auto_save,auto_save_interval); 
	}
	else
	{
		//关闭
		if(auto_save_timer!=null)
		{
			window.clearInterval(auto_save_timer);
		}
	}

	// 是否开启修订
	if(revise=="on"&&(user_number!=zhuyuanyishi_id))
	{
		is_revise = true;
	}

	//判断浏览器类型
	var isChrome = navigator.userAgent.toLowerCase().match(/chrome/) != null;
	if (isChrome)
	{
			window.onbeforeunload = function(event){
			if(saved===false)
			{
				window.location.href += "###";
				var returnValue = "您可能有未保存的内容，请选择是否继续离开或者确认保存后离开";
				return returnValue;
			}
		}
	}
	else
	{
		window.onbeforeunload = function(){
			if(saved===false)
			{
				window.location.href += "###";
				event.returnValue = "您可能有未保存的内容，请选择是否继续离开或者确认保存后离开";
			}
		}
	}
	
	/********************事件控制*********************/
	//获得焦点时启用定时器
	$("[contenteditable='true']").live("mousedown",function(e)
	{
		saved=false;
		current_editor_id  = $(this).attr("name");
	});

	//click
	$('[contenteditable="true"]').live("mouseup",function(e){
		// 清空所有[onEdditing='true'](避免点击一个标签改变多处的问题)
		$("[onEdditing='true']").each(function(){
			$(this).attr("onEdditing","false");
		});
	});

	//keydown
	$('[contenteditable="true"]').live("keydown",function(e)
	{
		// keyCode=299说明在中文输入法状态
		if (e.keyCode==229) 
		{
			is_chinese = true;
		}
		else
		{
			switch(e.keyCode)
			{
				//向上：
				case 38:
					if(current_cursor_position==0)
					{
						var all_editor = $('div[contenteditable="true"]').get();;
						for(editor_id = 0;editor_id<all_editor.length;editor_id++)
						{
							if(all_editor[editor_id].innerHTML==$(this).html()&&editor_id!=0)
							{
								all_editor[editor_id-1].focus();
							}
							
						}
					}
					break;

				//向下：
				case 40:
					if(current_cursor_position==$(this).text().length)
					{
						var all_editor = $('div[contenteditable="true"]').get();;
						for(editor_id = 0;editor_id<all_editor.length;editor_id++)
						{
							if(all_editor[editor_id].innerHTML==$(this).html()&&editor_id!=all_editor.length-1)
							{
								all_editor[editor_id+1].focus();
							}
						}
					}
					break;
			}
		}
	});

	//文字格式控制
	$(".top_piaofu_b").mousedown(function(){
		if(document.selection)
		{
			var xuanzhong = document.selection;
			var title = $(this).attr("title");
			if(xuanzhong!='')
			{
				if(title == '加粗')
				{
					document.execCommand('Bold');
				}
				else if(title == '斜体')
				{
					document.execCommand('Italic');
				}
				else if(title == '上标')
				{
					document.execCommand('SuperScript');
				}
				else if(title == '下标')
				{
					document.execCommand('SubScript');
				}
				else if(title == '更改文字颜色')
				{
					var rgb = $("#color_board_fore").val();
					document.execCommand('ForeColor',true,rgb);
				}
				else if(title == '更改背景颜色')
				{
					var rgb = $("#color_board_back").val();
					document.execCommand('BackColor',true,rgb);
				}
			}
			else
			{
				if(title == '撤销')
				{
					document.execCommand('Undo');
				}
				else if(title == '恢复')
				{
					document.execCommand('Redo');
				}
				else if(title=='符号')
				{
					var offset = $("[title='符号']").offset();
					var x_position = offset.left;
					var y_position = offset.top;

					art.dialog.open("/web_emr/Home/System/symbol",{
						id:"symbol_dialog",
						title:'符号',
						// width: '1000',
					    // height: '50%',
					    // left: x_position+'px',
					    // top: y_position+'px',
					    left: '40%',
					    top: '30%',
					    fixed: true,
					    resize: false,
					    drag: false,
					    margin:0,
					    padding:0,
					    // lock: true,
					    close:function(){
							if(art.dialog.data("insert_symbol")!=null&&art.dialog.data("insert_symbol")=="yes")
							{
								insertContent(art.dialog.data("symbol"))
								art.dialog.data("insert_symbol","no");
							}
					    }
					});
				}
			}
		}
		return false;
	});

	$("body").live("mousedown",function(){
		if(typeof(art)!="undefined")
		{
			art.dialog({id:"symbol_dialog"}).close();
		}
	});

	$("#color_board_fore").live("change",function(){
		if(document.getSelection&&document.getSelection!="")
		{
			var rgb = $("#color_board_fore").val();
			document.execCommand('ForeColor',true,rgb);
		}
	});

	$("#color_board_back").live("change",function(){
		if(document.getSelection&&document.getSelection!="")
		{
			var rgb = $("#color_board_back").val();
			document.execCommand('BackColor',true,rgb);
		}
	});

	//自动排版
	$("#guifan_geshi").click(function(){
		auto_format();
	});
	
	//表单相关参数
	$(".ajax_form").ajaxForm(form_options);
	$('[type="submit"]').click(function(){
		saved=true;
	});

	//添加多媒体
	$("#add_media").click(function(){
		art.dialog.data("zhuyuan_id",zhuyuan_id);
		art.dialog.open("/web_emr/Home/Media",{
			title: '插入图片',
			width: '95%',
		    height: '90%',
		    left: '50%',
		    top: '50%',
		    fixed: true,
		    resize: false,
		    drag: false,
		    lock: true,
		    close:function(){
		    	if(art.dialog.data("insert")!=null&&art.dialog.data("insert")=="yes")
				{
					insertContent(art.dialog.data("media"))
					art.dialog.data("insert","no");
				}
		    }
		});
	});

	/**********************************radio型***********************************/
	$(".radio_checkbox").change(function(){
		var this_name = $(this).attr("name");
		$("[name='"+this_name+"']").each(function(){
			$(this).attr("checked",false);
		});
		$(this).attr("checked",true);
	});
	$(".radio_checkbox_label").click(function(){
		$(this).prev().trigger("click");
	});
	$(".radio_checkbox").each(function(){
		var this_name = $(this).attr("name");
		var check_flag = "true";
		$(".radio_checkbox[name='"+this_name+"']").each(function(){
			if($(this).attr("checked")=="checked")
			{
				check_flag = "false";
			}
		});
		if(check_flag=="true")
		{
			$(".radio_checkbox[name='"+this_name+"']:first").attr("checked",true);
		}
	});
	
	/**********************************选择框***********************************/
	//设置select的默认值（从数据库取出什么就是什么）
	$(".ajax_select").each(function(){
		var val = $(this).prev().val();
		if(val!=null&&val!="")
		{
			val = val.replace(new RegExp("<span(?:.|[\r\n])*?\>","gmi"),"");
			val = val.replace(new RegExp("</span>","gmi"),"");
			$(this).parent().find("option[value='"+val+"']").attr("selected","selected");
		}
			
		if(val=="----" || val=="<span class='alert'>----</span>")
		{
			$(this).css("background","red");
		}
		else
		{
			$(this).css("background","white");
		}
		$(this).parent().find("option").each(function(){
			if($(this).html()=="----")
			{
				$(this).css("background","red");
			}
			else
			{
				$(this).css("background","white");
			}
		});
		$(this).change(function(){
			if($(this).val()=="----" || $(this).val()=="<span class='alert'>----</span>")
			{
				$(this).css("background","red");
			}
			else
			{
				$(this).css("background","white");
			}
		});
	});
	
	//添加样式，设置宽为其父元素的宽（td）
	$(".basic_info").each(function(){
		if(!($(this).is("input")&&$(this).attr("type")=="number"))
		{
			$(this).css("width",$(this).parent().width());
			$(this).css("height",$(this).parent().height()-1);
		}
	});

	// 解决民族的alert问题
	if($("input[name='minzu']").val()=="<span class='alert'>----</span>")
	{
		$("input[name='minzu']").val("----");
	}
	
	if(show_page_number==true)
		showPageNum();
	
	try
	{
		parent.loadingEnd();
	}
	catch(ex)
	{
	}
});

//自动保存函数
//************************************************************************
function auto_save(){
	$(".ajax_form").submit();
}

//自动关闭提示语句
//************************************************************************
function auto_close(){
	$("body").qtip("hide");
}

/****************************在当前光标处快速插入快捷内容****************************/
function insertContent(module_info)
{	
	try
	{
		module_info = formulaProcessOneContent(module_info);
	}
	catch(e)
	{
	}

	saved = false;
	var str = module_info;
	var selection= document.getSelection ? document.selection : document.selection;
	var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
	if (!document.getSelection)
	{
		$('[name="'+current_editor_id+'"]').focus();
		var selection= document.getSelection ? document.selection : document.selection;
		var range= selection.createRang   ? selection.createRange() : selection.getRangeAt(0);
		range.pasteHTML(str);
		range.collapse(false);
		range.select();
	}
	else
	{
		$('[name="'+current_editor_id+'"]').focus();
		range.collapse(false);
		var hasR = range.createContextualFragment(str);
		var hasR_lastChild = hasR.lastChild;
		while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br")
		{
			var e = hasR_lastChild;
			hasR_lastChild = hasR_lastChild.previousSibling;
			hasR.removeChild(e);
		}
		range.insertNode(hasR);
		if (hasR_lastChild)
		{
			range.setEndAfter(hasR_lastChild);
			range.setStartAfter(hasR_lastChild);
		}
		selection.removeAllRanges();
		selection.addRange(range);
	}
}

/********************文字格式化函数********************/
function formatContent(div_content)
{
	var div_content_filter = div_content.split("</b>:");
	if(div_content_filter[1]!=null)
		div_content = div_content_filter[1];
	var temp_div_content = div_content;
	while(true)
	{
		temp_div_content = div_content;
		div_content = div_content.replace(new RegExp("<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)</span>","gmi"),"$1");
		if(temp_div_content==div_content)
		{
			break;
		}
	}
	while(true)
	{
		temp_div_content = div_content;
		div_content = div_content.replace(new RegExp("<span(?:[^<])*?\>(((?!</span>).)*<span id=\"formula\"><span id=\"fenshi\"><span id=\"before\">((?!</span>).)*</span><span id=\"up\">((?!</span>).)*</span><span id=\"spliter\">((?!</span>).)*</span><span id=\"down\">((?!</span>).)*</span><span id=\"after\">((?!</span>).)*</span></span></span>.*)</span>","gmi"),"$1");
		if(temp_div_content==div_content)
		{
			break;
		}
	}
	div_content = div_content.replace(new RegExp("<div(?:.|[\r\n])*?\>","gmi"),"<div>");
	div_content = div_content.replace(new RegExp("</div>","gmi"),"</div>");
	div_content = div_content.replace(new RegExp("<a(?:.|[\r\n])*?\>","gmi"),"");
	div_content = div_content.replace(new RegExp("</a>","gmi"),"");
	div_content = div_content.replace(new RegExp("<p(?:.|[\r\n])*?\>","gmi"),"<p>");
	div_content = div_content.replace(new RegExp("<o:p>","gmi"),"");
	div_content = div_content.replace(new RegExp("</o:p>","gmi"),"");
	div_content = div_content.replace(new RegExp("<p><br></p>","gmi"),"");
	div_content = div_content.replace(new RegExp("<br></p>","gmi"),"</p>");
	div_content = div_content.replace(new RegExp("<p></p>","gmi"),"");
	div_content = div_content.replace(new RegExp("<p><u></u></p>","gmi"),"");
	div_content = div_content.replace(new RegExp("<div><br></div>","gmi"),"");
	div_content = div_content.replace(new RegExp("<br></div>","gmi"),"</div>");
	div_content = div_content.replace(new RegExp("<div></div>","gmi"),"");
	if(div_content_filter[1]!=null)
		div_content = div_content_filter[0]+"</b>:"+div_content;
	
	return div_content;
}

//自动排版
function auto_format()
{
	var div_content = $("div[name='"+current_editor_id+"']").html();
	div_content = formatContent(div_content);
	try
	{
		div_content = formulaProcessOneContent(div_content);
	}
	catch(e)
	{
	}
	$("div[name='"+current_editor_id+"']").html(div_content);
	for(var i=0;i<current_cursor_position;i++)
	{
		document.selection.modify("move","right","character");
	}
}

//显示页码
function showPageNum(){
	//每页高度
	var first_page_height = 0;
	var pageHeight = 1080;
	var current_url_temp = window.location.href;
	if(current_url_temp.toLowerCase().indexOf("bingcheng")!=-1)
	{
		first_page_height = 60;
		pageHeight = 1040;
	}
	else if(current_url_temp.toLowerCase().indexOf("ruyuanjilu")!=-1)
	{
		first_page_height = 80;
		pageHeight = 1060;
	}
	else
	{
		first_page_height = 0;
		pageHeight = 1040;
	}
	// 总高度
	var totalHeight = $(document).height();
	var offset_top = pageHeight-first_page_height;
	var i = 1;
	while(offset_top<totalHeight)
	{
		$("body").append("<div class='page_number' offset_page_id='"+i+"'>↑第"+i+"页↑</div>");
		$("body").append("<div class='page_spliter' offset_page_spliter_id='"+i+"'></div>");
		$("[offset_page_id='"+i+"']").offset({top:offset_top-13});
		$("[offset_page_spliter_id='"+i+"']").offset({top:offset_top+13});
		offset_top+=pageHeight;
		i++;
	}
	$("body").append("<div class='page_number' offset_page_id='"+i+"'>↑第"+i+"页↑</div>");
	$("body").append("<div class='page_spliter' offset_page_spliter_id='"+i+"'></div>");
	$("[offset_page_id='"+i+"']").offset({top:totalHeight-13});
	$("[offset_page_spliter_id='"+i+"']").offset({top:totalHeight+13});
}