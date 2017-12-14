<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	
	public function index()
	{
		echo $this->config->item('api_url');
		die(":::JJ");//$this->load->view('welcome_message');
	}

	
}
