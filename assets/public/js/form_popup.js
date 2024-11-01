function load_dms_popup(element_class,ajax_url,loading_delay,frequency_days,form_display_type,form_display,frequency_days_on_close,is_animated){
	
	if(jQuery(".custom_datepicker").length>0){
		
		jQuery('.custom_datepicker').datepicker({
			format: 'yyyy-mm-dd',
			autoclose:true
		});	
	}
	
	if(form_display_type==1){
		dms_load_scroll_popup()
	}else if(form_display_type==2){
		dms_load_exit_popup()
	}else{
		dms_load_simple_popup();
	}

	jQuery(document).ready(function(){

		jQuery(document).on("click","."+element_class+" .close",function(){
			jQuery.cookie(element_class, '1', { expires: frequency_days_on_close, path: '/' });
		});

	    jQuery("."+element_class).on('hidden.bs.modal', function () {
			jQuery("."+element_class+" .dms_response_div").html('');
		});

		jQuery(document).on("click",".dms_popup_button",function(){
			var currentObj=jQuery(this);
			var currentFormId=currentObj.data('id');

			var form = jQuery("#"+currentFormId);
			var email_input=form.find('.email');
			var dms_response_div=form.find(".dms_response_div");
			dms_response_div.html('');
			if(jQuery.trim(email_input.val()).length>0){
				if(isValidEmailAddress(email_input.val())){
					var formData=form.serialize();
					jQuery.ajax({
						url:ajax_url,
						data:formData,
						dataType:"json",
						type:"POST",
						beforeSend:function(){
							currentObj.attr('disabled','disabled');
							jQuery(".ajaxloader").show();
						},
						success:function(res){
							if(res.status==1){
								var htmlerror='<div class="alert-success" role="alert">'+res.message+'</div>';
								form.find("input, textarea").val("");
								currentObj.removeAttr('disabled');
								jQuery.cookie(element_class+'_subbmited','1',{ expires: frequency_days, path: '/' });
								/*setTimeout(function(){
									jQuery("."+element_class).modal("hide");
								},3000);*/
								form.find(".form-group").hide();
							}else{
								var htmlerror='<div class="alert-danger" role="alert">'+res.message+'</div>';
								currentObj.removeAttr('disabled');
							}
							dms_response_div.html(htmlerror);
							jQuery(".ajaxloader").hide();
						},
						error:function(xhr){
							var htmlerror='<div class="alert-danger" role="alert">'+xhr.responseText+'</div>';
							dms_response_div.html(htmlerror);
							currentObj.removeAttr('disabled');
							jQuery(".ajaxloader").hide();
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
		
	});

	function dms_popup_open(){
		
		if(typeof jQuery.cookie(element_class)=='undefined'){
			if(typeof jQuery.cookie(element_class+'_subbmited')=='undefined'){	
				var animInClass="";
				if(is_animated==1){
					animInClass="bounceInLeft";
				}else if(is_animated==2){
					animInClass="bounceInRight";
				}else if(is_animated==3){
					animInClass="bounceInUp";
				}else if(is_animated==4){
					animInClass="bounceInDown";
				}
				
				jQuery("."+element_class).addClass(animInClass);
    			jQuery("."+element_class).modal({backdrop: false});
	    		jQuery("."+element_class).modal("show");
	    	}
		}

	}

	function dms_load_exit_popup(){
		jQuery(document).on("mouseout",function(e){
			e=e?e:window.event;
			var documentWidth=Math.max(document.documentElement.clientWidth,window.innerWidth||0);
			if(e.clientX>=(documentWidth-50)){
				return;
			}

			if(e.clientY>=50){
				return;
			}
			
			var popuupbar = e.relatedTarget || e.toElement;
			if(!popuupbar){
				dms_popup_open();
			}			
		});
	}

	function dms_load_scroll_popup(){
		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop()>=100){
				dms_popup_open();	
			}
	    });	
	}

	function dms_load_simple_popup(){
		jQuery(window).load(function(){
			setTimeout(function(){
				dms_popup_open();	
			},(loading_delay*1000));
		});		
	}
}