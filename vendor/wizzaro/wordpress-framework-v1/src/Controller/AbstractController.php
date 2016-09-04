<?php
namespace Wizzaro\WPFramework\v1\Controller;

use Wizzaro\WPFramework\v1\Helper\View;

abstract class AbstractController {
    
    protected $_config;
    
    private $_view_templates_path;
    
    public function __construct( &$config ) {
        $this->_config = $config;
    }
    
    public function init() {
        
    }
    
    public function init_front() {
        
    }
    
    public function init_admin() {
        
    }
    
    public function get_view_templates_path() {
        //create view folder patch
        if ( ! $this->_view_templates_path ) {
            $this->_view_templates_path = $this->_config->get_view_templates_path() . DIRECTORY_SEPARATOR . View::get_instance()->get_instance_view_path( $this );
        }
        
        return $this->_view_templates_path;
    }
    
    public function render_view( $view_file, $view_data = array() ) {
        View::get_instance()->render( $this->get_view_templates_path() . $view_file . '.php', $view_data );
    }
    
    public function get_view( $view_file, $view_data = array() ) {
        return View::get_instance()->get_content( $this->get_view_templates_path() . $view_file . '.php', $view_data );
    }
}