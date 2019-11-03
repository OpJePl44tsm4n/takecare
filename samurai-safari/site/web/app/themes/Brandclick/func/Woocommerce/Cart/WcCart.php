<?php 
namespace Brandclick\Woocommerce\Cart;
use Brandclick\Brandclick;

class WcCart {

	public function __construct()
  	{	
        // Actions
        add_action( 'wp_ajax_cart_update',                      array( $this, 'action__ajax_cart_update') );
        add_action( 'wp_ajax_nopriv_cart_update',               array( $this, 'action__ajax_cart_update') );
        add_action( 'wp_ajax_cart_ajaxlogin',                   array( $this, 'action__ajax_login') );
        add_action( 'wp_ajax_nopriv_ajaxlogin',                 array( $this, 'action__ajax_login') );     

        // Filters
        add_filter( 'woocommerce_add_to_cart_fragments',        array( $this, 'filter__update_cart_on_ajax_add') );
        add_filter( 'woocommerce_cart_item_name',               array( $this, 'filter__update_cart_item_name'), 10, 3 ); 
        add_filter( 'woocommerce_widget_cart_item_quantity',    array( $this, 'filter__add_cart_item_quantity_input'), 10, 3 );
        add_filter( 'woocommerce_package_rates',                array( $this, 'filter__hide_shipping_when_free_is_available'), 10, 2 );
    
    }    

    /**
    *   Add AJAX cart_update functionality to cart
    */
    public function action__ajax_cart_update() 
    {
        if( $_POST['update_type'] === 'add_coupon' ) {
            $coupon = $_POST['coupon']; 
            WC()->cart->add_discount( $coupon );
        } else {         

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
            {
                if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
                {   
                    if( $_POST['update_type'] === 'product_remove' ) {
                        WC()->cart->remove_cart_item( $cart_item_key );
                    }
                    
                    if( $_POST['update_type'] === 'product_quantity' ) {
                        $product_count = $_POST['product_count']; 
                        WC()->cart->set_quantity( $cart_item_key, $product_count );
                    }  
                }
            }
        }   

        $this->callback__update_ajax_cart();

        die(); // always die after AJAX call ends
    }

    /**
    *   callback to handle Ajax login request 
    */
    public function action__ajax_login() 
    {   
        // First check the nonce, if it fails the function will break
        check_ajax_referer( 'ajax-login-nonce', 'security' );

        // Nonce is checked, get the POST data and sign user on
        $info = array();
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = true;

        $user_signon = wp_signon( $info, false );

         // Fragments and mini cart are returned
        $data = array(
            'loggedin' => false,
            'message' => __('Wrong username or password.', Brandclick::THEME_SLUG )
        );

        if ( !is_wp_error($user_signon) ){
            $data['loggedin'] = true;
            $data['message'] = __('Login successful, redirecting...', Brandclick::THEME_SLUG );
        }

        $this->callback__update_ajax_cart( $data );

        die();
    }

    /**
    *   callback to update Ajax cart 
    */
    public function callback__update_ajax_cart( $data = [] ) 
    {   
        $fragments = [];
      
        $defaults = array(
            'loggedin' => false,
            'message' => false,
            'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', $fragments ),
            // 'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
        );

        $data = wp_parse_args( $data, $defaults );
        wp_send_json( $data );
    }

    /**
    *   Update the cart after a change is made trough AJAX
    */
    public function filter__update_cart_on_ajax_add( $fragments ) 
    {   
        // Get mini cart
        ob_start(); 
        woocommerce_mini_cart(); 
        $cart = ob_get_clean();
        $fragments['div.cart-ajax'] = '<div class="cart-ajax">'. $cart . '</div>';
        
        
        // Get cart togle button
        ob_start(); 
        include(locate_template('func/Templates/CartTogleAjax.php'));
        $cart_totals = ob_get_clean();
        $fragments['div.cart-totals-ajax'] = $cart_totals;
        
        
        if( 0 == get_current_user_id() ) {
            ob_start(); 
            echo do_shortcode('[LOGINFORM]'); 
            $login = ob_get_clean();
            $fragments['div.login-ajax'] = $login;
        } else {
            $fragments['div.login-ajax'] = '';
        }

        return $fragments;
    }

    /**
    *   Update the cart item title
    */
    public function filter__update_cart_item_name( $title, $int1, $int2 ) 
    {   
        if ( is_checkout() ) {
            return $title; 
        }

        $title = sprintf('<h3 class="product-title">%s</h3>', $title);
        return $title; 
    }

    /**
    *   Change quantity to input
    */
    public function filter__add_cart_item_quantity_input( $quantity, $cart_item, $cart_item_key ) 
    {   
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

        $product_quantity = woocommerce_quantity_input( array(
            'input_id'     => $_product->get_id(),
            'input_name'   => $cart_item_key,
            'input_value'  => $cart_item['quantity'],
            'max_value'    => $_product->get_max_purchase_quantity(),
            'min_value'    => '0',
            'product_name' => $_product->get_name(),
        ), $_product, false );  

        $quantity = sprintf( '%s  %s', $product_price, $product_quantity );

        return $quantity; 
    }


    /**
    *   Remove shipping cost when shipping is free
    */
    public function filter__hide_shipping_when_free_is_available( $rates, $package ) 
    {   
        $new_rates = array();
        foreach ( $rates as $rate_id => $rate ) {
            // Only modify rates if free_shipping is present.
            if ( 'free_shipping' === $rate->method_id ) {
                $new_rates[ $rate_id ] = $rate;
                break;
            }
        }

        if ( ! empty( $new_rates ) ) {
            //Save local pickup if it's present.
            foreach ( $rates as $rate_id => $rate ) {
                if ('local_pickup' === $rate->method_id ) {
                    $new_rates[ $rate_id ] = $rate;
                    break;
                }
            }
            return $new_rates;
        }

        return $rates;
    }

}