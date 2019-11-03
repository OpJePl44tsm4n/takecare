<?php 
namespace Brandclick\Woocommerce\Admin;
use Brandclick\Brandclick;

class OrderStatuses {

    public function __construct()
    {   
        // Add Actions
        add_action( 'init', 									array( $this, 'action__register_custom_order_statuses'));
        add_action( 'admin_head', 								array( $this, 'action__custom_order_status_packing_css'));


        // Filters
        add_filter( 'wc_order_statuses',        				array( $this, 'filter__add_custom_order_statuses'));
    }    

    public function action__register_custom_order_statuses()
    {
    	register_post_status('wc-packing ', array(
	        'label' => __( 'Packing',  Brandclick::THEME_SLUG ),
	        'public' => true,
	        'exclude_from_search' => false,
	        'show_in_admin_all_list' => true,
	        'show_in_admin_status_list' => true,
	        'label_count' => _n_noop('Packing <span class="count">(%s)</span>', 'Packing <span class="count">(%s)</span>')
	    ));
    }


    public function filter__add_custom_order_statuses($order_statuses)
    {
    	$new_order_statuses = array();

	    // add new order status before processing
	    foreach ($order_statuses as $key => $status) {
	        $new_order_statuses[$key] = $status;
	        if ('wc-processing' === $key) {
	            $new_order_statuses['wc-packing'] = __('Packing', Brandclick::THEME_SLUG  );
	        }
	    }
	    return $new_order_statuses;
    }

  
	public function action__custom_order_status_packing_css() {
	    ?>
	    <style>
	        .order-status.status-packing {
	            color: #2196F3;
    			background: #b5e8ff;
	        }
	    </style>
	    <?php
	}
    
}

