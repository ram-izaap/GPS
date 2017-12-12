var map_channel = $("#phone").val();
var joined_mapp = $("#joined_map").val();

function msover(x) {
    $(x).attr("title","Double Click On Map you can get address");
}


$(function() {

   $("[data-toggle='tooltip']").tooltip(); 

   $("[data-toggle='dropdown']").dropdown();

$("#guest_current").click(function(){
   $("#popover").popover('hide'); 
});

//delete the set zoomlevel cookie
$(".go-search").click(function(){
  document.cookie="myMapCookie=;expires=Mon, 01 Jan 2015 00:00:00 GMT";
});

$("#popover").click(function(){
   var data = {};
       var uid = $(this).attr("data-uid");
        $.post(site_url+'/home/user_manual_address/'+uid, data, function(response){ 
            var addr = '';
            if(response.status== 'success') {
                addr = response.address;
            }
            else
            {
                addr = response.msg;
            }
        	$("#popover-content").html();
            $(".popover-content #guest_address").html(addr);
        }, "json");  
});

$('#popover').popover({ 
    html : true,
    animation: false,
    container: "body",
    content: function() {
      return $("#popover-content").html();
    }
});

$("#phone").keyup(function() {
   $(".updatephone .edit-icon").css("display","none");
   $(".updatephone .update-link").css("display","inline-block");
});

$("#display_name").keyup(function() {
   $(".updatedisplayname .edit-icon").css("display","none");
   $(".updatedisplayname .update-link").css("display","inline-block");
});


if(service_resp['status']=='error')
    	$('#error_throw').modal('show');

 $("#create-map,#quickshareicon").click(function(){ $('#create_new_map').modal('show'); });   

  $(".down").hide();
   
    $("#headerup").click(function(){
      $("#header_toggle").toggle();
      $(this).find(".up, .down").toggle();
      if($('#header_toggle').css('display') == 'none'){
        $("footer").css({position:'fixed',bottom:0});
      }
      else
      {
        $("footer").css({position:'absolute'});
      }
    });
});


$("#socialquickshare").socialButtonsShare({
  socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
  url: site_url+'/search/'+map_channel,
  text: "\nMy Map ID is "+map_channel+"\n\n View my location @ "+site_url+"/search/"+map_channel+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
  sharelabel: false
});

$("#socialquickshare_mobile").socialButtonsShare({
  socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
  url: site_url+'/search/'+map_channel,
  text: "\nMy Map ID is "+map_channel+"\n\n View my location @ "+site_url+"/search/"+map_channel+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
  sharelabel: false
});

        
$("#searchmapshare").socialButtonsShare({
  socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
  url: site_url+'/search/'+serchval,
  text: "\nMy Map ID is "+serchval+"\n\n View my location @ "+site_url+"/search/"+serchval+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
  sharelabel: false
});



$("#searchmapshare_mobile").socialButtonsShare({
  socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
  url: site_url+'/search/'+joined_mapp,
  text: "\nMy Map ID is "+joined_mapp+"\n\n View my location @ "+site_url+"/search/"+joined_mapp+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
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

function popupclose()
{
    $("#popover").popover('hide'); 
}

function viewyouraddress()
{
    
   var address = $(".popover-content #guest_address").val();
        if(address == '') {
            alert("Manual Address shouldn't be empty!");
            return false;
       }
       address = address.replace(","," ");
       address = address.replace(" ","+");
       window.open("https://www.google.co.in/maps/place/"+address, '_blank');
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

function create_map(input_type,callback,elm,type,clsname){

  var loctype = $("input[name="+input_type+"]:checked").val();
  var time = (loctype==1)?0:300;
  GetAddressByLocation(elm);
  setTimeout(function(){ callback(type,clsname); },time);
  
}

function guest_registration(type,clsname)
{
	var latlon = $("#latlang").val();

      	var p=0;
        var errors='';        
        var addtype = 'current';

        
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

              //latlon = $('#map_pos').val();
              latlon = $('.popover-content #guest_address').val();
              
              addtype   = 'manual';          
              
            }
            else
            {

               errors += "Please eneter the address \n"; p=1;
            }  
        }

      	if(p==1 && type!='auto'){          
          alert(errors);
          return false;
        }
      		
        ajax_loader(1);

        var data = {latlon:latlon,phone:$('#phone').val(),display:$('#display_name').val(),type:addtype};

        $.post(site_url+'/home/guest_registration', data, function(response){

          ajax_loader(0);
        	if(response.status=='success'){

        		$('#guest_registration').modal('hide');
                
                 $("."+clsname+" .edit-icon").css("display","table-cell");
                 $("."+clsname+" .update-link").css("display","none");
   
            if(response.join_key!='')
              location.href=site_url+'/search/'+response.join_key;
          }
          else
          {
              alert(response.msg);
          }  
        	
        }, "json"); 
}


function user_update(type,clsname)
{
  //alert("xdfgdgds");
  var latlon = $("#latlang").val();

        var p=0;
        var errors = '';
        var addtype   = '';
        
        addtype = 'current';

        
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

              //latlon = $('#map_pos').val();
              
              latlon = $('.popover-content #guest_address').val();
              
              addtype   = 'manual';          
              
            }
            else
            {

               errors += "Please enter valid address \n"; p=1;
            }  
        }

        if(p==1){          
          alert(errors);
          return false;
        }
          
        ajax_loader(1);

        var data = {latlon:latlon,phone:$('#phone').val(),display:$('#display_name').val(),group_id:$('#group_id').val(),prev_channel:$('#prev_channel').val(),type:addtype};

        $.post(site_url+'/home/user_update', data, function(response){

          ajax_loader(0);

          alert(response.msg);
          
           $("."+clsname+" .edit-icon").css("display","table-cell");
           $("."+clsname+" .update-link").css("display","none");
           $(".popover").hide();      
         // if(response.status=='success' && response.join_key!='')
          //    location.href=site_url+'/search/'+response.join_key;

          
        }, "json");
     
}

function invisible_participant(user_id,grp_id)
{
    
    var data = {user_id:user_id,group_id:grp_id};
    
    $.post(site_url+'/home/invisible_participant', data, function(response){
          alert(response.msg);
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

function copyToClipboard(element,altdiv) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(site_url+"/search/"+$(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
  var url = site_url+"/search/"+$(element).val();
  $(altdiv).css("display","block").html("Copied "+url+" to clipboard");
  $(altdiv).fadeOut(4000);

}

function checkUserId()
{
    var user_id = user_info.user_id;
    
    if(user_id == ''){
        alert('You are not registered yet!');
        return false;
    }
}
