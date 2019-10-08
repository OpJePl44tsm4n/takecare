<?php 
namespace Greylabel\Register;
use Greylabel\Greylabel;

class RegisterOptionsPage {

    /**
    *   register new options page 
    */
    public static function action__register_menu_page() 
    {
        add_menu_page(
            __( 'Page Title', Greylabel::THEME_SLUG  ),
            __( 'Greylabel', Greylabel::THEME_SLUG  ),
            'manage_options',
            'Greylabel_options',
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'add_settings_page_content',
            )
        );
    }


    /**
    *   register new options page 
    */
    public static function action__register_settings() 
    {   
        
        // register a section where the settings will belong to

        add_settings_section(
            'integration_settings_section',        
            'Integration Options',         
            function () {},
            'Greylabel_options'                   
        );

        add_settings_section(
            'mail_tracker_settings_section',        
            'Mail Tracker Options',         
            function () {
                echo '<i>'.  sprintf( __( 'The %s shortcode can be overwritten with: %s ', Greylabel::THEME_SLUG  ),'<b>[MAILTRACKER]</b>', '<b>[CALLTRACKER mail=""]</b>') .'</i>' ;
            },
            'Greylabel_options'                   
        );

        add_settings_section(
            'call_tracker_settings_section',        
            'Call Tracker Options',         
            function () {
                echo '<i>'. sprintf( __( 'The %s shortcode can be overwritten with: %s ', Greylabel::THEME_SLUG  ), '<b>[CALLTRACKER]</b>', '<b>[CALLTRACKER id="" tel="" tel-formatted=""]</b>' ) .'</i>' ;
            },
            'Greylabel_options'                   
        );

        add_settings_section(
            'social_settings_section',        
            'Social Options',         
            function () {},
            'Greylabel_options'                   
        );

        add_settings_section(
            'debugger_settings_section',        
            'Debug Settings',         
            function () {
                echo '<i>'. _e( 'Make sure you only use these options with care!', Greylabel::THEME_SLUG  ) .'</i>' ;
            },
            'Greylabel_options'                   
        );

        // add the setting fields
        add_settings_field( 
            'contact_mail',
            __( 'Contact Email:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'mail_tracker_settings_section',
            array(
                'id'            => 'contact_mail',
                'placeholder'   => 'info@Greylabel.com'
            )
        );

        add_settings_field( 
            'call_tracker_tel',                      
            __( 'Call tracker Tel:', Greylabel::THEME_SLUG  ),                           
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ),    
            'Greylabel_options', 
            'call_tracker_settings_section',
            array( 
                'id'            => 'call_tracker_tel',
                'placeholder'   => '+31034567890'
            )
        );

        add_settings_field( 
            'call_tracker_tel_formatted',                      
            __( 'Call tracker Tel Formatted:', Greylabel::THEME_SLUG  ),                           
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ),    
            'Greylabel_options', 
            'call_tracker_settings_section',
            array( 
                'id'            => 'call_tracker_tel_formatted',
                'placeholder'   => '+31 (0)34 56 78 90'
            )
        );

        add_settings_field( 
            'google_tag_manager_id',
            __( 'Google Tagmanager ID:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'integration_settings_section',
            array(
                'id'            => 'google_tag_manager_id',
                'placeholder'   => 'GTM-ABCDEFG3'
            )
        );

        add_settings_field( 
            'google_analytics_id',
            __( 'Google Analytics ID:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'integration_settings_section',
            array(
                'id'            => 'google_analytics_id',
                'placeholder'   => 'UA-123456789-1'
            )
        );

        add_settings_field( 
            'google_api_key',
            __( 'Google Api Key:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'integration_settings_section',
            array(
                'id'            => 'google_api_key',
                'placeholder'   => 'AIzaDFHsdfeBMEgePkhTOM-3-oO5dTapXBrlx8'
            )
        );

        add_settings_field( 
            'facebook_pixel_id',
            __( 'Facebook Pixel ID:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'integration_settings_section',
            array(
                'id'            => 'facebook_pixel_id',
                'placeholder'   => '12345678901234'
            )
        );

        add_settings_field( 
            'kiyoh_url',
            __( 'Kiyoh review feed Url:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'integration_settings_section',
            array(
                'id'            => 'kiyoh_url',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'mailchimp_url',
            __( 'Mailchimp form Url:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'integration_settings_section',
            array(
                'id'            => 'mailchimp_url',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'facebook_url',
            __( 'Facebook Url:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'social_settings_section',
            array(
                'id'            => 'facebook_url',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'instagram_url',
            __( 'Instagram Url:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'social_settings_section',
            array(
                'id'            => 'instagram_url',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'linkedin_url',
            __( 'Linkedin Url:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'Greylabel_options',
            'social_settings_section',
            array(
                'id'            => 'linkedin_url',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'mailtrap_toggle',
            __( 'Mailtrap:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_checkbox_input'
            ), 
            'Greylabel_options',
            'debugger_settings_section',
            array(
                'id'      => 'mailtrap_toggle',
                'label'   => 'All send mail will be caught in <a href="https://mailtrap.io">Mailtrap</a>'
            )
        );

        add_settings_field( 
            'site_redirection_toggle',
            __( 'Redirection:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_checkbox_input'
            ), 
            'Greylabel_options',
            'debugger_settings_section',
            array(
                'id'      => 'site_redirection_toggle',
                'label'   => 'Only allow whitelisted IP\'s' 
            )
        );

        add_settings_field( 
            'site_redirect_url',
            __( 'Redirect Url:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_url_input'
            ), 
            'Greylabel_options',
            'debugger_settings_section',
            array(
                'id'      => 'site_redirect_url',
                'placeholder'   => 'https://live-domain.com'
            )
        );

        add_settings_field( 
            'site_redirect_allowed_ips',
            __( 'Allowed IP\'s:', Greylabel::THEME_SLUG  ),
            array(
                '\Greylabel\Register\RegisterOptionsPage',
                'callback__add_text_area'
            ), 
            'Greylabel_options',
            'debugger_settings_section',
            array(
                'id'      => 'site_redirect_allowed_ips',
                'placeholder'   => 'https://live-domain.com'
            )
        );

        // register the settings 
        register_setting( 'Greylabel_options', 'contact_mail' );
        register_setting( 'Greylabel_options', 'call_tracker_tel' );
        register_setting( 'Greylabel_options', 'call_tracker_tel_formatted' );

        // Google settings
        register_setting( 'Greylabel_options', 'google_api_key' );
        register_setting( 'Greylabel_options', 'google_analytics_id' );  
        register_setting( 'Greylabel_options', 'google_tag_manager_id' ); 
        register_setting( 'Greylabel_options', 'facebook_pixel_id' );
        register_setting( 'Greylabel_options', 'kiyoh_url' ); 
        register_setting( 'Greylabel_options', 'mailchimp_url' );  

        // social settings
        register_setting( 'Greylabel_options', 'facebook_url' );  
        register_setting( 'Greylabel_options', 'instagram_url' );  
        register_setting( 'Greylabel_options', 'linkedin_url' );  

        // debugger settings
        register_setting( 'Greylabel_options', 'mailtrap_toggle' );
        register_setting( 'Greylabel_options', 'site_redirection_toggle' );      
        register_setting( 'Greylabel_options', 'site_redirect_url' );
        register_setting( 'Greylabel_options', 'site_redirect_allowed_ips' );
    }


    /**
    *   Add input text input field 
    */
    public static function callback__add_text_input( $args )
    {   
        $html = '<input type="text" id="'. $args['id'] .'" name="'. $args['id'] .'" placeholder="'. $args['placeholder'] .'" value="'. get_option($args['id']) .'" />';

        echo $html;
    }

    /**
    *   Add input text Area field 
    */
    public static function callback__add_text_area( $args )
    {   
        $ips = get_option($args['id']) ? get_option($args['id']) : $_SERVER['REMOTE_ADDR'] . ','; 
        $html = '<p><i>Comma seperated list of ips.</i> <b>(Your ip: '.$_SERVER['REMOTE_ADDR'].')</b></p><textarea rows="4" cols="50" id="'. $args['id'] .'" name="'. $args['id'] .'">'. $ips .'</textarea>';

        echo $html;
    }

    /**
    *   Add input url input field 
    */
    public static function callback__add_url_input( $args )
    {   
        $html = '<input type="url" id="'. $args['id'] .'" name="'. $args['id'] .'" placeholder="'. $args['placeholder'] .'" value="'. get_option($args['id']) .'" />';

        echo $html;
    }

    /**
    *   Add input checkbox input field 
    */
    public static function  callback__add_checkbox_input( $args )
    {   
        $html = '<input type="checkbox" id="'. $args['id'] .'" name="'. $args['id'] .'" value="1" ' . checked(1, get_option($args['id']), false) . '/>'; 
        $html .= '<label for="'. $args['id'] .'"> '  . $args['label'] . '</label>'; 
         
        echo $html;
    }

    /**
    *   Add content to the settings page 
    */
    public static function  add_settings_page_content()
    {
        ?><div class="wrap">
            <h2><?php _e( 'Greylabel Options Page', Greylabel::THEME_SLUG  ); ?></h2>
            <p><?php echo sprintf( __( 'Custom settings for the <b>%s</b> theme.', Greylabel::THEME_SLUG  ), Greylabel::THEME_SLUG)?></p>
            <hr>
            <form method="post" action="options.php"> 
                <?php 
                    settings_fields( 'Greylabel_options' );
                    do_settings_sections( 'Greylabel_options' ); 
                ?>
                
                <hr>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

}

