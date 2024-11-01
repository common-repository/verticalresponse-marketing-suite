<style type="text/css">
    .<?php echo esc_html($element_class);?>{
        display: none;
        z-index: 8888888 !important;
        <?php if(!empty($form->popup_bg_image)){?>
            background: url('<?php echo esc_url($form->popup_bg_image);?>') <?php echo esc_attr($form->popup_bg_color);?> !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
        <?php }else{?>
             <?php if(!empty($form->popup_bg_color)){?> background:<?php echo esc_attr($form->popup_bg_color);?> !important; <?php }?>
        <?php }?>
        
        <?php if(!empty($form->popup_text_color)){?> color:<?php echo esc_attr($form->popup_text_color);?> !important; <?php }?>
        border: none !important;
        padding: 2px 0 0 0 !important;
    }
    .<?php echo esc_html($element_class);?> .alert-danger, 
    .<?php echo esc_html($element_class);?> .alert-success{padding-top:5px !important; padding-bottom:5px !important; margin: 0px !important;font-size: 15px !important;}

    .<?php echo esc_html($element_class);?> .container{
        /*padding-bottom: 10px !important;*/
    }

    .<?php echo esc_html($element_class);?> .form_topbar_text{
        <?php if(!empty($form->popup_text_color)){?> color:<?php echo esc_attr($form->popup_text_color);?> !important; <?php }?>
        margin: 0px !important;
        padding: 6px 0 0 0 !important;
        font-size: 19px !important;
    }

    .<?php echo esc_html($element_class);?> .closetopbar{
        <?php if(!empty($form->popup_text_color)){?> color:<?php echo esc_attr($form->popup_text_color);?> !important; <?php }?>
        opacity:1 !important;
        margin-right: 2px !important;
        display: inline-block !important;
        margin-top: 7px !important;
    }

    .<?php echo esc_html($element_class);?> .closetopbar:hover{
        background: transparent !important; 
    }

    .<?php echo esc_html($element_class);?> .email{
        margin-top: 6px !important;
    } 
    .<?php echo esc_html($element_class);?> .dms_topbar_button{
        border-radius: 5px !important;
        padding: 7px !important;
        margin-top: 6px !important;
        <?php if($form->btn_shape==0){?>
            border-radius: 0px !important;
        <?php }else{?>
             border-radius: 5px !important;
        <?php }?>

        <?php if(!empty($form->btn_bg_color)){?>background:<?php echo esc_attr($form->btn_bg_color);?> !important; <?php }?>
        <?php if(!empty($form->btn_text_color)){?>color:<?php echo esc_attr($form->btn_text_color);?> !important; <?php }?>
        font-size:15px !important;
        border: none !important;
    }
    .<?php echo esc_html($element_class);?> .dms_topbar_button:hover{
        <?php if(!empty($form->btn_hover_bg_color)){?>
            background:<?php echo esc_attr($form->btn_hover_bg_color);?> !important; 
        <?php }else{?>
            <?php if(!empty($form->btn_bg_color)){?>
                background:<?php echo esc_attr($form->btn_bg_color);?> !important; 
            <?php }?>
        <?php }?>

        <?php if(!empty($form->btn_hover_text_color)){?>
            color:<?php echo esc_attr($form->btn_hover_text_color);?> !important; 
        <?php }else{?>
            <?php if(!empty($form->btn_text_color)){?>
                color:<?php echo esc_attr($form->btn_text_color);?> !important; 
            <?php }?>  
        <?php }?>
    }

    .<?php echo esc_html($element_class);?> .dms_response_div {
        margin: 5px auto !important;
    }
    @media only screen and (max-width:320px){
        .<?php echo esc_html($element_class);?> .dms_topbar_button{
            font-size: 12px !important;
        }
    }
    .popupboxshadow {
      -webkit-box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5) !important;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5) !important;
    }
</style>
<nav role="navigation" class="navbar navbar-default navbar-fixed-bottom <?php echo esc_attr($element_class);?> popupboxshadow">
    <button type="button" class="close closetopbar" data-id="" aria-label="Close" style="background: transparent">
      <span aria-hidden="true">&times;</span>
    </button>
    <div class="container">
        <form action="" method="post" class="dms_mailing_form"  id="dms_topbar_form_<?php echo md5($form->id);?>">
            <div class="col-12 col-sm-12 text-center success-hide p-0">
               <p class="form_topbar_text"><?php echo esc_html($form->title);?></p>
            </div>
            <div class="col-12 col-sm-12 success-hide p-0">
                <input type="hidden" name="action" value="dms_submit_and_send">
                <input type="hidden" name="setting_field" value="">
                <input type="hidden" name="setting_field_id" value="<?php echo md5($form->id);?>">
                <input type="email" class="form-control text-center email" name="form_fields[email]" placeholder="Email Address">
               
            </div>
            <div class="col-12 col-sm-12 success-hide p-0">
                <button class="dms_topbar_button col-12 w-100" type="button" data-id="dms_topbar_form_<?php echo md5($form->id);?>"><?php echo esc_html($form->button_text);?></button>
            </div>
            <div class="clearfix success-hide p-0"></div>
            <div class="dms_response_div text-center"></div>
        </form>
    </div>
</nav>

<div class="clearfix"></div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("div.<?php echo esc_attr($element_class);?>").parent().css('transform','initial');
        load_dms_topbar('<?php echo esc_html($element_class);?>','<?php echo admin_url('admin-ajax.php');?>',<?php echo esc_html($form->loading_delay);?>,<?php echo esc_html($form->frequency_days);?>,<?php echo esc_html($form->form_display_type);?>,<?php echo esc_html($form->form_display_befor_after_login);?>,<?php echo esc_html($form->frequency_days_on_close);?>);
    });
</script>