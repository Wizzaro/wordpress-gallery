<?php
namespace Wizzaro\WPFramework\v1\Setting;

use Wizzaro\WPFramework\v1\Helper\View;

abstract class AbstractSettingsPage {
    
    protected $_page_config;
    
    private $_admin_tabs = array();
    private $_default_tab = '';
    private $_current_tab = '';
    
    private $_settings_fields = array();
    
    public function __construct( array $page_config = array() ) {
        if ( is_admin() ) {
            $this->_page_config = (object) array_merge( $this->get_page_config(), $page_config );
            
            add_action('admin_menu', array($this, 'add_options_page'));
            
            $this->register_settings_fields( array(
                array( 'type' => 'text',  'callback' => array( $this, 'render_text_input' ) ),
                array( 'type' => 'password',  'callback' => array( $this, 'render_text_input' ) ),
                array( 'type' => 'hidden',  'callback' => array( $this, 'render_text_input' ) ),
                array( 'type' => 'email',  'callback' => array( $this, 'render_text_input' ) ),
                array( 'type' => 'date',  'callback' => array( $this, 'render_text_input' ) ),
                array( 'type' => 'month',  'callback' => array( $this, 'render_text_input' ) ),
                array( 'type' => 'number',  'callback' => array( $this, 'render_number_input' ) ),
                array( 'type' => 'select',  'callback' => array( $this, 'render_select_field' ) ),
                array( 'type' => 'wp_page',  'callback' => array( $this, 'render_wp_page_field' ) ),
            ));
        }
    }
    
    protected function get_page_config() {
        return array(
            'page_title' => '',
            'menu_title' => '',
            'capability' => '',
            'menu_slug' => ''
        );
    }
    
    private function get_view_templates_path() {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'Views' . DIRECTORY_SEPARATOR . 'settings-page' . DIRECTORY_SEPARATOR;
    }
    
    public function add_tab( $tab_name, $tab_slug, $component, $render_function, $position = 1 ) {
        $this->_admin_tabs[$tab_slug] = array(
            'position' => $position,
            'tab_name' => $tab_name,
            'component' => $component,
            'render_function' => $render_function,
        );
    }
    
    public function set_default_tab($tab_slug) {
        $this->_default_tab = $tab_slug;
    }
    
    public function get_default_tab() {
        if ( strlen( $this->_default_tab ) <= 0 ) {
            $tabs = $this->get_tabs_sorted_by_position();
            
            if ( count( $tabs ) > 0 ) {
                reset( $tabs );
                return key( $tabs );
            }
            
            return '';
        }
        
        return $this->_default_tab;
    }
    
    public function get_tabs_sorted_by_position() {
        $return = $this->_admin_tabs;
        $sorter = array();
        
        foreach ($return as $key => $row) {
            $sorter[$key] = $row['position'];
        }
        
        array_multisort($sorter, SORT_DESC, $return);
        
        return array_reverse($return);
    }
    
    public function add_options_page() {
        add_options_page(
            $this->_page_config->page_title, 
            $this->_page_config->menu_title, 
            $this->_page_config->capability, 
            $this->_page_config->menu_slug, 
            array( $this, 'render_setting_page' )
        );
    }
    
    public function render_setting_page() {
        $this->_current_tab = $this->get_default_tab();
        
        if( isset( $_GET['tab'] ) ) {
            $this->_current_tab = $_GET['tab'];
        }

        View::get_instance()->render( $this->get_view_templates_path() . 'admin-setting-page' . '.php', array(
            'title' => $this->_page_config->page_title,
            'slug' => $this->_page_config->menu_slug,
            'current_tab' => $this->_current_tab,
            'current_tab_render_fn' => $this->_admin_tabs[$this->_current_tab]['render_function'],
            'current_tab_obj' => $this->_admin_tabs[$this->_current_tab]['component'],
            'tabs' => $this->get_tabs_sorted_by_position()
        ) );
    }
    
    public function register_settings_fields( $fields ) {
        foreach ( $fields as $field ) {
            $this->register_settings_field( $field['type'], $field['callback'] );
        }
    }
    
    public function register_settings_field( $field_type, $field_callback ) {
        $this->_settings_fields[$field_type] = $field_callback;
    }
    
    private function get_settings_field_callback( $field_type ) {
        return $this->_settings_fields[$field_type];
    }
    
    private function _parse_section_name( $setting_name, $section_name ) {
        return $setting_name . '_' . $section_name;
    }
    
    public function register_settings( &$component, $config ) {
        
        foreach ( $config['settings'] as $setting_name => $st_config ) {
            
            $page = isset( $config['multiple'] ) && $config['multiple'] === true ? $setting_name : $config['page']; 
            
            register_setting(
                $page, // Option group
                $setting_name, // Option name
                $st_config['callback'] // Sanitize Callback
            );
            
            foreach ( $st_config['sections'] as $section_name => $sc_config ) {
                $section_name = $this->_parse_section_name( $setting_name, $section_name );

                add_settings_section(
                    $section_name, // ID
                    $sc_config['title'], // Title
                    $sc_config['callback'], //Callback
                    $section_name // Page
                ); 
                
                foreach ( $sc_config['fields'] as $field_name => $f_config ) {
                    $args = array(
                        'component' => &$component,
                        'settings_name' => $setting_name,
                        'field_name' => $field_name,
                        'field_type' => $f_config['type']
                    );
                    
                    $callback = $this->get_settings_field_callback($f_config['type']);
                    
                    if(is_array($f_config['args'])) {
                        $args = array_merge($f_config['args'], $args);
                    }
                    
                    add_settings_field(
                        $field_name, 
                        $f_config['title'], 
                        $callback,
                        $section_name, 
                        $section_name,
                        $args
                    ); 
                }
            }
        }
    }

    public function render_settings_form( $config ) {
        if ( isset( $config['multiple'] ) && $config['multiple'] === true ) {
            foreach($config['settings'] as $setting_name => $st_config) {
                ?>
                <form method="post" action="options.php">
                    <?php
                    settings_fields( $setting_name );   
                    echo isset( $st_config['title'] ) ? '<h1>' . $st_config['title'] . '</h1>': '' ;
                    foreach ( $st_config['sections'] as $section_name => $sc_config ) {
                        $section_name = $this->_parse_section_name( $setting_name, $section_name );
                        do_settings_sections( $section_name );
                    }
                    submit_button(); 
                    ?>
                </form>
                <?php    
            }
        } else {
            ?>
            <form method="post" action="options.php">
                <?php
                settings_fields( $config['page'] );   
                foreach ( $config['settings'] as $setting_name => $st_config ) {
                    echo isset( $st_config['title'] ) ? '<h1>' . $st_config['title'] . '</h1>': '' ;
                    foreach ( $st_config['sections'] as $section_name => $sc_config ) {
                        $section_name = $this->_parse_section_name( $setting_name, $section_name );
                        do_settings_sections( $section_name );
                    }
                }
                
                submit_button(); 
                ?>
            </form>
            <?php
        }
    }

    public function render_section_description( $args, $descriptions ) {
        if ( array_key_exists( $args['id'], $descriptions ) && mb_strlen( $descriptions[$args['id']] ) ) {
            echo '<p>' . $descriptions[$args['id']] . '</p>';
        }
    }
    
    public function render_field_description( $args ) {
        if ( isset( $args['description'] ) && mb_strlen( $args['description'] ) > 0 ) {
            echo '<p class="description">' . $args['description'] . '</p>';
        }
    }
    
    protected function _get_field_value( &$args ) {
        if ( array_key_exists( 'option_name', $args ) ) {
            return $args['component']->get_option( $args['settings_name'], $args['option_name'] );
        }
        
        return $args['component']->get_option( $args['settings_name'], $args['field_name'] );
    }
    
    public function render_text_input( $args ) {
        $value = $this->_get_field_value( $args );
        
        printf(
            '<input type="' . esc_attr( $args['field_type'] ) . '" id="' . esc_attr( $args['field_name'] ) . '" class="large-text" name="' . esc_attr( $args['settings_name'] ) . '[' . esc_attr( $args['field_name'] ) . ']" value="%s" />',
            esc_attr( $value )
        );
        
        $this->render_field_description( $args );
    }
    
    public function render_number_input( $args ) {
        $value = $this->_get_field_value( $args );
        
        $min = is_numeric( $args['min'] ) ? ' min="' . $args['min'] . '"' : '';
        $max = is_numeric( $args['max'] ) ? ' max="' . $args['max'] . '"' : '';
        
        printf(
            '<input type="number" id="' . esc_attr( $args['field_name'] ) . '" class="small-text" name="' . esc_attr( $args['settings_name'] ) . '[' . esc_attr( $args['field_name'] ) . ']" value="%s" ' . $min . $max .' />',
            esc_attr($value)
        );
        
        $this->render_field_description( $args );
    }

    public function render_select_field( $args ) {
        $value = $this->_get_field_value( $args );
        $args['value'] = $value;

        View::get_instance()->render( $this->get_view_templates_path() . 'fields' . DIRECTORY_SEPARATOR . 'admin-field-select.php', $args, false, $this->get_view_templates_path() );
        
        $this->render_field_description( $args );
    }
    
    public function render_wp_page_field( $args ) {
        $value = $this->_get_field_value( $args );
        
        $args['value'] = $value;
        $args['select_options'] = get_pages();

        View::get_instance()->render( $this->get_view_templates_path() . 'fields' . DIRECTORY_SEPARATOR . 'admin-field-wp-page.php', $args, false, $this->get_view_templates_path() );
        
        $this->render_field_description( $args );
    }
}