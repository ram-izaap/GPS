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
		$searchKey = '';

		//customPrint($this->data['user_info']);
		$this->data = array_merge($this->data, $userInfo);

		$this->data['search_key'] = $searchKey;

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

       	//prepare my map display string part
       	$mapDispStr = "Return to <small>My Map</small>";
       	if( $searchKey == '' )
       	{
       		$mapDispStr = "View <small>My Map</small>";
       	}
       	else if( $searchKey == $userInfo['channel_id'] )
       	{
       		$mapDispStr = "My <small>Map</small>";
       	}

       	$this->data['map_disp_str'] = $mapDispStr;

		$this->layout->view('desktop/home/index', $this->data);
	}

	
}
