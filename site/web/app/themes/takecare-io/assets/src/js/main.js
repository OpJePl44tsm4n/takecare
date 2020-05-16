

import 'bootstrap';
import VueSession from 'vue-session';

// window.Vue = require('vue');

// Vue.use(VueSession);
// Vue.component('user-auth', require('./components/UserAuth.vue').default);
// Vue.component('user-register', require('./components/UserRegister.vue').default);

// const app = new Vue({
//     el: '#takecare'
// });



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

    addAnimationClassInView: function( viewElementClass, newClass ){

        var el = document.getElementsByClassName( viewElementClass );

        if (el.length) {
            Brandclick.addClass( el[0] , 'animation-init' );

            window.onscroll = function() {
                if( Brandclick.isInViewport(el[0]) ) {
                    Brandclick.addClass( el[0], newClass );
                }
            }    
        }     
    },

    isInViewport: function(elem) {
        var bounding = elem.getBoundingClientRect();
        return (
            bounding.top >= 0 
        );
    },


    menuScroll: function() {
        var prevScrollpos = window.pageYOffset,
            mainNav = document.getElementById("main-nav");

        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;

            if (currentScrollPos > 300) {
                if(Brandclick.hasClass(mainNav,'transparant') ) {
                    Brandclick.removeClass(mainNav, 'transparant');
                }  
            } else {
                if(!Brandclick.hasClass(mainNav,'transparant') ) {
                    Brandclick.addClass(mainNav, 'transparant');
                }  
            }
            
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
        var links = document.querySelectorAll('a[href^="#"]');

        for (var i = 0; i<links.length; i++ ) {
            if(links[i].getAttribute('href') === '#' || Brandclick.hasClass(links[i],'control-btn')) {
                continue;
            }
            links[i].addEventListener("click", function(e){
                e.preventDefault(); 
                link = this.getAttribute('href');
                
                if (link!='#') { 
                    element = document.querySelectorAll(link)[0]; 
                    element.scrollIntoView({ 
                        behavior: 'smooth' 
                    });
                }
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
        var videoElements = document.getElementsByClassName('video-wrapper');
        console.log(videoElements);
        console.log('addYoutubeScript');
        if (videoElements.length) {
            for (var i = 0; i < videoElements.length; i++) {
               
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
        var videoWrapper = event.target.f.parentNode;
       
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

    },

    onSearchSubmit: function(e) {
        var searchString = document.querySelectorAll( '#' + this.id + ' #s')[0].value;
        Brandclick.trackEvent('Search', searchString );
    },

    trackEvent: function(type, data, leadId) {

        var leadId = (typeof leadId === 'undefined') ? '' : leadId;
        
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
   
window.onYouTubeIframeAPIReady = function (){
    Brandclick.loadYoutubeIframes();
}



jQuery(document).ready(function($){

    $('.carousel').on('slide.bs.carousel', function () {
        $('.carousel iframe[src*="youtube"]').each(function(i) {
          this.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        });
    });

    var url = wp_api_object.ajaxUrl + "?action=search_autocomplete&post_type=company";
    $( "#s" ).autocomplete({
        source: url,
        delay: 200,
        minLength: 3,
        cache: false,
        select: function( event, ui ) { 
            window.location.href = ui.item.link;
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<div><div class=\"logo\">" + item.logo + "</div>" + item.label + "</div>" )
        .appendTo( ul );
    };
    
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
    $('.carousel.multi-item-carousel .carousel-item').each(function(){
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


    // Perform AJAX login on form submit
    $('body').on('submit', 'form#login', function(e) {
        e.preventDefault();
        form_data = $( this ).serialize();
        id =  'form#login';

        $('form#login p.status').show().text(wp_api_object.loadAccountMessage);

        response = ajax_account_request( form_data, id );
    });

    $('body').on('submit','form#logout', function(e) {
        e.preventDefault();

        form_data = $( this ).serialize();
        id =  'form#logout';

        response = ajax_account_request( form_data, id );
    });

    function ajax_account_request(data, form_id)
    {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: wp_api_object.ajaxUrl,
            data: data,
            success: function(response) {
                if ( ! response || response.error )
                    return false;
                
                $( form_id + ' p.status').text(response.message);
                // if (response.loggedin == true){
                //     // document.location.href = wp_api_object.baseUrl;
                // }
            },
            error: function(response) {

                $( data.id + ' p.status').text(response.message);
                return false; 
            }
        });
    }


} );

