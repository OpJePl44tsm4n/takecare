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
        })(window,document,'script','dataLayer', <?php echo $GTM; ?> );</script>

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
                    <h1 class="brand-name"><?php echo get_bloginfo( 'name' ); ?></h1>
                </div>
                
                <?php 
                $frontpage_id = get_option( 'page_on_front' );
                $slug = get_post_field( 'post_name', $frontpage_id );
                // only show the menu on home when slug is home 
                if (!is_front_page() || (is_front_page() && $slug === 'home') ) : ?>

                    <div class="collapse navbar-collapse" id="navbarCollapse" data-parent="#main-nav">
                        <div class="close-btn d-flex">
                            <button class="navbar-toggler ml-auto close" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="true" aria-label="Close navigation"><span aria-hidden="true">&times;</span></button>
                        </div>

                        <div class="nav-wrap">
                            <?php wp_nav_menu( array( 
                                'theme_location' => 'header-menu',
                                'container' => false,
                                'menu_class' => 'nav navbar-nav mr-auto', 
                                'container_id'    => 'bs4navbar',
                                'menu_id'         => false,
                                'depth'           => 3,
                                'fallback_cb'     => 'bs4navwalker::fallback',
                                'walker' => new Greylabel\Vendor\bs4NavWalker()
                            ) ); ?> 


                            <?php echo do_action('wpml_add_language_selector'); ?>
                           
                        </div>
                    </div>
                    
                    <div class="menu-toggler collapsed"data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Navigation">
                        <button class="navbar-toggler" type="button">
                            <?php /*<span><?php _e('Menu', TakeCareIo::THEME_SLUG ); ?></span>*/?>
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    
                <?php endif; ?>     
            </div>
               
        </nav> 
    </header>    