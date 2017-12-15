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
	
}
