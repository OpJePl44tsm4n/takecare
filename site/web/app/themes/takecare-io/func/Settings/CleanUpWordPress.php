<?php 
namespace Greylabel\Settings;
use Greylabel\Greylabel;

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
        add_filter( 'the_generator',                            array( $this, 'filter__remove_wp_version') );
        add_filter( 'jpeg_quality',                             function($arg){return 100;});
        add_filter( 'upload_mimes',                             array( $this, 'filter__allow_mime_types') );
        add_filter( 'get_the_archive_title',                    array( $this, 'filter__simple_archive_title') );

        // Actions
        add_action('wp_dashboard_setup',                        array( $this, 'action__remove_dashboard_widgets') );
        add_action('wp_enqueue_scripts',                        array( $this, 'action__deregister_scripts') );
    }    

	/**
    *   Remove version info from head and feeds
    */
    public function filter__remove_wp_version()
    {
        return '';
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
        if( !is_admin() ){
            wp_deregister_script('heartbeat');
            wp_dequeue_style( 'wp-block-library' );
        }
    }

    /**
    *   Remove wp heartbeat function from frontend
    */
    public function filter__allow_mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function filter__simple_archive_title( $title )
    {
        if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        } elseif ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        } elseif ( is_tax() ) {
            $title = single_term_title( '', false );
        }
      
        return $title;
    }
}

