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
     
    }

	public function index()
	{
		$this->layout->view('index',$this->data);
	}

	public function search($search='')
	{

		try{


			$joinkey='';
			$user_id=0;
			
			$locations=array();

	   		$contents = array();

			$map_cookie = get_cookie('map_search');

			if(isset($_POST['search'])){
				$joinkey = $this->input->post('search');

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

			$user_id=get_cookie('map_user_id');

			//if(!$user_id)
				//throw new Exception("Please register with guest and continue!.");

			$this->rest->initialize(
				array('server' => $this->api_url,
			        'http_auth' => 'basic'
		    	)
			);

			$this->service_param['user_id']  = $user_id; 

			if((int)$user_id){				
				$cookie = array(
						'name' => 'map_user_id',
						'value' => $user_id,
						'expire' => time()+99000,
						'path'   => '/',
						);
				set_cookie($cookie);

				$userchk = $this->rest->get('user_exist_check', $this->service_param, 'json');

				$userchk = (array)$userchk;

				if(!empty($userchk) && $userchk['status']=='error')
				{
					delete_cookie('map_user_id');

					$this->service_param['user_id']  = $user_id = 0;
				}	
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

		    $this->service_param['join_key'] = $joinkey;

	   		$map_det = $this->rest->get('search_map', $this->service_param, 'json');

	   		$map_det = (array)$map_det;

	   		if(empty($map_det))
	   			$map_det['status']='';

	   		$service_status = array('status'=>$map_det['status'],'message'=>'');

	   		if(!isset($_POST['search']) && $joinkey=='')
	   			$service_status['status']='';

	   		if($map_det['status']=='error')
	   			$service_status['message'] = $map_det['msg'];

	   		$this->data['service_resp'] = $service_status;


	   		if(isset($map_det['status']) && $map_det['status']=='success' && isset($map_det['members']) && !empty($map_det['members'])){

	   			foreach($map_det['members'] as $val){

	   				if($val->user->profile->flag==0)
	   					continue;

	   				$lastseen = date('d-m-Y H:i',$val->user->group->last_seen_time);

	   				$currtime = date('d-m-Y H:i',strtotime('-1 hour'));

	   				$lastup = 1;
	   				if(strtotime($currtime) >= strtotime($lastseen))
	   				{
	   					if($val->user->profile->user_type=='member')
	   						continue;

	   					$lastup = 0;
	   				}	

	   				$displayname = $val->user->profile->display_name;

	   				if($val->user->profile->user_type=='admin')
	   					$displayname = $val->user->group->description;
	   					
	   				$locations[] = array($displayname,$val->user->position->lat,$val->user->position->lon,$lastup);
	   				
	   				$img = base_url().'assets/image/default-user.png';

	   				if($val->user->profile->profile_image !='' && file_exists($val->user->profile->profile_image))
	   					$img = $val->user->profile->profile_image;

	   				$browseloc = 'http://maps.google.com/maps?z=12&t=m&q=loc:'.$val->user->position->lat.'+'.$val->user->position->lon;

	   				$str = "<div class='map-info-img'><img src='".$img."'></div>";

	   				$str .= "<div class='map-info-cont'><span><strong>".$displayname."</strong></span><br/>
	   						<span>Accuracy: ".$val->user->position->accuracy."</span><br/>
	   						<span>Last Seen : ".date('h:i a D d-m-Y',$val->user->group->last_seen_time)."</span><br/>
	   						</div><div class='clear'>&nbsp;</div><div class='browseimg'><img src='".base_url().'assets/image/brows-icon.png'."'></div><div class='browseloc'><a href='".$browseloc."' target='_blank'>Browse Location</a></div>";

	   				$contents[] = $str;		
	   			}

	   			//update current active group
	   			if((int)$user_id && $joinkey!=''){

	   				$this->service_param['user_id']  = $user_id;
	   				$this->service_param['group_id'] = $joinkey;
	   			    $res = $this->rest->get('user_current_group_active', $this->service_param, 'json');
	   			}	
	   		}	
	   	}
	   	catch (Exception $e)
        {
            $service_status = array('status'=>'error','message'=>$e->getMessage());
            $this->data['service_resp'] = $service_status;
        } 
	   	$this->data['user_id'] = $user_id;	
   		$this->data['locations'] = json_encode($locations,JSON_PRETTY_PRINT);
   		$this->data['contents'] = json_encode($contents);

   		//print_r($this->data);exit;
     
		$this->layout->view('search',$this->data);
   		
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
	public function guest_registration()
	{
		$latlong = $this->input->post('latlon');
		$phone   = $this->input->post('phone');
		$display = $this->input->post('display');
		$password = $this->input->post('password');

		$latlong = explode(":",$latlong);

		$outputs=array();

		if($latlong[0]!==null && $latlong[1]!==null){

			$this->service_param['default'] 	= $phone;
			$this->service_param['email'] 		= '';
			$this->service_param['phonenumber'] = $phone;
			$this->service_param['password'] 	= $password;
			$this->service_param['user_plan'] 	= 'guest';
			$this->service_param['profile_image']= '';
			$this->service_param['display_name']= $display;
			$this->service_param['device_id']	= '';
			
			$this->rest->initialize(
				array('server' => $this->api_url,
			        'http_auth' => 'basic'
		    	)
			);

	   		$register = $this->rest->get('user_register', $this->service_param, 'json');
	   		$register = (array)$register;

	   		if(!empty($register) && isset($register['user_id'])){

	   			//user position save
	   			$this->user_position_save($register['user_id'],$latlong[0],$latlong[1],FALSE);

	   			$cookie = array(
					'name' => 'map_user_id',
					'value' => $register['user_id'],
					'expire' => time()+20000,
					'path'   => '/',
					);
				set_cookie($cookie);	

				$outputs['status']='success';
				$outputs['msg']   ='Successfully registerd as a guest or existing user!';
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
			$outputs['msg']   = 'Geolocation not available!';
		}

		echo json_encode($outputs);
		exit;	
	}

	function user_position_save($user_id,$lat,$lon,$resp=TRUE)
	{
		if((int)$user_id && $lat!='' && $lon!='')
		{
			$this->rest->initialize(
				array('server' => $this->api_url,
			        'http_auth' => 'basic'
		    	)
			);

			$this->service_param['user_id'] 	= $user_id;
			$this->service_param['lat'] 		= $lat;
			$this->service_param['lon'] 		= $lon;
			$this->service_param['accuracy'] 	= 0;

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


			$user_id=get_cookie('map_user_id');

			if(!(int)$user_id)
				throw new Exception("Please signup and create map!");

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

			$this->rest->initialize(
				array('server' => $this->api_url,
			        'http_auth' => 'basic'
		    	)
			);

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
}
