<?php 
	$current_user = wp_get_current_user();
	$referral_id = get_user_meta($current_user->ID, 'referral_id', true);
    $referred_users = get_user_meta($current_user->ID, 'referred_user_ids', true) ?: []; 
    
?>

<div id="referral-page">
	
	<?php if ($referral_page_id = get_option('wc_account_referral_page_id')) {
        $referral_page_id = apply_filters( 'wpml_object_id', $referral_page_id, 'post' ); 
        $referral_page = get_post($referral_page_id); ?>
		
		<h2><?php echo $referral_page->post_title ?></h2>
		<?php echo $referral_page->post_content;?>

	<?php } ?>
	
	<?php echo do_shortcode( '[REFERRAL_PROGRESS_BAR]' ); ?>
	
	<?php if( !empty($referred_users) ){ ?>
		<div class="referred_users">
			<h4><?php _e('Your accepted referrals', WhiteLabelTheme::THEME_SLUG); ?></h4>

		    <?php foreach ($referred_users as $user_id):
		    	$user = get_userdata($user_id); ?>

		    	<div class="card container referred_user">
		    		<div class="row vertical-align">
		    			<div class="col-4 col-md-5">
		    				<span class="user-name"><?php echo $user->user_login; ?></span>
		    				<div class="date-added">
		    					<i class="fa fa-check"></i> <span class="user-date_created"><?php echo date("d M, y", strtotime($user->user_registered)); ?></span>
		    				</div>
		    			</div>
		    			<div class="col-8 col-md-7">
							<span class="user-email"><?php echo $user->user_email; ?></span>
						</div>
		    		</div>
				</div>

		    <?php endforeach; ?>
		</div>
	<?php } ?>	
</div>