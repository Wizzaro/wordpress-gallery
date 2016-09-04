<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

class View extends AbstractSingleton {
    
    public function get_instance_view_path( $instance ) {
        $path = preg_replace( '/(\\\|\/)/i', DIRECTORY_SEPARATOR, get_class( $instance ) );
        $path = mb_strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $path) );
        
        if ( mb_strlen( $path ) > 0 ) {
            $path = trim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;    
        }
        
        return $path;
    }
    
    public function render( $view_path, $view_data = array() ) {
        include( $view_path );
    }
    
    public function render_view_for_instance( $templates_path, $instance, $file_name, $view_data ) {
        $this->render( $templates_path . $this->get_instance_view_path( $instance ) . $file_name . '.php', $view_data );
    }
    
    public function get_content( $view_path, $view_data = array() ) {
        ob_start();
        require $view_path;
        $view = ob_get_clean();
        return $view;
    }
    
    public function get_content_for_instance( $templates_path, $instance, $file_name, $view_data ) {
        return $this->get_content( $templates_path . DIRECTORY_SEPARATOR . $this->get_instance_view_path( $instance ) . $file_name . '.php', $view_data );
    }

    public function view_exist( $view_path ) {
        return file_exists( $view_path );
    }
    
    public function view_exist_for_instance( $templates_path, $instance, $file_name ) {
        return file_exists( $templates_path . DIRECTORY_SEPARATOR . $this->get_instance_view_path( $instance ) . $file_name . '.php' );
    }
}