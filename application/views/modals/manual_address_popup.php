<div class="modal fade" id="manual_address_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
    
      <!-- Modal content-->
      
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center"><b>Manual Address</b></h4>
          </div>
        <div class="modal-body">
         <!--  -->
        <div class="tab-wrapper">

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="manualAddress">              
              <div class="panel-gutter">
                <div class="">
                  <input type="text" class="form-control" name="manual_address" aria-describedby="basic-addon1" id="manual_address" placeholder="Enter Address" value="" />
                </div>
              </div>              
            </div>            
          </div>
        </div>
        <!--  -->
        </div>
        <div class="modal-footer">  
         <button type="button" name="add_manual_address" class="btn btn-default btn-green" id="add_manual_address" onclick="getLatLong();" > Submit </button>
          <button type="button" class="btn btn-default btn-gray" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>