
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


   <!--  Nav Menu -->
   <div id="slideNavigation_mobile" class="sidenav">
      <a href="javascript:void(0)" class="closebtn" onclick="closeNav('slideNavigation_mobile')">&times;</a>
      <a href="<?php echo base_url(); ?>">Home</a>
      <a href="<?php echo base_url(); ?>search/<?php echo $join_key; ?>">Search</a>
   </div>

   <!-- HEADER BEGINS -->
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
            <?php $this->load->view('_partials/visible_status', array('visible' => $visible)); ?> 
         </div>
      </div>

      <div class="col-xs-2 text-center viewmymapstatus">
         <div class="top-my-map">   
            <a href="<?php echo base_url();?>search/<?php echo $channel_id; ?>">
               <?php echo $map_disp_str;?>
            </a>
         </div>
      </div>

      <div class="col-xs-2 text-center exitallmap">
         <div class="top-exit">
            <a onclick="removeAllMaps()">
            <span>Exit</span>
            <img src="<?php echo base_url();?>/assets/images/exit-map.png" class="" alt="" />
            </a>
         </div>
      </div>

   </header>

   <!-- HEADER ENDS -->

   <!-- SEARCH BAR SECTION BEGINS -->
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
                  <input type="text" value="<?php echo $join_key;?>" name="join_key" id="join_key" class="form-control" placeholder="Enter Channel ID" />
                  </span>
                  <span class="search-wrap pass-search pull-left">
                  <input type="password" name="password" id="password" value="" class="form-control" placeholder="password" />
                  </span>
               </div>

               <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left">
               <input type="button" value="&nbsp;" class="go-search subm" id="search_btn" />       
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

   <!-- SEARCH BAR SECTION ENDS -->




   <div class="map-viewer">
      <!-- Map Layer -->
      <div class="map-layer-top clearfix"> 
         <div class="m-channel-info">
            <span> Display Name: <b><?php echo $channel_id; ?></b></span>
            <span> Map id: <b><?php echo $display_name; ?></b></span>
         </div>

         <span class="mobile--share">
            <a  data-toggle="modal" data-target="#searchshare_mb" onclick="openModals('social_share');">
            <i class="fa fa-share-alt fa-2x"></i></a>
         </span>      
      </div>

      <div id="latlang" style=""></div>

      <div class="col-xs-4 timer">
         <div class="pull-right">
            <span class="btn btn-default timer-btn">
               <h2 id="re-load"><time class="timer">00:00</time></h2>
            </span>
         </div>
      </div>


      <div id="map" style="width:100%; height: 600px;"></div>


   </div>



   <div class="map-layer-bottom">
      <div class="conta-iner clearfix">

         <div class="col-xs-4">
            <div class="drop-wrap btn-participant timer-btn">
               
               <div class="dropup">
                  <button class="btn btn-default dropdown-toggle parti" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Participants (<span id="participants-count">0</span>) <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" id="participants-list">
                     <a class="close_pop" onclick="add_toggle();">CLOSE</a>
                     <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#show-0">
                           <i class="fa fa-users" aria-hidden="true"></i> All
                           </a>
                        </li>
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
                           </a>
                        </li>
                     </ul>
                     <div class="tab-content">
                      <div id="show-0" class="tab-pane fade in active ">
                        <div class="" aria-labelledby="dropdownMenu2" id="tab0"></div>
                      </div>
                        <div id="show-1" class="tab-pane fade in">
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
                   
            </div>
         </div>

         <!--NIGHT MODE -->  
         <div class="col-xs-4 map-sel" style="text-align: center;">         
            <span>
               <input type="radio" name="mode_type" id="deafult_mode" value="deafult" class="selector-control"  />
               <label for="deafult_mode" style="color: #fff;">Default</label>
            </span>
            <span>
               <input type="radio" name="mode_type" id="night_mode" value="night" class="selector-control"  />
               <label for="night_mode" style="color: #fff;">Night</label>      
            </span>
         </div>


      </div>
   </div>


<div id="participant-template" style="display: none;">
  <li>
    <div class="p-parti">
      <span class="name"><b>DN:</b></span>
      <span class="name display_name"></span>
      <br/>
      <span class="name"><b>CHID:</b></span>
      <span class="name channel_id"></span>
    </div>
    <div class="p-find-iocn">
      <a href="javascript:;" class="myposition sprite-image">&nbsp;</a>
      <a href="javascript:;" class="statuspop sprite-image">&nbsp;</a>
    </div>
  </li>
</div>

<div id="ram1122" style="display: none;">
  <li>
    <div class="p-parti">
      <span class="name"><b>DN:</b></span>
      <span class="name display_name"></span>
      <br/>
      <span class="name"><b>CHID:</b></span>
      <span class="name channel_id"></span>
    </div>
    <div class="p-find-iocn">
      <a href="javascript:;" class="myposition sprite-image">&nbsp;</a>
      <a href="javascript:;" class="statuspop sprite-image">&nbsp;</a>
    </div>
  </li>
</div>


<div id="map_info_wndow" style="display:none">
   <div class="map-locator">
      <div class="map-pic clearfix">
         <div class="col-xs-4">
            <img src="" class="profile-img img-responsive img-circle" alt="user-image" onerror="this.src='http://heresmygps.com/assets/images/logo.png';">
         </div>
         <div class="col-xs-8">
            <p> 
              <i class="fa fa-user pull-left" aria-hidden="true"></i>
              <small> HMGPS User ID</small> <small class="channel_id"></small> 
            </p>
            <p> 
              <i class="fa fa-user pull-left" aria-hidden="true"></i> 
              <small>Display Name: </small> <small class="display_name"></small> 
            </p>
            <small></small>
         </div>
         <small></small>
      </div>
      <div class="map-record clearfix">
         <small>
            <div class="col-xs-12 clearfix">
               <p> 
                  <i class="fa fa-clock-o pull-left" aria-hidden="true"></i> 
                  <small>Position Time Updated</small>
                </p>
                <p class="lastseen"></p>
            </div>
            <div class="col-xs-3 clearfix">
               <p> <small>Speed</small> <small class="speed"></small> </p>
            </div>
            <div class="col-xs-6 clearfix">
               <p> <small>Accuracy</small> <small class="accuray"></small>  </p>
            </div>
            <div class="col-xs-12 clearfix">
              <p> 
                <i class="fa fa-globe pull-left" aria-hidden="true"></i> 
                <small>GPS Coordinate</small> 
                <small class="lat"></small>, &nbsp;&nbsp;&nbsp;  
                <small class="lng"></small> 
              </p>
            </div>
         </small>
      </div>

      <small>

        <div class="track-buttons clearfix">
          <label for="1">
            Track User
            <input value="track" class="track_userr" onclick="mapManager.trackUser(this);" type="checkbox">
          </label>
          <label for="2">
            10 mins
            <input onclick="breadcrumb();"  class="breadcrumb "  type="checkbox">
          </label>
          <label for="3">
            24 Hrs
            <input onclick="breadcrumb();"  class="breadcrumb"  type="checkbox">
          </label>
          <label for="4">
            24 Hrs in Detail 
            <input onclick="breadcrumb();"  class="breadcrumb" type="checkbox">
          </label>
        </div>

        <div class="map-action">
            <div class="map-buttons">
               <span class="tool-tip" data-text="Navigate">
                <a id="navigate_link">
                  <i class="fa fa-map-pin fa-2x" aria-hidden="true"></i>
                </a>
              </span>
             <!-- <span class="tool-tip" data-text="Call">
                <a id="call_link">
                  <i class="fa fa-phone fa-2x" aria-hidden="true"></i>
                </a>
              </span>
              <span class="tool-tip" data-text="SMS">
                <a class="sms" id="sms_link">
                  <i class="fa fa-commenting fa-2x" aria-hidden="true"></i>
                </a>
              </span>-->
              <span class="tool-tip" data-text="Email">
                <a id="email_link">
                  <i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
                </a>
              </span>
            </div>
         </div>
         
        <div class="btn-group btn-group-justified" role="group" aria-label="...">
          <div class="btn-group text-center" role="group">
            <button onclick="mapManager.breadcrumb();" type="button" class="btn btn-default btn-success"> OK </button>
          </div>
          <div class="btn-group" role="group">
             <button onclick="mapManager.clearTracking(this)" type="button" class="btn btn-default btn-info">Clear Tracking</button>
          </div>
          <div class="btn-group" role="group">
             <button onclick="mapManager.closeinfowindow()" type="button" class="btn btn-default btn-danger"> Close </button>
          </div>
       </div>

      </small>

   </div>   
</div>




