  

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/js/function.js"></script>
<script src="<?php echo site_url(); ?>assets/js/custom.js?v0.1.1"></script>

<script>
var uri = '<?php echo $this->uri->segment(1); ?>';
$(window).load(function() {
    
    if(uri == 'search') {
                                                 
                       
        if(splitStr == '' && trackedStr == '') {
       // setTimeout(function(){
            
            myclick(sel_group_id,1); 
            $('.tuser'+pro_id).trigger("click");
            
        //    },1000);
        
        setTimeout(function(){ closeinfowindow(); },3000);
        
        }
    }
});
</script>
<!--
<script src="<?php echo site_url();?>/assets/js/jquery-ui.js"></script>
-->
</body>
</html>