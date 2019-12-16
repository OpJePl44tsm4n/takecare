<?php 
namespace Greylabel\Register;
use Greylabel\Greylabel;

class RegisterPostTypes {



    /**
    *   register new post types 
    */
    public static function action__register_post_types() 
    {   
        $post_types = [
            [   
                'name'      => 'Company',
                'slug'      => 'company',
                'plural'    => 'Companies',
                'icon'      => 'dashicons-store',
                'publicly_queryable'  => true,
                'supports'  => array( 'title', 'editor', 'excerpt', 'thumbnail' )
            ],
        ];

        foreach ($post_types as $post_type) 
        {
            // Set UI labels for Custom Post Type
            $labels = array(
                'name'                => _x( $post_type['plural'], 'Post Type General Name', Greylabel::THEME_SLUG ),
                'singular_name'       => _x( $post_type['name'], 'Post Type Singular Name', Greylabel::THEME_SLUG ),
                'menu_name'           => __( $post_type['plural'], Greylabel::THEME_SLUG ),
                'parent_item_colon'   => __( "Parent " . $post_type['name'], Greylabel::THEME_SLUG ),
                'all_items'           => __( "All " . $post_type['plural'], Greylabel::THEME_SLUG ),
                'view_item'           => __( "View " . $post_type['name'], Greylabel::THEME_SLUG ),
                'add_new_item'        => __( "Add New " . $post_type['name'], Greylabel::THEME_SLUG ),
                'add_new'             => __( 'Add New', Greylabel::THEME_SLUG ),
                'edit_item'           => __( "Edit " . $post_type['name'], Greylabel::THEME_SLUG ),
                'update_item'         => __( "Update " . $post_type['name'], Greylabel::THEME_SLUG ),
                'search_items'        => __( "Search " . $post_type['name'], Greylabel::THEME_SLUG ),
                'not_found'           => __( 'Not Found', Greylabel::THEME_SLUG ),
                'not_found_in_trash'  => __( 'Not found in Trash', Greylabel::THEME_SLUG ),
            );
             
            // Set other options for Custom Post Type
            $args = array(
                'label'               => __( $post_type['plural'], Greylabel::THEME_SLUG ),
                // 'description'         => __( $post_type['plural'] . " news and reviews", Greylabel::THEME_SLUG ),
                'labels'              => $labels,
                // Features this CPT supports in Post Editor
                'supports'            => $post_type['supports'],
                'hierarchical'        => false,
                'public'              => true,
                'query_var'           => true,
                'rewrite'             => array( 'slug' => $post_type['slug'], 'with_front' => false  ),
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_rest'        => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 5,
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => $post_type['publicly_queryable'],
                'capability_type'     => 'post',
                'taxonomies'          => array( 'category', 'post_tag' ),
                'menu_icon'           => $post_type['icon'],
            );
             
            // Registering your Custom Post Type
            register_post_type( $post_type['slug'], $args );
        }
        
    }   

    /**
    *   register new taxonomies
    */
    public static function action__register_taxonomies() 
    {   
        register_taxonomy(
            'dossier',
            'post',
            array(
                'label'                 => __( 'Dossier', Greylabel::THEME_SLUG ),
                'rewrite'               => array( 'slug' => 'dossier' ),
                'hierarchical'          => true,
                'show_admin_column'     => true,
                'public'                => true,
                'show_in_rest'          => false,
                'has_archive'           => false,
            )
        );  

        register_taxonomy(
            'city',
            'company',
            array(
                'label'                 => __( 'City', Greylabel::THEME_SLUG ),
                'rewrite'               => array( 'slug' => 'city', 'with_front' => false ),
                'hierarchical'          => true,
                'show_admin_column'     => true,
                'public'                => true,
                'show_in_rest'          => false,
                'has_archive'           => false,
            )
        );

        register_taxonomy(
            'certificate',
            'company',
            array(
                'label'                 => __( 'Certificate', Greylabel::THEME_SLUG ),
                'rewrite'               => array( 'slug' => 'certificate', 'with_front' => false  ),
                'hierarchical'          => true,
                'show_admin_column'     => true,
                'public'                => true,
                'show_in_rest'          => true,
                'has_archive'           => false,

            )
        );

    
    } 

    /**
     * [action__add_post_types_to_archive filter the archive query and add posttypes]
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public static function action__add_post_types_to_archive( $query )
    {
        if( ($query->is_category() || $query->is_tag()) && $query->is_main_query()  ){
            $query->set( 'post_type', array( 'company' ) );
        }

    }

}



