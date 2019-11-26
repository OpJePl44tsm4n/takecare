<?php 
	$title = get_sub_field('title');
	$posts = get_sub_field('posts');
	$max_posts = get_sub_field('max_posts');
	
	$args = array(
		'post_type' 	=> 'vacancy',
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

		<section class="row vacancies">

				
			<div class="container">
				
				<?php if ($row_title = get_sub_field('title') ): ?>
					<header>
						<div class="d-flex justify-content-start">
							<div class="mr-auto"><h2><?php echo $row_title;?></h2></div>
						</div>
					</header>
				<?php endif; ?>
			
				<?php foreach ( $posts as $post ) : 
					setup_postdata( $post ); ?>

					<article class="row">
						<div class="col-12 col-sm-2 col-lg-2">
							<a href="<?php the_permalink(); ?>">
					            <?php echo get_the_post_thumbnail($post->ID, 'thumb'); ?>
					        </a>
						</div>
						
						<div class="content col-12 col-sm-10 col-lg-10">
				            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				            <?php the_excerpt(); ?>
				            <a class="btn btn-link" href="<?php the_permalink(); ?>">
					            <?php _e('Read more', TakeCareIo::THEME_SLUG ); ?>
					        </a>
				        </div>

					</article>	

				<?php endforeach; 
				wp_reset_postdata(); ?>
			
			</div>
		</section>

	<?php endif; ?>
