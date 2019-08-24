var widthBr = $(window).width();
var posScroll = 0;
if (widthBr <= 640) {
    var posScroll = 70;
}

/* Anime Scroll
=========================================== */
$(function() {
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top - posScroll
        }, 1000);
        return false;
      }
    }
  });
});





/* Resize swap images
-------------------------------------- */
// $(window).on('resize', function() {
//   var win = $(this);
//   if (win.width() <= 640) {
//     $('img').each(function() {
//         $(this).attr("src", $(this).attr("src").replace('_pc', '_sp'));
//     });
//   } else {
//     $('img').each(function() {
//         $(this).attr("src", $(this).attr("src").replace('_sp', '_pc'));
//     });
//   }
// });

// $(window).on('load', function () {
//   var win = $(this);
//   if (win.width() <= 640) {
//     $('img').each(function() {
//         $(this).attr("src", $(this).attr("src").replace('_pc', '_sp'));
//     });
//   } else {
//     $('img').each(function() {
//         $(this).attr("src", $(this).attr("src").replace('_sp', '_pc'));
//     });
//   }
// });
/* menu
-------------------------------------- */
$(document).ready(function(){
  $('.btn-icon').click(function(){
    $(this).toggleClass('active');
    $(".header-nav").stop().slideToggle();
  });
});

/* header-lang
-------------------------------------- */
$(document).ready(function(){
  $('.header-lang').click(function(){
    $(this).toggleClass('active');
  });
});
