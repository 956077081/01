/**
 * @原创作者 郭瑾
 * @改进作者 tiantanhehe
 * @版本 jquery.citypicker.js(version="1.1.0")
 */

$(function(){
	$("[action_type='location_selector']").focus(function(){
		var action_target= $(this);
		var input_address=action_target;
		var input_bianma=action_target.next();
		var city_address_temp = input_address.val();
		var area_address_temp = input_address.val();
		if(action_target[0].tagName=="TD")
		{
			city_address_temp = input_address.html();
			area_address_temp = input_address.html();
		}
		var temp_bianma=null;
		var pro_id=null;
		var city_id=null;
		var area_id=null;
		art.dialog({
			id:"city_dialog",
			title:input_address.parent().prev().html(),
			content:'<div class="address">'+
								'<div class="shuoming">'+
									'<input type="text" class="address_input_type" disabled="disabled" value="省（直辖市）"/>'+
									'<input type="text" class="address_input_type" disabled="disabled" value="市（直辖市区）"/>'+
									'<input type="text" class="address_input_type" disabled="disabled" value="详细地址（区、县街道）"/>'+
								'</div>'+
								'<div class="wrap_head">'+
									'<input type="text" name="address_left" class="address_input_type"/>'+
									'<input type="text" name="address_middle" class="address_input_type"/>'+
									'<input type="text" name="address_right" class="address_input_type"/>'+
								'</div>'+
								'<div class="wrap_body">'+
									'<div class="address_left">'+
										'<div class="pro_list"></div>'+
									'</div>'+
									'<div class="address_middle">'+
										'<div class="city_list"></div>'+
									'</div>'+
									'<div class="address_right">'+
										'<div class="area_list"></div>'+
									'</div>'+
								'</div>'+
								'<div class="wrap_foot">'+
									'<input type="button" class="address_button" name="address_cancel" value="取消"/>'+
									'<input type="button" class="address_button" name="address_confirm" value="确定"/>'+
								'</div>'+
							'</div>',
			lock: true,
			padding:5,
			drag: false,
			resize: false,
			fixed: true,
			close:function(){
				$("body").eq(0).css("overflow","scroll");
			},
			init: function () {
				$("body").eq(0).css("overflow","hidden");
				if((input_address.val()=="" && action_target[0].tagName=="INPUT") || (input_address.html()=="" && action_target[0].tagName=="TD"))
				{
					readAddress(1,"pro","",0);
				}
				else
				{
					if(action_target[0].tagName=="TD")
					{
						if(input_bianma.html().length==12)
						{
							pro_value = input_bianma.html().substring(0,3);
							pro_value = pro_value+"000";
							city_value = input_bianma.html().substring(0,6);
							area_value = input_bianma.html().substring(0,12);
							readAddress(pro_value,"pro","",1);
							readAddress(city_value,"city","",1);
							readAddress(area_value,"area","",1);
						}
						else if(input_bianma.html().length==6)
						{
							if(input_bianma.html().substring(3,6)=="000")
							{
								pro_value = input_bianma.html().substring(0,6);
								readAddress(pro_value,"pro","",1);
							}
							else
							{
								pro_value = input_bianma.html().substring(0,3);
								pro_value = pro_value+"000";
								city_value = input_bianma.html().substring(0,6);
								readAddress(pro_value,"pro","",1);
								readAddress(city_value,"city","",1);
							}
						}
						else
						{
							$("[name='address_left']").val(city_address_temp);
						}
					}
					else
					{
						if(input_bianma.val().length==12)
						{
							pro_value = input_bianma.val().substring(0,3);
							pro_value = pro_value+"000";
							city_value = input_bianma.val().substring(0,6);
							area_value = input_bianma.val().substring(0,12);
							readAddress(pro_value,"pro","",1);
							readAddress(city_value,"city","",1);
							readAddress(area_value,"area","",1);
						}
						else if(input_bianma.val().length==6)
						{
							if(input_bianma.val().substring(3,6)=="000")
							{
								pro_value = input_bianma.val().substring(0,6);
								readAddress(pro_value,"pro","",1);
							}
							else
							{
								pro_value = input_bianma.val().substring(0,3);
								pro_value = pro_value+"000";
								city_value = input_bianma.val().substring(0,6);
								readAddress(pro_value,"pro","",1);
								readAddress(city_value,"city","",1);
							}
						}
						else
						{
							$("[name='address_left']").val(city_address_temp);
						}
					}
				}
				
				function readAddress(address_id,address_type,keyword,ifempty)
				{
					if(address_type=="pro")
					{
						$("div.pro_list").empty();
						$("div.city_list").empty();
						$("div.area_list").empty();
						$.ajaxSetup({
							async: false
						});
						$.get("http://"+server_url+"/web_emr/Home/Data/getAddress", {id:address_id,type:address_type,keyword:keyword,ifempty:ifempty}, function(data){
							$(data).appendTo("div.pro_list");
							if(ifempty==1)
							{
								$("[name='address_left']").val($("[bianma="+address_id+"]").html());
								if(city_address_temp.indexOf($("[name='address_left']").val())>=0)
								{
									city_address_temp=city_address_temp.replace($("[name='address_left']").val(),"");
									$("[name='address_middle']").val(city_address_temp);
								}
								pro_id = $("[bianma="+address_id+"]").attr("id");
							}
							$(".pro").click(function(){
								var i = $(this).attr('mingcheng');
								$("[name='address_left']").val(i);
								$("[name='address_middle']").val("");
								$("[name='address_right']").val("");
								temp_bianma=$(this).attr('bianma');
								pro_id = $(this).attr('id');
								readAddress(pro_id,"city","",0);
							});
						});
						$.ajaxSetup({
							async: true
						});
					}
					else if(address_type=="city")
					{
						$("div.city_list").empty();
						$("div.area_list").empty();
						$.ajaxSetup({
							async: false
						});
						$.get("http://"+server_url+"/web_emr/Home/Data/getAddress", {id:address_id,type:address_type,keyword:keyword,ifempty:ifempty}, function(data){
							$(data).appendTo("div.city_list");
							if(ifempty==1)
							{
								$("[name='address_middle']").val($("[bianma="+address_id+"]").html());
								if(area_address_temp.indexOf($("[name='address_middle']").val())>=0)
								{
									area_address_temp=area_address_temp.replace($("[name='address_left']").val(),"");
									area_address_temp=area_address_temp.replace($("[name='address_middle']").val(),"");
									$("[name='address_right']").val(area_address_temp);
								}
								city_id = $("[bianma="+address_id+"]").attr("id");
							}
							$(".city").click(function(){
								var k = $(this).attr('mingcheng');
								$("[name='address_middle']").val(k);
								$("[name='address_right']").val("");
								temp_bianma=$(this).attr('bianma');
								city_id = $(this).attr('id');
								readAddress(city_id,"area","",0);
							});
						});
						$.ajaxSetup({
							async: true
						});
					}
					else if(address_type=="area")
					{
						$("div.area_list").empty();
						$.ajaxSetup({
							async: false
						});
						$.get("http://"+server_url+"/web_emr/Home/Data/getAddress", {id:address_id,type:address_type,keyword:keyword,ifempty:ifempty}, function(data){
							$(data).appendTo("div.area_list");
							if(ifempty==1)
							{
								$("[name='address_right']").val($("[bianma="+address_id+"]").html());
							}
							$(".area").click(function(){
								var m = $(this).attr('mingcheng');
								$("[name='address_right']").val(m);
								temp_bianma=$(this).attr('bianma');
								area_id = $(this).attr('id');
							});
						});
						$.ajaxSetup({
							async: true
						});
					}
				}
				var time=null;
				function autocomplete(object)
				{
					var oldword=object.val();
					time=function(){
						var inputword=object.val();
						if(oldword!=inputword)
						{
							if(object.attr("name")=="address_left")
							{
								$("[name='address_middle']").val("");
								$("[name='address_right']").val("");
								readAddress(1,"pro",inputword,0);
							}
							if(object.attr("name")=="address_middle")
							{
								$("[name='address_right']").val("");
								readAddress(pro_id,"city",inputword,0);
							}
							if(object.attr("name")=="address_right")
							{
								readAddress(city_id,"area",inputword,0);
							}
							oldword=inputword;
						}
					}
				}
				var intervalnum=null;
				$('[name="address_left"]').focus(function(){
					autocomplete($(this));
					intervalnum=setInterval(time,100);
				}).blur(function(){
					clearInterval(intervalnum);
				});
				$('[name="address_middle"]').focus(function(){
					autocomplete($(this));
					intervalnum=setInterval(time,100);
				}).blur(function(){
					clearInterval(intervalnum);
				});
				$('[name="address_right"]').focus(function(){
					autocomplete($(this));
					intervalnum=setInterval(time,100);
				}).blur(function(){
					clearInterval(intervalnum);
				});
				$('[name="address_cancel"]').click(function(){
					art.dialog.list['city_dialog'].close();
				});
				$('[name="address_confirm"]').click(function(){
					if(action_target[0].tagName=="TD")
					{
						input_address.html($("[name='address_left']").val()+$("[name='address_middle']").val()+$("[name='address_right']").val());
						if(temp_bianma!=null)
						{
							input_bianma.html(temp_bianma);
						}
					}
					else
					{
						input_address.val($("[name='address_left']").val()+$("[name='address_middle']").val()+$("[name='address_right']").val());
						if(temp_bianma!=null)
						{
							input_bianma.val(temp_bianma);
						}
					}
					art.dialog.list['city_dialog'].close();
				});
			}
		});
	});
});
