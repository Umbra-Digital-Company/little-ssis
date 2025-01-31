$(document).ready(function() {

    /*
     * GLOBAL FUNCTION
     * Scripts for global functionality start here
     * 
     */

    const togglePassword = () => { // show or hide password
        var x = $('#Password');
        (x.prop('type') === "password") ? x.attr('type', 'text'): x.attr('type', 'password');
    }

    const toggleSidebar = toggle => {
        switch (toggle) {
            case 'show':
                $('#sidebar').addClass('show');
                $('.ssis-backdrop').fadeIn(100);
                break;
            case 'hide':
                $('#sidebar').removeClass('show');
                $('.ssis-backdrop').fadeOut(100);
                break;
        }
    }

    const toggleFilter = toggle => {
        switch (toggle) {
            case 'show':
                $('.dashboard-filter').addClass('show');
                break;
            case 'hide':
                $('.dashboard-filter').removeClass('show');
                break;
        }
    }

    // toggle sidebar
    $('.burger').on('click', function() {
        toggleSidebar('show');

        $('.close-sidebar').on('click', function() {
            toggleSidebar('hide');
        });
    });

    // toggle dashboard filter
    $('#toggle-filter').on('click', function(e) {
        e.preventDefault();
        toggleFilter('show');

        $('#close-filter').on('click', function() {
            toggleFilter('hide');
        });
    });

    // activate custom scrollbar
    if ($('.activate-scrollbar').length) {
        $('.activate-scrollbar').each(function() {
            const ps = new PerfectScrollbar($(this)[0]);
        });
    }

    // adjust sidebar
    if ($('#sidebar').length) {
        var sidebar = $('#sidebar').outerHeight(true);
        var space = $('#sidebar .logo').outerHeight(true) + $('#admin-logout').outerHeight(true);
        var navHeight = sidebar - space;

        $(window).on('resize', function() {
            $('#sidebar .navigation').css('height', navHeight);
        }).resize();
    }





    /*
     * DASHBOARD FUNCTION
     * Scripts for assistant functionality start here
     * 
     * 1: Check if element exist | 2: Create a function | 3: Use the function
     * 
     */

    if ($('.table-custom').length) {

        if ($('.table-custom .tbody').outerHeight() >= 500 && $(window).width() >= 992) {
            $('.table-custom .thead').css('padding-right', '16px');
        }

    }



    /*
     * VVM FUNCTION
     * Scripts for assistant functionality start here
     * 
     * 1: Check if element exist | 2: Create a function | 3: Use the function
     * 
     */

    if ($('#main-vvm').length) {

        const keyboardTyping = ($input, $time, $function) => {
            // search templates
            var typingTimer; //timer identifier
            var doneTypingInterval = $time; //time in ms, 5 second for example
            var getInput = $input;

            getInput.on('keyup', function() { //on keyup, start the countdown
                clearTimeout(typingTimer);
                typingTimer = setTimeout($function, doneTypingInterval);
            }).on('keydown', function() { //on keydown, clear the countdown 
                clearTimeout(typingTimer);
            }).on('blur', $function);
        }

        const loadSearch = ($input, $search) => {
            var s = $input.val().replace(/\s/g, '+');
            switch ($search) {

                case 'template':
                    $('#vvm-data ul').load('/vvm/includes/specs/grab_templates.php?s=' + s);
                    break;

                case 'frame':
                    $('#search-result').load('/vvm/process/specs/search_treasure_chest.php?s=' + s, function() {
                        $(".drag-specs").click(function() {
                            // Set current data
                            var curImage = $(this).attr("src"),
                                curStyle = $(this).data("style"),
                                curColor = $(this).data("color");
                            // Set as treasure
                            $("#treasure-image").attr("src", curImage);
                            $("#treasure-image").data("style", curStyle);
                            $("#treasure-image").data("color", curColor);
                            $("#treasure-image").draggable();
                        });
                    });
                    break;
            }
        }

        // search template
        keyboardTyping($('#search_template'), 750, function() {
            loadSearch($('#search_template'), 'template');
        });

        // search frame
        keyboardTyping($('#treasure_chest_search'), 750, function() {
            loadSearch($('#treasure_chest_search'), 'frame');
        });

        // toggle vvm menu
        $('.burger-vvm').on('click', function() {
            $(this).toggleClass('open');
            $('.vvm-backdrop').fadeToggle();

            $('.main-menu-vvm').toggleClass('show');
            $('#vvm-nav a').each(function(i) {
                var navItem = $(this);
                setTimeout(function() {
                    navItem.toggleClass('show', !navItem.hasClass('show'));
                }, 300 * i);

            });
        });

        // create new template
        $('.no-available').on('submit', function(e) {
            if ($('#templateName').val() == '') {
                e.preventDefault();
                $('#templateName').addClass('border-danger');
            }
        });

        // toggle frame name and image
        $('#template_view').on('change', function() {
            if ($(this).data('action') == 'visibility') {
                if (this.value === 'frame') {
                    $('.mini-tooltip').hide().siblings('img').css('opacity', '1');
                } else if (this.value === 'name') {
                    $('.mini-tooltip').fadeIn().siblings('img').css('opacity', '0');
                }
            } else if ($(this).data('action') == 'hide') {
                if (this.value === 'frame') {
                    $('.mini-tooltip').hide().siblings('img').fadeIn();
                } else if (this.value === 'name') {
                    $('.mini-tooltip').fadeIn().siblings('img').hide();
                }
            }
        }).change();

        // toggle wall and drawer section
        $('#panel-top-bar a').on('click', function(e) {
            $(this).addClass('active').siblings().removeClass('active');
            switch ($(this).attr('id')) {
                case 'toggle-wall':
                    $('#left-panel').fadeIn().siblings().hide();
                    e.preventDefault();
                    break;

                case 'toggle-drawer':
                    $('#right-panel').fadeIn().siblings().hide();
                    e.preventDefault();
                    break;

                case 'toggle-update':
                    window.location.reload();
            }
        })

        // validate selecting template in vvm/store
        $('.template-selection select').each(function() {
            if ($(this).val() == "") {
                $(this).addClass('text-secondary');
            } else {
                $(this).removeClass('text-secondary');
            }
            $(this).on('change', function() {
                if ($(this).val() == "") {
                    $(this).addClass('text-secondary');
                } else {
                    $(this).removeClass('text-secondary');
                }

                // update store
                $('#update-visualize-store').submit();
            });
        });

        // template slider
        if ($('.template-slider').length) {
            $('.template-slider').slick({
                dots: false,
                fade: true,
                infinite: false,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                nextArrow: '<input type="button" class="sr-only" id="next-template-slide" />',
                prevArrow: '<input type="button" class="sr-only" id="prev-template-slide" />'
                    // responsive: [
                    // 	{
                    // 	breakpoint: 1024,
                    // 	settings: {
                    // 		slidesToShow: 3,
                    // 		slidesToScroll: 3,
                    // 		infinite: true,
                    // 		dots: true
                    // 	}
                    // 	},
                    // 	{
                    // 	breakpoint: 600,
                    // 	settings: {
                    // 		slidesToShow: 2,
                    // 		slidesToScroll: 2
                    // 	}
                    // 	},
                    // 	{
                    // 	breakpoint: 480,
                    // 	settings: {
                    // 		slidesToShow: 1,
                    // 		slidesToScroll: 1
                    // 	}
                    // 	}
                    // 	// You can unslick at a given breakpoint now by adding:
                    // 	// settings: "unslick"
                    // 	// instead of a settings object
                    // ]
            });
        }

    }

});

$(window).on('load scroll resize', function() {
    // if ($('#load-top-selling-item').length) {

    // 	$('#load-top-selling-item').width($('#load-top-selling-item').parents('.custom-card').width() - 50);

    // }

    if ($('.table-default').length) {

        $('.table-default').css('max-width', $('.table-default').parent().width());

    }

    if ($('#main-vvm').length) {

        // activate custom scrollbar
        if ($('.activate-scrollbar').length) {
            $('.activate-scrollbar').each(function() {
                const ps = new PerfectScrollbar($(this)[0]);
            });
        }

        var adjustHeight = 400;
        var adjustHeight2 = 400;
        var adjustHeight3 = 400;

        // calculate available height of sidebar
        var a = $('.logo').outerHeight(true);
        var b = $('.search-vvm').outerHeight(true);
        var c = $('.sidebar-container').height() - 30;

        // calculate available height of panel
        if ($('.panels').length) {
            var d = $('.treasure').outerHeight();
            // responsive height
            var f = ($(window).width() < 1600) ? $('#panel-top-bar').outerHeight(true) : 0;


            adjustHeight2 = c - d + 70;
            adjustHeight3 = c - f + 80;

            $('#search-result').css('height', adjustHeight2);
            if ($('.panel-scroll').hasClass('wall-panel')) {
                $('.wall-panel').css('height', adjustHeight3);
            } else {
                $('.panel-scroll').css('height', $('.sidebar-container').outerHeight(true));
            }
            $('.panel-scroll').fadeIn();
        }

        adjustHeight = c - (a + b);

        $('#vvm-data ul').css('max-height', adjustHeight + 'px').fadeIn();

        if ($('.wall-height').length) {
            $('.wall-height').height($('.custom-card').height() - $('.custom-card > p').outerHeight(true));
            // $('.custom-card').height($('.vvm-content').height() - 30);
        }

        if ($('.template-slider').length) {
            // show template slider
            $('.template-slider').animate({
                'opacity': '1'
            }, 500);
        }

    }

}).resize();