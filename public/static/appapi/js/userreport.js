$(function(){

	

	/*点击下一步*/

	$(".report_list_right input").click(function(){
		
		$(".reportarea input").attr("disabled", false);
		
		
	});

	$(".reportarea input").click(function(){
		var val=$('input:radio[name="classifyid"]:checked').val();
		
		if(!val){
			layer.msg(LangT("请选择举报理由"));
			return;
		}
		$(".classify_area").hide();
		$(".report_con").show();
	});

});