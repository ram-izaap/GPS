<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'AppController.php';

class Search extends AppController {

	public function __construct()
	{
		parent::__construct();

		$this->data['back_url'] = $this->back_url;
	}

	public function index( $jkey = '' )
	{
		try
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

			$passwd = $this->input->post('password');

			$validation = $this->validateJoinKey($this->joinKey, $passwd, TRUE );

			//customPrint( $validation );
			if( $validation['status'] != 'success' )
			{
				throw new Exception($validation['msg']);				
			}

			//save JOIN KEY
			$this->setJoinKey( $this->joinKey );

			

			$params = array(
					'user_id' => $this->userID,
					'join_key' => $this->joinKey
				);

			

			if( $passwd !== '' && $validation['type'] == 'password' )
			{
				$params['password'] = $passwd;

				$map_data = $this->rest->get('check_group_password', $params, 'json');
			}
			else
			{
				$map_data = $this->rest->get('search_map', $params, 'json');
			}
			
			//customPrint( $map_data->static_maps );
			
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
	                                'marker_color'  => base_url()."assets/images/orange-icon.png",
	                                'speed'			=> '',
	                                'accuracy'      => '',
	                                'profile_image' => ''
								);
				$locations[] = $location;
			}

			//Visibles & Invisibles
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

				if( $twenty_four_time > $last_seen_time ) continue;
				
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

				$profileImg = $member->user->profile->profile_image;

				if( empty($profileImg) )
				{
					//Todo
					$profileImg = base_url('assets/images/default-user.png');
				}

				$location = array(
									'display_name' 	=> $member->user->profile->display_name,
									 'phonenumber'  => $member->user->profile->phonenumber,
	                                'lat' 			=> $member->user->position->lat,
	                                'lang' 			=> $member->user->position->lon,
	                                'user_type' 	=> $member->user->profile->user_type,
	                                'channel_id'	=> $member->user->profile->default_id,
	                                'visible' 		=> $member->user->group->view,
	                                'location_type' => 'dynamic',
	                                'user_id' 		=> $member->user->profile->id,
	                                'marker_color'  => $img,
	                                'last_seen_time'=> $last_seen_time * 1000,
	                                'speed'			=> $member->user->position->speed,
	                                'accuracy'      => $member->user->position->accuracy,
	                                'profile_image'   => $profileImg

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
	                                'channel_id'	=> $this->joinKey,
	                                'visible' 		=> '',
	                                'location_type' => 'staticmap',
	                                'user_id' 		=> $static_map->user_id,
	                                'speed'			=> '',
	                                'accuracy'      => '',
	                                'notes' 		=> $static_map->notes,
	                                'profile_image' => $static_map->clue_image
								);

				$locations[] = $location;
			}

			//customPrint( $map_data->static_maps );
			
			$output = array(
					'info' => '',
					'type' => $map_data->type,
					'protection_type' => $validation['type'],
	                'join_key' => $this->joinKey,
					'locations' => $locations
				);

			$this->data['map_data'] = json_encode( $output );

			$this->data['search_key'] = $this->joinKey;

			//$this->data['user_info']['join_key'] 	=  $this->joinKey;
			//$this->data['user_info']['visible'] 	= $my_visible;
			//$this->data['visible'] 					= $my_visible;

			//load common data
			$this->getCommonData();

		}
		catch(Exception $e){

			$output = array(
					'info' => '',
					'type' => '',
					'protection_type' => 'normal',
	                'join_key' => $this->joinKey,
					'locations' => array()
				);

			$this->data['map_data'] = json_encode( $output );

			$this->data['search_key'] = $this->joinKey;

			$this->data['error_message'] = $e->getMessage();

			//load common data
			$this->getCommonData();
		}

		//customPrint( $this->data );
		if( $this->input->is_ajax_request() ) {
        	echo json_encode( $this->data );
        	exit;
        }
        else
        {
			$this->layout->view($this->viewPath.'/search/index', $this->data);
		}
	}
    
   	public function validateJoinKey( $jkey = '' , $passwd = '', $flag = FALSE )
	{


        $this->joinKey = '';
        
		if( $this->input->post('join_key') !== '' )
		{
			$this->joinKey 	= $this->input->post('join_key');
			$passwd 		= $this->input->post('password');
		}

		if( !empty($jkey) )
		{
			$this->joinKey = $jkey;
		}
		
		//save JOIN KEY
		//$this->setJoinKey( $this->joinKey );
        

		$params    = array('join_key' => $this->joinKey );
		$groupData = $this->rest->get('group_dt', $params, 'json');
        
        $resp = array();
        
        //validate group
        if($groupData->status == 'success')
        {
            if($groupData->data->password_protect == 1)
            {
             	$protectionType = 'password';
            }
            else if($groupData->data->allow_deny == 1)
            {
             	$protectionType = 'allow_deny';
            }
            else
            {
             	$protectionType = 'normal';
            }
              
              
            if($protectionType == 'normal')
            {
             	$resp = array(
             					"status" => "success", 
             					"msg" => "",
             					"type" => $protectionType

             				);
            }  
            
            else if($protectionType == 'allow_deny')
            {
            	$status = "error";

				$params    = array();
				$params['user_id'] = $this->userID;
				$joined_groups = $this->rest->get('joined_groups', $params, 'json');

				if( $joined_groups->status == 'success' )
				{
					$joined_groups = $joined_groups->list;

					if( is_array($joined_groups) && count($joined_groups) )
					{
						foreach ($joined_groups as $jg) {
							if( $jg->join_key == $this->joinKey )
							{
								$status = 'success';
								break;
							}
						}
					}
				}

             	$resp = array( 	
             					"status" => $status,
             					"type" => $protectionType ,
             					"msg" => "This map has been protected. Do you want to send join request?"
             				);
            }  
            else
            {
                
            	if(empty($passwd))
                {
                    $resp = array( 
                    		"status" => "error",
                    		"type" => $protectionType ,
                    		"msg" => 'This map has been password protected so please enter password'
                    	);
                }
                else if($passwd == $groupData->data->password)
                {
                    $resp = array( "status" => "success", "type" => $protectionType);
                }
                else
                {
                    $resp = array( 
                    	"status" => "error", 
                    	"type" => $protectionType ,
                    	"msg" => "Please Enter Correct Password"
                    );
                }
            }
                    
        }
        else
        {
            $resp = array( "status" => "error", "msg" => $groupData->msg);
        }
        
        if( $this->input->is_ajax_request() && !$flag )
        {
        	echo json_encode( $resp );
        	exit;
        }

        return $resp;
        
    }

    function breadcrumb()
   {
		
		$user_id = $this->input->post('user_id');

        $params  = array();
		$params['user_id'] 	     = json_encode(array($user_id));
		$params['time_limit']    = $this->input->post('timelimit');
		
    
        $map = $this->rest->get('get_trigger_positions', $params, 'json');
       // print_r($map);exit;
        $map      = (array)$map;
        $position = (array)$map['positions']->$user_id;
        
        $pos_array= array();
        if(count($position)){
	        foreach($position as $pkey=>$pvalue){
	            $pos_array[$pkey]['lon']        = $pvalue->lon;
	            $pos_array[$pkey]['lat']        = $pvalue->lat;
	            $pos_array[$pkey]['flag']       = $pvalue->flag;
	            $pos_array[$pkey]['speed']      = $pvalue->speed;
	            $pos_array[$pkey]['altitude']   = $pvalue->altitude;
	            $pos_array[$pkey]['bearing']    = $pvalue->bearing;
	            $pos_array[$pkey]['update_time']= $pvalue->update_time;
	        }
        }
		if(count($pos_array)){
	   		echo json_encode($pos_array);
	   		exit;
   		}
   }

   function allowDenyRestriction()
   {
     try {
              $join_key = $this->input->post('joinkey');
              $user_id  = $this->input->post('user_id');
              
              $params = array();
          	  $params['join_key'] 	= $join_key;
              $params['user_id']    = $user_id;
              
          	  $res = $this->rest->get('allowdeny_send_notification', $params, 'json');
          	  $res = (array)$res;
              
              if(empty($res))
   			       throw new Exception('Service request failed!');

   			  if($res['status']=='error')
   				  throw new Exception($res['msg']);
 
                $outputs['status']        = 'success';
                $outputs['msg']           = $res['msg'];
                $outputs['request_type']  = $res['request_type'];
    			$outputs['join_key']      = $res['join_key'];    
    		
	   	}
	   	catch (Exception $e)
        {
        	$outputs['status']='error';
			$outputs['msg']   = $e->getMessage(); 
        } 	
		
		echo json_encode($outputs);
		exit;
   }

}