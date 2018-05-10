/**
 * @原创作者 郭瑾
 * @改进作者 tiantanhehe
 * @版本 jquery.mazuipicker.js(version="1.1.0")
 */

$(function(){
	$("[action_type='mazui_picker']").click(function(){
		if($("#mazui_search_input").length==0)
		{
			var search_html = '<input class="mazui_search_input" id="mazui_search_input" type="text" />'+
                			'<input class="mazui_search_button" id="mazui_search_button" type="button" value="查找"/>'+
                		'<input class="mazui_show_all_button" id="mazui_show_all_button" type="button" value="显示所有"/>';
        	$(".mazui_content").before(search_html);
		}
		
				art.dialog({
					id:"mazui_dialog",	   
					title:"麻醉方式选择",	
					content: $(this).prev().html(),
					lock: true,
					padding:5,
					init: function () {
							$("div.mazui_list").empty();
							$.get("http://"+server_url+"/web_emr/Home/Data/getFlatData", {id: "33000", label_class: "mazui_item"}, function(mazui_data){
								$(mazui_data).appendTo("div.mazui_list");
								$(".mazui_item").live("click",function(){
									var i = $(this).attr('mingcheng');
									var input_name=$(this).parent().parent().parent().attr('input_name');
									$("[name='"+input_name+"']").val(i);
									art.dialog.list['mazui_dialog'].close();
								});
							});
						}
				});
				return false;
		 });

	$("#mazui_search_input").live("keyup",function(){
		var keyword = $(this).val();
		$.post("http://"+server_url+"/web_emr/Home/Data/searchMazuiFangshi", {keyword: keyword}, function(data){
			if(data!="")
			{
				$("div.mazui_list").html(data);
			}
		});
	});

	$("#mazui_search_button").live("click",function(){
		var keyword = $(this).prev().val();
		$.post("http://"+server_url+"/web_emr/Home/Data/searchMazuiFangshi", {keyword: keyword}, function(data){
			if(data!="")
			{
				$("div.mazui_list").html(data);
			}
		});
	});

	$("#mazui_show_all_button").live("click",function(){
		$.get("http://"+server_url+"/web_emr/Home/Data/getFlatData", {id: "33000", label_class: "mazui_item"}, function(data){
			$("div.mazui_list").html(data);
		});
	});
});
