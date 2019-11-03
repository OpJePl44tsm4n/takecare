<?php 
namespace Brandclick\Woocommerce\Single;
use Brandclick\Brandclick;

class WcSingleProduct {

	public function __construct()
  	{	
       	// Remove Actions
       	remove_action( 'woocommerce_single_product_summary',                        'woocommerce_template_single_price');
       	remove_action( 'woocommerce_single_product_summary',                        'woocommerce_template_single_title', 5);
        remove_action( 'woocommerce_single_product_summary',                        'woocommerce_template_single_meta', 40);

        remove_action( 'woocommerce_after_single_product_summary',                  'woocommerce_output_related_products', 20);
        remove_action( 'woocommerce_single_product_summary',                        'woocommerce_template_single_rating', 10);
        remove_action( 'woocommerce_single_product_summary',                        'woocommerce_template_single_add_to_cart', 30);

        // add Filters 
        add_filter( 'woocommerce_product_tabs',                                     array( $this,'filter__remove_product_tabs'), 98 );
        add_filter( 'woocommerce_dropdown_variation_attribute_options_args',        array( $this,'filter__wc_variation_dropdown_text'), 10, 1);

        // Remove add to cart message
        add_filter( 'wc_add_to_cart_message_html',                                  '__return_null' );

        // Add Actions
       	add_action( 'woocommerce_single_product_summary',                           'woocommerce_template_single_price', 22 );
        add_action( 'woocommerce_single_product_summary',                           array( $this, 'action__wc_single_title'), 10 );
        add_action( 'woocommerce_single_product_summary',                           array( $this, 'action__ajax_add_to_cart_button'), 30 );
       
         // Add Shortcodes
        add_shortcode( 'FREE_SHIPPING_RATE_TEXT',                                   array( $this, 'shortcode__register_free_shipping_rate_text') ); 
    } 


    /**
    *   Remove the tabs that are not used
    */
    public function filter__remove_product_tabs($tabs) 
    {   
        unset( $tabs['reviews'] ); // Remove the reviews tab
        unset( $tabs['additional_information'] ); // Remove the additional information tab

       return $tabs;
    }

    /**
    * [filter__wc_variation_dropdown_text Change the first option text]
    * @param  [array]  $args [description]
    * @return [array]  $args [description]
    */
    public function filter__wc_variation_dropdown_text($args) 
    {   
        // get the current option taxonomy
        $taxonomy = get_taxonomy($args['attribute']);
    
        //set show_option_none to our new text if the taxonomy is found 
        if($taxonomy) {
            $taxonomy_name = $taxonomy->labels->singular_name;
            $args['show_option_none'] = sprintf( __( 'Choose %s', Brandclick::THEME_SLUG ), $taxonomy_name );
        }
        
        return $args;
    }

    /**
    *   Add the custom title to the single page 
    */
    public function action__wc_single_title() 
    {   
    	echo sprintf('<h2 itemprop="name" class="product_title entry-title">%1s</h2>', get_the_title()); 
    }
	

    /**
    * [shortcode__register_free_shipping_rate_text description]
    * @return [type] [description]
    */
    public function shortcode__register_free_shipping_rate_text()
    {   
        if (defined('REST_REQUEST')){
            return; 
        }
        
        $packages = \WC()->cart->get_shipping_packages();
        $package = reset( $packages );
        $zone = wc_get_shipping_zone( $package );
        $order_min_amount = 0;

        foreach ( $zone->get_shipping_methods( true ) as $k => $method ) {
            if ( $method->id == 'free_shipping' ) {
                $order_min_amount = $method->get_option( 'min_amount' );
            }
        }

        if($order_min_amount){
            $order_min_amount = wc_price($order_min_amount);
            $country = \WC()->customer->get_shipping_country();
             
            if( !$country ){
                $location = \WC_Geolocation::geolocate_ip();
                $country = isset($location['country']) ? $location['country'] : 'NL';
            }

            $country_name = \WC()->countries->countries[$country];
            
            $text = sprintf( __('Free delivery on orders above %s (within %s)!', Brandclick::THEME_SLUG ), $order_min_amount, $country_name  );
            
            return sprintf( "<p style=\"color:var(--success);line-height: 1.5;\"><strong>%s</strong></p>" , $text );
        }

        return;
    }


    /**
     * [action__ajax_add_to_cart_button description]
     * @return [type] [description]
     */
    public function action__ajax_add_to_cart_button() 
    {
        global $product;  

        echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
            sprintf( '<a href="%s" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="%s" data-product_sku="%s" aria-label="Add “%s” to your cart" rel="nofollow">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->get_id() ),
                esc_attr( $product->get_sku() ),
                esc_attr( $product->get_title() ),
                esc_html( $product->add_to_cart_text() )
            ),
        $product);

        $top_views = get_option('ga_top_page_views'); 
        $product_view_count = isset($top_views['top_product_count']) ? $top_views['top_product_count'] : [];

        if(isset($product_view_count[$product->get_id()])){
           echo sprintf('<div class="view-count pt-3 text-danger"><small>%s %s</small></div>',  $product_view_count[$product->get_id()], __('times viewed in the last 24 hours', Brandclick::THEME_SLUG ) ); 
        }
    }
	

}