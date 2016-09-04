<?php
namespace Wizzaro\WPFramework\v1\Config;

use Wizzaro\WPFramework\v1\Config\AbstractConfig;

abstract class AbstractThemeConfig extends AbstractConfig {
    
    public function get_dir_path() {
        if ( ! isset( $this->_config['path']['dir'] ) ) {
            $this->_config['path']['dir'] = get_template_directory() . DIRECTORY_SEPARATOR;
        }
        
        return $this->_config['path']['dir'];
    }
    
    public function get_dir_url() {
        if ( ! isset( $this->_config['path']['url'] ) ) {
            $this->_config['path']['url'] = get_template_directory_uri() . DIRECTORY_SEPARATOR;
        }
        
        return $this->_config['path']['url'];
    }
}