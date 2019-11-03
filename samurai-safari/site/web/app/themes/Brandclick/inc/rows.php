<?php 
    $background = get_sub_field('background_color');
    $fluid_container = get_sub_field('fluid_container');
    $css_selector = get_sub_field('css_selector');
    $row_id = get_sub_field('row_id');
    $vertical_align = get_sub_field('vertical_align_content') ? 'vertical-align' : '';
    $container = $fluid_container ?'container-fluid' : 'container';

    if ($grid_columns = get_sub_field('grid_columns')) {
        $grid_columns = WhiteLabelTheme::calculate_grid_columns($grid_columns, 'md');
    } else {
        $grid_columns = 'col-md-12';
    }


	if( get_row_layout() == 'product_slider' ): 
        Global $tiny_products; 
        $row_title =  get_sub_field('title') ? '<h2>' . get_sub_field('title') . '</h2>' : '';
        $max_products = (get_sub_field('max_products') > 0 ) ? get_sub_field('max_products') : 4;
        $featured_products = is_product() ? wc_get_related_products( get_the_ID() ) : get_sub_field('featured_products');    
        $tiny_products = get_sub_field('tiny_products');    
      
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
    
    elseif( get_row_layout() == 'page_header' ): 

        include(locate_template('inc/row-header.php')); 
    
    elseif( get_row_layout() == 'custom_content' ): 

        include(locate_template('inc/row-content.php'));

    elseif( get_row_layout() == 'post_grid' ): 

        include(locate_template('inc/row-posts.php'));      

    endif;
