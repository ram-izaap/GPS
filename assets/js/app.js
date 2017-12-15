
function showLoader( flag )
{
  if ( typeof flag == 'undefined' ) flag = true;

  if( flag )
  {
    $("#wait").css("display", "block");
  }
  else
  {
    $("#wait").css("display", "none");
  }

}

function updateVisibleStatus()
{
    if( user_info.group_id == '' )
    {
        alert("You should join map");
        return false;
    }

    var data = {
                  user_id: user_info.user_id,
                  channel_id: user_info.channel_id,
                  visible: user_info.visible == '1' ? '0':'1'
                };

    showLoader();

    $.post(site_url+'home/updateVisible', data, function(response){

      user_info.visible = data.visible;

      showLoader( false );

      $("#visiblestatus, #visiblestatus2").html(response.visible_html);

    }, "json");


}

function removeAllMaps()
{
    var data = {user_id:user_info.user_id};

    showLoader();

    $.post(site_url+'home/removeAllMaps', data, function(response){

      showLoader( false );

      alert(response.msg);

      //TO do
      //location.href = site_url+'search/'+map_channel;

    }, "json");

}