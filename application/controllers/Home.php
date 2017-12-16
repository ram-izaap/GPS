<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'AppController.php';

class Home extends AppController {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->getCommonData();

		$this->layout->view('desktop/home/index', $this->data);
	}


   public function Aboutus()
	{
		$this->getCommonData();
		$this->layout->view('aboutus',$this->data);
	}


	public function Help()
	{
		$this->getCommonData();
		$this->layout->view('help',$this->data);
	}

	public function Tellus()
	{
		$this->getCommonData();
		$this->layout->view('tellus',$this->data);
	}

    public function Privacy_policy()
	{
		$this->getCommonData();
		$this->layout->view('privacy_policy',$this->data);
	}

	// public function index()
	// {
	// 	$this->getCommonData();

	// 	$this->layout->view('desktop/home/index', $this->data);
	// }


	/*public function search( $search_key = '')
	{
		$userInfo = $this->getUserInfo(80617);

		$params = array(
				'user_id' => 80617, 
				'join_key' => 'Miranda'
			);

		$map_data = $this->rest->get('search_map', $params, 'json');

		
		//prepare member-locations
		$locations = array();
		foreach($map_data->members as $member)
		{

			if( $member->user->profile->flag == 0 ) continue;

			$user_type = $member->user->profile->user_type;

			$last_seen_time = date('d-m-Y H:i', $member->user->group->last_seen_time);
			$last_seen_time = strtotime($last_seen_time);

			//24 hours last update time check
			$twenty_four_time = date('d-m-Y H:i',strtotime('-24 hour'));
			$twenty_four_time = strtotime($twenty_four_time);

			//if( $twenty_four_time > $last_seen_time ) continue;
			
			//-1Hr
			$current_time = date('d-m-Y H:i',strtotime('-1 hour'));
			$current_time = strtotime($current_time);

			$within_limit	= true;
			if( $current_time <= $last_seen_time )
			{
				$within_limit = true;
			}

			if( ($current_time > $last_seen_time) && ($twenty_four_time < $last_seen_time) ) 
			{  
			  if($user_type == 'member') {
			     //continue;
			  }
				$within_limit = false;
			}


			 
			$channel_id  = $member->user->profile->default_id;
			$phone_num   = $member->user->profile->phonenumber;

			$location = array(
								'display_name' 	=> $member->user->profile->display_name,
                                'lat' 			=> $member->user->position->lat,
                                'lang' 			=> $member->user->position->lon,
                                'user_type' 	=> $member->user->profile->user_type,
                                'channel_id'	=> $member->user->profile->default_id,
                                'visible' 		=> $member->user->group->view,
                                'location_type' => 'dynamic',
                                'user_id' 		=> $member->user->profile->id
							);

			$locations[] = $location;

		}


		$output = array('locations' => $locations);

		//echo json_encode(value)
		//customPrint( $locations);

		$this->layout->view('desktop/home/index', $this->data);
	}*/



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



}


