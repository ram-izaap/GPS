var map_channel = $("#phone").val();
$(function() {

   $("[data-toggle='tooltip']").tooltip(); 

   $("[data-toggle='dropdown']").dropdown();
 
  if(typeof user_info === "object" && user_info['user_id']==''){
      setTimeout(function(){ guest_registration('auto'); },500);
  }

$('#popover').popover({ 
    html : true,
    animation: false,
    container: "body",
    content: function() {
      return $("#popover-content").html();
    }
});

if(service_resp['status']=='error')
    	$('#error_throw').modal('show');

 $("#create-map,#quickshareicon").click(function(){ $('#create_new_map').modal('show'); });   

});


$("#socialquickshare").socialButtonsShare({
  socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
  url: site_url+'/search/'+map_channel,
  text: "\nMy Map ID is "+map_channel+"\n\n View my location @ "+site_url+"/search/"+map_channel+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
  sharelabel: false
});

        

function ajax_loader(type)
{
  if(type==1){
    $(".overlaybg").css("display", "block");
    $(".loader").css("display", "block");
  }else{
    $(".overlaybg").css("display", "none");
    $(".loader").css("display", "none");
  }

}

function editName(elm) {
    document.getElementById(elm).readOnly=false;
    $('#'+elm).focus();
}

function locationtype(type,address)
{
    if(type=='2'){
      $("#"+address).css("display", "block");
    }else{
      $("#"+address).css("display", "none");
    }  

}

function create_map(input_type,callback,elm,type){

  var loctype = $("input[name="+input_type+"]:checked").val();
  var time = (loctype==1)?0:300;

  GetAddressByLocation(elm);

  setTimeout(function(){ callback(type); },time);
  

}

function guest_registration(type)
{
	//alert("xdfgdgds");
	var latlon = $("#latlang").val();

      	var p=0;
        var errors='';

        
      	if($('#phone').val()=='' || $('#phone').val().length<=3){

      		errors += "Channel ID should be minimum 3 characters \n"; p=1;
      	}

        var loctype = $("input[name=guest_pos_type]:checked").val();

        if(loctype==1){

          if(latlon=='' || latlon==0 || latlon==1 || latlon==2){
             
            errors += "Sorry, browser does not support geolocation! \n"; p=1;

            if(type=='auto')
                return false;
          } 

        }
        else
        {
            if($('.popover-content #guest_address').val()!=''){

              latlon = $('#map_pos').val();

              if(latlon==1 || latlon==''){
                errors += "Sorry, Incorrect manual address! \n"; p=1;
              }             
              
            }
            else
            {

               errors += "Please eneter valid address \n"; p=1;
            }  
        }

      	if(p==1){          
          alert(errors);
          return false;
        }
      		
        ajax_loader(1);

        var data = {latlon:latlon,phone:$('#phone').val(),display:$('#display_name').val()};

        $.post(site_url+'/home/guest_registration', data, function(response){

          ajax_loader(0);
        	if(response.status=='success'){

        		$('#guest_registration').modal('hide');

            if(response.join_key!='')
              location.href=site_url+'/search/'+response.join_key;
          }
          else
          {
              alert(response.msg);
          }  
        	
        }, "json");
      
      
}

function user_update(type)
{
  //alert("xdfgdgds");
  var latlon = $("#latlang").val();

        var p=0;
        var errors='';

        
        if($('#phone').val()=='' || $('#phone').val().length<=3){

          errors += "Channel ID should be minimum 3 characters \n"; p=1;
        }

        var loctype = $("input[name=guest_pos_type]:checked").val();

        if(loctype==1){

          if(latlon=='' || latlon==0 || latlon==1 || latlon==2){
             
            errors += "Sorry, browser does not support geolocation! \n"; p=1;
          } 

        }
        else
        {
            if($('.popover-content #guest_address').val()!=''){

              latlon = $('#map_pos').val();

              if(latlon==1 || latlon==''){
                errors += "Sorry, Incorrect manual address! \n"; p=1;
              }             
              
            }
            else
            {

               errors += "Please eneter valid address \n"; p=1;
            }  
        }

        if(p==1){          
          alert(errors);
          return false;
        }
          
        ajax_loader(1);

        var data = {latlon:latlon,phone:$('#phone').val(),display:$('#display_name').val(),group_id:$('#group_id').val(),prev_channel:$('#prev_channel').val()};

        $.post(site_url+'/home/user_update', data, function(response){

          ajax_loader(0);

          alert(response.msg);
          
          if(response.status=='success' && response.join_key!='')
              location.href=site_url+'/search/'+response.join_key;

          
        }, "json");
      
      
}


function create_quickshare_map()
{
  //alert("xdfgdgds");
  var latlon = $("#latlang").val();
  var type='private';
  var location_type;

        var p=0;
        if($('#display').val()=='' || $('#display').val().length<5){

          $("#display_error").css("display", "block"); p=1;
        } 


        if($('#channel').val()=='' || $('#channel').val().length<=2){

          $("#channel_error").css("display", "block"); p=1;    

        } 

        var loctype = $("input[name=location_type]:checked").val();

        if(loctype==1){

          location_type='mobile';

          if(latlon=='' || latlon==0 || latlon==1 || latlon==2){
             p=1;
             alert("Sorry, browser does not support geolocation!");
          } 

        }
        else
        {
          location_type='static';
            if($('#address').val()!=''){

              latlon = $('#map_pos').val();

              if(latlon==1 || latlon==''){
                p=1;alert("Sorry, browser does not support geolocation!");
              }             
              
            }
            else
            {
               $("#address_error").css("display", "block"); p=1;
            }  
        }

        if(p==1)
          return false;

        ajax_loader(1);

        var data = {latlon:latlon,channel:$('#channel').val(),display:$('#display').val(),password:$('#map_pwd').val(),type:type,loc_type:location_type};

        $.post(site_url+'/home/create_group', data, function(response){

          ajax_loader(0);

          if(response.status=='success'){

            $('#create_new_map').modal('hide');

            location.href=site_url+'/search/'+response.join_key;
          }
          else                
          { 
           alert(response.msg);
          }
          
        }, "json");
}

function GetAddressByLocation(elm) {
    var address = $(elm).val();

    if(address!=''){
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ 'address': address }, function (results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
              var latitude = results[0].geometry.location.lat();
              var longitude = results[0].geometry.location.lng();
              var latlong=latitude+':'+longitude;
              $('#map_pos').val(latlong);
          } else {
              $('#map_pos').val(1);
          }
      });
    }  
}

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(site_url+"/search/"+$(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
}
