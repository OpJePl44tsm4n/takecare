<?php  $cart_item_count = WC()->cart->get_cart_contents_count(); ?>
<div class="cart-btn cart-totals-ajax <?php if($cart_item_count === 0){ echo 'empty';} ?> ">
	<a class="account-link" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account', WhiteLabelTheme::THEME_SLUG ); ?>">
		<i class="fa fa-user" style="color:black;"></i>
	</a>
    <button class="cart-toggler ml-auto cart collapsed" type="button" data-toggle="collapse" data-target="#cartCollapse" aria-controls="cartCollapse" aria-expanded="false" aria-label="Togle cart">
	    <?php 
	        echo sprintf('<span class="cart-totals">%s %s %s: %s</span>',
	           	$cart_item_count,
	           	_n( 'article', 'articles', $cart_item_count, WhiteLabelTheme::THEME_SLUG ),
	           	__('Total', WhiteLabelTheme::THEME_SLUG ), 
	            WC()->cart->get_cart_total() 
	        );
	    ?>
	    <span aria-hidden="true"><i class="fa fa-shopping-bag" style="color:black;"></i></span> <?php if($cart_item_count > 0){ ?><span class="cart-item-count"><?php echo $cart_item_count; ?></span><?php } ?>
	</button>
</div>