<?php 
namespace Brandclick\Woocommerce\Email;
use Brandclick\Brandclick;

class wcEmailSettings {

    public function __construct()
    {   
        // Filters
        add_filter('woocommerce_email_settings',                    array( $this, 'filter__add_setting_fields'), 10, 1 );
        add_filter('woocommerce_email_order_item_quantity',         array( $this, 'filter__email_product_count'), 10, 2 );

        // Actions
        add_action('woocommerce_email_header',                      array( $this, 'action__email_header_wrap_div_start'), 99, 2);
        add_action('woocommerce_email_order_details',               array( $this, 'action__email_header_wrap_div_end'), 1);
    }    

    /**
    *   Add extra admin setting to WC emails 
    */
    public function filter__add_setting_fields( $settings )
    {   
        $updated_settings = array();

        foreach ( $settings as $section ) {

            if( isset( $section['id'] ) && $section['id'] === 'woocommerce_email_header_image' ) {
                $updated_settings[] = array(
                    'name'     => __( 'Header Logo (png/jpeg)', Brandclick::THEME_SLUG ),
                    'desc_tip' => __( 'Url of the logo used in all woocommerce emails headers', Brandclick::THEME_SLUG ),
                    'id'       => 'woocommerce_email_header_logo',
                    'type'     => 'text',
                    'css'      => 'min-width:300px;',
                    'std'      => '',  // WC < 2.0
                    'default'  => '',  // WC >= 2.0
                );

                $updated_settings[] = array(
                    'name'     => __( 'Track and trace (png/jpeg/gif)', Brandclick::THEME_SLUG ),
                    'desc_tip' => __( 'Url of the image used for track and trace info', Brandclick::THEME_SLUG ),
                    'id'       => 'woocommerce_email_track_and_trace_img',
                    'type'     => 'text',
                    'css'      => 'min-width:300px;',
                    'std'      => '',  // WC < 2.0
                    'default'  => '',  // WC >= 2.0
                );

                $updated_settings[] = array(
                    'name'     => __( 'Delivery location (png/jpeg/gif)', Brandclick::THEME_SLUG ),
                    'desc_tip' => __( 'Url of the image used for delivery location info', Brandclick::THEME_SLUG ),
                    'id'       => 'woocommerce_email_delivery_location_img',
                    'type'     => 'text',
                    'css'      => 'min-width:300px;',
                    'std'      => '',  // WC < 2.0
                    'default'  => '',  // WC >= 2.0
                );
            }

            if( isset( $section['id'] ) && $section['id'] === 'woocommerce_email_footer_text' ) {
                $updated_settings[] = array(
                    'name'     => __( 'Header image secondary', Brandclick::THEME_SLUG ),
                    'desc_tip' => __( 'Url of the image used for the email header (used for order complete)', Brandclick::THEME_SLUG ),
                    'id'       => 'woocommerce_email_header_image_secondary',
                    'type'     => 'text',
                    'css'      => 'min-width:300px;',
                    'std'      => '',  // WC < 2.0
                    'default'  => '',  // WC >= 2.0
                );
            }

            $updated_settings[] = $section;
        }

        return $updated_settings;
    }

    /**
     * [filter__email_product_count description]
     * @param  [type] $item_qty [description]
     * @param  [type] $item     [description]
     * @return [type]           [description]
     */
    public function filter__email_product_count($item_qty, $item) 
    {
        return $item_qty . 'x'; 
    }


    public function action__email_header_wrap_div_start( $email_heading, $email )
    {   
        // safe te email id so we can use it in email-footer.php
        $GLOBALS['email_id'] = isset($email->id) ? $email->id : false;

        if( isset($email->id) && $email->id == 'customer_completed_order'){ 
            $img = get_option( 'woocommerce_email_header_image_secondary' ); 
        } else {
            $img = get_option( 'woocommerce_email_header_image' ); 
        }

        echo sprintf('<div class="card header">
            <img src="%s" alt="%s" /><div class="content"><div style="width:100%%;padding-bottom:1em">',
            esc_url( $img ),
            get_bloginfo( 'name' )
        );
    }

    public function action__email_header_wrap_div_end()
    {
        echo '</div></div></div>';
    }
}

