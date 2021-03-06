<?php get_header(); ?>

    <main id="content" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
    
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('post-content container'); ?> itemscope itemtype="http://schema.org/Article">

                <header>
                    <div class="thumb">
                        <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
                    </div> 
            
                    <h1><?php the_title(); ?></h1>
                    <span class="sub"><?php echo the_date('j M Y'); ?></span>
                    <?php 
                        $terms = get_the_terms( get_the_id(), 'category' );
                        if($terms) {
                            $count = 0; 
                            foreach ( $terms as $term ) {
                                if ($count !== 0) {
                                    echo ', ';
                                }
                                echo '<a href="'.get_term_link($term->term_id).'">'. $term->name .'</a>';
                                $count++;
                            }
                        }    
                    ?>
                </header>
                
                <section>
                    <?php $content = wpautop( get_the_content());
                        // turn the content in an array with paragraphs, breaking on </p>
                        $content = explode('</p>', $content);
                        // remove the last 3 paragraphs from the array and save it in $end
                        $end = array_splice($content, -3);
                        // make a string again from the first part, appending the lost </p> tags again
                        echo implode("</p>",$content);

                        // show the extra content in between
                        if( have_rows('inline_block_page_rows', 'option') ):
                            while ( have_rows('inline_block_page_rows', 'option') ) : the_row();
                                include(locate_template('inc/rows.php'));
                            endwhile;
                        endif; 

                        // make a string again from the end part, appending the lost </p> tags again
                        echo implode("</p>",$end);
                    ?>
                </section>

                <?php if( have_rows('after_content_block_page_rows', 'option') ):
                    while ( have_rows('after_content_block_page_rows', 'option') ) : the_row();
                        include(locate_template('inc/rows.php'));
                    endwhile;
                endif; ?>
                
                <footer class="post-navigation" >
                    <hr>
                    <?php previous_post_link( '%link', '<i class="fa fa-angle-left" aria-hidden="true"></i> ' . __("Previous article", TakeCareIo::THEME_SLUG ) ); ?> 
                    <?php next_post_link( '%link', __("Next article", TakeCareIo::THEME_SLUG ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>' ); ?>
                </footer>

            </article>

            <section class="container related-posts post-grid">
                <header>
                    <div class="d-flex justify-content-start">
                        <div class="mr-auto"><h2><?php _e('Related articles', TakeCareIo::THEME_SLUG ); ?></h2></div>
                    </div>
                </header>

                <div class="row justify-content-md-center">
                    <?php
                        $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 3, 'post__not_in' => array($post->ID) ) );
                        if( $related ):  
                            foreach( $related as $post ):  setup_postdata($post); 

                                include(locate_template('inc/partials/grid-post.php')); 

                            endforeach;
                        endif;
                        wp_reset_postdata(); 
                    ?>
                </div>      
            </section>

        <?php endwhile; endif; ?>

    </main><!-- #content -->

    
<?php get_footer();