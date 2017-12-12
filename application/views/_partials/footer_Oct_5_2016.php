  <div class="modal fade" id="create_new_map">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Create a new Map channel</h4>
        </div>
        <div class="modal-body">

              <form class="form-horizontal">

                  <div class="form-group">
                      <label for="display" class="control-label col-xs-3">Display Name</label>
                      <div class="col-xs-8">
                        <span class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                          <input type="text" class="form-control" id="display" name="display" placeholder="Enter Display Name">
                         </span> 
                        <div style="color:#a94442;display:none;" id="display_error" >Display name should be minimum 5 characters</div>  
                      </div>                     
                  </div>

                  <div class="form-group">
                      <label for="channel" class="control-label col-xs-3">Channel ID</label>
                      <div class="col-xs-8"> 
                        <span class="input-group">                               
                          <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
                          <input type="text" class="form-control" id="channel" value="<?php echo $rand_channelid;?>" name="channel" placeholder="Name your Map">
                        </span>
                        <div style="color:#a94442;display:none;" id="channel_error">Please enter a valid channel ID</div>
                      </div>             
                  </div>               

                  <div class="form-group">
                      <label for="map_pwd" class="control-label col-xs-3">Password</label>
                      <div class="col-xs-8">
                         <span class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                          <input type="password" class="form-control" id="map_pwd" name="map_pwd" placeholder="Assign password if required">
                        </span>
                        <div style="color:#a94442;display:none;" id="passwd_error" >password should be minimum 5 characters</div>  
                      </div>
                     
                  </div>

                  <div class="form-group">
                    <label for="map_pwd" class="control-label col-xs-3">Location Type</label>
                    <div class="col-xs-8"> 
                      <span class="input-group">                      
                        <input  name="location_type" id="location_type" onclick="locationtype(this.value,'show_address');" value='1' type="radio" checked="checked"> Show my location
                        &nbsp;&nbsp;
                        <input  name="location_type" id="location_type" onclick="locationtype(this.value,'show_address');" value='2' type="radio"> Manual Address
                      </span> 
                    </div>
                  </div>

                  <div class="form-group" id="show_address" style="display:none;">
                      <label for="address" class="control-label col-xs-3">Address</label>
                      <div class="col-xs-8">
                          <textarea class="form-control" id="address" name="address" placeholder="Enter Address"></textarea>
                          <div style="color:#a94442;display:none;" id="address_error" >Please eneter valid address</div>  
                      </div>
                     
                  </div>

              </form>
        </div>
        <div class="modal-footer">
          <div class="quick-info col-xs-9" style="font-size:11px;text-align:justify;border: 1px solid #ccc;">All Quickshare Map Channels aredeleted from our database after 24 hours. Create and manage your own permanent maps with a free account and get the mobile app at Here'sMyGPS.com</div>
          <button type="button" onclick="create_map('location_type',create_quickshare_map,'#address');" class="btn btn-success">Create My <br/>Map</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

 <footer class="martop"><!-- Footer area Start-->
      <div class="container">
        <ul class="footer-links">
          <li><a href="<?php echo site_url('home');?>">Home</a></li>
          <li><a href="<?php echo site_url('search');?>">Search</a></li>
          <li><a href="<?php echo site_url('aboutus');?>">About Us</a></li>
          <li><a href="<?php echo site_url('help');?>">Help</a></li>
          <li><a href="<?php echo site_url('tellus');?>">Tell Us Your Story</a></li>
        </ul>
      </div>
      <p class="text-center">© 2015, HMGPS Pvt. Ltd. All Rights Reserved.</p>
    </footer>
    <!-- Footer area Start--> 
    
  </div>
</div>
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/js/function.js"></script>
<script>
var uri = '<?php echo $this->uri->segment(1); ?>';
$(window).load(function() {
    
    if(uri == 'search') {
        if(splitStr == '' && trackedStr == '') {
       // setTimeout(function(){
            myclick(sel_group_id,1); 
        //    },1000);
        
        $('.tuser'+pro_id).trigger("click");
        breadcrumb(pro_id,breadtimelimit);
        setTimeout(function(){ closeinfowindow(); },3000);
       }
  }
});
</script>
<script src="<?php echo site_url();?>/assets/js/jquery-ui.js"></script>
</body>
</html>