//@prepros-prepend jquery.fittext.js
//@prepros-prepend fastclick.js
//@prepros-prepend burger.js
//@prepros-prepend min/remodal.min.js
//@prepros-prepend min/lazysizes.min.js
//@prepros-prepend min/jquery-ui.min.js

$('.b-nav').on('click', 'a', function(e) {
    if($(this).parent().find('ul').length) {
        
        if($(this).parent().find('ul').first().is(':visible')) {
            $(this).parent().find('ul').hide();
        } else {
            $('.b-nav li ul').hide();
            $(this).parent().find('ul').show();
        }
        
        return false;   
    }
});

$(".why__story").fitText(0.5);