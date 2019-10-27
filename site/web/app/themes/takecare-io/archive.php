<?php get_header(); 
$next_post = true; 
$category = get_query_var( 'cat' );
$taxonomy = get_query_var( 'taxonomy' );

if($taxonomy !== '') {
   $category = get_term_by('slug', get_query_var('term'), $taxonomy) ; 
   $category = $category ? $category->term_id : '';
}

$offset = get_query_var('posts_per_page');
?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
        
        <?php if ( have_posts() ) : ?> 
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="http://schema.org/Article">
                
                <section class="row post-grid">
                    <div class="container">
                        
                        <?php if($description = get_the_archive_title()): ?>
                            <header>
                                <div class="d-flex justify-content-start">
                                    <div class="mr-auto"><h1><?php echo $description; ?></h1></div>
                                </div>  
                            </header>
                        <?php endif; ?>

                        <div class="row justify-content-md-center">
                            <?php while ( have_posts() ) : the_post();

                                include(locate_template('inc/partials/grid-company.php')); 

                                if ( get_adjacent_post('true') == '') {
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
                            <div class="btn btn-secondary load-more__btn" data-offset="<?php echo $offset; ?>" data-s="" data-tax="<?php echo $taxonomy; ?>" data-cat="<?php echo $category; ?>">
                                <?php _e('Load more', TakeCareIo::THEME_SLUG )  ?>
                            </div>
                            <div class="load-more__no-more hidden">
                                <?php echo  _e('No more', TakeCareIo::THEME_SLUG ); ?>
                            </div>
                        </div>

                    <?php endif; ?>                 
                </footer>

                <section class="row tertiary-bg-color">
                    <div class="container">
                       <?php include(locate_template('inc/partials/form-mailchimp.php')); ?>
                    </div>   
                    
                </section>

             </article>   

        <?php endif; ?>



    </main><!-- #content -->

    
<?php get_footer(); 