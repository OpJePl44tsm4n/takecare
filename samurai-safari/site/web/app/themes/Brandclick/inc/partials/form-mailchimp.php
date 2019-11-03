<?php 
    $form_url = get_option( 'mailchimp_url' );
    $form_url = $form_url ? htmlspecialchars_decode($form_url) : get_sub_field('mailchimp_form_url');

    if ($form_url):

        $title = get_sub_field('title');
        $parts = parse_url($form_url);
        parse_str($parts['query'], $mailchimp); 
        $home_url = apply_filters( 'wpml_home_url', get_option( 'home' ) ); 
        $privary_url = $home_url ? trailingslashit( $home_url ) . 'privacy-policy' : '/privacy-policy'; 
        ?>
        
        <h2><?php echo $title; ?></h2>

        <div id="mc_embed_signup">
            <form action="<?php echo $form_url;?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div class="newsletter" id="mc_embed_signup_scroll">
                    <div class="mc-field-group">
                        <input type="text" value="" placeholder="<?php _e( 'Name', WhiteLabelTheme::THEME_SLUG ); ?>" name="FNAME" class="required" id="mce-FNAME">
                    </div>
                    <div class="mc-field-group">
                        <input type="email" value="" placeholder="<?php _e( 'E-mail', WhiteLabelTheme::THEME_SLUG ); ?>" name="EMAIL" class="required email" id="mce-EMAIL">
                    </div>
                    <button type="submit" name="subscribe" id="mc-embedded-subscribe" class="btn btn-primary"><?php _e( 'Subscribe', WhiteLabelTheme::THEME_SLUG ); ?></button>

                    <div id="mergeRow-gdpr" style="width:100%;" class="mergeRow gdpr-mergeRow content__gdprBlock mc-field-group">
                        <div class="content__gdpr">
                            <fieldset style="width:auto; margin-top: 1.2em;" class="mc_fieldset gdprRequired mc-field-group" name="interestgroup_field">
                                <label style="margin-bottom:0;" class="checkbox subfield" for="gdpr_44141">
                                    <input type="checkbox" id="gdpr_44141" name="gdpr[44141]" value="Y" class="av-checkbox gdpr">
                                    <span style="font-size:0.8rem;">  
                                        <?php echo sprintf( __('I have read and agree to the website %s.', WhiteLabelTheme::THEME_SLUG), 
                                                sprintf( '<a href="%s"> %s </a>',
                                                    $privary_url,
                                                    __('privacy and cookie policy', WhiteLabelTheme::THEME_SLUG)      
                                                )
                                            ); ?>
                                    </span>
                                </label>
                            </fieldset>    
                            <p style="color: initial;"><?php _e( 'you\'ll receive our newsletter once per month containing interesting articles and offers.', WhiteLabelTheme::THEME_SLUG); ?></p> 
                        </div>
                    </div>

                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div> 

                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_<?php echo $mailchimp['u']; ?>_<?php echo $mailchimp['id']; ?>" tabindex="-1" value=""></div>
                </div>
            </form>
        </div>
        <script type='text/javascript'>jQuery(document).ready(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text'; var $mcj = jQuery.noConflict(true); });</script>

    <?php 
    wp_enqueue_script( 'mc-validate', 'https://s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js', array( 'jquery' ), '', true ); 
    
    endif ?> 





    
   

