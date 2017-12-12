<div onclick="return invisible_participant('<?php echo $user_info['user_id'];?>','<?php echo $current_joined_group_id;?>','<?php echo (isset($visible)&&(!empty($visible))&&($visible == 1))?0:1;?>')">
<span id="invisible_icon"  <?php if($visible == 0){ ?> style="display: block;" <?php }else{ ?> style="display: none;" <?php } ?>>
Not Visible
   <i class="glyphicon"  aria-hidden="true" >
        <img src="<?php echo base_url();?>/assets/images/Invisable Icon Red.png" class="" alt="" height="25" />
  </i>
 <small>
  Click to Unhide
</small>
</span>
<span id="visible_icon" <?php if($visible == 1){ ?> style="display: block;" <?php } else{ ?> style="display: none;" <?php } ?>>
Visible
   <i class="glyphicon" ><img src="<?php echo base_url();?>/assets/images/eye-Green Squished.png" height="" class="" alt="" /></i>
  <small>
  Click to Hide
  </small>
</span>
</div>