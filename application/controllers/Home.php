<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'AppController.php';

class Home extends AppController {

	
	public function index()
	{
		echo $this->config->item('api_url');
        echo '<pre>';print_r($this->sample());
		die(":::JJ");//$this->load->view('welcome_message');
	}

	
}
