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
		$userInfo = $this->getUserInfo(80617);

		//customPrint($this->data['user_info']);
		$this->data = array_merge($this->data, $userInfo);

		$this->data['search_key'] = '';

		$this->data['user_action'] = ( (int)$userInfo['user_id'] ) ? 'user_update':'guest_registration';
		$uri      = $this->uri->segment(1);
       	$page     = ($uri == 'search' || $uri!='help' || $uri!='tellus' || $uri!='privacy-policy-and-terms-and-conditions')?"search-page":"";

       	$this->data['uri'] 	= $uri;
       	$this->data['page'] = $page;

       	$key = $userInfo['channel_id'];
       	if( $this->data['search_key'] )
       	{
       		$key = $this->data['search_key'];
       	}
       	$this->data['shareurl'] = base_url()."search/".$key;


		$this->layout->view('desktop/home/index', $this->data);
	}

	
}
