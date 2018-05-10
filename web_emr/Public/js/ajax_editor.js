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
	
	// 解决Firefox可拖拽、表的行和列可插入（删除）问题
	//document.execCommand('enableInlineTableEditing', false, 'false');
	//document.execCommand('enableObjectResizing', false, 'false');

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
				// 退格
				case 8:
					//只有在段首处才为0
					if(current_anchorOffset==0)break;

					//此处重新获取当前节点及当前位移是怕定时器不够准确
					current_anchorNode = window.getSelection().anchorNode;
					current_anchorOffset = window.getSelection().anchorOffset;
					// 当前节点内容
					var current_content = current_anchorNode.textContent;

					// 开启修订，关闭文本处理引擎
					if(is_revise)
					{
						// 如果在ins标签内按backspace默认事件执行
						if (!$(window.getSelection().anchorNode.parentNode).is("ins")) 
						{
							//取消退格键原有事件
							e.preventDefault();

							var del_content = current_content[current_anchorOffset-1];

							//在<del>标签内，只移动光标
							if ($(window.getSelection().anchorNode.parentNode).is("del")) 
							{
								window.getSelection().modify('move', 'left', 'character');
								break;
							}
							//紧跟<del>标签之前，把新删除的内容合并到后面<del>中
							else if (current_anchorOffset == current_anchorNode.length&&current_anchorNode.nextSibling!=null&&$(current_anchorNode.nextSibling).is("del")&&($(current_anchorNode.nextSibling).attr("user_number")==user_number)) 
							{
								var replace_content = current_content.substring(0,current_anchorOffset-1)+"<del class='modify' id=p"+(++postil_number)+" user_number='"+user_number+"' user_name='"+user_name+"' time='"+new Date().toLocaleString()+"' version=0>"+del_content+window.getSelection().anchorNode.nextSibling.textContent+"</del>"+current_content.substring(current_anchorOffset);
								current_anchorNode.nextSibling.remove();
							}
							//其他节点加del标签
							else
							{
								var replace_content = current_content.substring(0,current_anchorOffset-1)+"<del class='modify' id=p"+(++postil_number)+" user_number='"+user_number+"' user_name='"+user_name+"' time='"+new Date().toLocaleString()+"' version=0>"+del_content+"</del>&nbsp;"+current_content.substring(current_anchorOffset);
								
							}
							//使用加标签的内容替换当前节点
							replaceNode(replace_content);
							//TODO
							if (current_anchorOffset==1) {
								window.getSelection().modify('move', 'right', 'character');
								window.getSelection().modify('move', 'left', 'character');
								// $("[name='"+current_editor_id+"']").focus;
							}

							for(var i=0;i<current_anchorOffset-1;i++)
							{
								window.getSelection().modify('move', 'right', 'character');
							}
						}
					}
					// 关闭修订
					else
					{
					}
					break;

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
		//2秒后若还没有keyup事件认为回车被按下
		chinese_enter_check = setTimeout(isChineseEnter,2000);
		keydown_times++;
		is_keyup = false;

		//
		// if($(".replaceable_tips").is(":hidden")==false)
		// 	$(".replaceable_tips").remove();
	});

	//keyup
	$("[contenteditable='true']").live("keyup",function(e)
	{
		if(is_chinese&&(e.keyCode==32||e.keyCode==27||e.keyCode==16||(e.keyCode>=49&&e.keyCode<=53)||(e.keyCode>=219&&e.keyCode<=222)||(e.keyCode==186||e.keyCode==188||e.keyCode==190||e.keyCode==191)))
		{
			//进入中文输入法之前的offset（通过定时器记录的current_anchorOffset）
			var previous_anchorOffset = current_anchorOffset;
			//输入新内容后的offset
			current_anchorOffset = window.getSelection().anchorOffset;
			var current_content = window.getSelection().anchorNode.textContent;

			if(is_revise)
			{
				var c1 = current_content.substring(0,previous_anchorOffset);
				var c2 = current_content.substring(previous_anchorOffset,current_anchorOffset);
				var c3 = current_content.substr(current_anchorOffset);

				if ($(window.getSelection().anchorNode.parentNode).is("ins")) 
				{
					
				}
				else if ($(window.getSelection().anchorNode.parentNode).is("del")) 
				{
					
				}
				else
				{
					var replace_content = c1+"<ins class='modify' id=p"+(++postil_number)+" user_number='"+user_number+"' user_name='"+user_name+"' time='"+new Date().toLocaleString()+"' version=0>"+c2+"</ins>"+c3;
					
					replaceNode(replace_content);
					
					for (var i = 0; i <(c1+c2).length; i++) {
						window.getSelection().modify('move', 'right', 'character');
					}
				}

			}
			//esc，标点不触发
			if(!(e.keyCode==16||(e.keyCode>=219&&e.keyCode<=222)||(e.keyCode==186||e.keyCode==188||e.keyCode==190||e.keyCode==191)))
			{
				c = current_content.substring(current_anchorOffset-10,current_anchorOffset);
				invokeXiaobianque(c);
			}
			
			is_chinese = false;
		}
		

		window.clearTimeout(chinese_enter_check);
		keydown_times = 0;
		is_keyup = true;
	});

	//keypress,用于处理英文及数字、标点等字符
	$("[contenteditable='true']").live("keypress",function(e)
	{
		current_anchorOffset = window.getSelection().anchorOffset;
		var current_content = window.getSelection().anchorNode.textContent;

		if(is_revise)
		{
			if (e.keyCode==13) 
			{

			}
			else if (e.keyCode==32)
			{

			}
			else
			{
				
				var c1 = current_content.substring(0,current_anchorOffset);
				var c2 = String.fromCharCode(e.keyCode);
				var c3 = current_content.substr(current_anchorOffset);

				if ($(window.getSelection().anchorNode.parentNode).is("ins")) 
				{
					
				}
				else if ($(window.getSelection().anchorNode.parentNode).is("del")) 
				{
					
				}
				else
				{
					e.preventDefault();
					replace_content = c1+"<ins class='modify' id=p"+(++postil_number)+" user_number='"+user_number+"' user_name='"+user_name+"' time='"+new Date().toLocaleString()+"' version=0>"+c2+"</ins>"+c3;
					replaceNode(replace_content);
					
					for (var i = 0; i <c1.length+1; i++) {
						window.getSelection().modify('move', 'right', 'character');
					}
				}
				
			}
		}
		else
		{
		}
	});

	/********************编辑器媒体*********************/
	if(multi_media_engine=="on")
	{
	$("[media_type='all']").live("click",function(){
			var focus_name = $(this).attr("name");
			$("#stop_photo") && $("#stop_photo").remove();
			$("#stop_record") && $("#stop_record").remove();
			$("#take_photo") && $("#take_photo").remove();
			$("#record_audio") && $("#record_audio").remove();
			$("[name='read_pic']") && $("[name='read_pic']").remove();
			$("#read_audio") && $("#read_audio").remove();
			$("#canvas") && $("#canvas").remove();
			$("#video") && $("#video").remove();
			$("#record") && $("#record").remove();
			$("#stop") && $("#stop").remove();
			$("#img") && $("#img").remove();
			$("#audio") && $("#audio").remove();
			$("body").append('<div id="video_background" style="display:none"></div>'+
											'<input type="button" id="stop_photo" style="display:none" class="media_button" status="sleep">'+
											'<input type="button" id="take_photo" style="display:none" class="media_button" status="sleep">'+
											'<input type="button" id="stop_record" style="display:none" class="media_button" status="sleep">'+
											'<input type="button" id="record_audio" style="display:none" class="media_button" status="sleep">'+
											'<canvas id="canvas" style="display:none" width="480" height="320"></canvas>'+
											'<video id="video" style="display:none" width="480" max-width="480" height="320" autoplay=""></video>'+
											'<span id="recorder_state"></span>'+
											'<input id="record" type="button" style="display:none" value="开始"/>'+
											'<input id="stop" type="button" style="display:none" value="停止"/>'+
											'<img id="img" style="display:none"/>'+
											'<audio id="audio" style="display:none" controls="controls"/>');
			//初始化录音和照相功能的样式
			var img_count = 0;
			var audio_count = 0;
			var img_date_value = new Array();
			var audio_date_value = new Array();
			$.ajaxSetup({
				async: false
			});
			$.get("http://"+server_url+"/web_emr/Home/Data/readPic", {zhuyuan_id: zhuyuan_id, focus_name: focus_name, read_type: "count" },function(data){
				img_count = data.split("@")[0];
				img_date_value = data.split("@")[1].split("|");
			});
			$.get("http://"+server_url+"/web_emr/Home/Data/readAudio", {zhuyuan_id: zhuyuan_id, focus_name: focus_name, read_type: "count" },function(data){
				audio_count = data.split("@")[0];
				audio_date_value = data.split("@")[1].split("|");
			});
			$.ajaxSetup({
				async: true
			});
			
			if(img_count==0&&audio_count==0)
			{
				$("[name='read_pic']").remove();
			}
			else
			{
				var read_pic_html = "";
				var total_number = 0;
				if(img_count>0)
				{
					for(var i=0;i<img_count;i++)
					{
						var div_date_value = img_date_value[i].split(" ");
						var div_date_value_temp = div_date_value[0].split("-");
						var div_date = div_date_value_temp[1]+"月"+div_date_value_temp[2]+"日";
						var count_i = i+1;
						read_pic_html += '<div class="leftd read_pic" name="read_pic" total_number="'+total_number+'" number="'+i+'" status="sleep"><div class="speech left">图片'+count_i+'<br/>'+div_date+'<br/>'+div_date_value[1]+'</div></div>';
						total_number++;
					}
				}
				if(audio_count>0)
				{
					for(var i=0;i<audio_count;i++)
					{
						var div_date_value = audio_date_value[i].split(" ");
						var div_date_value_temp = div_date_value[0].split("-");
						var div_date = div_date_value_temp[1]+"月"+div_date_value_temp[2]+"日";
						var count_i = i+1;
						read_pic_html += '<div class="leftd read_audio" name="read_pic" total_number="'+total_number+'" number="'+i+'" status="sleep"><div class="speech left">音频'+count_i+'<br/>'+div_date+'<br/>'+div_date_value[1]+'</div></div>';
						total_number++;
					}
				}
				
				$("body").append(read_pic_html);
			}
			var iframe_width = $("#video_background").width();
			var iframe_height = $("#video_background").height();
			if(iframe_height>1000)
			{
				$("#video").attr("max-width","900");
				$("#video").attr("width","900");
				$("#video").attr("height","500");
				$("#canvas").attr("width","670");
				$("#canvas").attr("height","500");
			}
			$("#take_photo").css("display","inline");
			$("#record_audio").css("display","inline");
			var left_side_x = $(this).offset().left - 50;
			$("#stop_photo").css("left",left_side_x);
			$("#take_photo").css("left",left_side_x);
			$("#record_audio").css("left",left_side_x);
			$("#recorder_state").css("left",left_side_x+60);
			$("#record").css("left",left_side_x+60);
			$("#stop").css("left",left_side_x+130);
			$("#video").css("left",(iframe_width - $("#video").width())/2);
			$("#video").css("bottom",(iframe_height - $("#video").height())/2);
			$("#canvas").css("left",(iframe_width - $("#canvas").width())/2);
			$("#canvas").css("bottom",(iframe_height - $("#canvas").height())/2);
			if(iframe_height>1000)
			{
				$("#img").css("left",$(this).offset().left + $(this).width() - $("#img").offset().left - 670);
				$("#audio").css("left",$(this).offset().left + $(this).width() - $("#audio").offset().left - 670);
			}
			else
			{
				$("#img").css("left",$(this).offset().left + $(this).width() - $("#img").offset().left - 425);
				$("#audio").css("left",$(this).offset().left + $(this).width() - $("#audio").offset().left - 425);
			}
			$("#img").css("top",$(this).offset().top - $("#img").offset().top + 190);
			$("#audio").css("top",$(this).offset().top - $("#audio").offset().top + 190);
			var ob_text_this = $(this);
			$("[name='read_pic']").each(function(){
				$(this).css("left",ob_text_this.offset().left + ob_text_this.width() + 3 - $(this).offset().left);
				$(this).css("top",ob_text_this.offset().top - $(this).offset().top - 10 + $(this).attr("total_number")*25);
			});
			//$("#read_audio").css("left",$(this).offset().left + $(this).width() + 3 - $("#read_audio").offset().left);
			//$("#read_audio").css("top",$(this).offset().top - $("#read_audio").offset().top +60);
			$("[name='read_pic']").hover(function(){
					$(this).css("z-index","2000");
				},function(){
					$(this).css("z-index","1000");
			});
			$("#take_photo").hover(function(){
					$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/take_photo_hover.png) no-repeat center center");
				},function(){
					if($(this).attr("status")=="sleep")
					{
						$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/take_photo.png) no-repeat center center");
					}
					else
					{
						$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/taking_photo.png) no-repeat center center");
					}
			});
			$("#record_audio").hover(function(){
					$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/record_audio_hover.png) no-repeat center center");
				},function(){
					if($(this).attr("status")=="sleep")
					{
						$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/record_audio.png) no-repeat center center");
					}
					else
					{
						$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/recording_audio.png) no-repeat center center");
					}
			});
			//录音和照相功能
			var canvas = document.getElementById("canvas"),
					context = canvas.getContext("2d"),
					img = document.getElementById("img"),
					video = document.getElementById("video");
			var audio_context,recorder,volume,volumeLevel = 0;
			var audioElement = document.getElementById("audio");
			$("#take_photo").click(function(){
				if($(this).attr("status")=="sleep")
				{
					$("#video_background").css("display","inline");
					$("#stop_photo").css("display","inline");
					$(this).attr("status","work");
					$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/taking_photo.png) no-repeat center center");
					$("#canvas").css("display","none");
					$("#video").css("display","inline");
					var errBack = function(error) {

					};
					navigator.webkitGetUserMedia({ video: true,audio: false }, function(stream){
						video.src = window.webkitURL.createObjectURL(stream);
						video.play();
					}, errBack);
				}
				else
				{
					$("#video").css("display","none");
					$("#canvas").css("display","inline");
					if(iframe_height>1000)
					{
						context.drawImage(video, 0, 0, 670, 500);
					}
					else
					{
						context.drawImage(video, 0, 0, 427, 320);
					}
					$("#canvas").fadeOut(500,function(){
						$("#video").css("display","inline");
					});
					var imgData = canvas.toDataURL("image/png");
					var post_param = {};
					post_param['data'] = imgData;
					post_param['label'] = focus_name;
					post_param['zhuyuan_id'] = zhuyuan_id;
					var img_count_temp = 0;
					$.ajaxSetup({
						async: false
					});
					$.post("http://"+server_url+"/web_emr/Home/Data/updatePic", post_param,function(data){
						if(data=="true")
						{
							$.get("http://"+server_url+"/web_emr/Home/Data/readPic", {zhuyuan_id: zhuyuan_id, focus_name: focus_name, read_type: "count" },function(data){
								img_count_temp = data.split("@")[0];
								img_date_value = data.split("@")[1].split("|");
							});
							if(img_count_temp==0)
							{
								$("[name='read_pic']").remove();
							}
							else
							{
								var read_pic_html = "";
								for(var i=0;i<img_count_temp;i++)
								{
									var div_date_value = img_date_value[i].split(" ");
									var div_date_value_temp = div_date_value[0].split("-");
									var div_date = div_date_value_temp[1]+"月"+div_date_value_temp[2]+"日";
									var count_i = i+1;
									read_pic_html += '<div class="leftd" name="read_pic" number="'+i+'" status="sleep"><div class="speech left">图片'+count_i+'<br/>'+div_date+'<br/>'+div_date_value[1]+'</div></div>';
								}
								$("body").append(read_pic_html);
							}
							$("[name='read_pic']").each(function(){
								$(this).css("left",ob_text_this.offset().left + ob_text_this.width() + 3 - $(this).offset().left);
								$(this).css("top",ob_text_this.offset().top - $(this).offset().top - 10 + $(this).attr("number")*25);
							});
							$("[name='read_pic']").hover(function(){
									$(this).css("z-index","2000");
								},function(){
									$(this).css("z-index","1000");
							});
							$("[name='read_pic']").click(function(){
								$("#video_background").css("display","inline");
								$("#stop_photo").css("display","inline");
								var img_url = "";
								var read_number = $(this).attr("number");
								$.ajaxSetup({
									async: false
								});
								$.get("http://"+server_url+"/web_emr/Home/Data/readPic", {zhuyuan_id: zhuyuan_id, focus_name: focus_name, read_type: "read", read_number: read_number },function(data){
									img_url = data;
								});
								$.ajaxSetup({
									async: true
								});
								img.src = "data:image/png;base64,"+img_url;
								if($("#img").css("display")=="none")
								{
									$("#img").css("display","inline");
								}
							});
						}
					});
					$.ajaxSetup({
						async: true
					});
				}
			});
			$("#stop_photo").click(function(){
				$("#video_background").css("display","none");
				$(this).css("display","none");
				$("#img").css("display","none");
				$("#audio").css("display","none");
				$("#take_photo").attr("status","sleep");
				$("#take_photo").css("background","url(http://"+server_url+"/web_emr/Public/css/images/take_photo.png) no-repeat center center");
				$("#video").css("display","none");
				// $("#canvas").css("display","inline");
				// $("#canvas").fadeOut(3000);
				$("#canvas").css("display","none");
			});
			$(".read_pic").click(function(){
				$("#audio").css("display","none");

				var offset = $('.speech').offset(); 
				$("#img").css("top",offset.top);
	
				$("#video_background").css("display","inline");
				$("#stop_photo").css("display","inline");
				var img_url = "";
				var read_number = $(this).attr("number");
				$.ajaxSetup({
					async: false
				});
				$.get("http://"+server_url+"/web_emr/Home/Data/readPic", {zhuyuan_id: zhuyuan_id, focus_name: focus_name, read_type: "read", read_number: read_number },function(data){
					img_url = data;
				});
				$.ajaxSetup({
					async: true
				});
				img.src = "data:image/png;base64,"+img_url;
				if($("#img").css("display")=="none")
				{
					$("#img").css("display","inline");
				}
			});
			$(".read_audio").click(function(){
				$("#img").css("display","none");

				var offset = $('.speech').offset(); 
				$("#audio").css("top",offset.top);
	
				$("#video_background").css("display","inline");
				$("#stop_photo").css("display","inline");
				var audio_url = "";
				var read_number = $(this).attr("number");
				$.ajaxSetup({
					async: false
				});
				$.get("http://"+server_url+"/web_emr/Home/Data/readAudio", {zhuyuan_id: zhuyuan_id, focus_name: focus_name, read_type: "read", read_number: read_number },function(data){
					audio_url = data;
				});
				$.ajaxSetup({
					async: true
				});
				$("#audio").attr("src",audio_url);
				if($("#audio").css("display")=="none")
				{
					$("#audio").css("display","inline");
				}
			});
			$("#record_audio").click(function(){
				if($(this).attr("status")=="sleep")
				{
					$(this).attr("status","work");
					$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/recording_audio.png) no-repeat center center");
					$("#video_background").css("display","inline");
					$("#recorder_state").css("display","inline");
					$("#record").css("display","inline");
					$("#stop").css("display","inline");
					$("#recorder_state").css("display","inline");

					if (!navigator.getUserMedia)
					navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia ||
					navigator.mozGetUserMedia || navigator.msGetUserMedia;
					
					if (navigator.getUserMedia){
						navigator.getUserMedia({audio:true}, success, function(e) {
							alert("打开麦克风时出现错误。");
						});
					} else alert('2当前的浏览器不支持调用麦克风，换用Chrome试试吧。');
					$("#recorder_state").html("请点击开始按钮以开始录音。。。");
				}
				else
				{
					$(this).attr("status","sleep");
					$(this).css("background","url(http://"+server_url+"/web_emr/Public/css/images/record_audio.png) no-repeat center center");
					$("#video_background").css("display","none");
					$("#recorder_state").css("display","none");
					$("#record").css("display","none");
					$("#stop").css("display","none");
				}
			});
			$("#record").click(function(){
				recording = true;
				leftChannel.length = 0;
				recordingLength = 0;
				$("#recorder_state").html("开始录音。。。");
			});
			$("#stop").click(function(){
				recording = false;
				$("#recorder_state").html("录音上传中。。。");
				// we flat the left and right channels down
				// we interleave both channels together
				var blob = encodeWAV_8k16bit(leftChannel, recordingLength,inputSampleRate);
 				var url = (window.URL || window.webkitURL).createObjectURL(blob)
				var img_count_temp = 0;
				var reader = new FileReader();
				reader.onload = function (rResult) {
					var options = {
						type: 'POST',
						url: 'http://'+server_url+'/web_emr/Home/Data/getAudioFile/zhuyuan_id/'+zhuyuan_id+'/label/'+focus_name,
						data: reader.result,
						success:function(result){
							alert(result.msg);
							$(this).attr("status","sleep");
							$("#record_audio").css("background","url(http://"+server_url+"/web_emr/Public/css/images/record_audio.png) no-repeat center center");
							$("#video_background").css("display","none");
							$("#recorder_state").css("display","none");
							$("#record").css("display","none");
							$("#stop").css("display","none");
						},
						processData: false,// 告诉jQuery不要去处理发送的数据
						contentType: false,// 告诉jQuery不要去设置Content-Type请求头
						dataType:"json"
					};
					$.ajaxSetup({
						async: false
					});
					$.ajax(options);
				};
				reader.readAsArrayBuffer(blob);
			});
			
		});
	}
	/********************编辑器动态控制*********************/
	$('.modify').live("click",revise_confirm);
	
	$("#accept_all").live("click",function()
	{
		if($(".modify").length!=0)
		{
			$("ins").contents().unwrap();
			$("del").remove();
		}
		else
		{
			alert("没有新的修改！");
		}
	});

	$("#refuse_all").live("click",function()
	{
		if($(".modify").length!=0)
		{
			$("del").contents().unwrap();
			$("ins").remove();
		}
		else
		{
			alert("没有新的修改！");
		}
	});

	//文字格式控制
	$(".top_piaofu_b").mousedown(function(){
		if(window.getSelection())
		{
			var xuanzhong = window.getSelection();
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
					if ($(window.getSelection().anchorNode.parentNode).is("sup")) 
					{
						$(window.getSelection().anchorNode.parentNode).contents().unwrap();
					}
					else
					{
						document.execCommand('SuperScript');
					}
				}
				else if(title == '下标')
				{
					if ($(window.getSelection().anchorNode.parentNode).is("sub")) 
					{
						$(window.getSelection().anchorNode.parentNode).contents().unwrap();
					}
					else
					{
						document.execCommand('SubScript');
					}
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
					// var str_html = '<table id="symbol_table" class="symbol_table" cellspacing="0">'+
					// 					'<tr>'+
					// 						'<td>1</td><td>2</td><td>3</td><td>4</td><td>5</td>'+
					// 						'<td>6</td><td>7</td><td>8</td><td>9</td><td>10</td>'+
					// 					'</tr>'+
					// 					'<tr>'+
					// 						'<td>I</td><td>II</td><td>III</td><td>IV</td><td>V</td>'+
					// 						'<td>VI</td><td>VII</td><td>X</td><td>XI</td><td>XII</td>'+
					// 					'</tr>'+
					// 					'<tr>'+
					// 						'<td>i</td><td>ii</td><td>iii</td><td>iv</td><td>v</td>'+
					// 						'<td>vi</td><td>vii</td><td>x</td><td>xi</td><td>xii</td>'+
					// 					'</tr>'+
					// 					'<tr><td colspan="10" class="other_symbol">其他符号</td></tr>'+
					// 				'</table>';

					// var offset = $(this).offset();
					// var x_position = offset.left;
					// var y_position = offset.top;

					// $("body").append(str_html);
					// $("#symbol_table").offset({top:y_position+35,left:x_position});

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


					// artDialog.alert = function (content) {
					//     return artDialog({
					//     	id:"abc",
					// 		width: '30%',
					// 	    height: '50%',
					// 	    left: '50%',
					// 	    top: '50%',
					// 	    fixed: true,
					// 	    resize: false,
					// 	    drag: false,
					// 	    // lock: true,
					// 	    content:content,
					// 	    close:function(){
					// 			insertContent("dkdk");
					// 	    }
					//     });
					// };
					// art.dialog.alert(str_html);
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
		if(window.getSelection&&window.getSelection!="")
		{
			var rgb = $("#color_board_fore").val();
			document.execCommand('ForeColor',true,rgb);
		}
	});

	$("#color_board_back").live("change",function(){
		if(window.getSelection&&window.getSelection!="")
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
	
	//增加自动联想控制器：
	$(".auto_slelect_input").click(function(){
		//显示自动联想区域
		keyword = $(this).attr("name");
		current_auto_slelect_input_div = $(this);
		$.get("http://"+server_url+"/web_emr/Home/BingliEditor/getAutoSelectOptions", {keyword:keyword}, function(data){
			if(data!='none')
			{
				$("#auto_slelect_options_area").html(data);
				$("#auto_slelect_options_area").show();
				//定位标签
				$("#auto_slelect_options_area").css("top",current_auto_slelect_input_div.offset().top+20);
				$("#auto_slelect_options_area").css("left",current_auto_slelect_input_div.offset().left);
			}
		});
	});
	
	$(".one_option").live("click",function(){
		$("[name='"+$(this).attr("options_index")+"']").html($(this).attr("options_content"));
		$("[name='"+$(this).attr("options_second_index")+"']").html($(this).attr("options_second_content"));
	});
	
	$(document).click(function(e) {
		$("#auto_slelect_options_area").hide();
	});	
	
});


/********************编辑器定时器*********************/
//定时器
function editorTimer()
{
	//非中文输入状态下才记录
	if (!is_chinese)
	{
		current_selection = window.getSelection();
		current_anchorNode = window.getSelection().anchorNode;
		current_anchorOffset = current_selection.anchorOffset;
		current_cursor_position = getCurrentCursorPosition(current_anchorNode,current_anchorOffset);
	}
}

//是否在中文输入状态按下回车
function isChineseEnter()
{
	if (is_chinese&&!is_keyup&&keydown_times==1) 
	{
		is_chinese = false;
		keydown_times=0;
		is_keyup = true;
	}
}

//获取当前光标相对div的位置
function getCurrentCursorPosition(node,offset)
{
	if (node!=null)
	{
		var text = "";
		var p_count = getPCount(node);
		while(node.previousSibling!=null||(!$(node.parentNode).is("div")&&node.parentNode.previousSibling!=null))
		{
			if (node.previousSibling!=null)
			{
				node = node.previousSibling;
				text += node.textContent;
			}
			else if(!$(node.parentNode).is("div")&&node.parentNode.previousSibling!=null)
			{
				node = node.parentNode.previousSibling
				text += node.textContent;
			}
		}
		return (text.length + offset + p_count);
	}
}

//当前选中节点在第几段（一段结尾与下一段开始没有新内容但位置+1了）
function getPCount(node)
{
	var p_count = 0;
	//找出当前所在段落
	while(node!=null&&!$(node).is("p"))
	{
		node = node.parentNode;
	}

	//该段落是div的第几段
	while(node!=null&&node.previousSibling!=null)
	{
		node = node.previousSibling;
		p_count++;
	}
	return p_count;
}

//替换当前节点
function replaceNode(data)
{
	var range = window.getSelection().getRangeAt(0);
	var content_range = document.createRange();
	content_range.setStart(range.startContainer, range.startOffset);
	content_range.setEnd(range.endContainer, range.endOffset);
	var fragment = content_range.createContextualFragment(data);
	// content_range.startContainer.data = "";
	window.getSelection().anchorNode.remove();
	content_range.insertNode(fragment);
}

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
	var selection= window.getSelection ? window.getSelection() : document.selection;
	var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
	if (!window.getSelection)
	{
		$('[name="'+current_editor_id+'"]').focus();
		var selection= window.getSelection ? window.getSelection() : document.selection;
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
		//div_content = div_content.replace(new RegExp("<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\>(((?!<span).)*)</span>","gmi"),"$1");
		div_content = div_content.replace(new RegExp("<span (?!id=\"before\")(?!id=\"up\")(?!id=\"spliter\")(?!id=\"down\")(?!id=\"after\")(?:[^<])*?\></span>","gmi"),"");
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
	div_content = div_content.replace(new RegExp("<b></b>","gmi"),"");
	div_content = div_content.replace(new RegExp("<p><u></u></p>","gmi"),"");
	//div_content = div_content.replace(new RegExp("<span(?:.|[\r\n])*?\>","gmi"),"");
	//div_content = div_content.replace(new RegExp("</span>","gmi"),"");
	div_content = div_content.replace(new RegExp("<div><br></div>","gmi"),"");
	div_content = div_content.replace(new RegExp("<br></div>","gmi"),"</div>");
	div_content = div_content.replace(new RegExp("<div></div>","gmi"),"");
	
	//清除强制空格span:
	div_content = div_content.replace(new RegExp("<span style='min-width:10px;display:inline-block;height:20px'></span>","gmi"),"");
	
	//优化标题:
	div_content = div_content.replace(new RegExp("<p>(<b>)?入院记录(</b>)?</p>","gmi"),"<center><h2>住院病历</h3></center>");
	div_content = div_content.replace(new RegExp("<p>(<b>)?住院病历(</b>)?</p>","gmi"),"<center><h2>住院病历</h3></center>");
	div_content = div_content.replace(new RegExp("<div>(<b>)?入院记录(</b>)?</div>","gmi"),"<center><h2>住院病历</h3></center>");
	div_content = div_content.replace(new RegExp("<div>(<b>)?住院病历(</b>)?</div>","gmi"),"<center><h2>住院病历</h3></center>");	div_content = div_content.replace(new RegExp("<p>(<b>)?首次入院记录(</b>)?</p>","gmi"),"<center><h3>首次病程记录</h3></center>");
	div_content = div_content.replace(new RegExp("<div>(<b>)?首次入院记录(</b>)?</div>","gmi"),"<center><h3>首次病程记录</h3></center>");
	div_content = div_content.replace(new RegExp("<p>(<b>)?首次病程记录(</b>)?</p>","gmi"),"<center><h3>首次病程记录</h3></center>");
	div_content = div_content.replace(new RegExp("<div>(<b>)?首次病程记录(</b>)?</div>","gmi"),"<center><h3>首次病程记录</h3></center>");
	div_content = div_content.replace(new RegExp("<p>(<b>)?病程记录(</b>)?</p>","gmi"),"<center><h3>病程记录</h3></center>");
	div_content = div_content.replace(new RegExp("<p>(<b>)?体格检查(</b>)?</p>","gmi"),"<center><h4>体格检查</h4></center>");
	div_content = div_content.replace(new RegExp("<p>(<b>)?病历摘要(</b>)?</p>","gmi"),"<center><h4>病历摘要</h4></center>");
	div_content = div_content.replace(new RegExp("<p>(<b>)?辅助检查结果(</b>)?</p>","gmi"),"<center><h4>辅助检查结果</h4></center>");

	//优化常见错别字：
	div_content = div_content.replace(new RegExp("医生鉴名","gmi"),"医生签名");
	div_content = div_content.replace(new RegExp("医生签字","gmi"),"医生签名");

	//优化缩进格式：
	div_content = div_content.replace(new RegExp("&nbsp; ","gmi"),"&nbsp;");
	div_content = div_content.replace(new RegExp("<p>(&nbsp;){0,}","gmi"),"<p>&nbsp;&nbsp;&nbsp;&nbsp;");
	div_content = div_content.replace(new RegExp("<p>(&nbsp;){0,}<b","gmi"),"<p><b");
	div_content = div_content.replace(new RegExp("<p><b>(&nbsp;){0,}","gmi"),"<p><b>");
	div_content = div_content.replace(new RegExp("(&nbsp;)+</p>","gmi"),"</p>");
	div_content = div_content.replace(new RegExp("<div>(&nbsp;){0,}","gmi"),"<div>&nbsp;&nbsp;&nbsp;&nbsp;");
	div_content = div_content.replace(new RegExp("<div>(&nbsp;){0,}<b","gmi"),"<div><b");
	div_content = div_content.replace(new RegExp("<div><b>(&nbsp;){0,}","gmi"),"<div><b>");
	div_content = div_content.replace(new RegExp("(&nbsp;)+</div>","gmi"),"</div>");
	
	//标点优化
	//div_content = div_content.replace(new RegExp(":","gmi"),"：");
	div_content = div_content.replace(new RegExp("；","gmi"),"：");
	//优化签名
	div_content = div_content.replace(new RegExp("<p>(&nbsp;){0,}医生签名：</p>","gmi"),"<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;医生签名：</p>");


	if(div_content_filter[1]!=null)
		div_content = div_content_filter[0]+"</b>:"+div_content;
	
	if(bingli_type != "住院病案首页")
	{
		div_content = div_content.replace(new RegExp("&nbsp;","gmi"),"<span style='min-width:10px;display:inline-block;height:20px'></span>");
		div_content = div_content.replace(new RegExp("&nbsp; ","gmi"),"<span style='min-width:10px;display:inline-block;height:20px'></span>");
	}
	
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
		window.getSelection().modify("move","right","character");
	}
}

// 显示批注信息
var revise_confirm = function showReviseInfoConfirm()
{
	var user_name = $(this).attr("user_name");
	var time = $(this).attr("time");
	var operation = $(this).is("ins")?"新增":"删除";
	var content = $(this).text();
	var strhtml = user_name+"于"+time+operation+"了<span style='color:red;'>"+content+"</span>";
	var modify_node = $(this);

	artDialog.alert = function (content) {
	    return artDialog({
	        id: 'Alert',
	        icon: 'warning',
	        fixed: true,
	        lock: true,
	        content: content,
	        ok: true
	    });
	};

	artDialog.confirm = function (content, yes, no) {
	    return artDialog({
	        id: 'Confirm',
	        icon: 'question',
	        fixed: true,
	        lock: true,
	        opacity: .1,
	        content: content,
	        okVal: '接受',
	        cancelVal: '拒绝',
	        ok: function (here) {
	            return yes.call(this, here);
	        },
	        cancel: function (here) {
	            return no && no.call(this, here);
	        }
	    });
	};

	artDialog.tips = function (content, time) {
	    return artDialog({
	        id: 'Tips',
	        title: false,
	        cancel: false,
	        fixed: true,
	        lock: true
	    })
	    .content('<div style="padding: 0 1em;">' + content + '</div>')
	    .time(time || 1);
	};

	if(user_department_position=="住院医师")
	{
		//显示接受/拒绝对话框
		art.dialog.confirm(strhtml, function () {
			if (modify_node.is("ins")) 
			{
				modify_node.contents().unwrap();
			}
			else
			{
				modify_node.remove();
			}
		    art.dialog.tips('已接受');
		}, function () {
		    if (modify_node.is("ins")) 
			{
				modify_node.remove();
			}
			else
			{
				modify_node.contents().unwrap();
			}
		    art.dialog.tips('已拒绝');
		});

	}
	else if(user_number==$(this).attr("user_number"))
	{
		//显示删除/取消对话框
		artDialog.confirm = function (content, yes, no) {
		    return artDialog({
		        id: 'Confirm',
		        icon: 'question',
		        fixed: true,
		        lock: true,
		        opacity: .1,
		        content: content,
		        okVal: '确定',
		        cancelVal: '取消',
		        ok: function (here) {
		            return yes.call(this, here);
		        },
		        cancel: true
		    });
		};

		art.dialog.confirm(strhtml+"，是否取消本次修改？", function () {
			    if (modify_node.is("ins")) 
				{
					modify_node.remove();
				}
				else
				{
					modify_node.contents().unwrap();
				}
			
		    art.dialog.tips('已恢复');
		});
	}
	else
	{
		//只显示带有确定按钮的alert对话框
		art.dialog.alert(strhtml);
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

function invokeXiaobianque(content)
{
	$.post("/web_emr/Home/Data/matchKeyword", {"content": content},function(data)
	{
		if(keyword!=data&&data!="")
		{
			keyword = data;
			window.parent.wenYiWen(keyword);
		}
	});
}

function shuaxinSession() {
    $.get("/web_emr/Home/Data/shuaxinSession", function(data) {
        if (data == "no") {
            art.dialog({
                id: "checklogin",
                title: "您已经超过2个小时未与系统保持认证，请输入您的用户名和密码获取最新的安全认证:)",
                padding: 10,
                content: '您已经超过2个小时未与系统保持认证，<br />请输入您的用户名和密码获取最新的安全认证:)<table width="260" border="0" style="margin:0px; padding:0px; font-size:14px;"><tr><td width="50" height="30" align="center" valign="middle">帐号：</td><td width="210" height="30" align="left" valign="middle">' + user_number + '<input name="user_number" type="hidden" value="' + user_number + '" /></td></tr><tr><td height="30" align="center" valign="middle">密码：</td><td height="30" align="left" valign="middle"><input type="password" name="login_password" id="login_password" style="width:200px; height:22px;" /></td></tr><tr><td></td><td height="20" id="checkdenglu" valign="middle" style="color:#ff0000;"></td></tr></table>',
                lock: true,
                ok: function() {
                    var login_password = $("#login_password").val();
                    $.post("/web_emr/Home/Data/updateLogin", {user_number: user_number,login_password: login_password
                    }, function(data) {
                        if (data == "ok") {
                            art.dialog.list.checklogin.close();
                        } else {
                            $("#checkdenglu").html("用户名或者密码不正确");
                        }
                    });
                    return false;
                },
                cancelVal: "退出",
                cancel: function() {
                    art.dialog.list.checklogin.close();
                    window.open("/web_emr/Home/System/logout", "_parent");
                    return false;
                }
            });
            if (user_number == "") {
                $("#login_password").attr("disabled", "disabled");
                $(".aui_state_highlight").css("display", "none");
            }
        }
    });
}

function encodeWAV_8k16bit(leftChannel,recordingLength,inputSampleRate)
{
		
	var compress = function(leftChannel,recordingLength,inputSampleRate) {
	//合并
		var data = new Float32Array(recordingLength);
		var offset = 0;

		for (var i = 0; i < leftChannel.length; i++) {
			data.set(leftChannel[i], offset);
			offset += leftChannel[i].length;
		}
		//压缩
		var compression = parseInt(inputSampleRate / (44100/6));
		var length = data.length / compression;
		var bytes = new Float32Array(length);
		var index = 0, j = 0;
		while (index < length) {
			bytes[index] = data[j];
			j += compression;
			index++;
		}
		return bytes;
	}
	var sampleRate = (44100/6);
	var sampleBits = 8;      			//16
	var bytes = compress(leftChannel,recordingLength,inputSampleRate);
	var dataLength = bytes.length * (sampleBits / 8);
	var buffer = new ArrayBuffer(44 + dataLength);
	var data = new DataView(buffer);

	var channelCount = 1;//单声道
	var offset = 0;

	var writeString = function (str) {
		for (var i = 0; i < str.length; i++) {
			data.setUint8(offset + i, str.charCodeAt(i));
		}
	}
	
	// 资源交换文件标识符 
	writeString('RIFF'); offset += 4;
	// 下个地址开始到文件尾总字节数,即文件大小-8 
	data.setUint32(offset, 36 + dataLength, true); offset += 4;
	// WAV文件标志
	writeString('WAVE'); offset += 4;
	// 波形格式标志 
	writeString('fmt '); offset += 4;
	// 过滤字节,一般为 0x10 = 16 
	data.setUint32(offset, 16, true); offset += 4;
	// 格式类别 (PCM形式采样数据) 
	data.setUint16(offset, 1, true); offset += 2;
	// 通道数 
	data.setUint16(offset, channelCount, true); offset += 2;
	// 采样率,每秒样本数,表示每个通道的播放速度 
	data.setUint32(offset, sampleRate, true); offset += 4;
	// 波形数据传输率 (每秒平均字节数) 单声道×每秒数据位数×每样本数据位/8 
	data.setUint32(offset, channelCount * sampleRate * (sampleBits / 8), true); offset += 4;
	// 快数据调整数 采样一次占用字节数 单声道×每样本的数据位数/8 
	data.setUint16(offset, channelCount * (sampleBits / 8), true); offset += 2;
	// 每样本数据位数 
	data.setUint16(offset, sampleBits, true); offset += 2;
	// 数据标识符 
	writeString('data'); offset += 4;
	// 采样数据总数,即数据总大小-44 
	data.setUint32(offset, dataLength, true); offset += 4;
	// 写入采样数据 
	if (sampleBits === 8) {
		for (var i = 0; i < bytes.length; i++, offset++) {
			var s = Math.max(-1, Math.min(1, bytes[i]));
			var val = s < 0 ? s * 0x8000 : s * 0x7FFF;
			val = parseInt(255 / (65535 / (val + 32768)));
			data.setInt8(offset, val, true);
		}
	} else {
		for (var i = 0; i < bytes.length; i++, offset += 2) {
			var s = Math.max(-1, Math.min(1, bytes[i]));
			data.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
		}
	}

	return new Blob([data], { type: 'audio/wav' });
}

function success(e){
    // creates the audio context
    audioContext = window.AudioContext || window.webkitAudioContext;
    context = new audioContext();
    audioInput = context.createMediaStreamSource(e);
	inputSampleRate = context.sampleRate;
    

    var bufferSize = 4096;
    recorder = context.createJavaScriptNode(bufferSize, 1, 1);
	inputSampleRate = context.sampleRate;
    recorder.onaudioprocess = function(e){
        if (!recording) return;
        
		var left = e.inputBuffer.getChannelData (0);
		
        leftChannel.push (new Float32Array (left));
        recordingLength += left.length;
    }
	/*
	volume = context.createGain();
    audioInput.connect(volume);
    volume.connect (recorder);
	*/
	audioInput.connect (recorder);
    recorder.connect (context.destination); 
}
 
