<?php 
namespace Greylabel\DataAPI;
use Greylabel\Greylabel;
use Greylabel\DataAPI\ProcessExternalData;
use Greylabel\DataAPI\CreateDataFile;

class UpdateExternalData {

	public function __construct()
    {   
        // Actions
        add_action( 'acf/save_post',                      array( $this, 'action__update_fields_from_url'), 10 ); 
    }


    public function action__update_fields_from_url( $post_id )
    {   
        $post_type = get_post_type($post_id);
        if ($post_type != 'company') {
            return;
        }
       
        if( have_rows('articles', $post_id ) ) {
   
            while ( have_rows('articles', $post_id ) ) : the_row();
                $title = get_sub_field('title');
                $url = get_sub_field('url');
                $img_id = get_sub_field('featured_image');
                $article_excerpt = get_sub_field('article_description');
       
                if( !$url ) {
                    continue;
                }

                if( $title == '' && $img_id == '' ) {
                    $processor = new ProcessExternalData(); 
                    $tags = $processor->get__url_page_meta_tags($url); 
                    
                    $article_domain = isset($tags['og:site_name']) ? $tags['og:site_name'] : false;
                    $article_img = isset($tags['og:image']) ? $tags['og:image'] : false;
                    $article_title = isset($tags['og:title']) ? $tags['og:title'] : false;
                    $article_excerpt = isset($tags['og:description']) ? wp_trim_words($tags['og:description'], 40) : false;
                    
                    if( $article_title ){
                        update_sub_field('title', $article_title);
                        $title = $article_title;
                    }

                    if( $article_domain ){
                        update_sub_field('site_title', $article_domain);
                    }

                    if( $article_excerpt ) {
                        update_sub_field('article_description', $article_excerpt);
                    }
                    
                    if( $article_img ){
                        $processor = new CreateDataFile(); 
                        $attach_id = $processor->save__image_from_url($article_img); 

                        if( $attach_id ) {
                            update_sub_field('featured_image', $attach_id);
                        }
                    }

                }

            endwhile;  
     
            
        }
    }
    
}

