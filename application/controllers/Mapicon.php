<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapicon extends CI_Controller {

	function __construct()
    {
        // Construct our parent class
        parent::__construct();
    }

	public function index($text,$type=1,$user_type)
	{
		$x = 82;
		$y = 32;
		$padding=0;

        $text = str_replace("%20"," ",$text);  
         
		if (strlen($text) > 8)
        $text = substr($text, 0, 8);
        
		$finalImage = imagecreate($x,$y) or die('Cannot Initialize new GD image stream');

		//$white = imagecolorallocatealpha($finalImage, 255, 255, 255, 127);
		//imagefill($finalImage, 0, 0, $white);
		//imagefill($finalImage,0,0,0x7fff0000); // this is suppose to make it transparent
        
        if($user_type!='public'){
         $image = ((!empty($user_type)) && ($user_type == 'admin'))?ASSETSPATH.'/image/violet-icon.jpg':ASSETSPATH.'/image/image.png';
        }
        else
        {
            $image = ASSETSPATH.'/image/orange-icon.jpg';
        } 
        
	     $img   = array(ASSETSPATH.'/image/red-icon.jpg',$image);
		$originalImage = ImageCreateFromPNG($img[$type]);
		imagecopymerge($finalImage,$originalImage,($padding/2),($padding/2),0,0,$x,$y,100);
		// final, Original, final x, final y, Original x, Original y, Source width, Source height, **ptc

		$text_color = imagecolorallocate($finalImage,255, 255, 255); // color of text
		//imagefill($finalImage,0,0,0x7);
		imagestring($finalImage,9, 2, 2, $text, $text_color); // finalImage, font size, left, top, TEXT, color
		imagepng($finalImage);
		imagedestroy($finalImage);
		header("Content-type: image/png");
	}
}	