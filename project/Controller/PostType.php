<?php
namespace Wizzaro\Gallery\v1\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;

class PostType extends AbstractPluginController {
    
    public function init() {
        add_action( 'init', array( $this, 'register_gallery' ), 0 );
    }
    
    public function init_front() {
        add_action( 'pre_get_posts', array( $this, 'filter_add_gallery_to_posts_list' ) );
    }
    
    public function register_gallery() {
        $languages_domain = $this->_config->get( 'languages', 'domain' );
        
        //register post status
        register_post_status( 'w-gallery-noread', array(
            'public' => false,
            'show_in_admin_all_list' => false
        ));
        
        //register taxonomy
        $taxonomy_args = array(
            'labels' => array(
                'name'                  => __( 'Gallery Categories ', $languages_domain ),
                'singular_name'         => __( 'Gallery Category ', $languages_domain ),
                'all_items'             => __( 'All Categories' ), 
                'edit_item'             => __( 'Edit Category' ),
                'view_item'             => __( 'View Category' ),
                'update_item'           => __( 'Update Category' ),
                'add_new_item'          => __( 'Add New Category' ),
                'new_item_name'         => __( 'New Category Name' ),
                'parent_item'           => __( 'Parent Category' ),
                'parent_item_colon'     => __( 'Parent Category:' ),
                'search_items'          => __( 'Search Categories' ),
                'not_found'             => __( 'No categories found.' )
            ),
            'rewrite'               => array( 'slug' => 'galleries-category' ),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_quick_edit'  => true,
            'show_admin_column'   => true,
            'hierarchical'        => true,
        );
        
        
        register_taxonomy( 'wizzaro-gallery-category', 'wizzaro-gallery', $taxonomy_args );
        
        //register post type
        $post_type_args = array (
            'labels' => array(
                'name'                  => __( 'Galeries', $languages_domain ),
                'singular_name'         => __( 'Gallery', $languages_domain ),
                'add_new'               => __( 'Add Gallery', $languages_domain ),
                'add_new_item'          => __( 'Add New Gallery', $languages_domain ),
                'edit'                  => __( 'Edit Gallery', $languages_domain ),
                'edit_item'             => __( 'Edit Gallery', $languages_domain ),
                'new_item'              => __( 'New Gallery', $languages_domain ),
                'view_item'             => __( 'View Gallery', $languages_domain ),
                'search_items'          => __( 'Search Galleries', $languages_domain ),
                //'search_items'       => __( 'Find by ID', $languages_domain ),
                'not_found'             => __( 'No Galeries found', $languages_domain ),
                'not_found_in_trash'    => __( 'No Galeries found in trash', $languages_domain ),
                'all_items'             => __( 'All Galleries', $languages_domain ),
                'archives'              => __( 'Galleries Archives', $languages_domain ),
                'insert_into_item'      => __( 'Insert into gallery', $languages_domain ),
                'uploaded_to_this_item' => __( 'Uploaded to this gallery', $languages_domain ),
                'menu_name'             => __( 'Galeries', $languages_domain),
            ),
            'public'              => true,
            'has_archive'         => true,
            'supports'            => array( 'title', 'editor', 'excerpt', 'revisions' ),
            'taxonomies'          => array( 'wizzaro-gallery-category'),
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-format-gallery',
            'can_export'          => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'rewrite'               => array( 'slug' => 'galleries' ),
        );
        
        register_post_type( 'wizzaro-gallery', $post_type_args );
        
        flush_rewrite_rules();
        
        do_action( 'registered_taxonomy_wizzaro_gallery_category', $taxonomy_args );
        do_action( 'registered_post_type_wizzaro_gallery', $post_type_args );
    }

    public function filter_add_gallery_to_posts_list( $query ) {
        if ( $query->is_main_query() && ( is_home() || is_search() || is_author() || is_year() || is_month() || is_day() || is_tax() ) ) {
            $post_types = $query->get( 'post_type' );

            if ( is_array( $post_types ) ) {
                $post_types = array_merge( $post_types, array( 'wizzaro-gallery' ) );
            } elseif ( is_string( $post_types ) && mb_strlen( $post_types ) > 0 ) {
                $post_types = array( $post_types, 'wizzaro-gallery' );
            } else {
                $post_types = array( 'post', 'wizzaro-gallery' );
            }

            $query->set( 'post_type', $post_types );
        }

        return $query;
    }
}