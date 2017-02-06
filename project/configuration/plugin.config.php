<?php
namespace Wizzaro\Gallery\v1;

return array(
    'controllers' => array(
        'Wizzaro\Gallery\v1\Controller\PostType',
        'Wizzaro\Gallery\v1\Controller\Images'
    ),
    'configuration' => array(
        'path' => array(
            'main_file' => WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'wizzaro-gallery' . DIRECTORY_SEPARATOR . 'wizzaro-gallery.php',
        ),
        'view' => array(
            'templates_path' => 'project' . DIRECTORY_SEPARATOR . 'View'
        ),
        'languages' => array(
            'domain' => 'wizzaro-gallery-v1'
        ),
        'dir_names' => array(
            'gallery' => '/gallery/',
            'gallery_prefix' => '',
            'original_images' => 'origin',
            'thumbnails' => 'thumbs',
            'gallery_thumbnail' => 'gal_thumb'
        ),
        'ajax_actions' => array(
            'image_upload' => 'wizzaro_gallery_image_upload',
            'set_thumbnail' => 'wizzaro_gallery_set_thumbnail',
            'image_edit' => 'wizzaro_gallery_image_edit',
            'image_delete' => 'wizzaro_gallery_image_delete'
        ),
        'shortcode' => array(
            'default_name' => 'wizzaro-gallery'
        ),
        'default_post_type' => array(
            'post_type' => 'wizzaro-gallery',
            'slug' => 'galleries',
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
            'add_to_main_query' => true,
            'admin_menu_icon' => 'dashicons-format-gallery',
            //'menu_position' => 5,
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
            )
        )
    )
);
