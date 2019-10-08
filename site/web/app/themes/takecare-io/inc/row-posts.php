<?php 
	$type = get_sub_field('type');
	$posts = get_sub_field('posts');
	$max_posts = get_sub_field('max_posts');
	
	$args = array(
		'post_type' 	=> $type,
		'posts_per_page' => $max_posts,
		'suppress_filters' => 0 // Fix for WPML to only get posts in current language 
	);

	if($posts) {
		$args['post__in'] = $posts;
		$args['orderby'] = 'post__in';
	}
	
	// The Query
	$posts = get_posts( $args );
	
	if ( $posts ) : ?>

		<section class="row post-grid">
			<div class="container">
			<?php if ($row_title = get_sub_field('title') ): ?>
				<header>
					<div class="d-flex justify-content-start">
						<div class="mr-auto"><h2><?php echo $row_title;?></h2></div>
						<?php echo sprintf('<a href="%s">%s <i class="fa fa-angle-right"></i></a>',
								get_post_type_archive_link( 'post' ), 
								__('View all articles', TakeCareIo::THEME_SLUG )
							);	
						?>
					</div>
				</header>
			<?php endif; ?>
				<div class="row justify-content-md-center">
					<?php foreach ( $posts as $post ) : 
  						setup_postdata( $post );
					
						include(locate_template('inc/partials/grid-post.php'));

					endforeach; 
					wp_reset_postdata(); ?>
				</div>

			</div>
		</section>

	<?php endif; ?>
