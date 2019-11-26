<?php 
namespace Greylabel\DataAPI;
use Greylabel\Greylabel;

class ProcessExternalData {

	public function __construct()
    {   
        if( $kiyoh_url = get_option('kiyoh_url') ) {
            
            if ( ! wp_next_scheduled( 'process_external_xml' ) ) {
                wp_schedule_event( time(), 'hourly', 'process_external_xml' ); //daily
            }

            // Actions
            add_action( 'process_external_xml',                      array( $this, 'action__schedule_get_kiyoh_reviews_xml') ); 

            // run this function directly if de option is not set 
            if( ! get_option('kiyoh_reviews_json') ) { 
                self::action__schedule_get_kiyoh_reviews_xml(); 
            }
        }
    }

    

    /**
    *   return external xml object  
    */
    public function get__article_data_from_link( $url ) 
    {
	 	$content = file_get_contents( $url );
        $doc = new DOMDocument();
        @$doc->loadHTML($content);        // squelch HTML5 errors
        $meta = $doc->getElementsByTagName('meta');
        $tags= [];

        foreach ($meta as $element) {
            $property = '';
            $content = '';
            foreach ($element->attributes as $node) {

                if($node->name == 'property' || $node->name == 'name') {
                    $property = $node->value;
                }

                if($node->name == 'content') {
                    $content = $node->value;
                }

                if($property && $content) {
                    $tags[$property] = $content;
                } 
            }
        }

        return $tags ?: false; 
    }   


    /**
    *   update transient and options
    */
    public function action__schedule_get_kiyoh_reviews_xml() 
    {
        $xml = $this->callback__get_external_xml_as_json( get_option('kiyoh_url') ); 

        update_option( 'kiyoh_reviews_json', $xml );
    }    


    /**
    *   return external xml object  
    */
    public function callback__get_external_xml_as_json( $url ) 
    {
        $response   =   wp_remote_get($url);
        $body       =   wp_remote_retrieve_body($response);
        $xml_obj    =   simplexml_load_string($body);
        $json_obj   =   json_encode($xml_obj);
       
        return $json_obj;
    }    

}

