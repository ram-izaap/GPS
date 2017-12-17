<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'AppController.php';

class Search extends AppController {

	public function __construct()
	{
		parent::__construct();
	}

	public function index( $jkey = '' )
	{
		//load common data
		$this->getCommonData();

		//get join_key
		if( $jkey !== '' )
		{
			$this->joinKey = $jkey;
		}
		else if( $this->input->post('join_key') !== '' )
		{
			$this->joinKey = $this->input->post('join_key');
		}
		//save JOIN KEY
		$this->setJoinKey( $this->joinKey );

		$params = array(
				'user_id' => $this->userID,
				'join_key' => $this->joinKey
			);

		$passwd = $this->input->post('password');

		if( $passwd !== '' )
		{
			$params['password'] = $passwd;

			$map_data = $this->rest->get('check_group_password', $params, 'json');
		}
		else
		{
			$map_data = $this->rest->get('search_map', $params, 'json');
		}
		
		//customPrint($map_data);
		
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

		

		//STATIC MAPS
		$static_maps = $map_data->static_maps;

		foreach($static_maps as $static_map)
		{
			$location = array(
								'display_name' 	=> $static_map->map_name,
                                'lat' 			=> $static_map->lat,
                                'lang' 			=> $static_map->lon,
                                'user_type' 	=> '',
                                'channel_id'	=> $static_map->join_key,
                                'visible' 		=> '',
                                'location_type' => 'staticmap',
                                'user_id' 		=> $static_map->user_id,
							);

			$locations[] = $location;
		}

		//customPrint( $map_data->static_maps );
		
		$output = array(
				'info' => '',
				'locations' => $locations
			);

		$this->data['map_data'] = json_encode( $output );

		$this->data['search_key'] = $this->joinKey;

		//customPrint( $this->data );

		$this->layout->view('desktop/search/index', $this->data);
	}

}