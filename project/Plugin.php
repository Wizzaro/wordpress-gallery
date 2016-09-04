<?php
namespace Wizzaro\Gallery\v1;

use Wizzaro\WPFramework\v1\Bootstrap\AbstractPluginBootstrap;

use Wizzaro\Gallery\v1\Config\PluginConfig;

class Plugin extends AbstractPluginBootstrap {
    
    protected function _get_config_file() {
        return include __DIR__ . '/configuration/plugin.config.php';
    }
    
    protected function _set_config( $config ) {
        $this->_config = PluginConfig::get_instance();
        $this->_config->set_config( $config );
    }
}