
<div onclick="return updateVisibleStatus()">
   
	<?php if( $visible == 0 ): ?>

		<span id="invisible_icon">
			Not Visible
			<i class="glyphicon"  aria-hidden="true" >
   				<img src="<?php echo base_url();?>/assets/images/Invisable Icon Red.png" class="" alt="" height="25" />
   			</i>
   			<small>
	   			Click to Unhide
	   		</small>
		</span>
		

	<?php else: ?>
	
		<span id="visible_icon">
			Visible
			<i class="glyphicon" >
   				<img src="<?php echo base_url();?>/assets/images/eye-Green Squished.png" height="" class="" alt="" />
   			</i>
			<small>
   				Click to Hide
   			</small>
		</span>

	<?php endif; ?>

</div>

