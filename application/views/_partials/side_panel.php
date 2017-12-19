
  <input  type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>"  />                 
  <input  type="hidden" name="prev_channel" id="prev_channel" value="<?php echo $channel_id;?>"  />   
                     
<!-- slide Navigation Start -->
<div id="slideNavigation" class="sidenav">
  

  <!-- // Scroll Div -->
  <div class="scroll-div">
  
  <div class="left-form">
    
    <div class="current-map">
      <div class="col-sm-12">
        <span class="cMapid no-bg"> Current Joined Map ID is <i></i> <b><?php echo $join_key;?></b> </span>
      </div>
    </div>

    <div class="content-row clearfix">
      <div class="col-sm-6">
        <label for="displayName">My Display Name</label>
        <span>My Public User Tag <br /> Display Name. (optional)</span>
      </div>
      <div class="col-sm-6">
       
       <!-- <input class="display-name text-field" type="text" name="display_name" id="display_name" value="<?php //echo $display_name;?>" placeholder="Display Name" />-->
         <span class=""><?php echo $display_name;?></span>
      
        <button type="button" class="edit tool-tip" data-text="Edit" data-color="green" data-position="left" onclick="openModals('display_name_update')"> <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit" /> </button>

      </div>
    </div>
    
    <div class="content-row clearfix">
      <div class="col-sm-6">
        <label for="displayName">My Guest Map ID</label>
        <span>My Searchable HMGPS  <br /> Map ID.</span>
      </div>
      <div class="col-sm-6">
       
       <!-- <input type="text"  id="phone" value="<?php //echo $channel_id;?>" name="phone" class="map-id form-control text-field" readonly /> -->
       <span class=""><?php echo $channel_id;?></span>
      
        <button type="button" class="edit tool-tip" data-text="Edit" onclick="openModals('map_id_update');"  data-color="green" data-position="left"> <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit"> </button>

      </div>
    </div>
    
  </div>


  <!--
<div class="left-nav">
    <ul>
      <li><a href="<?php //echo site_url();?>search/<?php //echo $channel_id; ?>"><i class="fa fa-search"></i>Search</a></li>
      <li><a data-toggle="modal" data-target="#searchshare" onclick="openModals('social_share');"><i class="fa fa-share-alt"></i> Share</a></li>     
    </ul>
  </div>
-->

  <div class="content-row clearfix">
    <div class="col-sm-6">
     <label for="">Make Visible on Map</label>
     <div id="visiblestatus2">
         <?php $this->load->view('_partials/visible_status', array('visible' => $visible)); ?>
      </div>
    </div>

   
  </div>

  <div class="content-row border clearfix">
    <div class="col-sm-6">
        <div class="return-map-footer">
          <a href="<?php echo base_url('search/'.$channel_id);?>" class="view-map">
            <?php echo $map_disp_str;?>
          </a>
        </div>
    </div>
    
    <div class="col-sm-6">
      <div class="exit-map-footer">
        <a href="javascript:void(0);" onclick="removeAllMaps();">
          Exit <span>All Maps</span>       
        </a>
      </div>
    </div>

  </div>
  <div class="foot-nav">
    <ul>
      <li><a href="<?php echo site_url();?>">Home</a></li>
      <li><a href="<?php echo site_url();?>search/<?php echo $channel_id; ?>">Search</a></li>
      <li><a href="<?php echo site_url();?>">About Us</a></li>
      <li><a href="<?php echo site_url();?>home/Help">Help</a></li>
      <li><a href="<?php echo site_url();?>home/Tellus">Tell us your story</a></li>
      <li><a href="<?php echo site_url();?>home/Privacy_policy">Privacy Policy & Terms and Conditions</a></li>
    </ul>
  </div>
  

  <div class="foot-lable clearfix">
    <div class="col-sm-4">
     <a href="https://play.google.com/store/apps/details?id=com.hmgps&hl=en" target="_blank" class="app-store tool-tip" data-text="Coming Soon" data-color="orange">    
        <img src="<?php echo site_url();?>assets/images/app-store.png" class="img-responsive" alt="">
      </a>
    </div>
    
  </div>
</div>


<!-- Scroll Div // -->

</div>
<!-- slide Navigation End -->

  