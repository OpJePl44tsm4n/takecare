<?php 
namespace Greylabel\Woocommerce\Single;
use Greylabel\Greylabel;

class WcSingleProduct {

	public function __construct()
  	{	
       	// Remove Actions
       	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price');
       	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

        // add Filters 
        add_filter( 'woocommerce_product_tabs',  array( $this,'filter__remove_product_tabs'), 98 );
        
        // Add Actions
       	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 22 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'action__wc_single_title'), 10 );
       
    
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
    *   Add the custom title to the single page 
    */
    public function action__wc_single_title() 
    {
    	echo sprintf('<h2 itemprop="name" class="product_title entry-title">%1s</h2>', get_the_title()); 
    }
	
	

}