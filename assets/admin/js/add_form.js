var cb_checked=false;

(function($){
    'use strict';

    $(function() {
        $( '.cpa-color-picker' ).wpColorPicker();
    });

    $(document).on('click', '.panel-heading span.clickable', function(e){
	    var $this = $(this);
		if(!$this.hasClass('panel-collapsed')) {
			$this.parents('.panel').find('.panel-body').slideUp();
			$this.addClass('panel-collapsed');
			$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
		} else {
			$this.parents('.panel').find('.panel-body').slideDown();
			$this.removeClass('panel-collapsed');
			$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		}
	});

    var all_selected=true;
    $("#form_display_on option").each(function(){
        if($(this).val()!="" && !$(this).is(':selected')){
            all_selected=false;
        }
    });
    if(all_selected){
        cb_checked=true;
        $("#select_all_page").prop('checked',true);    
    }else{
        cb_checked=false;
        $("#select_all_page").prop('checked',false);
    }
    $(document).on("click","#select_all_page",function(){
        if(!cb_checked){
            $("#form_display_on option").each(function(){
                if($(this).val()!=""){
                    $(this).prop('selected', true);
                }else{
                    $(this).prop('selected', false);
                }
                cb_checked=true;
            });
        }else{
            $("#form_display_on option").each(function(){
                if($(this).val()!=""){
                    $(this).prop('selected', false);
                }else{
                    $(this).prop('selected', true);
                }
                cb_checked=false;
            });
        }
        
    });
    
    $(document).on("click",".create_new_vr_list",function(){
        $(".vr_contact_list_modal").modal("show");
    });  
    
    $(document).on("change","#dms_contect_list_id",function(){
        if($(this).val()=='add_new'){
            $(".create_new_vr_list").trigger("click");
        }
    });
    
    $(document).on("click",".save_btn_vr_list",function(){
        var app_response=$(".app_response");
        app_response.html("");
        if($.trim($("#list_name_id").val()).length>0){
            var form_data=$(".contact_add_vr_form").serialize();
            $.ajax({
                url:ajaxurl,
                data:form_data,
                type:"POST",
                dataType:"JSON",
                beforeSend:function(){
                    $(".ajaxloader").show();                    
                },
                success:function(res){
                    if(res.status==1){
                        var option_html='<option value="'+res.list_id+'" selected="selected">'+res.name+'</option>';
                        $('.dms_contect_list_id option').removeAttr("selected");
                        $("#dms_contect_list_id option:first").after(option_html);
                        $(".ajaxloader").hide();
                        $('.vr_contact_list_modal').modal("hide");
                    }else{
                        app_response.html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span class="sr-only">Error:</span>'+res.message+'</div>');    
                    }
                },
                error:function(){
                    app_response.html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span class="sr-only">Error:</span>Due to some error in processing</div>');
                    $(".ajaxloader").hide();
                }
            });
        }else{
            app_response.html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span class="sr-only">Error:</span>Please enter list name</div>');
        }

    });

    $('.vr_contact_list_modal').on('hidden.bs.modal', function (e) {
        $(".app_response").html("");
        $("#list_name_id").val("");
    })    
   
    /*if($(".show_as_form_input:checked")){
        var show_as_form_input=parseInt($(".show_as_form_input:checked").val());
         if(show_as_form_input==0){
            $(".form-display-type").hide();
        }else{
            $(".form-display-type").show();
        }
    }*/
    
    /*$(document).on("change",".show_as_form_input",function(){
        var show_as_form_input=parseInt($(this).val());
        if(show_as_form_input==0){
            $(".form-display-type").hide();
        }else{
            $(".form-display-type").show();
        }
    });*/
    if($("#dms_show_as_popup").is(":checked")==true){
        $(".show_as_popup_div").toggle();
        //$(".form-display-type").toggle();
    }

    $(document).on("change","#dms_show_as_popup",function(){
        $(".show_as_popup_div").toggle();
        //$(".form-display-type").toggle();
    });

    $(document).on("click",".form_submit_btn",function(){
        $("#form_status").val($(this).data('status'));
        $("#submit_handle").trigger("click");
        //$(".dms_connnect_vr_form").submit();
    });

})(jQuery);