<?php get_header(); 
$next_post = true; 
$taxonomy = '';
$featured = false;

if( is_tag() ) {
    $taxonomy = 'tag';
    $term = get_query_var( 'tag' );
} elseif ( is_category() ) {
    $taxonomy = 'category';
    $term = get_query_var( 'category_name' );
    $category = get_term_by('slug', $term, $taxonomy) ; 
    $featured_id = $term ? get_field('featured_image', $taxonomy . '_' . $category->term_id) : false;
    $featured = wp_get_attachment_image( $featured_id, 'full' );
}

$offset = get_query_var('posts_per_page');
?>

    <main id="content" class="<?php echo $taxonomy; ?>" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
        
        <?php if ( have_posts() ) : ?> 
            
            <article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Article">
                
                <section class="row header <?php echo $featured ?  'img-bg' : '' ?>">
                    
                    <?php if( $featured ) { ?>
                        <div class="col-md-6">
                            <?php if($title = get_the_archive_title()): ?>
                                    <h1><?php echo $title; ?></h1>
                            <?php endif; 
                            
                            if($description = get_the_archive_description()): 
                                echo $description;  
                             endif; ?>
                            
                        </div>
                        
                         <div class="col-md-6">
                            <?php echo $featured; ?>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-12">
                            <?php if($title = get_the_archive_title()): ?>
                                    <h1><?php echo $title; ?></h1>
                            <?php endif; 
                            
                            if($description = get_the_archive_description()): 
                                echo $description;  
                             endif; ?>
                        </div>
                    <?php } ?>
                </section> 

                <section class="row post-grid">
                    <div class="container">

                        <div class="row justify-content-md-center">
                            <?php while ( have_posts() ) : the_post();

                                include(locate_template('inc/partials/grid-company.php')); 

                                if ( get_adjacent_post() == '') {
                                    $next_post = false;
                                }
                                wp_reset_postdata();

                            endwhile; ?>
                        </div>    
                    </div>
                </section>    

                <footer>

                    <?php
                    if ($next_post) : ?>

                        <div class="load-more">
                            <div class="btn btn-secondary load-more__btn" data-offset="<?php echo $offset; ?>" data-s="" data-tax="<?php echo $taxonomy; ?>" data-cat="<?php echo $category->name; ?>">
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