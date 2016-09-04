<?php
namespace Wizzaro\WPFramework\v1\Option;

abstract class AbstractOption {
    
    protected $_options = array();
    
    public function __construct( array $options = array() ) {
        if ( count( $options ) > 0 ) {
            $this->_options = $options;
        }
        
        $this->load_options();
    }
    
    public function set_default_options_values( $force = false) {
        foreach ( $this->_options as $o_key => $o_val) {
            if ( ! get_option( $o_key ) || $force === true ) {
                $this->update_option( $o_key, $o_val );
            }
        }
    }
    
    public function load_options() {
        
        foreach ( $this->_options as $o_key => $o_value ) {
            $option_value = get_option( $o_key );

            if ( $option_value === false ) {
                continue;
            }

            if ( is_array( $this->_options[$o_key] ) ) {
                if ( is_array( $option_value ) ) {
                    $this->_options[$o_key] = array_merge( $this->_options[$o_key], $option_value );
                }
            } else {
                if ( ! is_array( $option_value ) ) {
                    $this->_options[$o_key] = $option_value;
                }
            }
        }
    }
    
    public function update_option( $opt_name, $opt_values ) {
        if( array_key_exists( $opt_name, $this->_options ) ) {
            
            $new_input = $this->_options[$opt_name];
            
            $option_exits_value = get_option( $opt_name );
            
            if ( $option_exits_value === false ) {
                if ( is_array( $option_exits_value ) ) {
                    $new_input = array_merge( $new_input, $option_exits_value );
                } else {
                    $new_input = $option_exits_value;
                }
            }
            
            if ( ! is_array( $opt_values ) ) {
                $new_input = $opt_values;
            } else {
                foreach ( $opt_values as $v_name => $v_value ) {
                    if ( is_array( $new_input ) && array_key_exists( $v_name, $new_input ) ) {
                        $new_input[$v_name] = $v_value;
                    }
                }
            }
            
            if ( ! update_option( $opt_name, $new_input ) ) {
                add_option( $opt_name, $new_input );
            }
            
            $this->load_options();
            
            return true;
        }
        
        return false;
    }

    public function get_all_options() {
        return $this->_options;
    }

    public function get_options( $opt_key ) {
        if ( array_key_exists( $opt_key, $this->_options ) ) {
            return $this->_options[$opt_key];
        }
        
        return '';
    }
    
    public function get_option( $opt_key, $opt_name ) {
        
        if ( array_key_exists( $opt_key, $this->_options ) && is_array( $this->_options[$opt_key] ) && array_key_exists( $opt_name, $this->_options[$opt_key] ) ) {
            return $this->_options[$opt_key][$opt_name];
        }
        
        return '';
    }
    
    public function remove_all_options() {
        foreach ( $this->_options as $o_key => $o_value ) {
            delete_option( $o_key );
        }
    }
}