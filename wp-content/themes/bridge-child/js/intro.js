/* START Check if it is a mobile device */
var DEBUG = false;
function mobileCheck() {
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
}

var isMobileDevice = mobileCheck();
/* END Check if it is a mobile device */
//var isMobileOrTablet = $(window).width() <= 1000;
var isMobileOrTablet = isMobileDevice;

// Generic functions for site-wide use
jQuery(document).ready(function($) {
    // Global resize end event with timeout
    $(window).resize(function() {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
            $(this).trigger('resizeend');
        }, 1000);
    });

    // Smooth scrolling for all internal links
    $('a[href^="#"]').not('a[href="#"]').on('click',function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });


    // Init select2 for WCPBC country selector
    var select = $('select[name="wcpbc-manual-country"]'),
        button = select.next('input[type="submit"]');
    if (select && select.length) {
        button.hide();
        select.select2()
            .on('change', function() {
                select.closest('form')[0].submit();
            });
    }

    // here we store the window scroll position to lock; -1 means unlocked
    var forceWindowScrollY = -1;

    $(window).scroll(function(event) {
        if(forceWindowScrollY != -1 && window.scrollY != forceWindowScrollY) {
            $(window).scrollTop(forceWindowScrollY);
        }
    });

    $(".popmake")
        .on('popmakeAfterOpen', function() {
            if(forceWindowScrollY == -1) {
                forceWindowScrollY = $(window).scrollTop();
            }
            $('body').addClass('popmake-visible');
        })
        .on('popmakeAfterClose', function() {
            forceWindowScrollY = -1;
            $('body').removeClass('popmake-visible');
        });

    var coupon_str = 'Coupon code already applied!';
    var coupon_str_count = 0;
    $('ul.woocommerce-error li').each(function(){

        if(coupon_str == $(this).text()) {

            coupon_str_count++;
        }
    });  
    if( coupon_str_count > 1 ) {
        var lis = $('ul.woocommerce-error li');
        for( var i=0; i<lis.length; i++ ) {

            if( $(lis[i]).text() == coupon_str ) {

                $(lis[i]).remove();
                break;
            }
        }      
    }
    $('.woocommerce ul.woocommerce-error').show();
    $(document).on('click', '#place_order', function(){

        setTimeout(function(){ $('.woocommerce ul.woocommerce-error').show(); }, 3000);
    });

    var mcc_checkout = $('#_mc4wp_subscribe_woocommerce_checkout_field');
    var mcc_checkout_clone = mcc_checkout.clone();
    if( mcc_checkout_clone.length > 0 ) {


        mcc_checkout_clone.css('display','block').insertAfter($('div#payment'));
        mcc_checkout.remove();
    }	
});

document.addEventListener('DOMContentLoaded', function() {
	FastClick.attach(document.body);
}, false);
// scroll to top code Starts here 

var classname1 = document.getElementsByClassName("current");
var classname2 = document.getElementsByClassName("mobile");

for(var i=0;i<classname1.length;i++){
	classname1[i].addEventListener('click', myFunction, false);
}
for(var i=0;i<classname2.length;i++){
	classname2[i].addEventListener('click', myFunction, false);
}

function myFunction(){
	window.scrollTo(0, 0);
}	
// scroll to top code Ends here 

// Initialize shop nav
function initShopNav() {
    var $ = jQuery,
        controller,        
        productsScroll = $('.ps-shop-nav-scroll');

    function initController() {
        if (!isMobileOrTablet && !controller) {
            // init
            controller = new ScrollMagic.Controller({
                globalSceneOptions: {
                    triggerHook: 'onLeave',
                    ease: Power0.easeNone
                }
            });
            // Pin the product nav
            var pinShopNav = new ScrollMagic.Scene({
                triggerElement: '.ps-shop-nav',
                offset: 0
            })
            .setPin('.ps-shop-nav')
            .addTo(controller);
        }
    }

    function checkNavWidth() {
        var navIsScrollable = (productsScroll.get(0).scrollWidth > productsScroll.width());
        $('.ps-shop-nav').toggleClass('hide-cycle', !navIsScrollable);
    }

    function onWindowResize() {
    //    isMobileOrTablet = $(window).width() <= 1000;

        checkNavWidth();

        if (!controller && !isMobileOrTablet) {
            initController();
            return;
        }

        if (!controller) {
            return;
        }

        if (isMobileOrTablet) {
            controller.enabled(false);
        } else if (!controller.enabled()) {
            // Doesn't work for some reason...
            controller.enabled(true);
        }
    }
    $(window).bind('resizeend', onWindowResize);

    onWindowResize();


    function setActiveNavItem() {
        var postID = WPURLS.postID,
            item = $('[data-product-id="'+postID+'"]'),
            distance,
            altID;

        // If the postID is the id of the shop page, then look for the featured product id
        // There must be a better way to do this...
        if (!item.length) {
            altID = parseInt($('.product').attr('id').match(/\d+/g), 10);
            item = $('[data-product-id="'+ altID +'"]');
        }

        if (item.length) {
            distance = item.length && item.offset().left - $('.ps-shop-nav .button-left').width();

            item.addClass('active');
            // Scroll to active on load
            productsScroll.animate({scrollLeft: distance}, 500);
        }
    }
    setActiveNavItem();

    // Cycling buttons for shop nav
    $('.ps-shop-nav .button-cycle').click(function(ev) {
        var distance;

        ev.preventDefault();

        if ($(ev.currentTarget).hasClass('button-left')) {
            distance = productsScroll.scrollLeft() - productsScroll.width();
        } else {
            distance = productsScroll.width() + productsScroll.scrollLeft();
        }

        productsScroll.animate({scrollLeft: distance}, 500);
    });
}


// Initialize intro
function initIntro () {
    var $ = jQuery,
        introStart = $('.section-intro'),
        introZoomContainer = $('.intro-main-image'),
        introStartFallback = $('.section-intro-fallback'),
        introStartFallbackImage = $('.section-intro-fallback .intro-fallback-image'),
        introTitle = $('.section-intro .intro-title'),
        introTitleHeight = introTitle.height() + introTitle.position().top,
        introScollCta = $('#intro-start-scroll-cta'),
        introWidth = 2800*2,
        introHeight = 1500*2,
        introDuration = 1500,
        controller,
//        isMobileOrTablet = $(window).width() <= 1000,
        headerHeight = 100,
        canvasImageLoaded,
        introReady,
        mobileReady,
        templateUrl = WPURLS.child_stylesheet_directory_uri; // Can't get value from el - don't know if sticky or not
    // Note: Phonics images have been combined with set images temporarily.
    //       In future, separate phonics images should be used to add depth
    //       when parallaxed with set
    var images = [
            {
                canvas: '.intro-scene-start'
            },
            {
                canvas: '.intro-scene-end'
            },
            // Use background image instead because firefox is shit
            // {
            //     src: 'family.png',
            //     canvas: '.intro-family',
            //     scale: 4 // scale 400% because intro-main-image ends at 25%
            // }
        ];

    // Load and draw all canvas images
    function loadCanvasImages(callback) {
        // Load all images and draw to respective canvases
        var promises = $.map(images, function(image, i) {
            var deferred = new $.Deferred(); // created a Deferred object to return
            var canvas = $('canvas' + image.canvas)[0];
            var getCanvasSrc = $(canvas).data('image-url');
            var ctx = canvas.getContext('2d');
            var img = new Image;
            var scaleFactor = image.scale || 1;

            // img.crossOrigin = 'Anonymous';

            // Draw image to canvas
            img.onload = function(){
                // Set canvas dimensions
                canvas.height = img.height * scaleFactor;
                canvas.width = img.width * scaleFactor;

                // draw image to canvas
                ctx.drawImage(img, 0, 0, img.width * scaleFactor, img.height * scaleFactor);

                var base64Img = canvas.toDataURL('image/png');
                // Clean up
                canvas = null;

                // Resolve the Deferred object when image is ready
                deferred.resolve(base64Img);
            };

            // Define the src after .onload defined
            img.src = image.src ? templateUrl + '/img/' + image.src : getCanvasSrc;

            // return the Deferred object so that it ends up in the array
            return deferred.promise();
        });

        // When all canvas images have loaded...
        $.when.apply($, promises).then(function() {
            canvasImageLoaded = true;
            callback();
        });
    }

    // Set amount of offset to get lockup timing right
    function setLockupOffset(removePadding) {
        var padding;

        if (removePadding) {
            padding = 0;
        } else {
            padding = introDuration + Math.round($('.section-intro-lockup').offset().top) + introTitle.position().top;
        }

        // Static intro section
        $('.section-intro-lockup').css({
            paddingTop: padding
        });
    }

    // Set all the starting positions for sections
    function setIntroElementsStyle() {
        var absoluteEls = [
                '.section-intro .intro-content-inner',
                '.section-intro .intro-heading-container',
                '.section-intro .intro-heading-inner'].join(", "),
            easeInOutCubic = (function (x, t, b, c, d) {
                    if ((t/=d/2) < 1) return c/2*t*t*t + b;
                    return c/2*((t-=2)*t*t + 2) + b;
                });

        // Position dynamically for easy editing in sqs
        if (!isMobileOrTablet && !canvasImageLoaded) {

            // Zooming container for scenes
            introZoomContainer.css({
                width: introWidth,
                height: introHeight
            });

            $(absoluteEls).css('position', 'absolute');

            $('.section-intro .intro-content').css({
                position: 'fixed',
                overflow: 'hidden'
            });

            $('.section-intro .intro-heading-inner').css('marginTop', headerHeight + 20);
        }

        if (!introReady) {

            introScollCta
                .addClass('bouncing')
                .click(function(ev) {
                    ev.preventDefault();

                    $('html, body').animate({
                        scrollTop: introDuration + 2 + 'px'
                    }, 2000, easeInOutCubic);
                });
        }
    }

    // Scrolling animation
    function initScrollAnimations(callback) {

        // Big zoom out
        var zoomOutTo = 0.25;
        var zoomIntroTween = TweenMax.fromTo(introZoomContainer.get(0), 1, {
                top: '45%',
                y: -introZoomContainer.height() * 0.25,
                x: '-50%',
                scale: 1,
                transformOrigin: '50% 0 0',
                ease: Quint.easeOut,
            }, {
                top: '0%',
                y: introTitleHeight,
                scale: zoomOutTo
            });
        var zoomIntro = new ScrollMagic.Scene({
                duration: introDuration - 10 // end slightly before lining up (smoother)
            })
            .setTween(zoomIntroTween)
            .addTo(controller);

        // During imac zoom out - e.g. for inverting nav color
        var introEnd = new ScrollMagic.Scene({
                offset: introDuration/2
            })
            .setClassToggle('body', 'intro-end')
            .addTo(controller);

        // Show intro-end cta for short duration
        var introEndCta = new ScrollMagic.Scene({
                offset: introDuration/2,
                duration: introDuration/2 + 40 // a little extra scroll
            })
            .setClassToggle('.intro-end-scroll-cta', 'show')
            .addTo(controller);

        // After zoom out done
        var introEndScroll = new ScrollMagic.Scene({
                offset: introDuration
            })
            .setClassToggle('body', 'intro-ended')
            .addTo(controller);


        // TV scene zoom
        var zoomSceneTween = TweenMax.fromTo('.intro-scene', 1, {
                top: '46%'
            }, {
                top: '50%',
                scale: 0.75
            });
        var zoomScene = new ScrollMagic.Scene({
                duration: introDuration - 500
            })
            .setTween(zoomSceneTween)
            .addTo(controller);


        // Fade out start scene
        var fadeSceneTween = TweenMax.fromTo('.intro-scene-end, .intro-family', 1, {
                opacity: 0
            }, {
                opacity: 1
            });
        var fadeScene = new ScrollMagic.Scene({
                duration: 150
            })
            .setTween(fadeSceneTween)
            .addTo(controller);

        // Fade intro scroll cta and intro heading
        var scrollCtaTween = TweenMax.to('.section-intro .intro-heading-inner, .intro-start-scroll-cta, .section-intro .intro-overlay', 1, {
                opacity: 0,
                display: 'none'
            });
        var scrollCta = new ScrollMagic.Scene({
                duration: 100
            })
            .setTween(scrollCtaTween)
            .addTo(controller);


        // Fade intro title
        var titleTween = TweenMax.fromTo(introTitle, 1, {
                opacity: 0,
                display: 'none'
            }, {
                opacity: 1,
                display: 'block'
            });
        var titleScene = new ScrollMagic.Scene({
                offset: (introDuration / 2),
                duration: (introDuration / 2) - 100 // end slightly before intro-ended
            })
            .setTween(titleTween)
            .addTo(controller);

        // After all animations initialized ...
        callback();
    }


    // Loading mask - shown before page and intro are ready
    function showLoader() {
        var num = 12;
        var loader = $('<div class="sk-fading-circle" />');
        for(var i = 1; i <= num; i++) {
            loader.append('<div class="sk-circle'+i+' sk-circle" />');
        }

        loader = $('<div class="sk-loader" />').append(loader);

        $('.section-intro-container').css('opacity', 0);
        $('body').append(loader);
    }
    function hideLoader() {
        // Fade in everything when intro loaded
        $('body .sk-loader').hide(0, function() {
            $(this).remove();
        });
        $('.section-intro-container').animate({opacity: 1}, 800);
    }

    function onIntroReady() {
        $('body').addClass('intro-ready');
        hideLoader();
        introReady = true;
    }

    function initMobile() {
        src = $('canvas' + images[0].canvas).data('image-url');
        introStartFallbackImage.css('backgroundImage', 'url("'+ src +'")');
        mobileReady = true;
    }

    function initController() {
        // Create controller
        controller = new ScrollMagic.Controller({
            globalSceneOptions: {
                triggerHook: 'onLeave',
                ease: Power0.easeNone
            }
        });

        // Start up the scrollmagic stuff
        initScrollAnimations(onIntroReady);
    }

    // Set some positioning and toggle controller on window resize
    function onWindowResize() {
   //   isMobileOrTablet = $(window).width() <= 1000;
		isMobileOrTablet = mobileCheck();
        // Set sizes for various intro components
        setIntroElementsStyle();

        // Callback when (and if) all canvas images are loaded
        function initAll() {
            introStart.toggle(!isMobileOrTablet);
            introStartFallback.toggle(isMobileOrTablet);
            $('.section-intro-lockup').toggleClass('visible', isMobileOrTablet);
            setLockupOffset(isMobileOrTablet);

            if (!controller && !isMobileOrTablet) {
                initController();
            } else {
                if (!mobileReady) {
                    initMobile();
                }
                onIntroReady();
            }
        }

        if (!isMobileOrTablet && !canvasImageLoaded) {
            showLoader();
            $('.intro-tv-container').css('backgroundImage', 'url("'+ templateUrl + '/img/tv.jpg' +'")');
            $('.intro-family').css('backgroundImage', 'url("'+ templateUrl + '/img/family.png' +'")');
            loadCanvasImages(initAll);
        } else {
            initAll();
        }
    }
    $(window).bind('resizeend', onWindowResize);

    // Start up the controller and setup positioning etc.
    onWindowResize();
}
