<?php 

    if( have_rows('logos') ): ?>
        
        <div class="row justify-content-around vertical-align">

            <?php while ( have_rows('logos') ) : 
                the_row();

                $image_id = get_sub_field('logo');
                $image = wp_get_attachment_image( $image_id, 'thumb' );
                $url = get_sub_field('url');

                if ( $url ){
                    echo sprintf('<div class="logo"><a href="%s">%s</a></div>', $url, $image );
                } else {
                    echo '<div class="logo">' . $image . '</div>'; 
                }

            endwhile; ?>
        
        </div>
        
    <?php endif;