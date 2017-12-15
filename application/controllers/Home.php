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

       	

       	$params = array('user_id' => $userInfo['user_id'], 'channel_id' => $userInfo['channel_id']);
		$resp = $this->rest->get('group_info', $params, 'json');
		
		if( is_object($resp) && $resp->status == 'success' )
		{
			$this->data['visible'] = $resp->data->is_view;
		}

		$this->data['user_info'] = $this->data;

		$this->layout->view('desktop/home/index', $this->data);
	}

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
		customPrint((array)$resp);
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
}
