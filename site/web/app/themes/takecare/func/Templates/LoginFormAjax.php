<div class="login-ajax">
    <?php echo sprintf( 
        '<span class="login collapsed" 
        data-toggle="collapse" 
        data-target="#login-field" 
        aria-expanded="false" 
        aria-controls="coupon-field">%s</span> %s', 
        __('login', TakeCareIo::THEME_SLUG ),
        __('to order faster.', TakeCareIo::THEME_SLUG )
    ); ?>
    
    <div id="login-field" class="collapse">
        <form id="login" action="login" method="post">
		    <p class="status"><?php _e('Checking credentials, please wait...', TakeCareIo::THEME_SLUG); ?></p>
		    <input id="username" placeholder="Username" type="text" name="username">
		    <input id="password" placeholder="Password" type="password" name="password">
		    <a class="lost" href="<?php echo wp_lostpassword_url(); ?>"><?php _e('Lost your password?', TakeCareIo::THEME_SLUG); ?></a>
		    <input class="submit_button btn btn-primary" type="submit" value="Login" name="submit">
		    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
		</form>
    </div>    
</div>
