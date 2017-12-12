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
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/social-buttons-share.css">
 <link rel="icon" href="<?php echo site_url();?>assets/image/fevicon.ico" type="image/gif" sizes="16x16"> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script> 
<!--
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
-->

<script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>
<script src="<?php echo base_url();?>assets/js/infobox.js"></script>
<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
<div class="overlaybg">&nbsp;</div>
<div class="loader"><img src="<?php echo base_url();?>assets/image/loader.gif" ></div>
<div class="container-fluid cf">
  <div class="row">
    <input  value="0" name="latlang" id="latlang" type="hidden">
    <input class="form-control" value="" name="map_pos" id="map_pos" type="hidden">
    
   
     
  <!-- Modal -->
  <div class="modal fade" id="searchshare" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Quick share - Current map</h4>
        </div>
        <div class="modal-body">
          <div id="searchmapshare">
             <div id="websitesearch_text"></div>
              <div class="sec-9 clearfix"> 
                  <a href="javascript:;" onclick="copyToClipboard('#search','#websitesearch_text')" >
                    <img src="<?php echo base_url();?>assets/image/copy.png"  class="img-responsive" alt="copy" />
                  </a>
               </div>

                <div class="sec-9 clearfix"> 
                  <a href="sms:?body=Hi, View my location on live map and join me at: <?php $grp = (!empty($map_search_key))?$map_search_key:get_cookie("map_search"); echo site_url('search/'.$grp);?>" class="sms"  >
                    <img src="<?php echo base_url();?>assets/image/sms.png"  class="img-responsive" alt="sms" target="_blank" />
                  </a>
               </div>
               <div class="sec-9 clearfix"> 
                  <a href="mailto:?subject=Here's MyGPS&body=Hi, View my location on live map and join me at: <?php $grp = (!empty($map_search_key))?$map_search_key:get_cookie("map_search"); echo site_url('search/'.$grp);?> " target="_blank" >
                    <img src="<?php echo base_url();?>assets/image/email_send.png"  class="img-responsive" alt="email" />
                  </a>
               </div>   
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="mobilesearchshare" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Quick share - Current map</h4>
        </div>
        <div class="modal-body">
          <div id="searchmapshare_mobile">
           <div id="mobilesearch_text"></div>
            <div class="sec-9 clearfix"> 
                  <a href="javascript:;" onclick="copyToClipboard('#search','#mobilesearch_text')" >
                    <img src="<?php echo base_url();?>assets/image/copy.png"  class="img-responsive" alt="copy" />
                  </a>
               </div>
                <div class="sec-9 clearfix"> 
                  <a href="sms:?body=Hi, View my location on live map and join me at: <?php $grp = (!empty($map_search_key))?$map_search_key:get_cookie("map_search"); echo site_url('search/'.$grp);?>" class="sms"  >
                    <img src="<?php echo base_url();?>assets/image/sms.png"  class="img-responsive" alt="sms" target="_blank" />
                    
                  </a>
               </div>
               <div class="sec-9 clearfix"> 
                  <a href="mailto:?subject=Here's MyGPS&body=Hi, View my location on live map and join me at: <?php $grp = (!empty($map_search_key))?$map_search_key:get_cookie("map_search"); echo site_url('search/'.$grp);?> " target="_blank" >
                    <img src="<?php echo base_url();?>assets/image/email_send.png"  class="img-responsive" alt="email" />
                    
                  </a>
               </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
  <div class="modal fade" id="mobilechannelshare" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Quick share - Current map channel </h4>
        </div>
        <div class="modal-body">
          <div id="socialquickshare_mobile">
            <div id="mobilechannel_text"></div>
             <div class="sec-9 clearfix"> 
                  <a href="javascript:;" onclick="copyToClipboard('#phone','#mobilechannel_text')"  >
                    <img src="<?php echo base_url();?>assets/image/copy.png"  class="img-responsive" alt="copy" />
                  </a>
               </div>

                <div class="sec-9 clearfix"> 
                  <a href="sms:?body=Hi, View my location on live map and join me at: <?php $grp = (!empty($user_info['channel_id']))?$user_info['channel_id']:""; echo site_url('search/'.$grp);?>" class="sms"  >
                    <img src="<?php echo base_url();?>assets/image/sms.png"  class="img-responsive" alt="sms" target="_blank" />
                    
                  </a>
               </div>
               <div class="sec-9 clearfix"> 
                  <a href="mailto:?subject=Here's MyGPS&body=Hi, View my location on live map and join me at: <?php $grp = (!empty($user_info['channel_id']))?$user_info['channel_id']:""; echo site_url('search/'.$grp);?> " target="_blank" >
                    <img src="<?php echo base_url();?>assets/image/email_send.png"  class="img-responsive" alt="email" />
                    
                  </a>
               </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>