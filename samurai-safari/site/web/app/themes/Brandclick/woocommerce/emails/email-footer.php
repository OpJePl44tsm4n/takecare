<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
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
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
										<?php if( isset($GLOBALS['email_id']) && ($GLOBALS['email_id'] == 'customer_completed_order' || $GLOBALS['email_id'] == 'customer_processing_order' )): ?>
											<tr id="footer_call_to_action">
												<td>
													<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
												</td>
											</tr>
										<?php endif; ?>
									</table>
									<!-- End Body -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="footer_row">
					<td>
						<table align="center" style="max-width: 630px; text-align: left;">
							<tr>
								<td style="text-align: left; padding: 48px 15px;">
									<h2><?php echo __('Service & contact', WhiteLabelTheme::THEME_SLUG );  ?></h2>
								<?php 
								if( $faq = get_page_by_path( 'faq' ) ): 
									$faq_id = apply_filters( 'wpml_object_id', $faq->ID, 'post' ); 
									$faq_link = sprintf( '<a href="%s">%s</a>', get_permalink($faq_id), __('FAQ', WhiteLabelTheme::THEME_SLUG ) );
									?>
									<table align="left" width="49%" border="0" class="mobile_full_width" valign="bottom" >
										<tr>
											<td>
												<h3><?php echo __('Questions', WhiteLabelTheme::THEME_SLUG ); ?></h3>
												<p>
													<?php echo sprintf('%s %s',
														sprintf( __('You can visit our %s page for all your questions.', WhiteLabelTheme::THEME_SLUG ), $faq_link ),
														__('Is your question and/or answer not listed? Please contact us.', WhiteLabelTheme::THEME_SLUG )
													); ?>
												</p>
											</td>
										</tr>	
									</table>
								<?php endif;

								$contact_email = get_option('contact_mail');
								$contact_tel = get_option('call_tracker_tel');
			    				$contact_tel_formatted = get_option('call_tracker_tel_formatted') ?: $contact_tel; 

			    				if($contact_email && $contact_tel): ?>
									<table align="left" width="49%" border="0" class="mobile_full_width" valign="bottom" >
										<tr>
											<td>
												<h3><?php echo __('Contact us', WhiteLabelTheme::THEME_SLUG );  ?></h3>
												<p>
													<?php echo sprintf( '%s <a href="mailto:%2$s" target="_blank">%2$s</a> %3$s <a style="text-decoration:none;" href="tel:%4$s" target="_blank">%5$s</a>', 
														__('Do you have an urgent question or has something gone wrong? Please contact us through:', WhiteLabelTheme::THEME_SLUG ),
														$contact_email,
														__('or', WhiteLabelTheme::THEME_SLUG ),
														$contact_tel,
														$contact_tel_formatted
													); ?>
												</p>
											</td>
										</tr>	
									</table>
								<?php endif; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
