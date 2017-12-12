<?php 
      $search_val  = ($map_search_key)?$map_search_key:get_cookie('map_search'); 
      $rand_channelid =  (isset($user_info['channel_id']))?$user_info['channel_id']:'';
      $display_name   =  (isset($user_info['display_name']) && $user_info['display_name']!='')?$user_info['display_name']:$rand_channelid;
      $phonenumber    =  (isset($user_info['phonenumber']) && $user_info['phonenumber']!='')?$user_info['phonenumber']:"";
      $group_id       =  (isset($user_info['group_id']) && $user_info['group_id']!='')?$user_info['group_id']:'';
      $updated_type   =  (isset($user_info['updated_type']) && ($user_info['updated_type']))?$user_info['updated_type']:"";
      $updated_phonenumber = (isset($user_info['updated_phonenumber']) && ($user_info['updated_phonenumber']))?$user_info['updated_phonenumber']:"";
      $usrchk = ($user_info['user_id']!='')?'user_update':'guest_registration';
?>
   
    <input  type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>"  />                 
    <input  type="hidden" name="prev_channel" id="prev_channel" value="<?php echo $rand_channelid;?>"  />   
                     
<!-- slide Navigation Start -->
<div id="slideNavigation" class="sidenav">
  

  <!-- // Scroll Div -->
  <div class="scroll-div">
  
  <div class="left-form">
    
    <div class="current-map">
      <div class="col-sm-12">
        <span class="cMapid"> Current Joined Map ID is <i></i> <b><?php echo $search_val;?></b> </span>
      </div>
    </div>

    <div class="content-row clearfix">
      <div class="col-sm-6">
        <label for="displayName">My Display Name</label>
        <span>My Public User Tag <br /> Display Name. (optional)</span>
      </div>
      <div class="col-sm-6">
        <!--
<input type="text" class="text-field" readonly="readonly" placeholder="Add your Name" id="" />
-->
        <input class="display-name text-field" type="text" name="display_name" id="display_name" value="<?php echo $display_name;?>" placeholder="Display Name" />
      <!--  <button class="edit tool-tip" data-text="Edit" data-color="green" data-position="left" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');"  > <img src="<?php //echo site_url();?>assets/images/edit-icon.png" alt="Edit" /> </button> -->
      <button class="edit tool-tip" data-text="Edit" data-color="green" data-position="left" onclick="trig_disp_popup('display_pp');"  > <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit" /> </button>
      
        <button class="submit tool-tip" data-text="Submit" data-color="green" data-position="left" style="display:none;" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');"  > <img src="<?php echo site_url();?>assets/images/tick-icon.png" alt="Done"  /> </button>
      </div>
    </div>
    
    <div class="content-row clearfix">
      <div class="col-sm-6">
        <label for="displayName">My Guest Map ID</label>
        <span>My Searchable HMGPS  <br /> Map ID.</span>
      </div>
      <div class="col-sm-6">
        <!--
<input type="text" class="text-field" readonly="readonly" placeholder="Add your Name" id="" />
-->
       <input type="text"  id="phone" value="<?php echo $rand_channelid;?>" name="phone" class="map-id form-control text-field" readonly />
      <!--  <button class="edit tool-tip" data-text="Edit" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');" data-color="green" data-position="left"> <img src="<?php //echo site_url();?>assets/images/edit-icon.png" alt="Edit"> </button> -->
      <button class="edit tool-tip" data-text="Edit" onclick="trig_disp_popup('display_popup_map_id');"  data-color="green" data-position="left"> <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit"> </button>
      
        <button class="submit tool-tip" data-text="Submit" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');" data-color="green" data-position="left"  style="display:none;"> <img src="<?php echo site_url();?>assets/images/tick-icon.png" alt="Done"> </button>
      </div>
    </div>
    
  </div>


  <div class="left-nav">
    <ul>
      <li><a href="<?php echo site_url();?>search/<?php echo $rand_channelid; ?>"><i class="fa fa-search"></i>Search</a></li>
      <li><a data-toggle="modal" data-target="#searchshare"><i class="fa fa-share-alt"></i> Share</a></li>
     <!--
         <li><a href="<?php //echo site_url();?>search/<?php //echo $rand_channelid; ?>">
         <i class="fa fa-link"></i> 911GPS.me/<?php //echo ucfirst($rand_channelid); ?></a></li>
      -->
    </ul>
  </div>

  <div class="content-row clearfix">
    <div class="col-sm-6">
     <label for="">Make Visible on Map</label>
     <div id="visiblestatus2">
         <?php $this->load->view('visible_status',$this->data); ?>
      </div>
    </div>
    <div class="col-sm-6 manual-address">
      <label for="" data-or="true">Enter Manual address</label>
      <input type="text" class="text-field" placeholder="Add your Address" id="guest_address" />
      <?php $usrchk = ($user_info['user_id']!='')?'user_update':'guest_registration';?>
      <button type="submit" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'#guest_address','manual');" class="btn btn-info btn-mupdate">
        Update
      </button>
    </div>
  </div>
  <div class="content-row border clearfix">
    <div class="col-sm-6">
        <div class="return-map-footer">
           <?php $uri = $this->uri->segment(1); if(((!empty($rand_channelid)) && ($search_val != $rand_channelid) && empty($search_val)) || ($uri != 'search')) { ?>
        <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" class="view-map">View My Map</a>
        <?php } else if(!empty($search_val) && ($search_val != $rand_channelid)) { ?>
         <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" > Return to <span>My Map</span></a>
         <?php }else if($search_val == $rand_channelid){?>
        <a href="<?php echo site_url();?>search/<?php echo $rand_channelid;?>" > 
            My <small></small>Map
        </a>
        <?php } else{}?>
        </div>
    </div>
    
   <div class="col-sm-6">
      <div class="exit-map-footer">
        <a href="javascript:void(0);" onclick="remove_user_from_all_groups();">
          Exit <span>All Maps</span>       
        </a>
      </div>
    </div>

  </div>
  <div class="foot-nav">
    <ul>
      <li><a href="<?php echo site_url();?>">Home</a></li>
      <li><a href="<?php echo site_url();?>search/<?php echo $rand_channelid; ?>">Search</a></li>
      <li><a href="<?php echo site_url();?>">About Us</a></li>
      <li><a href="<?php echo site_url();?>help">Help</a></li>
      <li><a href="<?php echo site_url();?>tellus">Tell us your story</a></li>
      <li><a href="<?php echo site_url();?>privacy-policy-and-terms-and-conditions">Privacy Policy & Terms and Conditions</a></li>
    </ul>
  </div>
  <!--
<div class="social-bar">
    <ul>
      <li><a href="https://www.facebook.com/" target="_blank" class="tool-tip" data-text="Facebook" data-color="green">
        <i class="fa fa-facebook" aria-hidden="true"></i></a>
      </li>
      
      <li><a href="https://twitter.com/" target="_blank" class="tool-tip" data-text="Twitter" data-color="green">
        <i class="fa fa-twitter" aria-hidden="true"></i></a>
      </li>

      <li><a href="https://plus.google.com/" target="_blank" class="tool-tip" data-text="Google+" data-color="green">
        <i class="fa fa-google-plus" aria-hidden="true"></i></a>
      </li>
      
      <li><a href="https://pinterest.com/" target="_blank" class="tool-tip" data-text="Pinterest" data-color="green">
        <i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
      </li>
      
      <li><a href="https://www.tumblr.com/" target="_blank" class="tool-tip" data-text="Tumblr" data-color="green">
        <i class="fa fa-tumblr" aria-hidden="true"></i></a>
      </li>
    </ul>
  </div>
-->

  <div class="foot-lable clearfix">
    <div class="col-sm-4">
     <a href="https://play.google.com/store/apps/details?id=com.hmgps&hl=en" target="_blank" class="app-store tool-tip" data-text="Coming Soon" data-color="orange">
    
        <img src="<?php echo site_url();?>assets/images/app-store.png" class="img-responsive" alt="">
      </a>
    </div>
    <!--
<div class="col-sm-4">
      <a href="javascript:void(0);" class="play-store tool-tip" data-text="Beta">
        <img src="<?php //echo site_url();?>assets/images/play-store.png" class="img-responsive" alt="">
      </a>
    </div>
    <div class="col-sm-4">
      <a href="javascript:void(0);" class="product-lable tool-tip" data-text="Direct Download" data-color="blue">
        <img src="<?php //echo site_url();?>assets/images/play-store.png" class="img-responsive" alt="">
      </a> 
    </div>
-->
  </div>
</div>
<!--
<div id="socialquickshare" class="sec-10 social">
                            
</div>
-->

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
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
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
                    <input type="text" class="form-control" name="custom_display_name" aria-describedby="basic-addon1" onkeypress="return displayup('#custom_disp_update');" id="custom_display_name" placeholder="Custom Display Name" value="" />
                  </div>
              </div>

              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dname1">
                          <input type="radio" class="itxt" name="custom_disp_update" id="system_disp_update" <?php echo ($display_name!='' && $updated_type=='system')?'checked="checked"':""; ?> />
                        </label>
                      </span>
                    <input type="text" class="form-control" value="Use System Generated(<?php echo $display_name; ?>)" disabled="disabled" aria-describedby="basic-addon2">
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
                      <input type="text" class="form-control" placeholder="Custom Phone Number" aria-describedby="basic-addon1" name="custom_phonenumber" onkeypress="return displayup('#custom_phone_update');" id="custom_phonenumber" value="" />
                    
                  </div>
              </div>

              <div class="panel-gutter" style="<?php echo ($phonenumber == ''?'display:none':''); ?>"" >
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dno1">
                          <input type="radio" class="itxt" name="custom_phone_update" id="system_phonenumber" <?php echo ($phonenumber!='' && $updated_phonenumber=='system')?'checked="checked"':""; ?> />
                        </label>
                      </span>
                    <input type="text" class="form-control" value="Use System Generated(<?php echo $phonenumber; ?>)" disabled="disabled" aria-describedby="basic-addon2">
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

  <div class="modal fade" id="update_map_id" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
    
      <!-- Modal content-->
      <form name="upd_disp_name" id="upd_disp_name">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center"><b>Update Map ID</b></h4>
          </div>
        <div class="modal-body">
         <!--  -->
        <div class="tab-wrapper">

          
         <input type="hidden" name="guest_user_id" id="guest_user_id" value="<?php echo $user_id; ?>"  />
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="displayName">              
              <div class="panel-gutter">
                <div class="">
                  <input type="text" class="form-control" name="custom_map_id" aria-describedby="basic-addon1" id="custom_map_id" placeholder="Map ID" value="" />
                </div>
              </div>              
            </div>            
          </div>
        </div>
        <!--  -->
        </div>
        <div class="modal-footer">  
         <button type="button" name="update_dis_name" class="btn btn-default btn-green" id="update_dis_name" onclick="update_map_id();" > Accept </button>
          <button type="button" class="btn btn-default btn-gray" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>