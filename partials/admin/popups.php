<style type="text/css">
    .active_form{color: #00ff00;}
    .deactive_form{color: #ff0000;}
    .ajaxloader{background:rgba(255,255,255,0.5); position: fixed; top: 0; bottom: 0; right: 0; left: 0; z-index: 999999; text-align: center;}
    .ajaxloader img{vertical-align: middle; margin-top: 20%;}
    * { font-size: 14px; }

</style>
<div class="ajaxloader">
    <img src="<?php echo VMS_ASSETS_URL;?>admin/images/ajax-loader.gif">
</div>

<div class="wrap">
   
    <div class="page-header">
      <h1><?php _e('Form List',DMS_NAME);?> <a href="<?php echo admin_url('admin.php?page='.$this->add_form_slug);?>" role="button" class="btn btn-sm btn-primary pull-right"><?php _e('Add new form',DMS_NAME);?></a></h1>
    </div>
    <?php if ( false !== ( $msg = get_transient( 'dms_success_message' ) ) ) {?>
        <div id="message" class="alert alert-success">
            <p><?php echo esc_html($msg); delete_transient('dms_success_message');?></p>
        </div>
    <?php }?>
    <?php if ( false !== ( $msg = get_transient( 'dms_error_message' ) ) ) {?>
        <div id="message" class="alert alert-danger">
            <p><?php echo esc_html($msg); delete_transient('dms_error_message');?></p>
        </div>
    <?php }?>
    <div class="tablenav top">
        
        <div class="update-nag">
            <p><strong>Note:</strong> Copy shortcode incuding square brackets i.e. [vr_subscribe_box id="xxxx"]</p>
        </div>
         
    </div>
    <br clear="clear">
    <br clear="clearfix">
    <br clear="clear">
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
    	<thead>
    		<tr>
                <th><?php _e('No.',DMS_NAME);?></th>
    			<th><?php _e('Title',DMS_NAME);?></th>
    			<th><?php _e('Shortcode',DMS_NAME);?></th>
                <th><?php _e('Total Signups',DMS_NAME);?></th>
                <th><?php _e('Total Views',DMS_NAME);?></th>
    			<th><?php _e('Actions',DMS_NAME);?></th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php if($popups){?>
                <?php $i=1;?>
    			<?php foreach ($popups as $popup) {?>
    				<tr>
                        <td><?php echo esc_html($i);?></td>
    					<td><?php echo esc_html($popup->title);?></td>
                        <td><?php echo '[vr_subscribe_box id="'. esc_html($popup->id) .'"]';?></td>
                        <td><?php echo esc_html($this->get_total_signup( esc_html($popup->id) ));?></td>
                        <td><?php echo esc_html($popup->popup_view_count);?></td>
    					<td>
                            
                            <a href="<?php echo admin_url('admin.php?page='. $this->add_form_slug .'&action=editaction&id='.$popup->id); ?>" title="Edit form"><span class="glyphicon glyphicon-pencil"></span>Edit</a>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <?php if($popup->is_active==1){?>
                                <a class="active_formzz" href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->parent_slug.'&id='.$popup->id),'form_deactivate_'.$popup->id,'form_deactive'); ?>" title="Activated"><span class="glyphicon glyphicon-off"></span>Deactivate</a>
                            <?php }else{?>
                                <a class="deactive_formzz" href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->parent_slug.'&id='.$popup->id),'form_activate_'.$popup->id,'form_active'); ?>" title="Deactivated"><span class="glyphicon glyphicon-off"></span>Activate</a>
                            <?php }?>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page='.$this->parent_slug.'&id='.$popup->id),'form_delete_'.$popup->id,'form_delete'); ?>" onclick="return confirm('Are you sure delete this form?');" title="Delete form"><span class="glyphicon glyphicon-trash"></span>Delete</a>
                        </td>
    				</tr>
                    <?php $i++;?>
    			<?php }?>
    		<?php }?>
    	</tbody>
        <?php if( $popups && count($popups)>15){?>
    	<tfoot>
    		<tr>
                <th><?php _e('No.',DMS_NAME);?></th>
                <th><?php _e('Title',DMS_NAME);?></th>
                <th><?php _e('Shortcode',DMS_NAME);?></th>
                <th><?php _e('Total Signups',DMS_NAME);?></th>
                <th><?php _e('Total Views',DMS_NAME);?></th>
                <th><?php _e('Actions',DMS_NAME);?></th>
    		</tr>
    	</tfoot>
        <?php }?>
    </table>
    </div>
</div>
