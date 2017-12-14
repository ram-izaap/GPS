<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['api_url']    = 'http://heresmygps.com/service/';







//Include local settings if available
if ( ($file=safe_include("config/settings.local.php", false)) ) {
    require $file;
}