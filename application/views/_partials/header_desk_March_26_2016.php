   <?php $search_val  = ($map_search_key)?$map_search_key:get_cookie('map_search'); 
      $rand_channelid =  (isset($user_info['channel_id']))?$user_info['channel_id']:'';
      $display_name   =  (isset($user_info['display_name']) && $user_info['display_name']!='')?$user_info['display_name']:$rand_channelid;
      $group_id       =  (isset($user_info['group_id']) && $user_info['group_id']!='')?$user_info['group_id']:'';
   
    ?>
   
    <header class="cf"><!-- Header area Start-->
      
      <div class="container cf"><!-- Container area Start-->

       <div class="row"> 

            <div class=" col-xs-12 col-sm-2 col-md-2 logo"> 
                <a href="<?php echo site_url('home');?>"><img src="<?php echo base_url();?>assets/image/header-logo.png" class="img-responsive" alt="Responsive image"></a>
            </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 mrb ">    

          <div class="row"> 
     
              <div class="header-section-one clearfix">
                    <div class="col-md-10 pal">         
                      <div class="header-inner-top">
                			   <span>
                              <img class="logo" class="logo-by" src="<?php echo base_url();?>assets/image/logo.png" class="img-responsive" alt="logo">
                              <h3>911GPS.me</h3> 
                              <h1>Quickly find anyone or be found. Put yourself on a live map.</h1>
                               
                           </span>
                      </div>
                    </div> 
                 
                    <div class="col-md-2 top-nav">
                 
                      <div class="top-menu">
                            <ul class="nav navbar-nav">
                              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                  <li class="active-current"><a href="<?php echo site_url('home');?>">Home</a></li>
                                  <li><a href="<?php echo site_url('search');?>">Search </a></li>
                                  <li><a href="<?php echo site_url('aboutus');?>">About Us</a></li>
                                  <li><a href="<?php echo site_url('help');?>">Help</a></li>
                                  <li><a href="<?php echo site_url('tellus');?>">Tell Us Your Story</a></li>
                                  
                                </ul>
                              </li>
                            </ul>
                        </div>
                   
                    </div> 
                </div>    
       
                <div class="desktop-header">
                                 
                  <div class="col-md-3 section-one box-border"> 

                      <div class="sec-1"> <img src="<?php echo base_url();?>assets/image/search.png" class="img-responsive" alt="Responsive image" ><h4>Quick Search</h4>
                      
                      <?php if((isset($maps['is_joined']) && ($maps['is_joined'] == 1) && ($search_val != $rand_channelid)) || (!empty($search_val) && ($search_val != $rand_channelid))) {  ?>
                    <span class="join-map">Map Joined 
                        <button class="close" type="button" onclick="group_user_delete('<?php echo $maps['group_id'];?>', '<?php echo $maps['user_id'];?>','<?php echo $rand_channelid; ?>')"><i class="glyphicon glyphicon-remove"></i></button>
                    </span>
                    <?php } else if((isset($maps['is_joined']) && ($maps['is_joined'] == 0)) || (!isset($maps['is_joined']))) { ?>
                    <span class="no-map">
                      <img src="<?php echo base_url();?>assets/image/no-map-joined.png" />
                    </span>  
                    <?php } ?>
                      </div> 
                      
                      <form method='post' action='<?php echo site_url('search');?>'>
                          
                          <div class="sec-2"> <img src="<?php echo base_url();?>assets/image/04_maps.png" class="img-responsive" alt="Responsive image"  >
                              <div id="custom-search-input">
                                    <div class="input-group">
                                        <input type="text"  value="<?php echo $search_val;?>" name="search" id="search" class="  search-query form-control" placeholder="Search"/>
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger go-search" type="submit" data-toggle="tooltip" data-placement="top" title="Channel search">
                                               <img src="<?php echo base_url();?>assets/image/GO-SEARCH.png" class="img-responsive" alt="Responsive image"  >
                                            </button>
                                        </span>
                                    </div>
                              </div>
                          </div> 

                          <div class="sec-3"> <img src="<?php echo base_url();?>assets/image/unlock_green.png" class="img-responsive" alt="Responsive image" >
                            <input type="password" value="" name="pwd" id="pwd" placeholder="Enter password if Required">
                          </div> 
                      </form>    

                      <div class="sec-last"> 
                          <ul>
                              <li><p>get the app!</p></li>
                              <li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Download Android App"><img src="<?php echo base_url();?>assets/image/android.png" class="img-responsive" alt="android" ></a></li>
                              <li><a href="#" data-toggle="tooltip" data-placement="bottom" title="Download iPhone App"><img src="<?php echo base_url();?>assets/image/apple.png" class="img-responsive" alt="/apple" ></a></li>
                          </ul>

                      </div> 

                  </div>  
                       
                  <div class="col-md-5 box-border section-tow">  

                      <input  type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>"  />                 
                      <input  type="hidden" name="prev_channel" id="prev_channel" value="<?php echo $rand_channelid;?>"  />   
                                   
        <div class="welcome-section"><img src="<?php echo base_url();?>assets/image/me-map.png" class="img-responsive me-map" alt="me-map" >
        <div class="wel-come">
        
        <?php if((!empty($rand_channelid)) && ($search_val != $rand_channelid) && empty($search_val)) { ?>
        <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" class="view-map">View My Map</a>
        <?php } else if(!empty($search_val) && ($search_val != $rand_channelid)) { ?>
         <a href="<?php echo base_url();?>search/<?php echo $rand_channelid; ?>" ><img class="my-mapp" src="<?php echo base_url();?>assets/image/return-to-map1.png" /></a>
         <?php }else if($search_val == $rand_channelid){?>
         <img class="my-mapp" src="<?php echo base_url();?>assets/image/my-map.png" />
        <?php } else{}?>
                   		 <span class="welcome">welcome HMGPS User</span> </div>  </div>   
                                          

                        <div class="sec-4 clearfix"> 
                           <p>Enter a Display Name(optional)</p>
                           <div class="user-tagg">User Tag Options</div>
                           <div class="dis-paly">
                           <input class="display-name" type="text" name="display_name" id="display_name" value="<?php echo $display_name;?>" placeholder="Display Name" />                 			<div class="arrow_box"></div>
                           </div>
                        </div> 
                        <div class="sec-5 clearfix"> 
                           <p> This is your guest Map Channel ID</p>
                           <img src="<?php echo base_url();?>assets/image/my-chn-id.png" class="img-responsive channel-id-img" alt="Channel ID" >
                           
                            <div class="input-group">
                                <input type="text"  id="phone" value="<?php echo $rand_channelid;?>" name="phone" class="map-id form-control" readonly/>
                                <span class="input-group-addon">
                                    <span class=" glyphicon glyphicon-pencil" onclick="editName('phone')"></span>
                                </span>
                            </div>
                            <!--<a href="javascript:void(0);" onclick="editName('phone')"><b>Click to edit ID</b></a>-->
                        </div>

                        <div class="clear"></div>

                        <div class="sec-6"> 
                          <!--
                            <label for="guest_current">
                              <input  name="guest_pos_type" id="guest_current" value='1' type="radio" checked="checked">                      
                              <img src="<?php echo base_url();?>assets/image/my-accout.png" class="img-responsive" alt="my-accout"> 
                            </label>
                            -->
                            <div class="radio chk-box-radio">
                                <label>
                                    <input type="radio" name="guest_pos_type" id="guest_current" value='1' type="radio" checked="checked">
                                    <span class="cr my-location"><i class="cr-icon fa fa-check"></i></span>
                                   
                                </label>
                            </div>
                            <h3>Allow my location to be shown if browser permits</h3>
                            <!--
                            <label for="guest_manual">
                              <input  name="guest_pos_type" id="guest_manual"  value='2' type="radio">
                            	<img src="<?php echo base_url();?>assets/image/keyboard.png" class="img-responsive" alt="keyboard" id="popover" rel="popover" data-placement="bottom" height="40" width="40">
                            </label>
                            -->
                             <div class="radio chk-box-radio">
                                <label>
                                    <input type="radio" name="guest_pos_type" id="guest_manual" value='2' >
                                    <span class="cr manual-address" id="popover" rel="popover" data-placement="bottom"><i class="cr-icon fa fa-check"></i></span>
                                   
                                </label>
                            </div>
                              <?php $usrchk = ($user_info['user_id']!='')?'user_update':'guest_registration';?>
                              <button type="submit" onclick="create_map('guest_pos_type',<?php echo $usrchk;?>,'.popover-content #guest_address','manual');" class="btn btn-info btn-mupdate">
                                <!--
<span class="glyphicon glyphicon-arrow-right"></span>

                                <img src="<?php //echo base_url();?>assets/image/update-icon.png" alt="update">-->
                                Update
                              </button>

                            <div id="popover-content" class="hide">
                              <textarea class="form-control" id="guest_address" name="guest_address" placeholder="Enter Address"></textarea>
                            </div> 

                       </div> 
                      
                  </div>
                       
                  <div class="col-md-4 box-border section-three"> 
                       <!--
                        <div class="sec-7 clearfix"> 
                           <a href="javascript:;" id="quickshareicon"><img src="<?php //echo base_url();?>assets/image/share-icon.png" class="img-responsive" alt="share-icon"></a>
                            <span class="Quick-share" id="create-map">Quick Share</span> 
                        </div> 
                        -->
                         <div class="sec-27 clearfix"> 
                           <a data-toggle="modal" data-target="#searchshare"><img class="quick-shar" src="<?php echo base_url();?>assets/image/quick-share.png" class="img-responsive" alt="quick-share-icon"></a>
                        </div> 
                         <div class="sec-8 clearfix"> 
                            <a><img src="<?php echo base_url();?>assets/image/067661-yellow-comment-bubbles-icon-people-things-speech.png" class="img-responsive" alt="speech" ></a>
                            <b> To verbally Share Use this ID</b>
                            <h3><?php echo $search_val;?></h3>
                         </div> 

                         <div class="sec-9 clearfix"> 
                           <div id="alert" role="alert"></div>
                              <a href="javascript:;" onclick="copyToClipboard('#phone','#alert')"  >
                                <img src="<?php echo base_url();?>assets/image/copy.png"  class="img-responsive" alt="copy">
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
                         <div id="socialquickshare" class="sec-10 social">
                            
                         </div>
                        
                  </div>
              </div>
        </div>
      </div>
      
  
</header>  