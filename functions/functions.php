<?php

use Wizzaro\Gallery\v1\Service\Images as ImagesService;

function wizzaro_gallery_get_post_thumbnail( $post ) {
    return ImagesService::get_instance()->get_post_thumbnail( $post );
}