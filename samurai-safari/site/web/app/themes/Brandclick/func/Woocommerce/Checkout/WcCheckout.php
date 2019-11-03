<?php 
namespace Brandclick\Woocommerce\Checkout;
use Brandclick\Brandclick;

class WcCheckout {

	public function __construct()
  	{	
        // Add Filters
       	add_filter( 'woocommerce_default_address_fields',       array( $this, 'filter__wc_override_adress_fields'), 1 );
        add_filter( 'woocommerce_checkout_fields',              array( $this, 'filter__wc_override_checkout_fields') );
        add_filter( 'woocommerce_order_button_text',            array( $this, 'filter__wc_override_checkout_button_text'), 11, 1 );

        // Add actions
        add_action( 'woocommerce_checkout_order_review',        array( $this, 'action__wc_prepend_order_review'), 1 );
        add_action( 'woocommerce_checkout_order_review',        array( $this, 'action__wc_append_order_review'), 11 );
        add_action( 'woocommerce_checkout_update_order_meta',   array( $this, 'action__fix_billing_adress'), 10 ,2 );
        add_action( 'woocommerce_before_checkout_form',         array( $this, 'action__wc_checkout_progress_bar'), 10 ,1 );
        
    }    

    /**
    *   add placeholders to the adress fields (Both billing and shipping adress)
    */
    public function filter__wc_override_adress_fields($fields) 
    {   
        // add placeholders to all fields
        $fields['first_name']['placeholder'] = sprintf('%s*', __('First name', Brandclick::THEME_SLUG ));
        $fields['last_name']['placeholder'] =  sprintf('%s*', __('Last name', Brandclick::THEME_SLUG ));
        $fields['company']['placeholder'] =  __('Company name', Brandclick::THEME_SLUG );
        $fields['address_1']['placeholder'] = sprintf('%s*', __('Street name', Brandclick::THEME_SLUG ));
        $fields['address_2']['placeholder'] = sprintf('%s*', __('House number', Brandclick::THEME_SLUG ));
        $fields['city']['placeholder'] = sprintf('%s*', __('City', Brandclick::THEME_SLUG ));
        $fields['state']['placeholder'] =  sprintf('%s*', __('State', Brandclick::THEME_SLUG ));
        $fields['postcode']['placeholder'] = sprintf('%s*', __('Postcode', Brandclick::THEME_SLUG ));

        // Set required fields 
        $fields['address_2']['required'] = true;
        
        // unset the labels (this causes the field name not to show up in the allert [Billing Last name is a required field.] )
        // unset($fields['last_name']['label']);
        
        // change the labels
        $fields['address_1']['label'] = __('Street name', Brandclick::THEME_SLUG );
        $fields['address_2']['label'] = __('House number', Brandclick::THEME_SLUG );

        // swap the order of fields 
        $fields['company']['priority'] = 5;
        $fields['state']['priority'] = 50;
        $fields['postcode']['priority'] = 55;
        $fields['address_2']['priority'] = 60; // house number
        $fields['address_1']['priority'] = 65;
        
        /* normal Woocommerce order: 
            first_name  = 10
            last_name   = 20
            company     = 30
            country     = 40
            address_1   = 50
            address_2   = 60
            city        = 70
            state       = 80
            postcode    = 90
        */

        // add row classes to show on the same line
        $fields['address_1']['class'] = ['form-row-first'];
        $fields['city']['class'] = ['form-row-last'];

        $fields['postcode']['class'] = ['form-row-first'];
        $fields['address_2']['class'] = ['form-row-last'];

        return $fields;
    }

    /**
    *   add placeholders to the adress fields & unset labels
    */
    public function filter__wc_override_checkout_fields($fields) 
    {   
        // add placeholders to all fields
        $fields['billing']['billing_phone']['placeholder'] =  sprintf('%s*', __('Phone', Brandclick::THEME_SLUG ));
        $fields['billing']['billing_email']['placeholder'] =  sprintf('%s*', __('Email address', Brandclick::THEME_SLUG ));
        $fields['order']['order_comments']['placeholder'] =  __('Notes about your order, e.g. special notes for delivery.', Brandclick::THEME_SLUG );

        // swap the order of fields 
        $fields['billing']['billing_email']['priority'] = 100;   
        $fields['billing']['billing_phone']['priority'] = 110;

        /* normal Woocommerce order: 
            billing_phone   = 100
            billing_email   = 110
        */

        // add row classes to show on the same line
        $fields['billing']['billing_email']['class'] = ['form-row-first'];
        $fields['billing']['billing_phone']['class'] = ['form-row-last'];
        

        return $fields;
    } 

    /**
    *   Change the checkout button text
    */
    public function filter__wc_override_checkout_button_text($string) 
    {   
        return __('Pay order', Brandclick::THEME_SLUG ); 
    }
    
    /**
    *   prepend html to the order review table
    */
    public function action__wc_prepend_order_review() 
    {   
	    echo sprintf('<div class="order-list"><h3>%1s</h3><a href="%2s">%3s</a>', 
            __('Your Order', Brandclick::THEME_SLUG ),
            wc_get_page_permalink( 'cart' ),
            __('Adjust', Brandclick::THEME_SLUG ) 
        );
    }

    /**
    *   append html to the order review table
    */
    public function action__wc_append_order_review() 
    {   
        echo '</div>';
    }

    /**
    *   merge the adress field with the house number 
    */
    public function action__fix_billing_adress($order_id, $data) 
    {   
        if ( ! $order_id )
            return;

        // Getting an instance of the order object
        $order = wc_get_order( $order_id );
        $order_data = $order->get_data();

        $billing_adress = $order_data['billing']['address_1'] . ' ' . $order_data['billing']['address_2'];
        update_post_meta($order_id, '_billing_address_1', $billing_adress);
        update_post_meta($order_id, '_billing_address_2', '');

        $shipping_adress = $order_data['shipping']['address_1'] . ' ' . $order_data['shipping']['address_2'];
        update_post_meta($order_id, '_shipping_address_1', $shipping_adress);
        update_post_meta($order_id, '_shipping_address_2', '');
    }
	
    public function action__wc_checkout_progress_bar($checkout) 
    {
        ?>
            <div class="steps-container">
                <div class="step step-1 current"><?php _e('Details','brandclick'); ?></div>
                <div class="step step-2"><?php _e('Payment','brandclick'); ?></div>
                <div class="step step-3"><?php _e('Done!','brandclick'); ?></div>
            </div>
        <?php
    }

}