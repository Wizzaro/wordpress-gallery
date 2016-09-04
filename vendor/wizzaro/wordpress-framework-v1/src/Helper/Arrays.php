<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Arrays extends AbstractSingleton {
    
    public function deep_merge( $array1, $array2 ) {
        $merged = $array1;
    
        foreach ( $array2 as $key => &$value )
        {
            if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) ) {
                $merged[$key] = $this->deep_merge( $merged[$key], $value );
            } else if ( is_numeric( $key ) ) {
                 if ( ! in_array( $value, $merged ) )
                    $merged[] = $value;
            } else {
                $merged[$key] = $value;
            }
        }
    
        return $merged;
    }
}