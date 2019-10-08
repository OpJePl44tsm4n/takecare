<?php 
	$background = get_sub_field('background_color');
	$bg_image = get_sub_field('background_image');
	$bg_image = $bg_image ? wp_get_attachment_image_url( $bg_image , 'full' ) : false; 
	$css_selector = get_sub_field('css_selector');
	$row_id = get_sub_field('row_id');
	$vertical_align = get_sub_field('vertical_align_content') ? 'vertical-align' : '';
	$grid_columns = get_sub_field('grid_columns');
	$grid_columns = TakeCareIo::calculate_grid_columns($grid_columns, 'md');
	$fluid_container = get_sub_field('fluid_container');
	$container = $fluid_container ?'container-fluid' : 'container';

	if( have_rows('content_blocks') ){ ?>

	<section <?php if($row_id) echo 'id="'.$row_id.'"'; ?> class="row row-<?php echo $row_count; ?> content-block <?php echo $background . ' ' . $css_selector; ?>">
		
		<?php 
		if( $bg_image ) { ?>
			<div class="thumb">
				<img class="bg-image" src="<?php echo $bg_image; ?>" alt="background">
			</div>
		<?php } ?>

		<div class="<?php echo $container; ?>">
			<div class="row">

				<?php 
				$column_counter = 0;
				while ( have_rows('content_blocks') ) : the_row(); 
					$layout = get_row_layout(); ?>

					<div class="grid-item <?php echo $grid_columns[$column_counter] . ' ' . $layout . ' ' . $vertical_align; ?>">
						<div class="content">
						
							<?php 
							if( $layout == 'editor' ):
								the_sub_field('content');

							elseif( $layout == 'animation' ) :
								$video_id = get_sub_field('video');
								$image_id = get_sub_field('image');
								include(locate_template('inc/partials/video-animation.php')); 

							elseif( $layout == 'faq_list' ) :
								$title = get_sub_field('title');
								$questions = get_sub_field('questions');
								include(locate_template('inc/partials/list-faq.php'));

							elseif( $layout == 'post_list' ) :
								include(locate_template('inc/partials/list-post.php')); 		

							elseif( $layout == 'logo_grid' ) :
								include(locate_template('inc/partials/slider-logos.php'));	 	

							elseif( $layout == 'single_image' ) :
								$image_id = get_sub_field('image');
								$link = get_sub_field('link');
								if ($link) {
									echo '<a href="'. $link .'">';
									echo wp_get_attachment_image( $image_id, 'large' );
									echo '</a>';
								} else {
									echo wp_get_attachment_image( $image_id, 'large' );
								}

							elseif( $layout == 'single_video' ) :
								$video_url = get_sub_field('video');
				 				$image_id = get_sub_field('image');
								include(locate_template('inc/partials/video-inline.php')); 		

							elseif( $layout == 'mailchimp_form' ) :
								include(locate_template('inc/partials/form-mailchimp.php')); 
							
							elseif( $layout == 'store_locator' ) :
        						include(locate_template('inc/partials/store-locator.php'));

        					elseif( $layout == 'map_basic' ) :
        						$focus_point = get_sub_field('focus_point');
							    $popup_content = get_sub_field('popup_content');
							    $zoom_level = get_sub_field('zoom_level') ?: 10;
        						
        						include(locate_template('inc/partials/map-basic.php'));       

        					elseif( $layout == 'kiyoh_reviews' ) :
        						include(locate_template('inc/partials/reviews.php'));

        					elseif( $layout == 'search_form' ) :
								$title = get_sub_field('title');
								$search_placeholder = get_sub_field('search_placeholder');
								include(locate_template('inc/partials/search.php'));	        

							elseif( $layout == 'contact_form' ) :
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
<?php }
