<?php 
namespace Brandclick\Register;
use Brandclick\Brandclick;

class RegisterRedirects {

	public function __construct()
    {
    	add_action("template_redirect", array($this, "action__redirect_magazine_posts"));
    } 

	/**
    *   Redirect magazine posts to fix old indexed posts who were missing the magazine structure
    */
    public function action__redirect_magazine_posts() 
    {
     	if(is_404()) {
            $url_obj = parse_url( $_SERVER['REQUEST_URI'] );
            $query_string = isset($url_obj['query']) ? '?' . $url_obj['query'] : '';
               
	        $page = get_page_by_path( trailingslashit( basename($url_obj['path']) ),OBJECT,'post');

		    if($page && $page->post_type !== 'attachment'){
		        header("HTTP/1.1 301 Moved Permanently"); 
		        header("Location: " . get_permalink($page->ID) . $query_string );
		    }
		}    
    }

}
