<div class="modal fade" id="map_id_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
         <button type="button" name="update_dis_name" class="btn btn-default btn-green" id="update_dis_name" onclick="updateMapID();" > Accept </button>
          <button type="button" class="btn btn-default btn-gray" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>