$(function() {
  Browser.init();
  Common.init();
  // SliderList.init();
  //TitleHight.init();
  CategoryPage.init();
  ProductPage.init();
});



var Common = {
  init : function() {
    Common.productsMenu();
    Common.fixedNav();
    Common.drawerNav();
    Common.titvertical();
    Common.pageTop();
    Common.smoothScroll();
    Common.searchBox();
  },
  productsMenu : function(){

    // $('#header .hover').each(function(){
    //   var chaffle = new Chaffle(this);
    //   $(this).on({
    //     'mouseenter' : function(){
    //       if ( Browser.responsivePCSP === 'pc' ) {
    //         $(this).width( $(this).width() );
    //         chaffle.init();
    //         Browser.addResizeHandler(function(){
    //           $('#header .hover').css({'width':'auto'});
    //         });
    //       }
    //     }
    //   });
    // });

    // new SimpleBar(document.getElementById('products-list-body'), {
    //   autoHide: false
    // });

    var header_height = $('#header').innerHeight();
    var st = 0;
    $('#header nav ul > li').on({
      'mouseenter' : function(){
        if ( Browser.responsive === 'pc' && $(this).hasClass('products') ) {
          $(this).addClass('on');

          st = Browser.scrollY;
          $('body,html').css({"overflow":"hidden","height":"100%"});
          var products_list_y = $('#header .products-list').position().top;
          var header_y = Browser.scrollY;
          if( header_y > 88 ){
            header_y = 88;
          }
          $('#header .products-list').height( Browser.windowHeight - products_list_y - 80 + header_y );
          $('#header').velocity('stop').velocity({ 'height': Browser.windowHeight + header_y }, { duration: 100 }, {easing:[ 0.19,1,0.22,1 ]});

          $('#header .products-list').scrollTop(0);
        }
      },
      'mouseleave' : function(){
        $(this).removeClass('on');
        if (Browser.responsive === 'pc' && $(this).hasClass('products') ) {
          $('body,html').scrollTop(st);
          $('body,html').css({"overflow":"visible","height":"auto"});
          $('#header').velocity('stop').velocity({ 'height': header_height }, { duration: 150 }, {easing:[ 0.19,1,0.22,1 ]});
          $('#header .products-list a .en').css({ 'width': 'auto', 'height': 'auto' });
        }
      }
    });

    $('#header .products-list a .en').each(function(){
      var chaffle = new Chaffle(this);
      $(this).closest('a').on({
        'mouseenter' : function(){
          if ( Browser.responsive === 'pc'){
            $(this).find('.en').width( $(this).find('.en').width() );
            $(this).find('.en').height( $(this).find('.en').height() );
            chaffle.init();
          }
        }
      });
    });

  },
  fixedNav : function(){
    Browser.addScrollHandler(function(){
      if (Browser.responsive === 'pc') {
        if( Browser.scrollY > 88 ){
          if( !$('#header').hasClass('fixed') ){
            $('#header').removeClass('absolute');
            $('#header').addClass('fixed');
          }
        }else{
          if( $('#header').hasClass('fixed') ){
            $('#header').addClass('absolute');
            $('#header').removeClass('fixed');
          }
        }
      }
    });
    Browser.addResizeHandler(function(){
      if (Browser.responsive !== 'pc') {
        $('#header').removeClass('absolute');
        $('#header').removeClass('fixed');
      }
    });
  },
  drawerNav : function(){
    var st = 0;
    $('#drawer-btn').on('click', function() {
      if( !$('#drawer-content').hasClass('on') ){
        st = Browser.scrollY;
        $('#drawer-content').addClass('on');
        $('#drawer-content').scrollTop(0);
        $('body,html').css({"overflow":"hidden","height":"100%"});
        $('#drawer-btn').velocity('stop').velocity(
        {
          translateX : '100px'
        },
        {
          duration: 100,
          easing:[ 0.19,1,0.22,1 ],
          complete: function(){
            $('#drawer-btn').addClass('close');
            $('#drawer-btn p').html('CLOSE');
            $('#drawer-btn').velocity(
            {
              translateX : '0px'
            },
            {
              duration: 100,
              easing:[ 0.19,1,0.22,1 ]
            }
            );
          }
        }
        );
      }else{
        $('#drawer-content').removeClass('on');
        $('body,html').css({"overflow":"visible","height":"auto"});
        $('body,html').scrollTop(st);
        $('#drawer-btn').velocity('stop').velocity(
        {
          translateX : '100px'
        },
        {
          duration: 100,
          easing:[ 0.19,1,0.22,1 ],
          complete: function(){
            $('#drawer-btn').removeClass('close');
            $('#drawer-btn p').html('MENU');
            $('#drawer-btn').velocity(
            {
              translateX : '0px'
            },
            {
              duration: 100,
              easing:[ 0.19,1,0.22,1 ]
            }
            );
          }
        }
        );
      }
    });
    var timer = false;
    Browser.addScrollHandler(function(){
      if (Browser.responsivePCSP === 'sp') {
          if( timer !== false ){
            clearTimeout( timer );
          }
          if( $('#drawer-btn').hasClass('hidden') ){
            $('#drawer-btn').removeClass('hidden');
          }
          timer = setTimeout( function () {
            if( Browser.scrollY > 0 && !$('#drawer-btn').hasClass('close') ){
              $('#drawer-btn').addClass('hidden');
            }
          }, 1000 ) ;
        }
    });
    $('.content-wrapper').on('touchstart', function() {
      if (Browser.responsivePCSP === 'sp') {
        $('#drawer-btn').removeClass('hidden');
      }
    });

  },
  titvertical : function(){
    $('.tit-vertical').each(function(){
      var self = $(this);
      var h = self.find('p').width();
      self.height(h);
    });
  },
  pageTop : function(){
    var limit = 0;
    var bottom = $('#footer').innerHeight() - 125;
    var top = 0;
    if( $('.ContentsKv').length ){
      top = $('.top-mainvis-block').height() - Browser.windowHeight*0.5 + 100;
    }else{
      top = 0;
    }
    if( $('#breadcrumbs').length ){
      bottom += $('#breadcrumbs').innerHeight() + 25;
    }
    Browser.addScrollHandler(pagetopFunc);
    function pagetopFunc(){
      if (Browser.responsivePCSP === 'pc') {
        limit = $('body').innerHeight() - bottom - Browser.windowHeight + 10;

        if( Browser.scrollY > top ){
          if( !$('#pagetop').hasClass('show') ){
            $('#pagetop').addClass('show');
            $('#pagetop').velocity('stop').velocity({ bottom: 10 },{ duration: 500, easing: [0.19,1,0.22,1] });
          }
          if( Browser.scrollY > limit ){
              $('#pagetop').velocity('stop').css({ 'position': 'absolute', 'bottom': bottom });
          }else{
              $('#pagetop').velocity('stop').css({ 'position': 'fixed', 'bottom': 10 });
          }
        }else{
          if( $('#pagetop').hasClass('show') ){
            $('#pagetop').removeClass('show');
            $('#pagetop').velocity('stop').velocity({ bottom: -150 },{ duration: 500, easing: [0.19,1,0.22,1] });
          }
        }
      }else if (Browser.responsivePCSP === 'sp') {
        if( Browser.scrollY > 50 ){
          if( !$('#pagetop').hasClass('show') ){
            $('#pagetop').addClass('show');
          }
        }else{
          if( $('#pagetop').hasClass('show') ){
            $('#pagetop').removeClass('show');
          }
        }
      }
    }
    pagetopFunc();

    $('#pagetop').on('click', function() {
      $('body').velocity("scroll", { duration: 1000, easing: [0.19,1,0.22,1] });
      $(this).removeClass('show');
    });
  },

  //========== smoothScroll ==========//
  smoothScroll : function(){
    if (Browser.responsive === 'pc') {

      var urlHash = location.hash;

      if(urlHash) {

        $('body,html').stop().scrollTop(0);

        if( $('.slick-wrapper').length ){
          var slickLength = $('.slick-wrapper').length;
          $('.slick-wrapper').each(function(i){
            $(this).on('init', function(slick){

              if( slickLength-1 <= i ){
                setTimeout(function () {
                  scrollToAnker(urlHash) ;
                }, 500);
              }
            });
          });
        }else{
          scrollToAnker(urlHash) ;
        }

      }

      $('a[href^="#"]').click(function() {
        var href = $(this).attr("href");

        var hash = href === "#" || href === "" ? 'html' : href;

        scrollToAnker(hash);

        return false;
      });

      function scrollToAnker(hash) {
        var target = $(hash);
        target.velocity('stop').velocity("scroll", { duration: 1000, offset: -88, easing: [0.19,1,0.22,1] });
      }

    }else if (Browser.responsive === 'sp') {
      $('a[href^="#"]').click(function(){
        var href= $(this).attr("href");
        var target = $(href === "#" || href === "" ? 'html' : href);
        target.velocity('stop').velocity("scroll", { duration: 1000, offset: 0, easing: [0.19,1,0.22,1] });
        return false;
      });
    }
  },

  searchBox : function(){
    $('.search').on('click', function() {
      var self = $(this);
      var searchBody = self.find('.searchBody');
      self.addClass('show');
      searchBody.addClass('show');
      searchBody.find('input').focus();
      $(document).on('click touchend', function(event) {
        if (!$(event.target).closest('.search').length) {
          self.removeClass('show');
          searchBody.removeClass('show');
        }
      });
    });
    $('.search-home').click(function(){
      var self = $(this);
      var searchBody = self.find('.searchBody');
      self.addClass('show');
      searchBody.addClass('show');
      searchBody.find('input').focus();
      $(document).on('click touchend', function(event) {
        if (!$(event.target).closest('.search-home').length) {
          self.removeClass('show');
          searchBody.removeClass('show');
        }
      });
    });
  }
};

var TopPage = {
  first: false,
  init : function() {
    $('html,body').animate({ scrollTop: 0 }, '1');

    TopPage.news();
    TopPage.loading();
  },
  loading : function(){
    $('.top-mainvis-block').css({ 'height': $(window).height() });
    $('body').imagesLoaded( { background: '.ContentsKvMainInner' }, function() {
      setTimeout(function(){
        new ContentsSlider();
        new TwitterInfo();
        new StylingList();
        if (Browser.responsive === 'sp') {
          TopPage.intro();
          $.cookie('access', 'on', {
            expires: 365,
            path:'/'
          });
        }
      },800);
    });
  },
  intro : function(){
    if ($.cookie('access') !== 'on') {
      TopPage.first = true;
      var $intro = $('#Intro');
      $intro.show();
      var $finger = $intro.find('.finger');
      setTimeout(function(){
        $finger.addClass('swipe');
        $intro.find('p.swipe').addClass('show');
        $intro.find('p.swipe span').each(function(j) {
          var self = $(this);
          setTimeout(function(){
            self.addClass('show');
          },50*j);
        });
        setTimeout(function(){
          $intro.find('p.swipe span').each(function(j) {
            var self = $(this);
            setTimeout(function(){
              self.removeClass('show').addClass('hide');
            },50*j);
          });
        },1000);
      },500);
      setTimeout(function(){
        $finger.addClass('scroll');
        $intro.find('p.scroll').addClass('show');
        $intro.find('p.scroll span').each(function(j) {
          var self = $(this);
          setTimeout(function(){
            self.addClass('show');
          },50*j);
        });
        setTimeout(function(){
          $intro.find('p.scroll span').each(function(j) {
            var self = $(this);
            setTimeout(function(){
              self.removeClass('show').addClass('hide');
            },50*j);
          });
        },1000);
      },2400);
      setTimeout(function(){
        $intro.fadeOut(700);
      },4300);
    }
  },
  news : function(){
    $(".news-section .slick-wrapper").slick({
      slidesToShow: 4,
      slidesToScroll: 4,
      infinite: true,
      responsive: [
      {
        breakpoint: 1280,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
        }
      },
      {
        breakpoint: 940,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerMode: true,
          dots: true
        }
      }]
    });
  }
};




var SliderList = {
  init : function(){
    $('.list-slide .slick-wrapper').slick({
      infinite: false,
      arrows: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      variableWidth: true,
      dots: false
    });
  }
};


// var TitleHight = {
//   init : function(){
//     var h = $('#section-list ul li h3').outerHeight();
//     $('#section-list ul li .pic').css('margin-top', -h + 'px');
//   }
// };


var CategoryPage = {
  init : function(){
    $('body.category .section-content').imagesLoaded( function() {
      $('body.category .section-content li').each(function(i) {
        var y = $(this).offset().top;
        var self = $(this);
        // if( y < Browser.windowHeight - 100 ){
          setTimeout(function(){
            self.addClass('show');
            var $h2 = self.find('a h2');

            var line = $h2.html();

            $h2.text('');
            var newText = '';
            if(line){
                var lineArray = line.split("<br>");
                for (var i = 0; i < lineArray.length; i++) {
                  var text = lineArray[i];
                  if( i > 0 ){
                    newText += '<br>';
                  }
                  text.split('').forEach(function (c) {
                    if( c === ' ' ){
                      c = '\u00a0';
                      newText += c;
                    }else{
                      newText += '<span>'+c+'</span>';
                    }
                  });
                }
            }
            $h2.html(newText);
            $h2.find('span').each(function(j) {
              var self = $(this);
              setTimeout(function(){
                self.addClass('show');
              },50*j);
            });
          },150*i);
        // }
      });

      // Browser.addScrollHandler(function(){
      //   $('body.category .section-content li').each(function() {
      //     var y = $(this).offset().top - Browser.windowHeight*0.5;
      //     var self = $(this);
      //     if( Browser.scrollY > y ){
      //       if( !self.hasClass('show') ){
      //         self.addClass('show');
      //         var $h2 = self.find('a h2');
      //         var text = $h2.text();
      //         var newText = '';
      //         $h2.text('');
      //         text.split('').forEach(function (c) {
      //           if( c === ' ' ){
      //             c = '\u00a0';
      //             newText += '<br>';
      //           }else{
      //             newText += '<span>'+c+'</span>';
      //           }
      //           $h2.html(newText);
      //         });
      //         $h2.find('span').each(function(j) {
      //           var self = $(this);
      //           setTimeout(function(){
      //             self.addClass('show');
      //           },50*j);
      //         });
      //       }
      //     }
      //   });
      // });

    });
  }
};

var ProductPage = {
  init : function(){
    ProductPage.step();
    ProductPage.listSlide();
    ProductPage.heighReplace();
    $('.acc-body').imagesLoaded( function() {
      ProductPage.moreAcc();
    });
  },
  listSlide : function(){
    if( $('.list-slide ul:not(.list-short)').length ){
      var w = 0;
      $('.list-slide ul li').each(function(){
        w += $(this).outerWidth(true);
      });
      $('.list-slide ul').scrollLeft(w-Browser.windowWidth);
      $('.list-slide').on('touchstart', function() {
        $('.list-slide ul').stop();
      });
      Browser.addScrollHandler(function(){
        var t = $('.list-slide').offset().top - Browser.windowHeight*0.85;
        if( Browser.scrollY > t ){
          if( !$('.list-slide').hasClass('show') ){
            $('.list-slide').addClass('show');
            $('.list-slide ul').animate({scrollLeft:0},{duration:1200,easing:'easeOutQuad'});
          }
        }
      });
    }
  },
  step : function(){
    $(".tech-section .slick-wrapper, .style-section .slick-wrapper").slick({
      lazyLoad: 'progressive',
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: true,
      dots: true,
      adaptiveHeight: true
    });
  },
  heighReplace : function(){
    function argHeight(){

      var setPrt =  $('.detail-list'),
      setChd = setPrt.find('li .detail-set .detail-section');
      var setPrt2 =  $('.detail-list'),
      setChd2 =  setPrt2.find('li .box');

      prtWidth = setPrt.outerWidth();
      prtWidth2 = setPrt2.outerWidth();
      chdWidth = setChd.outerWidth();
      chdWidth2 = setChd2.outerWidth();
      setNum = Math.floor(prtWidth / chdWidth);
      setNum2 = Math.floor(prtWidth2 / chdWidth2);
      chdLength = setChd.length;
      chdLength2 = setChd2.length;
      setChd.css({height:'auto'});
      setChd2.css({height:'auto'});

      setPrt.each(function(){
        var h = 0;
        setChd.each(function(i){
          var self = $(this),
          i = i+1,
          hSet = self.outerHeight(),
          pdTop = parseInt(self.css('padding-top')),
          pdBtm = parseInt(self.css('padding-bottom')),
          boxSizing = self.css('box-sizing');
          self.addClass('heightReplace');

          if(hSet > h){
            h = hSet;
          };

          if(boxSizing === 'border-box'){
            setPrt.find('.heightReplace').css({height:h});
          } else {
            setPrt.find('.heightReplace').css({height:(h-(pdTop + pdBtm))});
          }

          if(i%setNum == 0 || i == chdLength){
            h = 0;
            setChd.removeClass('heightReplace');
          }
        });
      });

    setPrt2.each(function(){
        var h = 0;
        setChd2.each(function(i){
          var self = $(this),
          i = i+1,
          hSet = self.outerHeight(),
          pdTop = parseInt(self.css('padding-top')),
          pdBtm = parseInt(self.css('padding-bottom')),
          boxSizing = self.css('box-sizing');
          self.addClass('heightReplace');

          if(hSet > h){
            h = hSet;
          };

          if(boxSizing === 'border-box'){
            setPrt2.find('.heightReplace').css({height:h});
          } else {
            setPrt2.find('.heightReplace').css({height:(h-(pdTop + pdBtm))});
          }

          if(i%setNum2 == 0 || i == chdLength2){
            h = 0;
            setChd2.removeClass('heightReplace');
          }
        });
      });

        }
        $(window).on('load resize',function(){
            argHeight();
        }).resize();
  },
  moreAcc : function(){
    if (Browser.responsive === 'pc') {
      // PC
      $(".acc-body").show();

      $("#product-different .acc-body, .shop-block .acc-body").wrap("<div class='acc-body-wrapper'></div>").show();
      $(".acc-body-wrapper").each(function(){
        $(this).attr('data-height', $(this).innerHeight()).hide();
      });

      $("#product-different .acc-btn, .shop-btn").on("click", function() {
        var $acc_body = $(this).parent().find(".acc-body-wrapper");
        var h = $acc_body.attr('data-height') + 'px';
        if( !$(this).hasClass('active') ){
          $(this).addClass('active');
          $acc_body.css({ 'display': 'block', 'height': 0, 'overflow': 'hidden' });
          $acc_body.velocity('stop').velocity({ 'height': h }, { duration:500, easing: 'ease-out'});
        }else{
          $(this).removeClass('active');
          $acc_body.velocity('stop').velocity({ 'height': 0 }, { duration:500, easing: 'ease-out',
            complete:function(){
              $(this).css({ 'height': 'auto', 'display': 'none' });
            }
          });
        }
        return false;
      });

    }else if (Browser.responsive === 'sp' || Browser.responsive === 'tb') {
      // SP
      $(".acc-body").wrap("<div class='acc-body-wrapper'></div>").show();
      $(".acc-body-wrapper").each(function(){
        $(this).attr('data-height', $(this).innerHeight()).hide();
      });
      $(".acc-btn, .shop-btn").on("click", function() {
        var $acc_body = $(this).parent().find(".acc-body-wrapper");
        var h = $acc_body.attr('data-height') + 'px';
        if( !$(this).hasClass('active') ){
          $(this).addClass('active');
          $acc_body.css({ 'display': 'block', 'height': 0, 'overflow': 'hidden' });
          $acc_body.velocity('stop').velocity({ 'height': h }, { duration:500, easing: 'ease-out'});
        }else{
          $(this).removeClass('active');
          $acc_body.velocity('stop').velocity({ 'height': 0 }, { duration:500, easing: 'ease-out', complete:function(){ $(this).css({ 'height': 'auto', 'display': 'none' }); }});
        }
        // $acc_body.stop(true, false).slideToggle();
        // $(this).toggleClass("active");
        return false;
      });
    }
  }
};

var StylingPage = {
  init : function(){
    new StylingList();
    $('#StylingListWrapper').slick({
      arrows : false,
      dots : false,
      draggable : false,
      swipe : false
    });
  }
};








//===============================================
//
// ContentsSlider
//
//===============================================
ContentsSlider = function () {
  var self = this;
  self.$dispatch = $('<div>');
  self.$ContentsKvWrapper = $('#ContentsKvWrapper');
  self.$ContentsKvInner = $('#ContentsKvInner');
  self.$ContentsBodyWrapper = $('#ContentsBodyWrapper');
  self.$ContentsBodyInner = $('#ContentsBodyInner');
  self.$ContentsBtns = $('#ContentsBtns');
  self.$ContentsBtnsInner = $('#ContentsBtnsInner');

  if( self.$ContentsBodyWrapper.length === 0 ) return;

  self.currentIndex = self.getHashIndex();
  self.sid = 0;
  self.lock = false;
  self.scrollLock = false;
  self.length = self.$ContentsKvWrapper.find('.ContentsKv').length;
  self.btnIndex = 0;
  self.reload = false;
  self.loaded = false;
  self.first = false;

  $('#ContentsBodyWrapper').imagesLoaded( function() {
    self.init();
  });

};
ContentsSlider.TIME = 7000;
ContentsSlider.prototype = {
  constructor: ContentsSlider,

  init : function(){
    var self = this;

    //slick Kv
    self.$ContentsKvInner.slick({
      infinite: true,
      arrows : false,
      dots : false,
      draggable : false,
      swipe : true,
      asNavFor : self.$ContentsBtnsInner
    });


    //slick Body
    self.$ContentsBodyInner.slick({
      infinite: true,
      arrows : false,
      dots : false,
      draggable : false,
      swipe : false,
      adaptiveHeight : true,
      fade: true
    });
    // self.$ContentsBodyInner.slick('slickGoTo',self.currentIndex,true);

    //slick Btn
    self.$ContentsBtnsInner.slick({
      infinite: true,
      // centerMode: true,
      // centerPadding: '15px',
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows : false,
      dots : false,
      asNavFor : self.$ContentsKvInner,
      focusOnSelect: true
    });

    // self.btnIndex = self.currentIndex+1;
    // if( self.btnIndex > self.length ){
    //  self.btnIndex = 0;
    // }
    // self.$ContentsBtnsInner.slick('slickGoTo',self.btnIndex,true);

    self.$ContentsBodyWrapper.find('.ContentsBodyTitles').show();

    self.$ContentsKvInner.slick('slickGoTo',self.currentIndex,true);
    // self.setBodyHeight();
    self.$ContentsKvInner.on('swipe',$.proxy(self.swipeHandler,self));
    self.$ContentsKvInner.on('afterChange',$.proxy(self.afterChangeHandler,self));

    self.$ContentsKvInner.find('.ContentsKv').on({
      'mouseenter' : function(){
        $(this).addClass('on');
      },
      'mouseleave' : function(){
        $(this).removeClass('on');
      }
    });


    self.$ContentsBtnsInner.on('swipe',$.proxy(self.swipeHandler,self));
    self.$ContentsBtnsInner.on('afterChange',$.proxy(self.afterChangeHandler,self));

    // self.$ContentsBtnsInner.find('.off span').each(function(){
    //   var chaffle = new Chaffle(this);
    //   $(this).on({
    //     'mouseenter' : function(){
    //       chaffle.init();
    //     }
    //   });
    // });





    $('.top-mainvis-block').css({ 'height': 'auto' });

    $('.ContentsBodyTitles p').each(function(){
      var chaffle = new Chaffle(this);
       $(window).on({
        'chaffleTitles' : function(){
          chaffle.init();
        }
      });
    });


    $('#loading').velocity({ 'opacity': 0, 'scale': 0 }, { duration:300, easing: 'ease-out', complete:function(){
      $(this).remove();
      var delay = 0;
      if( TopPage.first ){
        delay = 4000;
      }
      setTimeout(function(){
        self.start();
      },delay);
    }});

  },

  start : function(){
    var self = this;

    $('#TwitterInfo').addClass('initialized');

    self.$ContentsKvInner.find('.ContentsKv').addClass('loaded');

    setTimeout(function(){
      self.$ContentsKvInner.find('.slick-current .ContentsKvInfo').addClass('show');
      self.$ContentsKvInner.find('.slick-current .ContentsKvMain').addClass('show');

      self.responsiveHandler();
      self.resizeHandler();
      self.scrollHandler();

    },50);

    setTimeout(function(){
     self.$ContentsBtnsInner.find('.btnInner').each(function(i) {
      var $btnInner = $(this);
      setTimeout(function(){
        $btnInner.addClass('loaded');
      },100*i);
    });
    },600);

    setTimeout(function(){
      self.loaded = true;
      self.setTimer();
      self.btnMotion();
      self.slide('',0);
      self.$ContentsBtns.find('.btn').on('click',$.proxy(self.btnClickHandler,self));
      self.responsiveHandler();
      self.resizeHandler();
      self.scrollHandler();
    },900);

    Browser.addResponsivePCSPHandler(self.responsiveHandler,self);
    self.responsiveHandler();
    Browser.addResizeHandler(self.resizeHandler , self);
    self.resizeHandler();
    Browser.addScrollHandler(self.scrollHandler,self);
    self.scrollHandler();

  },

  responsiveHandler : function(){
    var self = this;

    if( self.loaded ){
      self.$ContentsBtns.find('.btn').off();
      self.resetTimer();
      self.btnMotionStop();
    }

    var $btn = self.$ContentsBtns.find('.btn');

    if(Browser.responsivePCSP === 'sp'){
      // self.$ContentsBtnsInner.find('.btn').each(function(){
      //  var $btn = $(this).clone();
      //  $btn.addClass('clone');
      //  self.$ContentsBtnsInner.append($btn);
      // });
      // var w = (((230/750*100) * self.$ContentsBtns.find('.btn').length) + 0) + 'vw';
      // self.$ContentsBtnsInner.width(w);
      // self.$ContentsBtnsInner.not('.slick-initialized').slick();
      // self.$ContentsBtnsInner.not('.slick-initialized').slick({
      //  infinite: true,
      //  slidesToShow: 3,
      //  slidesToScroll: 1,
      //  arrows : false,
      //  dots : false,
      //  asNavFor : self.$ContentsKvInner,
      //  focusOnSelect: true
      // });
      $(document).off('mouseenter, mouseleave', $btn);
      if(self.reload){
        window.location.reload(false);
      }
      // velocity-animating
      // self.$ContentsBtnsInner.slick('slickGoTo',self.currentIndex);
    } else {

      // self.$ContentsBtns.find('.btn').on({
      //   'mouseenter' : function(){
      //     $(this).addClass('hover');
      //   },
      //   'mouseleave' : function(){
      //     $(this).removeClass('hover');
      //   }
      // });
      var h = self.$ContentsBodyWrapper.find('.ContentsBodyTitles .tit-vertical p').width();
      self.$ContentsBodyWrapper.find('.ContentsBodyTitles .tit-vertical').height(h);

      // self.$ContentsBtnsInner.removeAttr('style');
      // self.$ContentsBtnsInner.find('.btn.clone').remove();
      self.$ContentsBtnsInner.slick('unslick');
      self.reload = true;
    }

    //loaded
    if( self.loaded ){
      self.$ContentsBtns.find('.btn').on('click',$.proxy(self.btnClickHandler,self));
      self.slide(null,self.currentIndex);
      self.setTimer();
      self.btnMotion();
    }
    self.setBodyHeight();
  },

  resizeHandler : function() {
    var self = this;
    self.responsivePCSP = '';

    if(Browser.responsivePCSP === 'sp'){
      self.responsivePCSP = 'sp';
      var top = $('#Kv').offset().top;
      // $('#Kv').height( Browser.windowHeight -top); old
      // $('#Kv').height( Browser.windowHeight + top);
      // console.log(Browser.windowHeight + top);
      var kvH = $('#Kv').height();
      var marginBottom = Browser.windowHeight - $('#ContentsBtns').offset().top + 20;
      var imgInnerH = $('.bg').height();

      //$('#Kv .ContentsKvMain').height( kvH + marginBottom); old
      // $('#Kv .ContentsKvMain').height( kvH + marginBottom);
      var picHeight = $('#Kv .ContentsKvMain').height();
      // $('#ContentsBtns').css( { 'top': picHeight+50});
      // $('#Kv ').height(imgInnerH+120);
      //$('#Kv').height(kvH-marginBottom );

      // console.log(imgInnerH+120);
    }else{
      if( self.responsivePCSP !== 'pc' ){

      }
      self.responsivePCSP = 'pc';
      $('#Kv').css({ 'height': 'auto' });
      $('#Kv .ContentsKvMain').css({ 'height': 'auto' });

    }
    var btnW = self.$ContentsBtnsInner.find('.btn').innerWidth() - 4;
    self.$ContentsBtnsInner.find('.btnInner span').width( btnW );

    self.setBodyHeight();
  },

  scrollHandler : function(){
    var self = this;
    var btnH = self.$ContentsBtns.height();
    var btnY = self.$ContentsBtnsInner.offset().top;
    var contentsY = self.$ContentsBodyInner.offset().top;
    var wH = Browser.windowHeight;
    var sY = Browser.scrollY;
    var limitY = self.$ContentsKvWrapper.height() + self.$ContentsKvWrapper.offset().top;
    var offsetY = 100;
    //console.log(btnY >= wH+sY , btnY , wH+sY);
    //console.log(btnY >= sY-btnH , btnY , sY-btnH);
    //console.log(sY+wH,limitY);

    if(sY+wH-offsetY > limitY){
      if(!self.scrollLock){
        self.resetTimer();
        self.btnMotionStop();
      }
      self.scrollLock = true;
    } else {
      if(self.scrollLock){
        self.setTimer();
        self.btnMotion();
      }
      self.scrollLock = false;
    }

    var count = 0;
    if( sY > contentsY-wH+300 ){
      if( !self.$ContentsBodyInner.hasClass('show') ){
        self.$ContentsBodyInner.addClass('show');
        if( self.currentIndex === 2 ){
          $('#StylingListInner .StylingListItem').each(function(){
            var x = $(this).offset().left;
            var w = $(this).width()*2;
            if( x > -w && x < Browser.windowWidth  ){
              // $(this).css({ transform: 'scale(0)' });
              // $(this).velocity({ scale: 1.0 }, {duration:500,easing:[0.175, 0.885, 0.32,1.275], delay: 100*count});
              // StylingList.pc_isLoop = true;
              count++;
            }
          });
        }
      }
    }else{
      if( self.$ContentsBodyInner.hasClass('show') ){
        self.$ContentsBodyInner.removeClass('show');
      }
    }

/*
    if(btnY <= wH+sY && btnY >= sY-btnH){
      if(!self.scrollLock){
        self.setTimer();
        self.btnMotion();
      }
      self.scrollLock = true;
    } else {
      if(self.scrollLock){
        self.resetTimer();
      }
      self.scrollLock = false;
    }
*/

  },

  slide : function(direction,gotoIndex){
    var self = this;
    var currentSlide = self.$ContentsKvInner.slick('slickCurrentSlide');
    var h = 0;

    if(direction === 'left'){
      self.$ContentsBodyInner.slick('slickNext');
      // self.$ContentsBodyInner.find('.ContentsBody').hide();
      // self.$ContentsBodyInner.find('.ContentsBody').eq(currentSlide).css({ 'display': 'block' });
      // h = self.$ContentsBodyInner.find('.ContentsBody').eq(currentSlide).height();
      self.lock = true;
    } else if(direction === 'right'){
      self.$ContentsBodyInner.slick('slickPrev');
      // self.$ContentsBodyInner.find('.ContentsBody').hide();
      // self.$ContentsBodyInner.find('.ContentsBody').eq(currentSlide).css({ 'display': 'block' });
      // h = self.$ContentsBodyInner.find('.ContentsBody').eq(currentSlide).height();
      self.lock = true;
    }
    if(gotoIndex >= 0){
      self.$ContentsKvInner.slick('slickGoTo',gotoIndex);
      self.$ContentsBodyInner.slick('slickGoTo',gotoIndex);
      // self.$ContentsBodyInner.find('.ContentsBtnsntsBody').hide();
      // self.$ContentsBodyInner.find('.ContentsBody').eq(gotoIndex).css({ 'display': 'block' });
      // h = self.$ContentsBodyInner.find('.ContentsBody').eq(gotoIndex).height();
      self.$ContentsBtnsInner.slick('slickGoTo',gotoIndex);
      self.currentIndex = gotoIndex;
      self.lock = true;
    } else{
      self.currentIndex = currentSlide;
    }



    self.$ContentsBodyWrapper.find('.ContentsBodyTitles .tit-vertical').each(function(i) {
      var index = self.currentIndex;
      if( index === i ){
        $(this).show();
        var h = $(this).find('p').width();
        $(this).parent().height(h);
        $(window).trigger('chaffleTitles');
      }else{
        $(this).hide();
      }
    });

    // self.$ContentsBodyInner.height(h);
    // self.lock = true;

    self.$ContentsBtns.find('.btn').removeClass('currentBtn');
    self.$ContentsBtns.find('.btn').eq(self.currentIndex).addClass('currentBtn');

    if(!self.scrollLock){
      self.setTimer();
    }
    self.setBodyHeight();
    self.showMotion();
    self.btnMotion(direction);
  },

  setBodyHeight : function(){
    var self = this;
    // if( self.currentIndex === 2 ){
    //   self.$ContentsBodyInner.css({ 'cssText': 'overflow: visible !important;' });
    //   self.$ContentsBodyInner.find('.slick-list').css({ 'cssText': 'overflow: visible !important;' });
    // }else{
    //   self.$ContentsBodyInner.css({ 'cssText': 'overflow: hidden !important;' });
    //   self.$ContentsBodyInner.find('.slick-list').css({ 'cssText': 'overflow: hidden !important;' });
    // }
    var h = self.$ContentsBodyInner.find('.slick-current').outerHeight();
    self.$ContentsBodyInner.height(h);
  },

  showMotion : function(){
    var self = this;

    self.$ContentsKvInner.find('.ContentsKv .ContentsKvMain').removeClass('show');
    self.$ContentsKvInner.find('.slick-current .ContentsKvMain').addClass('show');

    self.$ContentsKvInner.find('.ContentsKv .ContentsKvInfo').removeClass('show');
    self.$ContentsKvInner.find('.slick-current .ContentsKvInfo').addClass('show');

  },

  swipeHandler : function(event, slick, direction){
    var self = this;
    self.slide(direction,-1);
  },

  afterChangeHandler : function(event, slick, direction){
    var self = this;
    self.lock = false;
  },

  setTimer : function(){
    var self = this;
    clearTimeout(self.sid);
    self.sid = setTimeout(function(){
      if(!self.scrollLock){
        self.$ContentsKvInner.slick('slickNext');
        self.slide('left',-1);
      }
    }, ContentsSlider.TIME);
  },

  resetTimer : function(){
    var self = this;
    clearTimeout(self.sid);
  },

  btnClickHandler : function(e){
    var self = this;
    if(self.lock) return;
    var index = $(e.currentTarget).data('index');
    if(self.currentIndex === index) return;
    self.slide(null,index);
  },

  btnMotion : function(direction){
    var self = this;
    var currentSlide = self.$ContentsKvInner.slick('slickCurrentSlide');
    var $currentBtn = $('#ContentsBtns .btn[data-index="'+currentSlide+'"]');
    var $on = $currentBtn.find('.on');
    var $span = $currentBtn.find('.on span');

    self.btnIndex = self.currentIndex+1;
    if( self.btnIndex > self.length ){
      self.btnIndex = 0;
    }

    self.$ContentsBtnsInner.find('.on').removeClass('velocity-animating');

    $span.velocity('stop').css({left:'0',right:'auto'});
    $on.velocity('stop').css({left:'0',right:'auto',width:'0%'});
    $on.velocity({'width':'101%'},{duration:1000, easing: [0.19,1,0.22,1], complete:function(){
      if(!self.scrollLock){
        $on.css({left:'auto',right:'0'}).velocity({width:'0%'},{duration:ContentsSlider.TIME-1000,easing:'linear'});
        $span.css({left:'auto',right:'0'});
      }
    }});

    self.$ContentsBtns.find('.btn').each(function(){
      var indx = $(this).data('index');
      var $on = $('#ContentsBtns .btn[data-index="'+indx+'"]').find('.on');
      var $span = $('#ContentsBtns .btn[data-index="'+indx+'"]').find('.on span');
      if(indx !== currentSlide){
        $on.velocity('stop').css({left:'0',right:'auto'}).velocity({width:'0%'},{duration:500, easing: [0.19,1,0.22,1]});
        $span.velocity('stop').css({left:'0',right:'auto'});
      }
    });
    if(Browser.responsivePCSP === 'sp'){
      if(direction === 'left'){
        // self.$ContentsBtnsInner.slick('slickNext');
      } else if(direction === 'right'){
        // self.$ContentsBtnsInner.slick('slickPrev');
      }
      // self.$ContentsBtnsInner.slick('slickGoTo',self.btnIndex);
    }
  },

  btnMotionStop : function(){
    var self = this;
    var currentSlide = self.$ContentsKvInner.slick('slickCurrentSlide');
    var $currentBtn = $('#ContentsBtns .btn[data-index="'+currentSlide+'"]');
    var $on = $currentBtn.find('.on');
    var $span = $currentBtn.find('.on span');

    $on.velocity('stop').velocity({width:'100%'},{duration:500, easing: [0.19,1,0.22,1], complete: function(){
      $on.css({left:'0',right:'auto'});
      $span.velocity('stop').css({left:'0',right:'auto'});
    }});

    self.$ContentsBtns.find('.btn').each(function(i){
      var indx = $(this).data('index');
      var $on = $('#ContentsBtns .btn[data-index="'+indx+'"]').find('.on');
      var $span = $('#ContentsBtns .btn[data-index="'+indx+'"]').find('.on span');
      if(indx !== currentSlide){
        $on.velocity('stop').css({left:'0',right:'auto'}).velocity({width:'0%'},{duration:10});
        $span.velocity('stop').css({left:'0',right:'auto'});
      }
    });
  },

  getHashIndex : function(){
    var self = this;
    var hash = window.location.hash.replace('#','');
    if(!hash) return 0;

    var idx = 0;
    self.$ContentsBtns.find('.btn').each(function(){
      if($(this).data('id') === hash){
        idx =  $(this).data('index');
      }
    });

    return idx;
  },
};



//===============================================
//
// StylingList
//
//===============================================
StylingList = function () {
  var self = this;
  self.$dispatch = $('<div>');
  self.$StylingList = $('#StylingList');
  self.$StylingListWrapper = $('#StylingListWrapper');
  self.$StylingListInner = $('#StylingListInner');
  self.$StylingListArrowLeft = $('#StylingListArrowLeft');
  self.$StylingListArrowRight = $('#StylingListArrowRight');
  //self.$StylingListDots = $('#StylingListDots');

  if( self.$StylingList.length === 0 ) return;

  self.a = [];
  self.touchstart_func = $.proxy( self.sp_touchstart , self );
  self.touchmove_func = $.proxy( self.sp_touchmove , self );
  self.touchend_func = $.proxy( self.sp_touchend , self );

  self.$StylingListInnerGroup;
  self.$StylingListInnerGroupLeft;
  self.$StylingListInnerGroupCenter;
  self.$StylingListInnerGroupRight;

  self.allItem = [];
  self.allItemLength = 0;

  $.getJSON("/js/json/top-styling.json", function(data){
    for(var i in data){
      self.allItem.push( data[i] );
    }
    self.shuffle(self.allItem);
    self.allItemLength = self.allItem.length;
    self.init();
    Browser.addResponsivePCSPHandler(self.responsiveHandler,self);
    self.responsiveHandler();
  });
};
StylingList.prototype = {
  constructor: StylingList,

  init : function(){
    var self = this;
  },

  shuffle : function(array){
    var self = this;
    var m = array.length, t, i;
    while (m) {
      i = Math.floor(Math.random() * m--);
      t = array[m];
      array[m] = array[i];
      array[i] = t;
    }
    return array;
  },

  responsiveHandler : function(){
    var self = this;

    self.$StylingListInner.empty();
    //self.$StylingListDots.empty();
    self.$StylingListArrowLeft.off();
    self.$StylingListArrowRight.off();
    self.$StylingListInner.removeAttr('style');
    self.pc_isLoop = false;
    self.pc_groupWidth = 0;
    self.pc_x = 0;
    self.pc_move = -1;
    self.sp_movementX = 0;
    self.$StylingListInner[0].removeEventListener( 'touchstart', self.touchstart_func);
    self.itemWidth = 320;
    self.displayNumPC = 0;
    self.displayNumSP = 5;
    self.displayStart = 0;
    self.displayEnd = 0;

    if(Browser.responsivePCSP === 'sp'){
      self.sp_init();
      self.$StylingListInner[0].addEventListener( 'touchstart', self.touchstart_func , false );
      self.$StylingListArrowLeft.on('click',function(){
        self.sp_movemotion('left');
      });
      self.$StylingListArrowRight.on('click',function(){
        self.sp_movemotion('right');
      });
    } else {
      self.pc_init();
      self.$StylingListArrowLeft.on('click',function(){
        self.pc_move = -30;
      });
      self.$StylingListArrowRight.on('click',function(){
        self.pc_move = 35;
      });
    }
  },

  pc_init : function(){
    var self = this;
    var pos = ['left','center','right'];
    self.displayNumPC = Math.ceil($(window).width() / self.itemWidth) + 2;
    self.displayStart = 0;
    self.displayEnd = self.displayNumPC;
    self.$StylingListInner.append('<div class="StylingListInnerGroup"></div>');
    self.$StylingListInnerGroup = self.$StylingListInner.find('.StylingListInnerGroup');
    for(var v=0; v<self.displayNumPC; v++){
      var url = self.allItem[v].url;
      var thumb = self.allItem[v].thumb;
      var item = '<a href="'+ url +'" class="StylingListItem"><img src="'+ thumb +'"></a>';
      self.$StylingListInnerGroup.append(item);
    }
    self.$StylingListInnerGroup.find('a').each(function(i){
      var pos = self.itemWidth*i;
      $(this).css({
        'position': 'absolute',
        'transform': 'translateX('+ pos +'px)'
      });
      $(this).attr('data-pos',pos);
      $(this).attr('data-num',i);
      $(this).find('img').one('load', function(){
        $(this).parent().addClass('loaded');
      });
    });

    self.pc_groupWidth = self.$StylingListInnerGroup.width();

    // self.$StylingListInner.find('.StylingListItem').each(function() {
    //   $(this).addClass('hide');
    // });

    self.pc_isLoop = true;
    window.requestAnimationFrame($.proxy(self.pc_update,self));
  },

  pc_update : function(){
    var self = this;
    self.pc_move += (-1 - self.pc_move) * 0.1;
    self.pc_x += self.pc_move;
    // if(self.pc_x < -self.itemWidth*2){
    //   self.pc_x = 0;
    // } else if(self.pc_x > self.pc_groupLeftWidth+self.itemWidth*2){
    //   self.pc_x = 0;
    // }
    // self.$StylingListInner.css('transform', 'translateX(' + self.pc_x + 'px)');
    self.$StylingListInnerGroup.find('.StylingListItem').each(function() {

      var $this = $(this);
      var x = Number($this.attr('data-pos'));
      var pos = x + self.pc_move;

      $this.css('transform', 'translateX(' + pos + 'px)').attr('data-pos', pos);
      if( x > -self.itemWidth && x < Browser.windowWidth+self.itemWidth  ){
        $this.removeClass('hide');
        $this.addClass('show');
      }
      if( x < -self.itemWidth ){
        // if( !$this.hasClass('hide') ){
          $this.addClass('hide');
          $this.removeClass('show, loaded');

          var endX = Number(self.$StylingListInnerGroup.find('.StylingListItem').eq(self.displayNumPC-1).attr('data-pos'));
          $this.css('transform', 'translateX('+ endX + self.itemWidth +'px)').attr('data-pos', endX + self.itemWidth);

          var endNum = self.$StylingListInnerGroup.find('.StylingListItem').eq(self.displayNumPC-1).attr('data-num');
          var addNum = Number(endNum)+1;
          if( addNum >= self.allItemLength ){
            addNum = 0;
          }
          var url = self.allItem[addNum].url;
          var thumb = self.allItem[addNum].thumb;
          $this.attr('data-num', addNum);

          $this.attr('href', url);
          $this.find('img').attr('src', '');
          $this.find('img').attr('src', thumb);
          $this.find('img').one('load', function(){
            $(this).parent().addClass('loaded');
          });
          self.$StylingListInnerGroup.append($this);
        // }
      }else if( x > Browser.windowWidth+self.itemWidth ){
        if( self.pc_move > -1 ){
           $this.addClass('hide');
           $this.removeClass('show, loaded');

          var startX = Number(self.$StylingListInnerGroup.find('.StylingListItem').eq(0).attr('data-pos'));
          $this.css('transform', 'translateX('+ startX - self.itemWidth +'px)').attr('data-pos', startX - self.itemWidth);

          var startNum = self.$StylingListInnerGroup.find('.StylingListItem').eq(0).attr('data-num');
          var addNum = Number(startNum)-1;
          if( addNum < 0 ){
            addNum = self.allItemLength-1;
          }
          var url = self.allItem[addNum].url;
          var thumb = self.allItem[addNum].thumb;
          $this.attr('data-num', addNum);

          $this.attr('href', url);
          $this.find('img').attr('src', '');
          $this.find('img').attr('src', thumb);
          $this.find('img').one('load', function(){
            $(this).parent().addClass('loaded');
          });
          self.$StylingListInnerGroup.prepend($this);
        }
      }
    });

    if(self.pc_isLoop){
      window.requestAnimationFrame($.proxy(self.pc_update,self));
    }

  },

  sp_init : function(){
    var self = this;
    self.displayEnd = self.displayNumSP;
    for(var i=0;i<self.displayNumSP;i++){
      var url = self.allItem[i].url;
      var thumb = self.allItem[i].thumb;
      var item = '<a href="'+ url +'" class="StylingListItem" data-index="'+i+'"><img src="'+ thumb +'"></a>';
      self.$StylingListInner.append(item);
    }
    self.$StylingListInner.find('a').each(function(i){
      $(this).find('img').css({left:Math.floor(Math.random()*50-25),top:Math.floor(Math.random()*50-25),transform:'scale('+ (1-(self.displayNumSP-1)*0.05+0.05*i) +')'});
    });


    $('body').append('<div id="log"></div>');
    $('#log').css({ 'position': 'fixed', 'top': 0, 'left': 0, 'z-index': 99999 });
  },

  sp_touchstart : function(e){
    var self = this;

    window.addEventListener( 'touchmove', self.touchmove_func , false );
    window.addEventListener( 'touchend', self.touchend_func , false );

    self.startlayerX = self.getLayerX(e);
    self.startlayerY = self.getLayerY(e);
    self.no_scroll();

    var $a = self.$StylingListInner.find('a').eq(self.$StylingListInner.find('a').length-1);
    $a.velocity('stop').velocity({ scale: 1.05 }, {duration:300,easing:[0.175, 0.885, 0.32,1.275]});

  },

  sp_touchmove : function(e){
    var self = this;
    var layerX = self.getLayerX(e);
    var layerY = self.getLayerY(e);
    self.sp_movementX = (layerX - self.startlayerX);
    self.sp_movementY = (layerY - self.startlayerY);
    var $a = self.$StylingListInner.find('a').eq(self.$StylingListInner.find('a').length-1);
    $a.css({left:self.sp_movementX,top:self.sp_movementY,});
    $a.find('img').css({transform:'rotate('+self.sp_movementX*0.10+'deg)'});
  },

  sp_touchend : function(e){
    var self = this;
    window.removeEventListener( 'touchmove', self.touchmove_func);
    window.removeEventListener( 'touchend', self.touchend_func);

    var limit = 120;
    var rl = '';
    var tb = '';
    if(self.sp_movementX > limit){
      rl = 'right';
    } else if(self.sp_movementX < -limit){
      rl = 'left';
    }
    if(self.sp_movementY > limit){
      tb = 'top';
    } else if(self.sp_movementY < -limit){
      tb = 'bottom';
    }

    if(self.sp_movementX > limit){
      self.sp_movemotion(rl,tb);
    } else if(self.sp_movementX < -limit){
      self.sp_movemotion(rl,tb);
    } else {
      self.sp_movemotion('');
    }

    self.return_scroll();

  },

  sp_movemotion : function(rl,tb){
    var self = this;

    var $a = self.$StylingListInner.find('a').eq(self.$StylingListInner.find('a').length-1);

    var top = 0;
    var left = 0;
    if( tb === 'top' ){
      top = $a.position().top + self.sp_movementY*2;
    } else if( tb === 'bottom' ){
      top = $a.position().top + self.sp_movementY*2;
    }

    if( rl === 'right' ){
      left = Browser.windowWidth;
    } else if( rl === 'left' ){
      left = -Browser.windowWidth;
    }
    if( left === 0 ){
      $a.velocity('stop').velocity({left:0,top:0,scale:1},{duration:300,easing:[0.175, 0.885, 0.32,  1.275]});
    }else{
      $a.velocity('stop').velocity({left:left,top:top},{duration:300,complete:function(){
        self.displayEnd += 1;
        if( self.displayEnd > self.allItemLength-1 ){
          self.displayEnd = 0;
        }

        var $img = $a.find('img');

        $a.removeAttr('style');
        $img.removeAttr('style');
        $img.css({left:Math.floor(Math.random()*20-10),top:Math.floor(Math.random()*50-25)});
        var link = self.allItem[self.displayEnd].url;
        var thumb = self.allItem[self.displayEnd].thumb;
        $a.attr('href', link);
        $img.attr('src', '');
        $img.attr('src', thumb);
        $img.css({ opacity:0,transform:'scale(0.8)' });

        self.$StylingListInner.prepend($a);
        self.$StylingListInner.find('.StylingListItem').each(function(i) {
          $(this).find('img').velocity({opacity:1,scale: 1-(self.displayNumSP-1)*0.05+0.05*i },{duration:300,easing:[0.175, 0.885, 0.32,1.275],delay: 75*(self.displayNumSP-1)-100*i});
        });

      }});
    }
  },

  sp_dots_update : function(){
    var self = this;

    var $a = self.$StylingListInner.find('a').eq(self.$StylingListInner.find('a').length-1);
    var index = $a.data('reverseindex');

    self.$StylingListDots.find('.StylingListDot').each(function(i){
      if(i === index){
        $(this).addClass('current');
      } else{
        $(this).removeClass('current');
      }
    });
  },

  getLayerX : function(e){
    var self = this;
    if(e.type === 'touchstart' || e.type === 'touchmove'){
      //console.log(e.touches[0].pageX ,self.$scrollbox.offset().left );
      return e.touches[0].pageX - self.$StylingListInner.offset().left;
    } else {
      return e.layerX;
    }
  },

  getLayerY : function(e){
    var self = this;
    if(e.type === 'touchstart' || e.type === 'touchmove'){
      //console.log(e.touches[0].pageX ,self.$scrollbox.offset().left );
      return e.touches[0].pageY - self.$StylingListInner.offset().top;
    } else {
      return e.layerY;
    }
  },

  preventDefault : function(event){


    event.preventDefault();
  },

  no_scroll : function(){
    var self = this;
    //$(document).on(scroll_event,function(e){e.preventDefault();});
    // $(document).on('touchmove.noScroll', { passive: false }, function(e) {e.preventDefault();});
    document.addEventListener( 'touchmove' , self.preventDefault , { passive: false } );
  },

  return_scroll : function(){
    var self = this;
    //$(document).off(scroll_event);
    // $(document).off('.noScroll');
    document.removeEventListener( 'touchmove' , self.preventDefault , { passive: false } );
  }

};




//===============================================
//
// TwitterInfo
//
//===============================================
TwitterInfo = function () {
  var self = this;

  self.$TwitterInfo = $('#TwitterInfo');
  if( self.$TwitterInfo.length === 0) return;

  self.$list = self.$TwitterInfo.find('.list');

  self.data;
  self.sId;
  self.currentIndex = 0;
  self.init();

};
TwitterInfo.URL = '/localdata/twitter/twitter.json';
TwitterInfo.API = 'api/get/twitter.html';
TwitterInfo.TIMER_DURATION = 5000;
TwitterInfo.MOVE_DURATION = 1000;
TwitterInfo.prototype = {
  constructor: TwitterInfo,
  init : function() {
    var self = this;
    self.load();
  },

  load : function() {
    var self = this;
    $.ajax( {
        url: TwitterInfo.URL,
        dataType : 'json',
        success: function( data ) {
            self.data = data;
            self.initHtml();
            //console.log("DATA_LOAD_SUCCESS : " + url);
        },
        error: function( data ) {
            //console.log("DATA_LOAD_FAIL : " + url);
        }
    } );

    $.ajax( {
        url: TwitterInfo.API,
        dataType : 'json',
        success: function( data ) {
        },
        error: function( data ) {
        }
    } );
  },

  initHtml : function() {
    var self = this;
    var $txtNew = self.createTxt( self.currentIndex , self.data[ self.currentIndex ] );

    $txtNew.css({left:0,top:0});
    self.$list.append($txtNew);

    Browser.addResizeHandler(self.resizeHandler , self);
    self.resizeHandler();

    Browser.addResponsivePCSPHandler(self.responsiveHandler , self);
    self.responsiveHandler();
  },

  createTxt : function(index,d){
    var self = this;
    var mojicount = self.map( Browser.windowWidth, 768 , 1100 , 30, 52 );
    return $('<p class="txt" data-index='+index+'><span>'+d.created_at+'</span> <a href="'+d.detail_url+'" target="_blank" class="text" onclick="tmEvent(\'トップtwitter'+index+'\');">'+self.convertTenTen(d.text,mojicount)+'</a></p>');
  },

  resizeHandler : function() {
    var self = this;
    if(Browser.responsivePCSP !== "pc") return;

    var mojicount = self.map( Browser.windowWidth , 768 , 1100 , 30, 52 );

    self.$list.find('.txt').each(function(){
      var index = $(this).data('index');
      $(this).find('.text').each(function(){
        var d = self.data[index];
        $(this).text( self.convertTenTen(d.text , mojicount) );
      });
    });

  },

  responsiveHandler : function() {
    var self = this;
    clearInterval(self.sId);

    //INIT
    if(Browser.responsivePCSP === "pc"){
      self.sId = setInterval($.proxy( self.timerHandler , self), TwitterInfo.TIMER_DURATION);
    } else {
    }
  },

  timerHandler : function(){
    var self = this;
    self.currentIndex++;
    if(self.currentIndex >= self.data.length){
      self.currentIndex = 0;
    }
    self.slide();
  },

  slide : function(){
    var self = this;

    var $txtOld = self.$list.find('.txt');
    var $txtNew = self.createTxt( self.currentIndex , self.data[ self.currentIndex ] );

    function anime1(){
      var d = new $.Deferred;
      $txtOld.stop().velocity({left:0,top:0,opacity:0},TwitterInfo.MOVE_DURATION,'easeInQuad');

      self.$list.append($txtNew);
      $txtNew.stop().delay(TwitterInfo.MOVE_DURATION)
        .css({left:0,top:30,opacity:0})
        .velocity({left:0,top:0,opacity:1},TwitterInfo.MOVE_DURATION,'easeOutExpo').queue(function(){
          d.resolve();
          $(this).dequeue();
        });
      return d.promise();
    }

    function animeend(){
      var d = new $.Deferred;
      var len = self.$list.find('.txt').length;
      self.$list.find('.txt').each(function(i){
        if(i===len-1){
        } else {
          $(this).remove();
        }
      });

      return d.promise();
    }

    anime1().then(animeend);
  },

  map : function( value , inputMin , inputMax , outputMin , outputMax , clamp){
    var self = this;
    var outVal = ((value - inputMin) / (inputMax - inputMin) * (outputMax - outputMin) + outputMin);
    if( clamp ){
      if(outputMax < outputMin){
        if( outVal < outputMax )outVal = outputMax;
        else if( outVal > outputMin )outVal = outputMin;
      }else{
        if( outVal > outputMax )outVal = outputMax;
        else if( outVal < outputMin )outVal = outputMin;
      }
    }
    //if(outVal < outputMin) outVal = outputMin;
    //if(outVal > outputMax) outVal = outputMax;
    return outVal;
  },

  convertTenTen : function(str,max) {
    var self = this;
    if(str.length <= max){
      return str;
    } else {
      return ( str.substr(0,max) + '...' );
    }
  },
};




//===============================================
//
// Browser
//
//===============================================
var Browser = function () {};
//----------------------------------------
// STATIC MEMBER
//----------------------------------------
Browser.isIE = false;
Browser.isIE6 = false;
Browser.isIE7 = false;
Browser.isIE8 = false;
Browser.isIE9 = false;
Browser.isFF = false;
Browser.isFF35 = false;
Browser.isOpera = false;
Browser.isChrome = false;
Browser.isSafari = false;
Browser.isiPhone = false;
Browser.isiPad = false;
Browser.isiPod = false;
Browser.isAndroid = false;

Browser.isCanvas = false;
Browser.isTransform = false;
Browser.isTransition = false;
Browser.isSp = false;

Browser.scrollX = 0;
Browser.scrollY = 0;
Browser.scrollYPercent = 0;
Browser.windowWidth = 0;
Browser.windowHeight = 0;
Browser.windowWidthHalf = 0;
Browser.windowHeightHalf = 0;
Browser.documentWidth = 0;
Browser.documentHeight = 0;
Browser.scrollRate = 0;

Browser.resizeFunction = [];
Browser.responsiveFunction = [];
Browser.responsivePCSPFunction = [];
Browser.scrollFunction = [];

Browser.responsive = 'pc';
Browser.responsivePCSP = 'pc';
Browser.transitionend = 'oTransitionEnd mozTransitionEnd webkitTransitionEnd transitionend';
Browser.animationend = 'webkitAnimationEnd animationend';
Browser.scrollEvent = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';

//Browser.RESPONSIVE_DIVIDE_WIDTH1 = 640;
//Browser.RESPONSIVE_DIVIDE_WIDTH2 = 980;
//----------------------------------------
//PRIVATE STATIC METHOD
//----------------------------------------
Browser._getScrollPosition = function(){
    var obj = new Object();
  obj.x = document.documentElement.scrollLeft || document.body.scrollLeft;
  obj.y = document.documentElement.scrollTop || document.body.scrollTop;
  return obj;
};
Browser._getScreenSize = function(){
  var obj = new Object();
  if( !Browser.isSafari && !Browser.isOpera ){
    obj.width = document.documentElement.clientWidth || document.body.clientWidth || document.body.scrollWidth;
    obj.height = document.documentElement.clientHeight || document.body.clientHeight || document.body.scrollHeight;
  } else {
    obj.width = window.innerWidth;
    obj.height = window.innerHeight;
  }
  obj.widthHalf = parseInt((obj.width)/2);
  obj.heightHalf = parseInt((obj.height)/2);
  return obj;
};
Browser._getDocumentSize = function(){
  var obj = new Object();
  obj.width = document.documentElement.scrollWidth || document.body.scrollWidth;
  obj.height = document.documentElement.scrollHeight || document.body.scrollHeight;
  return obj;
};
Browser._scrollHandler = function(){
  var scrollPosition = Browser._getScrollPosition();
  Browser.scrollX = scrollPosition.x;
  Browser.scrollY = scrollPosition.y;
  Browser.scrollYPercent = Browser.windowWidth !== 0 ? Browser.scrollY / Browser.windowWidth : 0;
  Browser.scrollRate = Browser.scrollY / (Browser.documentHeight - Browser.windowHeight);
  for(var i=0;i < Browser.scrollFunction.length;i++){
    Browser.scrollFunction[i].fn.apply( Browser.scrollFunction[i].scope );
  }
};
Browser._resizeHandler = function(){
  var screenSize = Browser._getScreenSize();
  Browser.windowWidth = screenSize.width;
  Browser.windowHeight = screenSize.height;
  Browser.windowWidthHalf = screenSize.widthHalf;
  Browser.windowHeightHalf = screenSize.heightHalf;
  var documentSize = Browser._getDocumentSize();
  Browser.documentWidth = documentSize.width;
  Browser.documentHeight = documentSize.height;
  for(var i=0;i < Browser.resizeFunction.length;i++){
    Browser.resizeFunction[i].fn.apply( Browser.resizeFunction[i].scope );
  }

  //PC TB SP
  //if(Browser.windowWidth <= Browser.RESPONSIVE_DIVIDE_WIDTH1){
  if( window.matchMedia && window.matchMedia('(max-width:640px)').matches ) {
    if(Browser.responsive !== 'sp'){
      Browser.responsive = 'sp';
      for(var i=0;i < Browser.responsiveFunction.length;i++){
        Browser.responsiveFunction[i].fn.apply( Browser.responsiveFunction[i].scope );
      }
    }
  //} else if(
  //  Browser.windowWidth > Browser.RESPONSIVE_DIVIDE_WIDTH1
  //  && Browser.windowWidth <= Browser.RESPONSIVE_DIVIDE_WIDTH2
  //){
  } else if( window.matchMedia && window.matchMedia('(min-width: 768px) and (max-width: 979px)').matches ) {
    if(Browser.responsive !== 'tb'){
      Browser.responsive = 'tb';
      for(var i=0;i < Browser.responsiveFunction.length;i++){
        Browser.responsiveFunction[i].fn.apply( Browser.responsiveFunction[i].scope );
      }
    }
  } else {
    if(Browser.responsive !== 'pc'){
      Browser.responsive = 'pc';
      for(var i=0;i < Browser.responsiveFunction.length;i++){
        Browser.responsiveFunction[i].fn.apply( Browser.responsiveFunction[i].scope );
      }
    }
  }

  //PC SP
  if( window.matchMedia && window.matchMedia('(max-width:767px)').matches ) { //'(max-width:767px)' old
    if(Browser.responsivePCSP !== 'sp'){
      Browser.responsivePCSP = 'sp';
      for(var i=0;i < Browser.responsivePCSPFunction.length;i++){
        Browser.responsivePCSPFunction[i].fn.apply( Browser.responsivePCSPFunction[i].scope );
      }
    }
  } else {
    if(Browser.responsivePCSP !== 'pc'){
      Browser.responsivePCSP = 'pc';
      for(var i=0;i < Browser.responsivePCSPFunction.length;i++){
        Browser.responsivePCSPFunction[i].fn.apply( Browser.responsivePCSPFunction[i].scope );
      }
    }
  }

};

//----------------------------------------
//PUBLIC STATIC METHOD
//----------------------------------------
Browser.init = function(){
  Browser.initUA();

  $(window).scroll(Browser._scrollHandler);
  Browser._scrollHandler();

  // $(window).resize();
  $(window).on('orientationchange resize', function(){
    setTimeout(function(){
      Browser._resizeHandler();
    },0);
  });
  Browser._resizeHandler();

};
Browser.initUA = function(){
    var userAgent = window.navigator.userAgent.toLowerCase();
  var appVersion = window.navigator.appVersion.toLowerCase();

  if (userAgent.indexOf("msie") > -1){
    Browser.isIE = true;
    if (appVersion.indexOf("msie 6.0") > -1){
      Browser.isIE6 = true;
    } else if( appVersion.indexOf("msie 7.0") > -1 ){
      Browser.isIE7 = true;
    } else if( appVersion.indexOf("msie 8.0") > -1 ){
      Browser.isIE8 = true;
    } else if( appVersion.indexOf("msie 9.0") > -1 ){
      Browser.isIE9 = true;
    }
  } else if( userAgent.indexOf("trident") > -1 ){
    Browser.isIE = true;
  } else if( userAgent.indexOf("firefox") > -1 ){
    Browser.isFF = true;
    var version = parseFloat(userAgent.match(/firefox\/(\d+\.\d+)/)[1]);
    Browser.isFF35 = version >= 3.5;
  } else if( userAgent.indexOf("iphone") > -1 ){
    Browser.isiPhone = true;
  } else if( userAgent.indexOf("ipod") > -1 ){
    Browser.isiPod = true;
  } else if( userAgent.indexOf("ipad") > -1 ){
    Browser.isiPad = true;
  } else if( userAgent.indexOf("android") > -1 ){
    Browser.isAndroid = true;
  } else if( userAgent.indexOf("opera") > -1 ){
    Browser.isOpera = true;
  } else if( userAgent.indexOf("chrome") > -1 ){
    Browser.isChrome = true;
  } else if( userAgent.indexOf("safari") > -1 ){
    Browser.isSafari = true;
  }

  var el = $("<div>");
  Browser.isCanvas = (!this.isIE6 && !this.isIE7 && !this.isIE8 && !this.isiPhone && !this.isiPod && !this.isAndroid);
  //this.isTransition = (!this.isIE6 && !this.isIE7 && !this.isIE8 && !this.isIE9 && !this.isiPhone && !this.isiPod && !this.isAndroid);
  Browser.isTransform = typeof el.css("transform") === "string";
  Browser.isTransition = typeof el.css("transitionProperty") === "string";
  Browser.isSp = (this.isiPhone || this.isiPod || this.isAndroid);
};
Browser.addResizeHandler = function(func,scope){
  if(typeof func === 'function'){
      Browser.resizeFunction.push( {fn:func , scope:scope} );
  }
};
Browser.removeResizeHandler = function(func){
  if(typeof func === 'function'){
    for(i=0;i<=Browser.resizeFunction.length-1;i++){
        var f = Browser.resizeFunction[i].fn;
        if(f === func ) {
          Browser.resizeFunction.splice(i,1);
        }
    }
  }
};
Browser.addResponsiveHandler = function(func,scope){
  if(typeof func === 'function'){
      Browser.responsiveFunction.push( {fn:func , scope:scope} );
  }
};
Browser.removeResponsiveHandler = function(func){
  if(typeof func === 'function'){
    for(i=0;i<=Browser.responsiveFunction.length-1;i++){
        var f = Browser.responsiveFunction[i].fn;
        if(f === func ) {
          Browser.responsiveFunction.splice(i,1);
        }
    }
  }
};
Browser.addResponsivePCSPHandler = function(func,scope){
  if(typeof func === 'function'){
      Browser.responsivePCSPFunction.push( {fn:func , scope:scope} );
  }
};
Browser.removeResponsivePCSPHandler = function(func){
  if(typeof func === 'function'){
    for(i=0;i<=Browser.responsivePCSPFunction.length-1;i++){
        var f = Browser.responsivePCSPFunction[i].fn;
        if(f === func ) {
          Browser.responsivePCSPFunction.splice(i,1);
        }
    }
  }
};

Browser.addScrollHandler = function(func,scope){
  if(typeof func === 'function'){
      Browser.scrollFunction.push( {fn:func , scope:scope} );
  }
};
Browser.removeScrollHandler = function(func){
  if(typeof func === 'function'){
    for(i=0;i<=Browser.scrollFunction.length-1;i++){
      var f = Browser.scrollFunction[i].fn;
        if(f === func ) {
          Browser.scrollFunction.splice(i,1);
        }
    }
  }
};
Browser.resize = function(){
    Browser._resizeHandler();
};
Browser.setViewportiPad = function(){
  if(!Browser.isiPad) return;
  var metalist = document.getElementsByTagName('meta');
  for(var i = 0; i < metalist.length; i++) {
      var name = metalist[i].getAttribute('name');
      if(name && name.toLowerCase() === 'viewport') {
          metalist[i].setAttribute('content', 'width=1024px,user-scalable=no');
          break;
      }
  }
  Browser.resize();
};
Browser.webgl = (
  function () {
    try {
      var canvas = document.createElement( 'canvas' );
      return !! ( window.WebGLRenderingContext && ( canvas.getContext( 'webgl' ) || canvas.getContext( 'experimental-webgl' ) ) );
    } catch ( e ) {
      return false;
    }
  }
)();
