<?php 
namespace Greylabel\Vendor;
use Greylabel\Greylabel;

class AcfSettings {

	public function __construct()
  	{	
		add_filter( 'acf/init',                            array( $this, 'filter__add_acf_settings') );
        add_action( 'acf/init',                            array( $this, 'action__add_wpcf7_form_settings') );
        add_action( 'acf/fields/google_map/api',           array( $this, 'action__add_acf_google_map_api') );
	}

	/**
    *   add google maps api key to ACF 
    */
    public function filter__add_acf_settings() 
    {   
        $settings = Greylabel::$settings; 
        // var_dump($settings['google_api_key']);
        $google_api_key = isset($settings['google_api_key']) ? $settings['google_api_key'] : false;
        
        // only enqueue Google Maps when we have a key 
        if ($google_api_key) {
            acf_update_setting('enqueue_google_maps', true);
            acf_update_setting('google_api_key', $google_api_key);
        }    

        // As a programmer we woud like indexes to start with 0 
        acf_update_setting('row_index_offset', 0);

        // remove wp core metabox for performance 
        acf_update_setting('remove_wp_meta_box', true);

        // Don’t show ACF updates in the WP admin.   
        acf_update_setting('show_updates', false);

        // remove enqued files we don't need
        acf_update_setting('enqueue_datepicker', false);
        acf_update_setting('enqueue_datetimepicker', false);
        // acf_update_setting('enqueue_select2', false); // this breaks the clone field! 
    }

    /**
    *   add options page to wpcf7 menu
    */
    public function action__add_wpcf7_form_settings()
    {
        acf_add_options_page(array(
            'page_title'    => 'In article content',
            'menu_title'    => 'In Article Content',
            'menu_slug'     => 'in-article-content',
            'parent_slug'   => 'edit.php',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }

    /**
    *   add maps api
    */
    public function action__add_acf_google_map_api( $api )
    {
        $api['key'] = 'AIzaSyDO6SFdBMEgePkhTOM-7-oO5dTapXBrlx8'; 
        return $api;
    
    }   

}





