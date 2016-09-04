<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Url extends AbstractSingleton {
    
    public function get_args( $url ) {
        
        $parts = parse_url( htmlspecialchars_decode( $url ) );
        
        if ( is_array( $parts ) && array_key_exists( 'query', $parts ) ) {
            parse_str( $parts['query'], $query );
            
            return $query;
        }
        
        return array();    
    }
    
    public function get_short( $url ) {
        return apply_filters( '2xpr_blog_get_short_url', $url );
    }
}