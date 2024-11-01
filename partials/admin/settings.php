<style type="text/css">
    .ajaxloader{background:rgba(255,255,255,0.5); position: fixed; top: 0; bottom: 0; right: 0; left: 0; z-index: 999999; text-align: center;}
    .ajaxloader img{vertical-align: middle; margin-top: 20%;}
</style>
<div class="ajaxloader">
    <img src="<?php echo VMS_ASSETS_URL;?>admin/images/ajax-loader.gif">
</div>
<div class="wrap">
    <div class="page-header">
      <h1><?php _e('Custom messages',DMS_NAME);?></h1>
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
        <form method="post" action="" class="dms_setting_vr_form">
             <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Custom messages</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo wp_nonce_field('dms_setting_formsave','dms_setting_formsave');?>
                        <div class="form-group">
                            <label for="dms_success_msg"><?php _e('Success message',DMS_NAME);?></label>
                            <input type="text" name="dms_success_msg" class="form-control" id="dms_success_msg" placeholder="" value="<?php echo esc_html(get_option('dms_success_msg'));?>" required="required">
                        </div>
                        <div class="form-group">
                            <label for="dms_exists_msg"><?php _e('Message if contact exists',DMS_NAME);?></label>
                            <input type="text" name="dms_exists_msg" class="form-control" id="dms_exists_msg" placeholder="" value="<?php echo esc_html(get_option('dms_exists_msg'));?>" required="required">
                        </div>
                        <div class="form-group">
                            <label for="dms_error_msg"><?php _e('Error message',DMS_NAME);?></label>
                            <input type="text" name="dms_error_msg" class="form-control" id="dms_error_msg" placeholder="" value="<?php echo esc_html(get_option('dms_error_msg'));?>" required="required">
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary" name="save_setting_btn"><?php _e('Save messages',DMS_NAME);?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>