<!-- Scroll Div // -->

</div>
<!-- slide Navigation End -->
<div class="clearfix"></div>

<footer>
  <div class="container">
    <p class="text-center">&copy; <?php echo date("Y"); ?>, HMGPS Pvt. Ltd. All Rights Reserved.</p>
  </div>
</footer>

    

  
  <script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>
  <script src="<?php echo site_url(); ?>assets/js/lib/bootstrap.min.js?v3.3.7"></script>
  <script src="<?php echo site_url(); ?>assets/js/custom.js?v0.1.1"></script>
   <script src="<?php echo base_url();?>assets/js/function.js"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"
    async defer></script> -->


<script>
var uri = '<?php echo $this->uri->segment(1); ?>';
$(window).load(function() {
    
    if(uri == 'search') {
                                                 
                       
        if(splitStr == '' && trackedStr == '') {
       // setTimeout(function(){
            //alert(123);
            myclick(sel_group_id,1); 
            $('.tuser'+pro_id).trigger("click");
           
        //    },1000);
        
        setTimeout(function(){ closeinfowindow(); },3000);
        
        }
    }
});
</script>
<script src="<?php echo site_url();?>/assets/js/jquery-ui.js"></script>

</body>
</html>