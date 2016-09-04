<?php
namespace Wizzaro\Gallery\v1\Config;

use Wizzaro\WPFramework\v1\Config\AbstractPluginConfig;

class PluginConfig extends AbstractPluginConfig {
    
    public function get_galeries_post_types() {
        return array_merge( apply_filters( 'wizzaro_galley_get_post_types', array() ), array( 'wizzaro-gallery' ) );
    }
}