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
     * [get__url_page_meta_tags description]
     * @param  [type] $url [description]
     * @return [array]      [all meta tags]
     */
    public function get__url_page_meta_tags( $url ) 
    {
        $content = self::get__contents_curl($url);
        $doc = new \DOMDocument();

        // squelch HTML5 errors
        @$doc->loadHTML($content['content']);

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

        return $tags;
    }


    /**
     * [get__contents_curl description]
     * @param  [type] $url [get page content from url]
     * @return [type]      [description]
     */
    public function get__contents_curl( $url ) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        
        $result = curl_exec($ch);
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close($ch);

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $result;

        return $header;
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

