<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<h1><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></h1>

<?php 
/**
 * Show user-defined additonal content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( __( $additional_content, WhiteLabelTheme::THEME_SLUG ) ) ) );
} else {

}

$tt_link = $order->get_meta('TrackAndTraceCode');
$tt_code = $order->get_meta('TrackAndTraceBarCode'); 

if($tt_link && $tt_code){ 
	echo sprintf('<a class="btn-primary" href="%s">%s</a>', $tt_link, __('Track your order', WhiteLabelTheme::THEME_SLUG )); 
}  

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2 style="padding:25px"><?php echo sprintf( __('Details about your order', WhiteLabelTheme::THEME_SLUG ), get_bloginfo( 'name' ) ); ?></h2>
<table style="margin-bottom:2em;width:100%;">
	<?php if($img = get_option( 'woocommerce_email_track_and_trace_img' )) : 

		if($tt_link && $tt_code){ ?>
			<tr>
				<td align="center" style="width:30%;padding-bottom: 3em;">
					<img src="<?php echo $img ?>" alt="track and trace">
				</td>
				<td style="width:69%;padding-bottom: 3em;">
					<h3><?php echo __('Track & trace', WhiteLabelTheme::THEME_SLUG ); ?></h3>
					<p><?php echo sprintf('<a href="%s">%s</a>', $tt_link, $tt_code); ?></p>
				</td>	
			</tr>
		<?php }  
	endif; 

	if($img = get_option( 'woocommerce_email_delivery_location_img' )) : ?>
		<tr>
			<td align="center" style="width:30%;padding-bottom: 3em;">
				<img src="<?php echo $img ?>" alt="location">
			</td>
			<td style="width:69%;padding-bottom: 3em;">
				<h3><?php echo __('Delivery address', WhiteLabelTheme::THEME_SLUG ); ?></h3>
				<p><?php echo $order->get_formatted_shipping_address(); ?></p>
			</td>
		</tr>
	<?php endif; ?>
</table>

<div style="margin-bottom: 40px; padding: 0 25px;">
	<h2><?php echo sprintf( __('If you are not at home...', WhiteLabelTheme::THEME_SLUG ), get_bloginfo( 'name' ) ); ?></h2>

	<p><?php echo __('then we will try to deliver your package to the neighbors if it does not fit in the letterbox. The courier always leaves a message so that you know what you can do.', WhiteLabelTheme::THEME_SLUG ); ?></p>

	<?php if( $faq = get_page_by_path( 'faq' ) ): 
		$faq_id = apply_filters( 'wpml_object_id', $faq->ID, 'post' ); 
		$faq_link = sprintf( '<a href="%s">%s</a>', get_permalink($faq_id), __('frequently asked questions (FAQ)', WhiteLabelTheme::THEME_SLUG ) ); ?>
		
		<h2><?php echo __('A question regarding your order?', WhiteLabelTheme::THEME_SLUG ); ?></h2>  

		<p><?php echo sprintf('%s %s',
			sprintf( __('Please have a look at our %s page.', WhiteLabelTheme::THEME_SLUG ), $faq_link ),
			__('Here you’ll find questions and answers regarding shipping, delivery time, our products and more.', WhiteLabelTheme::THEME_SLUG )
		); ?></p> 

	<?php endif; ?>
</div>

<?php 
/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
