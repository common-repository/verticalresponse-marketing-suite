<style type="text/css">
    .card-title {font-size: 16px; color: inherit; }
    .divpreview.thumbnail{width: 50%; position: relative;}
    .delete_image{position: absolute;top: 5px;right: 5px;}
    .form-display-type{display: none;}
    .show_as_popup_div{display: none; padding-left: 20px;}
    .ajaxloader{background:rgba(255,255,255,0.5); position: fixed; top: 0; bottom: 0; right: 0; left: 0; z-index: 999999; text-align: center;}
    .ajaxloader img{vertical-align: middle; margin-top: 20%;}
    #accordion .card-title a:hover{text-decoration: none;}
    #accordion .card-title a:focus{text-decoration: none;outline: none;border: none;box-shadow: none;}
    #accordion  input[type=checkbox]:focus, input[type=radio]:focus {outline: none !important; box-shadow: none !important;}
    .preview_html{border: 1px solid #ccc; height: 300px; overflow-y: scroll; position: relative;padding: 10px;}
    .prview_container{position: relative;overflow: hidden;}
    .preview_html .navbar .container{width: 100% !important;}
    nav.custom-bottom.navbar.navbar-default.preview_topbar{position: absolute;left: 0;right: 0;bottom: 0; margin-bottom: 0 !important;}
    nav.navbar.navbar-default.custom-top.preview_topbar {position: absolute !important;left: 0;right: 0;top: 0;}
    .hide-on-desktop{display: none;}
    .fixtopbar{ position: fixed;left: 69%;right: 0;z-index: 7;top: 7%;}
    select#dms_contect_list_id{height: 34px !important;}
    .create_new_vr_list{cursor: pointer;}
    .vr_contact_list_modal{display: none;}
    @media screen and (max-width: 767px) {
        .hide-on-desktop{display: block;}
        .fixtopbar{ top: 8%;}
    }
    .card {padding: 0; max-width: 100% !important; width: 100%;  }
    
    * {
        font-size: 14px;
    }     
    .dblock {
        display: block !important;
    }
</style>
<div class="ajaxloader">
    <img src="<?php echo VMS_ASSETS_URL;?>admin/images/ajax-loader.gif">
</div>
<div class="wrap">
    <div class="page-header">
      <h1><?php _e('Edit Form',DMS_NAME);?></h1>
    </div>
    <br clear="clear"/>
    <div class="update-nag notice">
         <p><strong>Note: </strong><?php _e('When pop-up set as top bar or bottom bar, only email field will get displayed.',DMS_NAME);?></p>
    </div>
    
    <div class="">
        <?php if ( false !== ( $msg = get_transient( 'dms_success_message' ) ) ) {?>
            <div class="alert alert-success" role="alert">
                <p><?php echo esc_html($msg); delete_transient('dms_success_message');?></p>
            </div>
        <?php }?>
         <?php if ( false !== ( $msg = get_transient( 'dms_error_message' ) ) ) {?>
            <div class="alert alert-danger" role="alert">
                <p><?php echo esc_html($msg); delete_transient('dms_error_message');?></p>
            </div>
        <?php }?>
        <div class="cutom-topbar">
            <div class="row dblock" >
                <div class="col-12">
                    <div class="text-right" style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-primary form_submit_btn" name="save_and_draft_btn" data-status="draft"><?php _e('Save as draft',DMS_NAME);?></button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary form_submit_btn" name="save_and_publish_btn" data-status="publish"><?php _e('Save and publish',DMS_NAME);?></button>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
            <div class="row dblockzzz" style="padding: 20px;">
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12">
                        <form method="post" action="" class="dms_connnect_vr_form">
                            <input type="hidden" name="form_status" value="" id="form_status">
                            <input id="submit_handle" type="submit" style="display: none">
                            <input type="hidden" name="edit_form" value="<?php echo esc_html($form->id);?>">
                            <?php wp_nonce_field('dms_edit_form_settings','dms_edit_form_settings');?>
                    
                            <div class="card card-defaultzzz card-groupzzz" id="accordion">
                                <div class="card-header">
                                    <h3 class="card-title"><?php _e('Form settings',DMS_NAME);?></h3>
                                </div>
                                <div class="card-body">
                                    <div class="card card-defaultzzz">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#display_setting_item"><span class="glyphicon glyphicon-cog"></span>&nbsp;<?php _e('General pop-up or embedded form settings');?></a>
                                            </h4>
                                        </div>
                                        <div id="display_setting_item" class="card-collapse collapsezzz in">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="dms_contect_list_id"><?php _e("Contact list",DMS_NAME);?></label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="dms_contect_list_id" id="dms_contect_list_id" required="required">
                                                            <?php if(isset($lists['lists']['items']) && count($lists['lists']['items'])){?>
                                                                <?php foreach($lists['lists']['items'] as $item){?>
                                                                    <?php if(strtolower($item['attributes']['name'])=='all contacts'){?>
                                                                        <option value="<?php echo esc_html($item['attributes']['id']);?>" <?php selected($form->vr_list_id,$item['attributes']['id'],true);?>><?php echo esc_html($item['attributes']['name']);?></option>
                                                                    <?php }?>
                                                                <?php }?>
                                                                <?php foreach($lists['lists']['items'] as $item){?>
                                                                    <?php if(strtolower($item['attributes']['name'])!='all contacts'){?>
                                                                        <option value="<?php echo esc_html($item['attributes']['id']);?>" <?php selected($form->vr_list_id,$item['attributes']['id'],true);?>><?php echo esc_html($item['attributes']['name']);?></option>
                                                                    <?php }?>
                                                                <?php }?>
                                                            <?php }?>
                                                            <option value="add_new"><?php _e("----- Add new list -----",DMS_NAME);?></option>
                                                        </select>
                                                        <div title="Add new list" class="input-group-addon create_new_vr_list"><span class="glyphicon glyphicon-plus"></span></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="form_title"><?php _e('Title',DMS_NAME);?></label>
                                                    <input type="text" name="form_title" class="form-control" id="form_title" placeholder="Your title here" value="<?php echo esc_attr($form->title); ?>" required="required">
                                                </div>
                                                <div class="form-group">
                                                    <label for="form_description"><?php _e('Description',DMS_NAME);?></label>
                                                    <textarea name="form_description" id="form_description" placeholder="Your pop-up or embedded form description will go here" class="form-control"><?php echo esc_html($form->description);?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dms_show_as_embed"><input type="checkbox" value="1" name="dms_show_as_embed" id="dms_show_as_embed" <?php checked($form->show_as_embed,1,true);?>>&nbsp;<?php _e('Show as embed in page/sidebar',DMS_NAME);?>
                                                        <br/><small style="margin-left: 20px; font-weight: normal;"><i>shortcode to be appended must include  "[ ]"</i></small>
                                                    </label>
                                                    
                                                </div>
                                                <div class="form-group">
                                                    <label for="dms_show_as_popup"><input type="checkbox" value="1" name="dms_show_as_popup" id="dms_show_as_popup" <?php checked($form->show_as_popup,1,true);?>>&nbsp;<?php _e('Show as pop-up',DMS_NAME);?></label>
                                                    <br>
                                                    <small></small>
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="show_popup_as_normal"><input type="radio" name="show_as_popup_type" id="show_popup_as_normal" value="0" <?php checked($form->show_as_popup_type,0,true);?>> <?php _e('Normal',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="show_popup_as_topbar"><input type="radio" name="show_as_popup_type" id="show_popup_as_topbar" value="1" <?php checked($form->show_as_popup_type,1,true);?>> <?php _e('Top bar',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="show_popup_as_bottombar"><input type="radio" name="show_as_popup_type" id="show_popup_as_bottombar" value="2" <?php checked($form->show_as_popup_type,2,true);?>> <?php _e('Bottom bar',DMS_NAME);?></label>
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="dms_show_popup_in_homepage"><input type="checkbox" value="1" name="dms_show_popup_in_homepage" id="dms_show_popup_in_homepage" <?php checked($form->show_in_home,1,true);?>>&nbsp;<?php _e('Show pop-up/bar on homepage',DMS_NAME);?></label>
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="form_display_on"><?php _e('Display on (multiple select) for pop-up only',DMS_NAME);?></label><br/>
                                                    <label for="select_all_page"><input type="checkbox" name="select_all_page" id="select_all_page">&nbsp;<?php _e('Select all',DMS_NAME);?></label>
                                                    <select name="form_display_on[]" id="form_display_on" multiple="true" class="form-control">
                                                        <option value="" <?php if(empty($form->display_on)){ echo 'selected="selected"';}?>><?php _e('None',DMS_NAME);?></option>
                                                        <?php if($pages){
                                                            $display_on_pages=explode(',',$form->display_on);
                                                            foreach ($pages as $page) {?>
                                                                <option value="<?php echo esc_html($page->ID);?>" <?php if(in_array($page->ID,$display_on_pages)){ echo 'selected="selected"';}?>><?php echo esc_html($page->post_title);?></option>    
                                                            <?php }
                                                        }?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group show_as_popup_div">
                                                    <label><?php _e('Pop-up show',DMS_NAME);?></label>
                                                    <br/>
                                                    <label for="form_show_befor_login"><input type="radio" name="form_display_befor_after_login" id="form_show_befor_login" value="0" <?php checked($form->form_display_befor_after_login,0,true);?>> <?php _e('Before login',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="form_show_after_login"><input type="radio" name="form_display_befor_after_login" id="form_show_after_login" value="1" <?php checked($form->form_display_befor_after_login,1,true);?>> <?php _e('After login',DMS_NAME);?></label>
                                                    <label for="form_show_both"><input type="radio" name="form_display_befor_after_login" id="form_show_both" value="2" <?php checked($form->form_display_befor_after_login,2,true);?>> <?php _e('Both',DMS_NAME);?></label>
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label><?php _e('Display when',DMS_NAME);?></label>
                                                    <br/>
                                                    <label for="display_when_simple"><input type="radio" name="form_display_type" id="display_when_simple" value="0" <?php checked($form->form_display_type,0,true);?>> <?php _e('Simple',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="display_when_scroll"><input type="radio" name="form_display_type" id="display_when_scroll" value="1" <?php checked($form->form_display_type,1,true);?>> <?php _e('Scroll',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="display_when_exit"><input type="radio" name="form_display_type" id="display_when_exit" value="2" <?php checked($form->form_display_type,2,true);?>> <?php _e('Exit intent',DMS_NAME);?></label>
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label><?php _e('Pop-up slide',DMS_NAME);?></label>
                                                    <br/>
                                                    <label for="popup_slide_none"><input type="radio" name="is_animated" id="popup_slide_none" value="0" <?php checked($form->is_animated,0,true);?>> <?php _e('None',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="popup_slide_left"><input type="radio" name="is_animated" id="popup_slide_left" value="1" <?php checked($form->is_animated,1,true);?>> <?php _e('Left',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="display_slide_right"><input type="radio" name="is_animated" id="display_slide_right" value="2" <?php checked($form->is_animated,2,true);?>> <?php _e('Right',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="display_slide_up"><input type="radio" name="is_animated" id="display_slide_up" value="3" <?php checked($form->is_animated,3,true);?>> <?php _e('Up',DMS_NAME);?></label>&nbsp;&nbsp;
                                                    <label for="display_slide_down"><input type="radio" name="is_animated" id="display_slide_down" value="4" <?php checked($form->is_animated,4,true);?>> <?php _e('Down',DMS_NAME);?></label>
                                                </div>
                                                
                                                <div class="form-group show_as_popup_div">
                                                    <label for="form_loading_delay"><?php _e('Pop-up loading delay (seconds)',DMS_NAME);?></label>
                                                    <input type="number" name="form_loading_delay" class="form-control" id="form_loading_delay" placeholder="" value="<?php echo esc_attr($form->loading_delay); ?>" min="0">
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="form_frequency_days"><?php _e('Frequency on submit event ( days)',DMS_NAME);?></label>
                                                    <input type="number" name="form_frequency_days" class="form-control" id="form_frequency_days" placeholder="" value="<?php echo esc_attr($form->frequency_days); ?>" min="7">
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="frequency_days_on_close"><?php _e('Frequency on close event ( days)',DMS_NAME);?></label>
                                                    <input type="number" name="frequency_days_on_close" class="form-control" id="frequency_days_on_close" placeholder="" value="<?php echo esc_attr($form->frequency_days_on_close); ?>" min="1">
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="form_width"><?php _e('Pop-up width (only for pop-up)',DMS_NAME);?></label>
                                                    <input type="number" name="form_width" class="form-control" id="form_width" placeholder="" value="<?php echo esc_attr($form->popup_width); ?>" min="300">
                                                </div>
                                                <div class="form-group show_as_popup_div">
                                                    <label for="form_popup_height"><?php _e('Pop-up height (only for pop-up. Set zero for auto height)',DMS_NAME);?></label>
                                                    <input type="number" name="form_popup_height" class="form-control" id="form_popup_height" placeholder="" value="<?php echo esc_attr($form->popup_height); ?>" min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-defaultzzz">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#general_setting_item"><span class="glyphicon glyphicon-cog"></span>&nbsp;<?php _e('Pop-up or form appearance with look and feel');?></a>
                                            </h4>
                                        </div>
                                        <div id="general_setting_item" class="card-collapse collapse">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <label for="form_bg_color" class="control-label" style="display: block;"><?php _e('Form background color',DMS_NAME);?></label>
                                                            <input type="text" name="form_bg_color" class="cpa-color-picker" id="form_bg_color" placeholder="" value="<?php echo esc_attr($form->popup_bg_color); ?>">
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <label for="form_text_color" class="control-label" style="display: block;"><?php _e('Form text color',DMS_NAME);?></label>
                                                            <input type="text" name="form_text_color" class="cpa-color-picker" id="form_text_color" placeholder="" value="<?php echo esc_attr($form->popup_text_color); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="form_bg_image"><?php _e('Form background image',DMS_NAME);?></label>
                                                    <input type="url" name="form_bg_image" id="form_bg_image" class="form-control formbgimageurl" style="width: 80%; display: inline-block;" value="<?php echo esc_url($form->popup_bg_image);?>">
                                                    <input type="button" name="uplodimage_btn" value="<?php _e('Upload',DMS_NAME);?>" class="uploadimage_btn btn btn-primary"  style="display: inline-block;">
                                                    <?php if(!empty($form->popup_bg_image)){?>
                                                        <div class="divpreview thumbnail">
                                                        <button type="button" title="Delete image" class="btn btn-danger btn-sm delete_image"><span class="glyphicon glyphicon-trash"></span></button>
                                                        <img src="<?php echo esc_url($form->popup_bg_image);?>" class="img-responsive"></div>
                                                    <?php }?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="button_text"><?php _e('Button text',DMS_NAME);?></label>
                                                    <input type="text" name="button_text" class="form-control" id="button_text" placeholder="" value="<?php echo esc_attr($form->button_text); ?>" required="required">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <label for="button_bg_color" class="control-label" style="display: block;"><?php _e('Button background color',DMS_NAME);?></label>
                                                            <input type="text" name="button_bg_color" class="cpa-color-picker" id="button_bg_color" placeholder="" value="<?php echo esc_attr($form->btn_bg_color); ?>">
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <label for="button_text_color" class="control-label" style="display: block;"><?php _e('Button text color',DMS_NAME);?></label>
                                                            <input type="text" name="button_text_color" class="cpa-color-picker" id="button_text_color" placeholder="" value="<?php echo esc_attr($form->btn_text_color); ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <label for="button_hover_bg_color" class="control-label" style="display: block;"><?php _e('Button hover BG color',DMS_NAME);?></label>
                                                            <input type="text" name="button_hover_bg_color" class="cpa-color-picker" id="button_hover_bg_color" placeholder="" value="<?php echo esc_attr($form->btn_hover_bg_color);?>">
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <label for="button_hover_text_color" class="control-label" style="display: block;"><?php _e('Button hover text color',DMS_NAME);?></label>
                                                            <input type="text" name="button_hover_text_color" class="cpa-color-picker" id="button_hover_text_color" placeholder="" value="<?php echo esc_attr($form->btn_hover_text_color);?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php _e('Button shape',DMS_NAME);?></label>
                                                    <div class="row">
                                                        <div class="col-12 ">
                                                            <label for="button_shape_sharp_edge"><input type="radio" name="button_shape" id="button_shape_sharp_edge" value="0" <?php checked($form->btn_shape,0,true);?>>&nbsp;<?php _e('Rectangle with sharp edges',DMS_NAME);?></label>
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="button_shape_smooth_edge"><input type="radio" name="button_shape" id="button_shape_smooth_edge" value="1" <?php checked($form->btn_shape,1,true);?>>&nbsp;<?php _e('Rectangle with smooth edges',DMS_NAME);?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-defaultzzz">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#field_setting_item"><span class="glyphicon glyphicon-cog"></span>&nbsp;<?php _e('Normal and custom fields');?></a>
                                            </h4>
                                        </div>
                                        <div id="field_setting_item" class="card-collapse collapse">
                                            <div class="card-body">
                                                <ul class="list-group list-group-sortable-connected" id="sortable1">
                                                    <?php if( $popup_fields && count($popup_fields)>0){?>
                                                        <?php foreach($popup_fields as $popup_field){?>
                                                            <?php if($popup_field->field_name=='email'){?>
                                                            <li class="list-group-item disabled"><span class="glyphicon glyphicon-move"></span>
                                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo esc_html($popup_field->custom_field_id);?>" aria-expanded="true" aria-controls="collapse_<?php echo esc_html($popup_field->custom_field_id);?>">
                                                                    <?php echo esc_html($popup_field->field_label);?>
                                                                </a>
                                                                <div id="collapse_<?php echo esc_html($popup_field->custom_field_id);?>" class="card-collapse collapsezzz in">
                                                                    <div class="form-group">
                                                                        <label for="<?php echo esc_html($popup_field->field_name);?>"><?php _e('Label name',DMS_NAME);?></label>
                                                                        <input type="text" name="sorting_fields[][<?php echo esc_html($popup_field->custom_field_id);?>]" class="form-control" id="<?php echo esc_html($popup_field->field_name);?>" placeholder="" value="<?php echo esc_html($popup_field->field_label);?>">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php }else{?>
                                                                <li class="list-group-item" id="custom_field_id_<?php echo esc_html($popup_field->field_id);?>"><span class="glyphicon glyphicon-move"></span>
                                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo esc_html($popup_field->custom_field_id);?>" aria-expanded="true" aria-controls="collapse_<?php echo esc_html($popup_field->custom_field_id);?>">
                                                                    <?php echo esc_html($popup_field->field_label);?>
                                                                </a>
                                                                <div id="collapse_<?php echo esc_html($popup_field->custom_field_id);?>" class="card-collapse collapse in">
                                                                    <div class="pull-right">
                                                                        <a href="javascript:void(0);" data-id="custom_field_id_<?php echo esc_html($popup_field->field_id);?>" class="delete_c_field">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </a>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="<?php echo esc_html($popup_field->field_name);?>"><?php _e('Label name',DMS_NAME);?></label>
                                                                        <input type="text" name="sorting_fields[][<?php echo esc_html($popup_field->custom_field_id);?>]" class="form-control" id="<?php echo esc_html($popup_field->field_name);?>" placeholder="" value="<?php echo esc_html($popup_field->field_label);?>">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php }?>
                                                        <?php }?>
                                                    <?php }elseif($custom_fields){?>
                                                        <?php foreach ($custom_fields as $custom_field) {?>
                                                            <?php if($custom_field->field_type=='email'){?>
                                                            <li class="list-group-item disabled"><span class="glyphicon glyphicon-move"></span>
                                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo esc_html($custom_field->id);?>" aria-expanded="true" aria-controls="collapse_<?php echo esc_html($custom_field->id);?>">
                                                                    <?php echo esc_html($custom_field->field_label);?>
                                                                    </a>
                                                                    <div id="collapse_<?php echo esc_html($custom_field->id);?>" class="card-collapse collapse in">
                                                                        <div class="form-group">
                                                                            <label for="<?php echo esc_html($custom_field->field_name);?>"><?php _e('Label name',DMS_NAME);?></label>
                                                                            <input type="text" name="sorting_fields[][<?php echo esc_html($custom_field->id);?>]" class="form-control" id="<?php echo esc_html($custom_field->field_name);?>" placeholder="" value="<?php echo esc_html($custom_field->field_label);?>">
                                                                            <span class="help-desk">
                                                                            <?php echo esc_html($custom_field->field_description);?>
                                                                        </span>
                                                                        </div>
                                                                    </div>
                                                            </li>
                                                            <?php }?>
                                                        <?php }?>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                    </div>                
                    <div class="row">
                    <div class="col-12">
                        <div class="card card-defaultzzz">
                        <div class="card-header">
                            <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                            <h3 class="card-title">Custom fields</h3>

                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-sortable-connected" id="sortable2">
                                <?php if($custom_fields && count($custom_fields)>0){?>
                                    <?php foreach ($custom_fields as $custom_field) {?>
                                        <?php if(!in_array($custom_field->id, $popup_field_ids)){?>
                                        <?php if($custom_field->field_type!='email'){?>
                                            <li class="list-group-item"><span class="glyphicon glyphicon-move"></span> 
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo esc_html($custom_field->id);?>" aria-expanded="true" aria-controls="collapse_<?php echo esc_html($custom_field->id);?>">
                                                <?php echo esc_html($custom_field->field_label);?>
                                                </a>
                                                <div id="collapse_<?php echo esc_html($custom_field->id);?>" class="card-collapse collapse">
                                                    <div class="form-group">
                                                        <label for="<?php echo esc_html($custom_field->field_name);?>"><?php _e('Label name',DMS_NAME);?></label>
                                                        <input type="text" name="sorting_fields[][<?php echo esc_html($custom_field->id);?>]" class="form-control" id="<?php echo esc_html($custom_field->field_name);?>" placeholder="" value="<?php echo esc_html($custom_field->field_label);?>">
                                                        <span class="help-desk">
                                                            <?php echo esc_html($custom_field->field_description);?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php }?>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 prview_containerzzz">
                <div class="p_content">
                    <div class="card card-defaultzzz">
                        <div class="card-header">
                            <br>
                            <h3 class="card-title"><?php _e('Preview',DMS_NAME);?></h3>
                        </div>
                        <div class="card-body">
                            <div class="preview_html"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade vr_contact_list_modal" id="vr_contact_list_modal" tabindex="-1" role="dialog" aria-labelledby="vrcontactlistLabel">
    <form method="post" action="" class="contact_add_vr_form">
        <?php wp_nonce_field('dms_add_list_in_vr','dms_add_list_in_vr');?>
        <input type="hidden" name="action" value="vr_add_in_list">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="vrcontactlistLabel">Add new list in VerticalResponse</h4>
                </div>
                <div class="modal-body">
                    <div class="app_response"></div>
                    <div class="form-group">
                        <label for="list_name_id" class="control-label">List name</label>
                        <input type="text" name="vr_list_name" class="form-control" id="list_name_id">
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_btn_vr_list">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
