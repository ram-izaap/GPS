<?php 


function is_logged_in()
{
    $CI = get_instance();
    
    $user_data = get_user_data();
    
    if( is_array($user_data) && $user_data )
        return TRUE;

    return FALSE;

}

function get_user_data()
{
    $CI = get_instance();
    
    
    if($CI->session->userdata('user_data'))
    {
        return $CI->session->userdata('user_data');
    }
    else
    {
        return FALSE;
    }
}

function get_user_role( $user_id = 0 )
{
    $CI= & get_instance();
    
    if(!$user_id) 
    {
        $user_data = get_user_data();
        return $user_data['role'];
    }   
    
    $CI->load->model('user_model');
    $row = $CI->user_model->get_where(array('id' => $user_id))->row_array;

    if( !$row )
        return FALSE;

    return $row['role'];
}

 
function str_to_lower($str) {
    $ret_str ="";
    
    $ret_str = strtolower($str);
    
    return $ret_str;
}

function convert_latlon($address)
{
     
    
    $address = str_replace(",","",$address);
    $address = str_replace(" ","+",$address);
   
    $geolocation = array(); 
     
    $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $response_a     = json_decode($response);
    $geolocation[0] = $response_a->results[0]->geometry->location->lat;
    $geolocation[1] = $response_a->results[0]->geometry->location->lng;
    
    return $geolocation;
    
    
}

function customPrint( $data = array(), $die = TRUE )
{
    echo '<pre>';
    print_r($data);
    if( $die ) die;
} 
