<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Filter extends AbstractSingleton {
    
    public function filter_text( $text ) {
        $text = sanitize_text_field( esc_attr( $text ) );
        $text = trim( $text );
        
        return $text;
    }
    
    public function filter_url( $url, $default_scheme = "http" ) {
        $url = $this->filter_text( $url );
            
        if ( mb_strlen( $url ) > 0 ) {
            $parse_url = parse_url( $url );
            
            if ( ! array_key_exists( 'scheme', $parse_url) ) {
                $url = preg_replace( '/^(:)?(\/)*/i', '', $url);
                $url = $default_scheme . '://' . $url;
            }
        }
        
        return $url;
    }
    
    public function filter_int( $int ) {
        $int = $this->filter_text( $int );
        $int = preg_replace('/[^0-9]/i', '', $int);
        
        if ( mb_strlen( $int ) > 0 ) {
            return intval( $int );
        }
        
        return '';
    }
    
    public function filter_hex_color( $color ) {
        if ( '' === $color )
            return '';
    
        // 3 or 6 hex digits, or the empty string.
        if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
            return $color;
    }
}