<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
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

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<div style="margin-bottom: 40px; padding: 0 25px;">
	<h2>
		<?php 
		if ( $sent_to_admin ) {
			$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
			$after  = '</a>';
			echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
		} else {
			echo __('Your order', WhiteLabelTheme::THEME_SLUG );
		}
		/* translators: %s: Order ID. */
		
		?>
	</h2>

	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<tbody>
			<?php
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => true,
					'image_size'    => array( 110, 110 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);
			?>
		</tbody>
	</table>
</div>

<?php if( $email->id !== 'customer_completed_order'): ?>
	<table id="order_item_totals" class="card" style="width: 100%; padding-bottom:25px;">
		<?php
		$item_totals = $order->get_order_item_totals();
		unset($item_totals['cart_subtotal']); 
		// unset($item_totals['payment_method']);
		
		if ( $item_totals ) {
			$i = 0;
			foreach ( $item_totals as $total ) {
				$i++;
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:left;"><?php echo wp_kses_post( $total['label'] ); ?></th>
					<td class="td" style="text-align:right; <?php echo ($total['value'] == 'Free shipping') ? 'color:#74BB48;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
				<?php
			}
		} ?>
	</table>

	<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); 

	if ( $order->get_customer_note() ) { ?>
		<div class="card">
			<?php if ( $sent_to_admin ) { 
				echo sprintf('<h2>%s</h2>', __('Customer note', WhiteLabelTheme::THEME_SLUG ) );
			} else {
				echo sprintf('<h2>%s</h2>', __('Your note', WhiteLabelTheme::THEME_SLUG ) );
			} ?>
			<p><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></p>
		</div>			
	<?php } 
endif;
