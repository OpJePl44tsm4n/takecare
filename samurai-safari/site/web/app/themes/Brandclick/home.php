<?php
// template for the latest posts. 
get_header(); 
$next_post = true; 
$offset = get_query_var('posts_per_page');
?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
        
        <?php if ( have_posts() ) : ?> 
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="http://schema.org/Article">
             
                <section class="row post-grid">
                    <div class="container">

                        <header>
                            <div class="d-flex justify-content-start">
                                <div class="mr-auto"><h2><?php _e('Most recent articles', TakeCareIo::THEME_SLUG ); ?></h2></div>
                            </div>  
                        </header>

                        <div class="row justify-content-md-center">
                            <?php while ( have_posts() ) : the_post();

                                include(locate_template('inc/partials/grid-post.php')); 

                                if ( get_adjacent_post() == '') {
                                    $next_post = false;
                                }
                                wp_reset_postdata();

                            endwhile; ?>
                        </div>    
                    </div>
                </section> 

                <footer>
                    <?php if ($next_post) : ?>

                        <div class="load-more">
                            <div class="btn btn-secondary load-more__btn" data-offset="<?php echo $offset; ?>" data-s="" >
                                <?php _e('Load more articles', TakeCareIo::THEME_SLUG )  ?>
                            </div>
                            <div class="load-more__no-more hidden">
                                <?php echo  _e('No more articles', TakeCareIo::THEME_SLUG ); ?>
                            </div>
                        </div>

                    <?php endif; ?>                 
                </footer>   

             </article>   

        <?php endif; ?>


    </main><!-- #content -->

    
<?php get_footer();