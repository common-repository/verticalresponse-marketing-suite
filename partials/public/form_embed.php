<?php
$html='<div class="modal text-center '.$element_class.'" tabindex="-1" role="dialog">

  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h4 class="modal-title">'.$form->title.'</h4>
      		</div>
      		<div class="modal-body">
      			<div class="dms_popup_description">'.$form->description.'</div>
        		<form action="" method="post" class="dms_embeded_form" id="dms_embeded_form_'.md5($form->id).'">
        			<div class="dms_response_div"></div>
        			<input type="hidden" name="action" value="dms_submit_and_send">
        			<input type="hidden" name="setting_field" value="">
        			<input type="hidden" name="setting_field_id" value="'.md5($form->id).'">';
        			
	        		if($fields){
	        			foreach ($fields as $field) {
	        				$html.=$this->generate_field($field);	
	        			}
	        		}else{
	        			$html.='<div class="form-group">
	        				<input type="email" class="form-control text-center email" name="form_fields[email]" placeholder="Email Address">
	        			</div>';	
	        		}
	        		$html.='<div class="form-group">
	        			<button class="dms_embeded_button col-12 w-100" type="button" data-id="dms_embeded_form_'.md5($form->id).'">'.$form->button_text.'</button>
	        		</div>
	        		<div class="clearfix"></div>
        		</form>
      		</div>
    	</div>
  	</div>
</div>';

$html.='<style type="text/css">
	.datepicker table{width:auto;}
	.'.$element_class.'{
		display:block !important;
		position:relative !important;
		z-index: 1 !important;
	}

	.'.$element_class.' .dms_response_div {
	    margin: 5px auto !important;
	}
	.'.$element_class.' .alert-danger, .'.$element_class.' .alert-success{padding:5px 0 !important; }

	.'.$element_class.' .modal-dialog{
		width:auto !important;	
		display:block !important;
		position: relative !important;
		margin: 0 auto !important;
	}
	.'.$element_class.' input{
		padding: 0 !important;
	}
	.'.$element_class.' .modal-content{';
		if(!empty($form->popup_bg_image)){
			$html.='background: url('.$form->popup_bg_image.') '.$form->popup_bg_color.'!important; background-size:cover !important; background-repeat: no-repeat !important;';	
		}else{
			$html.='background:'.$form->popup_bg_color.' '.(!empty($form->popup_bg_color)?'!important':'').';';	
		}
		
		$html.='color:'.$form->popup_text_color.' '.(!empty($form->popup_text_color)?'!important':'').';
		border:none !important;
	}

	.'.$element_class.' .modal-body{
		padding-top:0 !important;
		padding-bottom:0 !important;
	}

	.'.$element_class.' .modal-header{
		padding:0 !important;
		border:none !important;
	}
	.'.$element_class.' .modal-title{
		display:block !important;
		margin:5px 15px 0 15px !important;
		font-size:20px !important;
		padding:0 !important;
		color:'.$form->popup_text_color.' '.(!empty($form->popup_text_color) ?'!important':'').';
	}
	.'.$element_class.' .dms_popup_description{
		font-size: 15px !important;
    	font-style: italic !important;
    	margin-bottom:5px !important;
	}
	.'.$element_class.' .dms_embeded_button{
	    border-radius: 5px !important;
	    padding: 7px !important;
	    margin-bottom: 15px !important;

	    background:'.$form->btn_bg_color.' '.(!empty($form->btn_bg_color)?'!important':'').';
		color:'.$form->btn_text_color.' '.(!empty($form->btn_text_color)?'!important':'').';

	    font-size:15px !important;
	    border:none !important;
	    border-radius: '.($form->btn_shape==0?0:5).'px !important;
        
	}
	.'.$element_class.' .dms_embeded_button:hover{
		background:'.$form->btn_bg_color.' '.(!empty($form->btn_bg_color)?'!important':'').';
		color:'.$form->btn_text_color.' '.(!empty($form->btn_text_color)?'!important':'').';
	}
	.'.$element_class.' .dms_embeded_button:hover{';
        if(!empty($form->btn_hover_bg_color)){
            $html.='background:'.$form->btn_hover_bg_color.' !important;';
        }else{
            if(!empty($form->btn_bg_color)){
                $html.='background:'.$form->btn_bg_color.' !important;'; 
            }
        }

        if(!empty($form->btn_hover_text_color)){
            $html.='color:'.$form->btn_hover_text_color.' !important;';
        }else{
            if(!empty($form->btn_text_color)){
                $html.='color:'.$form->btn_text_color.' !important;';
            }  
        }
    $html.='}

</style>';