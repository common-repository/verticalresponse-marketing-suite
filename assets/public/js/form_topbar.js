function load_dms_topbar(element_class,ajax_url,loading_delay,frequency_days,form_display_type,form_display,frequency_days_on_close){
	
	if(form_display_type==1){
		dms_load_scroll_bar()
	}else if(form_display_type==2){
		dms_load_exit_bar()
	}else{
		dms_load_simple_bar();
	}

	jQuery(document).on("click","."+element_class+" .closetopbar",function(){
		jQuery.cookie(element_class, '1', { expires: frequency_days_on_close, path: '/' });
		jQuery("."+element_class).hide();
	});

	jQuery(document).on("click",".dms_topbar_button",function(){
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
							var htmlerror='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.message+'</div>';
							form.find("input, textarea").val("");
							currentObj.removeAttr('disabled');
							jQuery.cookie(element_class+'_subbmited','1',{ expires: frequency_days, path: '/' });
							
							/*setTimeout(function(){
								jQuery("."+element_class).hide();
							},3000);*/
							form.find('.success-hide').hide();
                                                }else{
							var htmlerror='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+res.message+'</div>';
							currentObj.removeAttr('disabled');
                                                }
						dms_response_div.html(htmlerror);
            jQuery(".ajaxloader").hide();
					},
					error:function(xhr){
						var htmlerror='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+xhr.responseText+'</div>';
						dms_response_div.html(htmlerror);
						currentObj.removeAttr('disabled');
            jQuery(".ajaxloader").hide();
					}
				});
			}else{
				var error='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Please enter valid email address</div>';
				dms_response_div.html(error);
			}
		}else{
			var error='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Please enter email address</div>';
			dms_response_div.html(error);
		}
		
	});

	function dms_topbar_open(){
		if(typeof jQuery.cookie(element_class)=='undefined'){
			if(typeof jQuery.cookie(element_class+'_subbmited')=='undefined'){	
	    		jQuery("."+element_class).show();
	    	}
		}
	}

	function dms_load_scroll_bar(){
		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop()>=100){
				dms_topbar_open();	
			}
	    });
	}

	function dms_load_exit_bar(){
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
				dms_topbar_open();
			}			
		});

	}

	function dms_load_simple_bar(){
		jQuery(window).load(function(){
			setTimeout(function(){
				dms_topbar_open();	
			},(loading_delay*1000));
		});
	}
}