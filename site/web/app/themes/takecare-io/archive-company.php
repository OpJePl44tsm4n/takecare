<?php get_header(); 
$next_post = true; 
$taxonomy = '';
$featured = false;
$term_id = '';
$tax_name = '';

if( is_tag() ) {
    $taxonomy = 'tag';
    $term_id = get_query_var( 'tag_id' );
    $tax_name = 'tags';
} elseif ( is_category() ) {
    $taxonomy = 'category';
    $tax_name = 'categories';
    $term = get_query_var( 'category_name' );
    $category = get_term_by('slug', $term, $taxonomy) ; 
    $term_id = $category->term_id;
    $featured_id = $term ? get_field('featured_image', $taxonomy . '_' . $category->term_id) : false;
    $cat_name = $category->name;
    $featured = wp_get_attachment_image( $featured_id, 'full' );
}
global $wp_query
$offset = $wp_query->post_count;

?>

    <main id="content" class="<?php echo $taxonomy; ?>" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
        
        <?php if ( have_posts() ) : ?> 
            
            <article id="post-<?php the_ID(); ?>" class="page-content" itemscope itemtype="http://schema.org/Article">
                
                <section class="row header <?php echo $featured ?  'img-bg' : 'tertiary-bg-color' ?>">
                    <header class="container">
                        <div class="row swap-order">
                            <?php if( $featured ) { ?>
                                <div class="grid-item col-md-6 vertical-align">
                                    <div class="content">
                                        <?php if($title = get_the_archive_title()): ?>
                                                <h1><?php echo $title; ?></h1>
                                        <?php endif; 
                                        
                                        if($description = get_the_archive_description()): 
                                            echo $description;  
                                         endif; ?>
                                    </div>
                                </div>
                                
                                 <div class="grid-item col-md-6 featured vertical-align">
                                    <div class="thumb">
                                        <?php echo $featured; ?> 
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-12 heading">
                                    <div class="content">
                                        <?php if($title = get_the_archive_title()): ?>
                                                <h1><?php echo $title; ?></h1>
                                        <?php endif; 
                                        
                                        if($description = get_the_archive_description()): 
                                            echo $description;  
                                         endif; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </header>
                </section> 

                <section class="row post-grid pt-4">
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

                <?php if ($next_post) : ?>
                    <footer>

                        <div class="load-more" style="padding-bottom: 3em;">
                
                            <button class="btn btn-primary load-more__btn" data-post-type="company" data-offset="<?php echo $offset; ?>" data-s="" data-tax="<?php echo $taxonomy; ?>" data-cat="<?php echo $term_id; ?>">
                                <?php _e('Load more', TakeCareIo::THEME_SLUG )  ?>
                            </button>

                            <div class="load-more__no-more hidden">
                                <?php echo  _e('No more', TakeCareIo::THEME_SLUG ); ?>
                            </div>
                        </div>

                    </footer>
                <?php endif; ?>                 
                

                <section class="row tertiary-bg-color">
                    <div class="container">
                       <?php include(locate_template('inc/partials/form-mailchimp.php')); ?>
                    </div>   
                    
                </section>

             </article>   

        <?php endif; ?>



    </main><!-- #content -->

    
<?php get_footer(); 