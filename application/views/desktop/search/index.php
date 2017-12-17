

<style>
   .martop{ margin-top:10px !important;}
   .gm-style-iw + div {left: 10px;}
   /*.gm-style-iw + div { height:30px !important; width:30px !important; border:1px solid red;}
   .gm-style-iw + div:before { content:"x"; font-weight:bold; font-size:18px; padding:0 10px }*/
   .gm-style-iw + div, 
   .gm-style-iw + div img { display: none}
   [data-role="close"] {
   background: red none repeat scroll 0 0;
   border: 1px solid red;
   border-radius: 50%;
   color: #fff;
   cursor: pointer;
   font-size: 14px;
   font-weight: bold;
   height: 20px;
   left: 0;
   line-height: 10px;
   padding: 5px;
   position: absolute;
   text-align: center;
   top: 0;
   width: 20px;
   }
</style>
<!-- /.modal -->
<style type="text/css">
   .google-add.right-add02 {
   margin: auto;
   text-align: center;
   }
   /**
   * div#map {
   *     height: 83vh!Important;
   * }
   */
</style>

<div class="container-fluid search">
   <!-- Content area Start-->
   <div class="row participant">
      <div id="latlang" style=""></div>
      <div id="map" style="width:100%;"></div>
      <div class="btn-group btn-participant" ></div>   

      <div class="map-layer">
        <div class="conta-iner clearfix">
          
          <!--NIGHT MODE -->  
          <input type="radio" name="mode_type" id="deafult_mode" value="deafult" class="selector-control"  />
          <label for="deafult_mode" style="color: #fff;">Default</label>

          <input type="radio" name="mode_type" id="night_mode" value="night" class="selector-control"  />
          <label for="night_mode" style="color: #fff;">Night</label>

          <div class="drop-wrap pull-right btn-participant timer-btn">
            <div class="dropup">
              
              <button class="btn btn-default dropdown-toggle parti" type="button" id="dropdownMenu2" data-toggle="dropdown" >
                Participants <b>(<span id="participants-count">0</span>)</b>
                <span class="caret"></span>
              </button>


              <div class="dropdown-menu" aria-labelledby="dropdownMenu2" id="participants-list">
                 <a class="close_pop" onclick="add_toggle();">CLOSE</a>
                 <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#show-1">
                       <i class="fa fa-users" aria-hidden="true"></i> Visible
                       </a>
                    </li>
                    <li>
                       <a data-toggle="tab" href="#show-2"><i class="fa fa-users" aria-hidden="true"></i> Invisible </a>
                    </li>
                    <li><a data-toggle="tab" href="#show-3">
                       <i class="fa fa-users" aria-hidden="true"></i> Clues
                       </a>
                    </li>
                 </ul>
                 <div class="tab-content">
                    <div id="show-1" class="tab-pane fade in active ">
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

          <div class=" pull-right ">
             <span class="btn btn-default">
             <span id="re-load"><time class="timer">00:00</time></span>
             <i class="fa fa-repeat refresh-clk" aria-hidden="true"></i>
             </span>
          </div>


        </div>
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


<script src="<?php echo base_url();?>assets/js/geolocationmarker-compiled.js"></script>
<script src="<?php echo base_url();?>assets/js/show_position.js"></script>    


<script>

  var map_data = <?php echo $map_data;?>;
  var locations = map_data.locations;
  
</script>
