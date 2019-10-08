<?php 
namespace Greylabel\Register;
use Greylabel\Greylabel;

class RegisterShortcodes {

    public function __construct()
    {   
       add_shortcode( 'CALLTRACKER',                                 array( $this, 'shortcode__register_calltracker') );
       add_shortcode( 'MAILTRACKER',                                 array( $this, 'shortcode__register_mailtracker') );
       add_shortcode( 'MAILHEADER',                                  array( $this, 'shortcode__register_mailheader') );
       add_shortcode( 'MAILFOOTER',                                  array( $this, 'shortcode__register_mailfooter') );
       add_shortcode( 'ORDER',                                       array( $this, 'shortcode__register_order') );
       add_shortcode( 'LOGINFORM',                                   array( $this, 'shortcode__register_ajax_user_login_form') );  
       add_shortcode( 'MAILCHIMP_SIGNUP',                            array( $this, 'shortcode__register_mailchimp_signup') );  
       add_shortcode( 'SOCIAL_BUTTONS',                              array( $this, 'shortcode__register_social_buttons') );  

    }

    /**
    *  Call Tracker Callback
    */
    public function shortcode__register_calltracker( $atts ) 
    {   
        $atts = shortcode_atts( array(
            'tel'           => get_option('call_tracker_tel'),
            'tel-formatted' => get_option('call_tracker_tel_formatted') ? get_option('call_tracker_tel_formatted') : get_option('call_tracker_tel'),
        ), $atts, 'CALLTRACKER' );

        if ( $atts['tel'] !== '' ): 
           
            $tracker = '<div class="track-phone contact"><button type="button" data-tracking="data-layer" data-log-type="conversie" data-log-value="belContact"><i class="fa fa-mobile" aria-hidden="true"></i> '. __('Show phone number' , Greylabel::THEME_SLUG ) .'</button> <a class="call-link" href="tel:'. $atts['tel'] .'"> '. __('Call' , Greylabel::THEME_SLUG ) . ' ' . $atts['tel-formatted'] .'</a></div>';
        
            return $tracker; 
        else: 
            if (current_user_can('activate_plugins')) {
                return '<i style="color:red;">'. sprintf( __( 'Please make sure you have added the "tel" to the shortcode or added  "tracker tel" in the <a style="color:red;" href="%s">options page</a>', Greylabel::THEME_SLUG  ), admin_url('admin.php?page=Greylabel_options') ) .'</i>';
            } else {
                return;
            }

        endif;
    }

    /**
    *   Mail Tracker Callback
    */
    public function shortcode__register_mailtracker( $atts ) 
    {   
        $atts = shortcode_atts( array(
            'mail'            => get_option('contact_mail') ? get_option('contact_mail') : ''
        ), $atts, 'MAILTRACKER' );

        if ( $atts['mail'] !== '' ) : 
            $tracker = '<a href="mailto:'. $atts['mail'] .'" class="contact track-mail" ><i class="fa fa-envelope-o" aria-hidden="true"></i>'. $atts['mail'] .'</a>';
        
            return $tracker; 
        else: 
            if (current_user_can('activate_plugins')) {
                return '<i style="color:red;">'. sprintf( __( 'Please make sure you have added the "mail" attribute to the shortcode or added the "contact mail" in the <a style="color:red;" href="%s">options page</a>', Greylabel::THEME_SLUG ), admin_url('admin.php?page=Greylabel_options') ) .'</i>';
            } else {
                return;
            }

        endif;
    }



    /**
    *   Mail Header
    */
    public function shortcode__register_mailheader() 
    { 
        return get_template_part('func/Templates/EmailHeader');
    }

    /**
    *   Mail Footer
    */
    public function shortcode__register_mailfooter() 
    { 
        return get_template_part('func/Templates/EmailFooter');
    }

     /**
    *   Mail Footer
    */
    public function shortcode__register_order($atts) 
    {   
        $atts = shortcode_atts( array(
            'field' => ''
        ), $atts, 'ORDER' ); 

        if( ! is_wc_endpoint_url( 'order-received' ) ) {
            return; // Exit
        }

        global $wp;
        $order_id  = absint( $wp->query_vars['order-received'] );

        if ( empty($order_id) || $order_id == 0 ) {
            return; // Exit;
        }

        $order = new \WC_Order($order_id);

        switch ($atts['field']) {
            case 'first-name':
                $content = $order->get_billing_first_name();
                break;
            case 'last-name':
                $content = $order->get_billing_last_name();
                break;
            case 'order-id':
                $content = $order->get_id();
                break;
            default:
                ob_start(); 
                do_action( 'woocommerce_thankyou', $order_id );
                $content = ob_get_clean();
                break;    
        }

        return $content;
    }

    /**
    *   Ajax user login form
    */
    public function shortcode__register_ajax_user_login_form() 
    {
        if (is_user_logged_in()) return;
        
        return get_template_part('func/Templates/LoginFormAjax');
    }

    /**
    *   Mailchimp 
    */
    public function shortcode__register_mailchimp_signup() 
    {   
        ob_start();
            get_template_part('inc/partials/form-mailchimp-sm');
        $content = ob_get_clean();
        return $content; 
    }

    /**
    *   Social list
    */
    public function shortcode__register_social_buttons() 
    {   
        $socials = ['facebook', 'instagram', 'linkedin']; 
        $list_items = '';

        foreach ($socials as $social) {
            if ($url = get_option( $social . '_url' ) ) {  
                $list_items .= '<li><a class="social-btn" href="'. $url .'"><i class="fa fa-' . $social . '"></i></a></li>'; 
            }
        }
     
        if ( $list_items ) {
            return '<ul class="socials">' . $list_items . '</ul>'; 
        }

        return;    
    }
}
