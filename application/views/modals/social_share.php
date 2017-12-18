<!-- Modal -->
  <div class="modal fade" id="social_share" style="display: none;" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Quick share - Current map</h4>
        </div>
        <div class="modal-body">
          <input  />
          <div id="searchmapshare">
               
            </div>
            <div>
              <div class="sec-9 share-map">
              <input name="joined_map" id="joined_map" type="hidden" />
                 <label>Current Joined Map ID : </label>
                 <input type="radio" name="share_map_type" class="share_mp_type" id="share_mp_join_key" onclick="share_map('join_key');" value="" />
                 <label>My Map</label>
                 <input type="radio" name="share_map_type" checked="checked" id="share_mp_channel" class="share_mp_type" onclick="share_map('own_channel');" value="" />
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
      <a href="javascript:;" class="cpy_clip" >
        <img src="<?php echo base_url();?>assets/images/copy.png"  class="img-responsive" alt="copy" />
      </a>
   </div>

    <div class="sec-9 clearfix"> 
      <a class="sms sms_share" >
        <img src="<?php echo base_url();?>assets/images/sms.png"  class="img-responsive" alt="sms" target="_blank" />
      </a>
   </div>
   <div class="sec-9 clearfix"> 
      <a class="email_share" target="_blank" >
        <img src="<?php echo base_url();?>assets/images/email_send.png"  class="img-responsive" alt="email" />
      </a>
   </div>
 </div>