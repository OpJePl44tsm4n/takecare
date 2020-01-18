<form id="logout" action="logout" method="post">
    <p class="status"></p>
    <input id="logout" class="btn btn-primary" type="submit" value="<?php _e('Log out', TakeCareIo::THEME_SLUG ); ?>" name="submit" >
    <?php  wp_nonce_field( 'ajax_logout_action', 'security_logout_nonce' ); ?>
    <input type="hidden" name="action" value="ajaxlogout">
    <input type="hidden" name="form_id" value="logout">
</form>