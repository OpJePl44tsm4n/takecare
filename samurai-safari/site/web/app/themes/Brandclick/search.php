<?php get_header();
    
    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">

        <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="http://schema.org/Article">

            <section class="container">
                <div class="row">

                    <div class="col"> 
                        <nav class="single-nav-links">
                            <?php _e("Zoekresultaten voor: ", WhiteLabelTheme::THEME_SLUG ) ?> <b><?php echo get_search_query(); ?></b>
                        </nav>

                        <?php if ( have_posts() ) : ?> 

                            <section class="row justify-content-md-center">
                                <?php while ( have_posts() ) : the_post();
                                    $post_type = get_post_type(); 

                                    include(locate_template('inc/partials/grid-post.php'));

                                    wp_reset_postdata();
                                endwhile; ?>

                            </section>

                            <nav class="single-nav-links bottom d-flex">

                                <div class="mr-auto">
                                    <?php previous_posts_link( '<i class="fa fa-angle-left" aria-hidden="true"></i> ' . __("Vorige", WhiteLabelTheme::THEME_SLUG ) ); ?>
                                </div>
                                <div>
                                    <?php
                                        global $wp_query;
                                        $big = 999999999; // need an unlikely integer
                                        
                                        echo paginate_links( array(
                                            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                            'format' => '?paged=%#%',
                                            'prev_next' => false,
                                            'current' => max( 1, get_query_var('paged') ),
                                            'total' => $wp_query->max_num_pages
                                        ) );
                                    ?>
                                </div>
                                <div class="ml-auto">
                                    <?php next_posts_link( __("Volgende", WhiteLabelTheme::THEME_SLUG ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>' ); ?> 
                                </div>
                            </nav>

                        <?php else: ?> 
                            
                            <h2><?php _e("Helaas, er zijn geen berichten gevonden!", WhiteLabelTheme::THEME_SLUG ) ?></h2> 

                        <?php endif; ?>
                        

                    </div>
                </div>  
            </section>
           
        </article>

    </main><!-- #content -->

<?php get_footer(); ?>