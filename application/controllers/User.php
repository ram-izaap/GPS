<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'AppController.php';

class User extends AppController {

	protected $api_url = 'http://heresmygps.com/service/';
	protected $param = array('X-APP-KEY'=>'myGPs~@!');

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

			$params = array();
			$param['channel_id']= $channelID;
			$param['user_id'] 	= $userID;  

			$map_det = $this->rest->get('updateMapID', $this->service_param, 'json');

			//$output = array('nn' => $channelID, 'mm' => $userID);
			echo json_encode($map_det);

		}catch(Exception $e){

		}

	}

	//update user position two minutes once
	function updateUserPosition()
	{
		$lat 			= $this->input->post('lat');
		$lng 			= $this->input->post('long');
		$user_id    	= $this->input->post('user_id');

		$params = array();
		$params['lat'] 	       = $lat;
		$params['lon']         = $lng;
		$params['user_id']     = $user_id;
		$params['accuracy']    = 0;
		$params['allow_empty'] = 1;
		
   		$pos = $this->rest->get('user_position_save', $params, 'json');
   		echo json_encode($pos);
   		exit;
	}
}