var map_channel = '';
var joined_mapp = '';

function msover(x) {
    $(x).attr("title","Double Click On Map you can get address");
}

function render_social_share()
{
  var shr_ct = $("#shr_mp_content").html();   
  
  $("#sharemap_mobile").html(shr_ct);
  
  $("#sharemap_mobile").socialButtonsShare({
      socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
      url: site_url+'/search/'+joined_mapp,
      type: $("#joined_map").val(),
      text: "\nMy Map ID is "+joined_mapp+"\n\n View my location @ "+site_url+"/search/"+joined_mapp+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
      sharelabel: false
    });
}

function share_map(chid)
{
    //joined_mapp = chid;
    $("#joined_map").val(chid);
    $("#phone").val(chid);
   joined_mapp =  $("#joined_map").val();
   map_channel =  $("#phone").val();
   
  render_social_share();     
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


if(service_resp['status']=='error'){	
      var request_type = service_resp['request_type'];
      //alert(request_type);
      if(request_type == 'password_protect') {
            if(confirm(service_resp['message'])) {
                $(".pass-search").css("display","block");
                $("#pprotect").addClass("pass-activate");
                $("#pprotect").removeClass("pass-deactivate");
                $("#pwd").attr("placeholder","");
                $("#pwd").focus();
               // $(".subm").hide();
             //   $(".pass_protect").removeAttr('style');
                $("#pwd").val();
                
                return false;
            }
            else
            {
                $(".subm").show();
              //  $(".pass_protect").hide();
                return true;
            }
       } 
       else if(request_type=='allow_deny')
       {
          if(confirm(service_resp['message'])) {
              $("#wait").css("display", "block");
                var joinkey = $("#search").val();
                var data = {joinkey:joinkey,user_id:user_info.user_id};
                    $.post(site_url+'/home/allow_deny_restriction', data, function(response){
                     $("#wait").css("display", "none");
                      if(response.status=='success'){
                        alert(response.msg);
                      }
                      else                
                      { 
                        alert(response.msg);
                      }
                    }, "json");
           } 
       } 
      else
      {
        $('#error_throw').modal('show');
      }
}


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
  type: $("#joined_map").val(),
  text: "\nMy Map ID is "+joined_mapp+"\n\n View my location @ "+site_url+"/search/"+joined_mapp+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.hmgps.me/apps",
  sharelabel: false
});

render_social_share();   



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

function displayup(id)
{
    $(id).attr("checked","checked");
}

function popupclose()
{
    $("#popover").popover('hide'); 
}

function trig_disp_popup(id)
{
  if( id == 'display_popup_map_id' )
  {
    $('#custom_map_id').val( user_info.channel_id );
  } 
  
  $("#"+id).trigger("click");
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
       //  alert(latlon);
      	var p=0;
        var errors='';        
        var addtype = 'current';

        
      	if($('#phone').val()==''){
      		errors += "Channel ID should be minimum 3 characters \n"; p=1;
      	}

        var loctype = $("input[name=guest_pos_type]:checked").val();

        if(loctype==1){
          if(latlon=='' || latlon==0 || latlon==1 || latlon==2){
            $("#popover").trigger('click');
            var add = $('.popover-content #guest_address').val();
            if(add!=''){

              //latlon = $('#map_pos').val();
              latlon = (latlon!='')?latitude+":"+longitude:$('.popover-content #guest_address').val();
              
              addtype= 'manual';          
              
            }
            else
            {

               errors += "Please enter the address \n"; p=1;
            } 
            //errors += "Sorry, browser does not support geolocation! \n"; p=1;
          } 
        }
        else
        {
            if($('.popover-content #guest_address').val()!=''){
              // alert(123);
              //latlon = $('#map_pos').val();
              latlon = (latlon!='')?latitude+":"+longitude:$('.popover-content #guest_address').val();
              
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
      		
        $("#wait").css("display", "block");
        
        addtype = (latitude!='')?'current':addtype;
        
        var data = {latlon:latitude+":"+longitude,phone:$('#phone').val(),display:$('#display_name').val(),type:addtype};

        $.post(site_url+'/home/guest_registration', data, function(response){

          $("#wait").css("display", "none");
        	if(response.status=='success'){

        		$('#guest_registration').modal('hide');
                
                 $("."+clsname+" .edit-icon").css("display","table-cell");
                 $("."+clsname+" .update-link").css("display","none");
   
            if(response.user_info)
            {
              user_info = response.user_info;
            }

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
            $("#popover").trigger('click');
            var add = $('.popover-content #guest_address').val();
            if(add!=''){

              //latlon = $('#map_pos').val();
              latlon = $('.popover-content #guest_address').val();
              
              addtype   = 'manual';          
              
            }
            else
            {

               errors += "Please eneter the address \n"; p=1;
            } 
            //errors += "Sorry, browser does not support geolocation! \n"; p=1;
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
          
        $("#wait").css("display", "block");
        
        addtype = (latitude!='')?'current':addtype;

        var data = {latlon:latitude+":"+longitude,phone:$('#phone').val(),display:$('#display_name').val(),group_id:$('#group_id').val(),prev_channel:$('#prev_channel').val(),type:addtype};

        $.post(site_url+'/home/user_update', data, function(response){

          $("#wait").css("display", "none");

          alert(response.msg);
          
           $("."+clsname+" .edit-icon").css("display","table-cell");
           $("."+clsname+" .update-link").css("display","none");
           $(".popover").hide();      
         // if(response.status=='success' && response.join_key!='')
          //    location.href=site_url+'/search/'+response.join_key;

          
        }, "json");
     
}

function update_disp_name()
{
     var dispname = $("#custom_display_name").val();
     var phonenum = $("#custom_phonenumber").val();
     
     //if(($("#system_disp_update").prop("checked")==true) && ($("#system_phonenumber").prop("checked")==true)){
//        alert("You should enter Display Name or Phone Number");
//        return false;
//     }
//     
//     if(($("#system_disp_update").prop("checked")==true) && ($("#system_phonenumber").prop("checked")==false) && ($("#custom_phone_update").prop("checked")==false)){
//        alert("You should enter Display Name or Phone Number");
//        return false;
//     }
//     
     if(($("#system_disp_update").prop("checked")==false) && ($("#custom_disp_update").prop("checked") == false)){
        alert("Please Select any one of the option in display name section");
        return false;
     }
     
     
     if(dispname == '' && ($("#system_disp_update").prop("checked")==false)){
        alert("Please Enter display name");
        return false;
     }
   
     if($("#custom_disp_update").prop("checked") == true){
        dispname = dispname;
        var updated_type = 'custom';
     } 
     else
     {
        dispname = user_info.channel_id;
        var updated_type = 'system';
     }  
     
     if($("#custom_phone_update"). prop("checked") == true){
        phonenum = phonenum;
        var updated_phonenumber = 'custom';
     }
     else
     {
        phonenum = user_info.phonenumber;
        var updated_phonenumber = 'system';
     }
      //alert(dispname);
      
     $("#wait").css("display", "block");

        var data = {phone:phonenum,display:dispname,user_id:user_info.user_id,updated_type:updated_type,updated_phonenumber:updated_phonenumber};

        $.post(site_url+'/home/user_update_displayname', data, function(response){

          $("#wait").css("display", "none");

          alert(response.msg);
          
          location.reload();
          
        }, "json");   
}

function update_map_id()
{
     var mapID = $("#custom_map_id").val();
         mapID = mapID.trim(); 

     var validation_flag = true;

     if( !mapID.length ) 
     {
        alert('Please enter Map ID.');
        validation_flag = false;
     }

     if( !validation_flag ) return true;

     var data = {
        user_id: user_info.user_id,
        channel_id: mapID
     };

     //console.log(data);

     $("#wait").css("display", "block");

    $.post(site_url+'/user/updateChannelID', data, function(response){

      $("#wait").css("display", "none");

      alert(response.msg);
      
      if( response.status == 'success')
      {
        location.reload();
      }
      
      
    }, "json"); 
}


function invisible_participant(user_id,grp_id,visible)
{
   $("#wait").css("display", "block");
    if(grp_id == ''){
        alert("You should join map");
        $("#wait").css("display","none");
        return false;
    }
    var data = {user_id:user_id,group_id:grp_id,visible:visible};
    $.post(site_url+'/home/invisible_participant', data, function(response){
    $("#wait").css("display", "none");
          $("#visiblestatus").html(response.msg);
          if($("#visiblestatus2"))
             $("#visiblestatus2").html(response.msg);
            
    }, "json");
}

function remove_user_from_all_groups()
{
      var data = {user_id:user_info.user_id};
      $("#wait").css("display", "block");
     $.post(site_url+'/home/remove_user_from_all_groups', data, function(response){
        $("#wait").css("display", "none");
          alert(response.msg);
          location.href = site_url+'search/'+map_channel;
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

        $("#wait").css("display", "block");

        var data = {latlon:latlon,channel:$('#channel').val(),display:$('#display').val(),password:$('#map_pwd').val(),type:type,loc_type:location_type};

        $.post(site_url+'/home/create_group', data, function(response){

          $("#wait").css("display", "none");

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
        location.reload();
        return false;
    }
    return true;
}


function copyToClipboard_popup() {
var element = "#lan_log";
var altdiv = "#alert_popup";

  var $temp =$("<input>");

  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
  //var url = site_url+"/search/"+$(element).val();
  var text = $(element).val();
  $(altdiv).css("display","block").html("Copied "+text+" to clipboard");
  $(altdiv).fadeOut(4000);

}

function allowDrop(ev) {
    ev.preventDefault();
}


function drop(ev) {
    
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(data));
}