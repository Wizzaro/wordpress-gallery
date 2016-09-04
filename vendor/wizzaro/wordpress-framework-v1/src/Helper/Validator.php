<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Validator extends AbstractSingleton {
    
    public function validate_min_max_int( $int, $min = null, $max = null, $default = false ) {
        if( is_numeric( $int ) ) {
            if( ( ! is_numeric( $min ) || ( $int >= $min ) ) && ( ! is_numeric( $max ) || ( $int <= $max ) ) ) {
                return true;
            }
        }
        
        return $default;
    }
}