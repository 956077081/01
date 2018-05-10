/**************************************************
*  Created:  2013-09-01
*  Info:登录页面
*  @Tiantanhehe (C)2011-3011 Tiantanhehe
*  @Author DongJie <dongjie@tiantanhehe.com>
*  @Version 1.0
*  @Updated History:  
***************************************************/
//表单相关事件的配置参数：
//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
var form_options={
			dataType: 'json',
			success:function showResponse(data){
				if(data.result=="true")
				{
					window.location.href="/web_emr/System/showSystemChecked";
				}
				else
				{
					$(".login_tips").html(data.message);
				}
			}
};

$(function(){
	$(".ajax_form").ajaxForm(form_options);
	$("[name='user_number']").focus();
});
