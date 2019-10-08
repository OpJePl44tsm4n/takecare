<?php 

	if( get_row_layout() == 'product_slider' ): 

        $row_title =  get_sub_field('title') ? '<h2>' . get_sub_field('title') . '</h2>' : '';
    
        $max_products = (get_sub_field('max_products') > 0 ) ? get_sub_field('max_products') : 4;
        $featured_products = get_sub_field('featured_products');    

        $products_args = array(
            'post_type'     => 'product',
            'posts_per_page' => $max_products,
        );

        if($featured_products) {
            $products_args['post__in'] = $featured_products;
            $products_args['orderby'] = 'post__in';
        }

        $products = get_posts( $products_args );

        include(locate_template('inc/row-products.php'));

    elseif( get_row_layout() == 'text_slider' ):

        include(locate_template('inc/row-slider.php'));

    elseif( get_row_layout() == 'product_carousel' ): 

        include(locate_template('inc/row-carousel.php'));
    
    elseif( get_row_layout() == 'custom_content' ): 

        include(locate_template('inc/row-content.php'));

    elseif( get_row_layout() == 'post_grid' ): 

        include(locate_template('inc/row-posts.php')); 

    elseif( get_row_layout() == 'team_grid' ): 

        include(locate_template('inc/row-team.php'));    

     elseif( get_row_layout() == 'vacancies' ): 

        include(locate_template('inc/row-vacancies.php'));      

    endif;
