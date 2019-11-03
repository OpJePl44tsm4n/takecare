<?php

$loader = require __DIR__ . '/vendor/autoload.php';
use Brandclick\Brandclick;

class WhiteLabelTheme extends Brandclick {
	const VERSION = 0.21;
    const THEME_SLUG = 'gkazas';

    protected static $instance = null;

    public static function get_instance() 
    {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
    * [$theme_support Array with suported theme functionalities (auto initiated)]
    * @var array
    */
    protected $theme_support = [
        ['post-thumbnails', ['post','page'] ],
        'html5',
        'custom-logo',
        'woocommerce'
    ];

    /**
    * [$actions Array with actions (auto initiated)]
    * @var array
    */
    protected $actions = [
        'wp_enqueue_scripts' => [
            [__CLASS__, 'enqueue_scripts'],
            [__CLASS__, 'enqueue_styles'],
        ],
        'init' => [
            ['\Brandclick\Register\RegisterPostTypes', 'action__register_post_types'],
            ['\Brandclick\Register\RegisterPostTypes', 'action__register_taxonomies'],
        ],
        'admin_menu' => [
            ['\Brandclick\Register\RegisterOptionsPage', 'action__register_menu_page']
        ],
        'admin_init' => [
            ['\Brandclick\Register\RegisterOptionsPage', 'action__register_settings']
        ],
        'phpmailer_init' => ['\Brandclick\Admin\DebuggerSettings', 'action__set_mailtrap'],
    ];

    /**
    * [$actions Array with filters (auto initiated)]
    * @var array
    */
    protected $filters = [
        'user_contactmethods' => ['\Brandclick\Admin\CleanUpUserProfiles', 'filter__unset_user_fields'],
        'option_show_avatars' => '__return_false',
    ];

    /**
    * [$actions Array with scripts (initiated via $actions)]
    * @var array
    */
    public static $scripts = [
        'main' => [
            'src' => 'assets/dist/js/main.min.js',
            'dependencies' => ['jquery'],
            'version' => self::VERSION,
            'external' => false,
        ],
        'api' => [
            'src' => 'assets/dist/js/wp-api.min.js',
            'dependencies' => [],
            'version' => self::VERSION,
            'external' => false,
            'load_script' => ['is_archive', 'is_search', 'is_home'],
            'localize' => [
                'name' => 'wp_api_object',
                'options' => [
                    'jsonURI' => 'wp-json/wp/v2/'
                ]    
            ]
        ]
    ];

    /**
    * [$actions Array with styles (initiated via $actions)]
    * @var array
    */
    public static $styles = [
        'main' => [
            'src' => 'assets/dist/styles/main.min.css',
            'dependencies' => [],
            'version' => self::VERSION,
            'external' => false,
        ]
    ];

    /**
    * [$nav_menus Array with nav menus (auto initiated)]
    * @var array
    */
    protected $nav_menus = [
        'header-menu' => 'Header menu',
        'footer-menu' => 'Footer menu',
    ];

    /**
    * [$sidebars Array with sidebars (auto initiated)]
    * @var array
    */
    protected $sidebars = [
        'default' => 'Standaard sidebar',
        'search' => 'Zoekresultaten sidebar',
        'footer-widget' => 'Footer Widget'
    ];


    /**
    * [calculate the bootstrap grid classes]
    * @return Array of grid classes
    */
    public static function calculate_grid_columns($grid_columns, $screen_size)
    {   
        $columns = explode(" + ", $grid_columns);
        $grid_array = [];

        foreach ($columns as $column) {
            $column = explode('/',$column);
            $grid_array[] = 'col-'. $screen_size .'-' . (12 * $column[0] / $column[1]); 
        } 

        return $grid_array;
    }

}

$theme = WhiteLabelTheme::get_instance();