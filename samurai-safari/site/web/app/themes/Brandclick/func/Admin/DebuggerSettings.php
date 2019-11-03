<?php 
namespace Brandclick\Admin;
use Brandclick\Brandclick;


class DebuggerSettings {

    public function __construct()
    
    {
/** Fix ACC
        if( get_option('site_redirection_toggle') || (defined('WP_ENV') && 'staging' === WP_ENV) ){
            $allowed_ips = get_option('site_redirect_allowed_ips');
            $allowed_ips_array = $allowed_ips ? explode(",",$allowed_ips) : []; 
            $redirect_url = get_option('site_redirect_url') ?: '';

            if ( !in_array($_SERVER['REMOTE_ADDR'], $allowed_ips_array) && '' !== $redirect_url ) {
                header("Location: " . $redirect_url); 
                exit();
            }

        } 
**/
        add_action('phpmailer_init',    array( $this, 'action__set_mailtrap') );  
    }

    /**
    *   Set Mailtrap settings 
    */
    static public function action__set_mailtrap($phpmailer) {

        if( get_option('mailtrap_toggle') || (defined('WP_ENV') && 'development' === WP_ENV) ){
            $phpmailer->isSMTP();
            $phpmailer->Host = 'smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = '9d3ed1d2c6232b';
            $phpmailer->Password = '6b2249ee299a85';
        }  

        return;
    }

  

}

