<?php 
namespace Brandclick\Settings;
use Brandclick\Brandclick;

class RestApiSettings {

	public function __construct()
  	{	
        // Actions
        add_action('rest_api_init',    array( $this, 'action__add_rest_fields') );
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

}

