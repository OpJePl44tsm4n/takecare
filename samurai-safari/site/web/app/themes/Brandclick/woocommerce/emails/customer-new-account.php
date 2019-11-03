<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s Customer username */ ?>
<h1><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user_login ) ); ?></h1>
<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ 
        $url = parse_url(get_home_url());
?>
<p><?php printf( __( 'Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: <a href="%3$s">%4$s</a>', 'woocommerce' ), esc_html( $blogname ), '<strong>' . esc_html( $user_login ) . '</strong>',  esc_url( wc_get_page_permalink( 'myaccount' ) ), $url['host'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>
	<?php /* translators: %s Auto generated password */ ?>
	<p><?php printf( esc_html__( 'Your password has been automatically generated: %s', 'woocommerce' ), '<strong>' . esc_html( $user_pass ) . '</strong>' ); ?></p>
<?php endif; ?>

<?php 
        $current_user = get_user_by( 'login', $user_login);                                           
        $code = md5(time());                                                           // creates md5 code to verify later
        $string = array('id'=>$current_user->ID, 'code'=>$code);                       // makes it into a code to send it to user via email
        update_user_meta($current_user->ID, 'is_activated', 0);                        // creates activation code and activation status in the database
        update_user_meta($current_user->ID, 'activationcode', $code);
        
        // create the activation mail 
        $url = get_permalink( get_option('woocommerce_myaccount_page_id') ) . '?p=' .base64_encode( serialize($string));
        $activation_link = sprintf( '<a class="btn-primary" style="margin-bottom:1em;" href="%s">%s</a>', $url, __('verify now', WhiteLabelTheme::THEME_SLUG ) );     
		$message = __('Please verify your email address and complete the registration process.', WhiteLabelTheme::THEME_SLUG ); 

?>
<p style="font-weight:900;"><?php echo $message; ?></p>
<?php
echo $activation_link;

/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
