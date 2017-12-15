<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport"  content="initial-scale=1.0, user-scalable=no" />
      <meta charset="utf-8">
      <meta name="ad.size" content="width=160,height=600" />
      <meta property="og:url" content="<?php echo $shareurl; ?>"/>
      <meta property="og:title" content="HMGPS"/>
      <meta property="og:site_name" content="HMGPS" />
      <meta property="og:description" content="View my location @ <?php echo $shareurl; ?>"/>
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>HMGPS</title>
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/social-buttons-share.css" />
      <script src="<?php echo site_url();?>assets/js/lib/modernizr-2.8.3.js"></script>
      <!-- Bootstrap -->
      <link href="<?php echo site_url();?>assets/css/style.css" rel="stylesheet" />
      <script src="<?php echo site_url(); ?>assets/js/lib/jquery.min.js?v1.12.0"></script>
      <script src="<?php echo site_url();?>/assets/js/jquery-ui.js"></script>
      <script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClXC4q5fI1C8arpQ22WVOwvtsfh9a-eLw"></script>
      <script src="<?php echo base_url();?>assets/js/infobox.js"></script> 
  
      <!--GLOBAL VARS-->
      <script>
        var site_url = '<?php echo site_url();?>';
        var base_url = '<?php echo base_url();?>';
        var user_info = <?php echo json_encode($user_info);?>;
      </script>

   </head>
   <body class="<?php echo $page; ?>">
      
      <div id="wait" style="display:none; width:69px; height:89px; position:absolute; top:50%; left:50%; padding:2px;"></div>
      
      <a class="display_popup" data-toggle="modal" data-target="#update_displayname" id="display_pp"></a>
      <a class="display_popup" data-toggle="modal" data-target="#update_map_id" id="display_popup_map_id"></a>
      <input  value="0" name="latlang" id="latlang" type="hidden">
      <input class="form-control" value="" name="map_pos" id="map_pos" type="hidden">
      <input  value="" name="joined_map" id="joined_map" type="hidden" />
      <input name="phone" id="phone" type="hidden" />

      <header class="clearfix">
         <!-- 1st Block -->
         <div class="col-s-m-4 menu-block block-left">
            <span class="nav-close navEnabled" onclick="closeNav('slideNavigation')"> x <b>Close</b> </span>
            <span class="nav-trigger pull-left" onclick="openNav('slideNavigation','website')">
            <span class="burger-menu"></span>
            </span>
            <span class="pull-left logo">
            <a href="<?php echo site_url(); ?>">
            <img src="<?php echo site_url();?>assets/images/logo.png" alt="911 GPS" />
            </a>
            </span>

            <form method='post' action='<?php echo site_url('search');?>'>
               <div class="search-full">
                  <span class="dropdown search-filter pull-left">
                     <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Search By  <span class="caret"></span>
                     <span class="data-name">Channel ID</span></a>
                     <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="javascript:void(0);" data-name="Channel ID">Channel ID</a></li>
                     </ul>
                  </span>
                  <div class="pass-activate" id="pprotect">
                     <span class="search-wrap top-search pull-left">
                     <input type="text" value="<?php echo $search_key;?>" name="search" id="search" class="form-control" placeholder="Enter Channel ID">
                     </span>
                     <span class="search-wrap pass-search pull-left <?php if(!empty($pwd)){ ?>pass-activate <?php }else{ ?>pass-deactivate <?php } ?>">
                     <input type="password" name="pwwd" id="pwd" class="form-control" placeholder="password" />
                     </span>
                  </div>
                  <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left">
                  <input type="submit" value="" class="go-search subm" id="allow_dy" />       
                  </span>
               </div>
               <div class="password-search" style="display:none;">
                  <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left"  >
                  <input type="submit" value="" class="go-search subm pass_protect"  /> 
                  </span>
                  <span class="top-clear-icon tool-tip" data-text="Clear" data-color="green" data-position="left">      
                  <input type="reset" value="" class="pull-left" />
                  </span>
               </div>
               <span class="top-share"> <a data-toggle="modal" data-target="#searchshare"><i class="fa fa-share-alt fa-2x"></i></a></span>
            </form>
         </div>

         
         <!-- 2nd Block -->
         <div class="col-s-m-6 block-center">
            <span class="cMapid"> Your Map CH. ID.  <b><?php echo $channel_id;?></b> </span>
            <span class="cMapid"> Your Display Name: <b><?php echo $display_name;?></b> </span>
         </div>


         <!-- 3nd Block -->
         <div class="col-sm-3 block-3 block-right pull-right">

            <a onclick="removeAllMaps();" class="all-map pull-right">Exit <small>All Maps</small></a>
            
            <a href="<?php echo base_url();?>search/<?php echo $channel_id; ?>" class="my-map pull-right" >
              <?php echo $map_disp_str;?>
            </a>

            <div id="visiblestatus" class="top-visible">
               <?php $this->load->view('_partials/visible_status', array('visible' => $visible)); ?>
            </div>

         </div>

      </header>