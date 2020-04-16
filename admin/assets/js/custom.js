$(document).ready(function() {
  "use strict";


    /* ====== Index ======

    1. SCROLLBAR SIDEBAR
    2. BACKDROP
    3. SIDEBAR MENU
    4. SIDEBAR TOGGLE FOR MOBILE
    5. SIDEBAR TOGGLE FOR VARIOUS SIDEBAR LAYOUT
    7. RIGHT SIDEBAR

    ====== End ======*/



        /*======== 2. BACKDROP ========*/
        if ($(window).width() < 768) {
            var shadowClass = $(".mobile-sticky-body-overlay");
            $(".sidebar-toggle").on("click", function() {
                shadowClass.addClass("active");
                $("body").css("overflow", "hidden");
            });

            $(".mobile-sticky-body-overlay").on("click", function(e) {
                $(this).removeClass("active");
                $("#body").removeClass("sidebar-minified").addClass("sidebar-minified-out");
                $("body").css("overflow", "auto");
            });
        }

        /*======== 3. SIDEBAR MENU ========*/
        $(".sidebar .nav > .has-sub > a").click(function(){
            $(this).parent().siblings().removeClass('expand')
            $(this).parent().toggleClass('expand')
        })

        $(".sidebar .nav > .has-sub .has-sub > a").click(function(){
            $(this).parent().toggleClass('expand')
        })


        /*======== 4. SIDEBAR TOGGLE FOR MOBILE ========*/
        if ($(window).width() < 768) {
            $(document).on("click", ".sidebar-toggle", function(e) {
                e.preventDefault();
                var min = "sidebar-minified",
                    min_out = "sidebar-minified-out",
                    body = "#body";
                $(body).hasClass(min)
                    ? $(body)
                        .removeClass(min)
                        .addClass(min_out)
                    : $(body)
                        .addClass(min)
                        .removeClass(min_out)
            });
        }

        /*======== 5. SIDEBAR TOGGLE FOR VARIOUS SIDEBAR LAYOUT ========*/
        var body = $("#body");
        if ($(window).width() >= 768) {
            window.isMinified = false;
            window.isCollapsed = false;

            $("#sidebar-toggler").on("click", function () {
                if (
                    body.hasClass("sidebar-fixed-offcanvas") ||
                    body.hasClass("sidebar-static-offcanvas")
                ) {
                    $(this)
                        .addClass("sidebar-offcanvas-toggle")
                        .removeClass("sidebar-toggle");
                    if (window.isCollapsed === false) {
                        body.addClass("sidebar-collapse");
                        window.isCollapsed = true;
                        window.isMinified = false;
                    } else {
                        body.removeClass("sidebar-collapse");
                        body.addClass("sidebar-collapse-out");
                        setTimeout(function () {
                            body.removeClass("sidebar-collapse-out");
                        }, 300);
                        window.isCollapsed = false;
                    }
                }

                if (
                    body.hasClass("sidebar-fixed") ||
                    body.hasClass("sidebar-static")
                ) {
                    $(this)
                        .addClass("sidebar-toggle")
                        .removeClass("sidebar-offcanvas-toggle");
                    if (window.isMinified === false) {
                        body
                            .removeClass("sidebar-collapse sidebar-minified-out")
                            .addClass("sidebar-minified");
                        window.isMinified = true;
                        window.isCollapsed = false;
                    } else {
                        body.removeClass("sidebar-minified");
                        body.addClass("sidebar-minified-out");
                        window.isMinified = false;
                    }
                }
            });
        }

        if ($(window).width() >= 768 && $(window).width() < 992) {
            if (
                body.hasClass("sidebar-fixed") ||
                body.hasClass("sidebar-static")
            ) {
                body
                    .removeClass("sidebar-collapse sidebar-minified-out")
                    .addClass("sidebar-minified");
                window.isMinified = true;
            }
        }



        /*======== 7. RIGHT SIDEBAR ========*/
        if ($(window).width() < 1025) {
            body.addClass('right-sidebar-toggoler-out');

            var btnRightSidebarToggler = $('.btn-right-sidebar-toggler');

            btnRightSidebarToggler.on('click', function () {

                if (!body.hasClass('right-sidebar-toggoler-out')) {
                    body.addClass('right-sidebar-toggoler-out').removeClass('right-sidebar-toggoler-in');
                } else {
                    body.addClass('right-sidebar-toggoler-in').removeClass('right-sidebar-toggoler-out')
                }

            });

        }

        var navRightSidebarLink = $('.nav-right-sidebar .nav-link');

        navRightSidebarLink.on('click', function () {

            if(!body.hasClass('right-sidebar-in')){
                body.addClass('right-sidebar-in').removeClass('right-sidebar-out');

            } else if ($(this).hasClass('show')){
                body.addClass('right-sidebar-out').removeClass('right-sidebar-in');
            }
        });


        var cardClosebutton = $('.card-right-sidebar .close');
        cardClosebutton.on('click', function () {
            body.removeClass('right-sidebar-in').addClass('right-sidebar-out');
        })


// active page
    var page = location.pathname.split('/').pop();
    $('#sidebar-menu li').each(function(){
        $(this).toggleClass('active',$(this).find('a').attr('href')==page);
    });

});
