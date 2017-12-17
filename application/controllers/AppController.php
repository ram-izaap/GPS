<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppController extends CI_Controller {

	protected $data = array();
	public $userID 	= 0;
	public $joinKey = '';

	public $isMobile = FALSE;
	public $viewPath = 'desktop';

	public function __construct()
	{
		parent::__construct();

		//Set Device Type
		$this->isMobile = $this->agent->is_mobile();

		if( $this->isMobile ) $this->viewPath = 'mobile';

		//initialize REST
		$this->rest->initialize( 
				array(
					'server' => $this->config->item('api_url'), 
					'http_auth' => 'basic',
					'api_key' => X_APP_KEY 
				) 
			);

		//Set UserID & JOIN_KEy
		$this->setUserID();
		$this->setJoinKey();

	}

	public function getCommonData()
	{
		//echo $this->userID;die;
		$userInfo = $this->getUserInfo( $this->userID );
		
		$searchKey = '';

		//customPrint($this->data['user_info']);
		$this->data = array_merge($this->data, $userInfo);

		$this->data['search_key'] = $searchKey;



		//$this->data['user_action'] = ( (int)$userInfo['user_id'] ) ? 'user_update':'guest_registration';
		
		
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



       	//prepare my map display string part ( for Home page )
       	$mapDispStr = $this->isMobile ? "<b>Return to</b>My Map":"Return to <small>My Map</small>";
       	if( $searchKey == '' )
       	{
       		$mapDispStr = $this->isMobile ? "<b>View</b>My Map":"View <small>My Map</small>";
       	}
       	else if( $searchKey == $userInfo['channel_id'] )
       	{
       		$mapDispStr = $this->isMobile ? "<b>My</b>My Map":"My <small>Map</small>";
       	}

       	$this->data['map_disp_str'] = $mapDispStr;

       	

       	$params = array('user_id' => $userInfo['user_id'], 'channel_id' => $userInfo['channel_id']);
		$resp = $this->rest->get('group_info', $params, 'json');
		
		if( is_object($resp) && $resp->status == 'success' )
		{
			$this->data['visible'] = $resp->data->is_view;
		}
		else
		{
			$this->data['visible'] = '1';// default value
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
							"updated_phonenumber" => $user->updated_phonenumber,
							"join_key" => $this->joinKey
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
						'phonenumber' 	=> $randomID,
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
		$randomID = get_cookie('random_channelid');

		$resp = $this->rest->get('random_number_generation', array(), 'json');

		if( is_object($resp) && $resp->status == 'success' )
		{
			$cookie = array(
					'name' => 'random_channelid',
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


	/*
	*	Update groups's visiblily 
	*/
	public function updateVisible()
	{
		$userID 	= $_POST['user_id'];
		$channelID 	= $_POST['channel_id'];
		$view 		= $_POST['visible'];

		$params = array('user_id' => $userID, 'group_id' => $channelID, 'view' => $view);
		$resp = $this->rest->get('group_status', $params, 'json');
		
		$visible_html = $this->load->view('_partials/visible_status', array('visible' => $view), TRUE );

        echo json_encode(array("status" => "success",'visible_html' => $visible_html),200);
	}
	

	public function removeAllMaps()
	{
		$userID = $this->input->post('user_id');

		$params = array('user_id' => $userID);
		$resp = $this->rest->get('remove_user_from_all_groups', $params, 'json');
		//customPrint((array)$resp);
		$output = array();

		if( is_object($resp) && $resp->status == 'success' )
		{
			$output['status']	= 'success';
            $output['msg']     = "Removed user from all groups successfully";
		}
		else
		{
			$output['status']	= 'error';
            $output['msg']     	= "Something went wrong.";
		}

		echo json_encode($output);
	}

	public function updateDisplayNameAndPhone()
	{
		$userID 		= $this->input->post('user_id');
		$display_name 	= $this->input->post('display_name');
		$update_type 	= $this->input->post('update_type');
		$phone_number 	= $this->input->post('phone_number');

		$params = array(
			'user_id'		=> $userID,
			'display_name' 	=> $display_name,
			'update_type' 	=> $update_type,
			'phone_number' 	=> $phone_number
			);

		$resp = $this->rest->get('update_user_info', $params, 'json');

		

		$output = array();

		if( is_object($resp) && $resp->status == 'success' )
		{
			$output['status']	= 'success';
            $output['msg']     = "Updated successfully.";
		}
		else
		{
			$output['status']	= 'error';
            $output['msg']     	= "Something went wrong.";
		}

		echo json_encode($output);
	}
	




	public function setUserID( $uid = 0 )
	{
		
		$uid_cookie = get_cookie('user_id');

		if( (int)$uid )
		{
			$this->userID = $uid;

			$cookie = array(
					'name' => 'user_id',
					'value' => $this->userID,
					'expire' => time()+20000,
					'path'   => '/',
					);

			set_cookie($cookie);
		}
		else if( (int)$uid_cookie )
		{
			$this->userID = $uid_cookie;
		}

	}


	public function setJoinKey( $joinKey = '' )
	{
		
		$joinKey_cookie = get_cookie('join_key');

		if( $joinKey != '' )
		{
			$this->joinKey = $joinKey;

			$cookie = array(
					'name' => 'join_key',
					'value' => $this->joinKey,
					'expire' => time()+20000,
					'path'   => '/',
					);

			set_cookie($cookie);
		}
		else if( $joinKey_cookie != '' )
		{
			$this->joinKey = $joinKey_cookie;
		}

	}
	
}
