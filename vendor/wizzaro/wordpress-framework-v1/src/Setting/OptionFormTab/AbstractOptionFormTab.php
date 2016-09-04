<?php
namespace Wizzaro\WPFramework\v1\Setting\OptionFormTab;

abstract class AbstractOptionFormTab {
    
    protected $_setting_page_instance;
    
    protected $_option_instance;
    
    public function __construct( &$setting_page_instance, &$option_instance ) {
        
        $tab_conf = $this->_get_tab_config();
        
        $this->_setting_page_instance = $setting_page_instance;
        $this->_setting_page_instance->add_tab( $tab_conf['name'], $tab_conf['slug'], $this, 'render_option_tab' );
        
        $this->_option_instance = $option_instance;
        
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }
    
    protected function _get_tab_config() {
        return array(
            'name' => '',
            'slug' => ''
        );
    }
    
    protected function _get_settings_config() {
        return array();
    }
    
    public function get_option_instacne() {
        return $this->_option_instance;
    }
    
    public function render_option_tab() {
        $this->_setting_page_instance->render_settings_form( $this->_get_settings_config() );
    }
    
    public function register_settings() {
        $this->_setting_page_instance->register_settings( $this->_option_instance, $this->_get_settings_config() );
    }
    
    public function validate_options( $input ) {
        return $input;
    }
}