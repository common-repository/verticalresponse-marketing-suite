<?php

/**
 * The Verticale Response API functionality of the plugin.
 */

class VRes_Api{

	protected $client_secret;
	protected $client_id;
	protected $redirect_url;
	protected $api_url="https://vrapi.verticalresponse.com/api/v1/";

	public function __construct(){
		$this->client_id=get_option('dms_client_key');
		$this->client_secret=get_option('dms_secret_key');
		$this->redirect_url=admin_url('admin.php?page=verticalresponse-marketing-suite');
	}

	public function get_api_url(){
		return $this->api_url;
	}
	public function get_redirect_url(){
		return $this->api_url;
	}

	/**
	 * Generating authorization link
	 */
	public function create_authorize_link(){
		$url=$this->api_url."oauth/authorize?client_id=$this->client_id&redirect_uri=$this->redirect_url";
		return $url;
	}

	/**
	 * Generating access token by authorization code
	 */
	public function generate_access_token($code){
		global $wp_version;
		$args = array(
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		); 

		$response=wp_remote_get($this->api_url."oauth/access_token?client_id=$this->client_id&client_secret=$this->client_secret&redirect_uri=$this->redirect_url&code=$code",$args);
		return $response;	
	}

	/**
	 * Delete contact in VR by contactid
	 */
	public function delete_contact($contactid){
		$api_response=array();
		global $wp_version;
		
		
		$args=array(
			'method'=>'DELETE',
			'httpversion'=>'1.1',
			'headers' => array(
				//'Authorization' => 'Bearer ' . get_option('dms_access_token'),
				'Content-Type'=>'application/json'
			),
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		);
		
		$url=$this->api_url."contacts/".$contactid."?access_token=".get_option('dms_access_token');
		$response=wp_remote_get($url,$args);
		if(is_array($response) && !is_wp_error($response)){
			$body = wp_remote_retrieve_body($response);
			$data=json_decode($body,true);
			if(isset($data['success']) && $data['success']['code']==200){
				$api_response=array('status'=>1,'message'=>'Contact deleted');
			}else if(isset($data['error']) && $data['error']['code']==404){
				$api_response=array('status'=>1,'message'=>'Contact deleted');
			}else{
				$api_response=array('status'=>0,'message'=>'Contact not deleted');
			}
		}else{
			$api_response=array('status'=>0,'message'=>get_option('dms_error_msg'));
		}
		return $api_response;
	}

	/**
	 * Check access token is availabel or not
	 */

	public function check_validate(){
		if(get_option('dms_access_token')!=''){
			return true;
		}
		return false;
	}

	/**
	 * Create contact in VR by email
	 */
	public function create_contact($fields,$listid=""){
		
		$api_response=array();
		global $wp_version;
		
		
		$args=array(
			'method'=>'POST',
			'httpversion'=>'1.1',
			'headers' => array(
				//'Authorization' => 'Bearer ' . get_option('dms_access_token'),
				'Content-Type'=>'application/json'
			),
			'body'=>json_encode($fields),
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		);
		if(!empty($listid)){
			$url=$this->api_url."lists/$listid/contacts?access_token=".get_option('dms_access_token');
		}else{
			$url=$this->api_url."contacts?access_token=".get_option('dms_access_token');	
		}
		
		$response=wp_remote_post($url,$args);
		
		if(is_array($response) && !is_wp_error($response)){
			$body = wp_remote_retrieve_body($response);
			$data=json_decode($body,true);
			if(isset($data['success']) && $data['success']['code']==201){
				$url_part=explode('/',$data['url']);
				$contact_id=end($url_part);
				$api_response=array('status'=>1,'message'=>get_option('dms_success_msg'),'cid'=>$contact_id);
			}else if(isset($data['error']) && $data['error']['code']==409){
				$url_part=explode('/',$data['url']);
				$contact_id=end($url_part);
				$api_response=array('status'=>0,'message'=>get_option('dms_exists_msg'),'is_exist'=>1,'cid'=>$contact_id);
			}else{
				$api_response=array('status'=>0,'message'=>get_option('dms_error_msg'));
			}
		}else{
			$api_response=array('status'=>0,'message'=>get_option('dms_error_msg'));
		}

		return $api_response;
	}

	public function get_contact_lists(){
		global $wp_version;
		$api_response=array();
		$args = array(
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		);
		$url=$this->api_url."lists?access_token=".get_option('dms_access_token');
		$response=wp_remote_get($url,$args);
		if(is_array($response) && !is_wp_error($response)){
			$body = wp_remote_retrieve_body($response);
			$data=json_decode($body,true);
			$api_response=array('status'=>1,'lists'=>$data);
		}else{
			$api_response=array('status'=>0,'VR API not conntected.');
		}
		return $api_response;
		
	}

	/**
	 * Sync all contacts in VR
	 */
	public function sync_contacts($listid=""){
		global $wp_version;
		global $wpdb;
		$api_response=array();
		$args = array(
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		);
		if(!empty($listid)){
			$url=$this->api_url."lists/$listid/contacts?access_token=".get_option('dms_access_token').'&type=all&index=1&limit=500';	
		}else{
			$url=$this->api_url."contacts?access_token=".get_option('dms_access_token').'&type=all';	
		}
		
		$response=wp_remote_get($url,$args);
		if(is_array($response) && !is_wp_error($response)){

			$wpdb->query('TRUNCATE TABLE '.$wpdb->prefix.'dms_contacts');

			$body = wp_remote_retrieve_body($response);
			$data=json_decode($body,true);

			//var_dump($data['items']);

			$this->dms_sync_contacts_on_vr($data['items'],$listid);
			/*if(isset($data['links']['next']) && isset($data['links']['next']['url'])){
				$this->sync_with_next($data['links']['next']['url'],$listid);
			}*/
			if(isset($data['items']) && count($data['items'])>0){
				$api_response=array('status'=>1,'message'=>'Contacts successfully synchronized with VerticalResponse');
			}else{
				$api_response=array('status'=>0,'message'=>'Contacts not synchronized with VerticalResponse');	
			}

		}else{
			$api_response=array('status'=>0,'message'=>get_option('dms_error_msg'));
		}
		return $api_response;
	}

	private function dms_sync_contacts_on_vr($contacts,$listid=""){

		global $wpdb;
		foreach ($contacts as $contact){
			// commented code removed
			$attr_email = $contact['attributes']['email'];
			$db_contact=$wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."dms_contacts WHERE email=%s", $attr_email ));

			if($db_contact){
				// commented code removed
			} else {
				$contact_data=array(
					'email'=>$contact['attributes']['email'],
					'vr_contact_id'=>$contact['attributes']['id'],
					'created_date'=>date('Y-m-d H:i:s'),
					'vr_status'=>$contact['attributes']['status'],
					'vr_list_id'=>$listid
				);
				$inserted=$wpdb->insert($wpdb->prefix."dms_contacts",$contact_data);
				if($inserted){
					$id=$wpdb->insert_id;
					unset($contact['attributes']['id']);
					unset($contact['attributes']['source']);
					unset($contact['attributes']['created_at']);
					unset($contact['attributes']['updated_at']);
					unset($contact['attributes']['custom']);
					foreach ($contact['attributes'] as $key => $value) {
						if(!empty($value)){
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
		}
	}

	/* not in use right now */
	private function sync_with_next($url,$listid=""){
		global $wp_version;
		$args = array(
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		);
		$url=add_query_arg('access_token',get_option('dms_access_token'),$url);
		$response=wp_remote_get($url,$args);
		if(is_array($response) && !is_wp_error($response)){
			$body = wp_remote_retrieve_body($response);
			$data=json_decode($body,true);
			
			$this->dms_sync_contacts_on_vr($data['items'],$listid);
			if(isset($data['links']['next']) && isset($data['links']['next']['url'])){
				$this->sync_with_next($data['links']['next']['url'],$listid);
			}
		}
	}


	/* not in use right now */
	public function custom_list(){
		global $wp_version;
		$api_response=array();
		$args = array(
		    'timeout'     => 30,
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
		);
		$url=$this->api_url."contacts?access_token=".get_option('dms_access_token').'&type=all&index=1&limit=500';	
		$response=wp_remote_get($url,$args);
		if(is_array($response) && !is_wp_error($response)){
			$body = wp_remote_retrieve_body($response);
			$data=json_decode($body,true);
			echo '<pre>';
				print_r($data);
			echo '</pre>';
		}		
	}
        
    public function create_contact_list($fields){
        $api_response=array();
        global $wp_version;


        $args=array(
            'method'=>'POST',
            'httpversion'=>'1.1',
            'headers' => array(
							'Content-Type'=>'application/json'
            ),
            'body'=>json_encode($fields),
            'timeout'     => 30,
            'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
        );
        $url=$this->api_url."lists?access_token=".get_option('dms_access_token');	
        

        $response=wp_remote_post($url,$args);

        if(is_array($response) && !is_wp_error($response)){
            $body = wp_remote_retrieve_body($response);
            $data=json_decode($body,true);
            
            if(isset($data['success']) && $data['success']['code']==201){
                $url_part=explode('/',$data['url']);
                $list_id=end($url_part);
                $name=$fields['name'];
                $api_response=array('status'=>1,'message'=>"List created successfully",'list_id'=>$list_id,'name'=>$name);
            }else{
                $api_response=array('status'=>0,'message'=>get_option('dms_error_msg'));
            }
        }else{
            $api_response=array('status'=>0,'message'=>get_option('dms_error_msg'));
        }
        return $api_response;
    }

}


?>