
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
               <input type="text" placeholder="<?php echo $channel_id;?>" value="<?php echo $channel_id;?>" class="edit-id text edit-map-id" onclick="openModals('map_id_update');" >
               <span class="edit-map-id edit-ico glyphicon glyphicon-pencil __web-inspector-hide-shortcut__" aria-hidden="true" onclick="openModals('map_id_update');"></span>
            </div>
         </div>
      </div>


      <div class="display-name clearfix">
         <div class="col-xs-12">
            <div class="pencil-cover">
               <input type="text" placeholder="Enter Your Name" class="text" name="display" id="display_name" value="<?php echo $display_name;?>" />
               <button type="button" onclick="openModals('display_name_update');" class="edit-display-name edit-ico glyphicon glyphicon-pencil" aria-hidden="true" data-text="Submit" data-color="green" data-position="left" style="display:block;" ></button>
               <label for="display-name">Your Public User Tag Display Name. (optional)</label>
            </div>
         </div>
      </div>


      <div class="display-name clearfix">
         <div class="col-xs-12">
            <div class="pencil-cover">
               <input type="hidden" placeholder="Enter Your Phonenumber" class="text" name="phone" id="phone" value="<?php echo $phonenumber;?>" />
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


      <form method="post" id="main_search" action="<?php echo site_url();?>search">
         <div class="search-wrapper clearfix">
            <div class="col-xs-12">
               <div class="input-group">
                  <input type="text" value="<?php echo $join_key;?>" name="join_key" id="join_key" class="form-control" placeholder="Enter channel ID">
                  <span class="input-group-btn">
                     <input class="btn btn-default"  id="search_btn1" name="submit" type="submit" value="SEARCH" />
                  </span>
               </div>
               <!-- /input-group -->
            </div>
         </div>
      </form>


      <div class="map-action clearfix">
         <div class="col-xs-6">
            <div class="view-map">
            	<a href="<?php echo base_url();?>search/<?php echo $channel_id; ?>">
            		<?php echo $map_disp_str;?>
				   </a>
			</div>
         </div>
         <div class="col-xs-6">
            <div class="exit-map">
               <a onclick="removeAllMaps()">
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
                  <?php $this->load->view('_partials/visible_status', array('visible' => $visible)); ?>
               </div>
            </div>
         </a>
      </div>

   </div>
</center>

