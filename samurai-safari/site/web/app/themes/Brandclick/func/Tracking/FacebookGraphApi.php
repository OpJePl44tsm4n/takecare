<?php 
/**
 * Facebook Graph serverside conversion
 * Docs:   -    https://developers.facebook.com/docs/marketing-api/offline-conversions/v2.12
 *         -    https://github.com/facebook/php-graph-sdk
 *         -    https://business.facebook.com/
 */

namespace Brandclick\Tracking;
use Brandclick\Brandclick;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdsInsights;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;

class FacebookGraphApi {

  	public function __construct()
  	{
    	// Actions
        add_action( 'admin_init',                      			 array( $this, 'action__register_settings') );
  	}


  	/**
	* 
	*/
	public function action__register_settings()
	{	

		add_settings_section(
            'facebook_section',        
            'Facebook Options',         
            function () {

                // $access_token = get_option('fb_default_access_token');
                // $app_secret = get_option('fb_app_secret');
                // $app_id = get_option('fb_app_id');
                // $ad_account_id = get_option('fb_ad_account_id');

                $access_token = 'EAAeimDRrNyABAF1sGJuVYI2Yu3htLIkGMRtoFdnjA9GFaT3bycM5O96mr9JMjYk1NcNoNJ1AzbyZBConjhtPpWGVyqy0gJicYBWPhGrVvtydsjI4wU3quUCzOiKnMO2DM1uF5Bp2UeMGhpZC7VtWUwjKawp6TaZA1fZCkLpz8UZA6ePYBNCqsBe8ODFDuLKQZD';
                $ad_account_id = 'act_2251464988434716';
                $app_secret = 'b57ecc12daab07b31fe60f01a03017fb';
                $app_id = '2149099435144992';

                $api = Api::init($app_id, $app_secret, $access_token);
                $api->setLogger(new CurlLogger());

                $fields = array(
                  'actions:offsite_conversion.fb_pixel_purchase',
                );
                $params = array(
                  'level' => 'account',
                  'filtering' => array(),
                  'breakdowns' => array('days_1'),
                  'time_range' => array('since' => '2019-02-27','until' => '2019-03-29'),
                );
                echo json_encode((new AdAccount($ad_account_id))->getInsights(
                  $fields,
                  $params
                )->getResponse()->getContent(), JSON_PRETTY_PRINT);
 
            },
            'brandclick_options'                   
        );

        add_settings_field( 
            'fb_app_id',
            __( 'Facebook App ID:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'facebook_section',
            array(
                'id'            => 'fb_app_id',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'fb_app_secret',
            __( 'Facebook App Secret:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'facebook_section',
            array(
                'id'            => 'fb_app_secret',
                'placeholder'   => ''
            )
        );

        add_settings_field( 
            'fb_default_access_token',
            __( 'Facebook Access Token:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'facebook_section',
            array(
                'id'            => 'fb_default_access_token',
                'placeholder'   => ''
            )
        );

		add_settings_field( 
            'fb_ad_account_id',
            __( 'Facebook Ad Account ID:', Brandclick::THEME_SLUG  ),
            array(
                '\Brandclick\Register\RegisterOptionsPage',
                'callback__add_text_input'
            ), 
            'brandclick_options',
            'facebook_section',
            array(
                'id'            => 'fb_ad_account_id',
                'placeholder'   => ''
            )
        );
     
        // Facebook settings
        register_setting( 'brandclick_options', 'fb_app_id' ); 
        register_setting( 'brandclick_options', 'fb_app_secret' );   
        register_setting( 'brandclick_options', 'fb_default_access_token' ); 
        register_setting( 'brandclick_options', 'fb_ad_account_id' );
	}



	
}
