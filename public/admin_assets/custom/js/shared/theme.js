$(function () {

    var vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');

    $.app = $.app || {};

    var $body = $('body');
    var $window = $(window);
    var menuWrapper_el = $('div[data-menu="menu-wrapper"]').html();
    var menuWrapperClasses = $('div[data-menu="menu-wrapper"]').attr('class');

    // Main menu
    $.app.menu = {
        expanded: null,
        collapsed: null,
        hidden: null,
        container: null,
        horizontalMenu: false,

        is_touch_device: function () {
            var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
            var mq = function (query) {
                return window.matchMedia(query).matches;
            };
            if ('ontouchstart' in window || (window.DocumentTouch && document instanceof DocumentTouch)) {
                return true;
            }
            // include the 'heartz' as a way to have a non matching MQ to help terminate the join
            // https://git.io/vznFH
            var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
            return mq(query);
        },

        manualScroller: {
            obj: null,

            init: function () {
                var scroll_theme = $('.main-menu').hasClass('menu-dark') ? 'light' : 'dark';
                if (!$.app.menu.is_touch_device()) {
                    this.obj = new PerfectScrollbar('.main-menu-content', {
                        suppressScrollX: true,
                        wheelPropagation: false
                    });
                } else {
                    $('.main-menu').addClass('menu-native-scroll');
                }
            },

            update: function () {
                // if (this.obj) {
                // Scroll to currently active menu on page load if data-scroll-to-active is true
                if ($('.main-menu').data('scroll-to-active') === true) {
                    var activeEl, menu, activeElHeight;
                    activeEl = document.querySelector('.main-menu-content li.active');
                    menu = document.querySelector('.main-menu-content');
                    if ($('body').hasClass('menu-collapsed')) {
                        if ($('.main-menu-content li.sidebar-group-active').length) {
                            activeEl = document.querySelector('.main-menu-content li.sidebar-group-active');
                        }
                    }
                    if (activeEl) {
                        activeElHeight = activeEl.getBoundingClientRect().top + menu.scrollTop;
                    }

                    // If active element's top position is less than 2/3 (66%) of menu height than do not scroll
                    if (activeElHeight > parseInt((menu.clientHeight * 2) / 3)) {
                        var start = menu.scrollTop,
                            change = activeElHeight - start - parseInt(menu.clientHeight / 2);
                    }
                    setTimeout(function () {
                        $.app.menu.container.stop().animate(
                            {
                                scrollTop: change
                            },
                            300
                        );
                        $('.main-menu').data('scroll-to-active', 'false');
                    }, 300);
                }
                // this.obj.update();
                // }
            },

            enable: function () {
                if (!$('.main-menu-content').hasClass('ps')) {
                    this.init();
                }
            },

            disable: function () {
                if (this.obj) {
                    this.obj.destroy();
                }
            },

            updateHeight: function () {
                if (
                    ($('body').data('menu') == 'vertical-menu' ||
                        $('body').data('menu') == 'vertical-menu-modern' ||
                        $('body').data('menu') == 'vertical-overlay-menu') &&
                    $('.main-menu').hasClass('menu-fixed')
                ) {
                    $('.main-menu-content').css(
                        'height',
                        $(window).height() -
                        $('.header-navbar').height() -
                        $('.main-menu-header').outerHeight() -
                        $('.main-menu-footer').outerHeight()
                    );
                    this.update();
                }
            }
        },

        init: function (compactMenu) {
            if ($('.main-menu-content').length > 0) {
                this.container = $('.main-menu-content');

                var menuObj = this;

                this.change(compactMenu);
            }
        },

        change: function (compactMenu) {
            var currentBreakpoint = Unison.fetch.now(); // Current Breakpoint

            this.reset();

            var menuType = $('body').data('menu');

            if (currentBreakpoint) {
                switch (currentBreakpoint.name) {
                    case 'xl':
                        if (menuType === 'vertical-overlay-menu') {
                            this.hide();
                        } else {
                            if (compactMenu === true) this.collapse(compactMenu);
                            else this.expand();
                        }
                        break;
                    case 'lg':
                        if (
                            menuType === 'vertical-overlay-menu' ||
                            menuType === 'vertical-menu-modern' ||
                            menuType === 'horizontal-menu'
                        ) {
                            this.hide();
                        } else {
                            this.collapse();
                        }
                        break;
                    case 'md':
                    case 'sm':
                        this.hide();
                        break;
                    case 'xs':
                        this.hide();
                        break;
                }
            }

            // On the small and extra small screen make them overlay menu
            if (menuType === 'vertical-menu' || menuType === 'vertical-menu-modern') {
                this.toOverlayMenu(currentBreakpoint.name, menuType);
            }

            if ($('body').is('.horizontal-layout') && !$('body').hasClass('.horizontal-menu-demo')) {
                this.changeMenu(currentBreakpoint.name);

                $('.menu-toggle').removeClass('is-active');
            }

            // Dropdown submenu on large screen on hover For Large screen only
            // ---------------------------------------------------------------
            if (currentBreakpoint.name == 'xl') {
                $(document) // Use selector $('body[data-open="hover"] .header-navbar .dropdown') for menu and navbar DD open on hover
                    .on('mouseenter', 'body[data-open="hover"] .main-menu-content .dropdown', function () {
                        if (!$(this).hasClass('show')) {
                            $(this).addClass('show');
                        } else {
                            $(this).removeClass('show');
                        }
                    })
                    .on('mouseleave', 'body[data-open="hover"] .main-menu-content .dropdown', function (event) {
                        $(this).removeClass('show');
                    });
                /* ? Uncomment to enable all DD open on hover
                 $('body[data-open="hover"] .dropdown a').on('click', function (e) {
                 if (menuType == 'horizontal-menu') {
                 var $this = $(this);
                 if ($this.hasClass('dropdown-toggle')) {
                 return false;
                 }
                 }
                 });
                 */
            }

            // Added data attribute brand-center for navbar-brand-center

            if (currentBreakpoint.name == 'sm' || currentBreakpoint.name == 'xs') {
                $('.header-navbar[data-nav=brand-center]').removeClass('navbar-brand-center');
            } else {
                $('.header-navbar[data-nav=brand-center]').addClass('navbar-brand-center');
            }
            // On screen width change, current active menu in horizontal
            if (currentBreakpoint.name == 'xl' && menuType == 'horizontal-menu') {
                $('.main-menu-content').find('li.active').parents('li').addClass('sidebar-group-active active');
            }

            if (currentBreakpoint.name !== 'xl' && menuType == 'horizontal-menu') {
                $('#navbar-type').toggleClass('d-none d-xl-block');
            }

            // Dropdown submenu on small screen on click
            // --------------------------------------------------
            $(document).on('click', 'ul.dropdown-menu [data-toggle=dropdown]', function (event) {
                if ($(this).siblings('ul.dropdown-menu').length > 0) {
                    event.preventDefault();
                }
                event.stopPropagation();
                $(this).parent().siblings().removeClass('show');
                $(this).parent().toggleClass('show');
            });

            // Horizontal layout submenu drawer scrollbar
            if (menuType == 'horizontal-menu') {
                $(document).on('mouseenter', 'li.dropdown-submenu', function () {
                    if (!$(this).parent('.dropdown').hasClass('show')) {
                        $(this).removeClass('openLeft');
                    }
                    var dd = $(this).find('.dropdown-menu');
                    if (dd) {
                        var pageHeight = $(window).height(),
                            // ddTop = dd.offset().top,
                            ddTop = $(this).position().top,
                            ddLeft = dd.offset().left,
                            ddWidth = dd.width(),
                            ddHeight = dd.height();
                        if (pageHeight - ddTop - ddHeight - 28 < 1) {
                            var maxHeight = pageHeight - ddTop - 170;
                            $(this)
                                .find('.dropdown-menu')
                                .css({
                                    'max-height': maxHeight + 'px',
                                    'overflow-y': 'auto',
                                    'overflow-x': 'hidden'
                                });
                            var menu_content = new PerfectScrollbar('li.dropdown-submenu.show .dropdown-menu', {
                                wheelPropagation: false
                            });
                        }
                        // Add class to horizontal sub menu if screen width is small
                        if (ddLeft + ddWidth - (window.innerWidth - 16) >= 0) {
                            $(this).addClass('openLeft');
                        }
                    }
                });
                $('.theme-layouts').find('.semi-dark').hide();
            }

            // Horizontal Fixed Nav Sticky hight issue on small screens
            // if (menuType == 'horizontal-menu') {
            //   if (currentBreakpoint.name == 'sm' || currentBreakpoint.name == 'xs') {
            //     if ($(".menu-fixed").length) {
            //       $(".menu-fixed").unstick();
            //     }
            //   }
            //   else {
            //     if ($(".navbar-fixed").length) {
            //       $(".navbar-fixed").sticky();
            //     }
            //   }
            // }
        },

        transit: function (callback1, callback2) {
            var menuObj = this;
            $('body').addClass('changing-menu');

            callback1.call(menuObj);

            if ($('body').hasClass('vertical-layout')) {
                if ($('body').hasClass('menu-open') || $('body').hasClass('menu-expanded')) {
                    $('.menu-toggle').addClass('is-active');

                    // Show menu header search when menu is normally visible
                    if ($('body').data('menu') === 'vertical-menu') {
                        if ($('.main-menu-header')) {
                            $('.main-menu-header').show();
                        }
                    }
                } else {
                    $('.menu-toggle').removeClass('is-active');

                    // Hide menu header search when only menu icons are visible
                    if ($('body').data('menu') === 'vertical-menu') {
                        if ($('.main-menu-header')) {
                            $('.main-menu-header').hide();
                        }
                    }
                }
            }

            setTimeout(function () {
                callback2.call(menuObj);
                $('body').removeClass('changing-menu');

                menuObj.update();
            }, 500);
        },

        open: function () {
            this.transit(
                function () {
                    $('body').removeClass('menu-hide menu-collapsed').addClass('menu-open');
                    this.hidden = false;
                    this.expanded = true;

                    if ($('body').hasClass('vertical-overlay-menu')) {
                        $('.sidenav-overlay').addClass('show');
                        // $('.sidenav-overlay').removeClass('d-none').addClass('d-block');
                        // $('body').css('overflow', 'hidden');
                    }
                },
                function () {
                    if (!$('.main-menu').hasClass('menu-native-scroll') && $('.main-menu').hasClass('menu-fixed')) {
                        this.manualScroller.enable();
                        $('.main-menu-content').css(
                            'height',
                            $(window).height() -
                            $('.header-navbar').height() -
                            $('.main-menu-header').outerHeight() -
                            $('.main-menu-footer').outerHeight()
                        );
                        // this.manualScroller.update();
                    }

                    if (!$('body').hasClass('vertical-overlay-menu')) {
                        $('.sidenav-overlay').removeClass('show');
                        // $('.sidenav-overlay').removeClass('d-block d-none');
                        // $('body').css('overflow', 'auto');
                    }
                }
            );
        },

        hide: function () {
            this.transit(
                function () {
                    $('body').removeClass('menu-open menu-expanded').addClass('menu-hide');
                    this.hidden = true;
                    this.expanded = false;

                    if ($('body').hasClass('vertical-overlay-menu')) {
                        $('.sidenav-overlay').removeClass('show');
                        // $('.sidenav-overlay').removeClass('d-block').addClass('d-none');
                        // $('body').css('overflow', 'auto');
                    }
                },
                function () {
                    if (!$('.main-menu').hasClass('menu-native-scroll') && $('.main-menu').hasClass('menu-fixed')) {
                        this.manualScroller.enable();
                    }

                    if (!$('body').hasClass('vertical-overlay-menu')) {
                        $('.sidenav-overlay').removeClass('show');
                        // $('.sidenav-overlay').removeClass('d-block d-none');
                        // $('body').css('overflow', 'auto');
                    }
                }
            );
        },

        expand: function () {
            if (this.expanded === false) {
                if ($('body').data('menu') == 'vertical-menu-modern') {
                    $('.modern-nav-toggle')
                        .find('.collapse-toggle-icon')
                        .replaceWith(
                            feather.icons['disc'].toSvg({class: 'd-none d-xl-block collapse-toggle-icon primary font-medium-4'})
                        );
                }
                this.transit(
                    function () {
                        $('body').removeClass('menu-collapsed').addClass('menu-expanded');
                        this.collapsed = false;
                        this.expanded = true;
                        $('.sidenav-overlay').removeClass('show');

                        // $('.sidenav-overlay').removeClass('d-block d-none');
                    },
                    function () {
                        if ($('.main-menu').hasClass('menu-native-scroll') || $('body').data('menu') == 'horizontal-menu') {
                            this.manualScroller.disable();
                        } else {
                            if ($('.main-menu').hasClass('menu-fixed')) this.manualScroller.enable();
                        }

                        if (
                            ($('body').data('menu') == 'vertical-menu' || $('body').data('menu') == 'vertical-menu-modern') &&
                            $('.main-menu').hasClass('menu-fixed')
                        ) {
                            $('.main-menu-content').css(
                                'height',
                                $(window).height() -
                                $('.header-navbar').height() -
                                $('.main-menu-header').outerHeight() -
                                $('.main-menu-footer').outerHeight()
                            );
                            // this.manualScroller.update();
                        }
                    }
                );
            }
        },

        collapse: function () {
            if (this.collapsed === false) {
                if ($('body').data('menu') == 'vertical-menu-modern') {
                    $('.modern-nav-toggle')
                        .find('.collapse-toggle-icon')
                        .replaceWith(
                            feather.icons['circle'].toSvg({
                                class: 'd-none d-xl-block collapse-toggle-icon primary font-medium-4'
                            })
                        );
                }
                this.transit(
                    function () {
                        $('body').removeClass('menu-expanded').addClass('menu-collapsed');
                        this.collapsed = true;
                        this.expanded = false;

                        $('.content-overlay').removeClass('d-block d-none');
                    },
                    function () {
                        if ($('body').data('menu') == 'horizontal-menu' && $('body').hasClass('vertical-overlay-menu')) {
                            if ($('.main-menu').hasClass('menu-fixed')) this.manualScroller.enable();
                        }
                        if (
                            ($('body').data('menu') == 'vertical-menu' || $('body').data('menu') == 'vertical-menu-modern') &&
                            $('.main-menu').hasClass('menu-fixed')
                        ) {
                            $('.main-menu-content').css('height', $(window).height() - $('.header-navbar').height());
                            // this.manualScroller.update();
                        }
                        if ($('body').data('menu') == 'vertical-menu-modern') {
                            if ($('.main-menu').hasClass('menu-fixed')) this.manualScroller.enable();
                        }
                    }
                );
            }
        },

        toOverlayMenu: function (screen, menuType) {
            var menu = $('body').data('menu');
            if (menuType == 'vertical-menu-modern') {
                if (screen == 'lg' || screen == 'md' || screen == 'sm' || screen == 'xs') {
                    if ($('body').hasClass(menu)) {
                        $('body').removeClass(menu).addClass('vertical-overlay-menu');
                    }
                } else {
                    if ($('body').hasClass('vertical-overlay-menu')) {
                        $('body').removeClass('vertical-overlay-menu').addClass(menu);
                    }
                }
            } else {
                if (screen == 'sm' || screen == 'xs') {
                    if ($('body').hasClass(menu)) {
                        $('body').removeClass(menu).addClass('vertical-overlay-menu');
                    }
                } else {
                    if ($('body').hasClass('vertical-overlay-menu')) {
                        $('body').removeClass('vertical-overlay-menu').addClass(menu);
                    }
                }
            }
        },

        changeMenu: function (screen) {
            // Replace menu html
            $('div[data-menu="menu-wrapper"]').html('');
            $('div[data-menu="menu-wrapper"]').html(menuWrapper_el);

            var menuWrapper = $('div[data-menu="menu-wrapper"]'),
                menuContainer = $('div[data-menu="menu-container"]'),
                menuNavigation = $('ul[data-menu="menu-navigation"]'),
                /*megaMenu           = $('li[data-menu="megamenu"]'),
                 megaMenuCol        = $('li[data-mega-col]'),*/
                dropdownMenu = $('li[data-menu="dropdown"]'),
                dropdownSubMenu = $('li[data-menu="dropdown-submenu"]');

            if (screen === 'xl') {
                // Change body classes
                $('body').removeClass('vertical-layout vertical-overlay-menu fixed-navbar').addClass($('body').data('menu'));

                // Remove navbar-fix-top class on large screens
                $('nav.header-navbar').removeClass('fixed-top');

                // Change menu wrapper, menu container, menu navigation classes
                menuWrapper.removeClass().addClass(menuWrapperClasses);

                $(document).on('click', 'a.dropdown-item.nav-has-children', function () {
                    event.preventDefault();
                    event.stopPropagation();
                });
                $(document).on('click', 'a.dropdown-item.nav-has-parent', function () {
                    event.preventDefault();
                    event.stopPropagation();
                });
            } else {
                // Change body classes
                $('body').removeClass($('body').data('menu')).addClass('vertical-layout vertical-overlay-menu fixed-navbar');

                // Add navbar-fix-top class on small screens
                $('nav.header-navbar').addClass('fixed-top');

                // Change menu wrapper, menu container, menu navigation classes
                menuWrapper.removeClass().addClass('main-menu menu-light menu-fixed menu-shadow');
                // menuContainer.removeClass().addClass('main-menu-content');
                menuNavigation.removeClass().addClass('navigation navigation-main');

                // If Dropdown Menu
                dropdownMenu.removeClass('dropdown').addClass('has-sub');
                dropdownMenu.find('a').removeClass('dropdown-toggle nav-link');
                dropdownMenu.children('ul').find('a').removeClass('dropdown-item');
                dropdownMenu.find('ul').removeClass('dropdown-menu');
                dropdownSubMenu.removeClass().addClass('has-sub');

                $.app.nav.init();

                // Dropdown submenu on small screen on click
                // --------------------------------------------------
                $(document).on('click', 'ul.dropdown-menu [data-toggle=dropdown]', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $(this).parent().siblings().removeClass('open');
                    $(this).parent().toggleClass('open');
                });

                $('.main-menu-content').find('li.active').parents('li').addClass('sidebar-group-active');

                $('.main-menu-content').find('li.active').closest('li.nav-item').addClass('open');
            }

            if (feather) {
                feather.replace({width: 14, height: 14});
            }
        },

        toggle: function () {
            var currentBreakpoint = Unison.fetch.now(); // Current Breakpoint
            var collapsed = this.collapsed;
            var expanded = this.expanded;
            var hidden = this.hidden;
            var menu = $('body').data('menu');

            switch (currentBreakpoint.name) {
                case 'xl':
                    if (expanded === true) {
                        if (menu == 'vertical-overlay-menu') {
                            this.hide();
                        } else {
                            this.collapse();
                        }
                    } else {
                        if (menu == 'vertical-overlay-menu') {
                            this.open();
                        } else {
                            this.expand();
                        }
                    }
                    break;
                case 'lg':
                    if (expanded === true) {
                        if (menu == 'vertical-overlay-menu' || menu == 'vertical-menu-modern' || menu == 'horizontal-menu') {
                            this.hide();
                        } else {
                            this.collapse();
                        }
                    } else {
                        if (menu == 'vertical-overlay-menu' || menu == 'vertical-menu-modern' || menu == 'horizontal-menu') {
                            this.open();
                        } else {
                            this.expand();
                        }
                    }
                    break;
                case 'md':
                case 'sm':
                    if (hidden === true) {
                        this.open();
                    } else {
                        this.hide();
                    }
                    break;
                case 'xs':
                    if (hidden === true) {
                        this.open();
                    } else {
                        this.hide();
                    }
                    break;
            }
        },

        update: function () {
            this.manualScroller.update();
        },

        reset: function () {
            this.expanded = false;
            this.collapsed = false;
            this.hidden = false;
            $('body').removeClass('menu-hide menu-open menu-collapsed menu-expanded');
        }
    };

    // Navigation Menu
    $.app.nav = {
        container: $('.navigation-main'),
        initialized: false,
        navItem: $('.navigation-main').find('li').not('.navigation-category'),
        TRANSITION_EVENTS: ['transitionend', 'webkitTransitionEnd', 'oTransitionEnd'],
        TRANSITION_PROPERTIES: ['transition', 'MozTransition', 'webkitTransition', 'WebkitTransition', 'OTransition'],

        config: {
            speed: 300
        },

        init: function (config) {
            this.initialized = true; // Set to true when initialized
            $.extend(this.config, config);

            this.bind_events();
        },

        bind_events: function () {
            var menuObj = this;

            $(document)
                .on('mouseenter.app.menu', '.navigation-main li', function () {
                    var $this = $(this);
                    // $('.hover', '.navigation-main').removeClass('hover');
                    if ($('body').hasClass('menu-collapsed') && $('body').data('menu') != 'vertical-menu-modern') {
                        $('.main-menu-content').children('span.menu-title').remove();
                        $('.main-menu-content').children('a.menu-title').remove();
                        $('.main-menu-content').children('ul.menu-content').remove();

                        // Title
                        var menuTitle = $this.find('span.menu-title').clone(),
                            tempTitle,
                            tempLink;
                        if (!$this.hasClass('has-sub')) {
                            tempTitle = $this.find('span.menu-title').text();
                            tempLink = $this.children('a').attr('href');
                            if (tempTitle !== '') {
                                menuTitle = $('<a>');
                                menuTitle.attr('href', tempLink);
                                menuTitle.attr('title', tempTitle);
                                menuTitle.text(tempTitle);
                                menuTitle.addClass('menu-title');
                            }
                        }
                        // menu_header_height = ($('.main-menu-header').length) ? $('.main-menu-header').height() : 0,
                        // fromTop = menu_header_height + $this.position().top + parseInt($this.css( "border-top" ),10);
                        var fromTop;
                        if ($this.css('border-top')) {
                            fromTop = $this.position().top + parseInt($this.css('border-top'), 10);
                        } else {
                            fromTop = $this.position().top;
                        }
                        if ($('body').data('menu') !== 'vertical-compact-menu') {
                            menuTitle.appendTo('.main-menu-content').css({
                                position: 'fixed',
                                top: fromTop
                            });
                        }

                        // Content
                        /* if ($this.hasClass('has-sub') && $this.hasClass('nav-item')) {
                         var menuContent = $this.children('ul:first');
                         menuObj.adjustSubmenu($this);
                         } */
                    }
                    // $this.addClass('hover');
                })
                .on('mouseleave.app.menu', '.navigation-main li', function () {
                    // $(this).removeClass('hover');
                })
                .on('active.app.menu', '.navigation-main li', function (e) {
                    $(this).addClass('active');
                    e.stopPropagation();
                })
                .on('deactive.app.menu', '.navigation-main li.active', function (e) {
                    $(this).removeClass('active');
                    e.stopPropagation();
                })
                .on('open.app.menu', '.navigation-main li', function (e) {
                    var $listItem = $(this);

                    menuObj.expand($listItem);
                    // $listItem.addClass('open');

                    // If menu collapsible then do not take any action
                    if ($('.main-menu').hasClass('menu-collapsible')) {
                        return false;
                    }
                    // If menu accordion then close all except clicked once
                    else {
                        $listItem.siblings('.open').find('li.open').trigger('close.app.menu');
                        $listItem.siblings('.open').trigger('close.app.menu');
                    }

                    e.stopPropagation();
                })
                .on('close.app.menu', '.navigation-main li.open', function (e) {
                    var $listItem = $(this);

                    menuObj.collapse($listItem);
                    // $listItem.removeClass('open');

                    e.stopPropagation();
                })
                .on('click.app.menu', '.navigation-main li', function (e) {
                    var $listItem = $(this);
                    if ($listItem.is('.disabled')) {
                        e.preventDefault();
                    } else {
                        if ($('body').hasClass('menu-collapsed') && $('body').data('menu') != 'vertical-menu-modern') {
                            e.preventDefault();
                        } else {
                            if ($listItem.has('ul').length) {
                                if ($listItem.is('.open')) {
                                    $listItem.trigger('close.app.menu');
                                } else {
                                    $listItem.trigger('open.app.menu');
                                }
                            } else {
                                if (!$listItem.is('.active')) {
                                    $listItem.siblings('.active').trigger('deactive.app.menu');
                                    $listItem.trigger('active.app.menu');
                                }
                            }
                        }
                    }

                    e.stopPropagation();
                });

            $(document)
                .on('mouseenter', '.navbar-header, .main-menu', modernMenuExpand)
                .on('mouseleave', '.navbar-header, .main-menu', modernMenuCollapse);

            function modernMenuExpand() {
                if ($('body').data('menu') == 'vertical-menu-modern') {
                    $('.main-menu, .navbar-header').addClass('expanded');
                    if ($('body').hasClass('menu-collapsed')) {
                        if ($('.main-menu li.open').length === 0) {
                            $('.main-menu-content').find('li.active').parents('li').addClass('open');
                        }
                        var $listItem = $('.main-menu li.menu-collapsed-open'),
                            $subList = $listItem.children('ul');

                        $subList.hide().slideDown(200, function () {
                            $(this).css('display', '');
                        });

                        $listItem.addClass('open').removeClass('menu-collapsed-open');
                        // $.app.menu.changeLogo('expand');
                    }
                }
            }

            function modernMenuCollapse() {
                if ($('body').hasClass('menu-collapsed') && $('body').data('menu') == 'vertical-menu-modern') {
                    setTimeout(function () {
                        if ($('.main-menu:hover').length === 0 && $('.navbar-header:hover').length === 0) {
                            $('.main-menu, .navbar-header').removeClass('expanded');
                            if ($('body').hasClass('menu-collapsed')) {
                                var $listItem = $('.main-menu li.open'),
                                    $subList = $listItem.children('ul');
                                $listItem.addClass('menu-collapsed-open');

                                $subList.show().slideUp(200, function () {
                                    $(this).css('display', '');
                                });

                                $listItem.removeClass('open');
                                // $.app.menu.changeLogo();
                            }
                        }
                    }, 1);
                }
            }

            $(document).on('mouseleave', '.main-menu-content', function () {
                if ($('body').hasClass('menu-collapsed')) {
                    $('.main-menu-content').children('span.menu-title').remove();
                    $('.main-menu-content').children('a.menu-title').remove();
                    $('.main-menu-content').children('ul.menu-content').remove();
                }
                $('.hover', '.navigation-main').removeClass('hover');
            });

            // If list item has sub menu items then prevent redirection.
            $(document).on('click', '.navigation-main li.has-sub > a', function (e) {
                e.preventDefault();
            });
        },

        /**
         * Ensure an admin submenu is within the visual viewport.
         * @param {jQuery} $menuItem The parent menu item containing the submenu.
         */

        /* adjustSubmenu: function ($menuItem) {
         var menuHeaderHeight,
         menutop,
         topPos,
         winHeight,
         bottomOffset,
         subMenuHeight,
         popOutMenuHeight,
         borderWidth,
         scroll_theme,
         $submenu = $menuItem.children('ul:first'),
         ul = $submenu.clone(true);

         menuHeaderHeight = $('.main-menu-header').height();
         menutop = $menuItem.position().top;
         winHeight = $window.height() - $('.header-navbar').height();
         borderWidth = 0;
         subMenuHeight = $submenu.height();

         if (parseInt($menuItem.css('border-top'), 10) > 0) {
         borderWidth = parseInt($menuItem.css('border-top'), 10);
         }

         popOutMenuHeight = winHeight - menutop - $menuItem.height() - 30;
         scroll_theme = $('.main-menu').hasClass('menu-dark') ? 'light' : 'dark';

         topPos = menutop + $menuItem.height() + borderWidth;

         ul.addClass('menu-popout').appendTo('.main-menu-content').css({
         top: topPos,
         position: 'fixed',
         'max-height': popOutMenuHeight
         });

         var menu_content = new PerfectScrollbar('.main-menu-content > ul.menu-content', {
         wheelPropagation: false
         });
         }, */

        // Collapse Submenu With Transition (Height animation)
        collapse: function ($listItem, callback) {
            var subList = $listItem.children('ul'),
                toggleLink = $listItem.children().first(),
                linkHeight = $(toggleLink).outerHeight();

            $listItem.css({
                height: linkHeight + subList.outerHeight() + 'px',
                overflow: 'hidden'
            });

            $listItem.addClass('menu-item-animating');
            $listItem.addClass('menu-item-closing');

            $.app.nav._bindAnimationEndEvent($listItem, function () {
                $listItem.removeClass('open');
                $.app.nav._clearItemStyle($listItem);
            });

            setTimeout(function () {
                $listItem.css({
                    height: linkHeight + 'px'
                });
            }, 50);
        },

        // Expand Submenu With Transition (Height animation)
        expand: function ($listItem, callback) {
            var subList = $listItem.children('ul'),
                toggleLink = $listItem.children().first(),
                linkHeight = $(toggleLink).outerHeight();

            $listItem.addClass('menu-item-animating');

            $listItem.css({
                overflow: 'hidden',
                height: linkHeight + 'px'
            });

            $listItem.addClass('open');

            $.app.nav._bindAnimationEndEvent($listItem, function () {
                $.app.nav._clearItemStyle($listItem);
            });

            setTimeout(function () {
                $listItem.css({
                    height: linkHeight + subList.outerHeight() + 'px'
                });
            }, 50);
        },

        _bindAnimationEndEvent(el, handler) {
            el = el[0];

            var cb = function (e) {
                if (e.target !== el) return;
                $.app.nav._unbindAnimationEndEvent(el);
                handler(e);
            };

            let duration = window.getComputedStyle(el).transitionDuration;
            duration = parseFloat(duration) * (duration.indexOf('ms') !== -1 ? 1 : 1000);

            el._menuAnimationEndEventCb = cb;
            $.app.nav.TRANSITION_EVENTS.forEach(function (ev) {
                el.addEventListener(ev, el._menuAnimationEndEventCb, false);
            });

            el._menuAnimationEndEventTimeout = setTimeout(function () {
                cb({target: el});
            }, duration + 50);
        },

        _unbindAnimationEndEvent(el) {
            var cb = el._menuAnimationEndEventCb;

            if (el._menuAnimationEndEventTimeout) {
                clearTimeout(el._menuAnimationEndEventTimeout);
                el._menuAnimationEndEventTimeout = null;
            }

            if (!cb) return;

            $.app.nav.TRANSITION_EVENTS.forEach(function (ev) {
                el.removeEventListener(ev, cb, false);
            });
            el._menuAnimationEndEventCb = null;
        },

        _clearItemStyle: function ($listItem) {
            $listItem.removeClass('menu-item-animating');
            $listItem.removeClass('menu-item-closing');
            $listItem.css({
                overflow: '',
                height: ''
            });
        },

        refresh: function () {
            $.app.nav.container.find('.open').removeClass('open');
        }
    };

    // We listen to the resize event
    window.addEventListener('resize', function () {
        // We execute the same script as before
        var vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', vh + 'px');
    });

    $(document).on('click', '.menu-toggle, .modern-nav-toggle', function (e) {
        e.preventDefault();

        // Toggle menu
        $.app.menu.toggle();

        setTimeout(function () {
            $(window).trigger('resize');
        }, 200);

        if ($('#collapse-sidebar-switch').length > 0) {
            setTimeout(function () {
                if ($('body').hasClass('menu-expanded') || $('body').hasClass('menu-open')) {
                    $('#collapse-sidebar-switch').prop('checked', false);
                } else {
                    $('#collapse-sidebar-switch').prop('checked', true);
                }
            }, 50);
        }

        // Hides dropdown on click of menu toggle
        // $('[data-toggle="dropdown"]').dropdown('hide');

        return false;
    });

    window.initSidebar();

});//end of document ready

window.initSidebar = () => {

    $(function () {

        var $html = $('html');
        var $body = $('body');
        var $textcolor = '#4e5154';
        var assetPath = '../../../app-assets/';


        var rtl;
        var compactMenu = false;

        if ($('body').hasClass('menu-collapsed')) {
            compactMenu = true;
        }

        if ($('html').data('textdirection') == 'rtl') {
            rtl = true;
        }

        setTimeout(function () {
            $html.removeClass('loading').addClass('loaded');
        }, 1200);

        $.app.menu.init(compactMenu);

        // Navigation configurations
        var config = {
            speed: 300 // set speed to expand / collpase menu
        };
        if ($.app.nav.initialized === false) {
            $.app.nav.init(config);
        }

        Unison.on('change', function (bp) {
            $.app.menu.change(compactMenu);
        });

        // Tooltip Initialization
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });

        // Collapsible Card
        $(document).on('click', 'a[data-action="collapse"]', function (e) {
            e.preventDefault();
            $(this).closest('.card').children('.card-content').collapse('toggle');
            $(this).closest('.card').find('[data-action="collapse"]').toggleClass('rotate');
        });

        // Cart dropdown touchspin
        if ($('.touchspin-cart').length > 0) {
            $('.touchspin-cart').TouchSpin({
                buttondown_class: 'btn btn-primary',
                buttonup_class: 'btn btn-primary',
                buttondown_txt: feather.icons['minus'].toSvg(),
                buttonup_txt: feather.icons['plus'].toSvg()
            });
        }

        // Do not close cart or notification dropdown on click of the items
        $(document).on('click', '.dropdown-notification .dropdown-menu, .dropdown-cart .dropdown-menu', function (e) {
            e.stopPropagation();
        });

        //  Notifications & messages scrollable
        $('.scrollable-container').each(function () {
            var scrollable_container = new PerfectScrollbar($(this)[0], {
                wheelPropagation: false
            });
        });

        // Reload Card
        $(document).on('click', 'a[data-action="reload"]', function () {
            var block_ele = $(this).closest('.card');
            var reloadActionOverlay;
            if ($html.hasClass('dark-layout')) {
                var reloadActionOverlay = '#10163a';
            } else {
                var reloadActionOverlay = '#fff';
            }
            // Block Element
            block_ele.block({
                message: feather.icons['refresh-cw'].toSvg({class: 'font-medium-1 spinner text-primary'}),
                timeout: 2000, //unblock after 2 seconds
                overlayCSS: {
                    backgroundColor: reloadActionOverlay,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none'
                }
            });
        });

        // Close Card
        $(document).on('click', 'a[data-action="close"]', function () {
            $(this).closest('.card').removeClass().slideUp('fast');
        });

        $(document).on('click', '.card .heading-elements a[data-action="collapse"]', function () {
            var $this = $(this),
                card = $this.closest('.card');
            var cardHeight;

            if (parseInt(card[0].style.height, 10) > 0) {
                cardHeight = card.css('height');
                card.css('height', '').attr('data-height', cardHeight);
            } else {
                if (card.data('height')) {
                    cardHeight = card.data('height');
                    card.css('height', cardHeight).attr('data-height', '');
                }
            }
        });

        // Add disabled class to input group when input is disabled
        $('input:disabled, textarea:disabled').closest('.input-group').addClass('disabled');

        // Add sidebar group active class to active menu
        $('.main-menu-content').find('li.active').parents('li').addClass('sidebar-group-active');

        // Add open class to parent list item if subitem is active except compact menu
        var menuType = $('body').data('menu');
        if (menuType != 'horizontal-menu' && compactMenu === false) {
            $('.main-menu-content').find('li.active').parents('li').addClass('open');
        }
        if (menuType == 'horizontal-menu') {
            $('.main-menu-content').find('li.active').parents('li:not(.nav-item)').addClass('open');
            $('.main-menu-content').find('li.active').closest('li.nav-item').addClass('sidebar-group-active open');
            // $(".main-menu-content")
            //   .find("li.active")
            //   .parents("li")
            //   .addClass("active");
        }

        //  Dynamic height for the chartjs div for the chart animations to work
        var chartjsDiv = $('.chartjs'),
            canvasHeight = chartjsDiv.children('canvas').attr('height'),
            mainMenu = $('.main-menu');
        chartjsDiv.css('height', canvasHeight);

        if ($('body').hasClass('boxed-layout')) {
            if ($('body').hasClass('vertical-overlay-menu')) {
                var menuWidth = mainMenu.width();
                var contentPosition = $('.app-content').position().left;
                var menuPositionAdjust = contentPosition - menuWidth;
                if ($('body').hasClass('menu-flipped')) {
                    mainMenu.css('right', menuPositionAdjust + 'px');
                } else {
                    mainMenu.css('left', menuPositionAdjust + 'px');
                }
            }
        }

        //Custom File Input
        $('.custom-file-input').on('change', function (e) {
            $(this).siblings('.custom-file-label').html(e.target.files[0].name);
        });

        /* Text Area Counter Set Start */
        $('.char-textarea').on('keyup', function (event) {
            checkTextAreaMaxLength(this, event);
            // to later change text color in dark layout
            $(this).addClass('active');
        });

        /*
         Checks the MaxLength of the Textarea
         -----------------------------------------------------
         @prerequisite:  textBox = textarea dom element
         e = textarea event
         length = Max length of characters
         */
        function checkTextAreaMaxLength(textBox, e) {
            var maxLength = parseInt($(textBox).data('length')),
                counterValue = $('.textarea-counter-value'),
                charTextarea = $('.char-textarea');

            if (!checkSpecialKeys(e)) {
                if (textBox.value.length < maxLength - 1) textBox.value = textBox.value.substring(0, maxLength);
            }
            $('.char-count').html(textBox.value.length);

            if (textBox.value.length > maxLength) {
                counterValue.css('background-color', window.colors.solid.danger);
                charTextarea.css('color', window.colors.solid.danger);
                // to change text color after limit is maxedout out
                charTextarea.addClass('max-limit');
            } else {
                counterValue.css('background-color', window.colors.solid.primary);
                charTextarea.css('color', $textcolor);
                charTextarea.removeClass('max-limit');
            }

            return true;
        }

        /*
         Checks if the keyCode pressed is inside special chars
         -------------------------------------------------------
         @prerequisite:  e = e.keyCode object for the key pressed
         */
        function checkSpecialKeys(e) {
            if (e.keyCode != 8 && e.keyCode != 46 && e.keyCode != 37 && e.keyCode != 38 && e.keyCode != 39 && e.keyCode != 40)
                return false;
            else return true;
        }

        $(document).on('click', '.content-overlay', function () {
            $('.search-list').removeClass('show');
            var searchInput = $('.search-input-close').closest('.search-input');
            if (searchInput.hasClass('open')) {
                searchInput.removeClass('open');
                searchInputInputfield.val('');
                searchInputInputfield.blur();
                searchList.removeClass('show');
            }

            $('.app-content').removeClass('show-overlay');
            $('.bookmark-wrapper .bookmark-input').removeClass('show');
        });

        // To show shadow in main menu when menu scrolls
        var container = document.getElementsByClassName('main-menu-content');
        if (container.length > 0) {
            container[0].addEventListener('ps-scroll-y', function () {
                if ($(this).find('.ps__thumb-y').position().top > 0) {
                    // $('.shadow-bottom').css('display', 'block');
                } else {
                    // $('.shadow-bottom').css('display', 'none');
                }
            });
        }

        // Hide overlay menu on content overlay click on small screens
        $(document).on('click', '.sidenav-overlay', function (e) {
            // Hide menu
            $.app.menu.hide();
            return false;
        });

        // Execute below code only if we find hammer js for touch swipe feature on small screen
        if (typeof Hammer !== 'undefined') {
            var rtl;
            if ($('html').data('textdirection') == 'rtl') {
                rtl = true;
            }

            // Swipe menu gesture
            var swipeInElement = document.querySelector('.drag-target'),
                swipeInAction = 'panright',
                swipeOutAction = 'panleft';

            if (rtl === true) {
                swipeInAction = 'panleft';
                swipeOutAction = 'panright';
            }

            if ($(swipeInElement).length > 0) {
                var swipeInMenu = new Hammer(swipeInElement);

                swipeInMenu.on(swipeInAction, function (ev) {
                    if ($('body').hasClass('vertical-overlay-menu')) {
                        $.app.menu.open();
                        return false;
                    }
                });
            }

            // menu swipe out gesture
            setTimeout(function () {
                var swipeOutElement = document.querySelector('.main-menu');
                var swipeOutMenu;

                if ($(swipeOutElement).length > 0) {
                    swipeOutMenu = new Hammer(swipeOutElement);

                    swipeOutMenu.get('pan').set({
                        direction: Hammer.DIRECTION_ALL,
                        threshold: 250
                    });

                    swipeOutMenu.on(swipeOutAction, function (ev) {
                        if ($('body').hasClass('vertical-overlay-menu')) {
                            $.app.menu.hide();
                            return false;
                        }
                    });
                }
            }, 300);

            // menu close on overlay tap
            var swipeOutOverlayElement = document.querySelector('.sidenav-overlay');

            if ($(swipeOutOverlayElement).length > 0) {
                var swipeOutOverlayMenu = new Hammer(swipeOutOverlayElement);

                swipeOutOverlayMenu.on('tap', function (ev) {
                    if ($('body').hasClass('vertical-overlay-menu')) {
                        $.app.menu.hide();
                        return false;
                    }
                });
            }
        }

        // Add Children Class
        $('.navigation').find('li').has('ul').addClass('has-sub');

        $('.carousel').carousel({
            interval: 2000
        });

        // Update manual scroller when window is resized
        $(window).resize(function () {
            $.app.menu.manualScroller.updateHeight();
        });

        $(document).on('click', '#sidebar-page-navigation a.nav-link', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $this = $(this),
                href = $this.attr('href');
            var offset = $(href).offset();
            var scrollto = offset.top - 80; // minus fixed header height
            $('html, body').animate(
                {
                    scrollTop: scrollto
                },
                0
            );
            setTimeout(function () {
                $this.parent('.nav-item').siblings('.nav-item').children('.nav-link').removeClass('active');
                $this.addClass('active');
            }, 100);
        });

        // main menu internationalization

        // init i18n and load language file
        if ($('body').attr('data-framework') === 'laravel') {
            // change language according to data-language of dropdown item
            var language = $('html')[0].lang;
            if (language !== null) {
                // get the selected flag class
                var selectedLang = $('.dropdown-language')
                    .find('a[data-language=' + language + ']')
                    .text();
                var selectedFlag = $('.dropdown-language')
                    .find('a[data-language=' + language + '] .flag-icon')
                    .attr('class');
                // set the class in button
                $('#dropdown-flag .selected-language').text(selectedLang);
                $('#dropdown-flag .flag-icon').removeClass().addClass(selectedFlag);
            }
        } else {
            // i18next.use(window.i18nextXHRBackend).init(
            //     {
            //         debug: false,
            //         fallbackLng: 'en',
            //         backend: {
            //             loadPath: assetPath + 'data/locales/{{lng}}.json'
            //         },
            //         returnObjects: true
            //     },
            //     function (err, t) {
            //         // resources have been loaded
            //         jqueryI18next.init(i18next, $);
            //     }
            // );

            // change language according to data-language of dropdown item
            $(document).on('click', '.dropdown-language .dropdown-item', function () {
                var $this = $(this);
                $this.siblings('.selected').removeClass('selected');
                $this.addClass('selected');
                var selectedLang = $this.text();
                var selectedFlag = $this.find('.flag-icon').attr('class');
                $('#dropdown-flag .selected-language').text(selectedLang);
                $('#dropdown-flag .flag-icon').removeClass().addClass(selectedFlag);
                var currentLanguage = $this.data('language');
                i18next.changeLanguage(currentLanguage, function (err, t) {
                    $('.main-menu, .horizontal-menu-wrapper').localize();
                });
            });
        }

        /********************* Bookmark & Search ***********************/
            // This variable is used for mouseenter and mouseleave events of search list
        var $filename = $('.search-input input').data('search'),
            bookmarkWrapper = $('.bookmark-wrapper'),
            bookmarkStar = $('.bookmark-wrapper .bookmark-star'),
            bookmarkInput = $('.bookmark-wrapper .bookmark-input'),
            navLinkSearch = $('.nav-link-search'),
            searchInput = $('.search-input'),
            searchInputInputfield = $('.search-input input'),
            searchList = $('.search-input .search-list'),
            appContent = $('.app-content'),
            bookmarkSearchList = $('.bookmark-input .search-list');

        // Bookmark icon click
        bookmarkStar.on('click', function (e) {
            e.stopPropagation();
            bookmarkInput.toggleClass('show');
            bookmarkInput.find('input').val('');
            bookmarkInput.find('input').blur();
            bookmarkInput.find('input').focus();
            bookmarkWrapper.find('.search-list').addClass('show');

            var arrList = $('ul.nav.navbar-nav.bookmark-icons li'),
                $arrList = '',
                $activeItemClass = '';

            $('ul.search-list li').remove();

            for (var i = 0; i < arrList.length; i++) {
                if (i === 0) {
                    $activeItemClass = 'current_item';
                } else {
                    $activeItemClass = '';
                }

                var iconName = '',
                    className = '';
                if ($(arrList[i].firstChild.firstChild).hasClass('feather')) {
                    var classString = arrList[i].firstChild.firstChild.getAttribute('class');
                    iconName = classString.split('feather-')[1].split(' ')[0];
                    className = classString.split('feather-')[1].split(' ')[1];
                }

                $arrList +=
                    '<li class="auto-suggestion ' +
                    $activeItemClass +
                    '">' +
                    '<a class="d-flex align-items-center justify-content-between w-100" href=' +
                    arrList[i].firstChild.href +
                    '>' +
                    '<div class="d-flex justify-content-start align-items-center">' +
                    feather.icons[iconName].toSvg({class: 'mr-75 ' + className}) +
                    '<span>' +
                    arrList[i].firstChild.dataset.originalTitle +
                    '</span>' +
                    '</div>' +
                    feather.icons['star'].toSvg({class: 'text-warning bookmark-icon float-right'}) +
                    '</a>' +
                    '</li>';
            }
            $('ul.search-list').append($arrList);
        });

        // Navigation Search area Open
        navLinkSearch.on('click', function () {
            var $this = $(this);
            var searchInput = $(this).parent('.nav-search').find('.search-input');
            searchInput.addClass('open');
            searchInputInputfield.focus();
            searchList.find('li').remove();
            bookmarkInput.removeClass('show');
        });

        // Navigation Search area Close
        $(document).on('click', '.search-input-close', function () {
            var $this = $(this),
                searchInput = $(this).closest('.search-input');
            if (searchInput.hasClass('open')) {
                searchInput.removeClass('open');
                searchInputInputfield.val('');
                searchInputInputfield.blur();
                searchList.removeClass('show');
                appContent.removeClass('show-overlay');
            }
        });

        // Filter
        if ($('.search-list-main').length) {
            var searchListMain = new PerfectScrollbar('.search-list-main', {
                wheelPropagation: false
            });
        }
        if ($('.search-list-bookmark').length) {
            var searchListBookmark = new PerfectScrollbar('.search-list-bookmark', {
                wheelPropagation: false
            });
        }
        // update Perfect Scrollbar on hover
        $(document).on('mouseenter', '.search-list-main', function () {
            searchListMain.update();
        });

        searchInputInputfield.on('keyup', function (e) {
            $(this).closest('.search-list').addClass('show');
            if (e.keyCode !== 38 && e.keyCode !== 40 && e.keyCode !== 13) {
                if (e.keyCode == 27) {
                    appContent.removeClass('show-overlay');
                    bookmarkInput.find('input').val('');
                    bookmarkInput.find('input').blur();
                    searchInputInputfield.val('');
                    searchInputInputfield.blur();
                    searchInput.removeClass('open');
                    if (searchInput.hasClass('show')) {
                        $(this).removeClass('show');
                        searchInput.removeClass('show');
                    }
                }

                // Define variables
                var value = $(this).val().toLowerCase(), //get values of input on keyup
                    activeClass = '',
                    bookmark = false,
                    liList = $('ul.search-list li'); // get all the list items of the search
                liList.remove();
                // To check if current is bookmark input
                if ($(this).parent().hasClass('bookmark-input')) {
                    bookmark = true;
                }

                // If input value is blank
                if (value != '') {
                    appContent.addClass('show-overlay');

                    // condition for bookmark and search input click
                    if (bookmarkInput.focus()) {
                        bookmarkSearchList.addClass('show');
                    } else {
                        searchList.addClass('show');
                        bookmarkSearchList.removeClass('show');
                    }
                    if (bookmark === false) {
                        searchList.addClass('show');
                        bookmarkSearchList.removeClass('show');
                    }

                    var $startList = '',
                        $otherList = '',
                        $htmlList = '',
                        $bookmarkhtmlList = '',
                        $pageList =
                            '<li class="d-flex align-items-center">' +
                            '<a href="javascript:void(0)">' +
                            '<h6 class="section-label mt-75 mb-0">Pages</h6>' +
                            '</a>' +
                            '</li>',
                        $activeItemClass = '',
                        $bookmarkIcon = '',
                        $defaultList = '',
                        a = 0;

                    // getting json data from file for search results
                    $.getJSON(assetPath + 'data/' + $filename + '.json', function (data) {
                        for (var i = 0; i < data.listItems.length; i++) {
                            // if current is bookmark then give class to star icon
                            // for laravel
                            if ($('body').attr('data-framework') === 'laravel') {
                                data.listItems[i].url = assetPath + data.listItems[i].url;
                            }

                            if (bookmark === true) {
                                activeClass = ''; // resetting active bookmark class
                                var arrList = $('ul.nav.navbar-nav.bookmark-icons li'),
                                    $arrList = '';
                                // Loop to check if current seach value match with the bookmarks already there in navbar
                                for (var j = 0; j < arrList.length; j++) {
                                    if (data.listItems[i].name === arrList[j].firstChild.dataset.originalTitle) {
                                        activeClass = ' text-warning';
                                        break;
                                    } else {
                                        activeClass = '';
                                    }
                                }

                                $bookmarkIcon = feather.icons['star'].toSvg({class: 'bookmark-icon float-right' + activeClass});
                            }
                            // Search list item start with entered letters and create list
                            if (data.listItems[i].name.toLowerCase().indexOf(value) == 0 && a < 5) {
                                if (a === 0) {
                                    $activeItemClass = 'current_item';
                                } else {
                                    $activeItemClass = '';
                                }
                                $startList +=
                                    '<li class="auto-suggestion ' +
                                    $activeItemClass +
                                    '">' +
                                    '<a class="d-flex align-items-center justify-content-between w-100" href=' +
                                    data.listItems[i].url +
                                    '>' +
                                    '<div class="d-flex justify-content-start align-items-center">' +
                                    feather.icons[data.listItems[i].icon].toSvg({class: 'mr-75 '}) +
                                    '<span>' +
                                    data.listItems[i].name +
                                    '</span>' +
                                    '</div>' +
                                    $bookmarkIcon +
                                    '</a>' +
                                    '</li>';
                                a++;
                            }
                        }
                        for (var i = 0; i < data.listItems.length; i++) {
                            if (bookmark === true) {
                                activeClass = ''; // resetting active bookmark class
                                var arrList = $('ul.nav.navbar-nav.bookmark-icons li'),
                                    $arrList = '';
                                // Loop to check if current search value match with the bookmarks already there in navbar
                                for (var j = 0; j < arrList.length; j++) {
                                    if (data.listItems[i].name === arrList[j].firstChild.dataset.originalTitle) {
                                        activeClass = ' text-warning';
                                    } else {
                                        activeClass = '';
                                    }
                                }

                                $bookmarkIcon = feather.icons['star'].toSvg({class: 'bookmark-icon float-right' + activeClass});
                            }
                            // Search list item not start with letters and create list
                            if (
                                !(data.listItems[i].name.toLowerCase().indexOf(value) == 0) &&
                                data.listItems[i].name.toLowerCase().indexOf(value) > -1 &&
                                a < 5
                            ) {
                                if (a === 0) {
                                    $activeItemClass = 'current_item';
                                } else {
                                    $activeItemClass = '';
                                }
                                $otherList +=
                                    '<li class="auto-suggestion ' +
                                    $activeItemClass +
                                    '">' +
                                    '<a class="d-flex align-items-center justify-content-between w-100" href=' +
                                    data.listItems[i].url +
                                    '>' +
                                    '<div class="d-flex justify-content-start align-items-center">' +
                                    feather.icons[data.listItems[i].icon].toSvg({class: 'mr-75 '}) +
                                    '<span>' +
                                    data.listItems[i].name +
                                    '</span>' +
                                    '</div>' +
                                    $bookmarkIcon +
                                    '</a>' +
                                    '</li>';
                                a++;
                            }
                        }
                        $defaultList = $('.main-search-list-defaultlist').html();
                        if ($startList == '' && $otherList == '') {
                            $otherList = $('.main-search-list-defaultlist-other-list').html();
                        }
                        // concatinating startlist, otherlist, defalutlist with pagelist
                        $htmlList = $pageList.concat($startList, $otherList, $defaultList);
                        $('ul.search-list').html($htmlList);
                        // concatinating otherlist with startlist
                        $bookmarkhtmlList = $startList.concat($otherList);
                        $('ul.search-list-bookmark').html($bookmarkhtmlList);
                        // Feather Icons
                        // if (feather) {
                        //   featherSVG();
                        // }
                    });
                } else {
                    if (bookmark === true) {
                        var arrList = $('ul.nav.navbar-nav.bookmark-icons li'),
                            $arrList = '';
                        for (var i = 0; i < arrList.length; i++) {
                            if (i === 0) {
                                $activeItemClass = 'current_item';
                            } else {
                                $activeItemClass = '';
                            }

                            var iconName = '',
                                className = '';
                            if ($(arrList[i].firstChild.firstChild).hasClass('feather')) {
                                var classString = arrList[i].firstChild.firstChild.getAttribute('class');
                                iconName = classString.split('feather-')[1].split(' ')[0];
                                className = classString.split('feather-')[1].split(' ')[1];
                            }
                            $arrList +=
                                '<li class="auto-suggestion">' +
                                '<a class="d-flex align-items-center justify-content-between w-100" href=' +
                                arrList[i].firstChild.href +
                                '>' +
                                '<div class="d-flex justify-content-start align-items-center">' +
                                feather.icons[iconName].toSvg({class: 'mr-75 '}) +
                                '<span>' +
                                arrList[i].firstChild.dataset.originalTitle +
                                '</span>' +
                                '</div>' +
                                feather.icons['star'].toSvg({class: 'text-warning bookmark-icon float-right'}) +
                                '</a>' +
                                '</li>';
                        }
                        $('ul.search-list').append($arrList);
                        // Feather Icons
                        // if (feather) {
                        //   featherSVG();
                        // }
                    } else {
                        // if search input blank, hide overlay
                        if (appContent.hasClass('show-overlay')) {
                            appContent.removeClass('show-overlay');
                        }
                        // If filter box is empty
                        if (searchList.hasClass('show')) {
                            searchList.removeClass('show');
                        }
                    }
                }
            }
        });

        // Add class on hover of the list
        $(document).on('mouseenter', '.search-list li', function (e) {
            $(this).siblings().removeClass('current_item');
            $(this).addClass('current_item');
        });
        $(document).on('click', '.search-list li', function (e) {
            e.stopPropagation();
        });

        $(document).on('click', 'html', function ($this) {
            if (!$($this.target).hasClass('bookmark-icon')) {
                if (bookmarkSearchList.hasClass('show')) {
                    bookmarkSearchList.removeClass('show');
                }
                if (bookmarkInput.hasClass('show')) {
                    bookmarkInput.removeClass('show');
                }
            }
        });

        // Prevent closing bookmark dropdown on input textbox click
        $(document).on('click', '.bookmark-input input', function (e) {
            bookmarkInput.addClass('show');
            bookmarkSearchList.addClass('show');
        });

        // Favorite star click
        $(document).on('click', '.bookmark-input .search-list .bookmark-icon', function (e) {
            e.stopPropagation();
            if ($(this).hasClass('text-warning')) {
                $(this).removeClass('text-warning');
                var arrList = $('ul.nav.navbar-nav.bookmark-icons li');
                for (var i = 0; i < arrList.length; i++) {
                    if (arrList[i].firstChild.dataset.originalTitle == $(this).parent()[0].innerText) {
                        arrList[i].remove();
                    }
                }
                e.preventDefault();
            } else {
                var arrList = $('ul.nav.navbar-nav.bookmark-icons li');
                $(this).addClass('text-warning');
                e.preventDefault();
                var $url = $(this).parent()[0].href,
                    $name = $(this).parent()[0].innerText,
                    $listItem = '',
                    $listItemDropdown = '',
                    iconName = $(this).parent()[0].firstChild.firstChild.dataset.icon;
                if ($($(this).parent()[0].firstChild.firstChild).hasClass('feather')) {
                    var classString = $(this).parent()[0].firstChild.firstChild.getAttribute('class');
                    iconName = classString.split('feather-')[1].split(' ')[0];
                }
                $listItem =
                    '<li class="nav-item d-none d-lg-block">' +
                    '<a class="nav-link" href="' +
                    $url +
                    '" data-toggle="tooltip" data-placement="top" title="" data-original-title="' +
                    $name +
                    '">' +
                    feather.icons[iconName].toSvg({class: 'ficon'}) +
                    '</a>' +
                    '</li>';
                $('ul.nav.bookmark-icons').append($listItem);
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        // If we use up key(38) Down key (40) or Enter key(13)
        $(window).on('keydown', function (e) {
            var $current = $('.search-list li.current_item'),
                $next,
                $prev;
            if (e.keyCode === 40) {
                $next = $current.next();
                $current.removeClass('current_item');
                $current = $next.addClass('current_item');
            } else if (e.keyCode === 38) {
                $prev = $current.prev();
                $current.removeClass('current_item');
                $current = $prev.addClass('current_item');
            }

            if (e.keyCode === 13 && $('.search-list li.current_item').length > 0) {
                var selected_item = $('.search-list li.current_item a');
                window.location = selected_item.attr('href');
                $(selected_item).trigger('click');
            }
        });

        // Waves Effect
        Waves.init();
        Waves.attach(
            ".btn:not([class*='btn-relief-']):not([class*='btn-gradient-']):not([class*='btn-outline-']):not([class*='btn-flat-'])",
            ['waves-float', 'waves-light']
        );
        Waves.attach("[class*='btn-outline-']");
        Waves.attach("[class*='btn-flat-']");

        $(document).on('click', '.form-password-toggle .input-group-text', function (e) {
            e.preventDefault();
            var $this = $(this),
                inputGroupText = $this.closest('.form-password-toggle'),
                formPasswordToggleIcon = $this,
                formPasswordToggleInput = inputGroupText.find('input');

            if (formPasswordToggleInput.attr('type') === 'text') {
                formPasswordToggleInput.attr('type', 'password');
                if (feather) {
                    formPasswordToggleIcon.find('svg').replaceWith(feather.icons['eye'].toSvg({class: 'font-small-4'}));
                }
            } else if (formPasswordToggleInput.attr('type') === 'password') {
                formPasswordToggleInput.attr('type', 'text');
                if (feather) {
                    formPasswordToggleIcon.find('svg').replaceWith(feather.icons['eye-off'].toSvg({class: 'font-small-4'}));
                }
            }
        });

        // on window scroll button show/hide
        $(window).on('scroll', function () {
            if ($(this).scrollTop() > 400) {
                $('.scroll-top').fadeIn();
            } else {
                $('.scroll-top').fadeOut();
            }

            // On Scroll navbar color on horizontal menu
            if ($('body').hasClass('navbar-static')) {
                var scroll = $(window).scrollTop();

                if (scroll > 65) {
                    $('html:not(.dark-layout) .horizontal-menu .header-navbar.navbar-fixed').css({
                        background: '#fff',
                        'box-shadow': '0 4px 20px 0 rgba(0,0,0,.05)'
                    });
                    $('.horizontal-menu.dark-layout .header-navbar.navbar-fixed').css({
                        background: '#161d31',
                        'box-shadow': '0 4px 20px 0 rgba(0,0,0,.05)'
                    });
                    $('html:not(.dark-layout) .horizontal-menu .horizontal-menu-wrapper.header-navbar').css('background', '#fff');
                    $('.dark-layout .horizontal-menu .horizontal-menu-wrapper.header-navbar').css('background', '#161d31');
                } else {
                    $('html:not(.dark-layout) .horizontal-menu .header-navbar.navbar-fixed').css({
                        background: '#f8f8f8',
                        'box-shadow': 'none'
                    });
                    $('.dark-layout .horizontal-menu .header-navbar.navbar-fixed').css({
                        background: '#161d31',
                        'box-shadow': 'none'
                    });
                    $('html:not(.dark-layout) .horizontal-menu .horizontal-menu-wrapper.header-navbar').css('background', '#fff');
                    $('.dark-layout .horizontal-menu .horizontal-menu-wrapper.header-navbar').css('background', '#161d31');
                }
            }
        });

        // Click event to scroll to top
        $(document).on('click', '.scroll-top', function () {
            $('html, body').animate({scrollTop: 0}, 1000);
        });

        function getCurrentLayout() {
            var currentLayout = '';
            if ($html.hasClass('dark-layout')) {
                currentLayout = 'dark-layout';
            } else if ($html.hasClass('bordered-layout')) {
                currentLayout = 'bordered-layout';
            } else if ($html.hasClass('semi-dark-layout')) {
                currentLayout = 'semi-dark-layout';
            } else {
                currentLayout = 'light-layout';
            }
            return currentLayout;
        }

        // Get the data layout, for blank set to light layout
        var dataLayout = $html.attr('data-layout') ? $html.attr('data-layout') : 'light-layout';

        // Navbar Dark / Light Layout Toggle Switch
        // $(document).on('click', '.nav-link-style', function (e) {
        //     var currentLayout = getCurrentLayout(),
        //         switchToLayout = '',
        //         prevLayout = localStorage.getItem(dataLayout + '-prev-skin', currentLayout);
        //
        //     // If currentLayout is not dark layout
        //     if (currentLayout !== 'dark-layout') {
        //         // Switch to dark
        //         switchToLayout = 'dark-layout';
        //     } else {
        //         // Switch to light
        //         switchToLayout = prevLayout ? prevLayout : 'light-layout';
        //     }
        //
        //     let url = $('meta[name="layout-switch-route"]').attr('content')
        //
        //     $.ajax({
        //         url: url,
        //         data: {
        //             'layout': switchToLayout
        //         },
        //         //processData: false,
        //         //contentType: false,
        //         cache: false,
        //         success: function () {
        //
        //         },
        //     });//end of ajax call
        //
        //
        //     // Set Previous skin in local db
        //     // localStorage.setItem(dataLayout + '-prev-skin', currentLayout);
        //     // Set Current skin in local db
        //     // localStorage.setItem(dataLayout + '-current-skin', switchToLayout);
        //
        //     // Call set layout
        //     setLayout(switchToLayout);
        //
        //     // ToDo: Customizer fix
        //     $('.horizontal-menu .header-navbar.navbar-fixed').css({
        //         background: 'inherit',
        //         'box-shadow': 'inherit'
        //     });
        //     $('.horizontal-menu .horizontal-menu-wrapper.header-navbar').css('background', 'inherit');
        // });

        // Get current local storage layout
        var currentLocalStorageLayout = localStorage.getItem(dataLayout + '-current-skin');

        // Set layout on screen load
        //? Comment it if you don't want to sync layout with local db
        // setLayout(currentLocalStorageLayout);

        function setLayout(currentLocalStorageLayout) {
            var navLinkStyle = $('.nav-link-style'),
                currentLayout = getCurrentLayout(),
                mainMenu = $('.main-menu'),
                navbar = $('.header-navbar'),
                // Witch to local storage layout if we have else current layout
                switchToLayout = currentLocalStorageLayout ? currentLocalStorageLayout : currentLayout;

            $html.removeClass('semi-dark-layout dark-layout bordered-layout');

            if (switchToLayout === 'dark-layout') {
                $html.addClass('dark-layout');
                mainMenu.removeClass('menu-light').addClass('menu-dark');
                navbar.removeClass('navbar-light').addClass('navbar-dark');
                navLinkStyle.find('.ficon').replaceWith(feather.icons['sun'].toSvg({class: 'ficon'}));
            } else if (switchToLayout === 'bordered-layout') {
                $html.addClass('bordered-layout');
                mainMenu.removeClass('menu-dark').addClass('menu-light');
                navbar.removeClass('navbar-dark').addClass('navbar-light');
                navLinkStyle.find('.ficon').replaceWith(feather.icons['moon'].toSvg({class: 'ficon'}));
            } else if (switchToLayout === 'semi-dark-layout') {
                $html.addClass('semi-dark-layout');
                mainMenu.removeClass('menu-dark').addClass('menu-light');
                navbar.removeClass('navbar-dark').addClass('navbar-light');
                navLinkStyle.find('.ficon').replaceWith(feather.icons['moon'].toSvg({class: 'ficon'}));
            } else {
                $html.addClass('light-layout');
                mainMenu.removeClass('menu-dark').addClass('menu-light');
                navbar.removeClass('navbar-dark').addClass('navbar-light');
                navLinkStyle.find('.ficon').replaceWith(feather.icons['moon'].toSvg({class: 'ficon'}));
            }
            // Set radio in customizer if we have
            if ($('input:radio[data-layout=' + switchToLayout + ']').length > 0) {
                setTimeout(function () {
                    $('input:radio[data-layout=' + switchToLayout + ']').prop('checked', true);
                });
            }
        }
    });

    // To use feather svg icons with different sizes
    function featherSVG(iconSize) {
        // Feather Icons
        if (iconSize == undefined) {
            iconSize = '14';
        }
        return feather.replace({width: iconSize, height: iconSize});
    }

    // jQuery Validation Global Defaults
    if (typeof jQuery.validator === 'function') {
        jQuery.validator.setDefaults({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                if (
                    element.parent().hasClass('input-group') ||
                    element.hasClass('select2') ||
                    element.attr('type') === 'checkbox'
                ) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('custom-control-input')) {
                    error.insertAfter(element.parent().siblings(':last'));
                } else {
                    error.insertAfter(element);
                }

                if (element.parent().hasClass('input-group')) {
                    element.parent().addClass('is-invalid');
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('error');
                if ($(element).parent().hasClass('input-group')) {
                    $(element).parent().addClass('is-invalid');
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('error');
                if ($(element).parent().hasClass('input-group')) {
                    $(element).parent().removeClass('is-invalid');
                }
            }
        });
    }

    // Add validation class to input-group (input group validation fix, currently disabled but will be useful in future)
    /* function inputGroupValidation(el) {
     var validEl,
     invalidEl,
     elem = $(el);

     if (elem.hasClass('form-control')) {
     if ($(elem).is('.form-control:valid, .form-control.is-valid')) {
     validEl = elem;
     }
     if ($(elem).is('.form-control:invalid, .form-control.is-invalid')) {
     invalidEl = elem;
     }
     } else {
     validEl = elem.find('.form-control:valid, .form-control.is-valid');
     invalidEl = elem.find('.form-control:invalid, .form-control.is-invalid');
     }
     if (validEl !== undefined) {
     validEl.closest('.input-group').removeClass('.is-valid is-invalid').addClass('is-valid');
     }
     if (invalidEl !== undefined) {
     invalidEl.closest('.input-group').removeClass('.is-valid is-invalid').addClass('is-invalid');
     }
     } */


}

