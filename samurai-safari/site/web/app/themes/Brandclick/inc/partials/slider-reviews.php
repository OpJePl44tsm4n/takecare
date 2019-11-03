<?php if ( $row_title = get_sub_field('title') ): ?>
    <header>
        <div class="d-flex justify-content-center">
            <h2><?php echo $row_title;?></h2>
        </div>
    </header>
<?php endif; ?>


<?php if( have_rows('reviews') ): ?>

    <div id="reviewCarouselLarge" class="carousel slide">
        <div class="carousel-inner">
           <?php 
           $review_count = 0;
           $indicators = '';
            while ( have_rows('reviews') ) : 
                the_row();
                $class = '';
                if($review_count == 0) $class = "active"; 
                $indicators .= '<li data-target="#reviewCarouselLarge" data-slide-to="'. $review_count .'" class="'. $class .'"></li>'; 
                ?>

                <article class="card carousel-item container <?php echo $class; ?>">
                    <div class="row">

                        <header class="col-4 col-md-2 col-lg-2">
                            <div class="thumb">  
                                <?php if( $featured_id = get_sub_field('featured') ) {
                                    echo wp_get_attachment_image( $featured_id, 'thumb' );
                                } ?>
                          </div> 
                        </header>
                        <div class="col-8 col-md-3 col-lg-3">
                            <div class="center">
                                <span class="name"><?php the_sub_field('name'); ?></span><br>
                                <span class="date"><?php the_sub_field('date'); ?></span><br>
                                <span class="city"><?php the_sub_field('place'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-7 col-12 vertical-align">        
                            "<?php the_sub_field('review_text'); ?>"
                        </div>  

                    </div>
                </article>

            <?php $review_count++; endwhile; ?>
        </div>
        
        <?php if($indicators): ?>
            <ol class="carousel-indicators">
                <?php echo $indicators; ?>
            </ol>
        <?php endif; ?>
    </div>


<? endif; ?>     