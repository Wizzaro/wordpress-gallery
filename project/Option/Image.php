<?php
namespace Wizzaro\Gallery\v1\Option;

use Wizzaro\WPFramework\v1\Option\AbstractOptionSingleton;

class Image extends AbstractOptionSingleton {
    
    protected $_options = array(
        'wizzaro_gallery_image' => array(
            'width' => 1024,
            'height' => 1024,
            'thumb_width' => 360,
            'thumb_height' => 720,
            'default_alt' => 'Gallery {gallery_name} - image {image_name}'
        )
    );
}