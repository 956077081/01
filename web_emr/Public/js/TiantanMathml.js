/*
	@Name:TiantanMathml.js
	@Author:Dongjie
	@Copyright:Tiantanhehe 2013
	@Latest Update:2013-07-20
*/

var temp_text = "";
var formula_str_with_tag = "";
var formula_str = "";
var start_pos = "";
var end_pos = "";

function formulaProcessAllContent()
{
	$("p").each(function(){
		temp_text = $(this).html();
		if(temp_text.indexOf("[gs]")!=-1)
		{
			start_pos = temp_text.indexOf("[gs]");
			end_pos = temp_text.indexOf("[/gs]")+5;
			formula_str_with_tag = temp_text.substring(start_pos,end_pos);
			formula_str = temp_text.substring(start_pos+4,end_pos-5);
			fenshi = formula_str.split("|");
			if(fenshi.length==3)
			{
				before = fenshi[0];
				after = fenshi[2];
				fenshi_body = fenshi[1].split("/");
				up = fenshi_body[0].substring(1,fenshi_body[0].length-1);
				down = fenshi_body[1].substring(1,fenshi_body[1].length-1);
			}
			//[gs]17|(4-5)/28-30)|51[/gs]
			var yuejing_formula = '<span id="formula">'+
														'<span id="fenshi">'+
															'<span id="before">'+before+'</span>'+
															'<span id="up">'+up+'</span>'+
															'<span id="spliter"></span>'+
															'<span id="down">'+down+'</span>'+
															'<span id="after">'+after+'</span>'+
														'</span>'+
														'</span>';
			temp_text = temp_text.replace(formula_str_with_tag,yuejing_formula);
			$(this).html(temp_text);
		}
	});
}

function formulaProcessOneContent(processed_content)
{
	temp_text = processed_content;
	if(temp_text.indexOf("[gs]")!=-1)
	{
		start_pos = temp_text.indexOf("[gs]");
		end_pos = temp_text.indexOf("[/gs]")+5;
		formula_str_with_tag = temp_text.substring(start_pos,end_pos);
		formula_str = temp_text.substring(start_pos+4,end_pos-5);
		fenshi = formula_str.split("|");
		if(fenshi.length==3)
		{
			before = fenshi[0];
			after = fenshi[2];
			fenshi_body = fenshi[1].split("/");
			up = fenshi_body[0].substring(1,fenshi_body[0].length-1);
			down = fenshi_body[1].substring(1,fenshi_body[1].length-1);
		}
		//[gs]17|(4-5)/28-30)|51[/gs]
		var yuejing_formula = '<span id="formula">'+
													'<span id="fenshi">'+
														'<span id="before">'+before+'</span>'+
														'<span id="up">'+up+'</span>'+
														'<span id="spliter"></span>'+
														'<span id="down">'+down+'</span>'+
														'<span id="after">'+after+'</span>'+
													'</span>'+
													'</span>';
		temp_text = temp_text.replace(formula_str_with_tag,yuejing_formula);
	}
	return temp_text;
}