<?php 
    $form_url = get_option( 'mailchimp_url' );
    if ($form_url):

        $parts = parse_url(htmlspecialchars_decode($form_url));
        parse_str($parts['query'], $mailchimp); ?>


        <div id="mc_embed_signup">
            <form action="<?php echo $form_url;?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div class="newsletter" id="mc_embed_signup_scroll">
                    <div class="mc-field-group">
                        <input type="email" value="" placeholder="<?php _e( 'Email Address', TakeCareIo::THEME_SLUG ); ?>" name="EMAIL" class="required email" id="mce-EMAIL">
                        <button type="submit" name="subscribe" id="mc-embedded-subscribe" class="btn btn-secondary"><?php _e( 'Subscribe', TakeCareIo::THEME_SLUG ); ?></button>
                    </div>
                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div> 
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_<?php echo $mailchimp['u']; ?>_<?php echo $mailchimp['id']; ?>" tabindex="-1" value=""></div>
                </div>
            </form>
        </div>
        <script type='text/javascript' src='https://s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
    
    <?php endif; ?>