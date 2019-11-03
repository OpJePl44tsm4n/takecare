<div id="MediaReviewCarouselLarge" class="carousel slide" data-ride="carousel">

    <?php if ( $row_title = get_sub_field('title') ): ?>
        <h2><?php echo $row_title;?></h2>
    <?php endif; ?>
    
    <?php if( have_rows('reviews') ): ?>

        <div class="carousel-inner">
            <?php 
            $review_count = 0;
            $indicators = '';
            while ( have_rows('reviews') ) : 
                the_row();
                $class = '';
                if($review_count == 0) $class = "active"; 
                $indicators .= '<li data-target="#MediaReviewCarouselLarge" data-slide-to="'. $review_count .'" class="'. $class .'"></li>'; ?>

                <div class="carousel-item <?php echo $class; ?>">

                    <div class="content">     
                        <?php if($link = get_sub_field('link')){ ?>
                            <a href='<?php echo $link; ?>' target="_blank"> 
                        <?php } ?>     
                        
                            <p><?php the_sub_field('quote'); ?></p>
                            <p class="author"><?php the_sub_field('name'); ?></p>
                            <?php if( $logo_id = get_sub_field('logo') ) {
                                echo wp_get_attachment_image( $logo_id, 'thumb' );
                            } ?>

                        <?php if($link){ ?>
                            </a>
                        <?php } ?>     
                    </div> 

                </div>

            <?php $review_count++; endwhile; ?>

        </div>

        <?php if($indicators): ?>
            <ol class="carousel-indicators">
                <?php echo $indicators; ?>
            </ol>
        <?php endif; ?>

    <? endif; ?>    

</div>


     