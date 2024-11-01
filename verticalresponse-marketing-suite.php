<?php
/*
Plugin Name: VerticalResponse Marketing Suite
Plugin URI: http://www.verticalresponse.com/
Description: Generate more leads with VerticalResponse Marketing Suite's Contact Form
Version: 1.1.6
Author: VerticalResponse
Author URI: https://www.verticalresponse.com
License: GPLv2 or later
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
global $dms_db_version;
$dms_db_version = '1.0.0';


define('VMS_URL',plugin_dir_url(plugin_basename(__FILE__)));
define('VMS_DIR', plugin_dir_path(__FILE__));
define('VMS_CLASSES_DIR', plugin_dir_path(__FILE__).'/classes/');
define('VMS_ASSETS_URL', plugin_dir_url(plugin_basename(__FILE__)).'/assets/');
define('DMS_NAME','verticalresponsems');


/**
 * The code that runs during plugin activation.
 */
function activate_verticalresponse_ms(){
	require_once VMS_CLASSES_DIR . 'class-dms-activator.php';
	VerticalResponse_Marketing_Suite_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_verticalresponse_ms' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_verticalresponse_ms(){
	require_once VMS_CLASSES_DIR . 'class-dms-deactivator.php';
	(new VerticalResponse_Marketing_Suite_Deactivator)->deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_verticalresponse_ms' );

/**
 * The code that runs during plugin uninstallation.
 */
function uninstall_verticalresponse_ms(){
	global $wpdb;

	
	delete_option('dms_client_key');
	delete_option('dms_secret_key');
	delete_option('dms_access_token');

	require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

	$table_name = $wpdb->prefix."dms_contacts";
	$sql = "DROP TABLE ".$table_name;
	$wpdb->query( $wpdb->prepare($sql) );

	$contact_meta_table=$wpdb->prefix."dms_contact_meta";
	$sql2 = "DROP TABLE ".$contact_meta_table;
	$wpdb->query( $wpdb->prepare($sql2) );
	
	$popup_table=$wpdb->prefix."dms_popups";
	$sql3 = "DROP TABLE ".$popup_table;
	$wpdb->query( $wpdb->prepare($sql3) );

	$popup_fields_table=$wpdb->prefix."dms_popup_fields";
	$sql4 = "DROP TABLE ".$popup_fields_table;
	$wpdb->query( $wpdb->prepare($sql4) );

	$custom_field_table=$wpdb->prefix."dms_custom_fields";
	$sql5 = "DROP TABLE ".$custom_field_table;
	$wpdb->query( $wpdb->prepare($sql5) );

}
register_uninstall_hook(__FILE__,'uninstall_verticalresponse_ms');

/**
 * Upgrade plugin code
 */

function vmsplugin_update_db_check(){
	require_once VMS_CLASSES_DIR . 'class-dms-activator.php';
	VerticalResponse_Marketing_Suite_Activator::upgradeDb();
}
add_action( 'plugins_loaded', 'vmsplugin_update_db_check' );

/**
 * The core plugin class that is used to define 
 * admin-specific hooks, and public-specific site hooks.
 */
require_once VMS_CLASSES_DIR . 'class-dms.php';

function run_VerticalResponse_Marketing_Suite() {

	$plugin = new VerticalResponse_Marketing_Suite();
	$plugin->run();

}
run_VerticalResponse_Marketing_Suite();
