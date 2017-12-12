<?php 
    $key = ($map_search_key)?$map_search_key:$user_info['channel_id']; 
    $shareurl = base_url()."search/".$key; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport"  content="initial-scale=1.0, user-scalable=no" />
<meta charset="utf-8">
<meta name="ad.size" content="width=160,height=600" />
<meta property="og:url" content="<?php echo $shareurl; ?>"/>
<meta property="og:title" content="HMGPS"/>
<meta property="og:site_name" content="HMGPS" />
<meta property="og:description" content="View my location @ <?php echo $shareurl; ?>"/>
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Hmgps</title>
<!-- Bootstrap -->
<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<!--
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/social-buttons-share.css">
-->
 <link rel="icon" href="<?php echo site_url();?>assets/image/fevicon.ico" type="image/gif" sizes="16x16"> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="<?php echo base_url(); ?>/assets/js/lib/jquery.min.js?v1.12.0"></script>

<script src="<?php echo base_url();?>assets/js/show_position.js"></script>
<!--
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
-->


<script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBz9lLFL8ATbz-WsGewJcoSxQbEnpQI_JA&sensor=true"></script>
<!--
<script src="<?php //echo base_url();?>assets/js/infobox.js"></script>
-->
<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script src="<?php echo base_url();?>assets/js/lib/modernizr-2.8.3.js"></script>
<!--
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
-->
<script>
 var service_resp = <?php echo json_encode($service_resp);?>;
 var user_info = <?php echo json_encode($user_info);?>;
 
 var uid = '<?php echo $user_id; ?>';

 var site_url = '<?php echo site_url();?>';
 var base_url = '<?php echo base_url();?>';

 var  serchval = '<?php echo ($map_search_key)?$map_search_key:get_cookie("map_search"); ?>';

var popuptrigger = '<?php echo ($map_search_key)?$map_search_key:get_cookie("map_search"); ?>';
 (function() {
  geolocation();

 })();

      //var accuracy = position.coords.accuracy;
      //var speed = position.coords.speed;
      
 function showLocation(position) {

      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      $("#latlang").val(latitude + ":" + longitude);
      
      //if(typeof user_info === "object" && user_info['user_id']==''){
//        setTimeout(function(){ guest_registration('auto'); },3000);
//      }
  
}

function errorHandler(err) {

      if(err.code == 1) {
         //alert("Error: Access is denied!");
         $("#latlang").val(1);
      }
      else if( err.code == 2) {
         //alert("Error: Position is unavailable!");
         $("#latlang").val(2);
      }
      
      //if(typeof user_info === "object" && user_info['user_id']==''){
//        setTimeout(function(){ guest_registration('auto'); },3000);
//      }
   }

function geolocation() {

      if(navigator.geolocation){
         // timeout at 60000 milliseconds (60 seconds)
          var options = {
                        enableHighAccuracy: true,
                    	timeout:            30000,  // milliseconds (30 seconds)
                    	maximumAge:         600000 // milliseconds (10 minutes)
         };
         navigator.geolocation.getCurrentPosition(showLocation, errorHandler, options);
      }      
      else
      {
          //if(typeof user_info === "object" && user_info['user_id']==''){
//            setTimeout(function(){ guest_registration('auto'); },3000);
//          }
         $("#latlang").val(0); 
      }
}

if(typeof user_info === "object" && user_info['user_id']==''){
    setTimeout(function(){ guest_registration('auto'); },5000);
    setTimeout(function(){$("#display_pp").trigger("click");},9000);
}

 

//$(document).ready(function() {
//var man_addr = '<?php //echo $manual_address; ?>';
//if(man_addr == 'yes') {
//    $("#popover").trigger("click");
//    console.log("manual address");
//}
//});
</script>

</head>
<body>

    
   
     
 
 