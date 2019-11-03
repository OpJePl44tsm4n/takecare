<?php 
	
	if ( $products ) { 
		$class = $tiny_products ? 'grid-tiny carousel slide' : '';
		?>
		
		<section class="row product-grid">
            <div class="container">

            	<header>
            		<div class="d-md-flex justify-content-start">
						<div class="mr-auto">
							<?php echo $row_title; ?>
						</div>
						<?php 	
							echo sprintf('<a href="%s">%s <i class="fa fa-angle-right"></i></a>',
								wc_get_page_permalink( 'shop' ), 
								__('Check out all products', WhiteLabelTheme::THEME_SLUG )
							);	
						?>
					</div>	
				</header>

				<div <?php  echo $tiny_products ? 'id="TinyProductSlider"' : '';?> class="woocommerce <?php echo $class; ?>">
					<?php woocommerce_product_loop_start();
						global $post; 
						foreach ( $products as $post ) : 
  						setup_postdata( $post );
							wc_get_template_part( 'content', 'product' );
						endforeach;;
						woocommerce_product_loop_end();
					?>	
				</div>
			</div>
		</section>

		<?php wp_reset_postdata();
	}	

