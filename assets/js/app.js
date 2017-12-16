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