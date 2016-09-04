<?php
namespace Wizzaro\WPFramework\v1\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractController;
use Wizzaro\WPFramework\v1\Helper\View;

abstract class AbstractPluginController extends AbstractController {
    
    private $_themes_view_templates_path;
    /*
    private $_main_file_patch;
    
    public function __construct( &$bootstrap ) {
        parent::__construct( $bootstrap );
        
        if ( is_admin() ) {
            $main_file_patch = $bootstrap->get_main_file_path();
            
            register_activation_hook( $main_file_patch , array( $this, 'plugin_activation' ) );
            register_deactivation_hook( $main_file_patch , array( $this, 'plugin_deactivation' ) );
            register_uninstall_hook( $main_file_patch , array( $this, 'plugin_delete' ) );
        }
    }
    
    public function plugin_activation() {
    }
    
    public function plugin_deactivation() {
    }
    
    public function plugin_delete() {
    }*/
    
    public function get_themes_view_templates_path() {
        //create view folder patch
        if ( ! $this->_themes_view_templates_path ) {
            $this->_themes_view_templates_path = get_template_directory() . DIRECTORY_SEPARATOR . View::get_instance()->get_instance_view_path( $this );
        }
        
        return $this->_themes_view_templates_path;
    }
    
    public function render_themes_view( $view_file, $view_data = array() ) {
        View::get_instance()->render( $this->get_themes_view_templates_path() . $view_file . '.php', $view_data );
    }
    
    public function get_themes_view( $view_file, $view_data = array() ) {
        return View::get_instance()->get_content( $this->get_themes_view_templates_path() . $view_file . '.php', $view_data );
    }
    
    public function is_themes_view_exist( $view_file ) {
        return View::get_instance()->view_exist( $this->get_themes_view_templates_path() . $view_file . '.php' );
    }
}