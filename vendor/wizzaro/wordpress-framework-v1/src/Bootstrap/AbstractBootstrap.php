<?php
namespace Wizzaro\WPFramework\v1\Bootstrap;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

abstract class AbstractBootstrap extends AbstractSingleton {
    
    protected $_config = array();
    
    protected function __construct() {
        $config = $this->_get_config_file();
        
        //set configuration
        if ( array_key_exists( 'configuration', $config) ) {
            $this->_set_config( $config['configuration'] );
        }
        
        //init controllers
        if ( array_key_exists( 'controllers', $config ) ) {
            foreach ( $config['controllers'] as $c_name ) {
                $controller = new $c_name( $this->_config );
                
                $controller->init();
                
                if ( ! is_admin() ) {
                    $controller->init_front();
                } else {
                    $controller->init_admin();
                }
            }
        }
    }
    
    protected function _get_config_file() {
        return array();
    }
    
    protected function _set_config( $config ) {
        $this->_config = $config;
    }
}