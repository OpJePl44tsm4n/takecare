
<?php 

    if( have_rows('logos') ): ?>
        
            <div class="customer-logos">
  

            <?php while ( have_rows('logos') ) : 
                the_row();

                $image_id = get_sub_field('logo');
                $image = wp_get_attachment_image( $image_id, 'thumb' );
                $url = get_sub_field('url');

                if ( $url ){
                    echo sprintf('<div class="slide"><a href="%s">%s</a></div>', $url, $image );
                } else {
                    echo '<div class="slide">' . $image . '</div>'; 
                }
            endwhile; ?>
        </div>

        <?php wp_enqueue_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js', array( 'jquery' ), '', true ); ?> 
       
    <?php endif;    

     