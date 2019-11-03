<?php 
namespace Brandclick\Woocommerce\Account;
use Brandclick\Brandclick;

class wcAccount {

    public function __construct()
    {   
        // Actions    
        add_action( 'template_redirect',                                        array( $this, 'action__check_user_account_activation') );
        // add_action( 'user_register',                                         array( $this, 'action__resend_user_activation_mail'), 10 ,2);
        add_action( 'woocommerce_before_account_navigation',                    array( $this, 'action__open_mobile_account_page_menu_toggle') );
        add_action( 'woocommerce_after_account_navigation',                     array( $this, 'action__close_mobile_account_page_menu_toggle') );
        add_action( 'woocommerce_my_account_my_orders_column_order-tracking',   array( $this, 'action__add_orders_table_tracking_content') );

        // Filters
        add_filter( 'woocommerce_registration_redirect',                        array( $this, 'filter__redirect_after_user_acount_creation') );
        add_filter( 'wp_authenticate_user',                                     array( $this, 'filter__authenticate_user_account_activation'), 10 ,2); 
        add_filter( 'woocommerce_my_account_my_orders_columns',                 array( $this, 'filter__add_orders_table_columns'), 10 );
    }    

   
    /**
     * [filter__redirect_after_user_acount_creation description]
     * @param  [type] $redirect_to [description]
     * @return [type]              [description]
     */
    public function filter__redirect_after_user_acount_creation( $redirect_to ) 
    {    
        wp_logout();
        wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) . "?n=" );
        exit;
    }

    public function filter__authenticate_user_account_activation( $user ) 
    {     
        if ( !in_array( 'customer', (array) $user->roles ) ) {
           return $user;
        }

        $has_activation_status = get_user_meta($user->ID, 'is_activated', false);

        // checks if this is an older account without activation status; skips the rest of the function if it is
        if ($has_activation_status) { 

            $isActivated = get_user_meta($user->ID, 'is_activated', true);

            if ( !$isActivated ) {

                $account_link = get_permalink( get_option('woocommerce_myaccount_page_id') ) . "?u=" . $user->ID;
                $account_link = sprintf( '<a href="%s">%s</a>', $account_link, __('click here to resend it', Brandclick::THEME_SLUG ) );

                $user = new \WP_Error(
                    'my_theme_confirmation_error',
                    sprintf( __( '<strong>Error:</strong> Your account has to be activated before you can login. Please click the link in the activation email that has been sent to you.<br /> If you do not receive the activation email within a few minutes, check your spam folder or %s.', Brandclick::THEME_SLUG ), $account_link )
                );
            }
        }
        return $user;
    }

    /**
     * [filter__add_orders_table_columns description]
     * @param  [array] $columns [defining the columns: 'column_id' => 'column_name' ]
     * @return [array] $columns [Returns the new array]
     */
    function filter__add_orders_table_columns( $columns )
    {
        // define our new column
        $new_column = [ 'order-tracking' => 'Track & trace'];
            
        // split the $columns array after the 3th item and store the remains in $rest
        $rest = array_splice($columns, 3 );
            
        // add the 3 arrays together 
        $columns = $columns + $new_column + $rest;
            
        // A filter always needs to return the "updated" argument 
        return $columns;
    }


    /**
     * [action__add_orders_table_tracking_content description]
     * @param  [type]   $order [the woocommerce order object]
     * @return [string]        [returns the link to the track and trace if they are available]
     */
    function action__add_orders_table_tracking_content( $order )
    {
        $tt_link = $order->get_meta('TrackAndTraceCode');
        $tt_code = $order->get_meta('TrackAndTraceBarCode'); 

        if($tt_link && $tt_code){ 
            printf('<a class="btn-primary button" target="_blank" href="%s">%s</a>', $tt_link, $tt_code); 
        }  
    }

    /**
     * [action__check_user_account_activation description]
     * @return [type] [description]
     */
    public function action__check_user_account_activation()
    {    
        // stop here if not is account page
        if( !is_account_page() ) {
            return;
        }

        // If accessed via an authentification link
        if(isset($_GET['p'])){
            $data = unserialize(base64_decode($_GET['p']));
            $code = get_user_meta($data['id'], 'activationcode', true);
            $isActivated = get_user_meta($data['id'], 'is_activated', true);            // checks if the account has already been activated.
            
            // check if account is not already activated
            if( $isActivated ) {                                                
                wc_add_notice( __( 'This account has already been activated. Please log in with your username and password.', Brandclick::THEME_SLUG ), 'error' );
            } else {
                
                // checks whether the decoded code given is the same as the one in the database
                if($code == $data['code']){         

                    update_user_meta($data['id'], 'is_activated', 1);             
                    $user_id = $data['id'];                                     
                    $user = get_user_by( 'id', $user_id );

                    if( $user ) {

                        wp_set_current_user( $user_id, $user->user_login );
                        wp_set_auth_cookie( $user_id );
                        do_action( 'wp_login', $user->user_login, $user );
                    }
                    wc_add_notice( __( '<strong>Success:</strong> Your account has been activated! You have been logged in and can now use the site to its full extent.' ), 'notice' );

                } else {
                    $account_link = get_permalink( get_option('woocommerce_myaccount_page_id') ) . "?u=". $data['id'];
                    $account_link = sprintf( '<a href="%s">%s</a>', $account_link, __('resend the activation email', Brandclick::THEME_SLUG ) );

                    wc_add_notice( sprintf( __( '<strong>Error:</strong> Account activation failed. Please try again in a few minutes or %s.<br />Please note that any activation links previously sent lose their validity as soon as a new activation email gets sent.<br />If the verification fails repeatedly, please contact our administrator.', Brandclick::THEME_SLUG ), $account_link), 'error' );
                }
            }
        }

        // If resending confirmation mail
        if(isset($_GET['u'])){                                          
            self::action__resend_user_activation_mail($_GET['u']);
            wc_add_notice( __( 'Your activation email has been resent. Please check your email and your spam folder.', Brandclick::THEME_SLUG ), 'notice' );
        }

        // If account has been freshly created
        if(isset($_GET['n'])){                                          
            wc_add_notice( __( 'Thank you for creating your account. You will need to confirm your email address in order to activate your account. An email containing the activation link has been sent to your email address. If the email does not arrive within a few minutes, check your spam folder.', Brandclick::THEME_SLUG ), 'notice' );
        }
    }


    /**
     * [action__resend_user_activation_mail description]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public function action__resend_user_activation_mail($user_id) 
    {             
        $user_info = get_userdata($user_id);                                            
        $code = md5(time());                                                            // creates md5 code to verify later
        $string = array('id'=>$user_id, 'code'=>$code);                                 // makes it into a code to send it to user via email
        update_user_meta($user_id, 'is_activated', 0);                                  // creates activation code and activation status in the database
        update_user_meta($user_id, 'activationcode', $code);
        
        // create the activation mail 
        $url = get_permalink( get_option('woocommerce_myaccount_page_id') ) . '?p=' .base64_encode( serialize($string));
        $activation_link = sprintf( '<a class="btn-primary" style="margin-bottom:1em;" href="%s">%s</a>', $url, __('click here', Brandclick::THEME_SLUG ) );     

        $heading = sprintf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user_info->user_login ) );
        $message = sprintf( '<h1>%s</h1>
                            <p>%s</p> 
                            %s', 
            $heading, 
            __('Please verify your email address and complete the registration process.', Brandclick::THEME_SLUG ), 
            $activation_link 
        ); 

        $mailer = \WC()->mailer();
        $headers = "Content-Type: text/html\r\n";
        $email = $user_info->user_email;
        $subject = __( 'Activate your Account' );
        $heading = sprintf( __('Welcome to %', Brandclick::THEME_SLUG ), get_bloginfo( 'name' ) );

        $wc_email = new \WC_Email;
        $wrapped_message = $mailer->wrap_message($heading, $message);                   // Wrap message using woocommerce html email template
        $html_message = $wc_email->style_inline($wrapped_message);                      // Style the wrapped message with woocommerce inline styles
        $mailer->send( $email, $subject, $html_message, $headers );
    }


    /**
     * [action__open_mobile_account_page_menu_toggle description]
     * @return [type] [description]
     */
    public function action__open_mobile_account_page_menu_toggle()
    { 
        ?> 
        <div class="navbar-expand-lg">
            <div class="d-block d-lg-none wc-account-nav collapsed"data-toggle="collapse" data-target="#accountCollapse" aria-controls="accountCollapse" aria-expanded="false" aria-label="AccountNavigation">
                <button type="button">
                    <span><?php _e('Menu', Brandclick::THEME_SLUG ); ?></span>
                </button>
            </div>

            <div class="navbar-collapse collapse" id="accountCollapse"> 
        <?php 
    }


    /**
     * [action__ad_mobile_account_page_menu_toggle description]
     * @return [type] [description]
     */
    public function action__close_mobile_account_page_menu_toggle()
    {
        echo '</div></div>';
    }


}

