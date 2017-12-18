
  <input  type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>"  />                 
  <input  type="hidden" name="prev_channel" id="prev_channel" value="<?php echo $channel_id;?>"  />   
                     
<!-- slide Navigation Start -->
<div id="slideNavigation" class="sidenav">
  

  <!-- // Scroll Div -->
  <div class="scroll-div">
  
  <div class="left-form">
    
    <div class="current-map">
      <div class="col-sm-12">
        <span class="cMapid"> Current Joined Map ID is <i></i> <b><?php echo $join_key;?></b> </span>
      </div>
    </div>

    <div class="content-row clearfix">
      <div class="col-sm-6">
        <label for="displayName">My Display Name</label>
        <span>My Public User Tag <br /> Display Name. (optional)</span>
      </div>
      <div class="col-sm-6">
       
        <input class="display-name text-field" type="text" name="display_name" id="display_name" value="<?php echo $display_name;?>" placeholder="Display Name" />
      
        <button type="button" class="edit tool-tip" data-text="Edit" data-color="green" data-position="left" onclick="openModals('display_name_update')"> <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit" /> </button>

      </div>
    </div>
    
    <div class="content-row clearfix">
      <div class="col-sm-6">
        <label for="displayName">My Guest Map ID</label>
        <span>My Searchable HMGPS  <br /> Map ID.</span>
      </div>
      <div class="col-sm-6">
       
       <input type="text"  id="phone" value="<?php echo $channel_id;?>" name="phone" class="map-id form-control text-field" readonly />
      
        <button type="button" class="edit tool-tip" data-text="Edit" onclick="openModals('map_id_update');"  data-color="green" data-position="left"> <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit"> </button>

      </div>
    </div>
    
  </div>


  <div class="left-nav">
    <ul>
      <li><a href="<?php echo site_url();?>search/<?php echo $channel_id; ?>"><i class="fa fa-search"></i>Search</a></li>
      <li><a data-toggle="modal" data-target="#searchshare"><i class="fa fa-share-alt"></i> Share</a></li>     
    </ul>
  </div>

  <div class="content-row clearfix">
    <div class="col-sm-6">
     <label for="">Make Visible on Map</label>
     <div id="visiblestatus2">
         <?php $this->load->view('_partials/visible_status', array('visible' => $visible)); ?>
      </div>
    </div>

    <div class="col-sm-6 manual-address">
      <label for="" data-or="true">Enter Manual address</label>
      <input type="text" class="text-field" placeholder="Add your Address" id="guest_address" />
      
      <button type="submit" onclick="create_map('guest_pos_type','','#guest_address','manual');" class="btn btn-info btn-mupdate">
        Update
      </button>

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
      <li><a href="<?php echo site_url();?>home">Home</a></li>
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

<!-- Modal -->
  <div class="modal fade" id="searchshare" style="display: none;" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Quick share - Current map</h4>
        </div>
        <div class="modal-body">
          <div id="searchmapshare">
               
            </div>
            <div>
              <div class="sec-9 share-map">
              <input name="joined_map" id="joined_map" type="hidden" />
                 <label>Current Joined Map ID : </label>
                 <input type="radio" name="share_map_type" class="share_mp_type" onclick="share_map('<?php echo (!empty($map_search_key))?$map_search_key:$channel_id; ?>');" value="<?php echo (!empty($map_search_key))?$map_search_key:$channel_id; ?>" /><?php echo (!empty($map_search_key))?$map_search_key:$channel_id; ?>
                 <label>My Map</label>
                 <input type="radio" name="share_map_type" class="share_mp_type" onclick="share_map('<?php echo $channel_id; ?>');" value="<?php echo $channel_id; ?>" /><?php echo $channel_id; ?>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
  

  
  
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