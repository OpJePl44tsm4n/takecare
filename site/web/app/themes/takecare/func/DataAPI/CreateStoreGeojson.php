<?php 
namespace Greylabel\DataAPI;
use Greylabel\Greylabel;

class CreateStoreGeojson {

	public function __construct()
  	{	
        // Actions
        add_action( 'save_post_store',                      array( $this, 'action__create_geojson_object') ); 
    }

	/**
    *   Create geojson object  
    */
    public function action__create_geojson_object() 
    {
	 	$geojson = [
           'type'      => 'FeatureCollection',
           'features'  => []
        ]; 

        $file_name = 'stores.geojson'; 
        $stores = $this::prepare_store_data(); 
        $cities = $this::prepare_city_data(); 
       	$data = array_merge($stores, $cities);
       	$geojson['features'] = $data;
  
       	if(!empty( $data )) {
       		CreateDataFile::create_json_file($geojson, $file_name); 
       	}
    }    


	/**
    *   prepare store data 
    *   @return data [array]
    */
    private function prepare_store_data() 
    {
    	$args = array(
            'post_type'     => 'company',
            'posts_per_page' => -1
        ); 

    	// The Query
        $posts = get_posts( $args ); 
        $collection = []; 
       
       
        if ( $posts ) : 
            foreach ( $posts as $post ) : 
                setup_postdata( $post );
         
                $city = get_field('city', $post->ID);
                $store_title = $post->post_title;
                $store_url = get_field('url', $post->ID);
                $location_obj = get_field('location', $post->ID); 
                $address_obj = explode(', ', $location_obj['address']);
                $address = isset($address_obj[0]) ? $address_obj[0] : '';
                $postcode = isset($address_obj[1]) ? str_replace( ' ' . $city->name, '', $address_obj[1]) : '';
          
              	if(isset($location_obj['lng']) && isset($location_obj['lat'])) {
	                $feature = array(
	                    'id' => get_the_id(),
	                    'type' => 'Feature', 
	                    'geometry' => array(
	                        'type' => 'Point',
	                        'coordinates' => array($location_obj['lng'], $location_obj['lat'])
	                    ),
	                    'properties' => [
	                        'name' => $store_title,
	                        'type' => 'store',
	                        // 'marker-color' => '#f9ed1a',
	                        // 'marker-size' => 'small',
	                        // 'marker-symbol' => 'circle',
	                        'city' => $city->name,
	                        'postcode' => $postcode,
	                        'address' => $address,
	                        'site' => $store_url,
                            "icon" => [
                                'className' => 'custom-marker-icon',
                                // 'html' => '', 
                                'iconSize' => 'null' 
                            ]

	                    ]
	                );
	          
	                array_push($collection, $feature);
	            }

            endforeach;    
            wp_reset_postdata();
        endif;

        return $collection;
    }

	/**
    *   prepare city data  
    *   @return data [array]
    */
    private function prepare_city_data() 
    {	
    	$term_args = [
            'taxonomy' => 'city', 
            'orderby' => 'name',
        ];

        $terms = get_terms($term_args);
        $collection = []; 

        if($terms):
        	foreach ( $terms as $term):
        		$location_obj = get_field('location', $term); 

        		if(isset($location_obj['lng']) && isset($location_obj['lat'])) {
	                $feature = array(
	                    'type' => 'Feature', 
	                    'geometry' => array(
	                        'type' => 'Point',
	                        'coordinates' => array($location_obj['lng'], $location_obj['lat'])
	                    ),
	                    'properties' => [
	                        'name' => $term->name,
	                        'slug' => $term->slug,
	                        'type' => 'city'
	                    ]
	                );

	                array_push($collection, $feature);
	            }
        	endforeach;	
        endif;

        return $collection;
    }




}

