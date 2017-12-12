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
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/social-buttons-share.css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="<?php echo base_url();?>assets/js/lib/jquery.min.js?v1.12.0"></script>
    
    <script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClXC4q5fI1C8arpQ22WVOwvtsfh9a-eLw&sensor=true"></script>
    <script>
     var service_resp = <?php echo json_encode($service_resp);?>;
     var user_info    = <?php echo json_encode($user_info);?>;
     
     var uid = '<?php echo $user_id; ?>';
    
     var site_url = '<?php echo site_url();?>';
     var base_url = '<?php echo base_url();?>';
    
     var  serchval = '<?php echo ($map_search_key)?$map_search_key:get_cookie("map_search"); ?>';
    
    var popuptrigger = '<?php echo ($map_search_key)?$map_search_key:get_cookie("map_search"); ?>';
//    var joined_mapp = $("#joined_map").val();
     (function() {
      geolocation();
    
     })();
    
          //var accuracy = position.coords.accuracy;
          //var speed = position.coords.speed;
       var latitude = '', longitude = '';   
     function showLocation(position) {
         
           latitude = position.coords.latitude;
           longitude = position.coords.longitude;
          $("#latlang").val(latitude + ":" + longitude);
          console.log("current locatin lat long:"+latitude+" "+longitude);
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
//                    setTimeout(function(){ guest_registration('auto'); },3000);
//                }
          
             $("#latlang").val(0);
              
          }
    }
    
    if(typeof user_info === "object" && user_info['user_id']==''){
        //alert(123);
        setTimeout(function(){ guest_registration('auto'); },8000);
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
    <?php 
      $search_val     = ($map_search_key)?$map_search_key:get_cookie('map_search'); 
      $rand_channelid =  (isset($user_info['channel_id']))?$user_info['channel_id']:'';
      $display_name   =  (isset($user_info['display_name']) && ($user_info['display_name']!='') && !empty($user_info['display_name']))?$user_info['display_name']:$rand_channelid;
      $phonenumber    =  (isset($user_info['phonenumber']) && $user_info['phonenumber']!='')?$user_info['phonenumber']:"";
      $group_id       =  (isset($user_info['group_id']) && $user_info['group_id']!='')?$user_info['group_id']:'';
      $updated_type   =  (isset($user_info['updated_type']) && ($user_info['updated_type']))?$user_info['updated_type']:"";
      $updated_phonenumber = (isset($user_info['updated_phonenumber']) && ($user_info['updated_phonenumber']))?$user_info['updated_phonenumber']:"";
      $usrchk         = ($user_info['user_id']!='')?'user_update':'guest_registration';
      //print_r($user_info);
?>
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

  </head>
  <body class="home">
   <input  type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>"  />                 
   <input  type="hidden" name="prev_channel" id="prev_channel" value="<?php echo $rand_channelid;?>"  />
   <input  value="0" name="latlang" id="latlang" type="hidden">
   
   <input name="phone" id="phone" type="hidden" />
  <a class="display_popup" data-toggle="modal" data-target="#update_displayname" id="display_pp"></a>    
<center class="bg">
  
  <div class="container">
      <div class="logo">
        <a href="<?php echo base_url();?>" title="911 GPS">
          <img src="<?php echo base_url();?>/assets/images/logo.png" class="img-responsive" alt="">
        </a>
      </div>
      <div class="map-id clearfix">
        <div class="col-xs-8">
          <span class="span">
            Your Searchable HMGPS Map ID is
          </span>
        </div>
        <div class="col-xs-4">
          <div class="pencil-cover">
            <input type="text" placeholder="<?php echo $search_val;?>" value="<?php echo $rand_channelid;?>" class="edit-id text">
            <span class="edit-ico glyphicon glyphicon-pencil __web-inspector-hide-shortcut__" aria-hidden="true"></span>
          </div>
        </div>
      </div>
      <div class="display-name clearfix">
        <div class="col-xs-12">
          <div class="pencil-cover">
            <input type="text" placeholder="Enter Your Name" class="text" name="display" id="display_name" value="<?php echo $display_name;?>" />
            <button class="edit-ico  glyphicon glyphicon-ok" aria-hidden="true" data-text="Submit" data-color="green" data-position="left" style="display:block;" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');"  ></button>
            <label for="display-name">Your Public User Tag Display Name. (optional)</label>
          </div>
        </div>
      </div>
      
<div class="display-name clearfix">
        <div class="col-xs-12">
          <div class="pencil-cover">
            <input type="hidden" placeholder="Enter Your Phonenumber" class="text" name="phone" id="phone" value="<?php echo $display_name;?>" />
            <!-- <button class="edit-ico glyphicon glyphicon-ok" aria-hidden="true" data-text="Submit" data-color="green" data-position="left" style="display:block;" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');"  ></button>
          
 <label for="display-name">Your Public User Tag Phone. (optional)</label>
-->
          </div>
        </div>
      </div>

      <div class="share-location clearfix">
        <div class="col-xs-12">
         <a data-toggle="modal" data-target="#searchshare_mb">
          <div class="btn btn-default btn-block">
            <b>Share Your Location</b>
          </div>
           </a>
        </div> 
      </div>
    <form method="post" name="map_search" action="<?php echo site_url();?>search">
      <div class="search-wrapper clearfix">
        <div class="col-xs-12">
          <div class="input-group">
            <input type="text" value="<?php echo $search_val;?>" name="search" id="search" class="form-control" placeholder="Enter channel ID">
            <span class="input-group-btn">
             <input class="btn btn-default"  name="submit" type="submit" value="SEARCH" />
<!--
            <input type="submit" value="" class="go-search subm" id="allow_dy" value="SEARCH" />
            -->
            </span>
          </div><!-- /input-group -->
        </div>
      </div>
   </form>   

      <div class="map-action clearfix">
        <div class="col-xs-6">
          <div class="view-map">
           <?php $uri = $this->uri->segment(1); if(((!empty($rand_channelid)) && ($search_val != $rand_channelid) && empty($search_val)) || ($uri != 'search')) { ?>
            <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>"><b>View</b> My Map</a>
            <?php } else if(!empty($search_val) && ($search_val != $rand_channelid)) { ?>
             <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" > Return to <span>My Map</span></a>
             <?php }else if($search_val == $rand_channelid){?>
            <a href="<?php echo site_url();?>search/<?php echo $rand_channelid;?>" > 
                My <small></small>Map
            </a>
            <?php } else{}?>
             
          </div>
        </div>
        <div class="col-xs-6">
          <div class="exit-map">
          <a onclick="remove_user_from_all_groups()">
            <b>Exit</b> All Maps
          </a>  
          </div>
        </div>
      </div>
      
      <div class="map-on-off clearfix">
     <!-- <a href="javascript:void(0);" data-toggle="modal" data-target="#loadModal"> -->
      <a href="javascript:void(0);">
        <div class="col-xs-9">
          <div class="map-text">
            Make Visible on Map 
          </div>
        </div>
        <div class="col-xs-3">
            <div class="map-button" id="visiblestatus" >  
              <?php $this->load->view('visible_status',$this->data); ?>
            </div>
        </div>
      </a>
      </div>    
  </div>

  </center>

  <!-- Modal -->


<div class="modal fade" id="update_displayname" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
    
      <!-- Modal content-->
      <form name="upd_disp_name" id="upd_disp_name">
      <div class="modal-content">
         <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title text-center"><b>Update Display Name &amp; Phone Number</b></h4>
      </div>
        <div class="modal-body">
         <!--  -->
        <div class="tab-wrapper">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#displayName" aria-controls="displayName" role="tab" data-toggle="tab">Display Name</a>
            </li>

            <li role="presentation">
              <a href="#PhoneNumber" aria-controls="PhoneNumber" role="tab" data-toggle="tab">Phone Number</a>
            </li>
          </ul>
         <input type="hidden" name="guest_user_id" id="guest_user_id" value="<?php echo $user_id; ?>"  />
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="displayName">
              
              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dname">
                           <input type="radio" class="itxt" name="custom_disp_update"  id="custom_disp_update" <?php echo ($display_name!='' && $updated_type=='custom')?'checked="checked"':""; ?> />
                        </label>
                      </span>
                    <input type="text" class="form-control" name="custom_display_name" aria-describedby="basic-addon1" onkeypress="return displayup('#custom_disp_update');" id="custom_display_name" placeholder="Display Name" value="<?php echo $display_name; ?>" />
                  </div>
              </div>

              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dname1">
                          <input type="radio" class="itxt" name="custom_disp_update" id="system_disp_update" <?php echo ($display_name!='' && $updated_type=='system')?'checked="checked"':""; ?> />
                        </label>
                      </span>
                    <input type="text" class="form-control" value="Use System Generated" disabled="disabled" aria-describedby="basic-addon2">
                  </div>
              </div>

            </div>
            <div role="tabpanel" class="tab-pane fade" id="PhoneNumber">

              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dno">
                          <input type="radio" class="itxt" name="custom_phone_update" id="custom_phone_update" <?php echo ($phonenumber!='' && $updated_phonenumber=='custom')?'checked="checked"':""; ?>  />
                        </label>
                      </span>
                      <input type="text" class="form-control" placeholder="Phone Number" aria-describedby="basic-addon1" name="custom_phonenumber" onkeypress="return displayup('#custom_phone_update');" id="custom_phonenumber" value="<?php echo $phonenumber; ?>" />
                    
                  </div>
              </div>

              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dno1">
                          <input type="radio" class="itxt" name="custom_phone_update" id="system_phonenumber" <?php echo ($phonenumber!='' && $updated_phonenumber=='system')?'checked="checked"':""; ?> />
                        </label>
                      </span>
                    <input type="text" class="form-control" value="Use System Generated" disabled="disabled" aria-describedby="basic-addon2">
                  </div>
              </div>

            </div>
          </div>
        </div>
        <!--  -->
        </div>
        <div class="modal-footer">  
         <button type="button" name="update_dis_name" class="btn btn-default btn-green" id="update_dis_name" onclick="update_disp_name();" > Accept </button>
          <button type="button" class="btn btn-default btn-gray" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
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
          <div id="searchmapshare_mobile">
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
            <div>
              <div class="sec-9">
              <input name="joined_map" id="joined_map" type="hidden" />
                 <label>Current Joined Map ID</label>
                 <input type="radio" name="share_map_type" class="share_mp_type" onclick="share_map('<?php echo (!empty($map_search_key))?$map_search_key:$rand_channelid; ?>');" value="<?php echo (!empty($map_search_key))?$map_search_key:$rand_channelid; ?>" /><?php echo (!empty($map_search_key))?$map_search_key:$rand_channelid; ?>
                 <br />
                 <label>My Map</label>
                 <input type="radio" name="share_map_type" class="share_mp_type" onclick="share_map('<?php echo $rand_channelid; ?>');" value="<?php echo $rand_channelid; ?>" /><?php echo $rand_channelid; ?>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>