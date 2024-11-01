<style type="text/css">
    .ajaxloader{background:rgba(255,255,255,0.5); position: fixed; top: 0; bottom: 0; right: 0; left: 0; z-index: 999999; text-align: center;}
    .ajaxloader img{vertical-align: middle; margin-top: 20%;}
</style>
<div class="ajaxloader">
    <img src="<?php echo VMS_ASSETS_URL;?>admin/images/ajax-loader.gif">
</div>
<div class="wrap">
    <?php if ( false !== ( $msg = get_transient( 'dms_success_message' ) ) ) {?>
        <div id="message" class="updated fade">
            <p><?php echo esc_html($msg); delete_transient('dms_success_message');?></p>
        </div>
    <?php }?>
    <?php if ( false !== ( $msg = get_transient( 'dms_error_message' ) ) ) {?>
        <div id="message" class="updated error fade">
            <p><?php echo esc_html($msg); delete_transient('dms_error_message');?></p>
        </div>
    <?php }?>
    <h2><?php _e('Contacts',DMS_NAME);?></h2>
    <div class="alert alert-info"><p>To manage and see more contacts, please sign-in to your VerticalResponse account &nbsp;&nbsp;<a href="https://vr2.verticalresponse.com/users/sign_in" target="_blank" class="btn button-primary">Login</a></p></div>
    <div class="tablenav top">
         <?php if(get_option('sync_contacts_cron_start')=='1'){?>
            <div class="update-nag notice">
                <p><?php _e('Contact sync is in progress... It may take some time. However you can still visit other pages or settings. Or refresh page after sometime',DMS_NAME);?></p>
            </div>
         <?php }else{?>
            <div class="tablenav-button" style="float: right;">
                <a class="button button-primary button-large" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page='.$this->contacts_slug),'vr_cotact_sync','vr_cotact_sync'));?>"><?php _e('Sync Contacts',DMS_NAME);?></a>
            </div>
        <?php }?>

    </div>
    <br clear="clear">
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
    	<thead>
    		<tr>
                <th><?php _e('No.',DMS_NAME);?></th>
    			<th scope="col"><?php _e('Email',DMS_NAME);?></th>
    			<th scope="col"><?php _e('Date',DMS_NAME);?></th>
    			<th scope="col"><?php _e('Actions',DMS_NAME);?></th>
    		</tr>
    	</thead>
    	<tbody >
    		<?php if($contacts){?>
                <?php $i=1;?>
    			<?php foreach ($contacts as $contact) {?>
    				<tr>
                        <td style="width: 10%;"><?php echo esc_html($i);?></td>
    					<td><?php echo esc_html($contact->email);?></td>
    					<td><?php echo esc_html($contact->created_date);?></td>
    					<td><a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page='.$this->contacts_slug.'&contactid='.$contact->id),'contact_delete_'.$contact->id,'contact_delete')); ?>" onclick="return confirm('Are you sure delete this contact?');"><span class="glyphicon glyphicon-trash"></span>Delete</a></td>
    				</tr>
                    <?php $i++;?>
    			<?php }?>
    		<?php }?>
    	</tbody>
    	<tfoot>
    		<tr>
                <th><?php _e('No.',DMS_NAME);?></th>
                <th scope="col"><?php _e('Email',DMS_NAME);?></th>
                <th scope="col"><?php _e('Date',DMS_NAME);?></th>
                <th scope="col"><?php _e('Actions',DMS_NAME);?></th>
    		</tr>
    	</tfoot>
    </table>
    </div>
    <?php echo esc_html($customPagHTML);?>
    <br clear="clear">
    <div class="clearfix"></div>
    <div class="alert alert-info"><p>To manage and see more contacts, please sign-in to your VerticalResponse account &nbsp;&nbsp;<a href="https://vr2.verticalresponse.com/users/sign_in" target="_blank" class="btn button-primary">Login</a></p></div>
</div>