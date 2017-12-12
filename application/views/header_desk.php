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

        <span class="cMapid"> Current Map ID is <i></i> <b><?php echo $search_val;?></b> </span>

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

        <button class="edit tool-tip" data-text="Edit" data-color="green" data-position="left" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');"  > <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit" /> </button>

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

        <button class="edit tool-tip" data-text="Edit" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');" data-color="green" data-position="left"> <img src="<?php echo site_url();?>assets/images/edit-icon.png" alt="Edit"> </button>

        <button class="submit tool-tip" data-text="Submit" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');" data-color="green" data-position="left"  style="display:none;"> <img src="<?php echo site_url();?>assets/images/tick-icon.png" alt="Done"> </button>

      </div>

    </div>

    

  </div>





  <div class="left-nav">

    <ul>

      <li><a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>"><i class="fa fa-search"></i>Search</a></li>

      <li><a href="javascript:void(0);"><i class="fa fa-share-alt"></i> Share</a></li>

      <li><a href="javascript:void(0);"><i class="fa fa-link"></i> 911GPS.me/Miranda</a></li>

    </ul>

  </div>



  <div class="content-row clearfix">

    <div class="col-sm-6">

      <label for="">Make Visible on Map</label>

      <span> You can start sharing your<br /> location with others from<br /> your device.</span>



      <!--  -->

      <label class="switch">

        <input type="checkbox">

        <div class="slider-eye round"></div>

      </label>

      <!--  -->

    </div>



    <div class="col-sm-6">

      <label for="" data-or="true">Enter Manual address</label>

      <input type="text" class="text-field" placeholder="Add your Address" id="" />

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

            My Map

        </a>

        <?php } else{}?>

          

        </div>

    </div>

    <div class="col-sm-6">

      <div class="exit-map-footer">

        <a href="javascript:void(0);">

          Exit <span>All Maps</span>       

        </a>

      </div>

    </div>

  </div>



  <div class="foot-nav">

    <ul>

      <li><a href="index.html">Home</a></li>

      <li><a href="search.html">Search</a></li>

      <li><a href="index.html">About Us</a></li>

      <li><a href="javascript:void(0);">Help</a></li>

      <li><a href="javascript:void(0);">Tell us your story</a></li>

    </ul>

  </div>



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



  <div class="foot-lable clearfix">

    <div class="col-sm-4">

      <a href="javascript:void(0);" class="app-store tool-tip" data-text="Coming Soon" data-color="orange">

        <img src="<?php echo site_url();?>assets/images/app-store.png" class="img-responsive" alt="">

      </a>

    </div>

    <div class="col-sm-4">

      <a href="javascript:void(0);" class="play-store tool-tip" data-text="Beta">

        <img src="<?php echo site_url();?>assets/images/play-store.png" class="img-responsive" alt="">

      </a>

    </div>

    <div class="col-sm-4">

      <a href="javascript:void(0);" class="product-lable tool-tip" data-text="Direct Download" data-color="blue">

        <img src="<?php echo site_url();?>assets/images/play-store.png" class="img-responsive" alt="">

      </a> 

    </div>

  </div>





</div>



<!-- Scroll Div // -->



</div>

<!-- slide Navigation End -->



<div class="modal fade" id="update_displayname" role="dialog">

    <div class="modal-dialog">

    

      <!-- Modal content-->

      <form name="upd_disp_name" id="upd_disp_name">

      <div class="modal-content">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Update Display Name & Phone Number </h4>

        </div>

        <div class="modal-body">

          <div class="manual-address">

            <div class="row">

              <div class="text-center"><b>Display Name</b></div>

                <input type="hidden" name="guest_user_id" id="guest_user_id" value="<?php echo $user_id; ?>"  />

                <div class="input-group m-t-b-center w-150">

                  <div class="form-control">

                    <input type="radio" class="itxt" name="custom_disp_update"  id="custom_disp_update" <?php echo ($display_name!='' && $updated_type=='custom')?'checked="checked"':""; ?> />

                  </div>

                  <span class="input-group-addon white-bg"><b>Enter Your Name </b></span>

                </div>



                <div class="input-group m-t-b-center w-150">

                    <input type="text" class="form-control" name="custom_display_name" onkeypress="return displayup('#custom_disp_update');" id="custom_display_name" placeholder="Display Name" value="<?php echo $display_name; ?>" />

                </div>

                

                <div class="input-group m-t-b-center w-150">

                  <div class="form-control">

                    <input type="radio" class="itxt" name="custom_disp_update" id="system_disp_update" <?php echo ($display_name!='' && $updated_type=='system')?'checked="checked"':""; ?> />

                  </div>

                  <span class="input-group-addon white-bg"><b>Use System Generated </b></span>

                </div>

               

                <div class="text-center"><b>Phone Number</b></div>



                <div class="input-group m-t-b-center w-150">

                  <div class="form-control">

                    <input type="radio" class="itxt" name="custom_phone_update" id="custom_phone_update" <?php echo ($phonenumber!='' && $updated_phonenumber=='custom')?'checked="checked"':""; ?>  />

                  </div>

                  <span class="input-group-addon white-bg"><b>Enter your Phone Number </b></span>

                </div>



                <div class="input-group m-t-b-center w-150">

                    <input type="text" class="form-control" placeholder="Phone Number" name="custom_phonenumber" onkeypress="return displayup('#custom_phone_update');" id="custom_phonenumber" value="<?php echo $phonenumber; ?>" />

                </div>

                

                <div class="input-group m-t-b-center w-150">

                  <div class="form-control">

                    <input type="radio" class="itxt" name="custom_phone_update" id="system_phonenumber" <?php echo ($phonenumber!='' && $updated_phonenumber=='system')?'checked="checked"':""; ?> />

                  </div>

                  <span class="input-group-addon white-bg"><b>Use System Generated </b></span>

                </div>

            </div>

          </div>

<style>

   .manual-address input[type="text"] {

        min-width:200px;

    

   }

   

  .m-t-b-center {

      margin:5px auto;

  }



  .w-150 {

    max-width: 200px;

  }

  

  .itxt {

    width:inherit !important;

  }

</style>

          

        </div>

        <div class="modal-footer">  

         <button type="button" name="update_dis_name" class="btn btn-default" id="update_dis_name" onclick="update_disp_name();" > Accept JJ </button>

          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

        </div>

      </div>

      </form>

    </div>

  </div>