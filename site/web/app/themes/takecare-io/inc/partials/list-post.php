<?php 
    $taxonomy = get_sub_field('type');
    $max_posts = get_sub_field('max_posts');
    $term = get_sub_field('taxonomy');
    $term = get_term( $term, $taxonomy );
    $term_featured = $term ? get_field('featured_image', $taxonomy . '_' . $term->term_id) : false;
    $row_title = $term ? $term->name : __('Most recent articles', TakeCareIo::THEME_SLUG );
    $archive_link = get_post_type_archive_link( 'post' ); 
    $archive_title = __('Most recent articles', TakeCareIo::THEME_SLUG );

    $args = [
        'post_type'     => 'post',
        'posts_per_page' => $max_posts,
        'suppress_filters' => 0 // Fix for WPML to only get posts in current language 
    ];

    if ( false != $taxonomy ){
        $args['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'id',
                'terms'    => $term->term_id,
            ]
        ];    

        $archive_link = get_term_link($term, $taxonomy );
        $archive_title = sprintf( __('Show more %s', TakeCareIo::THEME_SLUG ), $term->name ) ;
    }


    // The Query
    $posts = get_posts( $args );
    
    if ( $posts ) : ?>

        <section>
          
            <header>
                <a href="<?php echo $archive_link; ?>">
                <h2><?php echo $row_title; ?></h2>
                <?php 
                    if($term_featured) { ?>
                        <div class="thumb">
                            <?php echo wp_get_attachment_image( $term_featured, 'large' ); ?>
                        </div>
                    <?php } 
                ?>
                </a>
            </header>
            
            <?php foreach ( $posts as $post ) : 
                setup_postdata( $post );
                
                include(locate_template('inc/partials/grid-post.php')); 

            endforeach; 
            wp_reset_postdata(); ?>
            
            <?php echo sprintf('<a class="btn btn-secondary" href="%1s">%2s</a>',  $archive_link, $archive_title ); ?> 

        </section>

    <?php endif; ?>
