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
		
		$my_visible = '';
		//prepare member-locations
		$locations = array();

		//public map location
		if( $map_data->type == 'public' )
		{
			$location = array(
								'display_name' 	=> $map_data->description,
                                'lat' 			=> $map_data->lat,
                                'lang' 			=> $map_data->lon,
                                'user_type' 	=> 'public',
                                'channel_id'	=> $map_data->join_key,
                                'visible' 		=> $map_data->is_view,
                                'location_type' => $map_data->location_type,
                                'user_id' 		=> $map_data->user_id,
                                'marker_color'  => base_url()."assets/images/orange-icon.png"
							);
			$locations[] = $location;
		}

		foreach($map_data->members as $member)
		{

			if( $member->user->profile->flag == 0 ) continue;

			if( $member->user->profile->id == $this->userID )
			{
				$my_visible = $member->user->group->view;
			}


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

			$img = base_url()."assets/images/red-icon.png";

			if( $within_limit ) 
			{
				if( $map_data->type != 'public' )
				{
					if( $member->user->profile->user_type == 'admin' )
					{
						$img = base_url()."assets/images/violet-icon.png";
					}
					else
					{
						$img = base_url()."assets/images/green-icon.png";
					}
					
				}
				else
				{
					$img = base_url()."assets/images/orange-icon.png";
				}
			}

			$location = array(
								'display_name' 	=> $member->user->profile->display_name,
                                'lat' 			=> $member->user->position->lat,
                                'lang' 			=> $member->user->position->lon,
                                'user_type' 	=> $member->user->profile->user_type,
                                'channel_id'	=> $member->user->profile->default_id,
                                'visible' 		=> $member->user->group->view,
                                'location_type' => 'dynamic',
                                'user_id' 		=> $member->user->profile->id,
                                'marker_color'  => $img,
                                'last_seen_time'=> $last_seen_time * 1000
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
				'type' => $map_data->type,
				'locations' => $locations
			);

		$this->data['map_data'] = json_encode( $output );

		$this->data['search_key'] = $this->joinKey;

		//$this->data['user_info']['join_key'] 	=  $this->joinKey;
		//$this->data['user_info']['visible'] 	= $my_visible;
		//$this->data['visible'] 					= $my_visible;

		//load common data
		$this->getCommonData();

		//customPrint( $this->data );

		$this->layout->view($this->viewPath.'/search/index', $this->data);
	}

}