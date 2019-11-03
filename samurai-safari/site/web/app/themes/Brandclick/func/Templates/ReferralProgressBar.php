<?php 
	$current_user = wp_get_current_user();
	$referral_bonus = $current_user ? get_user_meta( $current_user->ID, 'referral_bonus', true) : 10;
?>

<section id="referral-widget">
	<div id="referral-progress-bar">
		<h2><?php _e('Your discount', WhiteLabelTheme::THEME_SLUG); ?>:</h2>
		
		<div class="progress-widget">
		
			<div class="progress-labels">
				<span>25%</span>
				<span>50%</span>
				<span>75%</span>
				<span>100%</span>
			</div>

		  	<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $referral_bonus; ?>" aria-valuemin="0" aria-valuemax="100"><div data-width="<?php echo $referral_bonus; ?>" style="width: <?php echo $referral_bonus; ?>%" class="progress-value"><div class="progress-value-label"><?php echo $referral_bonus; ?>%</div></div></div>

		</div>
	</div>

	<?php if ( is_user_logged_in() ){ 
		$referral_id = get_user_meta($current_user->ID, 'referral_id', true);
		
		if( ! $referral_id ) {
			$referral_id = 'PL-test-coupon';
		}
		
	    $referral_link = get_permalink( get_option('woocommerce_myaccount_page_id') ) . '?referral=' . $referral_id;
	    $referral_share_quote_personal = __('Hi! I would love to invite you to the recycled denim project of Palais the l\'eau. Soon, they\'ll crowdfund the most sustainable baby towel ever made. Join now for free and you\'ll immediately receive a 10% discount on a product of your choice.', WhiteLabelTheme::THEME_SLUG);
	    $referral_share_quote = __('Meet Palais de l\'eau\'s sustainable baby essentials. Join the family for free and you\'ll immediately receive a 10% discount on a product of your choice. Also, very soon they will be crowdfunding the most sustainable baby towel ever made with recycled denim and you\'ll be the first to know the launch date!', WhiteLabelTheme::THEME_SLUG);
		$referred_users = get_user_meta($current_user->ID, 'referred_user_ids', true) ?: []; ?>
		
	
		<?php if( $referral_bonus > 0 ) { ?>
			<div class="referral-coupon">
				<input id="referral-id" type="text" name="" value="<?php echo $referral_id; ?>" readonly="readonly">
				<button type="button" class="btn btn-primary btn-copy js-tooltip js-copy" onclick="copyReferralLink(this)" data-copy-id="referral-id" data-toggle="tooltip" data-placement="bottom" title="<?php _e('Copy your coupon code', WhiteLabelTheme::THEME_SLUG); ?>">
			    	<?php _e('Copy coupon', WhiteLabelTheme::THEME_SLUG); ?>
			    </button>
			</div>
		<?php } ?>

		<div class="share-link">

			<?php if( count($referred_users) > 0 ) {
				echo sprintf( '<p class="referral-count"><b>%s</b> %s</p>', 
					count($referred_users), 
					__('friends have joined! Keep it up!', WhiteLabelTheme::THEME_SLUG) 
				);
			} ?>

			<h2><?php _e('Your unique Link', WhiteLabelTheme::THEME_SLUG); ?>:</h2>
			<p><?php _e('Share your unique ID with as many friends as possible to gain more discount!', WhiteLabelTheme::THEME_SLUG); ?></p>
			<input id="referral-link" type="url" name="" value="<?php echo $referral_link; ?>" readonly="readonly">
			<button type="button" class="btn btn-primary btn-copy js-tooltip js-copy" onclick="copyReferralLink(this)" data-copy-id="referral-link" data-toggle="tooltip" data-placement="bottom" title="<?php _e('Copy to clipboard', WhiteLabelTheme::THEME_SLUG); ?>">
		     	<i class="fa fa-copy"></i>
		    </button>
			
			<div class="social-share">
				<small><?php _e('Share your link on social media', WhiteLabelTheme::THEME_SLUG); ?></small>
				<ul class="socials">
					<li><a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($referral_link); ?>&quote=<?php echo urlencode($referral_share_quote); ?>" target="_blank" class="social-btn" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"><i class="fa fa-facebook"></i></a></li>
					<li><a href="http://www.twitter.com/intent/tweet?url=<?php echo urlencode($referral_link); ?>&text=<?php echo urlencode($referral_share_quote); ?>" target="_blank" class="social-btn" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"><i class="fa fa-twitter"></i></a></li>
					<li><a href="https://api.whatsapp.com/send?phone=&text=<?php echo rawurlencode($referral_share_quote_personal); ?> <?php echo rawurlencode($referral_link); ?>" target="_blank" class="social-btn"><i class="fa fa-whatsapp"></i></a></li>
				</ul>
			</div>	

		    <script type="text/javascript">
		    	
		    	function copyReferralLink(e){
		    		copyButton = document.getElementById(e.dataset.copyId);
		    		copyButton.select();

		    		/* Copy the text inside the text field */
		    		document.execCommand("copy");
		    	};

		    </script>
		</div>	

	<?php } else { 
		$signup_link = get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>
		
		<div class="sign-up">
			<p>
				<?php echo sprintf( '%s <a href="%s">%s</a>, %s',  
					__('Sign up now and get the first', WhiteLabelTheme::THEME_SLUG),
					$signup_link,
					__('10% discount', WhiteLabelTheme::THEME_SLUG),
					__('on us!', WhiteLabelTheme::THEME_SLUG)
				); ?>
			</p>
			<a href="<?php echo $signup_link; ?>" class="btn btn-primary " title="<?php _e('Sign up', WhiteLabelTheme::THEME_SLUG); ?>">
	   			<?php _e('Sign up', WhiteLabelTheme::THEME_SLUG); ?>
	    	</a>
		</div>

	<?php } ?>
	
</section>