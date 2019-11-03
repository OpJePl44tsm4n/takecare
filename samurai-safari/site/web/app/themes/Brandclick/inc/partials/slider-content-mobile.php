

            <?php if ($row_title = get_sub_field('title') ): ?>
                <header>
                    <div class="d-flex justify-content-start">
                        <div class="mr-auto title-content"><h3><?php echo $row_title;?></h3></div>
                    </div>
                </header>
<?php endif; ?>

<div id="reviewCarouselLarge" class="carousel-xs slide contentSlider">
  <div class="carousel-inner">
    <?php if( have_rows('content') ): 
        $count = 0;
        
        while ( have_rows('content') ) : 
            the_row();
            $image_id = get_sub_field('image');
            $image = wp_get_attachment_image( $image_id, 'medium' );
            $text = get_sub_field('text');


            if($count == 0) $class = "active"; ?>
                <article class="card carousel-item <?php echo $class; ?> ">
                     <?php echo wp_get_attachment_image( $image_id, 'medium' ); ?>                   
                    <h3><?php the_sub_field('title'); ?></h3>
                    <p class="content-description"><?php echo $text; ?></p>
                </article> 
                <?php $count++;
        endwhile; 
    endif;  ?>





</article>