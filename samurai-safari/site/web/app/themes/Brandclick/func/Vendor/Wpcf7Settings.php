<?php 
namespace Brandclick\Vendor;
use Brandclick\Brandclick;

class Wpcf7Settings {

    public function __construct()
    {   
        if(!get_option('wpcf7_lead_count')){
            update_option('wpcf7_lead_count', 0);
        }

        // filters 
        add_filter( 'wpcf7_mail_components',             array( $this, 'filter__wpcf7_mail_components'), 10, 3 );
        add_filter( 'wpcf7_ajax_json_echo',              array( $this, 'filter__wpcf7_add_response_meta'), 10, 4 );

        // actions 
        add_action( 'wpcf7_before_send_mail',            array( $this, 'action__wpcf7_before_send_mail'), 10, 1 ); 

        // shortcodes
        add_shortcode( 'WPCF7_LEAD_COUNT',            array( $this, 'shortcode__wpcf7_lead_count') ); 
    }

    /**
    *   Filter Contact Form 7  Mails content
    *   @return $components 
    */
    public function filter__wpcf7_mail_components( $components, $wpcf7_get_current_contact_form, $instance ) 
    {   
        $components['body'] = apply_filters( 'the_content', $components['body'] );
        return $components; 
    }

    /**
    *   Filter Contact Form 7 Forms 
    *   @return $content 
    */
    public function filter__wpcf7_add_response_meta( $response, $result ) 
    {   
        $count = get_option( 'wpcf7_lead_count', 0); 
        $response["wpcf7_lead_tracking_id"] =  $count;
        $response["result2"] =  $result;
        return $response;
    }
    

    /**
    *   Action update contact counter before we send the mail out 
    *   @return $content 
    */
    public function action__wpcf7_before_send_mail( $content ) 
    {   
        $val = get_option( 'wpcf7_lead_count', 0) + 1; //Increment the current count
        update_option('wpcf7_lead_count', $val);

        return $content;
    }

    /**
    *   Shortcode to output the current contact count 
    *   @return count (int)
    */
    public function shortcode__wpcf7_lead_count() 
    {   
        return 'lead ID: ' . get_option( 'wpcf7_lead_count', 0);
    } 
}



