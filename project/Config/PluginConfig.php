<?php
namespace Wizzaro\Gallery\v1\Config;

use Wizzaro\WPFramework\v1\Config\AbstractPluginConfig;

class PluginConfig extends AbstractPluginConfig {

    private $_post_types = array();
    private $_main_query_post_types = array();
    private $_post_types_shordcodes = array();

    public function set_post_type( $post_type ) {
        if ( ! in_array( $post_type, $this->_post_types ) ) {
            array_push( $this->_post_types, $post_type );
        }
    }

    public function get_post_types() {
        return $this->_post_types;
    }

    public function set_main_query_post_type( $post_type ) {
        if ( ! in_array( $post_type, $this->_main_query_post_types ) ) {
            array_push( $this->_main_query_post_types, $post_type );
        }
    }

    public function get_main_query_post_types() {
        return $this->_main_query_post_types;
    }

    public function get_galeries_post_types() {
        return array_merge( apply_filters( 'wizzaro_galley_get_post_types', array() ), $this->_post_types );
    }

    public function set_post_type_shordcode( $post_type, $shordcode ) {
        if ( ! array_key_exists( $post_type, $this->_post_types_shordcodes ) ) {
            $this->_post_types_shordcodes[$post_type] = $shordcode;
        }
    }

    public function get_post_types_shordcodes() {
        return $this->_shordcode;
    }

    public function get_post_type_shordcode( $post_type ) {
        if ( array_key_exists( $post_type, $this->_post_types_shordcodes ) ) {
            return $this->_post_types_shordcodes[$post_type];
        }

        return $this->get( 'shortcode', 'default_name' );
    }

    public function get_shordcodes() {
        return array_unique( array_values( $this->_post_types_shordcodes ) );
    }
}
