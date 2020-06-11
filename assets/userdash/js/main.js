(function ($) {
	"use strict";

    jQuery(document).ready(function($){
/*--theme swither activation--*/
        $(document).on('click','.Switcher',function(){
            var cname = $(this).attr('class');
            var length = cname.length;
            if(length == 8){
                $(this).addClass('active');
            }else{
                $(this).removeClass('active');
            }  
        });
        /*--change styleshet--*/
        $(document).on('click','#colors li',function(){
            var cname = $(this).attr('class');
            var cid = cname.substr(6);
            //alert(cid)
            if(cid == 1){
            $('head').append('<link href="assets/css/themes/theme-1.css" rel="stylesheet">');
            }else if(cid == 2){
               $('head').append('<link href="assets/css/themes/theme-2.css" rel="stylesheet">');
            }else if(cid == 3){
                $('head').append('<link href="assets/css/themes/theme-3.css" rel="stylesheet">');
            }else if(cid == 4){
                $('head').append('<link href="assets/css/themes/theme-4.css" rel="stylesheet">');
            }else if(cid == 5){
                $('head').append('<link href="assets/css/themes/theme-5.css" rel="stylesheet">');
            }else if(cid == 6){
                $('head').append('<link href="assets/css/themes/theme-6.css" rel="stylesheet">');
            }else if(cid == 7){
                $('head').append('<link href="assets/css/themes/theme-7.css" rel="stylesheet">');
            }else if(cid == 8){
                $('head').append('<link href="assets/css/themes/theme-8.css" rel="stylesheet">');
            }else if(cid == 9){
                $('head').append('<link href="assets/css/themes/theme-9.css" rel="stylesheet">');
            }else if(cid == 10){
                $('head').append('<link href="assets/css/themes/theme-10.css" rel="stylesheet">');
            }else if(cid == 11){
                $('head').append('<link href="assets/css/themes/theme-11.css" rel="stylesheet">');
            }else if(cid == 12){
                $('head').append('<link href="assets/css/themes/theme-12.css" rel="stylesheet">');
            }else if(cid == 13){
                $('head').append('<link href="assets/css/themes/theme-13.css" rel="stylesheet">');
            }else{
                $('head').append('<link href="assets/css/themes/theme-14.css" rel="stylesheet">');
            }
            return false;
        });
        /*Accordian Management*/
        var toogleAccordian = $(".toggle-accordion")
        toogleAccordian.on("click", function() {
            var accordionId = $(this).attr("accordion-id"),
              numPanelOpen = $(accordionId + ' .collapse.in').length;

            $(this).toggleClass("active");

          })
        
      /*--price range--*/
           var PriceRange = $(".range-slider");
           PriceRange.asRange({
            tip:false
           });
        /*--project counter activation--*/
          var projectCounter = $('.counter');
          projectCounter.each(function() {
          var $this = $(this),
              countTo = $this.attr('data-count');
          $({ countNum: $this.text()}).animate({
            countNum: countTo
          },
          {
            duration: 5000,
            easing:'linear',
            step: function() {
              $this.text(Math.floor(this.countNum));
            },
            complete: function() {
              $this.text(this.countNum);
            }
              });  
            });
         /*--testimonial carousel slider activation--*/
          var testimonialCaoursel = $('.slider-activation');
          testimonialCaoursel.owlCarousel({
            loop:true,
            dots:true,
            nav:true,
            navText:['<i class="fa fa-caret-left"></i>','<i class="fa fa-caret-right"></i>'],
            autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive : {
              0 : {
                  items: 1
              },
              768 : {
                  items: 1
              },
              960 : {
                  items: 1
              },
              1200 : {
                  items: 1
              },
              1920 : {
                  items: 1
              }
            }
        });     
          /*--Header carousel slider activation--*/
          var headerCaoursel = $('.head-slider');
          headerCaoursel.owlCarousel({
            loop:true,
            dots:true,
            nav:true,
            navText:['<i class="fa fa-caret-left"></i>','<i class="fa fa-caret-right"></i>'],
            autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive : {
              0 : {
                  items: 1
              },
              768 : {
                  items: 1
              },
              960 : {
                  items: 1
              },
              1200 : {
                  items: 1
              },
              1920 : {
                  items: 1
              }
            }
        });     
        });
        
       /*--slick Nav Responsive Navbar activation--*/
          var SlicMenu = $('#menu-bar');
         SlicMenu.slicknav();
        /*--scroll to top activation--*/
        $(document).on('click', '.scroll-to-top a', function (e) {
            e.preventDefault();
            $("html,body").animate({
                scrollTop: 0
            }, 3000);
            
        });
        
        
       

        /*--window Scroll functions--*/
        $(window).on('scroll', function () {
            /*--show and hide scroll to top --*/
            var ScrollTop = $('.scroll-to-top i');
            if ($(window).scrollTop() > 500) {
                ScrollTop.fadeIn(1000);
            } else {
                ScrollTop.fadeOut(1000);
            }
            /*--sticky menu activation--*/
            var mainMenuTop = $('.main-menu');
            if ($(window).scrollTop() > 300) {
                mainMenuTop.addClass('nav-fixed');
            } else {
                mainMenuTop.removeClass('nav-fixed');
            }
            /*--sticky menu activation--*/
            var slickNavTop = $('.slicknav_menu');
            var logoFixed = $('.mobile-logo')
            if ($(window).scrollTop() > 300) {
                slickNavTop.addClass('nav-fixed');
                logoFixed.addClass('fixed');
            } else {
                slickNavTop.removeClass('nav-fixed');
                logoFixed.removeClass('fixed');
            }
        });
           
/*--window load functions--*/
    jQuery(window).load(function(){
        var preLoder = $(".preloader");
        preLoder.fadeOut(1000);
    });


}(jQuery));	