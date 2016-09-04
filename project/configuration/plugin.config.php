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
            'image_delete' => 'wizzaro_gallery_image_delete'
        )
    )
);