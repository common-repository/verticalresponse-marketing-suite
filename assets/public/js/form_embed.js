(function($){
	'use strict';

	if($(".custom_datepicker").length>0){
		$('.custom_datepicker').datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true
		});	
	}
	

	$(document).on("click",".dms_embeded_button",function(){
		//$(".dms_response_div").html("");
		var currentObj=$(this);
		var currentFormId=currentObj.data('id');

		var form = $("#"+currentFormId);
		var email_input=form.find('.email');
		var dms_response_div=form.find(".dms_response_div");
		dms_response_div.html('');
		if($.trim(email_input.val()).length>0){
			if(isValidEmailAddress(email_input.val())){
				var formData=form.serialize();
				$.ajax({
					url:embedsetting.ajax_url,
					data:formData,
					dataType:"json",
					type:"POST",
					beforeSend:function(){
						currentObj.attr('disabled','disabled');
						$(".ajaxloader").show();
					},
					success:function(res){
						if(res.status==1){
							var htmlerror='<div class="alert-success" role="alert">'+res.message+'</div>';
							form.find("input, textarea").val("");
							currentObj.removeAttr('disabled');
							form.find(".form-group").hide();
							//currentObj.hide();
						}else{
							var htmlerror='<div class="alert-danger" role="alert">'+res.message+'</div>';
							currentObj.removeAttr('disabled');
						}
						dms_response_div.html(htmlerror);
            $(".ajaxloader").hide();
					},
					error:function(xhr){
						var htmlerror='<div class="alert-danger" role="alert">'+xhr.responseText+'</div>';
						dms_response_div.html(htmlerror);
						currentObj.removeAttr('disabled');
            $(".ajaxloader").hide();
					}
				});
			}else{
				var error='<div class="alert-danger" role="alert">Please enter valid email address</div>';
				dms_response_div.html(error);
			}
		}else{
			var error='<div class="alert-danger" role="alert">Please enter email address</div>';
			dms_response_div.html(error);
		}
	});

})(jQuery);