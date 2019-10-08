<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php _e( "Jouw bestelling is aangekomen en wordt verwerkt. Bekijk de bestelling hier onder:", 'thunderplugs' ); ?></p>

<?php 
	
    if( get_post_meta($order->get_id(), 'virtual_product', true) ): ?> 

		<p><?php _e( "Let op: Dit zijn de volgende stappen voor je Custom Fitted oordoppen:", 'thunderplugs' ); ?></p>

		<ol>
			<li><?php _e( "Binnen 48 uur (op werkdagen) neemt onze gecertificeerde gehoor-expert contact op om een aanmeet afspraak te maken.", 'thunderplugs' ); ?></li>
			<li><?php _e( "De aanmeet afspraak zal plaatsvinden bij jou thuis/op kantoor op een dag/tijd naar keuze. Tijdens deze aanmeet afspraak krijg je alle informatie over filters, materiaal en gebruiksadvies. Samen met onze gehoor-expert stel je jouw oordop op maat samen. Je oren worden opgemeten voor de perfecte pasvorm.", 'thunderplugs' ); ?></li>
			<li><?php _e( "Ten slotte worden de oordoppen gemaakt en naar je adres opgestuurd. Dit duurt ongeveer 3 weken.", 'thunderplugs' ); ?></li>
		</ol>

	<?php endif; 
/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
