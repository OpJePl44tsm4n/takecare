<?php 
    $form_url = get_option( 'mailchimp_url' );
    $form_url = $form_url ? htmlspecialchars_decode($form_url) : get_sub_field('mailchimp_form_url');

    if ($form_url):

        $title = get_sub_field('title');
        if(!$title){
            $title = __('Signup for our newsletter', TakeCareIo::THEME_SLUG );
        }

        $parts = parse_url($form_url);
        parse_str($parts['query'], $mailchimp); ?>
        <div class="mc-signup">

            <h2><?php echo $title; ?></h2>

            <div id="mc_embed_signup">
                <form action="<?php echo $form_url;?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div class="newsletter" id="mc_embed_signup_scroll">
                        <div class="mc-field-group">
                            <input type="text" value="" placeholder="<?php _e( 'First Name', TakeCareIo::THEME_SLUG ); ?>" name="FNAME" class="required" id="mce-FNAME">
                        </div>
                        <div class="mc-field-group">
                            <input type="email" value="" placeholder="<?php _e( 'Email Address', TakeCareIo::THEME_SLUG ); ?>" name="EMAIL" class="required email" id="mce-EMAIL">
                        </div>
                        <button type="submit" name="subscribe" id="mc-embedded-subscribe" class="btn btn-primary"><?php _e( 'Subscribe', TakeCareIo::THEME_SLUG ); ?></button>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div> 
                        <div style="position: absolute; left: -5000px;" aria-hidden="true">
                            <input type="text" value="website" name="SIGNUPTYPE" id="mce-SIGNUPTYPE">
                            <input type="text" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="LOCATION" id="mce-LOCATION">
                            <input type="text" name="b_<?php echo $mailchimp['u']; ?>_<?php echo $mailchimp['id']; ?>" tabindex="-1" value="">
                        </div>
                    </div>
                </form>
            </div>
        </div>

   <!--  <script type='text/javascript'>jQuery(document).ready(function($) {window.fnames = new Array(); window.ftypes = new Array(); 
        fnames[0]='EMAIL';ftypes[0]='email';
        fnames[1]='SIGNUPTYPE';ftypes[1]='text';
        fnames[2]='LOCATION';ftypes[2]='text';
        fnames[3]='FNAME';ftypes[3]='text';
        var $mcj = jQuery.noConflict(true); });</script> -->

    <?php 
    wp_enqueue_script( 'mc-validate', 'https://s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js', array( 'jquery' ), '', true ); 
    
    endif ?> 