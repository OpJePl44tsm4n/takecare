<!DOCTYPE html>

<!--[if lt IE 9]><html <?php language_attributes(); ?> class="oldie"><![endif]-->

<!--[if (gte IE 9) | !(IE)]><!--><html <?php language_attributes(); ?> class="modern"><!--<![endif]-->

<head>

    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <?php wp_head(); ?>

    <!-- Google Tag Manager -->
    <?php if( $GTM = get_option('google_tag_manager_id') ) : ?>
        <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo $GTM; ?>');</script>
    <?php endif; ?>    
    <!-- End Google Tag Manager -->
</head>



<body <?php body_class(); ?> >

    <!-- Google Tag Manager (noscript) -->
    <?php if( $GTM ) : ?>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $GTM; ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php endif; ?>    
    <!-- End Google Tag Manager (noscript) -->

    <div id="takecare">
        
        <!-- Header -->
        <header class="site-header">
            <nav id="main-nav" class="navbar navbar-expand-md navbar-dark fixed-top transparant">
                <div class="container">    
                        
                    <div class="brand-wrapper">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="home">
                            <?php 
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $image = isset($custom_logo_id) ? wp_get_attachment_image_url( $custom_logo_id , 'full' ) : false;
                                $image = $image ? $image : trailingslashit(get_stylesheet_directory_uri()) . 'assets/dist/img/logo.svg';
                            ?>
                            <img class="img-fluid brand-logo" src="<?php echo $image ?>" alt="<?php echo get_bloginfo( 'name' ); ?> Logo">
                        </a>
                    </div>

                    
                    <button id="search-collapse" class="btn btn-primary d-md-none collapsed" 
                        data-toggle="collapse" 
                        data-target="#search-bar" 
                        data-parent="#main-nav"
                        aria-expanded="false" 
                        aria-controls="search-bar"><i class="fas fa-search"></i>
                        <i class="fas fa-search-minus"></i>
                    </button>

                    <form id="search-bar" class="collapse collapse-sm-only" role="search" method="get" action="<?php echo home_url(); ?>?s=">
                        <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" data-type="company" placeholder="<?php echo _e("Find products, services or categories in your area", TakeCareIo::THEME_SLUG ); ?>" /><button type="submit" id="searchsubmit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        <input type="hidden" class="field" name="post_type" id="post_type" value="company">
                    </form>

                    <div class="menu-toggler collapsed"data-toggle="collapse" data-target="#navbarCollapse" data-parent="#main-nav" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Navigation">
                        <button class="navbar-toggler" type="button">
                            <?php /*<span><?php _e('Menu', WhiteLabelTheme::THEME_SLUG ); ?></span>*/?>
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarCollapse" data-parent="#main-nav">
                        <div class="close-btn d-flex">
                            <button class="navbar-toggler mr-auto close" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="true" aria-label="Close navigation"><span aria-hidden="true">&times;</span></button>
                        </div>

                        <div class="nav-wrap ml-auto">
                            <?php wp_nav_menu( array( 
                                'theme_location' => 'header-menu',
                                'container' => false,
                                'menu_class' => 'nav navbar-nav', 
                                'container_id'    => 'bs4navbar',
                                'menu_id'         => false,
                                'depth'           => 3,
                                'fallback_cb'     => 'bs4navwalker::fallback',
                                'walker' => new Greylabel\Vendor\bs4NavWalker()
                            ) ); ?> 
                        </div>

                        <div class="d-flex justify-content-center">
                            <button id='ninja-toggle' data-title="Activate light mode" title="Activate dark mode">
                                <svg id="sun" width="380px" height="380px" viewBox="0 0 380 380" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
                                        <g id="Group" fill="#010202" fill-rule="nonzero">
                                            <path d="M190.09766,290.09766 C134.95752,290.09766 90.09766,245.2378 90.09766,190.09766 C90.09766,134.95752 134.95752,90.09766 190.09766,90.09766 C245.2378,90.09766 290.09766,134.95752 290.09766,190.09766 C290.09766,245.2378 245.23779,290.09766 190.09766,290.09766 Z M190.09766,110.09766 C145.98536,110.09766 110.09766,145.98536 110.09766,190.09766 C110.09766,234.20996 145.98536,270.09766 190.09766,270.09766 C234.20996,270.09766 270.09766,234.20996 270.09766,190.09766 C270.09766,145.98536 234.20996,110.09766 190.09766,110.09766 Z" id="Shape"></path>
                                            <path d="M190,74 C184.47705,74 180,69.52295 180,64 L180,10 C180,4.47705 184.47705,0 190,0 C195.52295,0 200,4.47705 200,10 L200,64 C200,69.52295 195.52295,74 190,74 Z" id="Path"></path>
                                            <path d="M190,380 C184.47705,380 180,375.52295 180,370 L180,316 C180,310.47705 184.47705,306 190,306 C195.52295,306 200,310.47705 200,316 L200,370 C200,375.52295 195.52295,380 190,380 Z" id="Path"></path>
                                            <path d="M64,200 L10,200 C4.47705,200 0,195.52295 0,190 C0,184.47705 4.47705,180 10,180 L64,180 C69.52295,180 74,184.47705 74,190 C74,195.52295 69.52295,200 64,200 Z" id="Path"></path>
                                            <path d="M370,200 L316,200 C310.47705,200 306,195.52295 306,190 C306,184.47705 310.47705,180 316,180 L370,180 C375.52295,180 380,184.47705 380,190 C380,195.52295 375.52295,200 370,200 Z" id="Path"></path>
                                            <path d="M278.54199,111.45801 C275.98291,111.45801 273.42334,110.48194 271.4707,108.5293 C267.56591,104.62403 267.56591,98.292 271.4707,94.38672 L310.208,55.64942 C314.11327,51.74415 320.4453,51.74415 324.35058,55.64942 C328.25537,59.55469 328.25537,65.88672 324.35058,69.792 L285.61328,108.5293 C283.66064,110.48193 281.10107,111.45801 278.54199,111.45801 Z" id="Path"></path>
                                            <path d="M62.7207,327.2793 C60.16162,327.2793 57.60205,326.30323 55.64941,324.35059 C51.74462,320.44532 51.74462,314.11329 55.64941,310.20801 L94.38671,271.47071 C98.29198,267.56544 104.62401,267.56544 108.52929,271.47071 C112.43408,275.37598 112.43408,281.70801 108.52929,285.61329 L69.79199,324.35059 C67.83936,326.30322 65.27979,327.2793 62.7207,327.2793 Z" id="Path"></path>
                                            <path d="M101.45801,111.45801 C98.89893,111.45801 96.33936,110.48194 94.38672,108.5293 L55.64942,69.792 C51.74463,65.88673 51.74463,59.5547 55.64942,55.64942 C59.55469,51.74415 65.88672,51.74415 69.792,55.64942 L108.5293,94.38672 C112.43409,98.29199 112.43409,104.62402 108.5293,108.5293 C106.57666,110.48193 104.01709,111.45801 101.45801,111.45801 Z" id="Path"></path>
                                            <path d="M317.2793,327.2793 C314.72022,327.2793 312.16065,326.30323 310.20801,324.35059 L271.47071,285.61329 C267.56592,281.70802 267.56592,275.37599 271.47071,271.47071 C275.37598,267.56544 281.70801,267.56544 285.61329,271.47071 L324.35059,310.20801 C328.25538,314.11328 328.25538,320.44531 324.35059,324.35059 C322.39795,326.30322 319.83838,327.2793 317.2793,327.2793 Z" id="Path"></path>
                                        </g>
                                    </g>
                                </svg>
                                
                                 <svg id="moon" width="221px" height="241px" viewBox="0 0 221 241" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd">
                                        <path d="M120.91211,240.09766 C54.74414,240.09766 0.91211,186.26563 0.91211,120.09766 C0.91211,53.92969 54.74414,0.09766 120.91211,0.09766 C124.11768,0.09766 127.40186,0.23145 130.67285,0.49512 C134.25,0.78418 137.39746,2.9668 138.92187,6.21485 C140.44628,9.4629 140.11328,13.2793 138.0498,16.21485 C126.18408,33.09083 119.91211,52.95313 119.91211,73.65333 C119.91211,98.94435 129.3745,123.07911 146.55663,141.61329 C163.64647,160.04884 186.80272,171.30958 211.75878,173.3213 C215.33495,173.60939 218.48339,175.792 220.0078,179.04103 C221.53221,182.28908 221.19921,186.10548 219.13573,189.04103 C196.65723,221.01074 159.93848,240.09766 120.91211,240.09766 Z M112.31299,20.46387 C61.18457,24.84082 20.91211,67.85547 20.91211,120.09766 C20.91211,175.23828 65.77197,220.09766 120.91211,220.09766 C147.80273,220.09766 173.37988,209.22168 192.02441,190.38965 C169.08886,184.9336 148.1875,172.79102 131.88964,155.21094 C111.26855,132.9668 99.9121,104.00293 99.9121,73.65332 C99.91211,54.99512 104.16016,36.90137 112.31299,20.46387 Z" id="Shape" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>    
            </nav> 
        </header>    