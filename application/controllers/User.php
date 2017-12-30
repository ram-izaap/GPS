<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	protected $api_url = 'http://heresmygps.com/service/';
	protected $service_param = array('X-APP-KEY'=>'myGPs~@!');

	function __construct()
	{
		// Construct our parent class
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('Rest');

        $this->rest->initialize(
			array('server' => $this->api_url,
		        'http_auth' => 'basic'
	    	)
		);
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

	//update user position two minutes once
	function updateUserPosition()
	{
		
	}
}