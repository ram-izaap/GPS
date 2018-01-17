<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'AppController.php';

class Home extends AppController {

	public function __construct()
	{
		parent::__construct();
		
		$this->data['back_url'] = $this->back_url;
	}
	
	public function index()
	{
		$this->getCommonData();

		//customPrint($this->data);

		$this->layout->view( $this->viewPath.'/home/index', $this->data);
	}


	function guestRegistration()
	{
		$lat 			= $this->input->post('lat');
		$lng 			= $this->input->post('lng');
		$phone_number 	= $this->input->post('phone_number');
		$display_name 	= $this->input->post('display_name');
		$reg_type 		= $this->input->post('reg_type');

		$params = array();
		$params['default'] 			= $display_name;
		$params['email'] 			= '';
		$params['phonenumber'] 		= $phone_number;
		$params['password'] 		= '';
		$params['user_plan'] 		= 'guest';
		$params['profile_image']	= '';
		$params['display_name']		= $display_name;
		$params['device_id']		= '';
        $params['login_type']		= 'guest';
        $params['password_protect']	= 0;
        $params['allow_deny']		= 0;
        $params['updated_type']		= 'system';
        $params['updated_phonenumber'] = 'system';


        $resp = $this->rest->get('user_register', $params, 'json');

        $output = array();
        if( is_object($resp) && $resp->status == 'success' )
        {
        	$params = array();
			$params['user_id'] 	     = $resp->user_id;
			$params['lat'] 		     = $lat;
			$params['lon'] 		     = $lng;
			$params['accuracy'] 	 = 0;
			$params['allow_empty']   = 1;
            $params['address_type']  = $reg_type;

            // if(!empty($manual_address)){
            //   $params['manual_address'] = $manual_address;                             
                            
            // }     

        	$response = $this->rest->get('user_position_save', $params, 'json');
        	if( is_object($resp) && $resp->status == 'success' ){}
        	
        	$this->setUserID( $resp->user_id );
        	$this->setJoinKey( $resp->default_id);

        	$output['status']		= 'success';
			$output['msg']   		= 'Successfully registerd as a guest user!';
			$output['join_key'] 	= $display_name;
			$output['user_info']   	= $this->getUserInfo( $this->userID );
        }
        else
        {
        	$output['status'] 	= 'error';
        	$output['msg'] 		= 'Bad request please try after some time!';
        }

        echo json_encode($output);die;
	}


   // convert manual address to latlong
   public function convertLatLong()
   {
       $address     = $this->input->post('address');
       $geolocation = convert_latlon($address);
       
       if(is_array($geolocation)){
           $latlong = $geolocation[0].":".$gelocation[1];
           $res     = array("status" => "success", "latlong" => $latlong);
       }
       else
       {
           $res     = array("status" => "error", "msg" => "Please Enter correct address!");
       }
       
       echo json_encode($res);
       exit;
   }


	// STATIC PAGES
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


	function send_email()
	{

     	$name 			= $this->input->post('name');
		$from 			= $this->input->post('email');
		$message 	    = $this->input->post('message');
		$to             = "";
		$subject        = "GPS";

        $this->load->library('email'); 

        $this->email->set_newline("\r\n");

        $this->email->set_header('MIME-Version', '1.0; charset=utf-8'); 

        $this->email->set_header('Content-type', 'text/html');          

        $this->email->from($from, $name); 

        $this->email->to($to);

        $this->email->subject($subject); 

        $this->email->message($message); 

        //$this->email->send();
         if($this->email->send())
         {
            ?>
            <script type="text/javascript">alert("Email Send Successfully");</script>
            <?php
         }
        else
        {
          ?>
          <script type="text/javascript">alert("Email Not Send");</script>
          <?php
        }
    }

	


}


