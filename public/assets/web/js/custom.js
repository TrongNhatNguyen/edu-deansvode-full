$(function() {
    if ($("#refeshcode").length > 0)
    {
        $("#refeshcode").click(function(){
            $.get('/ajax/refresh-captcha',function(rs){
                $("#refresh-captcha").html(rs);
            });
            return false;
        });
    }
});


function validate_login(){
    var username = $('input[name="username"]').val();
    var password = $('input[name="password"]').val();
    var validate_string = $('input[name="validate_string"]').val();
    if(username==""){
        showPopup("User Required!");
        //$('input[name="username"]').focus();
        return false;
    }
    if(password==""){
        showPopup("Password Required!");
        //$('input[name="password"]').focus();
        return false;
    }
    if(validate_string==""){
        showPopup("Security Code Required");
        //$('input[name="validate_string"]').focus();
        return false;
    }

   return true;
}


$(window).load(function () {
    if($('#slider').length > 0)
    {
        $('#slider').nivoSlider();
    }
});


$( document ).ready(function() {
    //JS for Menu on Mobile
    $('.js-toggle-menu').click(function(e){
        e.preventDefault();
        $(this).toggleClass('open');
        $('.menu-login').toggleClass('is-open');
        $('body').toggleClass('open-menu');
    });
	$(".JSaboutus").owlCarousel({ 
              dots: false,
              nav:false,
              autoplay:true, 
              autoplayTimeout:2500,
              smartSpeed: 500,
              loop:true,
              items:1 
            });
});
function showPopup($msg,$calback) {
    $('#popup-noti').find('#content-msg').html($msg);
	/* $("#popup-noti").fancybox();
	console.log($('#popup-noti').find('#content-msg').html());
	$.fancybox.reposition();*/
   $.fancybox({
        href : '#popup-noti',
        afterClose  : $calback,
    });
}
