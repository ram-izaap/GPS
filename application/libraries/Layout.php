<?php
/**
 * Layout class
 *
 * Help to form a layout of application.
 *
 */
class Layout {

	
	protected $data = array();
	private $CI;
	
	function __construct()
	{
		$this->CI = & get_instance();
	}
	
	function view($view_name = '', $data = array())
	{
       // print_r($data);
		$this->data = array_merge($this->data, $data);
		

		if ( $this->CI->agent->is_mobile()  ) 
		{
			$this->CI->load->view('mobile/_partials/header', $this->data);

      		$this->CI->load->view($view_name, $this->data);

		    // $this->data['mobile_header'] = 'yes';
      		$this->CI->load->view('mobile/_partials/footer', $this->data);
        }    
        else
        {   	
        	//echo "ddssd"; exit;
        	$this->CI->load->view('_partials/header', $this->data);
        	
        	$this->CI->load->view($view_name, $this->data);           
            
            $this->data['mobile_header'] = 'no';
			
			$this->CI->load->view('_partials/side_panel', $this->data);

			$this->CI->load->view('_partials/footer', $this->data);
        }

		
	}
}

/* End of file layout.php */
