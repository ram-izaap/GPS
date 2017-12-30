
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
var share_map_id;
var geocoder;
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
    var publicLocations = []; //Only for Public Map

    var participants = [];

    var myCurrentPos = undefined;
    var adminInfo = undefined;
    var trackingUser ;
    var breadcrumbUser;
    var usertag_displayname;
    module.init = function ( callback )
    {

        module.prepareData();

        if( map_data.type == 'public' )
        {
            publicLocations = locations.splice(0,1);//.push( locations.splice(0,1) );
        }

        console.log('publicLocations', publicLocations);
        
        if( !trackingUser ) 
        {
            trackingUser = adminInfo;
            trackingUser.join_key = map_data.join_key;
            localStorage.setItem("tracking_user",JSON.stringify(trackingUser));
        }
        

        if( !breadcrumbUser ) 
        {
            breadcrumbUser = adminInfo;
            breadcrumbUser.timelimit = 1;
            localStorage.setItem("breadcrumb_user",JSON.stringify(breadcrumbUser));
        }
        
        //update user position two minutes once
        window.user_position_save_interval =  setInterval(function(){
            //clearInterval(window.user_position_save_interval);
            user_position_save();
         },120000);

        // console.log(adminInfo);
        // return;
        
        var zoomlvl = 13;

        map = new google.maps.Map(document.getElementById('map'), {
              zoom: zoomlvl,
              center: {lat: parseFloat(trackingUser.lat), lng: parseFloat(trackingUser.lang)},
              gestureHandling: 'greedy',
              mapTypeControl: true,
              mapTypeControlOptions: {
                                       style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                                       mapTypeIds: [                                
                                         google.maps.MapTypeId.ROADMAP,
                                         google.maps.MapTypeId.SATELLITE,
                                         google.maps.MapTypeId.TERRAIN
                                       ],
                                       position: google.maps.ControlPosition.RIGHT_CENTER
                                     },
             streetViewControl: true,
             zoomControl: true,
             zoomControlOptions: {
                 position: google.maps.ControlPosition.RIGHT_CENTER,
             },
             streetViewControlOptions: {
               position: google.maps.ControlPosition.RIGHT_CENTER
             },
             rotateControlOptions:{
                                   position: google.maps.ControlPosition.RIGHT_CENTER
                                 },
             
             scaleControl:true,
             scrollwheel:false,
             panControl: true,
             panControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER
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

        //module.prepareData();

        module.render( visibles, 'visibles');
        module.render( invisibles, 'invisibles');
        module.render( clues, 'clues');

        if( publicLocations.length )
        {
            module.renderMarkers(publicLocations[0], 0, 'publicLocations');
        }  
    };


    module.prepareData = function( )
    {
        var localTrackData = localStorage.getItem("tracking_user"),
            checkTrackUser = true;
        
        if( localTrackData ) localTrackData = JSON.parse(localTrackData);        
        
        if( !localTrackData || localTrackData.join_key != map_data.join_key )
        {
            checkTrackUser = false;
        }
            
        var localBreadCrumbData = localStorage.getItem("breadcrumb_user");

         if( localBreadCrumbData ) localBreadCrumbData = JSON.parse(localBreadCrumbData);        
        
        if( !localBreadCrumbData || localBreadCrumbData.user_id != map_data.user_id )
        {
            checkBreadCrumbUser = false;
        }

        for (var i = 0; i < locations.length; i++)
        {
            var location = locations[i],
                mapType = location.location_type,
                visible = location.visible;




            if( mapType == 'dynamic' )
            {
                //get admin data
                if( location.user_type == 'admin' )
                {
                    adminInfo = location;
                }
                
                

                if( visible == '1' )
                {
                    visibles.push( location );
                    
                    
                    if( checkTrackUser && localTrackData.channel_id == location.channel_id )
                    {
                        trackingUser = location;
                        trackingUser.channel_id = localTrackData.channel_id;
                    }

                    if( checkBreadCrumbUser && localBreadCrumbData.user_id == location.user_id )
                    {
                        breadcrumbUser           = location;
                        breadcrumbUser.timelimit = localBreadCrumbData.timelimit;
                    }
                    
                }
                else if( visible == '0' )
                {
                    invisibles.push( location );
                }
            } 
            else if( mapType == 'staticmap' && visible == '' )
            {
                clues.push( location );
            }
            else
            {
                //PUBLIC MAP
                //visibles.push( location );
            }

                      
        }
        
        console.log(visibles);
        console.log(clues);
        console.log(invisibles);
        console.log(adminInfo);
    };

    module.render = function( data, type )
    {
        for (var i = 0; i < data.length; i++) 
        {
            var adminIcon = '';

            if( type == 'visibles' || type == 'clues' )
            {
                module.renderMarkers( data[i], i, type );

                if( data[i].user_type == 'admin' )
                {
                    adminIcon = '<span class="group_admin sprite-image">&nbsp;</span>';
                }
                
            }

            //Add header
            if( i == 0 )
            {
                var $title = '';
                
                if( adminInfo !== undefined ) $title = $('<li class="text-left map-admin">Administrator : '+  adminInfo.display_name +'</li>');

                var $header = $('<li class="map-list-label">' +
                                '<span class="name">Participant</span>' + 
                                '<span>Find</span> ' + 
                                '<span>Status</span></li>');

                switch( type)
                {
                    case 'visibles':
                        $('#tab1').append($title, $header);
                        break;

                    case 'invisibles':
                        $header.find('span:nth-child(2)').remove();
                        $('#tab2').append($title, $header);
                        break;

                    case 'clues':
                        $title = '<li class="text-center invisible-head">Static Maps/Clues</li>';
                        $('#tab3').append($title, $header);
                        break;

                }

                //Add title for "All" tab
                if( !$('#tab0 .map-admin').length )
                {
                    $('#tab0').append($title, $header);
                }



            }


            var template = new Element('#participant-template'),
                $element = template.element;

            $element.find('.display_name').html( data[i].display_name );
            $element.find('.channel_id').html( data[i].channel_id );

            if( adminIcon !== '' )
            {
                $element.find('.p-find-iocn').prepend( adminIcon );  
            }

            $element.find('.myposition').attr( 'data-type', type);
            $element.find('.myposition').attr( 'data-index', i);

            $element.find('.statuspop').attr( 'data-type', type);
            $element.find('.statuspop').attr('data-index', i);
            
            $element.find('.myposition').on('click', function(){
                module.locatePosition( $(this).attr('data-index'), $(this).attr('data-type') );
            });

            $element.find('.statuspop').on('click', function(){
                module.locateAndOpenInfo( $(this).attr('data-index'), $(this).attr('data-type') );
            });

            if( type == 'visibles' )
            {
                $("#tab1, #tab0").append($element);
            }
            else if( type == 'invisibles' )
            {
                $element.find('.myposition, .statuspop').remove();
                $element.find('.p-find-iocn').html('<span class="invisible_icon">&nbsp;</span>');
                
                $("#tab2, #tab0").append($element);
            }
            else if( type == 'clues' )
            {
                $("#tab3, #tab0").append($element);
            }

            //$("#tab0").append($element);

        }

        
        $('#participants-count').html(visibles.length);
    };

    
    module.renderMarkers = function( location, index, type )
    {
        if(location.location_type != 'staticmap')
        {
            var iconcustom = {
                 url: location.marker_color, 
                 scaledSize: new google.maps.Size(80, 40)
             };

             if(location.display_name.length > 8){
                usertag_displayname = location.display_name.slice(0,8)+"...";    
             }
             else
             {
                 usertag_displayname = location.display_name;    
             }
            var labeltext           = {text:usertag_displayname,color:"white"};     
        }
        else
        {
            iconcustom = '';
            labeltext  = '';
        }

           

        var last_seen_time = new Date(location.last_seen_time);

        var marker = new google.maps.Marker({
             position: new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lang)),
             map: map,
             animation: google.maps.Animation.DROP,
             label:labeltext,
             title: location.display_name+'\nUpdated : '+formatTime( last_seen_time ),
             optimized: false,
             draggable:false,
             icon:iconcustom,
             zIndex:9999+index
            });

        google.maps.event.addDomListener(marker, 'click', (function(marker, i, type, location, module) {
               
               return function() 
               {
                    infowindow.close();
                    infowindow.setContent( module.renderInfoWindow( location, type, i ) );
                    infowindow.open(map, marker);
               }

        })(marker, index, type, location, module));

        if( type == 'visibles' ) visibles_markers.push( marker );
        if( type == 'clues' ) clues_markers.push( marker );        
        
    }

    module.renderInfoWindow = function( location, type, index )
    {
        //return 'rutoiweru';
        var template = new Element('#map_info_wndow'),
            $element = template.element;

        var last_seen_time = new Date(location.last_seen_time);

        $element.find('.profile-img').attr('src', location.profile_image);

        $element.find('.channel_id').html( location.channel_id );
        $element.find('.display_name').html( location.display_name );
        $element.find('.lastseen').html( formatTime( last_seen_time ) );

        //speed
        if( location.speed != '' )
        {
            $element.find('.speed').html( location.speed );
        }
        else
        {
            $element.find('.speed').remove();
        }

        //accuracy
        if( location.speed != '' )
        {
            $element.find('.accuracy').html( location.accuracy );
        }
        else
        {
            $element.find('.accuracy').remove();
        }

        $element.find('.lat').html( location.lat );
        $element.find('.lng').html( location.lang );
        
         //added map links to map buttons
         var navigator_link = 'http://maps.google.com/maps?z=12&t=m&q=loc:'+location.lat+location.lang;
         var email_link     = 'mailto:?subject=Here\'s MyGPS&body=Hi,'+site_url+location.channel_id;
         if(typeof location.phonenumber != 'undefined'){
            var call_link      = 'tel:'+location.phonenumber;
            var sms_link       = 'sms:'+location.phonenumber;
            $element.find("#call_link").attr("href",call_link);
            $element.find("#sms_link").attr("href",sms_link);
         }
         
         $element.find("#navigate_link").attr("href",navigator_link) ; 
         $element.find("#email_link").attr("href",email_link);


        var localTrackData = JSON.parse(localStorage.getItem('tracking_user'));

        if(localTrackData.channel_id == location.channel_id)
        {
            $element.find('.track_userr').attr( "checked", "checked");
        }
        
        var localBreadCrumbData = JSON.parse(localStorage.getItem('breadcrumb_user'));
        if(localBreadCrumbData.user_id == location.user_id)
        {

            $element.find('.breadcrumb[value="'+localBreadCrumbData.timelimit+'"]').attr( "checked", "checked");

        }

         $element.find('.track_userr').attr( 'data-type', type);
         $element.find('.track_userr').attr( 'data-index', index);
         $element.find('.breadcrumb').attr( 'data-member', location.user_id);
         $element.find('.breadcrumb').attr( 'data-index', index);
        return $element.html();
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
        
        //close participant menu
        add_toggle(); 
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

    module.closeinfowindow = function ( closeid )
    {
        infowindow.close();

        if(closeid != 1)
        {
           setTimeout(function(){ map.setZoom(16); },2000);
        }
    }

    module.breadcrumb = function ( obj )
    {
        infowindow.close();
         
        var timelimit = $(obj).val();
        var index     = $(obj).attr("data-index");

        if(typeof visibles[index] == 'undefined') return false;

        visibles[index].timelimit = timelimit;
        localStorage.setItem("breadcrumb_user",JSON.stringify(visibles[index]));

        var data = {
                        user_id: visibles[index].user_id,
                        timelimit: timelimit
                   };
                 
        $.ajax({
        type:"POST",
        url:site_url+'search/breadcrumb/',
        data:data,
        success:function(response){
                response  = JSON.parse(response);
                console.log(response);
                for(var i = 0;i<response.length;i++){
                    var markericon = (response[i].flag==2)?site_url+"assets/images/purple_pos.png":(response[i].flag==0)?site_url+"assets/images/yellow_pos.png":site_url+"assets/images/red_pos.png";
                    response[i].display_name = '';
                    response[i].marker_color = markericon;

                   var marker = new google.maps.Marker({
                                     position: new google.maps.LatLng(parseFloat(response[i].lat), parseFloat(response[i].lon)),
                                     map: map,
                                     animation: google.maps.Animation.DROP,
                                     optimized: false,
                                     draggable: false,
                                     icon:markericon,
                                    // zIndex:9999999+index
                        });
                    visibles_markers.push( marker );
                   
                }
               //  map.setCenter( visibles_markers[index] );
               //  map.setZoom(18); 
           }  
      });
    }

    module.clearTracking = function ( obj )
    {

        localStorage.setItem("tracking_user","");

      //  console.log(obj);
      //  return;
        //infowindow.close();
        //
        //        if(closeid != 1)
        //        {
        //           setTimeout(function(){ map.setZoom(16); },2000);
        //        }
    }

   //update track user
   module.trackUser = function ( obj )
   {
        var trackUserIndex = $(obj).attr('data-index');
        
        if(typeof visibles[trackUserIndex] == 'undefined') return false;
        
        visibles[trackUserIndex].join_key = map_data.join_key;
        localStorage.setItem("tracking_user",JSON.stringify(visibles[trackUserIndex]));
   }

  
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
        firstChild.style.marginTop = '10px';
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

            if( !$.isEmptyObject(myCurrentPos)  )
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
        map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(controlDiv);
    };
    

})( mapManager || ( mapManager = {} ) );


if( locations.length )  mapManager.init();


//Onload functions
$(document).ready(function(){
    
    set_my_current_pos_by_local_data_pos();

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
        
          window.doGuestRegistrationInterval =  setInterval(function(){
            
            if( user_info.user_id == '' ) 
            {
                clearInterval(window.doGuestRegistrationInterval);
                doGuestRegistration();
            }
            
         },5000);
         
        //setTimeout(function(){ doGuestRegistration(); },5000);        
    }

    $('#search_btn').off('click').on('click', doSearch);

    jpTimer();


    //search page error hanling
    if( controller == 'search' 
        && typeof user_info.error_message != 'undefined' 
        && user_info.error_message != '' )
    {
        alert(user_info.error_message);
        location.href = site_url;
    }

    
});


//HELPER FUNCTIONS
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

       // window.location.reload();
    }
        
  }, 1000);
}
             


function formatTime(date) 
{

    var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';

    var month = (date .getMonth()+1);
    month = month < 10 ? '0'+month : month;

    var day = days[date.getDay()];
    var fulldate = date .getDate()+"-"+ month +"-"+date .getFullYear();

    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;

    var strTime = hours + ':' + minutes + ' ' + ampm + ' '+ day + ' ' + fulldate;

    return strTime;
}

    
//FUNCTIONS

function doGuestRegistration()
{
    var latlon = localStorage.getItem('latlang'),//$("#latlang").val(),
        data = {},
        flag = false;
    
    if(!latlon){
     //if(1==1){   
      openModals('manual_address_popup');
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
                openModals( 'display_name_update' );
            }
        }, 'json');
    }

}

function doSearch()
{
    
    var joinKey = $('input[name="join_key"]').val(),
        pwd     = $('input[name="password"]').val();
    
    console.log(joinKey, pwd);
    
    var data    = {
                    join_key: joinKey,
                    password: pwd   
    };
    
    showLoader();

    $.post(site_url + 'search/validateJoinKey', data, function(response) {

        showLoader(false);
        
        if(response.status == 'success'){
            $('#main_search').submit();
        }
        else
        {
            alert(response.msg);
            if(response.type == 'password'){
              $('input[name="password"]').removeAttr('placeholder');  
              $('input[name="password"]').focus();
            }  
            return false;
        }

    }, "json"); 

    
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
      
        if (response.status == 'success') 
        {
            closeModals();

            setTimeout(function(){
                alert(response.msg);
                location.reload();
            }, 200);
            
        }
        else
        {
            alert(response.msg);
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
        channel_id: mapID,
        updateCookie: (user_info.join_key == user_info.channel_id)
    };

    //console.log(data);

    $("#wait").css("display", "block");

    $.post(site_url + '/home/updateChannelID', data, function(response) {

        $("#wait").css("display", "none");

        

        if (response.status == 'success') 
        {
            closeModals();

            /*  If we update the Map ID which we are on, update the url to reload. Otherwise
            *   Old MapID will get pagged and make issues
            */
            var urlToReload = location.href; 
            if(controller == 'search' && user_info.join_key == user_info.channel_id)
            {
                urlToReload = site_url+mapID;
            }
            
            setTimeout(function(){
                alert(response.msg);
                location.href = urlToReload
            }, 200);
            
        }
        else
        {
            alert(response.msg);
        }




    }, "json");
}

function updateVisibleStatus() 
{
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

        location.reload();

    }, "json");


}

function removeAllMaps() 
{
    var data = {
        user_id: user_info.user_id
    };

    showLoader();

    $.post(site_url + 'home/removeAllMaps', data, function(response) {

        showLoader(false);

        alert(response.msg);

        //Now redirect user to thier own map
        location.href = site_url+'search/'+ user_info.channel_id;

    }, "json");

}

//social share map section
function share_map(type)
{
    if(type == 'join_key')
        share_map_id = user_info.join_key;
    else
        share_map_id = user_info.channel_id;
    
    render_social_share(share_map_id);     
}

function render_social_share(share_map_id)
{
    var shr_ct = $("#shr_mp_content").html();   

    $("#searchmapshare").html(shr_ct); 

    var sms_href    = 'sms:?body=Hi, View my location on live map and join me at: '+site_url+"/"+share_map_id;
    var email_href  = "mailto:?subject=Here's MyGPS&body=Hi, View my location on live map and join me at: ";
        email_href += site_url+"/"+share_map_id;
    var cpy         = "copyToClipboard('#copy_clipboard','#websitesearch_text','"+share_map_id+"')";


    //copy clipboard adding onclick event
    $("#searchmapshare .cpy_clip").attr('onclick', cpy);

    //replace href in sms & email share
    $("#searchmapshare .sms_share").attr('href', ''+sms_href);
    $("#searchmapshare .email_share").attr('href', ''+email_href); 

    $("#searchmapshare").socialButtonsShare({
        socialNetworks: ["facebook", "twitter", "googleplus", "pinterest", "tumblr"],
        url: site_url+'/search/'+share_map_id,
        type: $("#joined_map").val(),
        text: "\nMy Map ID is "+share_map_id+"\n\n View my location @ "+site_url+"/"+share_map_id+"\n\n"+"Or you can search for my ID on the HeresMyGPS.com website.  You can also join me on my map using the free app.  Get the app @ \nwww.heresmygps.me/apps",
        sharelabel: false
    });
     
}

function copyToClipboard(element,altdiv,sharemp) 
{
    var $temp = $("<input type='hidden'>");
    $("body").append($temp);
    $temp.val(site_url+sharemp).select();
    document.execCommand("copy");
    $temp.remove();
    var url = site_url+sharemp;
    $(altdiv).css("display","block").html("Copied "+url+" to clipboard");
    $(altdiv).fadeOut(4000);
}


//get lat long from manual address
function getLatLong()
{
     var manual_address  = $('input[name="manual_address"]').val();

     var geocoder = new google.maps.Geocoder();

    geocoder.geocode( { 'address': manual_address}, function(results, status) {
        console.log(results);
       
      if (status == 'OK') {
           if( typeof results[0] == 'undefined' || typeof results[0].geometry == 'undefined' )
            {
                alert('Geocode was not successful' );
            }
            else
            {   
                //set myCurrentPos values
                myCurrentPos = {};
                myCurrentPos.lat = '13.040915799999999'//results[0].geometry.location.lat;
                myCurrentPos.lng = '80.17330849999999'//results[0].geometry.location.lng;

                //
                localStorage.setItem('latlang',myCurrentPos.lat+':'+myCurrentPos.lng);
                //$("#latlang").val(myCurrentPos.lat+':::'+myCurrentPos.lng);

                console.log(myCurrentPos);
                $("#manual_address_popup").hide();
                //now do registartion
                doGuestRegistration();
            }
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
      
}


//HELPER FUNCTIONS
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


function openModals(type) 
{
    if (type == undefined) type = 'display_name_update';

    $('#' + type).remove();
    $.post(site_url + 'home/getModalConent/' + type, {}, function(resp) {
        
        //insert into document
        $('body').append(resp.content);

        if (type == 'display_name_update') 
        {

            //$('input[name="update_type"]').attr("checked", "checked");
            changeRadioStatus( 'input[name="update_type"][value="system"]' );
            $('input[name="system_value"]').val( 'Use System Generated('+ user_info.display_name +')' );
            $('input[name="custom_phonenumber"]').val(  user_info.phonenumber );

        }

        if (type == 'map_id_update') 
        {
            $("#custom_map_id").val(user_info.channel_id);
        }

        if(type == 'social_share')
        {
            render_social_share(user_info.channel_id);
            $("#share_map_id_display").html(user_info.join_key);
            $("#share_map_own_id_display").html(user_info.channel_id);
        }
     
        //now open modal
        $('#' + type).modal();
        
        
        
    }, 'json');
}

function closeModals( type )
{
    type = type || '';

    //close all modals or only specific type
    $(type+' button[data-dismiss="modal"]').trigger('click');
}


function changeRadioStatus(id) 
{
  $(id).prop("checked", true);
}

//user position update 
function user_position_save(){

       var latlon = myCurrentPos;/*,
           res    = (latlon)?latlon.split(":"):[];*/

        if( myCurrentPos == undefined || myCurrentPos.lat == undefined || myCurrentPos.lng == undefined ){
            
          return;
          
          res[0] = 'noloc';
          res[1] = 'noloc';
        }
        
        var data  = {
                        'user_id': user_info.user_id,
                        'lat'    : myCurrentPos.lat,
                        'long'   : myCurrentPos.lng
                   };

        $.post(site_url+'/user/updateUserPosition/', data, function(response){

            if(user_info.join_key!=''){
                $.post(site_url+'/search/'+user_info.join_key, {}, function(response){  
                    if(response!=''){
                        response = JSON.parse(response);
                        console.log(response);
                        //map_search(response.locations,response.contents,response.user_id,0);
                    }
                });
            }               
        });
    }
    
    function set_my_current_pos_by_local_data_pos(){
        
        var latlon = localStorage.getItem('latlang'),
            res    = (latlon)?latlon.split(":"):[];
            
            if( res.length ){
                
               myCurrentPos = {
                  lat: res[0],
                  lng: res[1]
               };
            }
    }
