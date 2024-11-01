<style type="text/css">
    .ajaxloader{background:rgba(255,255,255,0.5); position: fixed; top: 0; bottom: 0; right: 0; left: 0; z-index: 999999; text-align: center;}
    .ajaxloader img{vertical-align: middle; margin-top: 20%;}
    .card { padding: 0; }
</style>
<div class="ajaxloader">
    <img src="<?php echo esc_url(VMS_ASSETS_URL);?>admin/images/ajax-loader.gif">
</div>
<div class="wrap">
    <div class="page-header">
      <h1><?php _e('Settings',DMS_NAME);?></h1>
    </div>
    <div class="">
        <?php if ( false !== ( $msg = get_transient( 'dms_validate_success_message' ) ) ) {?>
            <div class="alert alert-success" role="alert">
                <p><?php echo esc_html($msg); delete_transient('dms_validate_success_message');?></p>
            </div>
        <?php }?>
         <?php if ( false !== ( $msg = get_transient( 'dms_validate_error_message' ) ) ) {?>
            <div class="alert alert-danger" role="alert">
                <p><?php echo esc_html($msg); delete_transient('dms_validate_error_message');?></p>
            </div>
        <?php }?>
    
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card card-defaultzzz">
                    <div class="card-header">
                         <div class="card-headerzzz"><?php _e('VerticalResponse',DMS_NAME);?> <span class="badge badge-secondary"><?php if(!empty($dms_access_token)){?>Connected <?php }else{?> Not Connected <?php }?></span></div>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($dms_access_token)){?>
                            <div class="form-group">
                                <label for="dms_client_key"><?php _e('VerticalResponse Client Key',DMS_NAME);?></label>
                                <input type="text" name="dms_client_key" class="form-control" id="dms_client_key" placeholder="" value="<?php echo str_repeat('*', strlen($dms_client_key) - 4) . substr($dms_client_key, -4);?>" readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label for="dms_secret_key"><?php _e('VerticalResponse Secret Key',DMS_NAME);?></label>
                                <input type="text" name="dms_secret_key" class="form-control" id="dms_secret_key" placeholder="" value="<?php echo str_repeat('*', strlen($dms_secret_key) - 4) . substr($dms_secret_key, -4);?>" readonly="readonly">
                            </div>
                        <?php }?>
                        <?php if(!empty($dms_client_key) && !empty($dms_secret_key)){?>
                            <?php if(empty($dms_access_token)){?>
                                <a role="button" class="btn btn-primary connect_with_vr_btn" href="<?php echo $vrApi->create_authorize_link();?>">Connnect With VerticalResponse</a>
                            <?php }else{?> 
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->validate_slug.'&action=disconnectvr'),'dis_connect_vr','dis_connect_vr'); ?>" onclick="return confirm('Are you sure disconnnect?');" title="" role="button" class="btn btn-primary" ><?php _e('Disconnect VerticalResponse');?></a>
                            <?php }?>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">VerticalResponse</h3>
                    </div>
                    <div class="card-body"> Don't have a VerticalResponse account?<br/><br/>
                        <a href="
https://vr2.verticalresponse.com/users/sign_up?buy_now_product_id=VR2_FREEMIUM-300&page=topnav" class="btn btn-primary" target="_blank">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>