<?php get_header();
    
    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
?>
    
    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
        
        <?php if ( have_posts() ) : ?> 
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="http://schema.org/Article">
                
                <section class="row post-grid">
                    <div class="container">

                        <nav class="single-nav-links">
                            <?php _e("Results for", TakeCareIo::THEME_SLUG ) ?>: <b><?php echo get_search_query(); ?></b>
                        </nav>

                        <div class="row justify-content-md-center">
                            <?php while ( have_posts() ) : the_post();

                                include(locate_template('inc/partials/grid-company.php')); 

                                wp_reset_postdata();

                            endwhile; ?>
                        </div>   

                        <nav class="single-nav-links bottom d-flex">

                            <div class="mr-auto">
                                <?php previous_posts_link( '<i class="fa fa-angle-left" aria-hidden="true"></i> ' . __("Previous", TakeCareIo::THEME_SLUG ) ); ?>
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
                                <?php next_posts_link( __("Next", TakeCareIo::THEME_SLUG ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>' ); ?> 
                            </div>
                        </nav>

                    </div>
                </section>    

             </article>   

         <?php else: ?> 
                            
            <h2><?php _e("Sorry, We could not find any articles.. Try again!", TakeCareIo::THEME_SLUG ) ?></h2> 

        <?php endif; ?>


    </main><!-- #content -->

<?php get_footer(); ?>