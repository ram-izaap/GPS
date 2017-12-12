/*!
 * custom.Js
 * www.911gps.me
 */

// Nav Function
function openNav(id,device_type) {
    
    if(device_type == 'website')
        document.getElementById(id).style.width = "533px";
     else
        document.getElementById(id).style.width = "100%";
    // $(this).addClass('test');
     $('body').addClass('active');
     $('.nav-close').addClass('navEnabled');
}

function closeNav(id) {
    document.getElementById(id).style.width = "0";
    $('body').removeClass('active');
    $('.nav-close').removeClass('navDisabled');
}


$(function(){
  console.log('Ready to Launch');

$('body').animate({opacity:1},1000);


// Top Drop Menu [Custom Select Box]
$("ul.dropdown-menu a").click(function()
    {
      var search_text = $(this).attr("data-name");
      $("span.data-name").text(search_text);
});

// 
$(".edit").on("click",function()
  {
    $(this).prev(".text-field").removeAttr('readonly');
    $(this).css("display","none");
    $(this).next(".submit").css("display","block");
  });

$(".submit").on("click",function()
  {
    $(this).prevAll(".text-field").attr('readonly',true);
    $(this).prev(".edit").css("display","block");
    $(this).css("display","none");
  });

/*video banner*/
  //var vedioWid = $('.vedioPlayer iframe').width();
  var vidUrl = $('.vedioPlayer iframe').attr('src');
  //$('.video-section img').css({width:vedioWid});  
  $('.video-section .ply-icon').on('click', function(){
    $('.video-section').addClass('open');
    $('.vedioPlayer iframe').attr('src', vidUrl+'?rel=0&autoplay=1');
  });
  $('.closeIon').on('click', function(){
    $('.video-section').removeClass('open');
    $('.vedioPlayer iframe').attr('src', vidUrl+'?rel=0&autoplay=0');
      
  });





});

