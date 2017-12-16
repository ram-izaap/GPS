<div class="modal fade" id="display_name_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
         
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="displayName">
              
              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dname">
                          <input type="radio" class="itxt" name="update_type"  value="custom" />
                        </label>
                      </span>
                    <input type="text" class="form-control" name="custom_display_name" aria-describedby="basic-addon1" onkeypress="return changeRadioStatus('input[name=\'update_type\'][value=\'custom\']');"  placeholder="Custom Display Name" value="" />
                  </div>
              </div>

              <div class="panel-gutter">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <label for="Dname1">
                          <input type="radio" class="itxt" name="update_type" value="system" />
                        </label>
                      </span>
                    <input type="text" class="form-control" name="system_value" disabled="disabled" aria-describedby="basic-addon2">
                  </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="PhoneNumber">

              <div class="panel-gutter">
                  <input type="text" class="form-control" placeholder="Custom Phone Number" aria-describedby="basic-addon1" name="custom_phonenumber" onkeypress="return changeRadioStatus('input[name=\'custom_phone_update\'][value=\'custom_phone\']');"  /> 
              </div>

            </div>
          </div>
        </div>
        <!--  -->
        </div>
        <div class="modal-footer">  
         <button type="button" class="btn btn-default btn-green"  onclick="updateDisplayName();" > Accept </button>
          <button type="button" class="btn btn-default btn-gray" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>