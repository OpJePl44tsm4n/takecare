<?php 

	if( have_rows('content_blocks') ): ?>

	<section <?php if($row_id) echo 'id="'.$row_id.'"'; ?> class="row content-block <?php echo $background . ' ' . $css_selector; ?>">
		<div class="<?php echo $container; ?>">
			<div class="row">

				<?php 
				$column_counter = 0;
				while ( have_rows('content_blocks') ) : the_row(); ?>

					<div class="grid-item <?php echo $grid_columns[$column_counter] . ' ' . get_row_layout() . ' ' . $vertical_align; ?>">
						<div class="content">
						
							<?php 
							if( get_row_layout() == 'editor' ):
								the_sub_field('content'); 

							elseif( get_row_layout() == 'animation' ) :
								$video_id = get_sub_field('video');
								$image_id = get_sub_field('image');
								include(locate_template('inc/partials/video-animation.php')); 

							elseif( get_row_layout() == 'faq_list' ) :
								$title = get_sub_field('title');
								$questions = get_sub_field('questions');
								include(locate_template('inc/partials/list-faq.php'));

							elseif( get_row_layout() == 'post_list' ) :
								include(locate_template('inc/partials/list-post.php')); 

							elseif( get_row_layout() == 'logo_grid' ) :
								include(locate_template('inc/partials/grid-logos.php'));

							elseif( get_row_layout() == 'single_image' ) :
								$image_id = get_sub_field('image');
								echo wp_get_attachment_image( $image_id, 'large' );

							elseif( get_row_layout() == 'single_video' ) :
								$video_url = get_sub_field('video');
								$image_id = get_sub_field('image');
								include(locate_template('inc/partials/video-inline.php'));

							elseif( get_row_layout() == 'mailchimp_form' ) :
								include(locate_template('inc/partials/form-mailchimp.php'));

							elseif( get_row_layout() == 'slider_reviews' ) :
								include(locate_template('inc/partials/slider-reviews.php'));

							elseif( get_row_layout() == 'content_slider' ) :
								include(locate_template('inc/partials/slider-content-mobile.php'));

							elseif( get_row_layout() == 'slider_media_reviews' ) :
								include(locate_template('inc/partials/slider-media-reviews.php'));
							
							elseif( get_row_layout() == 'store_locator' ) :
								include(locate_template('inc/partials/store-locator.php'));

        					elseif( get_row_layout() == 'map_basic' || get_row_layout() == 'basic_map'  ) :
        						include(locate_template('inc/partials/map-basic.php'));

							elseif( get_row_layout() == 'kiyoh_reviews' ) :
								include(locate_template('inc/partials/reviews.php'));

							elseif( get_row_layout() == 'search_form' ) :
								$title = get_sub_field('title');
								$search_placeholder = get_sub_field('search_placeholder');
								include(locate_template('inc/partials/search.php'));

							elseif( get_row_layout() == 'contact_form' ) :
								$form = get_sub_field('contact_form');
								if ($form) {
									echo do_shortcode( '[contact-form-7 id="'. $form->ID .'" title="'. $form->post_title .'"]' );
								}
							endif; ?>

						</div>
					</div> 
					
					<?php 
					if( $column_counter == (count($grid_columns) - 1) ) {
						$column_counter = 0;
					} else {
						$column_counter++;
					}

				endwhile; ?>

			</div>	
		</div>
	</section>

<?php endif; ?>