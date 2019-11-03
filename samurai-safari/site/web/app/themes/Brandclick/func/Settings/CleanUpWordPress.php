<?php 
namespace Brandclick\Settings;
use Brandclick\Brandclick;

class CleanUpWordPress {

	public function __construct()
  	{	
		// Remove actions
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'widget_text_content', 'wpautop');


        // Filters
        add_filter('the_generator',                 array( $this, 'filter__remove_wp_version') );
        add_filter('jpeg_quality',                  function($arg){return 100;});
        add_filter('upload_mimes',                    array( $this, 'filter__allow_mime_types') );

        // Actions
        add_action('wp_dashboard_setup',    array( $this, 'action__remove_dashboard_widgets') );
        add_action('wp_enqueue_scripts',    array( $this, 'action__deregister_scripts') );
        // add_action('template_redirect'      array( $this, 'action__redirect_author_page' ));

         //set X-Frame-Options SAMEORIGIN to prefent clickjacking
        add_action( 'send_headers', 'send_frame_options_header', 10, 0 );
    }    

	/**
    *   Remove version info from head and feeds
    */
    public function filter__remove_wp_version()
    {
        return '';
    }

    /**
    *   Remove wp heartbeat function from frontend
    */
    public function filter__allow_mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
    *   Remove and add dashboard items 
    */
    public function action__remove_dashboard_widgets()
    {
        global $wp_meta_boxes;

        //Right Now - Comments, Posts, Pages at a glance
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        //Wordpress Development Blog Feed
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        //Quick Press Form
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

    }

    /**
    *   Remove wp heartbeat function from frontend
    */
    public function action__deregister_scripts()
    {
        wp_dequeue_style( 'wp-block-library' );
    }

    // /**
    //  * [action__redirect_author_page description]
    //  * @return [type] [description]
    //  */
    // public function action__redirect_author_page()
    // {   
    //     global $wp_query;
        
    //     // redirect the author page to the homepage
    //     if ( is_author() ) {
    //         wp_safe_redirect( get_home_url(), 301 );
    //         exit;
    //     }
    // } 
   

}

