
    <form id="login" action="login" method="post">
        <h1>Site Login</h1>
        <p class="status"></p>
     
        <input id="username" type="text" required placeholder="<?php _e( 'Username', TakeCareIo::THEME_SLUG ); ?>" name="username">
        <input id="password" type="password" required placeholder="<?php _e( 'Password', TakeCareIo::THEME_SLUG ); ?>" name="password">
        <a class="lost" href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Lost your password?', TakeCareIo::THEME_SLUG ); ?></a>
        <input class="btn btn-primary" type="submit" value="Login" name="submit">
        <?php wp_nonce_field( 'ajax_login_action', 'security_login_nonce' ); ?>
        <input type="hidden" name="action" value="ajaxlogin">
        <input type="hidden" name="form_id" value="login">
    </form>