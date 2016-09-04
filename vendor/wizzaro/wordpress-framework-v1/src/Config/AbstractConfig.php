<?php
namespace Wizzaro\WPFramework\v1\Config;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

abstract class AbstractConfig extends AbstractSingleton {
    
    protected $_config = array();
    
    public function set_config( $config ) {
        if ( count( $this->_config ) <= 0 ) {
            $this->_config = $config;
        }
    }
    
    public function get_group( $group, $default = array() ) {
        if ( isset( $this->_config[$group] ) ) {
            return $this->_config[$group];
        }
        
        return $default;
    }
    
    public function get( $group, $key, $default = '' ) {
        if ( isset( $this->_config[$group][$key] ) ) {
            return $this->_config[$group][$key];
        }
        
        return $default;
    }
    
    public function get_dir_path() {
        return '';
    }
    
    public function get_dir_url() {
        return '';
    }
    
    public function get_css_url() {
        return $this->get_dir_url() . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;
    }
    
    public function get_css_admin_url() {
        return $this->get_dir_url() . 'assets' . DIRECTORY_SEPARATOR . 'css'. DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
    }
    
    public function get_js_url() {
        return $this->get_dir_url() . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;
    }
    
    public function get_js_admin_url() {
        return $this->get_dir_url() . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
    }
    
    public function get_images_url() {
        return $this->get_dir_url() . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
    }
    
    public function get_images_admin_url() {
        return $this->get_dir_url() . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
    }
    
    public function get_view_templates_path() {
        return $this->get_dir_path() . $this->_config['view']['templates_path'] . DIRECTORY_SEPARATOR;
    }
}
