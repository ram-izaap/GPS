<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>911 GPS</title>
    <script src="js/lib/modernizr-2.8.3.js"></script>
    <!-- Bootstrap -->
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>



  <header class="clearfix">

    <!-- 1st Block -->
    <div class="col-s-m-4 menu-block block-left">
      <span class="nav-trigger pull-left" onclick="openNav()">
        <span class="burger-menu"></span>
      </span>

      <span class="pull-left logo">
        <a href="index.html">
          <img src="images/logo.png" alt="911 GPS">
        </a>
      </span>

      <form action="" class="serch-form pull-left">
        
        <span class="dropdown search-filter pull-left">
          <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Search By  <span class="caret"></span>
          <span class="data-name">Channel ID</span></a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="javascript:void(0);" data-name="Channel ID">Channel ID</a></li>
            <li><a href="javascript:void(0);" data-name="Display Name">Display Name</a></li>
            <li><a href="javascript:void(0);" data-name="Adress">Adress</a></li>
            <li><a href="javascript:void(0);" data-name="UTM Coordinates">UTM Coordinates</a></li>
            <li><a href="javascript:void(0);" data-name="GPS Coordinates">GPS Coordinates</a></li>
          </ul>
        </span>

        <span class="search-wrap pull-left">
          <input type="text" class="form-control" placeholder="Enter Channel ID">
        </span>

        <span class="tool-tip pull-left top-search-icon" data-text="Search" data-color="green" data-position="left">
          <input type="submit" value="">
        </span>

        <span class="top-clear-icon tool-tip" data-text="Clear" data-color="green" data-position="left">      
          <input type="reset" value="" class="pull-left">
        </span>
        
      </form>

    </div>
    
    <!-- 2nd Block -->
    <div class="col-s-m-6 block-center">
      <span class="cMapid"> Current Map ID is <i></i> <b>Miranda</b> </span>
      <span class="top-share"> <a href="javascript:void(0);" data-toggle="modal" data-target="#shareModal"><i class="fa fa-share-alt fa-2x"></i></a></span>

      <!-- <span class="map-visible pull-right"> <a href="javascript:void(0);"><i class="fa fa-eye fa-2x"></i> <b>Visible</b></a></span> -->
    </div>

    <!-- 3nd Block -->
    <div class="col-s-m-2 block-3 block-right pull-right">
      <span class="map-visible pull-left"> <a href="javascript:void(0);"><i class="fa fa-eye fa-2x"></i> <b>Visible</b></a></span>
      <a href="javascript:void(0);" class="my-map pull-left">Return to <small>My Map</small></a>
      <a href="javascript:void(0);" class="all-map pull-left">Exit <small>All Maps</small></a>
    </div>
  </header>

  <!--  -->

  <div class="map-parent">
    <div class="col-sm-9 map-left">
      <!-- <div id="map"></div> -->
    </div>
    <div class="col-sm-3 map-right">
      
      <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#show-1">
      <i class="fa fa-users" aria-hidden="true"></i>
    </a></li>
    <li><a data-toggle="tab" href="#show-2">
        <i class="fa fa-users" aria-hidden="true"></i>
    </a></li>
    <li><a data-toggle="tab" href="#show-3">
      <i class="fa fa-users" aria-hidden="true"></i>
    </a></li>
  </ul>

  <div class="tab-content">
    <div id="show-1" class="tab-pane fade in active">
      
      <p> <b>Participant</b></p>
      
      
      <!-- S code -->
      <ul class="list-group" id="participants-list"><li class="text-center map-admin">Administrator : Miranda</li><li class="map-list-label"><span class="name">Participant</span> <span>Find</span> <span>Status</span></li><li><a href="javascript:posclick(0)"><div class="p-parti"><span class="name"><b>DN: </b> Jeff Holden</span><span class="name"><b>CHID: </b>Jeff</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(0)" id="Jeff" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(1)"><div class="p-parti"><span class="name"><b>DN: </b> Miranda</span><span class="name"><b>CHID: </b>Miranda</span></div></a><div class="p-find-iocn"><span class="group_admin sprite-image">&nbsp;</span><a href="javascript:posclick(1)" id="Miranda" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(1,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(2)"><div class="p-parti"><span class="name"><b>DN: </b> KathyH</span><span class="name"><b>CHID: </b>KathyH</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(2)" id="KathyH" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(2,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(3)"><div class="p-parti"><span class="name"><b>DN: </b> ConroyT</span><span class="name"><b>CHID: </b>ConroyT</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(3)" id="ConroyT" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(3,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(6)"><div class="p-parti"><span class="name"><b>DN: </b> 2GM2OR</span><span class="name"><b>CHID: </b>2GM2OR</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(6)" id="2GM2OR" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(6,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li class="text-center invisible-head">Invisible Participants</li><li><a href="javascript:posclick(4)"><div class="p-parti"><span class="name"><b>DN: </b>Q31JUS</span><span class="name"><b>CHID: </b>Q31JUS</span></div></a><div class="p-find-iocn"><span class="invisible_icon">&nbsp;</span></div></li><li><a href="javascript:posclick(5)"><div class="p-parti"><span class="name"><b>DN: </b>YV23JL</span><span class="name"><b>CHID: </b>YV23JL</span></div></a><div class="p-find-iocn"><span class="invisible_icon">&nbsp;</span></div></li></ul>
      <!-- S code -->
    </div>
    <div id="show-2" class="tab-pane fade">
      <p> <b>Invisible Participant</b></p>
      
      <!-- S code -->
      <ul class="list-group" id="participants-list"><li class="text-center map-admin">Administrator : Miranda</li><li class="map-list-label"><span class="name">Participant</span> <span>Find</span> <span>Status</span></li><li><a href="javascript:posclick(0)"><div class="p-parti"><span class="name"><b>DN: </b> Jeff Holden</span><span class="name"><b>CHID: </b>Jeff</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(0)" id="Jeff" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(1)"><div class="p-parti"><span class="name"><b>DN: </b> Miranda</span><span class="name"><b>CHID: </b>Miranda</span></div></a><div class="p-find-iocn"><span class="group_admin sprite-image">&nbsp;</span><a href="javascript:posclick(1)" id="Miranda" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(1,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(2)"><div class="p-parti"><span class="name"><b>DN: </b> KathyH</span><span class="name"><b>CHID: </b>KathyH</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(2)" id="KathyH" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(2,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(3)"><div class="p-parti"><span class="name"><b>DN: </b> ConroyT</span><span class="name"><b>CHID: </b>ConroyT</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(3)" id="ConroyT" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(3,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(6)"><div class="p-parti"><span class="name"><b>DN: </b> 2GM2OR</span><span class="name"><b>CHID: </b>2GM2OR</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(6)" id="2GM2OR" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(6,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li class="text-center invisible-head">Invisible Participants</li><li><a href="javascript:posclick(4)"><div class="p-parti"><span class="name"><b>DN: </b>Q31JUS</span><span class="name"><b>CHID: </b>Q31JUS</span></div></a><div class="p-find-iocn"><span class="invisible_icon">&nbsp;</span></div></li><li><a href="javascript:posclick(5)"><div class="p-parti"><span class="name"><b>DN: </b>YV23JL</span><span class="name"><b>CHID: </b>YV23JL</span></div></a><div class="p-find-iocn"><span class="invisible_icon">&nbsp;</span></div></li></ul>
      <!-- S code -->

    </div>
    <div id="show-3" class="tab-pane fade">
      <p> <b>Participant 0000</b></p>
      
      <!-- S code -->
      <ul class="list-group" id="participants-list"><li class="text-center map-admin">Administrator : Miranda</li><li class="map-list-label"><span class="name">Participant</span> <span>Find</span> <span>Status</span></li><li><a href="javascript:posclick(0)"><div class="p-parti"><span class="name"><b>DN: </b> Jeff Holden</span><span class="name"><b>CHID: </b>Jeff</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(0)" id="Jeff" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(1)"><div class="p-parti"><span class="name"><b>DN: </b> Miranda</span><span class="name"><b>CHID: </b>Miranda</span></div></a><div class="p-find-iocn"><span class="group_admin sprite-image">&nbsp;</span><a href="javascript:posclick(1)" id="Miranda" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(1,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(2)"><div class="p-parti"><span class="name"><b>DN: </b> KathyH</span><span class="name"><b>CHID: </b>KathyH</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(2)" id="KathyH" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(2,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(3)"><div class="p-parti"><span class="name"><b>DN: </b> ConroyT</span><span class="name"><b>CHID: </b>ConroyT</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(3)" id="ConroyT" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(3,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li><a href="javascript:posclick(6)"><div class="p-parti"><span class="name"><b>DN: </b> 2GM2OR</span><span class="name"><b>CHID: </b>2GM2OR</span></div></a><div class="p-find-iocn"><a href="javascript:posclick(6)" id="2GM2OR" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(6,1)" class="statuspop sprite-image">&nbsp;</a></div></li><li class="text-center invisible-head">Invisible Participants</li><li><a href="javascript:posclick(4)"><div class="p-parti"><span class="name"><b>DN: </b>Q31JUS</span><span class="name"><b>CHID: </b>Q31JUS</span></div></a><div class="p-find-iocn"><span class="invisible_icon">&nbsp;</span></div></li><li><a href="javascript:posclick(5)"><div class="p-parti"><span class="name"><b>DN: </b>YV23JL</span><span class="name"><b>CHID: </b>YV23JL</span></div></a><div class="p-find-iocn"><span class="invisible_icon">&nbsp;</span></div></li></ul>
      <!-- S code -->

    </div>
  </div>

    </div>
  </div>

  

  <!--  Map Overlay -->
  <div class="map-layer">
    <div class="conta-iner clearfix">

        

        <div class="drop-wrap pull-right">
        <div class="dropup">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Participants <b>3</b>
            <span class="caret"></span>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu2" id="participants-list">
            <!-- <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li> -->

            <li class="map-list-label">
              <span class="name">Participant </span> 
              <span> Find </span> <span>Status </span>
            </li>

            <li>
              <a href="javascript:posclick(0)">
                <div class="p-parti">
                <span class="name"> <b>DN:  </b> Jeff Holden </span>
                <span class="name"> <b>CHID:  </b>Jeff </span>
                </div>
              </a>
              
              <div class="p-find-iocn">
                <a href="javascript:posclick(0)" id="Jeff" class="myposition sprite-image">&nbsp; </a>
                <a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp; </a>
              </div>
            </li>

            <li>
              <a href="javascript:posclick(0)">
                <div class="p-parti">
                <span class="name"> <b>DN:  </b> Jeff Holden </span>
                <span class="name"> <b>CHID:  </b>Jeff </span>
                </div>
              </a>
              
              <div class="p-find-iocn">
                <a href="javascript:posclick(0)" id="Jeff" class="myposition sprite-image">&nbsp; </a>
                <a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp; </a>
              </div>
            </li>

            <li>
              <a href="javascript:posclick(0)">
                <div class="p-parti">
                <span class="name"> <b>DN:  </b> Jeff Holden </span>
                <span class="name"> <b>CHID:  </b>Jeff </span>
                </div>
              </a>
              
              <div class="p-find-iocn">
                <a href="javascript:posclick(0)" id="Jeff" class="myposition sprite-image">&nbsp; </a>
                <a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp; </a>
              </div>
            </li>

            <li class="map-list-label"><span class="name">Participant</span> <span>Find</span> <span>Status</span></li>

            <li><a href="javascript:posclick(0)">
              <div class="p-parti"><span class="name"><b>DN: </b> Miranda</span><span class="name"><b>CHID: </b>Miranda</span></div></a><div class="p-find-iocn"><span class="group_admin sprite-image">&nbsp;</span><a href="javascript:posclick(0)" id="Miranda" class="myposition sprite-image">&nbsp;</a><a href="javascript:myclick(0,1)" class="statuspop sprite-image">&nbsp;</a></div></li>
          </div>
        </div>         
        </div>

        <div class=" pull-right timer-btn">
          <span class="btn btn-default">
            1.22 <i class="fa fa-repeat" aria-hidden="true"></i>
          </span>
        </div>

    </div>
  </div>

  </body>
</html>