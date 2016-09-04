<?php
namespace Wizzaro\Gallery\v1;

use Wizzaro\WPFramework\v1\Bootstrap\AbstractPluginBootstrap;
use Wizzaro\WPFramework\v1\Helper\Arrays;

use Wizzaro\Gallery\v1\Config\PluginConfig;

class Plugin extends AbstractPluginBootstrap {
    
    protected function _get_config_file() {
        $config = include __DIR__ . '/configuration/plugin.config.php';
        
        $local_config_file = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'wizzaro' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR . 'plugin.config.local.php';
        
        if ( file_exists( $local_config_file ) ) {
            $local_config = include $local_config_file;
            
            if ( is_array( $local_config ) ) {
                $config['configuration'] = Arrays::get_instance()->deep_merge( $config['configuration'], $local_config );
            }
        }

        return $config;
    }
    
    protected function _set_config( $config ) {
        $this->_config = PluginConfig::get_instance();
        $this->_config->set_config( $config );
    }
}