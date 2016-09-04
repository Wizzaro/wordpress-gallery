<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Session extends AbstractSingleton {
    
    protected function __construct() {
        if ( ! isset( $_SESSION ) ) {
            session_start(); //TODO - this is very slow
        }
    }
    
    public function set_variable( $variable_name, $variable_value ) {
        $_SESSION[$variable_name] =  $variable_value;
    }
    
    public function get_variable( $variable_name, $default = '', $remove_varialble_after = false ) {
        if ( isset( $_SESSION[$variable_name] ) ) {
            $return = $_SESSION[$variable_name];
            
            if ( $remove_varialble_after ) {
                $this->remove_variable( $variable_name );
            }
            
            return $return;
        }
        
        return $default;
    }
    
    public function remove_variable($variable_name) {
        if ( isset( $_SESSION[$variable_name] ) ) {
            unset( $_SESSION[$variable_name] );
        }
    }
}