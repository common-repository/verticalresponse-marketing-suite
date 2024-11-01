<?php

/**
 * The public-hooks functionality of the plugin.
 */

class VerticalResponse_Marketing_Suite_Public{

	public function __construct(){
		add_filter('widget_text','do_shortcode');
	}

	/**
	 * Generat all custom field
	 */
	private function generate_field($field){
		global $wpdb;

		$input="";
		$custom_field_id = $field->custom_field_id;
		$_field=$wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_custom_fields WHERE id=%d", $custom_field_id ) );

		if($_field){
			if($_field->field_type=='text'){
				$input='<div class="form-group">
	        		<input type="text" class="form-control text-center" id="'.$field->field_name.'" name="form_fields['.$field->field_name.']" placeholder="'.$field->field_label.'">
	        	</div>';
			}elseif($_field->field_type=='url'){
				$input='<div class="form-group">
	        		<input type="url" class="form-control text-center" id="'.$field->field_name.'" name="form_fields['.$field->field_name.']" placeholder="'.$field->field_label.'">
	        	</div>';
			}elseif($_field->field_type=='email'){ 
				$input='<div class="form-group">
	        		<input type="email" class="form-control text-center email" name="form_fields['.$field->field_name.']" placeholder="'.$field->field_label.'">
	        	</div>';
			}elseif($_field->field_type=='date'){ 
				$input='<div class="form-group">
	        		<input type="text" class="form-control text-center custom_datepicker" id="'.$field->field_name.'" name="form_fields['.$field->field_name.']" placeholder="'.$field->field_label.'" data-date-end-date="0d" readonly="readonly">
	        	</div>';
			}elseif($_field->field_type=='select'){
				if($field->field_name=='gender'){
					$input='<div class="form-group">
						<select class="form-control text-center" id="'.$field->field_name.'" name="form_fields['.$field->field_name.']">
							<option value="">Select '.$field->field_label.'</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
						</select>
		        	</div>';	
				}else if($field->field_name=='marital_status'){
					$input='<div class="form-group">
						<select class="form-control text-center" id="'.$field->field_name.'" name="form_fields['.$field->field_name.']">
							<option value="">Select '.$field->field_label.'</option>
							<option value="single">Single</option>
							<option value="married">Married</option>
							<option value="divorcee">Divorcee</option>
							<option value="widow/widower">Widow/Widower</option>
						</select>
		        	</div>';	
				}
			}elseif($_field->field_type=='editor'){ 
				$input='<div class="form-group">
	        		<textarea class="form-control text-center" id="'.$field->field_name.'" name="form_fields['.$field->field_name.']" placeholder="'.$field->field_label.'"></textarea>
	        	</div>';
			}
		}
		return $input;
	}

	/**
	 * Run shortcode [vr_subscribe_box]
	 * Fire when show as Embed in Page/Sidebar in admin setting
	 */

	public function dms_subscribe_box_load($atts){
		global $wpdb,$post;
		$html="";
		$args = shortcode_atts( 
			array(
				'id'   => '-1',
			), 
			$atts
		);

		$id = (int) $args['id'];

		if($id>0){
			$form=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_popups WHERE is_deleted=0 AND is_active=1 AND id=%d",$id));
			if($form){
				if($form->show_as_embed==1){
					$element_class='dms_embeded_div_'.md5($form->id);
					// $fields=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."dms_popup_fields WHERE popup_id=$form->id");
					$form_id = $form->id;
					$fields=$wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_popup_fields WHERE popup_id=%d", $form_id) );
					include(VMS_DIR . 'partials/public/form_embed.php');
				}
			}
			
		}
		
		return $html;

	}

	/**
	 * Load script for form popup/embed
	 */
	public function dms_load_public_script(){
		wp_enqueue_style('dms-css',VMS_ASSETS_URL.'public/dms.css');
		wp_enqueue_style('bootstrap-datepicker', VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap-datepicker.min.css');
		wp_enqueue_style('animated_css', VMS_ASSETS_URL . 'vendor/animate.css');
		wp_enqueue_script('bootstrap-js',  VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.js', array('jquery'), NULL, true);
		wp_enqueue_script('bootstrap-datepicker-js', VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap-datepicker.min.js');
		wp_enqueue_script('jquery.cookie', VMS_ASSETS_URL . 'vendor/jquery/jquery.cookie.js',array('jquery'),NULL,true);
		wp_enqueue_script('dms_global_js',VMS_ASSETS_URL.'public/js/global.js',array('jquery.cookie'),NULL,true);
		wp_enqueue_script('dms_popup_js',VMS_ASSETS_URL.'public/js/form_popup.js',array('jquery.cookie','dms_global_js'),NULL,true);
		wp_enqueue_script('dms_embeded_js',VMS_ASSETS_URL.'public/js/form_embed.js',array('jquery.cookie','dms_global_js'),NULL,true);

		$options=array(
			'ajax_url'=>admin_url('admin-ajax.php')
		);
		wp_localize_script('dms_embeded_js','embedsetting',$options);

		wp_enqueue_script('dms_topbar_js',VMS_ASSETS_URL.'public/js/form_topbar.js',array('jquery.cookie','dms_global_js'),NULL,true);
	}

	/**
	 * Fire when show as popup in admin setting
	 */
	public function dms_load_footer_script(){
		global $wpdb,$post;

		if(is_user_logged_in()){
			$is_logged_in=1;
			$extra_where=' AND (form_display_befor_after_login=2 OR form_display_befor_after_login=1)';
		}else{
			$is_logged_in=0;
			$extra_where=' AND (form_display_befor_after_login=2 OR form_display_befor_after_login=0)';
		}

		if(is_home() || is_front_page()){

			$sql="SELECT * FROM ".$wpdb->prefix."dms_popups WHERE show_as_popup=1 AND is_active=1 AND show_in_home=1 AND is_deleted=0 {$extra_where} ORDER BY id ASC";
			$form=$wpdb->get_row( $wpdb->prepare($sql) );
			if(!$form){
				$sql="SELECT * FROM ".$wpdb->prefix."dms_popups WHERE show_as_popup=1 AND is_active=1 AND FIND_IN_SET($post->ID,display_on) AND is_deleted=0 {$extra_where} ORDER BY id ASC";
				$form=$wpdb->get_row( $wpdb->prepare($sql) );
			}

			if($form){
				$element_class="dms_popupbar_form_".md5($form->id);
				$this->update_form_view_count($form->id,$element_class);
				
				$mform_id = $form->id;
				$fields=$wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_popup_fields WHERE popup_id=%d", $mform_id ) );
					
				if($form->show_as_popup_type==0){
					include(VMS_DIR . 'partials/public/popup_form.php');	
				}elseif($form->show_as_popup_type==1){
					include(VMS_DIR . 'partials/public/form_topbar.php');
				}elseif($form->show_as_popup_type==2){
					include(VMS_DIR . 'partials/public/form_bottombar.php');
				}
				
			}
		}elseif(is_page()){
			$sql="SELECT * FROM ".$wpdb->prefix."dms_popups WHERE show_as_popup=1 AND is_active=1 AND FIND_IN_SET($post->ID,display_on) AND is_deleted=0 {$extra_where} ORDER BY id ASC";
			$form=$wpdb->get_row( $wpdb->prepare($sql) );
			if($form){

				$element_class="dms_popupbar_form_".md5($form->id);
				$this->update_form_view_count($form->id,$element_class);

				// $fields=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."dms_popup_fields WHERE popup_id=$form->id");
				$mform_id =  $form->id;
				$fields=$wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_popup_fields WHERE popup_id=%d", $mform_id ));
					
				if($form->show_as_popup_type==0){
					include(VMS_DIR . 'partials/public/popup_form.php');	
				}elseif($form->show_as_popup_type==1){
					include(VMS_DIR . 'partials/public/form_topbar.php');
				}elseif($form->show_as_popup_type==2){
					include(VMS_DIR . 'partials/public/form_bottombar.php');
				}
			}	
		}
	}

	/**
	 * Check email existis or not in database
	 */
	private function dms_check_email_exisits($email,$listid=""){
		global $wpdb;
		// commented code removed
		// $email_row=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."dms_contacts WHERE email='".$email."'");
		$email_row=$wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_contacts WHERE email=%s", $email ) );
		if($email_row){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Store contact in database
	 */
	private function dms_store_contact($data,$cid,$form_id,$listid=""){
		global $wpdb;
		$contact_data=array(
			'email'=>$data['email'],
			'created_date'=>date('Y-m-d H:i:s'),
			'vr_contact_id'=>$cid,
			'vr_list_id'=>$listid,
			'form_id'=>$form_id
		);
		$inserted=$wpdb->insert($wpdb->prefix."dms_contacts",$contact_data);
		if($inserted){
			$id=$wpdb->insert_id;
			if($data){
				foreach ($data as $key => $value) {
					$meta_data=array(
						'contact_id'=>$id,
						'meta_key'=>$key,
						'meta_value'=>$value,
						'created_date'=>date('Y-m-d H:i:s')
					);
					$wpdb->insert($wpdb->prefix."dms_contact_meta",$meta_data);	
				}				
			}			
		}
	}

	/**
	 * Save popup data when user submit popup
	 */
	public function dms_popup_save_data(){
		global $wpdb;
		if(!empty($_POST['setting_field'])){
			$response=array('status'=>0,'message'=>'Invalid Request');
		}else{
			if(isset($_POST['setting_field_id']) && !empty($_POST['setting_field_id'])){
				$setting_field_id = ($_POST['setting_field_id']);
				$form=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_popups WHERE is_deleted=0 AND MD5(id)=%s",$setting_field_id));
				unset($_POST['form_fields']['region']);
				if($form){
					if(!filter_var($_POST['form_fields']['email'],FILTER_VALIDATE_EMAIL) === false) {
						$vrApi=new VRes_Api();
						$response=$vrApi->create_contact($_POST['form_fields'],$form->vr_list_id);
						if($response['status']==1){
							if($this->dms_check_email_exisits($_POST['form_fields']['email'],$form->vr_list_id)==false){
								$this->dms_store_contact($_POST['form_fields'],$response['cid'],$form->id,$form->vr_list_id);
							}
						}else if(isset($response['is_exist']) && $response['is_exist']==1){
							if($this->dms_check_email_exisits($_POST['form_fields']['email'],$form->vr_list_id)==false){
								$this->dms_store_contact($_POST['form_fields'],$response['cid'],$form->id,$form->vr_list_id);
							}
						}
					}else{
						$response=array('status'=>0,'message'=>'Please enter valide email address');
					}
				}else{
					$response=array('status'=>0,'message'=>'Invalid Request');
				}
			}else{
				$response=array('status'=>0,'message'=>'Invalid Request');
			}
		}
		echo json_encode($response);exit;
	}

	public function dms_load_user_loggedin_action($user_login,$user){
		$this->dms_destroy_temp_cookies();
		setcookie('dms_logged_popup_bar',time(),(time()+86400),'/');
	}
	private function dms_destroy_temp_cookies(){
		if (isset($_COOKIE['dms_logged_popup_bar'])) {
		    unset($_COOKIE['dms_logged_popup_bar']);
		    setcookie('dms_logged_popup_bar', '', time() - 3600,'/');
		}
	}

	public function dms_load_user_logout_action(){
		$this->dms_destroy_temp_cookies();
	}

	private function update_form_view_count($form_id,$cookie_id){
		if(!isset($_COOKIE[$cookie_id])){
			if(!isset($_COOKIE[$cookie_id."_subbmited"])){
				$this->dms_store_form_view_count($form_id);	
			}
		}
	}

	private function dms_store_form_view_count($form_id){
		global $wpdb;
		$form=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."dms_popups WHERE id=".$form_id);
		if($form){
			$update_count=$form->popup_view_count+1;
			$wpdb->update($wpdb->prefix."dms_popups",array('popup_view_count'=>$update_count),array('id'=>$form->id));
		}
	}
	/**
	 * Intialize and check form data for testing
	 */

	public function dms_load_check_popup_data(){
		// 
	}
        
	public function dms_load_ajax_loader_scrpt(){
			?>
			<style type="text/css">
					.ajaxloader{display: none;background:rgba(255,255,255,0.5); position: fixed; top: 0; bottom: 0; right: 0; left: 0; z-index: 99999999; text-align: center;}
					.ajaxloader img{vertical-align: middle; margin-top: 20%;}
			</style>
			<div class="ajaxloader">
					<img src="<?php echo esc_url(VMS_ASSETS_URL);?>admin/images/front-ajax-loader.gif">
			</div>
			<?php
	}
	
}