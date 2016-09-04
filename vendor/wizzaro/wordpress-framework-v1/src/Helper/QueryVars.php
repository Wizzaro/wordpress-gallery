<?php
namespace Wizzaro\WPFramework\v1\Helper;

class QueryVars {
    
    private $_query_vars = array();
    
    public function __construct( $query_vars ) {
        $this->_query_vars = $query_vars;
        $this->init_query_vars();
    }
    
    public function init_query_vars() {
        add_action( 'init', array( $this, 'add_query_vars_endpoints' ) );
        
        if ( ! is_admin() ) {
            add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
            add_action( 'parse_request', array( $this, 'parse_query_vars_request' ), 0 );
        }
    }
    
    public function add_query_vars_endpoints() {
        foreach ( $this->_query_vars as $key => $var ) {
            if ( array_key_exists( 'end_point', $var ) && true === $var['end_point'] ) {
                add_rewrite_endpoint( $var['name'], EP_ALL );
            }
        }
    }
    
    public function add_query_vars($vars) {
        foreach ( $this->_query_vars as $key => $var ) {
            $vars[] = $key;
        }

        return $vars;
    }
    
    public function parse_query_vars_request() {
        global $wp;

        // Map query vars to their keys, or get them if endpoints are not supported
        foreach ( $this->_query_vars as $key => $var ) {
            if ( isset( $_GET[ $var['name'] ] ) ) {
                $wp->query_vars[ $key ] = $_GET[ $var['name'] ];
            }
            elseif ( isset( $wp->query_vars[ $var['name'] ] ) ) {
                $wp->query_vars[ $key ] = $wp->query_vars[ $var['name'] ];
            }
        }
    }
}