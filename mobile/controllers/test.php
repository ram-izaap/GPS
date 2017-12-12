<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

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
        
     
     
    }

	public function index()
	{
	   $this->load->view('custom_ads',$this->data);
	//	$this->service_param['user_id'] = 809;
		/*
		$userchk = $this->rest->get('user_exist_check', $this->service_param, 'json');

		$userchk = (array)$userchk;
		print_r($userchk);
		*/
	  //  $this->service_param['join_key'] = 'miranda';
            
          //  print_r($this->service_param); exit;
   	//	$map_det = $this->rest->get('search_map', $this->service_param, 'json');
   		//echo "<pre>";
   	//	print_r($map_det);
	}
}
