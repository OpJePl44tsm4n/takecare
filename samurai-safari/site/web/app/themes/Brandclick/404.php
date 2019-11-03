<?php 
/* Template Name: 404 */ 
get_header(); 
    
   $page404 = get_page_by_title( __("404 Page", WhiteLabelTheme::THEME_SLUG ) ); 
   $title = $page404 ? get_the_title( $page404 ) : __("OOPS! Wrong URL", WhiteLabelTheme::THEME_SLUG ) ;
?>

    <main id="content" class="highlighted" role="main">

        <article <?php post_class('page-content'); ?>>

            <section class="container">
                <div class="row">
                    
                    <header>
                        <h2 class="display-2">404</h2>
                        <?php echo get_the_post_thumbnail( $page404, 'large' ); ?>   
                    </header>
                    
                    <section class="container">
                        <div class="row">
                            <?php echo get_post_field('post_content', $page404); ?>
                        </div>  
                    </section>

                    <footer>
                        <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-primary">
                            <?php _e("Back to Home", WhiteLabelTheme::THEME_SLUG ) ?>  
                        </a>

                        <a href="<?php echo get_permalink( get_page_by_title( __("contact", WhiteLabelTheme::THEME_SLUG ) ) ); ?>" class="btn btn-secondary">
                            <?php _e("Contact us", WhiteLabelTheme::THEME_SLUG ) ?>
                        </a>
                    </footer>
                   
                </div>  
            </section>
           
        </article>

    </main><!-- #content -->

<?php get_footer(); ?>