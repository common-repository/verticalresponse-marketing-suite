<style type="text/css">
	.datepicker table{width:auto;}
	.animated{animation-duration:2s !important;}


	.<?php echo esc_html($element_class);?> input{
		padding: 0 !important;
	}

	.<?php echo esc_html($element_class);?>.fade.in{
		background: rgba(0,0,0,0.6) !important;
		z-index: 8888888 !important
	}
	.<?php echo esc_html($element_class);?>.animated.in{
		z-index: 8888888 !important
	}
	.<?php echo esc_html($element_class);?> .alert-danger, 
	.<?php echo esc_html($element_class);?> .alert-success{padding:5px 0 !important; }



	.<?php echo esc_html($element_class);?> .modal-dialog{
		width:<?php echo esc_html($form->popup_width);?>px !important;

		<?php if(!empty($form->popup_height)) {?>
			height:<?php echo esc_html($form->popup_height);?>px !important;
		<?php }?>

		display:inline-block !important;
		position: absolute !important;
	  	top: 50% !important;
	  	left: 50% !important;
		-webkit-transform: translate(-50%, -50%) !important;
		-moz-transform: translate(-50%, -50%) !important;
		-ms-transform: translate(-50%, -50%) !important;
		-o-transform: translate(-50%, -50%) !important;
		transform: translate(-50%, -50%) !important;
		margin: 0 !important;
		border-radius: 6px;

		<?php if(!empty($form->popup_bg_image)){?>
			background: url('<?php echo esc_url($form->popup_bg_image);?>') <?php echo esc_attr($form->popup_bg_color);?> !important;
			background-size: cover !important;
			background-repeat: no-repeat !important;
		<?php }else{?>
			 <?php if(!empty($form->popup_bg_color)){?> background:<?php echo esc_attr($form->popup_bg_color);?> !important; <?php }?>
		<?php }?>
	}


	.<?php echo esc_html($element_class);?> .modal-content{

		background: transparent;
		box-shadow: none;


		<?php if(!empty($form->popup_text_color)){?> color:<?php echo esc_attr($form->popup_text_color);?> !important; <?php }?>
		border: none !important;

        /* added following css to set model-content vertical center when custom height*/ 

		<?php if(!empty($form->popup_height)) {?>
		  	top: 50% !important;
		  	left: 50% !important;
			-webkit-transform: translate(-50%, -50%) !important;
			-moz-transform: translate(-50%, -50%) !important;
			-ms-transform: translate(-50%, -50%) !important;
			-o-transform: translate(-50%, -50%) !important;
			transform: translate(-50%, -50%) !important;
			margin: 0 !important;
		<?php }?>

	}

	.<?php echo esc_html($element_class);?> .modal-body{
		padding-top:0 !important;
		padding-bottom:0 !important;
	}

	.<?php echo esc_html($element_class);?> .close{
		<?php if(!empty($form->popup_text_color)){?> color:<?php echo esc_attr($form->popup_text_color);?> !important; <?php }?>
		opacity:1 !important;
		margin-right: -8px !important;
	    display: inline-block !important;
	    margin-top: -8px !important;
	    position: absolute;
	    right: 0;
	}
	.<?php echo esc_html($element_class);?> .close img{width: 24px;}
	.<?php echo esc_html($element_class);?> .close:hover{
		background: transparent !important;	
	}
	
	.<?php echo esc_html($element_class);?> .modal-header{
		padding:0 !important;
		border:none !important;
	}
	.<?php echo esc_html($element_class);?> .modal-title{
		display:inline-block !important;
		margin:5px 0 0 0 !important;
		font-size:20px !important;
		padding:0 15px !important;
		<?php if(!empty($form->popup_text_color)){?>color:<?php echo esc_attr($form->popup_text_color);?> !important; <?php }?>
	}
	.<?php echo esc_html($element_class);?> .dms_popup_description{
		font-size: 15px !important;
    	font-style: italic !important;
    	margin-bottom:5px !important;
	}
	.<?php echo esc_html($element_class);?> .dms_popup_button{
	    border-radius: 5px !important;
	    padding: 7px !important;
	    margin-bottom: 15px !important;
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
	.<?php echo esc_html($element_class);?> .dms_popup_button:hover{
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

</style>

<div class="modal <?php if($form->is_animated==0){ echo 'fadezz';} else { echo 'animated';}?> text-center <?php echo esc_attr($element_class);?>" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: transparent"><span aria-hidden="true"><img src="<?php echo VMS_ASSETS_URL;?>/public/close.png"></span></button>
		<div class="modal-content">
  			<div class="modal-header">
				<h4 class="modal-title"><?php echo esc_html($form->title);?></h4>
			</div>
			<div class="modal-body">
      	
				<div class="dms_popup_description"><?php echo esc_html($form->description);?></div>
				<form action="" method="post" class="dms_mailing_form"  id="dms_popup_form_<?php echo md5($form->id);?>">
    				<div class="dms_response_div"></div>
    				<input type="hidden" name="action" value="dms_submit_and_send">
        			<input type="hidden" name="setting_field" value="">
            		<input type="hidden" name="setting_field_id" value="<?php echo md5($form->id);?>">
		
								<?php if( $fields && count($fields)>0){
									foreach ($fields as $field) {
										echo ($this->generate_field($field));	
									}
								}else{
									?><div class="form-group">
										<input type="email" class="form-control text-center email" name="form_fields[email]" placeholder="Email Address">
									</div><?php 	
								}?>

			        <div class="form-group">
			        	<button class="dms_popup_button col-12 w-100" type="button" data-id="dms_popup_form_<?php echo md5($form->id);?>"><?php echo esc_html($form->button_text);?></button>
			        </div>
			        <div class="clearfix"></div>
    			</form>
  			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$("div.<?php echo esc_attr($element_class);?>").parent().css('transform','initial');
		load_dms_popup('<?php echo esc_html($element_class);?>','<?php echo admin_url('admin-ajax.php');?>',<?php echo esc_html($form->loading_delay);?>,<?php echo esc_html($form->frequency_days);?>,<?php echo esc_html($form->form_display_type);?>,<?php echo esc_html($form->form_display_befor_after_login);?>,<?php echo esc_html($form->frequency_days_on_close);?>,<?php echo esc_html($form->is_animated);?>);
	});
</script>