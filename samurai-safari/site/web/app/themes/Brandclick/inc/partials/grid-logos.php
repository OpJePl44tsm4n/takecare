<?php 

    if( have_rows('logos') ): 
        while ( have_rows('logos') ) : 
            the_row();

            $image_id = get_sub_field('logo');
            $image = wp_get_attachment_image( $image_id, 'thumb' );
            $url = get_sub_field('url');

            if ( $url ){
                echo sprintf('<a href="%s">%s</a>', $url, $image );
            } else {
                echo $image; 
            }

        endwhile;
    endif;    

     
        