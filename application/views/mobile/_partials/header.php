<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport"  content="initial-scale=1.0, user-scalable=no" />
      <meta charset="utf-8">
      <meta name="ad.size" content="width=160,height=600" />
      <meta property="og:url" content="<?php echo $shareurl; ?>"/>
      <meta property="og:title" content="HMGPS"/>
      <meta property="og:site_name" content="HMGPS" />
      <meta property="og:description" content="View my location @ <?php echo $shareurl; ?>"/>
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>HMGPS</title>
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/social-buttons-share.css" />
      <script src="<?php echo site_url();?>assets/js/lib/modernizr-2.8.3.js"></script>

      <!-- Bootstrap -->
      <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" />
      <link href="<?php echo site_url();?>assets/css/custom.css" rel="stylesheet" />
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

      <script src="<?php echo site_url(); ?>assets/js/lib/jquery.min.js?v1.12.0"></script>
      <script src="<?php echo site_url();?>/assets/js/jquery-ui.js"></script>
      <script src="<?php echo base_url();?>assets/js/social-buttons-share.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClXC4q5fI1C8arpQ22WVOwvtsfh9a-eLw"></script>
      <script src="<?php echo base_url();?>assets/js/infobox.js"></script> 
  
      <!--GLOBAL VARS-->
      <script>
        var site_url = '<?php echo site_url();?>';
        var base_url = '<?php echo base_url();?>';
        var user_info = <?php echo json_encode($user_info);?>;
        var controller = '<?php echo $this->router->fetch_class(); ?>';

        var map_data, locations = [], myCurrentPos = undefined;

        //Get Lat nad Lang values
        if (navigator.geolocation) {

            var options = {
                            enableHighAccuracy: true,
                           timeout:            30000,  // milliseconds (30 seconds)
                           maximumAge:         600000 // milliseconds (10 minutes)
               };

            navigator.geolocation.getCurrentPosition(function(position) {

               myCurrentPos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
               };

               $("#latlang").val(myCurrentPos.lat + ":" + myCurrentPos.lng);

                console.log('My Position', myCurrentPos);
            }, 
            function(err) {
               if(err.code == 1) 
                {
                   $("#latlang").val('INVALID');
                }
                else if( err.code == 2) 
                {
                   $("#latlang").val('INVALID');
                }
            }, 
            options);            

        }

        if( controller == 'search' )
        {
            map_data = <?php echo isset($map_data)?$map_data:"{locations:[]}";?>;
            locations = map_data.locations;
        }

      </script>

    
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
   </head>

   <body>