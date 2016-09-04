<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class Request extends AbstractSingleton {
    
    public function is_ajax() {
        if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ) {
            return true;
        }
        
        return false;
    }
    
    public function get_host_url() {
        if ( array_key_exists( 'HTTP_HOST', $_SERVER ) ) {
            return 'http' . ( empty( $_SERVER['HTTPS'] ) ? '' : 's' ) . '://' . $_SERVER['HTTP_HOST'];
        }
        
        return '';
    }
    
    public function get_request_url( $with_parameters = true ) {
        $host_url = $this->get_host_url();
        
        if ( array_key_exists( 'REQUEST_URI', $_SERVER ) && mb_strlen( $host_url ) > 0 ) {
            return $host_url . $this->get_request_uri( $with_parameters );
        }
        
        return '';
    }
    
    public function get_request_uri( $with_parameters = true ) {
        if ( $with_parameters ) {
            return $_SERVER['REQUEST_URI'];
        }
        
        return parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    }
    
    public function get_user_agent() {
        if ( array_key_exists( 'HTTP_USER_AGENT', $_SERVER ) ) {
            return esc_attr( $_SERVER['HTTP_USER_AGENT'] );
        }
        
        return '';
    }
    
    public function get_remote_addr() {
        if ( array_key_exists( 'REMOTE_ADDR', $_SERVER ) ) {
            return esc_attr( $_SERVER['REMOTE_ADDR'] );
        }
        
        return '';
    }
    
    public function get_http_referer() {
        if ( array_key_exists( 'HTTP_REFERER', $_SERVER ) ) {
            return esc_attr( $_SERVER['HTTP_REFERER'] );
        }
        
        return '';
    }
}