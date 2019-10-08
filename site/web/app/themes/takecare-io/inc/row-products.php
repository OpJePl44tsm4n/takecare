<?php 

	if ( $products ) { ?>
		
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
								__('View all products', TakeCareIo::THEME_SLUG )
							);	
						?>
					</div>	
				</header>

				<div class="woocommerce">
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

