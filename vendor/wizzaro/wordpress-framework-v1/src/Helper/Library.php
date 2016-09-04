<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Library extends AbstractSingleton {
    
    private $_librarys_instances = array();
    
    public function get_library( $class_name ) {
        if ( ! array_key_exists( $class_name, $this->_librarys_instances ) ) {
            $this->_librarys_instances[$class_name] = new $class_name();
        }
        
        return $this->_librarys_instances[$class_name];
    }
}