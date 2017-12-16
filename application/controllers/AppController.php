<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppController extends CI_Controller {

	protected $data = array();

	public function __construct()
	{
		parent::__construct();

		//initialize REST
		$this->rest->initialize( 
				array(
					'server' => $this->config->item('api_url'), 
					'http_auth' => 'basic',
					'api_key' => X_APP_KEY 
				) 
			);
	}

	public function getCommonData()
	{
		$userInfo = $this->getUserInfo(80617);
		$searchKey = '';

		//customPrint($this->data['user_info']);
		$this->data = array_merge($this->data, $userInfo);

		$this->data['search_key'] = $searchKey;

		$this->data['user_action'] = ( (int)$userInfo['user_id'] ) ? 'user_update':'guest_registration';
		$uri      = $this->uri->segment(1);
       	$page     = ($uri == 'search' || $uri!='help' || $uri!='tellus' || $uri!='privacy-policy-and-terms-and-conditions')?"search-page":"";

       	$this->data['uri'] 	= $uri;
       	$this->data['page'] = $page;

       	$key = $userInfo['channel_id'];
       	if( $this->data['search_key'] )
       	{
       		$key = $this->data['search_key'];
       	}
       	$this->data['shareurl'] = base_url()."search/".$key;

       	//prepare my map display string part
       	$mapDispStr = "Return to <small>My Map</small>";
       	if( $searchKey == '' )
       	{
       		$mapDispStr = "View <small>My Map</small>";
       	}
       	else if( $searchKey == $userInfo['channel_id'] )
       	{
       		$mapDispStr = "My <small>Map</small>";
       	}

       	$this->data['map_disp_str'] = $mapDispStr;

       	

       	$params = array('user_id' => $userInfo['user_id'], 'channel_id' => $userInfo['channel_id']);
		$resp = $this->rest->get('group_info', $params, 'json');
		
		if( is_object($resp) && $resp->status == 'success' )
		{
			$this->data['visible'] = $resp->data->is_view;
		}

		$this->data['user_info'] = $this->data;
	}

	public function getUserInfo( $userID = 0 )
	{ 
		//if not valid user, return default data
		if( !$this->isValidUser($userID) ) return $this->getDefaultUserData();		
		
		$params = array('user_id' => $userID);
		$resp = $this->rest->get('get_channel_byuser', $params, 'json');

		if( is_object($resp) && $resp->status == 'success' )
		{
			$user = $resp->user;
			$userInfo = array(
							'channel_id' =>	$user->channel_id, 
							'display_name'=> $user->udispname,
							'phonenumber' => $user->phonenumber , 
							'user_id'=> $user->user_id,
							'group_id'=> $user->group_id,
							'joined_group'=> $resp->joined_group,
							"updated_type" => $user->updated_type, 
							"updated_phonenumber" => $user->updated_phonenumber
						);

			return $userInfo;
		}
		
		return $this->getDefaultUserData();
		
	}

	private function getDefaultUserData()
	{
		$randomID =  $this->getRandomChannelID();

		$userInfo = array(
						'channel_id' 	=> $randomID, 
						'display_name'	=> $randomID,
						'phonenumber' 	=> '' ,
						'user_id'		=> '',
						'group_id'		=>	'',
						'joined_group'	=>	'',
						"updated_type" 	=> 'system',
						"updated_phonenumber" => "system"
					);

		return $userInfo;
	}

	public function isValidUser( $userID = 0 )
	{
		$userID = (int) $userID;

		if( !$userID ) return FALSE;

		$params = array('user_id' => $userID);
		$resp = $this->rest->get('user_exist_check', $params, 'json');
		
		if( is_object($resp) && $resp->status == 'success' ) return TRUE;		

		return FALSE;

	}

	public function getRandomChannelID()
	{
		$randomID = get_cookie('rand_channelid');

		$resp = $this->rest->get('user_exist_check', array(), 'json');

		if( is_object($resp) && $resp->status == 'success' )
		{
			$cookie = array(
					'name' => 'rand_channelid',
					'value' => $resp->random_id,
					'expire' => time()+20000,
					'path'   => '/',
					);

			set_cookie($cookie);

			$randomID = $resp->random_id;
		}

		return $randomID;

	}

	public function getModalConent( $type = '')
	{
		$data = array();
		$data['content'] = $this->load->view('modals/'.$type, array(), TRUE);

		echo json_encode( $data );
	}

	function updateChannelID()
	{
		try{
			$channelID 	= $_POST['channel_id'];
			$userID 	= $_POST['user_id'];
			if( !isset($channelID, $userID) )
			{
				throw new Exception("Invalid Parameters");				
			}
			$this->service_param['channel_id'] 	= $channelID;
			$this->service_param['user_id'] 	= $userID;  
			$map_det = $this->rest->get('updateMapID', $this->service_param, 'json');
			//$output = array('nn' => $channelID, 'mm' => $userID);
			echo json_encode($map_det);
		}catch(Exception $e){
		}
	}
	
}
