<?php
class FacebookPage extends Page {
	
	public static $db = array(
		'FacebookTitle' => 'Varchar(255)',
		'FacebookAppId' => 'Varchar(50)',
		'FacebookAppSecret' => 'Varchar(128)',
		'FacebookCanvasURL' => 'Varchar(512)',
		'FacebookPageTabURL' => 'Varchar(512)',
		'FacebookPermissions' => 'Varchar(255)',
		'FacebookNameSpace' => 'Varchar(255)',
		'OGTitle' => 'Varchar(1024)',
		'OGDescription' => 'Varchar(1024)',
		'OGImageUrl' => 'Varchar(512)'
	);
	
	public static $has_one = array(
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab("Root.Content", "Content");
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookTitle", "Title"));
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookAppId", "App Id"));
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookAppSecret", "App Secret"));
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookCanvasURL", "Canvas URL"));
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookPageTabURL", "Page Tab URL"));
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookPermissions", "Permissions e.g. email,user_photos"));
		$fields->addFieldToTab('Root.FacebookApp', new TextField("FacebookNameSpace", "Facebook Name Space"));
		$fields->addFieldToTab('Root.FacebookOG', new TextField("OGTitle", "OG Title"));
		$fields->addFieldToTab('Root.FacebookOG', new TextField("OGDescription", "OG Description"));
		$fields->addFieldToTab('Root.FacebookOG', new TextField("OGImageUrl", "OG Image URL e.g. https://domain.com/image.png"));
		return $fields;
	}
	
}
class FacebookPage_Controller extends Page_Controller {
	/*
	 * $session_variables - massive hack to fix session problems in iframe safari
	 */
	public $session_variables = array();
	
	/*
	 * facebook instance - to be used with opengraph
	 */
	protected $fb_instance = null;
	
	/*
	 * this variable should contain the redirect url 
	 * before calling the redirect template
	 */
	protected $redirect_url = '';
	
	/*
	 * facebook user id
	 */
	protected $fb_user_id = 0;
	protected $fb_user_permissions = null;
	protected $fb_user_details = null;
	
	/*
	 * no error by default
	 */
	protected $facebook_error = false;
	
	/*
	 * initiate fb session
	 */
	public function init() {
		parent::init();
		
		require '../facebook-php-sdk-master/src/facebook.php';
		
		try {
			$this->fb_instance = new Facebook(array(
				'appId'  => $this->FacebookAppId,
				'secret' => $this->FacebookAppSecret
			));
		} catch(FacebookApiException $e) {
			$this->facebook_error = true;
			return false;
		}
		
		$this->redirect_url = $this->FacebookPageTabURL;
		if (isset($_REQUEST['app_data'])) {
			$this->redirect_url = $this->redirect_url.'&app_data='.$_REQUEST['app_data'];
		}
		
		$ajax_processed = false;
		if ($this->isAjax() && isset($_REQUEST['signed_request'])) {
			$this->fb_user_signed_request = $this->parse_signed_request($_REQUEST['signed_request'], $this->FacebookAppSecret);
			if ($this->fb_user_signed_request) {
				if (isset($this->fb_user_signed_request['user_id']))
					$this->fb_user_id = $this->fb_user_signed_request['user_id'];
				if (isset($this->fb_user_signed_request['oauth_token']))
					$this->fb_user_access_token = $this->fb_user_signed_request['oauth_token'];
				$ajax_processed = true;
			}
		}
		
		if (!$ajax_processed) {
			$this->fb_user_signed_request = $this->fb_instance->getSignedRequest();
			$this->fb_user_id = $this->fb_instance->getUser();
			if (isset($this->fb_user_signed_request['oauth_token']))
				$this->fb_user_access_token = $this->fb_user_signed_request['oauth_token'];
		}
		
		if (isset($this->fb_user_signed_request) && $this->fb_user_signed_request) {
			if (isset($this->fb_user_signed_request['page']['id'])) {
				if ($this->fb_user_signed_request['page']['id'] != '')
					$this->fb_page_id = $this->fb_user_signed_request['page']['id'];
			}
		}
		
		$this->application_access_token = $this->fb_instance->getAppId().'|'.$this->fb_instance->getApiSecret();
		
		/*
		if ($this->fb_user_id > 0) {
			
			$this->readCustomSession();
			
			//get user permissions
			if ($this->isAjax() && isset($_REQUEST['signed_request']) && 
					isset($this->session_variables['fb_user_permissions'])) {
				//retrieve data
				$this->fb_user_permissions = $this->session_variables['fb_user_permissions'];
			} else {
				try {
					
					$this->fb_user_permissions = $this->fb_instance->api('/me/permissions');
					//save data
					$this->session_variables['fb_user_permissions'] = $this->fb_user_permissions;
					$this->saveCustomSession();
					
				} catch(FacebookApiException $e) {
					$this->facebook_error = true;
					return;
				}
			}
			
			//print_r($this->session_variables['fb_user_friends']);
			if (isset($this->session_variables['fb_user_details'])) {
				$this->fb_user_details = $this->session_variables['fb_user_details'];
			} else {
				try {
					$this->fb_user_details = $this->fb_instance->api('/me');
					$this->session_variables['fb_user_details'] = $this->fb_user_details;
					$this->saveCustomSession();
				} catch(FacebookApiException $e) {
					$this->facebook_error = true;
					return;
				}
			}
			
		}
		*/
		return;
	}
	
	protected function parse_signed_request($signed_request, $secret) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

		// decode the data
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);

		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}

		// check sig
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}

		return $data;
	}

	protected function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
	
	/*
	 * check if request was sent from facebook
	 */
	protected function isRequestFromFB() {
		if (isset($_REQUEST['signed_request'])) {
			return true;
		}
		return false;
	}
	
	/*
	 * get facebook id as retrieved from facebook sdk
	 */
	protected function getFbUserId() {
		return $this->fb_user_id;
	}
	
	public function deleteCustomSession() {
		$this->session_variables = array();
		$session_folder = sys_get_temp_dir().'/'.$this->FacebookAppId.'/';
		$session_file = $session_folder.$this->fb_user_id.'.sess';
		if (file_exists($session_file)) {
			unlink($session_file);
		}
	}
	
	public function readCustomSession() {
		$session_folder = sys_get_temp_dir().'/'.$this->FacebookAppId.'/';
		$session_file = $session_folder.$this->fb_user_id.'.sess';
		if (!is_dir($session_folder))
			mkdir($session_folder);
		if (file_exists($session_file)) {
			//check to see if file needs to be deleted
			$seconds_since_last_update = (time() - filemtime($session_file));
			if ($seconds_since_last_update > 3600) {
				$this->deleteCustomSession();
			} else {
				$this->session_variables = unserialize(file_get_contents($session_file));
			}
		}
	}
	
	public function saveCustomSession() {
		$session_folder = sys_get_temp_dir().'/'.$this->FacebookAppId.'/';
		$session_file = $session_folder.$this->fb_user_id.'.sess';
		if (!is_dir($session_folder))
			mkdir($session_folder);
		file_put_contents($session_file, serialize($this->session_variables));
	}
	
	/*
	 * check if user has publish actions permission enabled
	 */
	protected function fbUserCanPublishActions($user_permissions=null) {
		$permissions = $this->fb_user_permissions;
		if ($user_permissions)
			$permissions = $user_permissions;
		
		if (isset($permissions)
				&& isset($permissions['data'][0]['publish_actions'])
				&& $permissions['data'][0]['publish_actions'] == 1) {
			return true;
		}
		return false;
	}
	
	/*
	 * used to retrieve redirect url inside FacebookRedirect.ss
	 */
	protected function getRedirectUrl() {
		return $this->redirect_url;
	}
	
	/*
	 * get facebook auth url - to allow app and permissions (as set in cms)
	 */
	protected function getAuthDialogUrl($include_app_data=1) {
		$request_vars = $this->getAppDataVariables();
		$url = $this->FacebookCanvasURL;
		if ($include_app_data && isset($this->fb_user_signed_request['app_data'])) {
			$url .= (isset($this->fb_user_signed_request['app_data'])?'&app_data='.$this->fb_user_signed_request['app_data']:'');
		}
		$url = urlencode($url);
		return "https://www.facebook.com/dialog/oauth?client_id=".$this->FacebookAppId."&redirect_uri=".$url."&scope=".$this->FacebookPermissions;
	}
	
	/*
	 * check if user liked the page or not
	 */
	protected function getPageLike() {
		$request = $this->fb_instance->getSignedRequest();
		return (isset($request['page']['liked'])?$request['page']['liked']:false);
	}
	
	/*
	 * get current page id
	 */
	protected function getFbPageId() {
		return $this->fb_page_id;
	}
	
	/*
	 * used in templates for form submitions
	 */
	protected function getSignedRequest() {
		if (isset($_REQUEST['signed_request']))
			return $_REQUEST['signed_request'];
		return '';
	}
	
	/*
	 * get application access token
	 */
	protected function getApplicationAccessToken() {
		return $this->application_access_token;
		
		$token_url =  "https://graph.facebook.com/oauth/access_token?" .
									"client_id=" . $this->FacebookAppId .
									"&client_secret=" . $this->FacebookAppSecret .
									"&grant_type=client_credentials";
		$app_token = file_get_contents($token_url);
		return $app_token;
	}
	
	/*
	 * get facebook variables e.g. app_data=var1:value1|var2:value2
	 */
	protected function getAppDataVariables() {
		$app_data_array = array();
		if (isset($_REQUEST['app_data'])) {
			$app_vars = explode('|', $_REQUEST['app_data']);
			foreach($app_vars as $app_variable) {
				$app_data = explode(':', $app_variable);
				if (count($app_data)==2) {
					$app_data_array[$app_data[0]] = $app_data[1];
				}
			}
		} elseif (isset($this->fb_user_signed_request['app_data'])) {
			$app_vars = explode('|', $this->fb_user_signed_request['app_data']);
			foreach($app_vars as $app_variable) {
				$app_data = explode(':', $app_variable);
				if (count($app_data)==2) {
					$app_data_array[$app_data[0]] = $app_data[1];
				}
			}
		}
		return $app_data_array;
	}
	
}