/**************************************************
*  Created:  2011-12-01
*  Info:天坦UI控制器
*  @Tiantanhehe (C)2011-3011 Tiantanhehe
*  @Author DongJie <dongjie@tiantanhehe.com>
*  @Version 3.0
*  @Updated History:
***************************************************/

//一些全局变量：
var server_url = "";

//当前编辑文档是否执行自动保存
var edit_form_type = "auto_save";
var time_of_auto_save = 60000;
var auto_save_timer;
var saved = true;
var cishu=0;
var cishu_saved = 0;
//是否锁定聚焦的表格

var without_event_lock = false;

var current_editor_id = "notinyemc";
var current_patient_id = 0;
var current_zhuyuan_id = 0;
var current_template_category_id = "0";
var current_template_category_name = "请选择模板或者模板类别";

//错误提醒标记
var validate_flag = true;
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

	//表单相关事件的配置参数：
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	var form_options={
			   dataType: 'json',
			   beforeSerialize:function validateForm(){
					$(".textarea_type_auto").each(function(){
						$("."+$(this).attr("submit_name")+"_submit").val($(this).html());
					});
					$(".textarea_type_small").each(function(){
						$("."+$(this).attr("submit_name")+"_submit").val($(this).html());
					});
				},
			   beforeSubmit:function validateForm(){
						validate_flag = true;
						$("[reg]").each(function(){
							if(!validate($(this))){
								return false;
							}
						});
						
						//检测页面上的复选框是否选中，并且对应的下拉菜单是否选择
						var checkmark = false;
						var selectnum = 0;
						var posnum = 0;
						var exist = false;
						$("input.checkbox_type").each(function(){
							expression1 = $(this).is(':checked');
							expression2 = (parseInt($(this).parent().next().find("select.select_type").val())!=0);
							if($(this).parent().next().find("select.select_type").length==0){
								exist = false;
								return false;
							}else{
								exist = true;
								if(expression1 != expression2){
									checkmark = true;
									posnum = $("input.checkbox_type").index(this);
									return false;
								}
								if(expression1 && expression2){
									selectnum++;
								}
							}
						});
						if(exist){
							if((checkmark || selectnum == 0) && ($("select.select_type").length!=0)){
								validate_flag = false;
								$("input.checkbox_type").eq(posnum).ScrollTo("top");
								checkmark = false;
								selectnum = 0;
							}else{
								validate_flag = true;
							}
						}
						return 	validate_flag;
					},
					success:function showResponse(data){
					cishu++;
					if(cishu >= 3){
						cishu=1;
					}
					if(cishu == 1){
						$(".edit_form").submit();
						$(".ajax_form").submit();
						return false;
					}
					window.setTimeout(auto_close,2000); 
					saved = true;
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
	try
	{
		parent.loadingEnd();
	}
	catch(ex)
	{
		
	}

	//添加各种事件
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	//1. 添加一般表格的背景变色效果：
	$('table tr').mouseover(function(){
		$(this).addClass("onShow");
	});
	$("table tr").mouseout(function(){
		$(this).removeClass("onShow");
	});
	$('table tr').click(function() {
			$(".click").removeClass("click");
			$(this).addClass("click");
	});

	//2. 有些表格添加边框变色效果： 
	$(".without_event").unbind();
	
	$(".without_event").mouseover(function(){
		if(!without_event_lock){
			$(this).find(".edit_textarea_menu").css('visibility','visible')
					.end()
					.siblings().find(".edit_textarea_menu").css('visibility','hidden');
			$(this).addClass("without_event_onShow")
					.siblings().removeClass("without_event_onShow");
		}
	});
	
	//3. 有些表格什么变化效果都不需要：
	$(".without_event_total").unbind();

	//表单验证事件
	$("[reg]").click(function(){
		$(this).parent().next().find("[name='tips_message']").removeClass();
		$(this).parent().next().find("[name='tips_message']").addClass("onFocus");	 
	});
	$("[reg]").blur(function(){
		validate($(this));
	});
	
	//4. 输入框输入变色提示事件：
	$(".input_type").focus(function(){
		saved=false;
		$(this).css({"border":"#09F 2px solid"});
		$(this).parent().parent().removeClass();
		$(this).parent().parent().addClass("onShow");	
		return false;
	});
	$(".input_type").blur(function(){
		$(this).css({"border":"#6F88C4 1px solid"});
		$(this).removeClass("onFocusInput");	
		$(this).parent().parent().removeClass();
		$(this).parent().parent().addClass("info_input");
		return false;
	});
	$(".input_type_medium").focus(function(){
		saved=false;
		$(this).css({"border":"#09F 2px solid"});
		$(this).parent().parent().removeClass();
		$(this).parent().parent().addClass("onShow");	
		return false;
	});
	$(".input_type_medium").blur(function(){
		$(this).css({"border":"#6F88C4 1px solid"});
		$(this).removeClass("onFocusInput");	
		$(this).parent().parent().removeClass();
		$(this).parent().parent().addClass("info_input");
		return false;
	});
	$(".input_type_small").focus(function(){
		saved=false;
		$(this).css({"border":"#09F 2px solid"});
		$(this).parent().parent().removeClass();
		$(this).parent().parent().addClass("onShow");	
		return false;
	});
	$(".input_type_small").blur(function(){
		$(this).css({"border":"#6F88C4 1px solid"});
		$(this).removeClass("onFocusInput");	
		$(this).parent().parent().removeClass();
		$(this).parent().parent().addClass("info_input");
		return false;
	});
	
	//设置直接型文本编辑框，未保存事件
	$("form").find(".textarea_type_auto").click(function(e){
		saved=false;
	});
	//radio类型，未保存事件
	$("form").find("[type='radio']").click(function(e){
		saved=false;
	});
	//改变select菜单时的未保存事件
	$("form").find("select").change(function(e){
			saved=false;
	});
	
	//6. 表单中的按钮事件
	$(".submit_button").click(function(){
		saved=true;
	});
	$("[action='save']").live('click',function(){
		saved=true;
	});
	$("[action_type='delete']").live('click',function(){
		if (confirm('是否确认进行删除操作？'))
			return true;
		else
			return false;
	});
	$("[action_type='change']").live('click',function(){
		if (confirm('是否确认进行此操作？'))
			return true;
		else
			return false;
	});
	$("[action_type='others']").live('click',function(){
		if($(this).val()=="其它")
		{
			if($(this).attr("name").indexOf("_changed")!=-1)
			{
				var name = $(this).attr("name").substr(0,$(this).attr("name").indexOf("_changed"));
				$("[name='"+name+"']").remove();
				$(this).attr("name",name);
			}
			
			$(this).parent().remove(".input_type");
			$(this).parent().append('<input type="text" value="请输入其它名称" id="added_select_others" name="'+$(this).attr("name")+'" class="input_type" reg="[^\s]{0,40}" right_message="录入正确" error_message="请至少输入一个字"/>');
			$(this).attr("name",$(this).attr("name")+"_changed");
			$("#added_select_others").focus(function(){
				if($(this).val()=="请输入其它名称")
					$(this).val("");
				});
			$("#added_select_others").blur(function(){
				if($(this).val()=="")
					$(this).val("请输入其它名称");
				});
			return false;
		}
		else
		{
			if($(this).attr("name").indexOf("_changed")!=-1)
			{
				var name = $(this).attr("name").substr(0,$(this).attr("name").indexOf("_changed"));
				$("[name='"+name+"']").remove();
				$(this).attr("name",name);
			}
			return false;
		}
	});
	//重置按钮 
	$(".reset_btn").click(function(){
		location.reload();
		return false;
	});
	
	//7. 痕迹记录有关的操作
	$('.edit_menu_button[value=接受]').click(function(){
		var item = $(this).attr('id').replace("_accept","");
		$('#'+item+'_accept').hide();
		$('#'+item+'_deny').hide();
		$("#"+item+"_zhuangtai").val("接受");
		
		var t;
		function showTimer(){
			window.location.href="http://"+server_url+"/web_emr/ZhuyuanYishi/Bingli/editBingshiJilu/zhuyuan_id/"+current_zhuyuan_id+"";
			window.clearTimeout(t);
		}
		t = window.setTimeout(showTimer,1000);
	});
	
	$('.edit_menu_button[value=不接受]').each(function(){
		var item = $(this).attr('id').replace("_deny","");
		$(this).click(function(){
			$('#'+item+'_accept').hide();
			$('#'+item+'_deny').hide();
			$("#"+item+"_zhuangtai").val("不接受");
			
			var t;
			function showTimer(){
				window.location.href="http://"+server_url+"/web_emr/ZhuyuanYishi/Bingli/editBingshiJilu/zhuyuan_id/"+current_zhuyuan_id+"";
				window.clearTimeout(t);
			}
			t = window.setTimeout(showTimer,1000);
		});
	});
	
	//8. 初始化各种时间控件：
	if($('[action_type="timePicker_data_time"]').length!=0)
	{
		$('[action_type="timePicker_data_time"]').datetimepicker({
			timeFormat: 'hh:mm',
			dateFormat: 'yy-mm-dd'
		});
	}
	//9.复选框变换样式
	var checkbox_number = 0;
	$('input[type="checkbox"]').each(function(){
		var label = '';								  
		var id = $(this).attr("id");
		if(typeof(id) == "undefined")
		{
			var name = $(this).attr("name");
			if($('input[name="'+name+'"]').size() > 1)
			{
				checkbox_number = checkbox_number + 1;
				name = name+"_"+checkbox_number;
				name = name.replace("[]","");
			}
			$(this).attr("id",name);
			id = name;
		}
		else
		{
			id = $(this).attr("id");
		}
		if ($(this).is(":checked"))
		{
			if(typeof($(this).next("label")[0]) == "undefined")
			{
				label = '<label for="'+id+'">√&nbsp;</label>';
				$(this).after(label);
			}
			else
			{
				$(this).next("label").attr("for",id);
				$(this).next("label").prepend("√&nbsp;");
			}
		}
		else
		{
			if(typeof($(this).next("label")[0]) == "undefined")
			{
				label = '<label for="'+id+'">口&nbsp;</label>';
				$(this).after(label);
			}
			else
			{
				$(this).next("label").attr("for",id);
				$(this).next("label").prepend("口&nbsp;");
			}
		}
		
		$("#"+id).button().click(function(){
			if ($(this).is(":checked"))
			{
				var replace = $(this).next("label").html().replace("口&nbsp;","√&nbsp;");
				$(this).next("label").html(replace);
			}
			else
			{
				var replace = $(this).next("label").html().replace("√&nbsp;","口&nbsp;");
				$(this).next("label").html(replace);
			}
		});
	  });
	
	//显示提示框：
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	$('.detail_info').each(function(){
			$(this).qtip({
			   content: {
			   	text:$(this).attr("detail_info"),
			   	title: {
            	 button: "关闭"
					    }
			   	},
			    style: {
					  'font-size' : 12,
					  border: {
						 width: 2,
						 radius: 1
					  },
					  padding: 2, 
					  textAlign: 'left',
					  tip: true,
					  name: 'cream',
					  width: { max: 250 }
               },
				position: {
							  corner: {
								 target: 'bottomLeft',
								 tooltip: 'bottomRight'
							  },
							  adjust: { screen: true }
						   },
				show: {
						  delay:0,
						  solo:true,
						  when: { event: 'click' },
						  ready: false
					   },
               hide: {
				   		  delay:0,
						  when: { event: 'click' }
					   }
			});
	});
	

	//添加联动菜单的数据获取事件：
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	//1.先自动获取所有第一级的内容：
	var pid = $("[dynamic='1']").attr("id");
	if($("[dynamic='1']").val()=="0")
	{
		$.get("http://"+server_url+"/web_emr/Home/Data/getSelectDataJson", {"pid":pid}, function(data){
		  $("[dynamic='1']").html(data);
		});
	}

	//2.再增加二级的动作
	$("[dynamic='1']").change(function(e) {
		pid = $(this).val().split("|")[1];
		$.get("http://"+server_url+"/web_emr/Home/Data/getSelectDataJson", {"pid":pid}, function(data){
			$("[dynamic='2']").html(data);
		});
	});
	
	//表单相关事件：
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	try
	{
		$(".edit_form").ajaxForm(form_options);
		$(".ajax_form").ajaxForm(form_options);
	}
	catch(e)
	{
		
	}

	$(".textarea_type_auto").each(function(){
		$(this).click(function(){
			current_editor_id  = $(this).attr("id");
		});
	});

	if($(".textarea_type_auto:first").attr("id")!="")
		current_editor_id  = $(".textarea_type_auto:first").attr("id");
	
	$(".textarea_type_small").each(function(){
		$(this).click(function(){
			current_editor_id  = $(this).attr("id");
		});
	});
	if($(".textarea_type_small:first").attr("id")!="")
		current_editor_id  = $(".textarea_type_auto:first").attr("id");

	$(".add_form").submit(function(){
		$(".textarea_type_auto").each(function(){
			$("."+$(this).attr("submit_name")+"_submit").val($(this).html());
		});
		$(".textarea_type_small").each(function(){
			$("."+$(this).attr("submit_name")+"_submit").val($(this).html());
		});
	
			$(this).find("[reg]").each(function(){
				if(!validate($(this))){
					return false;
				}
			});
		//检测页面上的复选框是否选中，并且对应的下拉菜单是否选择
						var checkmark = false;
						var selectnum = 0;
						var posnum = 0;
						var exist = false;
						$("input.checkbox_type").each(function(){
							expression1 = $(this).is(':checked');
							expression2 = (parseInt($(this).parent().next().find("select.select_type").val())!=0);
							if($(this).parent().next().find("select.select_type").length==0){
								exist = false;
								return false;
							}else{
								exist = true;
								if(expression1 != expression2){
									checkmark = true;
									posnum = $("input.checkbox_type").index(this);
									return false;
								}
								if(expression1 && expression2){
									selectnum++;
								}
							}
						});
						if(exist){
							if((checkmark || selectnum == 0) && ($("select.select_type").length!=0)){
								validate_flag = false;
								$("input.checkbox_type").eq(posnum).ScrollTo("top");
								checkmark = false;
								selectnum = 0;
							}else{
								validate_flag = true;
							}
						}
		$(this).append('<input type="hidden" name="form_type" value="tiantan_add_form"/>');
		return 	validate_flag;
	});
	
	//为文档本身添加默认动作，主要是去除弹出菜单的动作
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^	
	$(document).click(function(e) {
		if($(e.target).parent("#select_box").length==0&&$(e.target).parent().parent("#select_box").length==0) {	
			$(".info_module_button").removeClass("menu-open");
			$(".info_module_content").hide();
			$('#select_box').remove();
		}
		if($(".detail_info").length > 0)
			$(".detail_info").qtip("hide");
	});	
	
	//启动自动保存
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	if(edit_form_type=="auto_save")
	{
		auto_save_timer = window.setInterval(auto_save,time_of_auto_save); 
	}
});

//自动保存函数
//************************************************************************
function auto_save(){
	$(".edit_form").submit();
}

//自动关闭提示语句
//************************************************************************
function auto_close(){
	$("body").qtip("hide");
		
}

//表格验证函数
//*************************************************************************
function validate(obj){
	var reg = new RegExp(obj.attr("reg"));

	//获取最小可以输入的值
	var zuixiaozhi =  new Array();
	var high_warnging_value = new RegExp(obj.attr("high_warnging_value"));
	zuixiaozhi = high_warnging_value.toString().split('/');
	//获取最大可以输入的值
	var zuidazhi =  new Array();
	var low_warning_value = new RegExp(obj.attr("low_warning_value"));
	zuidazhi = low_warning_value.toString().split('/');
	
	var obj_value = obj.attr("value");
	var right_message = obj.attr("right_message");
	var error_message = obj.attr("error_message");
	var tipObj = obj.parent().next().find("[name='tips_message']");
	var tips_message = tipObj.html();
	tipObj.removeClass();

	//j检测可输入的最小值和可输入的最大值
	if(Number(zuixiaozhi[1]) != 0 && Number(zuidazhi[1]) != 0 )
	{
		if(Number(obj_value) < Number(zuixiaozhi[1]))
		{
			alert('您输入的值小于最低限额,请重新输入！');
		}
		if(Number(obj_value) > Number(zuidazhi[1]))
		{
			alert('您输入的值大于最高限额,请重新输入！');
		}
		validate_flag = false;
	}
	
	// 检测 是否符合正则表达
	if(!reg.test(obj_value)){
		validate_flag = false;
	}
	else
	{
		validate_flag = true;
		
		if(obj.is("[data_type='needed_radio']")){  //判断radio表单元素;
			//alert(obj.attr("value"));
			var inputname=obj.attr("name");
			var radiovalue=obj.parent().find(":radio[name="+inputname+"]:checked").val();
			if(!radiovalue){
				validate_flag = false;
			}
		}
		if(obj.is("[data_type='needed_checkbox']")){  //判断checkbox表单元素;
			//alert(obj.attr("value"));
			var inputname=obj.attr("name");
			var checkboxvalue=obj.parent().find(":checkbox[name="+inputname+"]:checked").val();
			if(!checkboxvalue){
				validate_flag = false;
			}
		}
	}
	
	if(validate_flag == false)
	{
		tipObj.html(error_message);
		tipObj.addClass("error_tips");
		obj.parent().parent().addClass("error_input_state");
		$(".error_tips:first").parent().ScrollTo("top");
	}
	else
	{
		tipObj.html(right_message);
		tipObj.addClass("right_tips");
		obj.parent().parent().removeClass("error_input_state");
	}
	
	return validate_flag;
}

jQuery.extend({
	imgButton:function(name){
		var img_name = name;
		var img_name_hover = name+"_hover";
		var names = $("[name='"+img_name+"']");
		names.html("<img src='"+"http://"+server_url+"/web_emr/Public/css/images/menu_icon/"+img_name+".png"+"' alt='' />");
		names.hover(function(){
			$(this).find('img').attr("src","http://"+server_url+"/web_emr/Public/css/images/menu_icon/"+img_name_hover+".png");
		},function(){
			$(this).find('img').attr("src","http://"+server_url+"/web_emr/Public/css/images/menu_icon/"+img_name+".png");
		});
	}
});

jQuery.extend({
	imgTitleButton:function(id){
		var img_name = id;
		var names = $("[id='"+img_name+"']");
		names.find("img").remove();
		names.prepend("<img src='"+"http://"+server_url+"/web_emr/Public/css/images/menu_icon/"+img_name+".png"+"' alt='' />");
	}
});

function insertContent(module_info)
{
	saved = false;
	var text_area_name = $("#"+current_editor_id).attr("name");
	$("#"+text_area_name).append(module_info).click();
	$("#"+current_editor_id.replace(/edit/,"link")).ScrollTo("top");
}