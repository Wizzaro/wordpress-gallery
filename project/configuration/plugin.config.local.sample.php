<?php
/*
 * This is sample configuration for wizzaro gallery plugin
 * In this place you can replace original configuration, add new post types etc.
 * Add this configuration to ../wp-content/wizzaro/plugins/gallery/plugin.config.local.php file
 */
namespace Wizzaro\Gallery\v1;

return array(
    /*
     * This variable allows decide whether use the default post types (Gallery) with custom post types which are defined in "post_types" settings variable in this file
     */
    'use_default_post_type' => true, //or false
    /*
     * This variable gives you the opportunity to replace default post type settings.
     * If you don't wont replace any settings or you set "use_default_post_type" to false - skip this setting.
     */
    'default_post_type' => array(
        'post_type' => 'wizzaro-gallery',
        'args' => array(
            'public' => true,
            'labels'=> array(
                'name'                  => __( 'Galeries', 'wizzaro-gallery-v1' ),
                'singular_name'         => __( 'Gallery', 'wizzaro-gallery-v1' ),
                'add_new'               => __( 'Add Gallery', 'wizzaro-gallery-v1' ),
                'add_new_item'          => __( 'Add New Gallery', 'wizzaro-gallery-v1' ),
                'edit'                  => __( 'Edit Gallery', 'wizzaro-gallery-v1' ),
                'edit_item'             => __( 'Edit Gallery', 'wizzaro-gallery-v1' ),
                'new_item'              => __( 'New Gallery', 'wizzaro-gallery-v1' ),
                'view_item'             => __( 'View Gallery', 'wizzaro-gallery-v1' ),
                'search_items'          => __( 'Search Galleries', 'wizzaro-gallery-v1' ),
                'not_found'             => __( 'No Galeries found', 'wizzaro-gallery-v1' ),
                'not_found_in_trash'    => __( 'No Galeries found in trash', 'wizzaro-gallery-v1' ),
                'all_items'             => __( 'All Galleries', 'wizzaro-gallery-v1' ),
                'archives'              => __( 'Galleries Archives', 'wizzaro-gallery-v1' ),
                'insert_into_item'      => __( 'Insert into gallery', 'wizzaro-gallery-v1' ),
                'uploaded_to_this_item' => __( 'Uploaded to this gallery', 'wizzaro-gallery-v1' ),
                'menu_name'             => __( 'Galeries', 'wizzaro-gallery-v1'),
            ),
            'menu_icon' => 'dashicons-format-gallery',
            'rewrite' => array(
                'slug' => 'galleries'
            )
        ),
        'taxonomies' => array(
            'wizzaro-gallery-category' => array(
                'slug' => 'galleries-category',
                'hierarchical' => true,
                'labels' => array(
                    'name'                  => __( 'Gallery Categories ', 'wizzaro-gallery-v1' ),
                    'singular_name'         => __( 'Gallery Category ', 'wizzaro-gallery-v1' ),
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
                )
            )
        ),
        'add_to_main_query' => true,
        'shordcode' => true
    ),
    /*
     * This variable gives you the opportunity to create your own post types for this plugin.
     * If you don't wont create custom post types - skip this setting.
     * IMPORTANT: If you wont use onlu one post type and name of this in not "Gallery" but "My Gallery" (for example) then better idea is replace "default_post_type" setting.
     */
    'post_types' => array(
        'custom_post_type_key' => array(
            /*
             * Inform about is this post  should be registered. Use false if you wont add gallery support for existing post type
             * Required: no
             */
            'register' => true,
            /*
             * An array of arguments. For more information look on: https://codex.wordpress.org/Function_Reference/register_post_type#Arguments
             * Required: If "register" parameter is true then yes otherwise no
             */
            'args' => true,
            /*
             * Define shordcode name for post type which use for build shordcode string ex. [defined-shordcode-name id="gallery_post_id"]
             * use boolean true | false or string with shordcoe name (true = default shordcode name)
             * Required no
             */
            'shordcode' => 'shordcode-name'
        ),
        // ... - second post type
    )
);
