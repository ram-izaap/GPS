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
                 <input type="radio" name="share_map_type" class="share_mp_type" onclick="share_map('<?php echo (!empty($map_search_key))?$map_search_key:$rand_channelid; ?>');" value="<?php echo (!empty($map_search_key))?$map_search_key:$rand_channelid; ?>" /><?php echo (!empty($map_search_key))?$map_search_key:$rand_channelid; ?>
                 <label>My Map</label>
                 <input type="radio" name="share_map_type" checked="checked" class="share_mp_type" onclick="share_map('<?php echo $rand_channelid; ?>');" value="<?php echo $rand_channelid; ?>" /><?php echo $rand_channelid; ?>
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