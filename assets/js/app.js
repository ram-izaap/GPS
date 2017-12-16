
var mapManager = {};

(function ( module )
{
    var map;
    var infowindow;
    var markers = [];

    module.init = function ( callback )
    {
        console.log('KKKK', locations);
        var zoomlvl = 5;
        map = new google.maps.Map(document.getElementById('map'), {
              zoom: zoomlvl,
              center: {lat: parseFloat(locations[0].lat), lng: parseFloat(locations[0].lang)},
              gestureHandling: 'greedy',
              mapTypeControl: true,
              mapTypeControlOptions: {
                                       style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                                       mapTypeIds: [                                
                                         google.maps.MapTypeId.ROADMAP,
                                         google.maps.MapTypeId.SATELLITE,
                                         google.maps.MapTypeId.TERRAIN
                                       ],
                                       position: google.maps.ControlPosition.RIGHT_BOTTOM
                                     },
             streetViewControl: true,
             zoomControl: true,
             zoomControlOptions: {
                 position: google.maps.ControlPosition.RIGHT_BOTTOM,
             },
             streetViewControlOptions: {
               position: google.maps.ControlPosition.RIGHT_BOTTOM
             },
             rotateControlOptions:{
                                   position: google.maps.ControlPosition.RIGHT_BOTTOM
                                 },
             
             scaleControl:true,
             scrollwheel:false,
             panControl: true,
             panControlOptions: { position: google.maps.ControlPosition.RIGHT_BOTTOM
                                 },
            
             overviewMapControl:true,
             rotateControl:true,
            
             heading: 90,
             tilt: 45,
             fullscreenControl:true

            });


        infowindow = new google.maps.InfoWindow({
                 maxWidth: 300 
               });
      

        module.renderLocations();  
    };


    module.renderLocations = function()
    {
        for (var i = 0; i < locations.length; i++) 
        {
          
            if(locations[i].location_type != 'staticmap')
            {
                var iconcustom = {
                     url: 'http://911gps.me/assets/images/violet-icon.png', 
                     scaledSize: new google.maps.Size(80, 40)
                 };

                var labeltext = {text:locations[i].display_name,color:"white"};     
            }
            else
            {
             iconcustom = '';
             labeltext  = '';
            }

               

            var marker = new google.maps.Marker({
                 position: new google.maps.LatLng(parseFloat(locations[i].lat), parseFloat(locations[i].lang)),
                 map: map,
                 animation: google.maps.Animation.DROP,
                 label:labeltext,
                 title: locations[i].display_name+'\nUpdated : ',
                 optimized: false,
                 draggable:false,
                 icon:iconcustom,
                 zIndex:9999+i
                });

                google.maps.event.addDomListener(marker, 'click', (function(marker, i) {
                       return function() {
                         var cont = 'Hi sakhdjksah:: '+ locations[i].display_name;//contents[i][0].replace("<<lastseen>>",formattime(dat) );
                         infowindow.setContent(cont);
                         infowindow.open(map, marker);
                       }
                })(marker, i));

            markers.push(marker);
        }
    };

})( mapManager || ( mapManager = {} ) );



mapManager.init();


    




function showLoader(flag) {
    if (typeof flag == 'undefined') flag = true;

    if (flag) {
        $("#wait").css("display", "block");
    } else {
        $("#wait").css("display", "none");
    }

}

function updateVisibleStatus() {
    if (user_info.group_id == '') {
        alert("You should join map");
        return false;
    }

    var data = {
        user_id: user_info.user_id,
        channel_id: user_info.channel_id,
        visible: user_info.visible == '1' ? '0' : '1'
    };

    showLoader();

    $.post(site_url + 'home/updateVisible', data, function(response) {

        user_info.visible = data.visible;

        showLoader(false);

        $("#visiblestatus, #visiblestatus2").html(response.visible_html);

    }, "json");


}

function removeAllMaps() {
    var data = {
        user_id: user_info.user_id
    };

    showLoader();

    $.post(site_url + 'home/removeAllMaps', data, function(response) {

        showLoader(false);

        alert(response.msg);

        //TO do
        //location.href = site_url+'search/'+map_channel;

    }, "json");

}

function openModals(type) {
    if (type == undefined) type = 'display_name_update';

    $('#' + type).remove();
    $.post(site_url + 'home/getModalConent/' + type, {}, function(resp) {
        //insert into document
        $('body').append(resp.content);

        if (type == 'display_name_update') {

            //$('input[name="update_type"]').attr("checked", "checked");
            changeRadioStatus( 'input[name="update_type"][value="system"]' );
            $('input[name="system_value"]').val( 'Use System Generated('+ user_info.display_name +')' );
            $('input[name="custom_phonenumber"]').val(  user_info.phonenumber );

        }


        if (type == 'map_id_update') {
            $("#custom_map_id").val(user_info.channel_id);
        }

        //now open modal
        $('#' + type).modal();

    }, 'json');
}

function updateDisplayName() {
    

    var update_type = $('input[name="update_type"]:checked').val(),
        custom_display_name = $('input[name="custom_display_name"]').val(),
        custom_phonenumber = $('input[name="custom_phonenumber"]').val();

    custom_display_name = custom_display_name.trim();
    custom_phonenumber = custom_phonenumber.trim();

    if( update_type == 'custom' && custom_display_name == '' )
    {
      alert("Please Enter display name.");
      return false;
    }

    // if( $('input[name="custom_phone_update"]:checked').length 
    //     && $('input[name="custom_phone_update"]:checked').val() == 'custom_phone' 
    //     && custom_phonenumber == '' )
    // {
    //   alert("Please Enter phone number.");
    //   return false;
    // }

    if( update_type == 'system' ) custom_display_name = user_info.display_name;
    

    var data = {
      user_id: user_info.user_id,
      display_name: custom_display_name,
      update_type: update_type,
      phone_number: custom_phonenumber
    }

    showLoader();
    $.post(site_url + '/home/updateDisplayNameAndPhone', data, function(response) {
      showLoader( false );
      alert(response.msg);
      if (response.status == 'success') {
        location.reload();
      }
    }, 'json');

}


function updateMapID() {

    var mapID = $("#custom_map_id").val();
    mapID = mapID.trim();

    var validation_flag = true;

    if (!mapID.length) {
        alert('Please enter Map ID.');
        validation_flag = false;
    }

    if (!validation_flag) return true;

    var data = {
        user_id: user_info.user_id,
        channel_id: mapID
    };

    //console.log(data);

    $("#wait").css("display", "block");

    $.post(site_url + '/home/updateChannelID', data, function(response) {

        $("#wait").css("display", "none");

        alert(response.msg);

        if (response.status == 'success') {
            location.reload();
        }


    }, "json");
}


function changeRadioStatus(id) {
  $(id).prop("checked", true);
}