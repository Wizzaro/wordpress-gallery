<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Encrypt extends AbstractSingleton {
    
    public function encryption( $text ) {
        return trim( str_rot13( base64_encode( $text ) ),'=');
    }
    
    public function decryption( $text ) {
        return base64_decode( str_rot13( $text ) );
    }
}