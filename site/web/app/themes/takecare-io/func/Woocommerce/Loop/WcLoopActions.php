<?php 
namespace Greylabel\Woocommerce\Loop;
use Greylabel\Greylabel;

class WcLoopActions {

	public function __construct()
  	{	
        // Actions
        add_action('woocommerce_after_shop_loop_item',    array( $this, 'action__add_content_after_shop_loop_item'), 9 );
        add_action('woocommerce_before_shop_loop_item',   array( $this, 'action__add_content_before_shop_loop_item'), 9 );
        add_action('woocommerce_shop_loop_item_title',    array( $this, 'action__add_excerpt'), 11 );
        add_action('woocommerce_before_shop_loop',        array( $this, 'action__remove_page_specific_hooks'));

        // Remove Actions
        remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
        remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_after_shop_loop_item_title' , 'woocommerce_template_loop_rating', 5 );

        // Filters 
        add_filter('woocommerce_variable_price_html',       array( $this, 'filter__singular_variation_price'), 10, 2 ); 
    }    

    /**
    *   Add content before shop loop item 
    */
    public function action__add_content_before_shop_loop_item() 
    {
        if(is_shop()){
            echo '<div class="container">';
        }
    }

	/**
    *   Add content after shop loop item 
    */
    public function action__add_content_after_shop_loop_item() 
    {
        global $product;
        if(is_shop()){
            // Add the read more info button before the add to cart
        //     $link = '<div class="read-more-wrap"><a href="%s" class="btn btn-link read-more">%s</a></div>';
        //     echo sprintf($link, get_permalink( $product->get_id() ),  __( 'More information', Greylabel::THEME_SLUG  ));
        // } else {
        //     $link = '<div class="read-more-wrap"><a href="%s" class="btn btn-link read-more">%s</a></div>';
        //     echo sprintf($link, get_permalink( $product->get_id() ),  __( 'More information', Greylabel::THEME_SLUG  ));
            echo '</div>' /* close container div */;
        }
    }

    /**
    *   Remove page specific hooks 
    */
    public function action__remove_page_specific_hooks() 
    {
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
    }


    /**
    *   Add the exerpt on the shop page 
    */
    public function action__add_excerpt()
    {   
        global $product;
        $product_link = get_permalink( $product->get_id() );
        $tiny_description = sprintf( '<p class="tiny-desc">%s</p>', get_field('tiny_description')) ?: '' ;

        if(is_shop()){
            $exerpt =  get_the_excerpt();
            echo sprintf( '<div class="content">
                <h2 class="woocommerce-loop-product__title shop-title"><a href="%s">%s</a></h2>
                %s
                %s',
                $product_link,  
                get_the_title(), 
                $tiny_description,
                apply_filters('the_content', $exerpt )
            );
        } else {
            echo $tiny_description;
        }    
    }

    /**
    *   Filter only show the cheapest price 
    */
    public function filter__singular_variation_price( $price, $product ) 
    {
        $price = '' . wc_price($product->get_price()); 

        return $price;
    }

}