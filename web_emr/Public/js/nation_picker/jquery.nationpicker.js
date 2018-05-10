/**
 * @原创作者 郭瑾
 * @改进作者 tiantanhehe
 * @版本 jquery.nationpicker.js(version="1.1.0")
 */

$(function(){
	$("[action_type='nation_picker']").click(function(){ 
				var input_nation=$(this);
				art.dialog({
					id:"nation_dialog",	   
					title:"民族选择",	
					content: '<div class="nation">'+
										'<div class="nation_content">'+
											'<div class="nation_list"></div>'+
										'</div>'+
										'<div class="wrap_foot">'+
											'<input type="button" class="nation_button" name="nation_cancel" value="取消"/>'+
										'</div>'+
									'</div>',
					lock: true,
					padding:5,
					init: function () {
							$("div.nation_list").empty();
							$.get("http://"+server_url+"/web_emr/Home/Data/getFlatData", {id: "31000", label_class: "nation_item"}, function(nation_data){
								$(nation_data).appendTo("div.nation_list");
								$(".nation_item").click(function(){
									var i = $(this).attr('mingcheng');
									input_nation.val(i);
									art.dialog.list['nation_dialog'].close();
								});
							});
							$('[name="nation_cancel"]').click(function(){
								art.dialog.list['nation_dialog'].close();
							});
						}
				});
				return false;
		 });
});
