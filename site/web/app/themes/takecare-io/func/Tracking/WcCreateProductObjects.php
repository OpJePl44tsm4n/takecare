<?php 
namespace Greylabel\Tracking;
use Greylabel\Greylabel;

class WcCreateProductObjects {


  	public function __construct()
  	{
    	add_action("woocommerce_checkout_order_processed", array($this, "create_payment_completed_data_object"), 1, 1);
    	add_action("woocommerce_order_status_completed", array($this, "send_payment_completed_data_object"), 10, 1);
  	}

  	/**
	* Fill the product detail object
	**/
	public function create_payment_completed_data_object( $order_id )
	{

		if ( ! $order_id )
    		return;

		// Getting an instance of the order object
		$order = wc_get_order( $order_id );

		$shipping_total = $order->get_total_shipping();
		$payment_type = $order->get_payment_method_title();
		$total = $order->get_total();
		$total_tax = number_format($order->get_total_tax(), 2, '.', '');
		
		$coupons = '';
		if( $order->get_used_coupons() ) {
	        $order_coupons = $order->get_used_coupons();

	        foreach( $order_coupons as $coupon) {
	            $coupons .= $coupon->id . ', ';
	        }
	    } 

	    $order_obj = [
			'ti' => $order->get_id(),			// Transaction ID.
			'el' => $order->get_id(), 			// event Label
            'tr' => $total,  					// Revenue.
            'tt' => $total_tax,					// Tax.
            'ts' => $shipping_total,			// Shipping.
            'tcc' => $coupons,					// Transaction coupon.
            'pa' => 'purchase',                 // Product action 
        ];  
	    
	    $count = 1; 
		foreach ($order->get_items() as $key => $item) { 
			$order_obj["pr{$count}id"] = $item['product_id'];											// Product 1 ID. Either ID or name must be set.
			$order_obj["pr{$count}nm"] = $item['name'];													// Product 1 name. Either ID or name must be set.
			$order_obj["pr{$count}pr"] = $item['subtotal'];												// Product 1 Price.
			$order_obj["pr{$count}ca"] = get_bloginfo('name');											// Product 1 category.	
			$order_obj["pr{$count}br"] = get_bloginfo('name');											// Product 1 brand.
			$order_obj["pr{$count}va"] = isset($item['variation_id']) ? $item['variation_id'] : '';		// Product 1 variant.
			$order_obj["pr{$count}ps"] = $item['quantity'];												// Product 1 position.

			$count++; 
		}

		$order_analytics_obj = ServerSideAnalytics::gaPrepareTransaction( $order_obj ); 
		if($order_analytics_obj){
		    $order->update_meta_data( 'order_analytics_obj', $order_analytics_obj );
		    $order->save();
		}

		return; 
	}


	/**
	* Send data when order is completed 
	**/
	public function send_payment_completed_data_object( $order_id )
	{ 	
		$order_obj = get_post_meta( $order_id,'order_analytics_obj' ); 
		$sendData = 'false';

		if($order_obj && isset($order_obj[0])){
			$sendData = ServerSideAnalytics::gaSendData($order_obj[0]);
		} 
		
		update_post_meta( $order_id,'order_analytics_send', $sendData );

		return; 	
	}

}
