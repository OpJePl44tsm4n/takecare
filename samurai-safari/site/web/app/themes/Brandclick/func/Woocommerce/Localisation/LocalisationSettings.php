<?php 
namespace Brandclick\Woocommerce\Localisation;
use Brandclick\Brandclick;

class LocalisationSettings {

	public function __construct()
  	{
  		add_filter( 'woocommerce_countries', 						array( $this, 'filter__correct_country_names') );

  		// Actipons 
  		add_action( 'wp_ajax_lang_redirect_popup', 					array( $this, 'action__ajax_lang_redirect_popup') );
		add_action( 'wp_ajax_nopriv_lang_redirect_popup', 			array( $this, 'action__ajax_lang_redirect_popup') );
  	}

  	/**
  	 * [filter__correct_country_names description]
  	 * @param  [type] $countries [description]
  	 * @return [type]            [description]
  	 */
  	public function filter__correct_country_names( $countries )
  	{
		$countries['NL'] = __('The Netherlands', Brandclick::THEME_SLUG );
		return $countries;
  	}

  	/**
  	 * [action__ajax_lang_redirect_popup description]
  	 * @return [type] [description]
  	 */
  	public function action__ajax_lang_redirect_popup()
  	{	
  		$location = \WC_Geolocation::geolocate_ip();
        $customer_coutry = (isset($location['country']) && $location['country']) ? strtolower($location['country']) : '';

  		if( $customer_coutry && ICL_LANGUAGE_CODE == 'en' && $customer_coutry != 'en' ){
  			$url = $_GET['currentUrl'];
  			$local_permalink = apply_filters( 'wpml_permalink', $url , $customer_coutry );
            $html = ''; 

            if( have_rows('language_redirect_popup', 'option') ):
                while ( have_rows('language_redirect_popup', 'option') ) : the_row();
                    if( strtolower(get_sub_field('user_country')) == $customer_coutry ){
                        $link_open = '<a href="'. $local_permalink .'">';
                        $popup_text = str_replace(
                            array('[link]', '[/link]'), 
                            array($link_open, '</a>'), 
                            get_sub_field('popup_text')
                        );

                        $html = sprintf('<div class="popup">%s</div>', 
                            $popup_text
                        );

                        break; 
                    }
                endwhile;
            endif; 

	  		echo $html; 
  		}
 	
  		die(); 
  	}

}  	