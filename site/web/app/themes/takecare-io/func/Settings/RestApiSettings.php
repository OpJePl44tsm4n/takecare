<?php 
namespace Greylabel\Settings;
use Greylabel\Greylabel;

class RestApiSettings {

	public function __construct()
  	{	
        // Actions
        add_action( 'rest_api_init',                                            array( $this, 'action__add_rest_fields') );
        add_action( 'init',                                                     array( $this, 'action__add_scripts') );
        add_action( 'wp_ajax_search_autocomplete',                              array( $this, 'action__get_search_autocomplete') );
        add_action( 'wp_ajax_nopriv_search_autocomplete',                       array( $this, 'action__get_search_autocomplete') );

        // Filters 
        add_filter( 'rest_company_collection_params',                           array( $this, 'filter__set_rest_order_to_menu_order'), 10, 1 );
    }    

    public function action__add_scripts() {
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_register_style( 'jquery-ui-styles','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui-styles' );
    }


    /**
    *   Get the autocomplete values 
    */
    public function action__get_search_autocomplete() 
    {   
        $term = strtolower( $_GET['term'] );
        $post_type = strtolower( $_GET['post_type'] );
        $suggestions = array();
        
        $args = [
            's' => $term,
            'post_type' => $post_type
        ];
        $loop = new \WP_Query($args);
        
        while( $loop->have_posts() ) {
            $loop->the_post();
            $suggestion = array();
            $logo_id = get_field('company_logo');
            $logo = wp_get_attachment_image( $logo_id, 'thumb' );
            $suggestion['logo'] = $logo;
            $suggestion['label'] = get_the_title();
            $suggestion['link'] = get_permalink();
            
            $suggestions[] = $suggestion;
        }
        
        wp_reset_query();
        
        $response = json_encode( $suggestions );
        echo $response;
        exit();

    }

    /**
    *   Add extra fields to the WP rest api
    */
    public function action__add_rest_fields()
    {
        register_rest_field( 'post', 'post_grid_data', array(
               'get_callback'    => array( $this, 'callback__post_grid_data'),
            )
        );


        register_rest_field( 'company', 'post_grid_data', array(
               'get_callback'    => array( $this, 'callback__company_grid_data'),
            )
        ); 

    }

    public function callback__post_grid_data() 
    {   
        global $post; 
        $post_id = $post->ID;
        $next_post = isset($_GET['categories']) ? get_adjacent_post(true) : get_adjacent_post();

        $post_grid_data = [
            'date_rendered' => get_the_date('j M \'Y'),
            'thumbnail_rendered' => get_the_post_thumbnail($post_id, 'medium'), 
            'next_post' => $next_post ? true : false,
        ];

        $types = get_the_terms( $post_id, 'category' );
        if($types) {
            foreach ( $types as $term ) {
                $post_grid_data['category_rendered'] = '<a href="'.get_term_link($term->term_id).'">'. $term->name .'</a>';
                break;
            }
        }  

        return $post_grid_data;
    }


    public function callback__company_grid_data() 
    {   
        global $post; 
        $post_id = $post->ID;
        $next_post = isset($_GET['categories']) ? get_adjacent_post(true) : get_adjacent_post();

        $post_grid_data = [
            'thumbnail_rendered' => get_the_post_thumbnail($post_id, 'medium'), 
            'exerpt_rendered' => sprintf("<p class=\"intro\">%s</p>", get_the_excerpt()),
            'next_post' => $next_post ? true : false,
        ];

        $founded = get_field('founded', $post_id);
        $cities = get_the_terms( $post_id, 'city' );
        $city = isset($cities[0]) ? $cities[0] : false;

        if($city) {
            $post_grid_data['city_rendered'] = sprintf('<a class="city" target="_blank" href="%s"><i class="fa fa-map-marker"></i> %s</a>', 
                get_term_link($city->term_id),
                $city->name
            );
        }   

        if( $founded ) {
            $post_grid_data['year_rendered'] = sprintf('<span class="year-founded"><i class="fa fa-calendar"></i> %s %s</span>', 
                __('Founded in', Greylabel::THEME_SLUG ),
                $founded
            );
        }

        if($main_tag = get_field( 'main_tag', $post_id )){
            $post_grid_data['main_tag_rendered'] = '<a class="tag" href="'.get_term_link($main_tag->term_id).'">'. $main_tag->name .'</a>';
        }

        $tags = get_the_tags();
        if($tags) {
            $count = count($tags) - 1;
            $tag_list = ''; 

            foreach ( $tags as $tag ) {
                $tag_list .= '<a class="tag" href="'.get_term_link($tag->term_id).'">'. $tag->name .'</a>';
            }

            if($tag_list !== ''){ 
                            
                $post_grid_data['tag_toggle_rendered'] = "<button class=\"tag collapsed\" 
                    data-toggle=\"collapse\" 
                    data-target=\"#post-{$post_id}-tags\" 
                    aria-expanded=\"false\" 
                    aria-controls=\"post-{$post_id}-tags\">
                    + {$count}
                </button>
                <div id=\"post-{$post_id}-tags\" class=\"collapse\">
                    <button class=\"close collapsed\" 
                        data-toggle=\"collapse\" 
                        data-target=\"#post-{$post_id}-tags\" 
                        aria-expanded=\"false\" 
                        aria-controls=\"post-{$post_id}-tags\">
                        X
                    </button>
                    {$tag_list}
                </div>";
                
            }
        } 

        $types = get_the_terms( $post_id, 'category' );
        if($types) {
            foreach ( $types as $term ) {
                $post_grid_data['category_rendered'] = '<a class="main-category" href="'.get_term_link($term->term_id).'">'. $term->name .'</a>';
                break;
            }
        }  

        return $post_grid_data;
    }


    public function filter__set_rest_order_to_menu_order( $params )
    {
        $params['orderby']['enum'][] = 'menu_order';

        return $params;
    }

}

