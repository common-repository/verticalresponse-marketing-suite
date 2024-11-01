(function($){
    'use strict';
    
    generate_preview();
    $(document).ready(function(){
        $(document).on("change","#form_title",function(){
            generate_preview();
        });

        $(document).on("change","#form_description",function(){
            generate_preview();
        });

        $(document).on("change","#dms_show_as_popup",function(){
            generate_preview();
        });
        
        $(document).on("change","#show_popup_as_topbar",function(){
            generate_preview();
        });
        
        $(document).on("change","#show_popup_as_normal",function(){
            generate_preview();
        });
        
        $(document).on("change","#show_popup_as_bottombar",function(){
            generate_preview();
        });

        $(document).on("change","#sortable1 input",function(){
            generate_preview();
        });

        $(document).on("change","#button_shape_sharp_edge",function(){
            generate_preview();
        });

        $(document).on("change","#button_shape_smooth_edge",function(){
            generate_preview();
        });

        $(document).on("change",".formbgimageurl",function(){
            generate_preview();
        });

        $(document).on("click",".delete_c_field",function(){
            var id=$(this).data('id');
            $("#"+id).remove();
            generate_preview();
        });

        $(document).on("change","#button_text",function(){
            generate_preview(); 
        });

        $('.list-group-sortable-connected').sortable({
            placeholderClass: 'list-group-item',
            connectWith: '.connected',
            items: ':not(.disabled)'
        }).bind('sortupdate', function() {
            generate_preview();
        }).bind('drop', function() {
            generate_preview();
        });
        
        
        $('#sortable1').sortable({
            placeholderClass: 'list-group-item',
            connectWith: '.connected2'
        });

        $( '.cpa-color-picker' ).wpColorPicker({
            clear: function() {
                 generate_preview(); 
            },
            change:function(){
                setTimeout(function(){
                    generate_preview(); 
                },1000);
                   
            }
        });
    });
    
    $(window).scroll(function(){
        var w_width=$(window).width();
        if(jQuery(window).scrollTop()>=200){
            $(".cutom-topbar").addClass("fixtopbar");    
        }else{
            $(".cutom-topbar").removeClass("fixtopbar");
        }
        
        if(jQuery(window).scrollTop()>=200 && w_width>979){
            $(".prview_container .p_content").css("margin-top",jQuery(window).scrollTop()-150);
        }else{
            $(".prview_container .p_content").css("margin-top",0);
            
        }
    }); 

    function generate_preview(){
        //if($("#dms_show_as_popup").is(":checked")==true){
            var form_title=$("#form_title").val();
            var form_description=$("#form_description").val();
            var form_bg_color=$("#form_bg_color").val();
            var form_text_color=$("#form_text_color").val();
            var form_bg_image=$("#form_bg_image").val();
            var button_text=$("#button_text").val();
            var button_bg_color=$("#button_bg_color").val();
            var button_text_color=$("#button_text_color").val();
            var popup_html="";
            var popup_style="";
            var show_as_popup=0;
            var button_shape=5;
            var bar_postion="top";
            var btn_hover_bg_color=$("#button_hover_bg_color").val();
            var btn_hover_text_color=$("#button_hover_text_color").val();

            if($("#button_shape_sharp_edge").is(":checked")==true){
                button_shape=0;
            }

            if($("#show_popup_as_topbar").is(":checked")==true){
                show_as_popup=1;
                bar_postion="top";
            }else if($("#show_popup_as_bottombar").is(":checked")==true){
                show_as_popup=2;
                bar_postion="bottom";
            }
            if($("#dms_show_as_popup").is(":checked")==false){
                show_as_popup=0;
            }
            
            var form_width=(parseInt($("#form_width").val())>0?parseInt($("#form_width").val()):365);
            var custom_fields="";
            if(show_as_popup==0){

                popup_style+='<style type="text/css">';
                popup_style+='.prviewpopup{display: block !important;position: absolute !important;z-index: 1 !important;overflow: visible !important;opacity: 1 !important;padding: 2%; width:96%;}';
                popup_style+='.prviewpopup .shadow .modal-dialog{width:auto !important; display:block !important;position: relative !important;margin: 0 auto !important;}';

                if($.trim(form_bg_image).length>0){
                    popup_style+='.prviewpopup .modal-content{background:url('+form_bg_image+') '+form_bg_color+'; color:'+form_text_color+'; background-size:cover; background-repeat: no-repeat; border:none; }';      
                }else{
                    popup_style+='.prviewpopup .modal-content{background:'+form_bg_color+'; color:'+form_text_color+'; border:none; box-shadow: 1px 1px 5px;}';
                }
                
                if($("#sortable1").length>0){
                    $("#sortable1").find(".form-group input").each(function(){
                        custom_fields+='<div class="form-group"><input type="'+$(this).attr('type')+'" class="form-control text-center" name="" placeholder="'+$(this).attr('placeholder')+'" value="'+$(this).val()+'"></div>';
                    });
                }else{
                    custom_fields='<div class="form-group"><input type="email" class="form-control text-center email" name="" placeholder="Email Address"></div>';
                }

                popup_style+='.prviewpopup .modal-body{padding-top:0;padding-bottom:0;}';
                popup_style+='.prviewpopup .close{color:'+form_text_color+';opacity:1;margin-right: -24px !important;display: inline-block;margin-top: -24px !important;}';
                popup_style+='.prviewpopup .close:hover{background: transparent;}';
                popup_style+='.prviewpopup .close img{width:24px;}';
                popup_style+='.prviewpopup .modal-header{padding:0;border:none;}';
                popup_style+='.prviewpopup .modal-title{display:inline-block;margin:5px 0 0 0;font-size:20px !important;padding:0 !important;}';
                popup_style+='.prviewpopup .dms_popup_description{font-size: 15px;font-style: italic;margin-bottom:5px;}';
                popup_style+='.prviewpopup .dms_popup_button{border-radius: '+button_shape+'px;padding: 7px;margin-bottom: 15px;background:'+button_bg_color+';color:'+button_text_color+';font-size:15px; border:none;}';
                popup_style+='.prviewpopup .dms_popup_button:hover{background:'+btn_hover_bg_color+';color:'+btn_hover_text_color+';}';
                popup_style+='.prviewpopup .dms_response_div {margin: 5px auto;}</style>';


                popup_html+='<div class="text-center prviewpopup" tabindex="-1" role="dialog">';
                    popup_html+='<div class="modal-dialog" role="document">';

                    popup_html+='<div class="modal-content">';
                        popup_html+='<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="'+pj.assetsurl+'public/close.png"></span></button></div>';
                        popup_html+='<div><h4 class="modal-title">'+form_title+'</h4></div>';

                        popup_html+='<div class="modal-body">';
                
                            popup_html+='<div class="popup_description">'+form_description+'</div>';
                            popup_html+='<form action="" method="post" class="dms_mailing_form"  id="dms_popup_form">';
                                popup_html+=custom_fields;
                                popup_html+='<div class="form-group"><button class="dms_popup_button col-12 w-100" type="button" data-id="dms_popup_form">'+button_text+'</button></div><div class="clearfix"></div>';

                            popup_html+='</form>';
                        popup_html+='</div>';
                    popup_html+='</div>';
                popup_html+='</div>';
                $(".preview_html").html('');
                $(".preview_html").html(popup_style+popup_html);
                $(".preview_html .form-group label").hide();
                
            }else if(show_as_popup==1 || show_as_popup==2){
                popup_html='<style type="text/css">.preview_topbar{display: none;';
                if($.trim(form_bg_image).length>0){
                    popup_html+='background: url('+form_bg_image+') '+form_bg_color+' !important;background-size: cover !important;background-repeat: no-repeat !important;';
                }else{ 
                    popup_html+='background:'+form_bg_color+' !important;';
                }
                    
                popup_html+='color:'+form_text_color+' !important;border: none !important;padding: 2px 0 0 0 !important;}.preview_topbar .alert-danger, .preview_topbar .alert-success{padding-top:5px !important; padding-bottom:5px !important; margin: 0px !important; font-size: 15px !important;}.preview_topbar .container{}.preview_topbar .form_topbar_text{color:'+form_text_color+' !important;margin: 0px !important;padding: 6px 0 0 0 !important;font-size: 19px !important;}.preview_topbar .closetopbar{color:'+form_text_color+' !important;opacity:1 !important;margin-right: 2px !important;display: inline-block !important;margin-top: 7px !important;}.preview_topbar .closetopbar:hover{background: transparent !important;}.preview_topbar .email{margin-top: 6px !important;} .preview_topbar .dms_topbar_button{border-radius: 5px !important;padding: 7px !important;margin-top: 6px !important;background:'+button_bg_color+' !important;color:'+button_text_color+' !important;font-size:15px !important;border: none !important;}.preview_topbar .dms_topbar_button:hover{background:'+btn_hover_bg_color+' !important; color:'+btn_hover_text_color+' !important;}.preview_topbar .dms_response_div {margin: 5px auto !important;}@media only screen and (max-width:320px){.preview_topbar .dms_topbar_button{font-size: 12px !important;}}</style><nav role="navigation" class="navbar navbar-default custom-'+bar_postion+' preview_topbar"><div class="container" style="display:block"><form  style="display: inline-flex" action="" method="post" class="dms_mailing_form"  id="dms_topbar_form"><div class="col-12 col-sm-5 text-center success-hide"><p class="form_topbar_text">'+form_title+'</p></div><div class="col-8 col-sm-4 success-hide"><input type="hidden" name="action" value="dms_submit_and_send"><input type="hidden" name="action" value="dms_submit_and_send"><input type="hidden" name="setting_field" value=""><input type="hidden" name="setting_field_id" value=""><input type="email" class="form-control text-center email" name="form_fields" placeholder="Email Address"></div><div class="col-4 col-sm-3 success-hide"><button class="dms_topbar_button col-12 w-100" type="button" data-id="dms_topbar_form_<?php echo md5($form->id);?>">'+button_text+'</button></div><div class="clearfix success-hide"></div><div class="dms_response_div text-center"></div></form></div></nav>';
                $(".preview_html").html('');
                $(".preview_html").html(popup_html);
                $(".preview_topbar").show();
            }    
        /*}else{
               $(".preview_html").html('');
        }*/
    }

})(jQuery);