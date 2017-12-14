<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}


	public function sample()
	{
		$data = array(2,3,4,5);

		return $data;
	}

	
}
