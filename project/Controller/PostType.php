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

        //register post type
        $default_public_post_type_args = array (
            'public'              => true,
            'has_archive'         => true,
            'supports'            => array( 'title', 'editor', 'excerpt', 'revisions' ),
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-format-gallery',
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
        );

        $default_private_post_type_args = array_merge( $default_public_post_type_args, array(
            'public'              => false,
            'has_archive'         => false,
            'supports'            => array( 'title', 'revisions' ),
            'show_in_nav_menus'   => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'rewrite'             => false,
            'query_var'           => false,
        ) );

        $post_types_settings = array();

        if (  $this->_config->get_group('use_default_post_type', true ) ) {
            $default_post_type_setting = $this->_config->get_group( 'default_post_type' );
            $post_types_settings[$default_post_type_setting['post_type']] = $default_post_type_setting;
        }

        $post_types_settings = array_merge( $post_types_settings, $this->_config->get_group( 'post_types', array() ) );

        do_action( 'wizzaro_gallery_before_register_post_types' );

        foreach ( $post_types_settings as $post_type => $post_type_settings ) {
            if ( array_key_exists( 'register', $post_type_settings) && $post_type_settings['register'] === false ) {
                $this->_config->set_post_type( $post_type );
            } else {
                $is_public = ( array_key_exists( 'public', $post_type_settings['args'] ) && $post_type_settings['args']['public'] === true );

                if ( ! $is_public ) {
                    $args = array_merge( $default_private_post_type_args, $post_type_settings['args'] );
                } else {
                    $args = array_merge( $default_public_post_type_args, $post_type_settings['args'] );
                }

                if ( array_key_exists( 'taxonomies', $post_type_settings ) ) {
                    $args['taxonomies'] = array_keys( $post_type_settings['taxonomies'] );

                    foreach ( $post_type_settings['taxonomies'] as $tax_name => $tax_settings ) {
                        $this->register_taxonomy( $tax_name, $post_type, $tax_settings, $is_public );
                    }
                }

                register_post_type( $post_type, $args );

                $this->_config->set_post_type( $post_type );

                if ( $args['public'] === true && $post_type_settings['add_to_main_query'] === true ) {
                    $this->_config->set_main_query_post_type( $post_type );
                }
            }
        }

        do_action( 'wizzaro_gallery_after_register_post_types', $post_types_settings );

        flush_rewrite_rules();

        do_action( 'registered_taxonomy_wizzaro_gallery_category', $taxonomy_args );
        do_action( 'registered_post_type_wizzaro_gallery', $post_type_args );
    }

    private function register_taxonomy( $taxonomy, $object_type, array $args, $public = true ) {

        $taxonomy_args = array(
            'labels'              => $args['labels'],
            'public'              => $public,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_quick_edit'  => true,
            'show_admin_column'   => true,
            'hierarchical'        => true,
        );

        if ( array_key_exists( 'slug', $args) ) {
            $taxonomy_args['rewrite'] = array( 'slug' => $args['slug'] );
        }

        if ( array_key_exists( 'hierarchical', $args) ) {
            $taxonomy_args['hierarchical'] = $args['hierarchical'];
        }

        register_taxonomy( $taxonomy, $object_type, $taxonomy_args );
    }

    public function filter_add_gallery_to_posts_list( $query ) {
        if ( $query->is_main_query() && ( is_home() || is_year() || is_month() || is_day() ) ) {
            $post_types = $query->get( 'post_type' );

            if ( is_array( $post_types ) ) {
                $post_types = array_merge( $post_types, $this->_config->get_main_query_post_types() );
            } elseif ( is_string( $post_types ) && mb_strlen( $post_types ) > 0 ) {
                $post_types = array_merge( array( $post_types ), $this->_config->get_main_query_post_types() );
            } else {
                $post_types = array_merge( array( 'post' ), $this->_config->get_main_query_post_types() );
            }

            $query->set( 'post_type', $post_types );
        }

        return $query;
    }
}
