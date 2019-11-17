<!DOCTYPE html>

<!--[if lt IE 9]><html <?php language_attributes(); ?> class="oldie"><![endif]-->

<!--[if (gte IE 9) | !(IE)]><!--><html <?php language_attributes(); ?> class="modern"><!--<![endif]-->

<head>

    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

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

    <!-- Header -->
    <header class="site-header">
        <nav id="main-nav" class="navbar navbar-expand-md navbar-light fixed-top">
    
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
                
                <div class="collapse navbar-collapse" id="navbarCollapse" data-parent="#main-nav">
                    <div class="close-btn d-flex">
                        <button class="navbar-toggler ml-auto close" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="true" aria-label="Close navigation"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="nav-wrap">
                        <?php wp_nav_menu( array( 
                            'theme_location' => 'header-menu',
                            'container' => false,
                            'menu_class' => 'nav navbar-nav ml-auto', 
                            'container_id'    => 'bs4navbar',
                            'menu_id'         => false,
                            'depth'           => 3,
                            'fallback_cb'     => 'bs4navwalker::fallback',
                            'walker' => new Brandclick\Vendor\bs4NavWalker()
                        ) ); ?> 


                        <?php echo do_action('wpml_add_language_selector'); ?>
                       
                    </div>
                </div>
                
                <div class="lang-navigation">
                    <div class="ajax-lang-redirect"></div>
                </div>
                
                <div class="menu-toggler collapsed"data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Navigation">
                    <button class="navbar-toggler" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <?php if(function_exists('is_cart') && !is_cart() && !is_checkout()):
                    // include(locate_template('func/Templates/CartTogleAjax.php'));
                endif; ?>

            </div>
            
            <?php if(function_exists('is_cart') && !is_cart() && !is_checkout()): ?>
                <div class="collapse cart-collapse" id="cartCollapse" data-parent="#main-nav" aria-expanded="false" aria-label="Cart">
                    <div class="close-btn d-flex">
                        <h2><?php _e('Shopping cart', WhiteLabelTheme::THEME_SLUG ); ?></h2> 
                        <button class="cart-toggler ml-auto close" type="button" data-toggle="collapse" data-target="#cartCollapse" aria-controls="cartCollapse" aria-expanded="true" aria-label="Close cart"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="wrapper">
                        <?php if ( !is_user_logged_in() && 'no' !== get_option( 'woocommerce_enable_checkout_login_reminder' ) ) :
                            echo do_shortcode('[LOGINFORM]'); 
                        endif; ?>
                        
                            <div class="widget_shopping_cart">
                                <div class="cart-ajax"><?php if(!WC()->cart->is_empty()): ?><?php woocommerce_mini_cart(); ?><?php endif; ?><div id="woo_pp_ec_button_null"></div></div>
                            </div>
                        
                    </div>    
                </div>
            <?php endif; ?> 
            
                <?php if(function_exists('is_cart') && is_product()){ ?>
                    <div class="mobile-header">
                        <div class="mobile-content col-8">
                            <?php $product = wc_get_product( get_the_ID());
                                the_title( '<h1 class="product_title entry-title">', '</h1>' ); ?>
                            <p class="price"><?php echo $product->get_price_html(); ?></p>
                        </div>

                        <div class="col-4"><?php
                            echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
                                sprintf( '<a href="%s" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="%s" data-product_sku="%s" aria-label="Add ´%s¡ to your cart" rel="nofollow">%s</a>',
                                    esc_url( $product->add_to_cart_url() ),
                                    esc_attr( $product->get_id() ),
                                    esc_attr( $product->get_sku() ),
                                    esc_attr( $product->get_title() ),
                                    esc_html( $product->add_to_cart_text() )
                                ),
                            $product); ?>
                        </div> 
                    </div>            
                <?php } ?>
                
        </nav> 
    </header>    