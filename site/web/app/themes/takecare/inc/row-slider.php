<?php 
	$row_title =  get_sub_field('title') ? '<h2>' . get_sub_field('title') . '</h2>' : '';
	$row_content = get_sub_field('content');
		
	if( have_rows('text_blocks') ): 
		$carousel_slides = '';
		$carousel_indicators = '';
		$count = 0; 

		while ( have_rows('text_blocks') ) : the_row();

			$title = get_sub_field('title') ? '<h1>' . get_sub_field('title') . '</h1>' : '';
			$image_id = get_sub_field('image');
			$image = wp_get_attachment_image( $image_id, 'large' );
			$content = get_sub_field('content'); 
			$active = ($count === 0) ? 'active' : '';

			$carousel_slides .= '<div class="carousel-item '. $active .'">
                <article class="d-block col-4">
                    <div class="row">
                        <div class="col-md-5 image">
                        '. $image .'
                        </div>
                        <div class="col-md-7">
                        	<div class="content">
	                            '. $title .
	                            $content .'
	                        </div>
                        </div>
                    </div>
                </article>
            </div>';


			$carousel_indicators .= '<li data-target="#contentCarousel" data-slide-to="'. $count .'" class="'. $active .'">'. ($count + 1) .'</li>';
			
			$count++;

		endwhile; ?>

		<section class="row secondary-bg-color block-carousel">
			<header class="container">
				<?php 
					echo $row_title;
					echo $row_content;
				?>
			</header>
			
			<div id="contentCarousel" class="carousel slide multi-item-carousel" data-ride="carousel">
				<div class="carousel-inner"><?php echo $carousel_slides; ?></div>

				<a class="carousel-control-prev" href="#contentCarousel" role="button" data-slide="prev"></a>
                <a class="carousel-control-next" href="#contentCarousel" role="button" data-slide="next"></a>

                <div class="carousel-controls">
                    <a class="control-btn control-prev" href="#contentCarousel" role="button" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <ol class="carousel-indicators">
                        <?php echo $carousel_indicators; ?>
                    </ol>
                    <a class="control-btn control-next" href="#contentCarousel" role="button" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
			</div>	

		</section>
	<?php endif;
