<?php 
namespace Greylabel\Account;
use Greylabel\Greylabel;

class AccountLogin {

    public function __construct()
    {   
        add_action('wp_ajax_nopriv_ajaxlogin',              array( $this, 'action__handle_ajax_login_request') );
        add_action('wp_ajax_ajaxlogin',                     array( $this, 'action__handle_ajax_login_request') );

        add_action('wp_ajax_ajaxlogout',                    array( $this, 'action__handle_ajax_logout_request') );  
        add_action('wp_ajax_nopriv_ajaxlogout',             array( $this, 'action__handle_ajax_logout_request') );  
    }

    public function action__handle_ajax_login_request()
    {
        // First check the nonce, if it fails the function will break
        if ( ! isset( $_POST['security_login_nonce'] ) || ! wp_verify_nonce( $_POST['security_login_nonce'], 'ajax_login_action' ) ){
            echo json_encode(array('loggedin'=>false, 'message'=>__('Invalid request, not able to validate')));
            die();
        }

        $info = array();
        $info['user_login'] = sanitize_text_field( $_POST['username']);
        $info['user_password'] = sanitize_text_field( $_POST['password']);
        $info['remember'] = true;

        $user_signon = wp_signon( $info, false );
        if ( is_wp_error($user_signon) ){
            echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
        } else {
            wp_set_current_user($user_signon->ID);
            wp_set_auth_cookie($user_signon->ID);
            echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
        }

        die();
    }


    public function action__handle_ajax_logout_request()
    {   
        if ( ! isset( $_POST['security_logout_nonce'] ) || ! wp_verify_nonce( $_POST['security_logout_nonce'], 'ajax_logout_action' ) ){
            echo json_encode(array('loggedin'=>false, 'message'=>__('Invalid request, not able to validate')));
            die();
        }

        wp_clear_auth_cookie();
        wp_logout();
        ob_clean();
        echo json_encode(array('loggedin'=>false, 'message'=>__('Logout successful!')));
        wp_die();
    }

}

