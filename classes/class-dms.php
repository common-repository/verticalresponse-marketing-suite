<?php

/**
 * The file that defines the core plugin class
 */

class VerticalResponse_Marketing_Suite{
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */	
	protected $loader;

	public function __construct(){

		$this->dms_load_dependencies();
		$this->dms_set_locale();
		if(is_admin()){
			$this->dms_define_admin_hooks();	
		}else{
			$this->dms_define_public_hooks();
		}

		$this->dms_load_ajax_hooks();

	}

	/**
	 *	Load all dependencies for localization, admin and public classes
	 */
	private function dms_load_dependencies(){

		require_once( VMS_CLASSES_DIR . 'class-dms-loader.php' );
		require_once( VMS_CLASSES_DIR . 'class-dms-i18n.php');
		require_once( VMS_CLASSES_DIR . 'class-dms-admin.php');
		require_once( VMS_CLASSES_DIR . 'class-dms-public.php');
		require_once( VMS_CLASSES_DIR . 'class.vr.api.php');
		$this->loader = new VerticalResponse_Marketing_Suite_Loader();
	}

	/**
	 *	Set internationalization for plugin
	 */
	private function dms_set_locale() {

		$plugin_i18n = new VerticalResponse_Marketing_Suite_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'dms_load_plugin_textdomain' );
	}

	/**
	 *	Define admin hooks
	 */
	private function dms_define_admin_hooks() {
		$plugin_admin = new VerticalResponse_Marketing_Suite_Admin();
		$this->loader->add_action('admin_menu',$plugin_admin,'dms_add_menu_on_admin');
		$this->loader->add_action('init',$plugin_admin,'dms_save_admin_settings');
	}

	/**
	 *	Define public hooks
	 */
	private function dms_define_public_hooks(){
		$vrApi=new VRes_Api();
		if($vrApi->check_validate()==true){
			$plugin_public=new VerticalResponse_Marketing_Suite_Public();
			$this->loader->add_action('init',$plugin_public,'dms_load_check_popup_data');
			$this->loader->add_action('wp_enqueue_scripts',$plugin_public,'dms_load_public_script');
			$this->loader->add_action('wp_footer',$plugin_public,'dms_load_footer_script',100);
			$this->loader->add_shortcode('vr_subscribe_box',$plugin_public,'dms_subscribe_box_load');
			//$this->loader->add_action('wp_login',$plugin_public,'dms_load_user_loggedin_action',10,2);
			$this->loader->add_action('wp_logout',$plugin_public,'dms_load_user_logout_action');
      $this->loader->add_action('wp_head',$plugin_public,'dms_load_ajax_loader_scrpt');
		}
	}

	/**
	 *	Load ajax hooks
	 */

	public function dms_load_ajax_hooks(){
		$plugin_public=new VerticalResponse_Marketing_Suite_Public();
		$this->loader->add_action('wp_ajax_dms_submit_and_send',$plugin_public,'dms_popup_save_data');
		$this->loader->add_action('wp_ajax_nopriv_dms_submit_and_send',$plugin_public,'dms_popup_save_data'); 

		$plugin_admin = new VerticalResponse_Marketing_Suite_Admin();  
		$this->loader->add_action('dms_contact_sync_event',$plugin_admin,'dms_conect_syn_with_vr_fn');   
		$this->loader->add_action('wp_ajax_vr_add_in_list',$plugin_admin,'dms_vr_add_in_list_fn');  
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}

?>