$(function () {
    "use strict";

    //**  Carousel Functions Here **//
    function carousel() {
        $(".carousel").not(".slick-initialized").each(function () {
            var $this = $(this);

            var slidesPerViewXs = $this.data("xs-items");
            var slidesPerViewSm = $this.data("sm-items");
            var slidesPerViewMd = $this.data("md-items");
            var slidesPerViewLg = $this.data("lg-items");
            var slidesPerViewXl = $this.data("xl-items");
            var slidesPerView = $this.data("items");

            var slidesCenterMode = $this.data("center");
            var slidesArrows = $this.data("arrows");
            var slidesDots = $this.data("dots");
            var slidesRows = $this.data("rows");
            var slidesAutoplay = $this.data("autoplay");
            var slidesFade = $this.data("fade");
            var asNavFor = $this.data("nav-for");
            var infinite = $this.data("infinite");
            var focusOnSelect = $this.data("focus-select");
            var adaptiveHeight = $this.data("auto-height");
            var centerPadding = $this.data("center-padding");


            var vertical = $this.data("vertical");
            var verticalXs = $this.data("vertical-xs");
            var verticalSm = $this.data("vertical-sm");
            var verticalMd = $this.data("vertical-md");
            var verticalLg = $this.data("vertical-lg");
            var verticalXl = $this.data("vertical-xl");

            var timeout = $this.data("timeout");
            timeout = !timeout ? 3000 : timeout;

            var slidescroll = $this.data("slidescroll");
            slidescroll = !slidescroll ? 1 : slidescroll;

            slidesPerView = !slidesPerView ? 1 : slidesPerView;
            slidesPerViewXl = !slidesPerViewXl ? slidesPerView : slidesPerViewXl;
            slidesPerViewLg = !slidesPerViewLg ? slidesPerViewXl : slidesPerViewLg;
            slidesPerViewMd = !slidesPerViewMd ? slidesPerViewLg : slidesPerViewMd;
            slidesPerViewSm = !slidesPerViewSm ? slidesPerViewMd : slidesPerViewSm;
            slidesPerViewXs = !slidesPerViewXs ? slidesPerViewSm : slidesPerViewXs;


            vertical = !vertical ? false : vertical;
            verticalXl = (typeof verticalXl == 'undefined') ? vertical : verticalXl;
            verticalLg = (typeof verticalLg == 'undefined') ? verticalXl : verticalLg;
            verticalMd = (typeof verticalMd == 'undefined') ? verticalLg : verticalMd;
            verticalSm = (typeof verticalSm == 'undefined') ? verticalMd : verticalSm;
            verticalXs = (typeof verticalXs == 'undefined') ? verticalSm : verticalXs;


            slidesCenterMode = !slidesCenterMode ? false : slidesCenterMode;
            slidesArrows = !slidesArrows ? false : slidesArrows;
            slidesDots = !slidesDots ? false : slidesDots;
            slidesRows = !slidesRows ? 1 : slidesRows;
            slidesAutoplay = !slidesAutoplay ? false : slidesAutoplay;
            slidesFade = !slidesFade ? false : slidesFade;
            asNavFor = !asNavFor ? null : asNavFor;
            infinite = !infinite ? false : infinite;
            focusOnSelect = !focusOnSelect ? false : focusOnSelect;
            adaptiveHeight = !adaptiveHeight ? false : adaptiveHeight;


            var slidesRtl = ($("html").attr("dir") === "rtl" && !vertical) ? true : false;
            var slidesRtlXL = ($("html").attr("dir") === "rtl" && !verticalXl) ? true : false;
            var slidesRtlLg = ($("html").attr("dir") === "rtl" && !verticalLg) ? true : false;
            var slidesRtlMd = ($("html").attr("dir") === "rtl" && !verticalMd) ? true : false;
            var slidesRtlSm = ($("html").attr("dir") === "rtl" && !verticalSm) ? true : false;
            var slidesRtlXs = ($("html").attr("dir") === "rtl" && !verticalXs) ? true : false;

            $this.slick({
                slidesToShow: slidesPerView,
                autoplay: slidesAutoplay,
                dots: slidesDots,
                arrows: slidesArrows,
                infinite: infinite,
                vertical: vertical,
                rtl: slidesRtl,
                rows: slidesRows,
                centerPadding: centerPadding,
                centerMode: slidesCenterMode,
                fade: slidesFade,
                asNavFor: asNavFor,
                focusOnSelect: focusOnSelect,
                adaptiveHeight: adaptiveHeight,
                slidesToScroll: slidescroll,
                autoplaySpeed: timeout,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: slidesPerViewXl,
                            vertical: verticalXl,
                            rtl: slidesRtlXL,
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: slidesPerViewLg,
                            vertical: verticalLg,
                            rtl: slidesRtlLg,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: slidesPerViewMd,
                            vertical: verticalMd,
                            rtl: slidesRtlMd,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: slidesPerViewSm,
                            vertical: verticalSm,
                            rtl: slidesRtlSm,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: slidesPerViewXs,
                            vertical: verticalXs,
                            rtl: slidesRtlXs,
                        },
                    },
                ],
            });
        });

        /*-------------------------------------
            Product details big image slider
        ---------------------------------------*/
        $(".big-thumb").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            draggable: false,
            fade: true,
            asNavFor: ".nav-thumb",
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
        });

        $(".nav-thumb").slick({
            slidesToShow: 5,
            slidesToScroll: 3,
            asNavFor: ".big-thumb",
            focusOnSelect: true,
            fade: false,
            variableWidth: true
        });
    }

    //**  On Click Functions Here **//
    function onClicks() {

        $(document).on('click', '.parent-menu-button', function (e) {
            e.preventDefault();
            let item = $(this).data('class');
            $('.parent-menu').removeClass('open');
            if ($('.' + item).hasClass('open')) {
                $('.' + item).removeClass('open');
            } else {
                $('.' + item).addClass('open');
            }
        });

        $(document).mouseup(function (e) {
            var container = $(".main-menu-sub-menu");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('.parent-menu').removeClass('open');
            }
        });

        $(document).on('click', '.parent-mobilemenu-button', function (e) {
            e.preventDefault();
            let item = $(this).data('class');
            $('.parent-mobilemenu').hide();
            $('.' + item).show();
            $('.parent-layer').addClass('closed');
            $('.submenu-layer').addClass('open');
        });

        $(document).on('click', '.btn-back', function (e) {
            e.preventDefault();
            $('.parent-mobilemenu').hide();
            $('.parent-layer').removeClass('closed');
            $('.submenu-layer').removeClass('open');
        });

        var mobileMenu = document.getElementById('mobileMenu')
        mobileMenu.addEventListener('hidden.bs.offcanvas', function () {
            $('.parent-layer').removeClass('closed');
            $('.submenu-layer').removeClass('open');
        });
        
        $(window).on("scroll", function () {
            var scroll = $(window).scrollTop();
            if (scroll < 100) {
                $(".menubar").removeClass("sticky");
                $(".topbar").removeClass("sticky");
                $(".scrollTop").fadeOut("100");
            } else {
                $(".menubar").addClass("sticky");
                $(".topbar").addClass("sticky");
                $(".scrollTop").fadeIn("100");
            }
        });

        $(".scrollTop").click(function () {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        });

        // Manage Quantity Events
        let plusBtn = document.querySelectorAll(".qty-plus");
        let minusBtn = document.querySelectorAll(".qty-minus");
        if (plusBtn) {
            plusBtn.forEach((single) => {
                single.addEventListener('click', () => {
                    let getInput = single.closest(".quantity-wrapper").querySelector('.input-wrapper input').value;
                    getInput++;
                    single.closest(".quantity-wrapper").querySelector('.input-wrapper input').value = getInput;
                });
            });
        }
        if (minusBtn) {
            minusBtn.forEach((single) => {
                single.addEventListener('click', () => {
                    let getInput = single.closest(".quantity-wrapper").querySelector('.input-wrapper input').value;
                    getInput--;
                    if (getInput == 0) {
                        getInput = 1
                    }
                    single.closest(".quantity-wrapper").querySelector('.input-wrapper input').value = getInput;
                });
            });
        }

        // Control Review Stars    
        $(".rating-trigger i").click(function () {
            $(this).addClass("fas").removeClass("fal");
            $(this).prevAll().addClass("fas").removeClass("fal");
            $(this).nextAll().removeClass("fas").addClass("fal");
        });

        $('.viewmore-btn').click(function () {
            $(this).closest('.exerp-menu').find('ul').toggleClass('show-all');
            $(this).toggleClass('acitve');
            return false;
        });
    }

    //**  Zoom Initialize **//
    function zoomInit() {
        if ($('.zoom').length) {
            $('.zoom').zoom({
                magnify: 1.5
            });
            if ((('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0))) {
                $('.zoom').trigger('zoom.destroy');
            }
        }
    }

    carousel();
    onClicks();
    zoomInit();
});

// $(window).on('load', function () {
//     $('.preloader').fadeOut('slow');
// });