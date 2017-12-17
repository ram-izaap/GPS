
var Element = (function ( $ )
{
    function Element ( selector, data )
    {
        this.selector = selector || '<div><div></div></div>';

        this.template = $( selector );

        // this.element = $( selector ).clone();
        this.element = $( this.template.html() );

        this.element.data = data || {};

        this.element.removeAttr('id');
    }

    Element.prototype.render = function () { return this.element; };

    return Element;

})( jQuery );




var mapManager = {};
var map;
(function ( module )
{
    //var map;
    var infowindow;
    var visibles_markers = [];
    var clues_markers = [];
    var styles;

    var visibles = [];
    var invisibles = [];
    var clues = [];
    var participants = [];

    var myCurrentPos = undefined;

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
      

        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(function(position) {

                myCurrentPos = {
                      lat: position.coords.latitude,
                      lng: position.coords.longitude
                    };

                console.log('My Position', myCurrentPos);
            });

        }

        module.addYourLocationButton(map);

        module.prepareData();

        module.render( visibles, 'visibles');
        module.render( invisibles, 'invisibles');
        module.render( clues, 'clues');  
    };


    module.prepareData = function( )
    {
        for (var i = 0; i < locations.length; i++)
        {
            var location = locations[i];


            var mapType = location.location_type,
                visible = location.visible,
                addToParticipants = false;


            var template = new Element('#participant-template'),
                $element = template.element;

            $element.find('.myposition').on('click', function(){
                module.locatePosition( i );
            });

            $element.find('.statuspop').on('click', function(){
                module.locateAndOpenInfo( i );
            });

            if( mapType == 'dynamic' )
            {
                if( visible == '1' )
                {
                    $element.find('.display_name').html(location.display_name);
                    $element.find('.channel_id').html(location.channel_id);

                    visibles.push( location );

                    //Add to partcipants list
                    addToParticipants = true;
                    
                }
                else if( visible == '0' )
                {
                    $element.find('.display_name').html(location.display_name);
                    $element.find('.channel_id').html(location.channel_id);

                    invisibles.push( location );
                }
            } 
            else if( mapType == 'staticmap' && visible == '' )
            {
                $element.find('.display_name').html(location.display_name);
                $element.find('.channel_id').html(location.channel_id);

                clues.push( location );

                //Add to partcipants list
                addToParticipants = true;
            }

            //
            if( addToParticipants )
            {
                //participants.push( location );
            }            
        }
        
        console.log(visibles);
        console.log(clues);
        console.log(invisibles);
    };

    module.render = function( data, type )
    {
        for (var i = 0; i < data.length; i++) 
        {
            if( type == 'visibles' || type == 'clues' )
            {
                module.renderMarkers( data[i], i, type );
            }

            //Add header
            if( i == 0 )
            {
                var $header = $('<li class="map-list-label">' +
                                '<span class="name">Participant</span>' + 
                                '<span>Find</span>' + 
                                '<span>Status</span></li>');

                if( type == 'visibles' ) $('#tab1').append($header);
                if( type == 'invisibles' )
                {
                    $header.find('span:nth-child(2)').remove();
                    $('#tab2').append($header);
                }
                if( type == 'clues' ) $('#tab3').append($header);
            }


            var template = new Element('#participant-template'),
                $element = template.element;

            $element.find('.display_name').html( data[i].display_name );
            $element.find('.channel_id').html( data[i].channel_id );
            $element.attr('data-type', type);
            $element.attr('data-index', i);

            $element.find('.myposition').on('click', function(){
                module.locatePosition( $element.attr('data-index'), $element.attr('data-type') );
            });

            $element.find('.statuspop').on('click', function(){
                module.locateAndOpenInfo( $element.attr('data-index'), $element.attr('data-type') );
            });

            if( type == 'visibles' )
            {
                $("#tab1").append($element);
            }
            else if( type == 'invisibles' )
            {
                $element.find('.myposition, .statuspop').remove();
                $element.find('.p-find-iocn').html('<span class="invisible_icon">&nbsp;</span>');
                
                $("#tab2").append($element);
            }
            else if( type == 'clues' )
            {
                $("#tab3").append($element);
            }

        }

        $('#participants-count').html(visibles.length);
    };

    
    module.renderMarkers = function( location, index, type )
    {
        if(location.location_type != 'staticmap')
        {
            var iconcustom = {
                 url: 'http://911gps.me/assets/images/violet-icon.png', 
                 scaledSize: new google.maps.Size(80, 40)
             };

            var labeltext = {text:location.display_name,color:"white"};     
        }
        else
        {
         iconcustom = '';
         labeltext  = '';
        }

           

        var marker = new google.maps.Marker({
             position: new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lang)),
             map: map,
             animation: google.maps.Animation.DROP,
             label:labeltext,
             title: location.display_name+'\nUpdated : ',
             optimized: false,
             draggable:false,
             icon:iconcustom,
             zIndex:9999+index
            });

        google.maps.event.addDomListener(marker, 'click', (function(marker, i) {
               return function() {
                 var cont = 'Hi sakhdjksah:: '+ location.display_name;//contents[i][0].replace("<<lastseen>>",formattime(dat) );
                 infowindow.setContent(cont);
                 infowindow.open(map, marker);
               }
        })(marker, index, type));

        if( type == 'visibles' ) visibles_markers.push( marker );
        if( type == 'clues' ) clues_markers.push( marker );        
        
    }

    module.locatePosition = function( index, type, callback )
    {
        console.log(type, index);
        //console.log(visibles_markers);

        if( type == 'visibles' && typeof visibles_markers[index] == 'undefined' ) return;

        if( type == 'clues' && typeof clues_markers[index] == 'undefined' ) return;

        var current_marker = ( type == 'visibles' ) ? visibles_markers[index]: clues_markers[index];

        console.log(current_marker.position);

        var bounds = new google.maps.LatLngBounds();        
            bounds.extend( current_marker.position );
        
        map.fitBounds(bounds);
        map.setZoom(16);
        map.setCenter( current_marker.getPosition() );

        if( typeof callback == 'function' ) callback( current_marker );
    };

    module.locateAndOpenInfo = function( index, type )
    {
        module.locatePosition( index, type, function( current_marker ){
            google.maps.event.trigger(current_marker, "click");
        });
    };


    module.changeMode = function( type )
    {
        var options = {
            styles: module.getModeOptions( type )
        };

        map.setOptions( options );

        console.log(options);
    };

    module.getModeOptions = function( type )
    {
        if( typeof type == 'undefined' ) type = 'default';

        styles = {
            default: null,
            night: [
                        {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                        {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                        {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                        {
                        featureType: 'administrative.locality',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#d59563'}]
                        },
                        {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#d59563'}]
                        },
                        {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{color: '#263c3f'}]
                        },
                        {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#6b9a76'}]
                        },
                        {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{color: '#38414e'}]
                        },
                        {
                        featureType: 'road',
                        elementType: 'geometry.stroke',
                        stylers: [{color: '#212a37'}]
                        },
                        {
                        featureType: 'road',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#9ca5b3'}]
                        },
                        {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{color: '#746855'}]
                        },
                        {
                        featureType: 'road.highway',
                        elementType: 'geometry.stroke',
                        stylers: [{color: '#1f2835'}]
                        },
                        {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#f3d19c'}]
                        },
                        {
                        featureType: 'transit',
                        elementType: 'geometry',
                        stylers: [{color: '#2f3948'}]
                        },
                        {
                        featureType: 'transit.station',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#d59563'}]
                        },
                        {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{color: '#17263c'}]
                        },
                        {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{color: '#515c6d'}]
                        },
                        {
                        featureType: 'water',
                        elementType: 'labels.text.stroke',
                        stylers: [{color: '#17263c'}]
                        }
                    ]
            };

        console.log(styles[type]);

        return styles[type];

    };

    module.addYourLocationButton = function (map, marker) 
    {
        var controlDiv = document.createElement('div');

        var firstChild = document.createElement('button');
        firstChild.style.backgroundColor = '#fff';
        firstChild.style.border = 'none';
        firstChild.style.outline = 'none';
        firstChild.style.width = '28px';
        firstChild.style.height = '28px';
        firstChild.style.borderRadius = '2px';
        firstChild.style.boxShadow = '0 1px 4px rgba(0,0,0,0.3)';
        firstChild.style.cursor = 'pointer';
        firstChild.style.marginRight = '10px';
        firstChild.style.padding = '0';
        firstChild.title = 'Your Location';
        controlDiv.appendChild(firstChild);

        var secondChild = document.createElement('div');
        secondChild.style.margin = '5px';
        secondChild.style.width = '18px';
        secondChild.style.height = '18px';
        secondChild.style.backgroundImage = 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-2x.png)';
        secondChild.style.backgroundSize = '180px 18px';
        secondChild.style.backgroundPosition = '0 0';
        secondChild.style.backgroundRepeat = 'no-repeat';
        firstChild.appendChild(secondChild);

        google.maps.event.addListener(map, 'center_changed', function () {
            secondChild.style['background-position'] = '0 0';
        });

        firstChild.addEventListener('click', function () {
            var imgX = '0',
                animationInterval = setInterval(function () {
                    imgX = imgX === '-18' ? '0' : '-18';
                    secondChild.style['background-position'] = imgX+'px 0';
                }, 500);

            if( myCurrentPos )
            {
                var myMarker = new google.maps.Marker({
                  map: map,
                  position: myCurrentPos,
                  animation: google.maps.Animation.DROP
                });

                map.setCenter(myCurrentPos);
                map.setZoom(21);
                clearInterval(animationInterval);
                secondChild.style['background-position'] = '-144px 0';
            }
            else
            {
                clearInterval(animationInterval);
                secondChild.style['background-position'] = '0 0';
            }            

            
        });

        controlDiv.index = 1;
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);
    };
    

})( mapManager || ( mapManager = {} ) );


if( locations.length )  mapManager.init();


//Onload functions
$(document).ready(function(){

    $("input[name='mode_type']").off('click').on('click', function(){
        console.log(this.value);
        mapManager.changeMode( this.value );
    });

    $("#deafult_mode").prop('checked', true);

    $("[data-toggle='dropdown']").dropdown();

     $("#dropdownMenu2").mouseover(function(){
        $("#dropdownMenu2").removeAttr('data-toggle'); 
    });

    //If First time user, do register
    if( user_info.user_id == '' ) 
    {
        setTimeout(function(){ doGuestRegistration(); },5000);        
    }

    $('#search_btn').off('click').on('click', doSearch);

});


//TIMER
function jpTimer(){
  var secTime = 0,
      minTime = 0,
      hourTime = 0;
  
  setInterval(function(){
    var maxSec = 59,
        maxMin = 59,
        maxHour = 59;
    
    if( secTime > maxSec ){
      minTime++;
      if( minTime > maxMin ){
        hourTime++;
        if( hourTime > maxHour ){
          hourTime = 0;
          minTime = 0;
          secTime = 0;
        }
        minTime = 0
      }
      secTime = 0;
    }
        
        var newSec = (secTime.toString().length == 1) ? '0' + secTime : secTime,
            newMin = (minTime.toString().length == 1) ? '0' + minTime : minTime,
            newHour = (hourTime.toString().length == 1) ? '0' + hourTime : hourTime;
        //console.log(newHour + ':' + newMin + ':' + newSec);

        $('.timer').html( newMin + ':' + newSec );
    
    secTime++;

    if( newMin == '02' )
    {
        secTime = 0,
        minTime = 0,
        hourTime = 0;

        window.location.reload();
    }
        
  }, 1000);
}
             
//jpTimer();



    
//FUNCTIONS

function doSearch()
{
    
    var joinKey = $('input[name="join_key"]').val(),
        pwd = $('input[name="password"]').val();


    $('#main_search').submit();
    //console.log(joinKey, pwd);
}

function add_toggle()
{
   $("#dropdownMenu2").attr("data-toggle","dropdown");  
   $(".dropup").removeClass('open');
}

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
        channel_id: user_info.join_key,
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


function doGuestRegistration()
{
    var latlon = $("#latlang").val(),
        data = {},
        flag = false;
    
    if( latlon == 'INVALID')   
    {


    }
    else
    {
        flag = true;

        data = {
            lat: myCurrentPos.lat,
            lng: myCurrentPos.lng,
            phone_number: user_info.phonenumber,
            display_name: user_info.display_name,
            reg_type: 'current'
        };
    }

    if( flag )
    {
        showLoader();
        $.post(site_url + 'home/guestRegistration', data, function(response) {
            showLoader( false );
            alert(response.msg);
            if (response.status == 'success') {

                //update global user_info 
                user_info = response.user_info;

            }
        }, 'json');
    }



    
    



       
}