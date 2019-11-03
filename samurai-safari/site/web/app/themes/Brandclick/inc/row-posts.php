<?php 
	$type = get_sub_field('type');
	$posts_array = get_sub_field('posts');
	$max_posts = get_sub_field('max_posts');
	$categories = get_sub_field('category');
	$dosiers = get_sub_field('dosier');
	
	if($type == 'top_posts' && $top_posts = get_option('ga_top_page_views')) {
		$type = 'post';
		$posts_array = $top_posts['top_post_ids'] ?: false;
	}

	$args = array(
		'post_type' 	=> $type,
		'posts_per_page' => $max_posts,
		'suppress_filters' => 0, // Fix for WPML to only get posts in current language 
	);

	if($posts_array) {
		$args['post__in'] = $posts_array;
		$args['orderby'] = 'post__in';
	}	

	$args['tax_query'] = [];
	$args['tax_query']['relation'] = 'OR';

	if($categories) {
		$category = [
			'taxonomy' => 'category',
			'field'    => 'id',
			'terms'    => $categories,
			'operator' => 'IN',
		];

	  	$args['tax_query'][] = $category;
	}
	if($dosiers) {
		$dosier = [
			'taxonomy' => 'dossier',
			'field'    => 'id',
			'terms'    => $dosiers,
			'operator' => 'IN',
		];
		$args['tax_query'][] = $dosier;
	}

	// The Query
	$post_array = get_posts( $args );

	if ( $post_array ) : ?>


		<section <?php if($row_id) echo 'id="'.$row_id.'"'; ?> class="row post-grid content-block <?php echo $background . ' ' . $css_selector; ?>">

			<div class="<?php echo $container; ?>">
			<?php if ($row_title = get_sub_field('title') ): ?>
				<header>
					<div class="d-md-flex d-block">
						<div class="mr-auto"><h2><?php echo $row_title;?></h2></div>
						<?php echo sprintf('<a href="%s">%s <i class="fa fa-angle-right"></i></a>',
								get_post_type_archive_link( 'post' ), 
								__('Check out all posts', WhiteLabelTheme::THEME_SLUG )
							);	
						?>
					</div>
				</header>
			<?php endif; ?>
				<div class="row justify-content-md-center">
					<?php foreach ( $post_array as $post ) : 
  						setup_postdata( $post );

						include(locate_template('inc/partials/grid-post.php'));

					endforeach; 
					wp_reset_postdata(); ?>
				</div>	
				
				

			</div>
		</section>

	<?php endif; ?>
