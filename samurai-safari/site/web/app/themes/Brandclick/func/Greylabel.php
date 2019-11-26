<?php 
namespace Greylabel;
use Greylabel\Register\RegisterShortcodes;
use Greylabel\Vendor\AcfSettings;
use Greylabel\Vendor\Wpcf7Settings;
use Greylabel\Settings\CleanUpWordPress;
use Greylabel\Settings\RestApiSettings;
use Greylabel\Admin\DebuggerSettings;
// use Greylabel\DataAPI\CreateStoreGeojson;
// use Greylabel\DataAPI\ProcessExternalData;


// use Greylabel\Tracking\WcCreateProductObjects;

// Woocommmerce 
// use Greylabel\Woocommerce\WcReset;

abstract class Greylabel {

    const VERSION = 0.01;
    const THEME_SLUG = 'greylabel';
    public static $settings = ['google_api_key' => NULL];

    protected $theme_support;
    protected $filters;
    protected $actions;

    protected $nav_menus;
    protected $sidebars;
    protected $shortcodes;

    public static $scripts;
    public static $styles;



    public function __construct()
    {   
        add_action('after_setup_theme', [$this, 'init']);
    }

    public function init()
    {   
        $debug_settings             = new DebuggerSettings();
        $shortcodes                 = new RegisterShortcodes();
        $acf_settings               = new AcfSettings();
        $wpcf7_settings             = new Wpcf7Settings();
        $rest_api_settings          = new RestApiSettings();
        $clean_up_wp                = new CleanUpWordPress();
        // $woocommerce_reset          = new WcReset();
        // $create_store_geojson       = new CreateStoreGeojson();
        // $wc_create_product_Objects  = new WcCreateProductObjects();
        // $process_external_data      = new ProcessExternalData();
        

        /**
        * @todo refactor old ^ register classes to new abstract class setup
        */
       
        $this->add_theme_support();
        $this->add_filters();
        $this->add_actions();

        $this->register_nav_menus();
        $this->register_sidebars();
    }

    /**
    * [add_theme_support description]
    */
    protected function add_theme_support()
    {
        foreach ($this->theme_support as $theme_support) {
            if (is_array($theme_support)) {
                add_theme_support($theme_support[0], $theme_support[1]);
            } else {
                add_theme_support($theme_support);
            }
        }
    }

    /**
    * [enqueue_scripts initiates all the styles in the $scripts array]
    * @return [none]
    */
    public static function enqueue_scripts()
    {
        foreach (static::$scripts as $handle => $script) {
            $load_script = true; 

            if ( isset($script['load_script']) ) {
                $load_script = false; 
                global $wp_query;

                foreach ($script['load_script'] as $is_page) {
                    if ($is_page()) {
                        $load_script = true;
                        break;
                    } 
                    
                    if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
                        $load_script = true;
                        break;
                    }
                }
            }

            if ($load_script) {
                $src = $script['src'];

                if ($script['external'] !== true) {
                    $src = get_stylesheet_directory_uri() . '/' . $script['src'];
                }

                if (! array_key_exists('in_footer', $script)) {
                    $script['in_footer'] = true;
                }

                wp_register_script( $handle, $src, $script['dependencies'], $script['version'], $script['in_footer'] );
                
                if (isset($script['localize'])) {
                    $script['localize']['options']['baseUrl'] = trailingslashit( get_bloginfo( 'url' ) ); 

                    // add variables to the object that's used in the js file
                    wp_localize_script( $handle, $script['localize']['name'], $script['localize']['options'] );
                }

                wp_enqueue_script( $handle, self::VERSION );
            }    
        }
    }

    /**
    * [enqueue_styles initiates all the styles in the $styles array]
    * @return [none]
    */
    public static function enqueue_styles()
    {
        foreach (static::$styles as $handle => $style) {
            $src = $style['src'];

            if ($style['external'] !== true) {
                $src = get_stylesheet_directory_uri() . '/' . $style['src'];
            }

            wp_enqueue_style($handle, $src, $style['dependencies'], $style['version']);
        }
    }

    /**
    * [add_filters initiates all the filters in the $filters array]
    * 
    */
    private function add_filters()
    {
        foreach ($this->filters as $name => $filter) {
            if (is_array($filter[0])) {
                foreach ($filter as $sub_filter) {
                    add_filter($name, [$sub_filter[0], $sub_filter[1]]);
                }
            } else {
                if (isset($filter[2]) && is_array($filter[2])) {

                    add_filter($name, $filter[2], $filter[0], $filter[1]);
                } else {
                    add_filter($name, $filter);
                }
            }
        }
    }

    /**
    * [add_actions initiates all the actions in the $actions array]
    */
    private function add_actions()
    {
        foreach ($this->actions as $name => $action) {
            if (is_array($action[0])) {
                foreach ($action as $class => $sub_action) {
                    add_action($name, [$sub_action[0], $sub_action[1]]);
                }
            } else {
                if (isset($action[2]) && is_array($action[2])) {

                    add_action($name, $action[2], $action[0], $action[1]);
                } else {
                    add_action($name, $action);
                }
            }
        }
    }

    /**
    * [register_nav_menus registers all the navigation menus in the $nav_menus array]
    */
    private function register_nav_menus()
    {
        foreach ($this->nav_menus as $location => $name) {
            register_nav_menu($location, $name);
        }
    }

    /**
    * [register_sidebars registers all the sidebars in the $sidebars array]
    */ 
    private function register_sidebars()
    {
        foreach ($this->sidebars as $id => $name) {
            register_sidebar([
                'name'          => $name,
                'id'            => $id,
                'before_widget' => '<div id="%1$s" class="widget widget-' . $id . ' %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
            ]);
        }
    }

}

