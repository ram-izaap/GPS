<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>911 GPS</title>
    
    <script src="<?php echo base_url();?>assets/js/lib/modernizr-2.8.3.js"></script>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet" />
    
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo base_url();?>assets/js/lib/jquery.min.js?v1.12.0"></script>
    
    
    <script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClXC4q5fI1C8arpQ22WVOwvtsfh9a-eLw"></script>
    <script>
     var service_resp = <?php echo json_encode($service_resp);?>;
     var user_info    = <?php echo json_encode($user_info);?>;
     
     var uid = '<?php echo $user_id; ?>';

     var site_url  = '<?php echo site_url();?>';
     var base_url  = '<?php echo base_url();?>';
     var  serchval = '<?php echo ($map_search_key)?$map_search_key:get_cookie("map_search"); ?>';
     var popuptrigger = '<?php echo ($map_search_key)?$map_search_key:get_cookie("map_search"); ?>';
     
     (function() {
      geolocation();
    
     })();
     
    
          //var accuracy = position.coords.accuracy;
          //var speed = position.coords.speed;
          var latitude = '', longitude = ''; 
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
    
    <style>
	.martop{ margin-top:10px !important;}
	.gm-style-iw + div {left: 10px;}

  /*.gm-style-iw + div { height:30px !important; width:30px !important; border:1px solid red;}
  .gm-style-iw + div:before { content:"x"; font-weight:bold; font-size:18px; padding:0 10px }*/
  .gm-style-iw + div, 
  .gm-style-iw + div img { display: none}

  [data-role="close"] {
    background: red none repeat scroll 0 0;
    border: 1px solid red;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    height: 20px;
    left: 0;
    line-height: 10px;
    padding: 5px;
    position: absolute;
    text-align: center;
    top: 0;
    width: 20px;
}
</style>

<script src="<?php echo base_url();?>assets/js/geolocationmarker-compiled.js"></script>
<script src="<?php echo base_url();?>assets/js/show_position.js"></script>

  </head>
  <body>

 <?php 
      $search_val     = ($map_search_key)?$map_search_key:get_cookie('map_search'); 
      $rand_channelid =  (isset($user_info['channel_id']))?$user_info['channel_id']:'';
      $display_name   =  (isset($user_info['display_name']) && $user_info['display_name']!='')?$user_info['display_name']:$rand_channelid;
      $phonenumber    =  (isset($user_info['phonenumber']) && $user_info['phonenumber']!='')?$user_info['phonenumber']:"";
      $group_id       =  (isset($user_info['group_id']) && $user_info['group_id']!='')?$user_info['group_id']:'';
      $updated_type   =  (isset($user_info['updated_type']) && ($user_info['updated_type']))?$user_info['updated_type']:"";
      $updated_phonenumber = (isset($user_info['updated_phonenumber']) && ($user_info['updated_phonenumber']))?$user_info['updated_phonenumber']:"";
      $usrchk = ($user_info['user_id']!='')?'user_update':'guest_registration';
?>
    <input name="joined_map" id="joined_map" type="hidden" />
    <input name="phone" id="phone" type="hidden" />
<div id="slideNavigation_mobile" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('slideNavigation_mobile')">&times;</a>
  <a href="<?php echo base_url(); ?>">Home</a>
 <!--
 <a href="<?php //echo base_url(); ?>">About</a>
-->
  <a href="<?php echo base_url(); ?>search/<?php echo $search_val; ?>">Search</a>
  <!--
<a href="#">Share</a>
  <a href="#">Contact</a>
-->
</div>

    <header class="clearfix">
      <div class="col-xs-6">
        <div class="row">
        <div class="col-xs-3">
          <span class="nav-ico" onclick="openNav('slideNavigation_mobile','mobile')">&#9776;</span>
        </div>
        <div class="col-xs-9">
            <div class="logo-small">
              <a href="<?php echo base_url();?>">
                <img src="<?php echo base_url();?>/assets/images/logo-small.png" class="img-responsive" alt="" />
              </a>
            </div>
        </div>
        </div>
      </div>
      <div class="col-xs-2 text-center viewstatus">
        <div class="top-visible" id="visiblestatus">
         <?php $this->load->view('visible_status',$this->data); ?> 
        </div>
      </div>
      <div class="col-xs-2 text-center viewmymapstatus">
        <div class="top-my-map">      
                    <?php $uri = $this->uri->segment(1); if(((!empty($rand_channelid)) && ($search_val != $rand_channelid) && empty($search_val)) || ($uri != 'search')) { ?>
            <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>"><b>View</b> My Map
            <img src="<?php echo base_url();?>/assets/images/view-map.png" class="" alt="" />
            </a>
            <?php } else if(!empty($search_val) && ($search_val != $rand_channelid)) { ?>
             <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" > <span>Return to My Map</span>
              <img src="<?php echo base_url();?>/assets/images/view-map.png" class="" alt="" />
             </a>
             <?php }else if($search_val == $rand_channelid){?>
            <a href="<?php echo site_url();?>search/<?php echo $rand_channelid;?>" > 
               <span>My Map</span>
                <img src="<?php echo base_url();?>/assets/images/view-map.png" class="" alt="" />
            </a>
            <?php } else{}?>
        </div>
      </div>
      <div class="col-xs-2 text-center exitallmap">
        <div class="top-exit">
         <a onclick="remove_user_from_all_groups()">
          <span>Exit</span>
          <img src="<?php echo base_url();?>/assets/images/exit-map.png" class="" alt="" />
          </a>
        </div>
      </div>
    </header>

  <div class="top-search-wrap clearfix">
    <form method="post" action="<?php echo base_url('search'); ?>">
    <div class="search-full">
        <span class="dropdown search-filter pull-left">
          <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="search-by">Search By  <span class="caret"></span>
          <span class="data-name">Channel ID</span></a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="javascript:void(0);" data-name="Channel ID">Channel ID</a></li>
          </ul>
        </span>
        
        <div class="pass-activate<?php if(!empty($pwd)){ ?>pass-activate <?php }else{?>pass-deactivate<?php } ?>" id="pprotect">
        <span class="search-wrap top-search pull-left">
          <input type="text" value="<?php echo $search_val;?>" name="search" id="search" class="form-control" placeholder="Enter Channel ID" />
        </span>
        <span class="search-wrap pass-search pull-left">
           <input type="password" name="pwwd" id="pwd" value="<?php echo $pwd; ?>" class="form-control" placeholder="password" />
        </span>
        </div>
        <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left">
          <input type="submit" value="&nbsp;" class="go-search subm" id="allow_dy" />       
        </span>
        
        </div>
        <div class="password-search" style="display:none;">
         
         <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left"  >
            <input type="submit" value="" class="go-search subm pass_protect"  /> 
        </span>
        <span class="top-clear-icon tool-tip" data-text="Clear" data-color="green" data-position="left">      
          <input type="reset" value="&nbsp;" class="pull-left" />
        </span>
        </div>
        
        
      </form>
  </div> 
<?php //print_r($locations); ?>
  <div class="map-viewer <?php if(count($locations)== 0){echo "noparticipant"; } ?>">
    <!-- Map Layer -->
    <div class="map-layer-top clearfix">
      <!-- <span class="cMapid"> Current Map ID is <i></i> <b><?php // echo $search_val;?></b> </span> -->
      <div class="m-channel-info">
        <span> Display Name: <b><?php echo $display_name; ?></b></span>
        <span> Map id: <b><?php echo $display_name; ?></b></span>
      </div>
    
         <span class="mobile--share">
           <a  data-toggle="modal" data-target="#searchshare_mb">
             <i class="fa fa-share-alt fa-2x"></i></a>
          </span> 


      <!-- <span class="mobile--back pull-right">
        <a href="<?php echo base_url(); ?>"><i class="fa fa-arrow-left"></i> Back </a>
      </span> -->
      <!-- <span class="top-share pull-right"> 
        <a data-toggle="modal" data-target="#searchshare">
          <i class="fa fa-share-alt fa-2x"></i>
        </a>
      </span> -->

      <style type="text/css">
        .map-layer-top {
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 3px;
            background: #fff;
        }

        .g__map--id {
           font-size:12px; 
        }

        .mobile--share i {
          color:#F06D06;
          font-size:16px;
        }

        .mobile--back {
              padding: 0 5px;
        }
 


      </style>
    </div>
    <div id="latlang" style=""></div>
      
      <div class="col-xs-4 timer">
          <div class="pull-right">
            <span class="btn btn-default timer-btn">
              <h2 id="re-load"><time>00:00</time></h2>
              <button style="display:none;" id="start">start</button>
              <button style="display:none;" id="stop">stop</button>
              <button style="display:none;" id="clear">clear</button>
              <i class="fa fa-repeat refresh-clk" aria-hidden="true"></i>
            </span>
          </div>
        </div>          
    <div id="map" style="width:100%; height: 600px;">
        
    </div>
  </div>
  

  <div class="map-layer-bottom">
      <div class="conta-iner clearfix">
   
         
          <div class="col-xs-4">
            <div class="drop-wrap btn-participant timer-btn">
            <?php  if($m > 0){ ?>
              <div class="dropup">
                <button class="btn btn-default dropdown-toggle parti" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Participants (<span id="count-participants">0</span>) <span class="caret"></span>
                </button>
                 <div class="dropdown-menu" id="participants-list">
                  <a class="close_pop" onclick="add_toggle();">CLOSE</a>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#show-1">
                            <i class="fa fa-users" aria-hidden="true"></i> Visible
                            </a>
                        </li>
            
                        <li>
                            <a data-toggle="tab" href="#show-2">
                            <i class="fa fa-users" aria-hidden="true"></i> Invisible </a>
            
                       </li>
            
                      <li><a data-toggle="tab" href="#show-3">
            
                      <i class="fa fa-users" aria-hidden="true"></i> Clues
            
                    </a></li>               
            
                 </ul>
                 <div class="tab-content">
                    <div id="show-1" class="tab-pane fade in active ">
                     <div class="" aria-labelledby="dropdownMenu2" id="tab1"></div>
                    </div>
                    <div id="show-2" class="tab-pane fade">
                    <div class="" aria-labelledby="dropdownMenu2" id="tab2"></div>
                    </div>
                    <div id="show-3" class="tab-pane fade">
                      <div class="" aria-labelledby="dropdownMenu2" id="tab3"></div>
                    </div>
                  </div>
                  
                 </div>
              </div>   
              <?php } ?>      
            </div>  
        </div>
          <div class="col-xs-4 map-sel" style="text-align: center;">
            <!--
            <span>
             <input type="radio" name="show-hide" id="hide-poi" class="selector-control" />
              <label for="hide-poi" style="color: #fff;">Default</label>
            </span>
          -->
            <span>
            <input type="radio" name="show-hide" id="show-poi" class="selector-control"  />
            <label for="show-poi" style="color: #fff;">Night</label>
            </span>
            </div>

       
      </div>
    </div>
</div>

 <div class="modal fade" id="searchshare_mb" style="display: none;" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Quick share - Current map</h4>
        </div>
        <div class="modal-body">
            <div id="sharemap_mobile">
               
            </div>
            <div class="sec-9">
                <input name="joined_map" id="joined_map" type="hidden" />
                 <label>Current Joined Map ID</label>
                 <input type="radio" name="share_map_type_search" class="share_mp_type_sr" onclick="share_map('<?php echo $map_search_key; ?>');" value="<?php echo $map_search_key; ?>" /><?php echo $map_search_key; ?>
                 <br />
                 <label>My Map</label>
                 <input type="radio" name="share_map_type_search" class="share_mp_type_sr" onclick="share_map('<?php echo $rand_channelid; ?>');" value="<?php echo $rand_channelid; ?>" /><?php echo $rand_channelid; ?>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="error_throw">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Error</h4>
      </div>
      <div class="modal-body">
        <div style="color:#a94442;"><?php echo $service_resp['message']; ?></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="shr_mp_content" style="display: none;">
    <div id="websitesearch_text"></div>
      <div class="sec-9 clearfix"> 
          <a href="javascript:;" onclick="copyToClipboard('#search','#websitesearch_text')" >
            <img src="<?php echo base_url();?>assets/images/copy.png"  class="img-responsive" alt="copy" />
          </a>
       </div>

        <div class="sec-9 clearfix"> 
          <a href="sms:?body=Hi, View my location on live map and join me at: <?php $grp = (!empty($map_search_key))?$map_search_key:get_cookie("map_search"); echo site_url('search/'.$grp);?>" class="sms"  >
            <img src="<?php echo base_url();?>assets/images/sms.png"  class="img-responsive" alt="sms" target="_blank" />
          </a>
       </div>
       <div class="sec-9 clearfix"> 
          <a href="mailto:?subject=Here's MyGPS&body=Hi, View my location on live map and join me at: <?php $grp = (!empty($map_search_key))?$map_search_key:get_cookie("map_search"); echo site_url('search/'.$grp);?> " target="_blank" >
            <img src="<?php echo base_url();?>assets/images/email_send.png"  class="img-responsive" alt="email" />
          </a>
       </div> 
</div>
<style type="text/css">
.google-add.right-add02 {
    margin: auto;
    text-align: center;
}
/**
 * div#map {
 *     height: 83vh!Important;
 * }
 */
    </style>
<script>

vpw = $(window).width();
vph = $(window).height();
$('#map').css({"height": vph + 'px'});

    //$(document).on("pagecreate",function(event){
         // $(window).on("orientationchange",function(event){
//            var orientat = event.orientation;
//          });                     
   // });

    if($('div[id="header_toggle"]').text())
      $('#header_toggle').hide();

     // Define your locations: HTML content for the info window, latitude, longitude
    var locations         = <?php echo $locations;?>;
    var contents          = <?php echo $contents;?>;
    var participant_ct    = <?php echo $participant_count ?>;       
    var mheader           = '<?php echo $mobile_header; ?>'; 
    var breaduser         = '<?php echo $breadcrumb_user;?>';
    var breadtimelimit    = '<?php echo $breadcrumb_timelimit;?>';
    var breadcrumb_status = '<?php echo $breadcrumb_status;?>';
    
    var maptype  = '<?php echo $type; ?>';
    var maplt    = '<?php echo $lat; ?>';
    var mapln    = '<?php echo $lon; ?>';
    var maplctype= '<?php echo $location_type; ?>';
    var join_key = '<?php echo $join_key; ?>';
    var mapuptime= '<?php echo $dateupdate; ?>';
    var public_map_name = '<?php echo $description; ?>';
    var participant_txt = '';
    
   // alert(mheader);
    
    var user_id = '<?php echo $user_id;?>';
    
    var sel_group_id = false;

    var markers = new Array();
    
    var markercustomImages = new Array();

    var map = '',geocoder = '', savedMapLat='', savedMapLng='', savedMapZoom='', splitStr = '', pro_id='',utype = '',breadtimelt = '',breaduser='' ;

    var reloadzoomlvl = '';
    var labeltext     = '';

    var infowindow = new google.maps.InfoWindow({
          maxWidth: 500
         // Width: 450
        });
    window.localStorage.removeItem('mapzoom');
    
    var trackingUser    = tracker_details("trackinguser");
    var trackedUser     = tracker_details("trackeduser");
    var splitStr        = get_user_changed_position("myMapCookie"); 
    var trackedStr      = get_user_changed_position("trackedUser_position");
    
    var stt = ''; var staticmap = '';
    map_search(locations,contents,user_id,1);  
   
    var styles = {
        default: null,
       night: [
            {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
              featureType: 'administrative.locality',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'geometry',
              stylers: [{color: '#263c3f'}]
            },
            {
              featureType: 'poi.park',
              elementType: 'labels.text.fill',
              stylers: [{color: '#6b9a76'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry',
              stylers: [{color: '#38414e'}]
            },
            {
              featureType: 'road',
              elementType: 'geometry.stroke',
              stylers: [{color: '#212a37'}]
            },
            {
              featureType: 'road',
              elementType: 'labels.text.fill',
              stylers: [{color: '#9ca5b3'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry',
              stylers: [{color: '#746855'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'geometry.stroke',
              stylers: [{color: '#1f2835'}]
            },
            {
              featureType: 'road.highway',
              elementType: 'labels.text.fill',
              stylers: [{color: '#f3d19c'}]
            },
            {
              featureType: 'transit',
              elementType: 'geometry',
              stylers: [{color: '#2f3948'}]
            },
            {
              featureType: 'transit.station',
              elementType: 'labels.text.fill',
              stylers: [{color: '#d59563'}]
            },
            {
              featureType: 'water',
              elementType: 'geometry',
              stylers: [{color: '#17263c'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.fill',
              stylers: [{color: '#515c6d'}]
            },
            {
              featureType: 'water',
              elementType: 'labels.text.stroke',
              stylers: [{color: '#17263c'}]
            }
          ]
      };
      
    function map_search(locations,contents,user_id,stable){
//alert(stable);
         breadtimelt    = tracker_details("breadcrumb_timelimit");
         breaduser      = tracker_details("breadcrumb_user");
        
       //  alert(locations);
         breadtimelimit = (breadtimelt!='' && breadtimelt != 'undefined')?breadtimelt:breadtimelimit;
    
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }

        markers = new Array();
        //alert(participant_ct);
        $("#count-participants").html(participant_ct);

        reloadzoomlvl = window.localStorage.getItem('mapzoom');
        
        if(serchval!=''){

           var myInterval = setInterval(function(){
              geolocation();
              user_position_save(user_id);
              clearInterval(myInterval);
            },120000);
        }  

        var gotCookieString = getCookie("myMapCookie"); 
        var splitStr        = gotCookieString.split("_");
         savedMapLat        = (trackedStr!='')?parseFloat(trackedStr[0]):parseFloat(splitStr[0]);
         savedMapLng        = (trackedStr!='')?parseFloat(trackedStr[1]):parseFloat(splitStr[1]);
         savedMapZoom       = (trackedStr!='')?parseFloat(trackedStr[2]):parseFloat(splitStr[2]);
         
        var centerlat = "";
        var centerlon = "";
        var zoomlvl   = "";
      //  alert(locations[0][1]+""+locations[0][1]);
        if(splitStr!='' || trackedStr!=''){
            centerlat = (savedMapLat!=0)?savedMapLat:38.53;
            centerlon = (savedMapLng!=0)?savedMapLng:-101.42;
            zoomlvl   = (savedMapZoom!=0)?savedMapZoom:3;
        }
        else
        {
            centerlat = (locations[0][1]!=0)?locations[0][1]:38.53;
            centerlon = (locations[0][2]!=0)?locations[0][2]:-101.42;
            zoomlvl   = (locations[0][1]!=0)?13:3;
        }
        //if(locations.length > 0){
//        	centerlat = (locations[0][1]!=0)?locations[0][1]:38.53;
//        	centerlon = (locations[0][2]!=0)?locations[0][2]:-101.42;
//        	zoomlvl   = (locations[0][1]!=0)?13:3;
//        }
        
        //remove the localstroage
        window.localStorage.removeItem('mapzoom'); 

        if(stable == 1){

        map = new google.maps.Map(document.getElementById('map'), {
        zoom: zoomlvl,
        gestureHandling: 'greedy',
          center: new google.maps.LatLng(centerlat, centerlon),
          mapTypeControl: true,
          mapTypeControlOptions: {
                                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                                    mapTypeIds: [                                
                                      google.maps.MapTypeId.ROADMAP,
                                      google.maps.MapTypeId.SATELLITE,
                                      google.maps.MapTypeId.TERRAIN
                                    ],
                                    position: google.maps.ControlPosition.TOP_LEFT
                                  },
          streetViewControl: true,
          
          zoomControl: true,
          zoomControlOptions: {
              position: google.maps.ControlPosition.RIGHT_TOP,
          },
          streetViewControlOptions: {
            position: google.maps.ControlPosition.RIGHT_TOP
          },
          rotateControlOptions:{
                                position: google.maps.ControlPosition.RIGHT_TOP
                              },
          
          scaleControl:true,
          scrollwheel:false,
          panControl: true,
          panControlOptions: { position: google.maps.ControlPosition.RIGHT_TOP
                              },
         
          overviewMapControl:true,
          rotateControl:true,
          heading: 90,
          tilt: 45,
          fullscreenControl:true 
        });
        
       
        var geoloccontrol = new klokantech.GeolocationControl(map, 15);

        }
        //loadMapState();
        
        geocoder = new google.maps.Geocoder();   

        google.maps.event.addListener(map, 'dragstart', function() {
          $("#geolocationIcon").css('background-position','0px center');
        }); 
        
        var iconCounter = 0;
        var colors      = ['F08080','C6EF8C'];
        var filters    = '',filters1 ='', inv_pt='',static_cluess='',invisible_pt='',groupname = '', infoindexx = '', partcipanthead = '', clues = '', markericon='';
        
         groupname = '<?php echo $this->uri->segment(2);?>';
          
          if(groupname == '') {
            groupname = popuptrigger;
          }
     
        // Add the markers and infowindows to the map
        if(locations.length > 0 ){
        //  filters += '<div class="tab-content"><div id="show-1" class="tab-pane fade in active "><div class="" aria-labelledby="dropdownMenu2" id="participants-list"></div></div><div id="show-2" class="tab-pane fade"><div class="" aria-labelledby="dropdownMenu2" id="tab2"></div></div><div id="show-3" class="tab-pane fade"><div class="" aria-labelledby="dropdownMenu2" id="tab3"></div></div></div>';  
          
        }
            
       if(maptype == 'public'){ 
            var publiciconcustom = {
                        url: site_url+'/assets/images/orange-icon.png', 
                        scaledSize: new google.maps.Size(100, 40), 
                    };
            var mdat    = new Date((mapuptime*1000));
            var marker  = new google.maps.Marker({
                position: new google.maps.LatLng(maplt, mapln),
                map: map,
                animation: google.maps.Animation.DROP,
                title: join_key+'\nUpdated : '+formattime(mdat),
                optimized: false,
                label:public_map_name,
                draggable:false,
                icon: publiciconcustom
              });
              markers.push(marker);
        }  
       
       
        for (var i = 0; i < locations.length; i++) {  
            var invisible = locations[i][6];
           staticmap = (locations[i][7]!='')?locations[i][7]:"";
          var dat    = new Date((contents[i][1]*1000));
          var tp     = locations[i][3];
          if(staticmap != 'staticmap'){
             
               iconcustom = {
                        url: locations[i][9], 
                        scaledSize: new google.maps.Size(80, 40)
                    };
              var labeltext = {text:locations[i][0],color:"white"};      
          }
          else
          {
            iconcustom = '';
            labeltext  = '';
          }
          
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            animation: google.maps.Animation.DROP,
            label:labeltext,
            title: locations[i][0]+'\nUpdated : '+formattime(dat),
            optimized: false,
            draggable:false,
            icon:iconcustom,
            zIndex:9999+i
          });
          markers.push(marker);
          
          if(locations[i][1] == 0 || locations[i][2] == 0)
            markers[i].setMap(null);

          google.maps.event.addDomListener(marker, 'click', (function(marker, i) {
            return function() {    
              dat = new Date((contents[i][1]*1000));          
              var cont = contents[i][0].replace("<<lastseen>>",formattime(dat) );
              infowindow.setContent(cont);
              infowindow.open(map, marker);
            }
          })(marker, i));
          
          var highlight_classname = '';  
          var group_admin_icon = "", invisible_icon = "";

          groupname  = groupname.toLowerCase();
          var locat  = locations[i][5].toLowerCase();
          if(groupname == locat && staticmap == 'dynamic' && invisible==1) {
            
               sel_group_id = i;
               pro_id = locations[i][8];
               utype  = locations[i][4];
               highlight_classname = 'highlight'; 
          
               var asr = getCookie('map_search');
               group_admin_icon= '<span class="group_admin sprite-image">&nbsp;</span>';

               partcipanthead += '<li class="text-center map-admin">Administrator : '+locations[i][0].substring(0,10)+'</li>';
               filters += '<li class="map-list-label"><span class="name">Participant</span> <span>Find</span> <span>Status</span></li>';
            
            //center on current position
            if(splitStr == '' && trackedStr ==''){
                //posclick(sel_group_id);
            }
            
            if(trackedStr == '') {
              //trackuser(user_id,locations[i][5],asr,locations[i][1],locations[i][2],locations[i][8]);
              //trackuser();
            }
          }
            invisible_icon = '<span class="invisible_icon">&nbsp;</span>';
            
            
             var gpus      = locations[i][0].substring(0,13);
             var ctrack    = locations[i][5].substring(0,13);
            //alert(invisible);
          if(invisible == 1 && staticmap == 'dynamic') {
            
             if((gpus == trackedUser || ctrack == trackedUser) ){
                 highlight_classname = 'highlight'; 
             }
             else
             {
                highlight_classname = 'sprite-image'; 
             }
             
            filters += '<li><a href="javascript:posclick('+ i + ')"><div class="p-parti"><span class="name"><b>DN: </b> '+gpus+'</span><br /><span class="name"><b>CHID: </b>'+ctrack+'</span></div></a><div class="p-find-iocn">'+group_admin_icon+'<a href="javascript:posclick('+ i + ')" id="'+locations[i][5]+'" class="myposition '+highlight_classname+'">&nbsp;</a><a href="javascript:myclick('+ i + ',1)" class="statuspop sprite-image">&nbsp;</a></div></li>';
          }
          
          if(invisible == 0 && staticmap == 'dynamic') { 
            
            inv_pt += '<li><div class="p-parti"><span class="name"><b>DN: </b>'+gpus+'</span><br /><span class="name"><b>CHID: </b>'+ctrack+'</span></div><div class="p-find-iocn">'+group_admin_icon+invisible_icon+'</div></li>';
          }
        
          //clues integration by punitha
          if(invisible == '' && staticmap == 'staticmap') {
            
            clues += '<li><a href="javascript:posclick('+ i + ')"><div class="p-parti"><span class="name"><b>DN: </b> '+gpus+'</span><br /><span class="name"><b>CHID: </b>'+ctrack+'</span></div></a><div class="p-find-iocn"><a href="javascript:posclick('+ i + ')" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick('+ i + ',1)" class="statuspop sprite-image">&nbsp;</a></div></li>';
          }
       }
       
        if(inv_pt!=''){
            
            invisible_pt = '<li class="text-center invisible-head">Invisible Participants</li>';
            invisible_pt +=  inv_pt;
        }
         //added clues header 
        if(clues!=''){
             static_cluess  = '<li class="text-center invisible-head">Static Maps/Clues</li>';
             static_cluess  += clues;
        }
        
        $("#tab1").html(partcipanthead+filters);
        $("#tab2").html(invisible_pt);
        $("#tab3").html(static_cluess);
        pro_id = (breaduser!='')?breaduser:pro_id;
        //if(stt == 'yes'){
         // breadcrumb(pro_id,breadtimelimit);
       // }
        google.maps.event.addListener(map, "dblclick", function(event) {
               placeMarker(event.latLng,'dbclick'); 
        });
        
        // this is our gem
        var currCenter = map.getCenter();
        google.maps.event.addDomListener(window, "resize", function() {
          // if(mheader == 'yes') {
            google.maps.event.trigger(map, "resize");
            //
            var splitStr        = get_user_changed_position("myMapCookie"); 
            var trackedStr      = get_user_changed_position("trackedUser_position");
            if(splitStr!='' || trackedStr!=''){
              var   savedMapLat        = (trackedStr!='')?parseFloat(trackedStr[0]):parseFloat(splitStr[0]);
              var   savedMapLng        = (trackedStr!='')?parseFloat(trackedStr[1]):parseFloat(splitStr[1]);
              var   savedMapZoom       = (trackedStr!='')?parseFloat(trackedStr[2]):parseFloat(splitStr[2]);
              map.setCenter(new google.maps.LatLng(savedMapLat,savedMapLng));
            //  map.panBy(20,250);
            }
            else
            {
               map.setCenter(markers[sel_group_id].getPosition());
             //  map.panBy(20,250);
            }
        });
        
        // as a suggestion you could use the event listener to save the state when zoom changes or drag ends
        google.maps.event.addListener(map, 'tilesloaded', tilesLoaded);    
        
        /*
        document.getElementById('hide-poi').addEventListener('click', function() {
          map.setOptions({styles: styles['default']});
        });
        */
        document.getElementById('show-poi').addEventListener('click', function() {
          map.setOptions({styles: styles['night']});
        });
    
    }
    
    var marker;    
    function placeMarker(location,eventtype = '') {
        if(marker){ 
            marker.setPosition(location); 
        }
        else
        {
            if(eventtype != 'dragevent') {
                marker = new google.maps.Marker({ 
                    position: location, 
                    map: map
                });
            } 
        }
        getAddress(location);
    }

    function getAddress(latLng) 
    {
        geocoder.geocode( {'latLng': latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                $(".popover #guest_address").val(results[0].formatted_address);
              }
              else 
              {
                $(".popover #guest_address").val("No results");
              }
            }
            else 
             {
               $("guest_address").val(status);
             }
          });
     }    
   
   function codeAddress(address) {
    //var address = document.getElementById('address').value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == 'OK') {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }
        
   function drag(event) {
    //alert(event);
      event.dataTransfer.setData('text/html', "Pegman Drag"); //cannot be empty string
      
    google.maps.event.addListener(map, 'mouseover', function(event) {
    geocoder.geocode( {'latLng': event.latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                $(".popover #guest_address").val(results[0].formatted_address);
              }
              else 
              {
                $(".popover #guest_address").val("No results");
              }
            }
            else 
            {
               $("guest_address").val(status);
             }
          });
    // marker = new google.maps.Marker({position: event.latLng, map: map});

  });
    // ev.dataTransfer.setData("text", ev.target.id);
    // var add = $(".popover-content #guest_address").val();
    // codeAddress(add);
  }
     
    function myclick(i,tag) 
    {
        if(tag == 1 )
         google.maps.event.trigger(markers[i], "click");
            
         var bounds = new google.maps.LatLngBounds();
        
         bounds.extend(markers[i].position);
         map.fitBounds(bounds);
         
         map.setCenter(markers[i].getPosition());
        
    }

    function posclick(i) 
    {
         var bounds = new google.maps.LatLngBounds();
         bounds.extend(markers[i].position);
         map.fitBounds(bounds);
         map.setCenter(markers[i].getPosition());
       //  setTimeout(function(){ map.setZoom(16); },4000);
    }
   
    function rotate90() {
      var heading = map.getHeading() || 0;
      map.setHeading(heading + 90);
    }


    function closeinfowindow(closeid){
        infowindow.close();
        if(closeid != 1){
           setTimeout(function(){ map.setZoom(16); },2000);
        }
    }
    
 
  $('#sharethis a').click(function(){

      var $scocial = $(this).parents("#sharethis").find('.vertical');

        if($scocial.length){
          $scocial.remove();
        }
        else
        {
            $("#sharethis").socialButtonsShare({
              socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
              url: '<?php echo site_url();?>/search/'+serchval,
              text: "Here's My GPS",
              sharelabel: false,
              sharelabelText: "SHARE",
              verticalAlign: true
            });
        }
        
    });

   
  function user_position_save(user_id){
     
      var latlon = $("#latlang").val();
    
        var res = latlon.split(":");

        if(res.length <= 1){
          res[0] = 'noloc';
          res[1] = 'noloc';
        }

        $.post('<?php echo site_url();?>/home/user_position_save/'+user_id+'/'+res[0]+'/'+res[1], {}, function(response){
             
            //ajax_loader(1);
            window.localStorage.setItem('mapzoom', '1');
            var srchkey = $("#search").val();

            if(srchkey!=''){
                $.post('<?php echo site_url();?>/home/search/'+srchkey+'/1', {}, function(response){  
                    if(response!=''){
                        response = JSON.parse(response);
                        map_search(response.locations,response.contents,response.user_id,0);
                    }
                });
            }              
            
        });
        
      
    }

    function formattime(date) {

    var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';

    var month = (date .getMonth()+1);
    month = month < 10 ? '0'+month : month;

    var day = days[date.getDay()];
    var fulldate = date .getDate()+"-"+ month +"-"+date .getFullYear();

    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;

    var strTime = hours + ':' + minutes + ' ' + ampm + ' '+ day + ' ' + fulldate;

    return strTime;
  }
    
  
   function group_user_delete(group_id,user_id,channel_id){
     
        $.post('<?php echo site_url();?>/home/delete_member/'+user_id+'/'+group_id, {}, function(response){
             
                location.href='<?php echo site_url();?>/search/'+channel_id;  
        });     
  }   
  
  function tilesLoaded() {
 
    google.maps.event.clearListeners(map, 'tilesloaded');
    google.maps.event.addListener(map, 'zoom_changed', saveMapState);
    google.maps.event.addListener(map, 'dragend', saveMapState);
}   


// functions below

function saveMapState() 
{ 
    
    var mapZoom   = map.getZoom(); 
    var mapCentre = map.getCenter(); 
    
    var mapLat = mapCentre.lat(); 
    var mapLng = mapCentre.lng(); 
    var cookiestring=mapLat+"_"+mapLng+"_"+mapZoom; 
    setCookie("myMapCookie",cookiestring, 30); 
    
    var trackedUser_zoom_update = get_user_changed_position("trackedUser_position");
   if(trackedUser_zoom_update!='') {
        var cookie_zoom = trackedUser_zoom_update[0]+"_"+trackedUser_zoom_update[1]+"_"+mapZoom;
        setCookie("trackedUser_position",cookie_zoom, 30);
    }
    placeMarker(mapCentre,'dragevent'); 
} 

function loadMapState() 
{ 
    var gotCookieString = getCookie("myMapCookie"); 
    var splitStr        = gotCookieString.split("_");
    var savedMapLat     = parseFloat(splitStr[0]);
    var savedMapLng     = parseFloat(splitStr[1]);
    var savedMapZoom    = parseFloat(splitStr[2]);
    if ((!isNaN(savedMapLat)) && (!isNaN(savedMapLng)) && (!isNaN(savedMapZoom))) {
        map.setCenter(new google.maps.LatLng(savedMapLat,savedMapLng));
        map.setZoom(savedMapZoom);
    }
}

function setCookie(c_name,value,exdays) {
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name) {
    var i,x,y,ARRcookies=document.cookie.split(";");
   
    for (i=0;i<ARRcookies.length;i++)
    {
      x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
      y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
      x=x.replace(/^\s+|\s+$/g,"");
      
       if (x==c_name){
        
        return unescape(y);
       }
      }
    return "";
} 

function trackuser()
{
   var already_tracked = tracker_details("trackeduser");
       if(already_tracked !=''){
           //remove highlight already tracked user
            $("#"+already_tracked).removeClass("highlight");
            $("#"+already_tracked).addClass("sprite-image");
       }
       
       if((!$(".track_userr").is(":checked")) && (pro_id == '')){
         if(confirm("Do you want to cancel track this member: "+already_tracked)) {
             document.cookie="trackeduser=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
             document.cookie="trackinguser=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
             document.cookie="trackmapID=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
             document.cookie="trackedUser_position=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
             document.cookie="track_user_type=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
             return true;
         }
       }
       else
       {
           $(".tuser").prop( "checked", function( i, val ) {
                  
                  $(this).attr("checked","checked");
                  var uid          = $(".track_userr").attr("data-uid");
                  var sid          = $(".track_userr").attr("data-mapsearch");
                  var cid          = $(".track_userr").attr("data-chid");
                  var tutype       = $(".track_userr").attr("data-usertype");
                  var al_uid       = getCookie("trackinguser");
                  var mapZoom      = map.getZoom(); 
                  var mapCentre    = map.getCenter(); 
                  var mapLat       = mapCentre.lat(); 
                  var mapLng       = mapCentre.lng(); 
                  var cookiestring = mapLat+"_"+mapLng+"_"+mapZoom;
                  
                  if(uid == al_uid) {
                    if(confirm("Do you want to cancel track this member "+tracker_details("trackeduser"))) {
                      setCookie("trackeduser",cid,2);
                      setCookie("track_user_type",tutype,2);
                      setCookie("trackedUser_position",cookiestring, 30);
                      //highlight tracked user 
                      $("#"+cid).addClass("highlight");
                    }
                  }
                  else
                  {
                   // var trk = (utype == 'admin')?true:confirm("Do you want to track this member: "+cid);
                    if(confirm("Do you want to track this member: "+cid)) {
                         setCookie("trackeduser",cid,2);
                         setCookie("trackinguser",uid,2);
                         setCookie("trackmapID",sid,2);
                         setCookie("trackedUser_position",cookiestring, 30);
                         setCookie("track_user_type",tutype,2);
                         //highlight tracked user 
                         $("#"+cid).addClass("highlight");
                         if(utype != 'admin') {
                            alert("You are now tracking map member: "+cid);
                         }
                         return true; 
                    } 
                  }   
           });
    }
}

function get_user_changed_position(cookie_name) {
    var trackeduserpos  = getCookie(cookie_name);
    var posstr = trackeduserpos.split("_");
    return posstr;
}

function tracker_details(cookie_name) {
    var trackdet  = getCookie(cookie_name);
    return trackdet;
}

function breadcrumb(user_id='',timelimit='')
{
    if($("#breadcrumb"+timelimit).prop('checked') == false){
         document.cookie="breadcrumb_user=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         document.cookie="breadcrumb_timelimit=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         setTimeout(function(){$("#allow_dy").trigger("click");},2000); 
    }
    else
    { 
         if(timelimit == 1){
            if($("#breadcrumb"+timelimit).prop('checked') == true){
              $("#breadcrumb"+timelimit).attr("checked","checked");
              $("#breadcrumb2").removeAttr("checked");
              $("#breadcrumb0").removeAttr("checked");
            }
         }
         else if(timelimit == 2)
         {
            if($("#breadcrumb"+timelimit).prop('checked') == true){
              $("#breadcrumb"+timelimit).attr("checked","checked");
              $("#breadcrumb1").removeAttr("checked");
              $("#breadcrumb0").removeAttr("checked");
            }
         }
         else
         {
            if($("#breadcrumb"+timelimit).prop('checked') == true){
              $("#breadcrumb"+timelimit).attr("checked","checked");
              $("#breadcrumb2").removeAttr("checked");
              $("#breadcrumb1").removeAttr("checked");
            }
         }
    
    
        if($("#breadcrumb"+timelimit).prop('checked') == true){
            $("#breadcrumb"+timelimit).attr("checked","checked");
        }
        var timelimit = (timelimit=='')?$('input[name=breadcrumb]:checked').val():timelimit;
        setCookie("breadcrumb_user",user_id,2);
        setCookie("breadcrumb_timelimit",timelimit,2);
        
        if(timelimit == 'undefined'){
            timelimit = 0;
        }
           
        var markers = new Array(), marker='';            
        $.ajax({
        type:"POST",
        url:'<?php echo site_url();?>home/breadcrumb/'+user_id+"/"+timelimit,
        data:'',
        success:function(response){
            if(response!=''){
                response  = JSON.parse(response);
                for(var i = 0;i<response.length;i++){
                    var markericon = (response[i].flag==2)?'<?php echo site_url();?>'+"assets/images/purple_pos.png":(response[i].flag==0)?'<?php echo site_url();?>'+"assets/images/yellow_pos.png":'<?php echo site_url();?>'+"assets/images/red_pos.png";
                    var marker     = new google.maps.Marker({
                                                             position: new google.maps.LatLng(response[i].lat, response[i].lon),
                                                             map: map,
                                                             draggable:false,
                                                             icon:markericon
                                                           });
                    markers.push(marker);  
                    setTimeout(function(){closeinfowindow();},3000);         
                }
               // var srchkey = $("#search").val();
//                if(srchkey!=''){
//                    $.post('<?php //echo site_url();?>/home/search/'+srchkey+'/1', {}, function(response){  
//                        if(response!=''){
//                            response = JSON.parse(response);
//                            map_search(response.locations,response.contents,response.user_id,0,"no");
//                        }
//                    });
//                }
             }  
           }  
      });
   }  
}

var dropzone = document.getElementById('dropzone'), draggable = document.getElementById('draggable');


function onDragStart(event) {
   event.preventDefault();
    event.dataTransfer.setData('text/html', null); //cannot be empty string
}

function onDragOver(event) {
    var counter = document.getElementById('counter');
    counter.innerText = parseInt(counter.innerText, 10) + 1;
}   

//draggable.addListener('dragstart', onDragStart, false);
//dropzone.addListener('dragover', onDragOver, false);

function clear_track()
{
    //if($("#clear_tracking").prop(":checked")==true){
         document.cookie="trackeduser=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         document.cookie="trackinguser=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         document.cookie="trackmapID=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         document.cookie="trackedUser_position=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         document.cookie="track_user_type=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
         location.reload();
         return true;
   // } 
}

</script>





<script type="text/javascript">
	
		
		$(document).ready(function(){
			
             $("#dropdownMenu2").mouseover(function(){
                if($("#dropdownMenu2").find('data-toggle')){
                  $("#dropdownMenu2").removeAttr('data-toggle');
                } 
            });
            
			$(".refresh-clk").click(function(){
			
			window.location.reload();
    
			});
            
            $("#map").on("touchstart", function(e) { dragFlag = true; start = e.originalEvent.touches[0].pageY; });
			
		});
		
		
		
		/*  2 nd script Timer */
		
	//var h1 = document.getElementsByTagName('h2')[0],
    var h1 = document.getElementById('re-load'),
    start = document.getElementById('start'),
    stop = document.getElementById('stop'),
    clear = document.getElementById('clear'),
    seconds = 0, minutes = 0, hours = 0,
    t;

function add() {
    seconds++;
    if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
            minutes = 0;
            hours++;
        }
    }
    
    h1.textContent = (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

    timer();
}
function timer() {
    t = setTimeout(add, 1000);
}

if(locations.length>0){
    timer();
}

/* Start button */
start.onclick = timer;

/* Stop button */
stop.onclick = function() {
    clearTimeout(t);
}

/* Clear button */
clear.onclick = function() {
    h1.textContent = "00:00";
    seconds = 0; minutes = 0; hours = 0;
}


	/* Timer set interval */

		
	$(document).ready(function(){	
		
		setInterval(refresh_timer, 120000);	
		
	});
		
	function refresh_timer()
	{
		
	  $('#clear').trigger('click');

	}
		
    	function add_toggle()
        {
            $("#dropdownMenu2").attr("data-toggle","dropdown");  
            $(".dropup").removeClass('open');
        }    
</script>

<?php 
//$this->load->view('_partials/footer', $this->data);

?>