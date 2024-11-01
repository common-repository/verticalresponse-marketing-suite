<?php

/**
 * The admin-specific functionality of the plugin.
 */
class VerticalResponse_Marketing_Suite_Admin {

    protected $parent_slug = "verticalresponse-marketing-suite";
    protected $contacts_slug = "verticalresponse-marketing-suite-contacts";
    protected $add_form_slug = "verticalresponse-marketing-suite-add";
    protected $validate_slug = "verticalresponse-marketing-suite-connect";
    protected $setting_slug = "verticalresponse-marketing-suite-custom-msgs";

    public function __construct() {
        
    }

    /**
     * Contact syncronize with Verticale Response
     */
    public function dms_conect_syn_with_vr_fn() {
        $this->dms_sync_contacts_on_vr();
    }

    /**
     * Check access token set or not
     */
    private function is_validated() {
        $dms_access_token=get_option('dms_access_token');
        if (!$dms_access_token || empty($dms_access_token)) {
            $url = admin_url('admin.php?page=' . $this->validate_slug);
            ?>
            <script type="text/javascript">
                window.location="<?php echo esc_html($url); ?>";
            </script>
            <?php
            exit;
        }
        return true;
    }

    /**
     * Set menu in admin side
     */
    public function dms_add_menu_on_admin() {


        $admin_hook = add_menu_page(__('VerticalResponse Marketing Suite', DMS_NAME), __(__('VerticalResponse', DMS_NAME)), 'manage_options', $this->parent_slug, array($this, 'load_dms_popup_listing_page'), VMS_ASSETS_URL . 'admin/images/favicon.ico');
        $addform_hook = add_submenu_page($this->parent_slug, __('Add New'), __('Add New'), 'manage_options', $this->add_form_slug, array($this, 'dms_add_new_from_page'));
        $validate_hook = add_submenu_page($this->parent_slug, __('Connect with VerticalResponse'), __('Connect with VerticalResponse'), 'manage_options', $this->validate_slug, array($this, 'dms_connect_with_vr_page'));

        $contacthook = add_submenu_page($this->parent_slug, __('Contacts', DMS_NAME), __('Contacts', DMS_NAME), 'manage_options', $this->contacts_slug, array($this, 'dms_load_contact_page'));

        $setting_hook = add_submenu_page($this->parent_slug, __('Custom Messages', DMS_NAME), __('Custom Messages', DMS_NAME), 'manage_options', $this->setting_slug, array($this, 'dms_load_setting_page'));
        // $setting_hook = add_submenu_page($this->parent_slug, __(phpversion(), DMS_NAME), __(phpversion(), DMS_NAME), 'manage_options', $this->setting_slug, array($this, 'dms_load_setting_page'));

        add_action('admin_print_scripts-' . $validate_hook, array($this, 'dms_load_validate_script'));
        add_action('admin_print_scripts-' . $addform_hook, array($this, 'dms_load_addform_script'));
        add_action('admin_print_scripts-' . $admin_hook, array($this, 'dms_load_script_for_listing'));
        add_action('admin_print_scripts-' . $setting_hook, array($this, 'dms_load_script_for_listing'));
        add_action('admin_print_scripts-' . $contacthook, array($this, 'dms_load_script_for_listing'));
    }

    /**
     * Load setting page in admin
     */
    public function dms_load_setting_page() {
        $this->is_validated();
        include(VMS_DIR . 'partials/admin/settings.php');
    }

    /**
     * Get all pages in database
     */
    private function get_pages() {
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        return $pages;
    }

    /**
     *  Load add/edit form page in admin side
     */
    public function dms_add_new_from_page() {
        global $wpdb;
        $this->is_validated();
        $custom_fields = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "dms_custom_fields ORDER BY id asc");
        $vrApi = new VRes_Api();
        $lists = $vrApi->get_contact_lists();
        $popup_field_ids = array();

        $pages = $this->get_pages();

        if (isset($_GET['action']) && $_GET['action'] == 'editaction' && isset($_GET['id']) && intval($_GET['id'])) {

            $mid = $_GET['id'];

            $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_popups WHERE is_deleted=0 AND id=%d", $mid ));

            if ( $form  ) {
                $form_id = $form->id;
                $popup_fields = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_popup_fields WHERE popup_id=%d", $form_id ));
                if ($popup_fields) {
                    foreach ($popup_fields as $popup_field) {
                        $popup_field_ids[] = $popup_field->custom_field_id;
                    }
                }

                include(VMS_DIR . 'partials/admin/edit_form.php');
            } else {
                $url = admin_url('admin.php?page=' . $this->parent_slug);
                echo '<script type="text/javascript">window.location="' . esc_html($url) . '";</script>';
                exit;
            }
        } else {
            include(VMS_DIR . 'partials/admin/add_form.php');
        }
    }

    /**
     * Script loading on conatact listing page
     */
    public function dms_load_script_for_listing() {
        wp_enqueue_style('bootstrap-css', VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js',  VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('ajaxloaderjs', VMS_ASSETS_URL . 'admin/js/ajaxloader.js', array('jquery'), NULL, true);
    }

    /**
     * Script loading on add/edit form page
     */
    public function dms_load_addform_script() {

        wp_enqueue_style('style-css', VMS_ASSETS_URL . 'admin/css/style.css');
        wp_enqueue_style('bootstrap-css', VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.css');

        wp_enqueue_style('wp-color-picker');

        wp_enqueue_media();

        wp_enqueue_script('imageuploderjs', VMS_ASSETS_URL . 'admin/js/imageuploader.js', array('jquery'), NULL, true);
        wp_localize_script('imageuploderjs', 'bg', array('wpv' => get_bloginfo('version')));

        wp_enqueue_script('bootstrap-js',  VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('jquery.sortable.js', VMS_ASSETS_URL . 'admin/js/jquery.sortable.js', array('jquery'), NULL, true);
        wp_enqueue_script('add_from_js', VMS_ASSETS_URL . 'admin/js/add_form.js', array('jquery.sortable.js', 'wp-color-picker'), NULL, true);
        wp_enqueue_script("previewjs", VMS_ASSETS_URL . 'admin/js/preview.js', array('jquery'), NULL, true);
        wp_localize_script('previewjs', 'pj', array('assetsurl' => VMS_ASSETS_URL));
        wp_enqueue_script('ajaxloaderjs', VMS_ASSETS_URL . 'admin/js/ajaxloader.js', array('jquery'), NULL, true);
    }

    /**
     * Script loading on connect VR page
     */
    public function dms_load_validate_script() {
        wp_enqueue_style('bootstrap-css', VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.css');
        wp_enqueue_script('bootstrap-js',  VMS_ASSETS_URL . 'vendor/bootstrap/bootstrap.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('connect-vrjs', VMS_ASSETS_URL . 'admin/js/connect.js', array('jquery'), NULL, true);
        wp_enqueue_script('ajaxloaderjs', VMS_ASSETS_URL . 'admin/js/ajaxloader.js', array('jquery'), NULL, true);
    }

    /**
     * Load connect VR page in admin side
     */
    public function dms_connect_with_vr_page() {
        $vrApi = new VRes_Api();
        $dms_client_key = get_option('dms_client_key');
        $dms_secret_key = get_option('dms_secret_key');
        $dms_access_token = get_option('dms_access_token');
        include(VMS_DIR . 'partials/admin/connect.php');
    }

    /**
     * Popup Listing Page
     */
    public function load_dms_popup_listing_page() {
        global $wpdb;
        $this->is_validated();
        $popups = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "dms_popups WHERE is_deleted=0 ORDER BY id ASC");
        include(VMS_DIR . 'partials/admin/popups.php');
    }

    /**
     * Load contact lists page in admin side
     */
    public function dms_load_contact_page() {
        global $wpdb;
        $this->is_validated();
        $customPagHTML = "";
        $contacts = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "dms_contacts ORDER BY id ASC LIMIT 50");
        include(VMS_DIR . 'partials/admin/contacts.php');
    }

    /**
     * Fire when admin delete specific contact
     */
    private function dms_delete_contact($id) {
        global $wpdb;
        if (!intval($id)) {
            set_transient('dms_error_message', 'Invalid request or contact id', 60);
        } else {
            $contact = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_contacts WHERE id=%d", $id));
            if ($contact) {
                $vrApi = new VRes_Api();
                $response = $vrApi = $vrApi->delete_contact($contact->vr_contact_id);
                if ($response['status'] == 1) {
                    $wpdb->delete($wpdb->prefix . "dms_contacts", array('id' => $contact->id));
                    $wpdb->delete($wpdb->prefix . "dms_contact_meta", array('id' => $contact->id));
                    set_transient('dms_success_message', $response['message'], 60);
                } else {
                    set_transient('dms_error_message', $response['message'], 60);
                }
            } else {
                set_transient('dms_error_message', 'This contact may be delete or not availabel');
            }
        }
    }

    /**
     * Fire when admin delete form
     */
    private function dms_delete_form($id) {
        global $wpdb;
        if (!intval($id)) {
            set_transient('dms_error_message', 'Invalid request or form id', 60);
        } else {
            $contact = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_popups WHERE id=%d", $id));
            if ($contact) {
                $wpdb->update($wpdb->prefix . "dms_popups", array('is_deleted' => 1), array('id' => $contact->id));
                set_transient('dms_success_message', 'Form deleted successfully', 60);
            } else {
                set_transient('dms_error_message', 'This form may be delete or not availabel');
            }
        }
    }

    /**
     * Pull contact lists in VR
     */
    private function dms_sync_contacts_on_vr() {
        global $wpdb;
        $vrApi = new VRes_Api();
        $contact_lists = $vrApi->get_contact_lists();
        $response = $vrApi->sync_contacts();
        update_option('sync_contacts_cron_start', '0');
    }

    /**
     * Fire when admin save setting
     */
    private function setting_save($data) {
        $dms_success_msg=trim($data['dms_success_msg']);
        $dms_exists_msg=trim($data['dms_exists_msg']);
        $dms_error_msg=trim($data['dms_error_msg']);

        if (!empty($dms_success_msg)) {
            update_option('dms_success_msg', esc_attr($dms_success_msg));
        }

        if (!empty($dms_exists_msg)) {
            update_option('dms_exists_msg', esc_attr($dms_exists_msg));
        }

        if (!empty($dms_error_msg)) {
            update_option('dms_error_msg', esc_attr($dms_error_msg));
        }
    }




    /**
     * Intialize all admin data manipulation action
     */
    public function dms_save_admin_settings() {
        if (isset($_POST['save_setting_btn']) && wp_verify_nonce($_POST['dms_setting_formsave'], 'dms_setting_formsave')) {

            $dms_success_msg=sanitize_text_field(trim($_POST['dms_success_msg']));
            $dms_exists_msg=sanitize_text_field(trim($_POST['dms_exists_msg']));
            $dms_error_msg=sanitize_text_field(trim($_POST['dms_error_msg']));
    
            if (!empty($dms_success_msg)) {
                update_option('dms_success_msg', esc_attr($dms_success_msg));
            }
    
            if (!empty($dms_exists_msg)) {
                update_option('dms_exists_msg', esc_attr($dms_exists_msg));
            }
    
            if (!empty($dms_error_msg)) {
                update_option('dms_error_msg', esc_attr($dms_error_msg));
            }

            if (wp_get_referer()) {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=' . $this->setting_slug));
                exit;
            }
        } else if (isset($_GET['vr_cotact_sync']) && wp_verify_nonce($_GET['vr_cotact_sync'], 'vr_cotact_sync')) {
            $this->dms_conect_syn_with_vr_fn();
            if (wp_get_referer()) {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=' . $this->contacts_slug));
                exit;
            }
        } elseif ((isset($_GET['contactid']) && intval($_GET['contactid'])) && (isset($_GET['contact_delete']) && wp_verify_nonce($_GET['contact_delete'], 'contact_delete_' . $_GET['contactid']))) {
            $this->dms_delete_contact($_GET['contactid']);
            if (wp_get_referer()) {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=' . $this->contacts_slug));
                exit;
            }
        } elseif ((isset($_GET['id']) && intval($_GET['id'])) && (isset($_GET['form_delete']) && wp_verify_nonce($_GET['form_delete'], 'form_delete_' . $_GET['id']))) {
            $this->dms_delete_form($_GET['id']);
            if (wp_get_referer()) {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                exit;
            }
        } else if (isset($_GET['code']) && !empty($_GET['code'])) {
            $vrApi = new VRes_Api();
            $response = $vrApi->generate_access_token($_GET['code']);
            if (is_array($response) && !is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body);
                if (!empty($data)) {
                    update_option('vr_body_response', $body);
                    update_option('dms_access_token', $data->access_token);
                    set_transient('dms_validate_success_message', 'VerticalResponse  connected successfully', 60);
                } else {
                    set_transient('dms_validate_error_message', 'Invlaid Client', 60);
                }
            } else {
                set_transient('dms_validate_error_message', 'Some error on conntecting. Please try again', 60);
            }

            wp_redirect(admin_url('admin.php?page=' . $this->validate_slug));
            exit;
        } elseif (isset($_POST['dms_add_new_formsave']) && wp_verify_nonce($_POST['dms_add_new_formsave'], 'dms_add_new_formsave')) {

            if (isset($_POST['dms_contect_list_id']) && empty($_POST['dms_contect_list_id'])) {
                set_transient('dms_error_message', 'Please select contact list');
            } elseif (isset($_POST['dms_contect_list_id']) && !empty($_POST['dms_contect_list_id']) && $_POST['dms_contect_list_id'] == "add_new") {
                set_transient('dms_error_message', 'Please select contact list');
            } elseif (isset($_POST['form_title']) && empty($_POST['form_title'])) {
                set_transient('dms_error_message', 'Please enter form title');
            } else {
                if ($_POST['form_status'] == 'publish') {
                    $_POST['is_active'] = 1;
                } else {
                    $_POST['is_active'] = 0;
                }

                $form_display_on = array();
                if ( $_POST['form_display_on'] && count($_POST['form_display_on']) > 0) {
                    foreach ($_POST['form_display_on'] as $display_on) {
                        if (!empty($display_on)) {
                            $form_display_on[] = $display_on;
                        }
                    }
                }
                $data = array(
                    'vr_list_id' => sanitize_key($_POST['dms_contect_list_id']),
                    'show_as_popup' => (isset($_POST['dms_show_as_popup']) ? sanitize_key($_POST['dms_show_as_popup']) : 0),
                    'show_as_popup_type' => (isset($_POST['show_as_popup_type']) ? sanitize_key($_POST['show_as_popup_type']) : 0),
                    'show_as_embed' => (isset($_POST['dms_show_as_embed']) ? 1 : 0),
                    'show_in_home' => (isset($_POST['dms_show_popup_in_homepage']) ? 1 : 0),
                    'title' => sanitize_text_field($_POST['form_title']),
                    'description' => sanitize_text_field($_POST['form_description']),
                    'button_text' => sanitize_text_field($_POST['button_text']),
                    'btn_bg_color' => sanitize_hex_color($_POST['button_bg_color']),
                    'btn_text_color' => sanitize_hex_color($_POST['button_text_color']),
                    'popup_bg_color' => sanitize_hex_color($_POST['form_bg_color']),
                    'popup_text_color' => sanitize_hex_color($_POST['form_text_color']),
                    'loading_delay' => sanitize_key($_POST['form_loading_delay']),
                    'frequency_days' => (empty($_POST['form_frequency_days']) ? 365 : sanitize_key($_POST['form_frequency_days'])),
                    'popup_width' => sanitize_key($_POST['form_width']),
                    'display_on' => implode(',', ($form_display_on)),
                    'popup_bg_image' => sanitize_url($_POST['form_bg_image']),
                    'form_display_befor_after_login' => (isset($_POST['form_display_befor_after_login']) ? sanitize_key($_POST['form_display_befor_after_login']) : 0),
                    'form_display_type' => (isset($_POST['form_display_type']) ? sanitize_key($_POST['form_display_type']) : 0),
                    'is_active' => sanitize_key($_POST['is_active']),
                    'frequency_days_on_close' => ($_POST['frequency_days_on_close'] > 0 ? sanitize_key($_POST['frequency_days_on_close']) : 7),
                    'btn_shape' => (isset($_POST['button_shape']) ? sanitize_key($_POST['button_shape']) : 1),
                    'is_animated'=>(isset($_POST['is_animated'])? sanitize_key($_POST['is_animated']):0),
                    'btn_hover_bg_color' => sanitize_hex_color($_POST['button_hover_bg_color']),
                    'btn_hover_text_color' => sanitize_hex_color($_POST['button_hover_text_color']),
                    'popup_height' => sanitize_key($_POST['form_popup_height']),
                );

                global $wpdb;
                $data['created_date'] = date('Y-m-d H:i:s');
                $inserted = $wpdb->insert($wpdb->prefix . "dms_popups", $data);
                if ($inserted) {
                    $last_id = $wpdb->insert_id;
                    foreach ($_POST['sorting_fields'] as $key => $value) {
        
                        $key_val = sanitize_text_field(key($value));
                        $field = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "dms_custom_fields WHERE id=%d",  $key_val ));
        
                        $data = array(
                            'popup_id' => sanitize_key($last_id),
                            'field_order' => sanitize_key($key),
                            'custom_field_id' => sanitize_key(key($value)),
                            'created_date' => date('Y-m-d H:i:s'),
                            'field_label' => sanitize_text_field($value[key($value)]),
                            'field_name' => sanitize_text_field($field->field_name)
                        );
                        $insertd = $wpdb->insert($wpdb->prefix . "dms_popup_fields", $data);
                    }
                }
 
                if ($last_id) {
                    if ($_POST['form_status'] == 'draft') {
                        set_transient('dms_success_message', 'Your form successfully save on draft');
                        wp_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                        exit;
                    } else {
                        wp_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                        exit;
                    }
                }
            }
        } elseif (isset($_POST['dms_edit_form_settings']) && wp_verify_nonce($_POST['dms_edit_form_settings'], 'dms_edit_form_settings')) {
            global $wpdb;
            if (isset($_POST['dms_contect_list_id']) && empty($_POST['dms_contect_list_id'])) {
                set_transient('dms_error_message', 'Please select contact list');
            } elseif (isset($_POST['dms_contect_list_id']) && !empty($_POST['dms_contect_list_id']) && $_POST['dms_contect_list_id'] == "add_new") {
                set_transient('dms_error_message', 'Please select contact list');
            } elseif (isset($_POST['form_title']) && empty($_POST['form_title'])) {
                set_transient('dms_error_message', 'Please enter form title');
            } else {
                if ($_POST['form_status'] == 'publish') {
                    $_POST['is_active'] = 1;
                } else {
                    $_POST['is_active'] = 0;
                }
                
                $form_display_on = array();
                if ( $_POST['form_display_on'] && count($_POST['form_display_on']) > 0) {
                    foreach ($_POST['form_display_on'] as $display_on) {
                        if (!empty($display_on)) {
                            $form_display_on[] = $display_on;
                        }
                    }
                }

                $data = array(
                    'vr_list_id' => sanitize_key($_POST['dms_contect_list_id']),
                    'show_as_popup' => (isset($_POST['dms_show_as_popup']) ? sanitize_key($_POST['dms_show_as_popup']) : 0),
                    'show_as_popup_type' => (isset($_POST['show_as_popup_type']) ? sanitize_key($_POST['show_as_popup_type']) : 0),
                    'show_as_embed' => (isset($_POST['dms_show_as_embed']) ? 1 : 0),
                    'show_in_home' => (isset($_POST['dms_show_popup_in_homepage']) ? 1 : 0),
                    'title' => sanitize_text_field($_POST['form_title']),
                    'description' => sanitize_text_field($_POST['form_description']),
                    'button_text' => sanitize_text_field($_POST['button_text']),
                    'btn_bg_color' => sanitize_hex_color($_POST['button_bg_color']),
                    'btn_text_color' => sanitize_hex_color($_POST['button_text_color']),
                    'popup_bg_color' => sanitize_hex_color($_POST['form_bg_color']),
                    'popup_text_color' => sanitize_hex_color($_POST['form_text_color']),
                    'loading_delay' => sanitize_key($_POST['form_loading_delay']),
                    'frequency_days' => (empty($_POST['form_frequency_days']) ? 365 : sanitize_key($_POST['form_frequency_days'])),
                    'popup_width' => sanitize_key($_POST['form_width']),
                    'display_on' => implode(',', ($form_display_on)),
                    'popup_bg_image' => sanitize_url($_POST['form_bg_image']),
                    'form_display_befor_after_login' => (isset($_POST['form_display_befor_after_login']) ? sanitize_key($_POST['form_display_befor_after_login']) : 0),
                    'form_display_type' => (isset($_POST['form_display_type']) ? sanitize_key($_POST['form_display_type']) : 0),
                    'is_active' => sanitize_key($_POST['is_active']),
                    'frequency_days_on_close' => ($_POST['frequency_days_on_close'] > 0 ? sanitize_key($_POST['frequency_days_on_close']) : 7),
                    'btn_shape' => (isset($_POST['button_shape']) ? sanitize_key($_POST['button_shape']) : 1),
                    'is_animated'=>(isset($_POST['is_animated'])? sanitize_key($_POST['is_animated']):0),
                    'btn_hover_bg_color' => sanitize_hex_color($_POST['button_hover_bg_color']),
                    'btn_hover_text_color' => sanitize_hex_color($_POST['button_hover_text_color']),
                    'popup_height' => sanitize_key($_POST['form_popup_height']),
                );
                
                $edit_form_id = sanitize_key($_POST['edit_form']);

                $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_popups WHERE id=%d", $edit_form_id));
                if ($form) {
                    $data['updated_date'] = date('Y-m-d H:i:s');
                    $updated = $wpdb->update($wpdb->prefix . "dms_popups", $data, array('id' => $edit_form_id));
                    if ( $_POST['sorting_fields'] && count($_POST['sorting_fields']) > 0) {
                        $checkFilds = $wpdb->get_var( $wpdb->prepare("SELECT count(field_id) FROM " . $wpdb->prefix . "dms_popup_fields WHERE popup_id=%d", $edit_form_id ));
                        if ($checkFilds > 0) {
                            $wpdb->delete($wpdb->prefix . "dms_popup_fields", array('popup_id' => $edit_form_id));
                        }
                        foreach ($_POST['sorting_fields'] as $key => $value) {
                            $key_val = sanitize_text_field(key($value));
                            $field = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "dms_custom_fields WHERE id=%d", $key_val ));
                            $data2 = array(
                                'popup_id' => sanitize_key($edit_form_id),
                                'field_order' => sanitize_key($key),
                                'custom_field_id' => sanitize_key(key($value)),
                                'created_date' => date('Y-m-d H:i:s'),
                                'field_label' => sanitize_text_field($value[key($value)]),
                                'field_name' => sanitize_text_field($field->field_name)
                            );
                            $insertd = $wpdb->insert($wpdb->prefix . "dms_popup_fields", $data2);
                        }
                    }
                }

                if ($_POST['form_status'] == 'publish') {
                    set_transient('dms_success_message', "Form updated", 60);
                    wp_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                    exit;
                } else {
                    set_transient('dms_success_message', "Your form save as draft", 60);
                    wp_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                    exit;
                }
            }
        } elseif (isset($_GET['action']) && $_GET['action'] == 'disconnectvr' && wp_verify_nonce($_GET['dis_connect_vr'], 'dis_connect_vr')) {
            delete_option('dms_access_token');
            set_transient('dms_success_message', "VerticalResponse successfully disconnected", 60);
            wp_redirect(admin_url('admin.php?page=' . $this->validate_slug));
            exit;
        } else if ((isset($_GET['id']) && intval($_GET['id'])) && (isset($_GET['form_deactive']) && wp_verify_nonce($_GET['form_deactive'], 'form_deactivate_' . $_GET['id']))) {
            $this->dms_form_deactivated($_GET['id']);
            if (wp_get_referer()) {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                exit;
            }
        } else if ((isset($_GET['id']) && intval($_GET['id'])) && (isset($_GET['form_active']) && wp_verify_nonce($_GET['form_active'], 'form_activate_' . $_GET['id']))) {
            $this->dms_form_activated($_GET['id']);
            if (wp_get_referer()) {
                wp_safe_redirect(wp_get_referer());
                exit;
            } else {
                wp_safe_redirect(admin_url('admin.php?page=' . $this->parent_slug));
                exit;
            }
        }
    }

    private function dms_form_activated($id) {
        global $wpdb;
        if (!intval($id)) {
            set_transient('dms_error_message', 'Invalid request or form id', 60);
        } else {
            $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_popups WHERE id=%d", $id));
            if ($form) {
                $wpdb->update($wpdb->prefix . "dms_popups", array('is_active' => 1), array('id' => $form->id));
                set_transient('dms_success_message', 'Form activated successfully', 60);
            } else {
                set_transient('dms_error_message', 'This form may be delete or not availabel');
            }
        }
    }

    private function dms_form_deactivated($id) {
        global $wpdb;
        if (!intval($id)) {
            set_transient('dms_error_message', 'Invalid request or form id', 60);
        } else {
            $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "dms_popups WHERE id=%d", $id));
            if ($form) {
                $wpdb->update($wpdb->prefix . "dms_popups", array('is_active' => 0), array('id' => $form->id));
                set_transient('dms_success_message', 'Form deactivated successfully', 60);
            } else {
                set_transient('dms_error_message', 'This form may be delete or not availabel');
            }
        }
    }

    private function get_total_signup($form_id) {
        global $wpdb;
        $formid = $form_id;
        $return_val = $wpdb->get_var( $wpdb->prepare("SELECT count(id) FROM " . $wpdb->prefix . "dms_contacts WHERE form_id=%d", $formid ));
        return $return_val;
    }

    public function dms_vr_add_in_list_fn() {
        if (isset($_POST['dms_add_list_in_vr']) && wp_verify_nonce($_POST['dms_add_list_in_vr'], 'dms_add_list_in_vr')) {
            $vrApi = new VRes_Api();
            $response = $vrApi->create_contact_list(array('name' => esc_html($_POST['vr_list_name'])));
        } else {
            $response = array('status' => 0, 'message' => 'Invalid request');
        }
        echo json_encode($response);
        exit;
    }

}
