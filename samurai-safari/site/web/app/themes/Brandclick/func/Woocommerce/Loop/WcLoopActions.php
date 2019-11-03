<?php 
namespace Brandclick\Woocommerce\Loop;
use Brandclick\Brandclick;

class WcLoopActions {

    public $product_view_count;

	public function __construct()
  	{	
        // Actions
        add_action('init',                                          array( $this, 'init'));
        add_action('woocommerce_before_shop_loop_item',             array( $this, 'action__load_page_specific_hooks'));
        add_action('woocommerce_after_shop_loop_item_title',        'woocommerce_template_loop_product_link_close', 10 );
        add_action('woocommerce_after_shop_loop_item_title',        array( $this, 'action__add_excerpt'), 11 );
        add_action('woocommerce_before_shop_loop_item',             array( $this, 'action__add_product_tag'));
        add_action('woocommerce_product_query_tax_query',           array( $this, 'action__remove_grouped_products_from_shop'), 20, 1);
        add_action('template_redirect',                             array( $this, 'action__redirect_grouped_to_first_child'));

        // Remove Actions
        remove_action( 'woocommerce_before_shop_loop' ,             'woocommerce_catalog_ordering', 30 );
        remove_action( 'woocommerce_before_shop_loop' ,             'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_after_shop_loop_item_title' ,   'woocommerce_template_loop_rating', 5 );

        // Filters 
        add_filter( 'woocommerce_variable_price_html',              array( $this, 'filter__singular_variation_price'), 10, 2 ); 
        add_filter( 'woocommerce_loop_add_to_cart_link',            array( $this, 'filter__grouped_product_btn_args'), 10, 2 ); 
    }    


    public function init()
    {
        $top_views = get_option('ga_top_page_views'); 
        $this->product_view_count = isset($top_views['top_product_count']) ? $top_views['top_product_count'] : [];
    }


    /**
     * [action__load_page_specific_hooks description]
     * @return [type] [description]
     */
    public function action__load_page_specific_hooks() 
    {   
        global $tiny_products;
        if(!$tiny_products){
            remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        } else {
            remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
            add_action( 'woocommerce_before_shop_loop_item_title',  array( $this, 'action_tiny_loop_product_thumbnail'), 10);
        }
    }

     /**
    *   Get tiny loop product thumbnail
    */
    public function action_tiny_loop_product_thumbnail()
    {
        global $post, $woocommerce;
        $image_id = get_field('tiny_featured_image');
        $image = wp_get_attachment_image( $image_id, 'thumb' );
        echo $image;
    }

    /**
     * [filter__grouped_product_btn_args description]
     * @return [type] [description]
     */
    public function filter__grouped_product_btn_args( $link, $product )
    {   
        if ( $product->is_type( 'grouped' ) ){ 
            $child_products_ids = $product->get_children();
            $parent_id = $product->get_id();

            if(isset($child_products_ids[0])) {
                $product = wc_get_product($child_products_ids[0]);

                $link = sprintf( '<a id="add_to_cart_%s" href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                    $parent_id,
                    esc_url( $product->add_to_cart_url() ),
                    1,
                    implode(
                        ' ',
                        array_filter(
                            array(
                                'button',
                                'product_type_' . $product->get_type(),
                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                            )
                        )
                    ),
                    wc_implode_html_attributes( array(
                        'data-product_id'  => $product->get_id(),
                        'data-product_sku' => $product->get_sku(),
                        'aria-label'       => $product->add_to_cart_description(),
                        'rel'              => 'nofollow',
                    )),
                    esc_html( $product->add_to_cart_text() )
                );
            }    
        }

        return $link; 
    }

    /**
    *   Add the exerpt on the shop page 
    */
    public function action__add_excerpt()
    {   
        global $product, $tiny_products;
        $product_link = get_permalink( $product->get_id() );
        $tiny_description = sprintf( '<p class="tiny-desc">%s</p>', get_field('tiny_description')) ?: '' ;
        $view_count_text = '';

        if(!$tiny_products){
            $exerpt =  get_the_excerpt();

            if(isset($this->product_view_count[$product->get_id()])){
                $view_count_text = sprintf('<small class="view-count">%s %s</small>',  $this->product_view_count[$product->get_id()], __('times viewed in the last 24 hours', Brandclick::THEME_SLUG ) ); 
            }

            echo sprintf( '<div class="content">
                <h2 class="woocommerce-loop-product__title shop-title"><a href="%s">%s</a></h2>
                %s
                %s</div>',
                $product_link,  
                get_the_title(), 
                $tiny_description,
                $view_count_text
            );
        } 

        if ( $product->is_type( 'grouped' ) ) {
            $variable_options = ''; 
            $child_products_ids = $product->get_children();

            foreach ($child_products_ids as $product_id) {
                
                $thumb = wp_get_attachment_image_url( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );;
                $term = get_the_terms( $product_id, 'pa_color' );
                if(isset($term[0])) {
                    $color = get_term_meta($term[0]->term_id, 'color', true );
                    $variable_options .= '<button data-parent-id="'.$product->get_id().'" data-product-id="'. $product_id .'" data-featured="'. $thumb .'" class="btn btn-radio" style="background:'. $color .'"></button>'; 
                } 
            }
            echo '<div class="color-swatches">' . $variable_options . '</div>';
        }

    }


    /**
    *   Add the exerpt on the shop page 
    */
    public function action__add_product_tag(){
        $terms = get_the_terms( get_the_ID(), 'product_tag' );
        if($terms){
            foreach($terms as $product_tag){
                $name = $product_tag->name;
                $color_class = get_term_meta($product_tag->term_id, 'color_class', true );
                if($color_class){
                echo '<div class="tag_container"> <span>'. $name . '</span><div class="tag_bg '. $color_class .'"></div></div> ';
                }
            }
        }
    }

    /**
     * [action__remove_grouped_products_from_shop description]
     * @return [type] [description]
     */
    public function action__remove_grouped_products_from_shop( $tax_query )
    {
        $tax_query[] = array(
            'taxonomy'  => 'product_type',
            'terms'     => array('grouped'),
            'field' => 'slug',
            'operator' => 'NOT IN',
        );
        return $tax_query;
    }

    public function action__redirect_grouped_to_first_child( $tax_query )
    {   
        if( is_product() ) {
            $product = wc_get_product( get_the_ID() );
       
            if ( $product->is_type( 'grouped' ) ){ 
                $child_products_ids = $product->get_children();

                if(isset($child_products_ids[0])) {
                    wp_redirect( get_permalink($child_products_ids[0]), 301 );
                } else {
                    wp_redirect( home_url(), 301 );
                }
                
                exit;
            }
        }
       
    }
    

    /**
    *   Filter only show the cheapest price 
    */
    public function filter__singular_variation_price( $price, $product ) 
    {
        $price = '' . wc_price($product->get_price()); 

        return $price;
    }

}