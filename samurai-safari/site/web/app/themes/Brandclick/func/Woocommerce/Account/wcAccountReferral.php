<?php 
namespace Brandclick\Woocommerce\Account;
use Brandclick\Brandclick;

class wcAccountReferral {

    public function __construct()
    {   
        // Actions 
        add_action( 'init',                                         array( $this, 'action__add_custom_account_endpoint') );
        add_action( 'woocommerce_account_referrals_endpoint',       array( $this, 'action__add_custom_account_page_content') );
        add_action( 'woocommerce_created_customer',                 array( $this, 'action__generate_referral_id') );
        add_action( 'woocommerce_register_form_start',              array( $this, 'action__add_registration_input_fields') );
        add_action( 'woocommerce_account_dashboard',                array( $this, 'action__add_content_to_dashboard') );
        add_action( 'woocommerce_login_form_start',                 array( $this, 'action__add_content_to_login_form') );
        add_action( 'woocommerce_register_form_start',              array( $this, 'action__add_content_to_register_form') );
        
        add_action( 'woocommerce_cart_calculate_fees',              array( $this, 'action__recalculate_personal_coupon'));

        if( get_option('mailtrap_toggle') || (defined('WP_ENV') && 'staging' === WP_ENV) ){
            add_action( 'woocommerce_checkout_order_processed',     array( $this, 'action__reset_referral_bonus_after_payment' ), 99, 1);
        } else {
            add_action( 'woocommerce_payment_complete',             array( $this, 'action__reset_referral_bonus_after_payment' ), 99, 1);
        } 

        // Filters
        add_filter( 'woocommerce_account_menu_items',               array( $this, 'filter__add_custom_account_page_to_menu'), 10, 1 );
        add_filter( 'wp_authenticate_user',                         array( $this, 'filter__check_user_referral_points'), 9 ,2);

        // shortcodes 
        add_shortcode( 'REFERRAL_PROGRESS_BAR',                     array( $this, 'shortcode__register_referral_progress_bar') );  
    }    


    /**
     * [action__add_custom_account_endpoint description]
     * @return [type] [description]
     */
    public function action__add_custom_account_endpoint() {
        add_rewrite_endpoint( 'referrals', EP_PAGES );

        // run create content pages if option is nog set 
        if( is_admin() && ! get_option('wc_account_referral_pages') ) { 
            self::callback__create_editable_account_subpages(); 
        }

    }


    /**
     * [action__add_custom_account_page_content description]
     * @return [type] [description]
     */
    public function action__add_custom_account_page_content() { 
        include(locate_template('func/Templates/ReferralPage.php'));
    }


    /**
     * [action__generate_referral_id description]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public function action__generate_referral_id( $user_id ) {
        
        if ( isset( $_POST['referred_by_id'] ) ) {
            
            // get the user that referred the current user
            $referred_by_user = get_users(
                array(
                    'meta_key' => 'referral_id',
                    'meta_value' => sanitize_text_field( $_POST['referred_by_id'] ),
                    'number' => 1,
                    'count_total' => false
                )
            );

            if(isset($referred_by_user[0]->ID)) {
 
                if ( $referred_user_ids = get_user_meta( $referred_by_user[0]->ID, 'referred_user_ids', true) ) { 
                    $referred_user_ids[] = $user_id; 
                } else {
                    $referred_user_ids = [ $user_id ];
                }

                update_user_meta( $referred_by_user[0]->ID, 'referred_user_ids', $referred_user_ids );
            }

        }

        $referral_id = self::callback__create_referral_id( $user_id );
        
    }


    /**
     * [action__ad_registration_input_fields description]
     * @return [type] [description]
     */
    public function action__add_registration_input_fields()
    {   
        if(isset($_GET['referral']) && $_GET['referral'] !== ''){ ?>

            <input type="hidden" class="input-text" name="referred_by_id" id="referred_by_id" value="<?php esc_attr_e( $_GET['referral'] ); ?>" />

        <?php }
    }


    /**
     * [action__add_content_to_dashboard description]
     * @return [type] [description]
     */
    public function action__add_content_to_dashboard()
    {   

        if ($dashboard_page_id = get_option('wc_account_dashboard_page_id')) :
            $dashboard_page_id = apply_filters( 'wpml_object_id', $dashboard_page_id, 'post' ); 
            $dashboard_page = get_post($dashboard_page_id);

            if( have_rows('page_rows', $dashboard_page) ):

                while ( have_rows('page_rows', $dashboard_page) ) :  the_row();
                   include(locate_template('inc/rows.php'));
                endwhile; 

            endif; 
        endif; 
    }

    /**
     * [action__add_content_to_login_form description]
     * @return [type] [description]
     */
    public function action__add_content_to_login_form()
    {   
        echo sprintf( '<p style="display:block; padding: 0 0 1.2em;">%s</p>', 
            __('Already have an account? Login here to check how much <b>discount you have collected!</b>', Brandclick::THEME_SLUG )
        );
    }

    /**
     * [action__add_content_to_register_form description]
     * @return [type] [description]
     */
    public function action__add_content_to_register_form()
    {   
        echo sprintf( '<p style="display:block; padding: 0 0 1.2em;">%s</p>', 
            __('Earn <b>10% discount now</b> if you leave your email address and start inviting friends & family for even more discount!', Brandclick::THEME_SLUG )
        );
    }

    /**
     * [action__recalculate_personal_coupon description]
     * @return [type] [description]
     */
    public function action__recalculate_personal_coupon()
    {   
        $current_user = wp_get_current_user();
        $referral_bonus = get_user_meta( $current_user->ID, 'referral_bonus', true);
        $coupon_id = get_user_meta( $current_user->ID, 'referral_coupon_id', true);
        $referral_id = get_user_meta( $current_user->ID, 'referral_id', true);

        if($referral_bonus && $coupon_id && WC()->cart->has_discount($referral_id) ){
            $highest_price = 0;

            foreach ( WC()->cart->get_cart() as $cart_item ) {
                if ( $cart_item['data']->get_price() > $highest_price ) {
                    $highest_price = $cart_item['data']->get_price();
                }
            }
            $highest_price  =  ($highest_price / 100) * $referral_bonus;
            update_post_meta( $coupon_id, 'coupon_amount', $highest_price );
        }
    }


    /**
     * [action__reset_referral_bonus_after_payment description]
     * @param  [type] $order_id [description]
     * @return [type]           [description]
     */
    public function action__reset_referral_bonus_after_payment( $order_id )
    {
   
        if ( ! $order_id )
            return;

        // Getting an instance of the order object
        $order = wc_get_order( $order_id );
        $user_id = $order->get_user_id();
        $referral_id = get_user_meta( $user_id, 'referral_id', true);

        if( $order_coupons = $order->get_used_coupons() ) {
            
            foreach( $order_coupons as $coupon) {

                if($coupon == strtolower($referral_id)) {
                    update_user_meta( $user_id, 'referral_bonus', 0);
                    $referral_bonus_times_used = get_user_meta( $user_id, 'referral_bonus_times_used', true) + 1;
                    update_user_meta( $current_user->ID, 'referral_bonus_used', $referral_bonus_times_used);
                }
            }
        } 

        return;
    }


    /**
     * [filter__add_custom_account_page_to_menu description]
     * @param  [type] $items [description]
     * @return [type]        [description]
     */
    public function filter__add_custom_account_page_to_menu( $items )
    {   
        $items['referrals'] = __( 'Referrals',  Brandclick::THEME_SLUG );

        return $items;
    }


    /**
     * [filter__check_user_referral_points update the users bonus on login]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function filter__check_user_referral_points( $user ) {

        if ( $referred_user_ids = get_user_meta( $user->ID, 'referred_user_ids', true) ) { 
            // set referral bonus to 10 when first buy
            $referral_bonus = (get_user_meta( $user->ID, 'referral_bonus_times_used', true) == 0) ? 10 : 0;
            
            foreach ($referred_user_ids as $userID) {
               $has_activation_status = get_user_meta($userID, 'is_activated', false);

                // checks if this is an account with activation status;
                if ($has_activation_status) { 

                    $isActivated = get_user_meta($userID, 'is_activated', true);

                    // only update if user is active
                    if ( !$isActivated ) {
                        continue;
                    } else {
                        $referral_bonus += 5; 
                    }

                } else {
                    // (backwards compatible) we would like to give the bonus to people who did not had to activate before
                    $referral_bonus += 5; 
                }   
            }    

            if ( $referral_bonus > 100 ) {
                $referral_bonus = 100; 
            }

            update_user_meta( $user->ID, 'referral_bonus', $referral_bonus);
        } 

        return $user;
    }


    /**
     * [shortcode__register_referral_progress_bar description]
     * @return [type] [description]
     */
    public function shortcode__register_referral_progress_bar()
    { 
        return get_template_part('func/Templates/ReferralProgressBar');
    }
    
    
    /**
     * [callback__generate_personal_coupon description]
     * @return [type] [description]
     */
    public function callback__generate_personal_coupon( $user_id, $amount, $name ) 
    {   
        $user_info = get_userdata($user_id);
        $discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
                            
        $coupon = array(
            'post_title' => $name,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type'     => 'shop_coupon'
        );
                            
        $new_coupon_id = wp_insert_post( $coupon );
        // safe the coupon id as user meta for update purpose 
        update_user_meta( $user_id, 'referral_coupon_id', $new_coupon_id );

        // Add meta
        update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
        update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
        update_post_meta( $new_coupon_id, 'individual_use', 'yes' ); // not in combination with other coupons
        update_post_meta( $new_coupon_id, 'product_ids', '' );
        update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
        update_post_meta( $new_coupon_id, 'usage_limit', '' );
        update_post_meta( $new_coupon_id, 'expiry_date', '' );
        update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
        update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
        update_post_meta( $new_coupon_id, 'customer_email', array($user_info->user_email) ); // restrict to single user
        
    }

    /**
     * [callback__create_editable_account_subpages description]
     * @return [type] [description]
     */
    public function callback__create_editable_account_subpages() 
    {
        $account_page_id = get_option( 'woocommerce_myaccount_page_id' ); 

        if ( !get_page_by_title( 'Referrals' ) ) {

            $referrals = array(
              'post_title'    => 'Referrals',
              'post_type'     => 'page',
              'post_content'  => '',
              'post_status'   => 'draft',
              'post_parent'   => $account_page_id,
              'post_author'   => 1
            );

            $referral_page = wp_insert_post( $referrals );
            update_option('wc_account_referral_page_id', true);
        } 

        if ( !get_page_by_title( 'Dashboard' ) ) {

            $dashboard = array(
              'post_title'    => 'Dashboard',
              'post_type'     => 'page',
              'post_content'  => '',
              'post_status'   => 'draft',
              'post_parent'   => $account_page_id,
              'post_author'   => 1
            );
             
            $dashboard_page = wp_insert_post( $dashboard );
            update_option('wc_account_dashboard_page_id', $dashboard_page);
        }


        update_option('wc_account_referral_pages', true);
    }

    public function callback__create_referral_id( $user_id )
    {
        // Create a new referral id for the new user
        $current_user = get_userdata( $user_id );
        $referral_id = 'PL-' . substr(md5( $current_user->user_email ), 0, 8);
        update_user_meta( $current_user->ID, 'referral_id', $referral_id );
        update_user_meta( $current_user->ID, 'referral_bonus', 10);
        update_user_meta( $current_user->ID, 'referral_bonus_times_used', 0);
        $this->callback__generate_personal_coupon( $current_user->ID,  10, $referral_id); 

        return $referral_id;
    }

}

