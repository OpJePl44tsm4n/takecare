

var Brandclick = {

    load: function(func) {
   
        var oldOnLoad = window.onload;
        // if there is not any function hooked to it
        if (typeof window.onload != 'function') {
            // you can hook your function with it
            window.onload = func
        } else { // someone already hooked a function
            window.onload = function () {
                // call the function hooked already
                oldOnLoad();
                // call your awesome function
                func();
            }
        }
    },

    init: function() {

        window.dataLayer = window.dataLayer || [];
        window.uetq = window.uetq || [];
        window.interaction = false; 
       
        this.addYoutubeScript();
        this.carouselSlideActions();
        this.addTrackingListeners();
        this.inpageLinks();
        this.menuScroll();
        this.autoplayVideos();

        return; 
    },

    autoplayVideos: function() {

        document.addEventListener("click", function( event ) {
            if( interaction === true ) return; 

            var videos = document.querySelectorAll('video[autoplay]'); 
            
            for (var i = videos.length - 1; i >= 0; i--) {
                videos[i].play();
            }
            interaction = true; 
        });    
    },

    menuScroll: function() {
        var prevScrollpos = window.pageYOffset;
        var mainNav = document.getElementById("main-nav");

        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;

            // if (currentScrollPos > 300) {
            //     if(Brandclick.hasClass(mainNav,'transparant') ) {
            //         Brandclick.removeClass(mainNav, 'transparant');
            //     }  
            // } else {
            //     if(!Brandclick.hasClass(mainNav,'transparant') ) {
            //         Brandclick.addClass(mainNav, 'transparant');
            //     }  
            // }
            
            if (currentScrollPos < 200) {
                return;
            }

            if (prevScrollpos > currentScrollPos) {

                if(Brandclick.hasClass(mainNav,'scroll-down') ) {
                    Brandclick.removeClass(mainNav, 'scroll-down');
                }    
            } else {
                if(!Brandclick.hasClass(mainNav,'scroll-down') ) {
                    Brandclick.addClass(mainNav, 'scroll-down');
                }  
            }
            prevScrollpos = currentScrollPos;
        }
    },

    inpageLinks: function() {
        links = document.querySelectorAll('a[href^="#"]');

        for (i = 0; i<links.length; i++ ) {
          links[i].addEventListener("click", function(e){
            e.preventDefault(); 
            link = this.getAttribute('href');
            element = document.querySelectorAll(link)[0]; 
            element.scrollIntoView({ 
              behavior: 'smooth' 
            });
          }); 
        } 
    }, 

    addYoutubeScript: function() {
        if (document.getElementsByClassName('video-wrapper').length) {
            // 2. This code loads the IFrame Player API code asynchronously.
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }    
    },

    loadYoutubeIframes: function() {
        videoElements = document.getElementsByClassName('video-wrapper');

        if (videoElements.length) {
            for (i = 0; i < videoElements.length; i++) {
               
                videoElements[i].addEventListener('click', function(e){
                    var playerElement = this.dataset.player;
                    var player;  
                    player = new YT.Player(playerElement, {
                        height: '390',
                        width: '640',
                        playerVars: {
                            showinfo: 0,
                            rel: 0,
                            controls: 0, 
                        },
                        videoId: this.dataset.videoId,
                        events: {
                           'onReady': Brandclick.onPlayerReady
                        }
                    });

                });
                
           
            }
        } 
    },
    onPlayerReady: function(event) {
        videoWrapper = event.target.a.parentNode;

        if(Brandclick.hasClass(videoWrapper,'collapse') ) {
            Brandclick.addClass(videoWrapper.parentNode, 'playing');
            Brandclick.addClass(videoWrapper.parentNode.parentNode, 'playing-video');

            jQuery(videoWrapper).on('hide.bs.collapse', function () { 
                event.target.pauseVideo(); 
                Brandclick.removeClass(videoWrapper.parentNode, 'playing');
                Brandclick.removeClass(videoWrapper.parentNode.parentNode, 'playing-video');
            }); 

            jQuery(videoWrapper).on('show.bs.collapse', function () { 
                Brandclick.addClass(videoWrapper.parentNode, 'playing');
                Brandclick.addClass(videoWrapper.parentNode.parentNode, 'playing-video');
                event.target.playVideo(); 
            }); 

        } else {
            Brandclick.addClass(videoWrapper, 'playing');
        }
       
        event.target.playVideo();
    },

    carouselSlideActions: function() {
        jQuery('.carousel').on('slide.bs.carousel', function () {
            jQuery('.carousel iframe[src*="youtube"]').each(function(i) {
              this.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            });
        });
    },

    addClass: function(el, className) {
        if (el.classList)
            el.classList.add(className)
        else if (!this.hasClass(el, className)) el.className += " " + className
    },

    removeClass: function(el, className) {
        if (el.classList)
            el.classList.remove(className)
        else if (hasClass(el, className)) {
            var reg = new RegExp('(\\s|^)' + className + '(\\s|$)')
            el.className=el.className.replace(reg, ' ')
        }
    },

    hasClass: function(el, className) {
        if (el.classList)
            return el.classList.contains(className)
        else
            return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'))
    },

    getElementPosition: function(el) {
        var rect = el.getBoundingClientRect();
        return { top: rect.top, bottom: rect.bottom, left: rect.left, right: rect.right }
    },

    addTrackingListeners: function(){

        // attach listeners to all searchforms
        var forms = document.getElementsByClassName('search-form');
        for(var i = 0; i < forms.length; i++) {
            var form = forms[i];
            form.addEventListener("submit", this.onSearchSubmit);
        }

        // track all wpcf7 submits
        document.addEventListener( 'wpcf7mailsent', function( event ) {

            function findFormType(type) {
                return type.name == 'form-type';
            }
           
            // search the event object for input with key form-type
            var pageType = event.detail.inputs.find(findFormType);
            // If form-type is found show the value otherwise default to contactAanvraag
            pageType = (pageType != undefined) ? pageType.value : 'contactAanvraag';
            // get the lead tracking id
            wpcf7_lead_tracking_id = event.detail.apiResponse.wpcf7_lead_tracking_id; 

            Brandclick.trackEvent('Generic', pageType, wpcf7_lead_tracking_id);
            // jQuery('#genericModal').modal('toggle');
            
        }, false );

        
        // make sure we track special tracking elements
        var trackingElements = document.querySelectorAll("[data-tracking=\"data-layer\"]");
        for(var i = 0; i < trackingElements.length; i++) { 
            var element = trackingElements[i]
            element.addEventListener("click", function( event ) {
                Brandclick.trackEvent('Generic', this.dataset.logValue);
            }, false );
        }

        // track signup popup form
        MailChimpSubscribe = document.getElementById('mc-embedded-subscribe-form');
        if (typeof MailChimpSubscribe !== 'undefined' && MailChimpSubscribe !== null ) {
            MailChimpSubscribe.addEventListener("submit", function( event ) {
                Brandclick.trackEvent('CompleteRegistration');
            }, false );
        }

        var callButtons = document.querySelectorAll(".track-phone button");
        for(var i = 0; i < callButtons.length; i++) {
            var callButton = callButtons[i];
            callButton.addEventListener( 'click', function( event ) {
                Brandclick.addClass(this.parentElement, "is-clicked");
            }, false );
        }
        
    },

    onSearchSubmit: function(e) {
        searchString = document.querySelectorAll( '#' + this.id + ' #s')[0].value;
        Brandclick.trackEvent('Search', searchString );
    },

    trackEvent: function(type, data, leadId) {

        leadId = (typeof leadId === 'undefined') ? '' : leadId;
        
        if(type === 'Generic') {
            dataLayer.push({
                'event': 'track-conversion',
                'conversie' : data,
                'leadId' : leadId
            });

            window.uetq.push({
                'ec': 'track-conversion',       // Event category (string)
                'ea': data,                      // Event action (string)
                'el': window.location.pathname, // Event label (string)
                'ev': 1                         // Event value (numeric)
            });
        }

        if(type === 'addFavorite' || type === 'removeFavorite') {
            dataLayer.push({
                'event': 'track-conversion',
                'conversie': type,
                'favName': data.title,
                'favId': data.id
            });
        }

        if(type === 'Purchase') {
            window.uetq.push({
                'ec': 'track-conversion',   
                'ea': 'Purchase',                
                'el': window.location.pathname, 
                'ev': data                      
            });
        }

        if(type === 'CompleteRegistration') {
            window.uetq.push({
                'ec': 'track-conversion',      
                'ea': type,                   
                'el': window.location.pathname, 
                'ev': 1                        
            });
        }    

        // FB calls
        if (typeof fbq === "function") { 
            if(type === 'addFavorite') {
                fbq('track', 'AddToWishlist', {
                    content_type: 'product',
                    content_ids: data.id,
                });
            }

            if(type === 'Search') {
                fbq('track', 'Search', {
                    search_string: data,
                });
            }   

            if(type === 'CompleteRegistration') {
                fbq('track', type);
            }

            if(type === 'Generic') {
                fbq('track', data);
            }

            if(type === 'Purchase') {
                fbq('track', 'Purchase', {
                    value: data,
                    currency: 'EUR'
                });
            }    
        } 

    }, 
}


Brandclick.load(function(){
    Brandclick.init();
});
   
function onYouTubeIframeAPIReady() {
    Brandclick.loadYoutubeIframes();
}



jQuery(document).ready(function($){
    
    // add swipe functionality to bootstrap carousel
    $(".carousel").on("touchstart", function(event){
        var xClick = event.originalEvent.touches[0].pageX;
        $(this).one("touchmove", function(event){
            var xMove = event.originalEvent.touches[0].pageX;
            if( Math.floor(xClick - xMove) > 5 ){
                $(this).carousel('next');
            }
            else if( Math.floor(xClick - xMove) < -5 ){
                $(this).carousel('prev');
            }
        });
        $(".carousel").on("touchend", function(){
                $(this).off("touchmove");
        });
    });

    // add 3 item carousel functionality
    $('.carousel.multi-item-carousel:not(#TinyProductSlider) .carousel-item').each(function(){
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));
        
        if (next.next().length>0) {
            next.next().children(':first-child').clone().appendTo($(this));
        }
        else {
            $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
        }
    });

    $('#TinyProductSlider').each(function(){ 
       
        $(this).append('<div class="carousel-controls d-flex d-lg-none"><ol class="carousel-indicators"></ol></div>'); 
        $(this).addClass('multi-item-carousel');
        indicators = $('ol', this );
        innerCarousel = $('ul', this);
        carouselInnerHtml = ''; 
        count = 0; 

        $('li', this ).each(function(){
            el = $(this);
            var next = el.next();
            var previous = el.prev();  
            active = (count == 0) ? ' active' : '';
            
            if (!next.length) {
                next = el.siblings(':first');
            }
            lastHtml = next.html();

            if (!previous.length) {
                firstHtml = el.siblings(':last').html();
            }
            else {
                firstHtml = previous.html();
            }

            currentHtml = el.html();
            carouselInnerHtml += '<li class="carousel-item'+ active +'"><div class="row"><div class="d-block product col-4">'+firstHtml+'</div><div class="d-block product col-4">'+currentHtml+'</div><div class="d-block product col-4">'+lastHtml+'</div></div></li>';

            indicators.append('<li data-target="#TinyProductSlider" data-slide-to="'+ count +'" class="'+ active +'"></li>'); 
            count++; 
        });

        $(this).prepend('<ul class="products carousel-inner d-block d-lg-none">' + carouselInnerHtml + '</ul>'); 
        innerCarousel.addClass('d-none d-lg-flex');
    });





    $(document).on('click', '.added_to_cart.wc-forward', function (e)
    {
        e.preventDefault();

        $('#cartCollapse').collapse();
    });

    // Ajax delete product in the cart
    $(document).on('click', 'a.remove', function (e)
    {
        e.preventDefault();

        var product_id = $(this).attr("data-product_id"),
            cart_item_key = $(this).attr("data-cart_item_key"),
            data = {
                action: "cart_update",
                update_type: "product_remove",
                product_id: product_id,
                cart_item_key: cart_item_key
            };

        WpAjaxCall(data);  
       
    });

    $(document).on('change', '.quantity input.qty', function () {

        var product_id = $(this).attr("id"),
            cart_item_key = $(this).attr("name"),
            product_count = $(this).attr("value"),
            data = {
                action: "cart_update",
                update_type: "product_quantity",
                product_id: product_id,
                cart_item_key: cart_item_key,
                product_count: product_count
            };

        WpAjaxCall(data);         
    });

    $(document).on('click', '.ajax-coupon #coupon-submit', function () {
        
        var coupon_element = $(this).attr("data-for"),
            coupon = $(coupon_element).attr("value"),

            data = {
                action: "cart_update",
                update_type: "add_coupon",
                coupon: coupon
            };

        
        WpAjaxCall(data);
    });

    // Perform AJAX login on form submit
    $('body').on('submit', 'form#login', function(e) {
        e.preventDefault();

        data = { 
            action: 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
            username: $('form#login #username').val(), 
            password: $('form#login #password').val(), 
            security: $('form#login #security').val() 
        };

        WpAjaxCall(data);
        
    });

    function WpAjaxCall( data ) {

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            success: function(response) {
                if ( ! response || response.error )
                    return false;

                UpdateWcSnippets(response);
            },
            error: function() {
                return false; 
            }
        });
    }

    function UpdateWcSnippets(response) {

        if (response) {
            var fragments = response.fragments;

            // Replace fragments
            if ( fragments ) {
                $.each( fragments, function( key, value ) {
                    $( key ).replaceWith( value );
                });
            }

            if( response.loggedin ){
                $('.login-ajax').toggle();
            }
        }    
    }

        if ( $('.slider-reviews').length ) {
        $('.slider-reviews').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            cssEase: 'ease',
            autoplay: true,
            swipeToSlide: true,
            autoplaySpeed: 3000,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [ 
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 5
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        slidesToShow: 4
                    }
                },
                 {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3
                    }
                },  
                {
                    breakpoint: 520,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
    } 

    
    $('#cartCollapse, #navbarCollapse').on('shown.bs.collapse', function (e) {
        $( document.body ).toggleClass('scroll-lock'); 
    }).on('hidden.bs.collapse', function (e) {
        $( document.body ).toggleClass('scroll-lock'); 
    });  

} );

