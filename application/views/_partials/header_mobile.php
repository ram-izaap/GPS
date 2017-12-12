   <?php $search_val  = ($map_search_key)?$map_search_key:get_cookie('map_search'); 
      $rand_channelid =  (isset($user_info['channel_id']))?$user_info['channel_id']:'';
      $display_name   =  (isset($user_info['display_name']) && $user_info['display_name']!='')?$user_info['display_name']:$rand_channelid;
      $group_id       =  (isset($user_info['group_id']) && $user_info['group_id']!='')?$user_info['group_id']:'';
    ?>
   
   <a class="display_popup" data-toggle="modal" data-target="#update_displayname" id="display_pp"></a>
 <input  value="0" name="latlang" id="latlang" type="hidden">
    <input class="form-control" value="" name="map_pos" id="map_pos" type="hidden">
  <header class="clearfix">
    <!-- 1st Block -->
    <div class="col-s-m-4 menu-block block-left">
      <span class="nav-close navEnabled" onclick="closeNav()"> x <b>Close</b> </span>
      <span class="nav-trigger pull-left" onclick="openNav()">
        <span class="burger-menu"></span>
      </span>

      <span class="pull-left logo">
        <a href="<?php echo site_url(); ?>">
          <img src="<?php echo site_url();?>assets/images/logo.png" alt="911 GPS" />
        </a>
      </span>

      <form method='post' action='<?php echo site_url('search');?>'>
        
        <span class="dropdown search-filter pull-left">
          <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Search By  <span class="caret"></span>
          <span class="data-name">Channel ID</span></a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="javascript:void(0);" data-name="Channel ID">Channel ID</a></li>
            <!--
<li><a href="javascript:void(0);" data-name="Display Name">Display Name</a></li>
            <li><a href="javascript:void(0);" data-name="Adress">Adress</a></li>
            <li><a href="javascript:void(0);" data-name="UTM Coordinates">UTM Coordinates</a></li>
            <li><a href="javascript:void(0);" data-name="GPS Coordinates">GPS Coordinates</a></li>
-->
          </ul>
        </span>

        <span class="search-wrap pull-left">
          <input type="text" value="<?php echo $search_val;?>" name="search" id="search" class="form-control" placeholder="Enter Channel ID">
        </span>

        <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left">
          <input type="submit" value="" class="go-search subm" id="allow_dy" />
                   
        </span>

        <span class="top-clear-icon tool-tip" data-text="Clear" data-color="green" data-position="left">      
          <input type="reset" value="" class="pull-left">
        </span>
        
      </form>

    </div>
    
    <!-- 2nd Block -->
    <div class="col-s-m-6 block-center">
      <span class="cMapid"> Current Map ID is <i></i> <b><?php echo $search_val;?></b> </span>
      <span class="top-share"> <a data-toggle="modal" data-target="#searchshare"><i class="fa fa-share-alt fa-2x"></i></a></span>

      <!-- <span class="map-visible pull-right"> <a href="javascript:void(0);"><i class="fa fa-eye fa-2x"></i> <b>Visible</b></a></span> -->
    </div>

    <!-- 3nd Block -->
    <div class="col-s-m-2 block-3 block-right pull-right">
      <!--
<span class="map-visible pull-left"> <a href="javascript:void(0);"><i class="fa fa-eye fa-2x"></i> <b>Visible</b></a></span>
-->
    
    <?php $uri = $this->uri->segment(1); if(((!empty($rand_channelid)) && ($search_val != $rand_channelid) && empty($search_val)) || ($uri != 'search')) { ?>
        <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" class="my-map pull-left" >View <small>My Map</small></a>
        <?php } else if(!empty($search_val) && ($search_val != $rand_channelid)) { ?>
         <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" class="my-map pull-left" > Return to <small>My Map</small></a>
         <?php }else if($search_val == $rand_channelid){?>
        <a href="<?php echo site_url();?>search/<?php echo $rand_channelid;?>" class="my-map pull-left" > 
            My <small>Map</small>
        </a>
        <?php } else{}?>
      <!--
<a href="javascript:void(0);" class="all-map pull-left">Exit <small>All Maps</small></a>
-->
    </div>

  </header>
  