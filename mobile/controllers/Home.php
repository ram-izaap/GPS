<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	private $data = array('map_search_key'=>'','service_resp'=>array('status'=>''));

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
        
        $join_key = get_cookie('map_search');
        
		if($this->uri->segment(1)=='search' && $this->uri->segment(2)!=''){

			$cookie = array(
					'name' => 'map_search',
					'value' => $this->uri->segment(2),
					'expire' => time()+3600,
					'path'   => '/',
					);
				set_cookie($cookie);	
				$this->data['map_search_key'] = $this->uri->segment(2);
		}

		 $this->data['user_info'] = "";
	     $this->get_user_details();
       
         $user_id = get_cookie('map_user_id');
         
         $this->data['user_id'] = $user_id;
         
         $user_data  = $this->db->query("select default_id from user where id='".$user_id."'")->row_array();
         $group_data = $this->db->query("select id from groups where join_key='".$join_key."'")->row_array();
         $user_group = $this->db->query("select ug.* from user_groups ug where ug.user_id='".$user_id."' and ug.group_id='".$group_data['id']."'")->row_array();
         $this->data['visible'] = $user_group['is_visible'];
         $this->data['current_joined_group_id'] = (!empty($group_data['id']))?$group_data['id']:"";
         $this->data['manual_address'] = $this->user_manual_address($user_id,"search");
    }


	public function index()
	{ 
        $this->data['pwd'] = "";
		$this->layout->view('index',$this->data);
	}


	public function search($search='',$ajax=0)
	{
		try
        {

			$joinkey='';
            $breadcrumb_status = 0;
			$user_id=0;
			$m = 0;
			$locations=array();

	   		$contents = array();

			$map_cookie = get_cookie('map_search');

			if(isset($_POST['search'])){
				$joinkey = $this->input->post('search');
                 $pwd     = $this->input->post('pwwd');
                
				if(!$joinkey)
					throw new Exception("Please fill the required fields.");
                       
				delete_cookie('map_search');  
			}
			elseif($search!='')
			{
				$joinkey = $search;
			}	
			elseif($map_cookie!='')
			{
				$joinkey = $map_cookie;
			}	
            
			$user_id = get_cookie('map_user_id');
             
			if(!empty($user_id)){				
				$cookie = array(
        						'name' => 'map_user_id',
        						'value' => $user_id,
        						'expire' => time()+86400,
        						'path'   => '/',
						       );
				set_cookie($cookie);
			}

			//set search value to cookie and expire time 1 hour
      			if($joinkey!=''){
      				$cookie = array(
                  					'name' => 'map_search',
                  					'value' => $joinkey,
                  					'expire' => time()+3600,
                  					'path'   => '/',
      					           );
      				set_cookie($cookie);	
      				$this->data['map_search_key'] = $joinkey;	
      			}

               $this->service_param['user_id']  = $user_id;
		        $this->service_param['join_key'] = $joinkey;
              $this->service_param['allowed']  = 'no';
            
             if(!empty($pwd)){
                $this->service_param['password'] = (!empty($pwd))?$pwd:"";
                $map_det = $this->rest->get('check_group_password', $this->service_param, 'json');
             } 
             else
             {
                  $this->service_param['type']     = "join";
	   		      $map_det = $this->rest->get('search_map', $this->service_param, 'json');
             }
           
	   		$map_det = (array)$map_det;
           
           //echo "<pre>"; print_r($map_det); exit;
           
	   		if(empty($map_det))
	   			  $map_det['status']='';

	   		$service_status = array('status'=>$map_det['status'],'message'=>'');

	   		if(!isset($_POST['search']) && $joinkey=='')
	   			$service_status['status']='';

	   		if($map_det['status']=='error'){
               $service_status['message']       = $map_det['msg'];
                $service_status['request_type']  = $map_det['request_type'];
            }
            
	   		$this->data['service_resp'] = $service_status;

            $current_tracked_user = ''; $trackd_user = '';
                   
	   		if(isset($map_det['status']) && $map_det['status']=='success' && isset($map_det['members']) && !empty($map_det['members'])){
             
                if(isset($map_det['type']) && ($map_det['type']=='public')){
                   $map_locations = array(ucfirst($joinkey),$map_det['lat'],$map_det['lon']); 
                }
                
	   			foreach($map_det['members'] as $val){
                    $m++;
                   $user_type = $val->user->profile->user_type;
                    
	   				if($val->user->profile->flag==0)
	   					continue;

	   				$lastseen = date('d-m-Y H:i',$val->user->group->last_seen_time);
                
	   				//24 hours last update time check
           $twenty_four_time = date('d-m-Y H:i',strtotime('-24 hour')); 
            
            if(strtotime($twenty_four_time) > strtotime($lastseen)){
               continue;
            }
            
            $currtime = date('d-m-Y H:i',strtotime('-1 hour'));
            $lastup   =  1;
            
            if(strtotime($currtime) <= strtotime($lastseen)){
               $lastup   = 1; 
            }
            
            if((strtotime($currtime) > strtotime($lastseen)) && (strtotime($twenty_four_time) < strtotime($lastseen))) {  
                if($usertype == 'member') {
                   continue;
                }
              $lastup = 0;
            }
        
	   			  $displayname = $val->user->profile->display_name; 
	   				$channel_id  = $val->user->profile->default_id;
	   				$phone_num   = $val->user->profile->phonenumber;
                 
	   				if($user_type == 'admin' && ($map_det['type']!='public')){
	   					$displayname = $val->user->group->description;
	   					$channel_id  = $val->user->group->join_key;  
	   				}
	   			
              $group_invisible = $val->user->group->view;
              
             if(isset($_POST['search']) && !empty($_POST['search'])){
               if($user_type == 'admin') {
                     $breadcrumb_admin_user = $val->user->profile->id; 
                     $current_tracked_user = $channel_id;
                 } 
              }

              if($map_det['type'] !='public'){
                $iconImage  = (!empty($user_type) && ($user_type=='admin'))?"/assets/images/violet-icon.png":"/assets/images/green-icon.png";
              }
              else
              {
                 $iconImage = "/assets/images/orange-icon.png";
              }
             
              $img = array(base_url()."/assets/images/red-icon.png",base_url().$iconImage);
              $locations[]      = array($displayname,$val->user->position->lat,$val->user->position->lon,$lastup,$val->user->profile->user_type,$channel_id,$group_invisible,'dynamic',$val->user->profile->id,$img[$lastup]);

          //$static_maps[]    = (array)$val->user->static_map->maps;
            $st_map_user_id[] = $val->user->static_map->user_id;
                    
            $pro_pic   = $val->user->profile->profile_image;
            $img       = (!empty($pro_pic) && fopen($pro_pic, "r"))?$val->user->profile->profile_image:base_url().'assets/images/default-user.png';
  					 	
            $browseloc = 'http://maps.google.com/maps?z=12&t=m&q=loc:'.$val->user->position->lat.'+'.$val->user->position->lon;

          $groups = $this->db->query("select * from groups where join_key='".$joinkey."'")->row_array();
          $str = '';
                    		
	   				$str    = '<div class="map-locator">
                             <div class="map-pic clearfix">
    				             <div class="col-xs-4">
    								<img src="'.$img.'" class="img-responsive img-circle" alt="user-image" />
    							</div>';
                    if($group_invisible == 0) {
                           $str .='<a onclick="invisible_participant('.$val->user->profile->id.','.$groups['id'].',1)" class="visible_status">
                                    <span class="invisible_pp">
                                      <img src="'.base_url().'assets/images/invisible_participant_icon.png" target="_blank" alt="Invisible Participant" /> Invisible
                                    </span>
                                    </a>';
                    } 
    				$str   .=	'<div class="col-xs-8">
    								<p> <i class="fa fa-user pull-left" aria-hidden="true"></i><small> HMGPS User ID</small> '.$channel_id.'</p>
    							    <p> <i class="fa fa-user pull-left" aria-hidden="true"></i> <small>Display Name: '.$displayname.'</p>
    							</div>
							</div>';
                            
							
				  $str .=	'<div class="map-record clearfix">
                  <div class="col-xs-12 clearfix">
                   <p> <i class="fa fa-clock-o pull-left" aria-hidden="true"></i> 
                     <small>Position Time Updated</small></p>
    				       <p><<lastseen>></p>
    							</div>
							    <div class="col-xs-3 clearfix">
                    <p> <small>Speed</small> '.$val->user->position->speed.' </p>
                  </div>
                  <div class="col-xs-6 clearfix">
                    <p> <small>Accuracy</small> '.$val->user->position->accuracy.' </p>
                  </div>
							   <div class="col-xs-12 clearfix">
                  <p> <i class="fa fa-globe pull-left" aria-hidden="true"></i> <small>GPS Coordinate</small> '.$val->user->position->lat.', &nbsp;&nbsp;&nbsp;  '.$val->user->position->lon.' </p>
                </div>
            </div>';
                            
						      $breadcrumb_user      = get_cookie('breadcrumb_user');
                              $breadcrumb_timelimit = get_cookie('breadcrumb_timelimit'); 
                              $current_tracked_user = get_cookie('trackeduser');
                             
                              $bread2 = (($breadcrumb_user == $val->user->profile->id) && ($breadcrumb_timelimit == 2))?'checked="checked"':"";
                              $bread3 = (($breadcrumb_user == $val->user->profile->id) && ($breadcrumb_timelimit == 0))?'checked="checked"':"";
                              
                              $checked = '';
                              if(($current_tracked_user == $channel_id) || ($current_tracked_user == $displayname) || ($user_type == 'admin') ){
                                $checked = 'checked="checked"';
                              }
                              else if(empty($current_tracked_user))
                              {
                                $checked = '';
                              }
                              
                              $bread1 = '';
                              if(($breadcrumb_user == $val->user->profile->id) && ($breadcrumb_timelimit == 1) ){
                                $bread1 = 'checked="checked"';
                              }
                              else if(($user_type == 'admin') && (empty($breadcrumb_user)))
                              {
                                $bread1 = 'checked="checked"';
                              }
                              
                              $str .= ' <div class="track-buttons clearfix">';
                              $str .='<label for="1">Track User<input type="checkbox" '.$checked.' value="track" class="track_userr tuser'.$val->user->profile->id.'" data-chid="'.$channel_id.'" data-mapsearch="'.$joinkey.'" data-uid="'.get_cookie('map_user_id').'" data-profileid="'.$val->user->profile->id.'" data-usertype="'.$val->user->profile->user_type.'" onclick="trackuser();" /></label>';
                              $str .='<label for="2">10 mins<input type="checkbox" '.$bread1.' onclick="breadcrumb('.$val->user->profile->id.',1);" name="breadcrumb1" value="1" id="breadcrumb1" class="breadcrumb user'.$val->user->profile->id.'"  data-uid="'.$val->user->profile->id.'" data-timelimit="1" /></label>';
                              $str .='<label for="3">24 Hrs<input type="checkbox" '.$bread2.' onclick="breadcrumb('.$val->user->profile->id.',2);" name="breadcrumb2" value="2" id="breadcrumb2" class="breadcrumb"  data-uid="'.$val->user->profile->id.'" data-timelimit="2" /></label>';  
                              $str .='<label for="4">24 Hrs in Detail <input type="checkbox" '.$bread3.' onclick="breadcrumb('.$val->user->profile->id.',0);" name="breadcrumb0" value="0" id="breadcrumb0" class="breadcrumb"  data-uid="'.$val->user->profile->id.'" data-timelimit="0" /></label>';
                            //  $str .='<li><input type="radio" name="breadcrumb" value="clear_trk" class="clear_tracking"  data-timelimit="0" />Clear Tracking</li>';
							  $str .= '</div>';
                              
                             $str .= ' <div class="map-action">
                                        <div class="map-buttons">
								        <span class="tool-tip" data-text="Navigate"><a href="'.$browseloc.'"><i class="fa fa-map-pin fa-2x" aria-hidden="true"></i></a></span>';
								
								if(is_numeric($phone_num)){
								$str .='<span class="tool-tip" data-text="Call"><a href="tel:'.$phone_num.'"><i class="fa fa-phone fa-2x" aria-hidden="true"></i></a></span>
							          <span class="tool-tip" data-text="SMS"><a href="sms:'.$phone_num.'&body=Here\'s MyGPS" class="sms"><i class="fa fa-commenting fa-2x" aria-hidden="true"></i></a></span>';
							    }

						 		$str .='<span class="tool-tip" data-text="Email"><a href="mailto:?subject=Here\'s MyGPS&body=Hi, '.site_url('search/'.$channel_id).'"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i></a></span>';
                                
                                $str .= '</div></div>';
                              
                                $str .= ' <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                        <div class="btn-group text-center" role="group">';
							    $str .= '<button onclick="breadcrumb('.$val->user->profile->id.');" class="btn btn-default btn-success" > OK </button></div>';
                                $str .= '<div class="btn-group" role="group">
                                       <button onclick="clear_track()" class="btn btn-default btn-info">Clear Tracking</button>
                                       </div> 
                                       <div class="btn-group" role="group">
                                        <button  onclick="closeinfowindow()" class="btn btn-default btn-danger"> Close </button>
                                        </div>
							           </div>
                                      </div>';
                            
					
	   				$contents[] = array($str,$val->user->group->last_seen_time);		
	   			}
	   			//update current active group
	   			if((int)$user_id && $joinkey!=''){

	   				$this->service_param['user_id']  = $user_id;
	   				$this->service_param['group_id'] = $joinkey;
	   			    $res = $this->rest->get('user_current_group_active', $this->service_param, 'json');
	   			}
                   
                $static_maps[] = $map_det['static_maps'];	
	   		}	
	   	}
	   	catch (Exception $e)
        {
            $service_status = array('status'=>'error','message'=>$e->getMessage(),'request_type' => $e->request_type);
            $this->data['service_resp'] = $service_status;
        } 
       	$this->data['participant_count']    = count($locations);
         
        $stat_maps = array(); $locations1 = array();

        foreach($static_maps as $skey => $svalue){ 
            
          for($j = 0; $j<count($svalue); $j++) {
            if(!empty($svalue[$j]->map_name)){
            $clueImage = $svalue[$j]->clue_image;
            $clueImage = (!empty($clueImage) && fopen($clueImage,'r'))?$clueImage:base_url().'assets/images/default-user.png';
            $gp = $this->db->query("select * from groups where id='".$svalue[$j]->group_id."'")->row_array();
            $ur = $this->db->query("select * from user where id='".$st_map_user_id[$j]."'")->row_array();
           // $locations1[] = array($svalue[$j]->map_name,$svalue[$j]->lat,$svalue[$j]->lon,'','',$gp['join_key'],'','staticmap',$svalue[$j]->user_id);
            array_push($locations,array($svalue[$j]->map_name,$svalue[$j]->lat,$svalue[$j]->lon,'','',$gp['join_key'],'','staticmap',$svalue[$j]->user_id));

            $browseloc = 'http://maps.google.com/maps?z=12&t=m&q=loc:'.$svalue[$j]->lat.'+'.$svalue[$j]->lon;

            $pro_pic   = $ur['profile_image'];
	   				$img       = (!empty($pro_pic) && fopen($pro_pic, "r"))?$ur['profile_image']:base_url().'assets/images/default-user.png';
  					 
          $str    = '<div class="map-locator">
                             <div class="map-pic clearfix">
    				             <div class="col-xs-4">
    								<img src="'.$img.'" class="img-responsive img-circle" alt="user-image" />
    							</div>';
                    if($this->data['user_info']['channel_id'] == $gp["join_key"]) {
                           $str .='<a onclick="invisible_participant('.$svalue[$j]->user_id.','.$gp['id'].',1)" class="visible_status">
                                    <span class="invisible_pp">
                                      <img src="'.base_url().'assets/images/invisible_participant_icon.png" target="_blank" alt="Invisible Participant" /> Invisible
                                    </span>
                                    </a>';
                    } 
                      
    				  $str   .=	'<div class="col-xs-8">
    								<p> <i class="fa fa-user pull-left" aria-hidden="true"></i><small> HMGPS User ID</small> '.$ur["default_id"].'</p>
    							    <p> <i class="fa fa-user pull-left" aria-hidden="true"></i> <small>Display Name: '.$ur['display_name'].'</p>
    							</div>
							</div>';
                         //   $str   .= '<img src="'.$clueImage.'" width="40" height="30" class="img-responsive img-circle" alt="seach-gps" />';
							
				         $str .='<div class="map-record clearfix">
                          <div class="col-xs-12 clearfix">
                             <p> <i class="fa fa-clock-o pull-left" aria-hidden="true"></i> <small>Position Time Updated</small></p>
			                        <p><<lastseen>></p>
    							         </div>
							    <div class="col-xs-3 clearfix">
                                  <p> <small>Speed</small>  </p>
                                </div>
                                <div class="col-xs-6 clearfix">
                                  <p> <small>Accuracy</small>  </p>
                                </div>
							   <div class="col-xs-12 clearfix">
                                  <p> <i class="fa fa-globe pull-left" aria-hidden="true"></i> <small>GPS Coordinate</small> '.$svalue[$j]->lat.', &nbsp;&nbsp;&nbsp;  '.$svalue[$j]->lon.' </p>
                                </div>
                               </div>';
                               $str .= ' <div class="map-action">
                                        <div class="map-buttons">
								        <span class="tool-tip" data-text="Navigate"><a href="'.$browseloc.'"><i class="fa fa-map-pin fa-2x" aria-hidden="true"></i></a></span>';
								
								if(is_numeric($ur["phonenumber"])){
								$str .='<span class="tool-tip" data-text="Call"><a href="tel:'.$ur["phonenumber"].'"><i class="fa fa-phone fa-2x" aria-hidden="true"></i></a></span>
							           <span class="tool-tip" data-text="SMS"><a href="sms:'.$ur["phonenumber"].'&body=Here\'s MyGPS" class="sms"><i class="fa fa-commenting fa-2x" aria-hidden="true"></i></a></span>';
							    }

						 		$str .='<span class="tool-tip" data-text="Email"><a href="mailto:?subject=Here\'s MyGPS&body=Hi, '.site_url('search/'.$gp["join_key"]).'"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i></a></span>';
                                $str .= '</div></div>';
                                $str .= ' <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                         <div class="btn-group text-center" role="group">';
							  //  $str .= '<button onclick="breadcrumb('.$svalue[$j]->user_id.');" class="btn btn-default btn-success" > OK </button></div>';
                                //$str .= '<div class="btn-group" role="group">
                                    //   <button onclick="clear_track()" class="btn btn-default btn-info">Clear Tracking</button>
                                   //    </div> 
                                 $str .=  '<div class="btn-group" role="group">
                                              <button  onclick="closeinfowindow()" class="btn btn-default btn-danger"> Close </button>
                                            </div>
							                </div>
                                           </div>';  
                            
             $contents[]      =   array($str,'') ;
            }              
          }      
        }
        
        if(is_array($locations1) && count($locations1)>0){
            $locations[] = $locations1;
        }
        
	   	  $this->data['user_id']             = $user_id;	
   		  $this->data['locations']           = json_encode($locations,JSON_PRETTY_PRINT);
   		  $this->data['contents']            = json_encode($contents);
        $this->data['breadcrumb_user']     = $breadcrumb_admin_user;
        $this->data['breadcrumb_timelimit']= 1;
        $this->data['breadcrumb_status']   = $breadcrumb_status;
        $this->data['user_type']           = $user_type;
        $this->data['type']                = $map_det['type'];
        $this->data['lat']                 = $map_det['lat'];
        $this->data['lon']                 = $map_det['lon'];
        $this->data['location_type']       = $map_det['location_type'];
        $this->data['join_key']            = $map_det['join_key']; 
        $this->data['description']         = $map_det['description']; 
        $this->data['dateupdate']          = $map_det['date_update']; 
        $this->data['pwd']                 = (isset($pwd) && !empty($pwd))?$pwd:"";
        $this->data['m']                   = $m;  

   		//ajax request rsponse
   		if($ajax==1){
   			   echo json_encode(array('user_id' => $user_id,'locations' => $locations,'contents' => $contents)); exit;
   		}
   		
        $userInfo = $this->data['user_info'];
        
        if(isset($_POST['search']) && ($_POST['search'] != $userInfo['joined_group'])){
     	  $this->get_user_details();
        }
        
        $this->user_joined_map($user_id,$joinkey);
       
        if(!empty($ajax) && ($ajax == 'all')){
           
		  $this->layout->view('search_map',$this->data);
        }
        else
        {
            $this->layout->view('search',$this->data);
        }
	}


	public function aboutus()
	{
		$this->layout->view('aboutus',$this->data);
	}


	public function help()
	{
		$this->layout->view('help',$this->data);
	}

	public function tellus()
	{
		$this->layout->view('tellus',$this->data);
	}
    
    
   function privacypolicy()
   {
      $this->layout->view('privacy_policy',$this->data);
   }
   
     //invisible participant 
    function invisible_participant()
    {
        //user id
        $user_id = (isset($_POST['user_id']))?$_POST['user_id']:"";
        $grp_id  = (isset($_POST['group_id']))?$_POST['group_id']:"";
        $visible = (isset($_POST['visible']))?$_POST['visible']:0;
        
        $this->db->query("update user_groups set is_visible='".$visible."' where group_id='".$grp_id."' and user_id='".$user_id."'");
        
        $this->data['visible'] = $visible;
        $output          = $this->load->view('visible_status',$this->data,true);
        echo json_encode(array("status" => "success",'msg' => $output),200);
    } 
    
	function get_user_details(){

		 $this->service_param['user_id']  = get_cookie('map_user_id');

		if(!empty($this->service_param['user_id'])){

			$userchk = $this->rest->get('user_exist_check', $this->service_param, 'json');

			$userchk = (array)$userchk;

			if(!empty($userchk) && $userchk['status']=='error')
			{
				delete_cookie('map_user_id');

				$this->service_param['user_id'] = 0;
			}	
		}

	 $join_key = get_cookie('map_search');

		if(!empty($this->service_param['user_id'])){
  		
			$this->service_param['join_key'] = $join_key;
			$userdet = $this->rest->get('get_channel_byuser', $this->service_param, 'json');

			$userdet = (array)$userdet;

			if(!empty($userdet) && $userdet['status']=='success'){	
				$this->data['user_info'] = array('channel_id' => $userdet['user']->channel_id, 'display_name'=>$userdet['user']->udispname,'phonenumber' => $userdet['user']->phonenumber , 'user_id'=>$userdet['user']->user_id,'group_id'=>$userdet['user']->group_id,'joined_group'=>$userdet['joined_group'],"updated_type" => $userdet['user']->updated_type, "updated_phonenumber" => $userdet['user']->updated_phonenumber);
			}
		}
		else
		{
			delete_cookie('map_search');
			$this->data['user_info'] = array('channel_id' => $this->random_channelid(), 'display_name'=>'','phonenumber' =>'' ,'user_id'=> '','group_id'=>'','joined_group'=>'',"updated_type" => 'system',"updated_phonenumber" => "system");
		}
	}
        
	function random_channelid(){
 	  

		$random_id = get_cookie('rand_channelid');

		$rand = $this->rest->get('random_number_generation', $this->service_param, 'json');

		$rand = (array)$rand;
        
		if(is_array($rand) && !empty($rand))
		{
			$cookie = array(
					'name' => 'rand_channelid',
					'value' => $rand['random_id'],
					'expire' => time()+20000,
					'path'   => '/',
					);
			set_cookie($cookie);

			$random_id = $rand['random_id'];

		}				
		
		return $random_id;

	}


	public function guest_registration()
	{
		$latlong = $this->input->post('latlon');
		$phone   = $this->input->post('phone');
		$display = $this->input->post('display');
    $type    = $this->input->post('type');
        
        $manual_address = '';
        if($type != 'current') {
            $manual_address = $latlong;
        }

		//$latlong = explode(":",$latlong);
        $latlong = (!empty($type) && ($type == 'current'))?explode(":",$latlong):convert_latlon($latlong);

		$outputs=array();

		if(count($latlong) <= 1){
			$latlong[0] = 'noloc';
			$latlong[1] = 'noloc';
		}
			

		$this->service_param['default'] 	= ($display)?$display:$phone;
		$this->service_param['email'] 		= '';
		$this->service_param['phonenumber'] = $phone;
		$this->service_param['password'] 	= '';
		$this->service_param['user_plan'] 	= 'guest';
		$this->service_param['profile_image']= '';
		$this->service_param['display_name']= ($display)?$display:$phone;
		$this->service_param['device_id']	= '';
        $this->service_param['login_type']	= 'website';
        $this->service_param['password_protect']= 0;
        $this->service_param['allow_deny']	= 0;
        $this->service_param['updated_type']	= 'system';
        $this->service_param['updated_phonenumber'] = 'system';
		

   		$register = $this->rest->get('user_register', $this->service_param, 'json');
   		$register = (array)$register;

   		if(!empty($register) && isset($register['status'])){

   			if($register['status']=='success'){
                                                                              
	   			//user position save                                                   
	   			$this->user_position_save($register['user_id'],$latlong[0],$latlong[1],$manual_address,$type,FALSE);
	   			

	   			$cookie = array(
					'name' => 'map_user_id',
					'value' => $register['user_id'],
					'expire' => time()+86400,
					'path'   => '/',
					);
				set_cookie($cookie);	

				$outputs['status']='success';
				$outputs['msg']   ='Successfully registerd as a guest or existing user!';
				$outputs['join_key']= (get_cookie('map_search')!='')?get_cookie('map_search'):'';

	   		}	
	   		else
	   		{
	   			$outputs['status']='error';
				$outputs['msg']   = $register['msg'];
	   		}

	   	}
	   	else
	   	{
	   		$outputs['status']='error';
			$outputs['msg']   = 'Bad request please try after some time! ';
	   	}	
		
	//	$this->get_user_details();
        
		echo json_encode($outputs);
		exit;	
	}
    

    function user_update_displayname()
    {
        $phone              = $this->input->post('phone'); 
		$display            = $this->input->post('display');
        $user_id            = $this->input->post('user_id');
        $updated_type       = $this->input->post('updated_type');
        $updated_phonenumber= $this->input->post('updated_phonenumber');
        $user_id            = (empty($user_id))?get_cookie('map_user_id'):$user_id;
        
        if($user_id==''){
            $user_data = $this->get_user_details();
            $user_id   = $user_data['user_id'];
        } 
       
        if(!empty($user_id)){
            //$upd = (!empty($phone))?"updated_phonenumber='custom'":"updated_phonenumber='system'";
            $qy    = 'update user set ';
            $qy   .= (!empty($display))?'display_name="'.$display.'",':"";
            $qy   .= (!empty($phone))?'phonenumber="'.$phone.'" ,':"";
            $this->db->query("$qy updated_type='".$updated_type."', updated_phonenumber='".$updated_phonenumber."' where id='".$user_id."'");
            //echo $this->db->last_query(); exit;
            $status = "success";
            $msg    = "Updated successfully";
        }
        else
        {
            $status = "error";
            $msg    = "Guest user not yet created!";
        }
        $outputs['status']=$status;
		$outputs['msg']   = $msg;
        echo json_encode($outputs);
		exit;
    }
    
    
    
	function user_update()
	{
	 
		$latlong     = $this->input->post('latlon');
		$phone       = $this->input->post('phone');
		$display     = $this->input->post('display');
		$group_id    = $this->input->post('group_id');
		$prev_channel= $this->input->post('prev_channel');

		$type        = $this->input->post('type');
        
        $manual_address = '';
        if($type != 'current') {
            $manual_address = $latlong;
        }
        
        $latlong     = (!empty($type) && ($type == 'current'))?explode(":",$latlong):convert_latlon($latlong);

		$outputs=array();

		
		$this->service_param['channel_id'] 	 = $phone;
		$this->service_param['display_name'] = $display; 			
		$this->service_param['group_id']     = $group_id;
		$this->service_param['user_id']      = get_cookie('map_user_id');

		$updat = $this->rest->get('update_groupanduser', $this->service_param, 'json');	
		
   		$updat = (array)$updat;

   		if(!empty($updat) && isset($updat['status'])){

   			if($updat['status']=='success'){

				$outputs['status']='success';
				$outputs['msg']   ='Channel details updated successfully';
				$outputs['join_key'] = (get_cookie('map_search')==$prev_channel)?$phone:'';

				if($latlong[0]!==null && $latlong[1]!==null){                                
				                                                                             
					$this->user_position_save(get_cookie('map_user_id'),$latlong[0],$latlong[1],$manual_address,$type,FALSE);
				}
					
	   		}	
	   		else
	   		{
	   			$outputs['status']='error';
				$outputs['msg']   = $register['msg'];
	   		}

	   	}
	   	else
	   	{
	   		$outputs['status']='error';
			$outputs['msg']   = 'Bad request please try after some time! ';
	   	}	
		
		

		echo json_encode($outputs);
		exit;	
	}                                              
                                                
	function user_position_save($user_id,$lat,$lon,$manual_address,$type,$resp=TRUE)
	{
		if((int)$user_id && $lat!='' && $lon!='')
		{                                 
                                    
			$this->service_param['user_id'] 	     = $user_id;
			$this->service_param['lat'] 		     = $lat;
			$this->service_param['lon'] 		     = $lon;
			$this->service_param['accuracy'] 	     = 0;
			$this->service_param['allow_empty']      = 1;
            $this->service_param['address_type']     = $type;                        
            if(!empty($manual_address)){
              $this->service_param['manual_address'] = $manual_address;  
                                       
            }                 

	   		$pos = $this->rest->get('user_position_save', $this->service_param, 'json');

	   		if($resp){
		   		echo json_encode($pos);
		   		exit;
	   		}
		}
	}

	public function create_group()
	{
		try{

			$latlong   = $this->input->post('latlon');
			$channel   = $this->input->post('channel');
			$display   = $this->input->post('display');
			$password  = $this->input->post('password');
			$type  	   = $this->input->post('type');
			$loc_type  = $this->input->post('loc_type');

			$outputs=array();


			$user_id = get_cookie('map_user_id');

			if(!(int)$user_id)
				throw new Exception("Please register and create map!");

			$latlong = explode(":",$latlong);

			$this->service_param['join_key'] 	= $channel;
			$this->service_param['name'] 		= $display;
			$this->service_param['description'] = $display;
			$this->service_param['password'] 	= $password;
			$this->service_param['type'] 	    = $type;
			$this->service_param['location_type']= $loc_type;
			$this->service_param['user_id']     = $user_id;
			$this->service_param['lat']			= $latlong[0];
			$this->service_param['lon']     	= $latlong[1];
			$this->service_param['map_avail']   = 'temporary';
			$this->service_param['is_available']= 1;
            $this->service_param['is_tracked']	= 1;

			
	   		$res = $this->rest->get('group_save', $this->service_param, 'json');
	   		$res = (array)$res;

	   		if(empty($res))
	   			throw new Exception('Service request failed!');

   			if($res['status']=='error')
   				throw new Exception($res['msg']);

   			$cookie = array(
				'name' => 'map_search',
				'value' => $res['join_key'],
				'expire' => time()+3600,
				'path'   => '/',
				);
			set_cookie($cookie);	  		

			$outputs['status']='success';
			$outputs['msg']   ='Group created successfully';
			$outputs['join_key'] =$res['join_key'];	

			
	   	}
	   	catch (Exception $e)
        {
        	$outputs['status']='error';
			$outputs['msg']   = $e->getMessage();
           
        } 	
		
		echo json_encode($outputs);
		exit;	
	}
    
    
    //check if user joined or not in searched map
    function  user_joined_map($user_id, $join_key)
    {
        
       	$this->service_param['join_key'] = $join_key;
        $this->service_param['user_id']  = $user_id;
        
        $map = $this->rest->get('check_user_joined_map', $this->service_param, 'json');
      
        $map = (array)$map;
        
		if(!empty($map) && $map['status']=='success'){	
			$this->data['maps'] = array('is_joined' => $map['is_joined'], 'group_id' => $map['group_id'], 'user_id' => $user_id);
		}
        
    }

   //delete group user 
   function delete_member($user_id,$group_id,$resp=TRUE) {
    
    if(!empty($user_id) && !empty($group_id)) {
        
        $this->service_param['group_id'] = $group_id;
        $this->service_param['user_id']  = $user_id;
        
        $map = $this->rest->get('delete_member', $this->service_param, 'json');
      
        $map = (array)$map;
        
        if($resp) {
    		if(!empty($map) && $map['status']=='success'){	
    		  $this->data['is_joined'] = 0;
    		   delete_cookie('map_search');
    		   echo 'success'; 
               exit;
    		}
       }
     } 
   }
   
   //get user manual address if address_type manual means
   function user_manual_address($user_id,$from)
   {
        if(!empty($user_id)) {
           $result    = $this->db->query("select id,manual_address from user_position where user_id='".$user_id."' and address_type='manual'")->row_array();
           
           $address= array();
           if(count($result)) {
              if($from == 'search') {
                return "yes";
              }
              else
              {
                 $address['status']  = 'success';
                 $address['address'] = $result['manual_address'];
                 
                 echo json_encode($address);
	             exit;
              }
           }
           else
           {
             if($from == 'search') {
                return "no";
             } 
             else
             {
                 $address['status']  = 'error';
                 $address['msg'] = "No manual address for this user";
                 
                 echo json_encode($address);
	             exit;
             }
           }   
        }
   }
   
   
   function breadcrumb($user_id='',$timelimit='')
   {
		
            //$user_id = $this->input->post('user_id');
            $this->service_param['user_id']     = "[".$user_id."]";
            $this->service_param['time_limit']  = $timelimit;
        
            $map = $this->rest->get('get_trigger_positions', $this->service_param, 'json');
          
            $map      = (array)$map;
            $position = (array)$map['positions']->$user_id;
            
            $pos_array= array();
            foreach($position as $pkey=>$pvalue){
                $pos_array[$pkey]['lon']        = $pvalue->lon;
                $pos_array[$pkey]['lat']        = $pvalue->lat;
                $pos_array[$pkey]['flag']       = $pvalue->flag;
                $pos_array[$pkey]['speed']      = $pvalue->speed;
                $pos_array[$pkey]['altitude']   = $pvalue->altitude;
                $pos_array[$pkey]['bearing']    = $pvalue->bearing;
                $pos_array[$pkey]['update_time']= $pvalue->update_time;
            }
            
    		if(count($pos_array)){
		   		echo json_encode($pos_array);
		   		exit;
	   		}
   }
   
   function allow_deny_restriction()
   {
     try {
              $join_key = $this->input->post('joinkey');
              $user_id  = $this->input->post('user_id');
              
          	  $this->service_param['join_key'] 	= $join_key;
              $this->service_param['user_id']   = $user_id;
              
          	  $res = $this->rest->get('allowdeny_send_notification', $this->service_param, 'json');
          	  $res = (array)$res;
              
              if(empty($res))
   			       throw new Exception('Service request failed!');

   			  if($res['status']=='error')
   				  throw new Exception($res['msg']);

                // if($res['status'] == 'success') {
//                    $this->service_param['join_key'] 	= $join_key;
//                    $this->service_param['user_id']     = $user_id;
//                    $this->service_param['cfrom']       = 'website';
//              
//          	        $res = $this->rest->get('user_join_request_map', $this->service_param, 'json');
//                    $res = (array)$res;
//                    
//                    $user = $this->db->query("select * from user where id='".$user_id."'")->row_array();
//                    
//                   
//                 }  
                $outputs['status']        = 'success';
                $outputs['msg']           = $res['msg'];
                $outpusts['request_type'] = $res['request_type'];
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
   
     //set user view or hide 
   function view_or_hide() {
    
    if(!empty($user_id) && !empty($group_id)) {
        
        $this->service_param['group_id'] = $group_id;
        $this->service_param['user_id']  = $user_id;
        
        $map = $this->rest->get('delete_member', $this->service_param, 'json');
      
        $map = (array)$map;
        
        if($resp) {
    		if(!empty($map) && $map['status']=='success'){	
    		  $this->data['is_joined'] = 0;
    		   delete_cookie('map_search');
    		   echo 'success'; 
               exit;
    		}
       }
     } 
   }
   
    function remove_user_from_all_groups()
   {
        
            $this->service_param['user_id']     = $this->input->post('user_id');
            $map = $this->rest->get('remove_user_from_all_groups', $this->service_param, 'json');
            $map = (array)$map;
            if($map['status'] == 'success'){
                $outputs['status']  ='success';
                $outputs['msg']     = "Removed user from all groups successfully";
                echo json_encode($outputs);
                exit;
            }
           
   }
   
}
