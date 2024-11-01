<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */
class VerticalResponse_Marketing_Suite_Activator{

	public static function activate() {
		global $wpdb;
		global $dms_db_version;

		
		if(!get_option('dms_success_msg')){
			update_option('dms_success_msg','Thank you for signing up for our mailing list.');
		}

		if(!get_option('dms_exists_msg')){
			update_option('dms_exists_msg','We already have your email and appreciate your business with us.');
		}
		
		if(!get_option('dms_error_msg')){
			update_option('dms_error_msg','Something went wrong..! Please try after some time.');
		}

		if(!get_option('dms_client_key')){
			update_option('dms_client_key','b9mmpufz82bvtnrmqecprw52');
		}
		if(!get_option('dms_secret_key')){
			update_option('dms_secret_key','MemZa7TM26gRwe5xs7g6pf3r');
		}
		
		require_once(ABSPATH.'wp-admin/includes/upgrade.php' );

		$table_name = $wpdb->prefix."dms_contacts";
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `created_date` datetime NOT NULL,
		  `updated_date` datetime NOT NULL,
		  `vr_contact_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `vr_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `vr_list_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `form_id` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		dbDelta($sql);	

		$contact_meta_table=$wpdb->prefix."dms_contact_meta";
		$sql2="CREATE TABLE IF NOT EXISTS $contact_meta_table (
		  `meta_id` int(11) NOT NULL AUTO_INCREMENT,
		  `contact_id` int(11) NOT NULL,
		  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `meta_value` text COLLATE utf8_unicode_ci DEFAULT NULL,
		  `created_date` datetime NOT NULL,
		  `updated_date` datetime NOT NULL,
		  PRIMARY KEY (`meta_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		dbDelta($sql2);		

		$popup_table=$wpdb->prefix."dms_popups";
		$sql3="CREATE TABLE IF NOT EXISTS $popup_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `show_as_popup` tinyint(4) NOT NULL DEFAULT '0',
		  `show_as_embed` tinyint(4) NOT NULL DEFAULT '0',
		  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
		  `button_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `btn_bg_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `btn_text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `popup_bg_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `popup_bg_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `popup_text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `loading_delay` int(11) NOT NULL,
		  `frequency_days` int(11) NOT NULL,
		  `popup_width` int(11) NOT NULL,
		  `vr_list_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `display_on` text COLLATE utf8_unicode_ci NOT NULL,
		  `show_in_home` tinyint(4) NOT NULL DEFAULT '0',
		  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
		  `form_display_type` tinyint(4) NOT NULL DEFAULT '0',
		  `form_display_befor_after_login` tinyint(4) NOT NULL DEFAULT '0',
		  `show_as_popup_type` tinyint(4) NOT NULL DEFAULT '0',
		  `is_active` tinyint(4) NOT NULL DEFAULT '1',
		  `popup_view_count` int(11) NOT NULL,
		  `frequency_days_on_close` int(11) NOT NULL,
		  `btn_shape` tinyint(4) NOT NULL DEFAULT '1',
		  `created_date` datetime NOT NULL,
		  `updated_date` datetime NOT NULL,
		  `is_animated` tinyint(4) NOT NULL DEFAULT '0',
		  `btn_hover_bg_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `btn_hover_text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			`popup_height` smallint(4) NOT NULL,
		  `popup_options` text COLLATE utf8_unicode_ci DEFAULT NULL,

		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		dbDelta($sql3);	

		$popup_fields_table=$wpdb->prefix."dms_popup_fields";
		$sql4="CREATE TABLE IF NOT EXISTS $popup_fields_table (
		  `field_id` int(11) NOT NULL AUTO_INCREMENT,
		  `popup_id` int(11) NOT NULL,
		  `field_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `field_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `created_date` datetime NOT NULL,
		  `updated_date` datetime NOT NULL,
		  `is_required` tinyint(4) NOT NULL,
			`custom_field_id` int(11) NOT NULL,
			`field_order` int(11) NOT NULL,
		  PRIMARY KEY (`field_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		dbDelta($sql4);

		$custom_field_table=$wpdb->prefix."dms_custom_fields";
		$sql5="CREATE TABLE IF NOT EXISTS $custom_field_table (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `field_label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `field_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `field_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `field_description` text COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		dbDelta($sql5);
		$row_count=$wpdb->get_row( $wpdb->prepare("select * from %s", $custom_field_table) );
		
		if( !$row_count ){
			$sql6="INSERT INTO $custom_field_table (`id`, `field_label`, `field_name`, `field_type`, `field_description`) VALUES
			(1, 'Email', 'email', 'email', 'Email address of the contact'),
			(2, 'First Name', 'first_name', 'text', 'First name of the contact'),
			(3, 'Last Name', 'last_name', 'text', 'Last name of the contact'),
			(4, 'Birth Date', 'birthdate', 'date', 'Birthdate of the contact'),
			(5, 'Gender', 'gender', 'select', 'Gender of the contact'),
			(6, 'Marital Status', 'marital_status', 'select', 'Marital status of the contact'),
			(7, 'Company', 'company', 'text', 'Company the contact represents'),
			(8, 'Title', 'title', 'text', 'Title the contact holds in their company'),
			(9, 'Website', 'website', 'url', 'website address of the contact'),
			(10, 'Street Address', 'street_address', 'editor', 'Street address of contact'),
			(11, 'Extended Address', 'extended_address', 'editor', 'Extended address of contact'),
			(12, 'City', 'city', 'text', 'City of contact''s address'),
			(13, 'Region', 'region', 'text', 'State of contact''s address'),
			(14, 'Postal Code', 'postal_code', 'text', 'Postal code of contact''s address'),
			(15, 'Country', 'country', 'text', 'Country of contact''s address'),
			(16, 'Home Phone', 'home_phone', 'text', 'Home phone of contact'),
			(17, 'Mobile Phone', 'mobile_phone', 'text', 'Mobile phone number of contact'),
			(18, 'Work Phone', 'work_phone', 'text', 'Work phone number of contact'),
			(19, 'Fax', 'fax', 'text', 'Fax number of contact');";
			dbDelta($sql6);
		}



		update_option('dms_db_version',$dms_db_version);
	}

	public static function upgradeDb(){
		global $wpdb;
		global $dms_db_version;

		// update version in /verticalresponse.php to run following code
		
	    if ( get_site_option( 'dms_db_version' ) != $dms_db_version ) {
	        require_once(ABSPATH.'wp-admin/includes/upgrade.php' );
	        $popup_table=$wpdb->prefix."dms_popups";
			$sql="CREATE TABLE $popup_table (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `show_as_popup` tinyint(4) NOT NULL DEFAULT '0',
			  `show_as_embed` tinyint(4) NOT NULL DEFAULT '0',
			  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
			  `button_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `btn_bg_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `btn_text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `popup_bg_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `popup_bg_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `popup_text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `loading_delay` int(11) NOT NULL,
			  `frequency_days` int(11) NOT NULL,
			  `popup_width` int(11) NOT NULL,
			  `vr_list_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `display_on` text COLLATE utf8_unicode_ci NOT NULL,
			  `show_in_home` tinyint(4) NOT NULL DEFAULT '0',
			  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
			  `form_display_type` tinyint(4) NOT NULL DEFAULT '0',
			  `form_display_befor_after_login` tinyint(4) NOT NULL DEFAULT '0',
			  `show_as_popup_type` tinyint(4) NOT NULL DEFAULT '0',
			  `is_active` tinyint(4) NOT NULL DEFAULT '1',
			  `popup_view_count` int(11) NOT NULL,
			  `frequency_days_on_close` int(11) NOT NULL,
			  `btn_shape` tinyint(4) NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL,
			  `updated_date` datetime NOT NULL,
			  `is_animated` tinyint(4) NOT NULL DEFAULT '0',
			  `btn_hover_bg_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
				`btn_hover_text_color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
				`popup_height` smallint(4) NOT NULL,
			  `popup_options` text COLLATE utf8_unicode_ci DEFAULT NULL,


			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
			$d=dbDelta($sql);	

			update_option( "dms_db_version", $dms_db_version );
	    }


/*
		$col = "SELECT 'popup_height' FROM {$wpdb->prefix}dms_popups where popup_height=0 or popup_height!=0" ;
		$coldata = $wpdb->get_results($col, OBJECT);
			
		if (!$coldata){
		    $popup_heightadd="ALTER TABLE {$wpdb->prefix}dms_popups ADD popup_height int(11) NOT NULL";
			$wpdb->query($popup_heightadd);
		}


		$col = "SELECT 'popup_options' FROM {$wpdb->prefix}dms_popups where popup_options='' or popup_options!='' "  ;
		$coldata = $wpdb->get_results($col, OBJECT);
			
		if (!$coldata){
		    $popup_optionsadd="ALTER TABLE {$wpdb->prefix}dms_popups ADD popup_options text COLLATE utf8_unicode_ci DEFAULT NULL";
			$wpdb->query($popup_optionsadd);
		} 

*/

	}
}
