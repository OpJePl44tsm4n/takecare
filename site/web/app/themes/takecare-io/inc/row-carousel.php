<?php 
	$featured_products = get_sub_field('featured_products');
	$products_args = array(
		'post_type' 	=> 'product',
		'posts_per_page' => $max_products,
	);

	if($featured_products) {
		$products_args['post__in'] = $featured_products;
		$products_args['orderby'] = 'post__in';
	}

	$products = new WP_Query( $products_args );

	if ( $products->have_posts() ):
		$carousel_indicators = '';
		$count = 0; ?>

		<section class="row product-carousel">
			<div class="container">
				
					<div id="productCarouselLarge" class="carousel slide woocommerce">
						<div class="carousel-inner">
							
							<?php while ( $products->have_posts() ) : $products->the_post();
								global $product;

								// Ensure visibility.
								if ( empty( $product ) || ! $product->is_visible() ) {
									return;
								}

								$active = ($count === 0) ? 'active' : ''; ?>

								<div class="carousel-item <?php echo $active ?>">
							        <article <?php wc_product_class(); ?>>
							            <div class="row">
							                <div class="col-md-5 image">
							                	<?php the_post_thumbnail( 'shop_single' ); ?>
							                </div>
							                <div class="col-md-7 product-info">
							                	<div class="content">
							                		<h2><?php the_title(); ?></h2>
							                		<div class="description">
							                			<?php the_excerpt(); ?>
							                		</div>
							                		<?php 
							                		// Show price 
							                		wc_get_template( 'loop/price.php' ); 

							                		// Show add to cart button 
							                		do_action( 'woocommerce_after_shop_loop_item' ); ?>
							                    </div>
							                </div>
							            </div>
							        </article>
							    </div>

								<?php 
								$carousel_indicators .= '<li data-target="#productCarouselLarge" data-slide-to="'. $count .'" class="'. $active .'">'. get_the_title() .'</li>';
								
								$count++;

							endwhile; ?>

						</div>

						<a class="carousel-control-prev" href="#productCarouselLarge" role="button" data-slide="prev"></a>
		                <a class="carousel-control-next" href="#productCarouselLarge" role="button" data-slide="next"></a>

		                <div class="carousel-controls offset-md-5 col-md-7">
		                    <ol class="carousel-indicators">
		                        <?php echo $carousel_indicators; ?>
		                    </ol>
		                </div>
					</div>	
				
			</div>
		</section

	<?php endif; ?>