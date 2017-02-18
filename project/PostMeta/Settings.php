<?php
namespace Wizzaro\Gallery\v1\PostMeta;

class Settings extends AbstractPostMeta {

    /**
     * @var string
     */
    protected $_meta_key = '_post_gallery_settings';

    /**
     * @var array
     */
    protected $_meta_value = array(
        'columns_count' => 4,
        'display_empty_info' => 1
    );
}
