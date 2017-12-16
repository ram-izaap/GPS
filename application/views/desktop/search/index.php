

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
   </div>
</div>



<script src="<?php echo base_url();?>assets/js/geolocationmarker-compiled.js"></script>
<script src="<?php echo base_url();?>assets/js/show_position.js"></script>    


<script>

  var locations = <?php echo $locations;?>;
  
</script>
