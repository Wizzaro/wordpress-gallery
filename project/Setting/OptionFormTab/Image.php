<?php
namespace Wizzaro\Gallery\v1\Setting\OptionFormTab;

use Wizzaro\WPFramework\v1\Setting\OptionFormTab\AbstractOptionFormTab;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Validator;

use Wizzaro\Gallery\v1\Config\PluginConfig;

class Image extends AbstractOptionFormTab {
    
    protected function _get_tab_config() {
        return array(
            'name' => __( 'Image', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
            'slug' => 'image'
        );
    }
    
    protected function _get_settings_config() {
        $language_domain = PluginConfig::get_instance()->get( 'languages', 'domain' );
        
        return array(
            'page' => 'wizzaro_gallery_img_settings',
            'settings' => array(
                'wizzaro_gallery_image' => array(
                    'callback' => array( $this, 'validate_options' ),
                    'sections' => array(
                        'sizes' => array(
                            'title' => __( 'Image size', $language_domain ),
                            'callback' => '',
                            'fields' => array(
                                'width' => array(
                                    'title' => __( 'Width', $language_domain ) . ':',
                                    'type' => 'number'
                                ),
                                'height' => array(
                                    'title' => __( 'Height', $language_domain ) . ':',
                                    'type' => 'number'
                                )
                            )
                        ),
                        'thumb_sizes' => array(
                            'title' => __( 'Image thumbnail size', $language_domain ),
                            'callback' => '',
                            'fields' => array(
                                'thumb_width' => array(
                                    'title' => __( 'Width', $language_domain ) . ':',
                                    'type' => 'number'
                                ),
                                'thumb_height' => array(
                                    'title' => __( 'Height', $language_domain ) . ':',
                                    'type' => 'number'
                                )
                            )
                        ),
                        'alt_text' => array(
                            'title' => __( 'Default alt text', $language_domain ),
                            'callback' => '',
                            'fields' => array(
                                'default_alt' => array(
                                    'title' => __( 'Text', $language_domain ) . ':',
                                    'type' => 'text',
                                    'args' => array(
                                        'description' => __( 'In this text you can use shortcode "{gallery_name}", which include gallery title and "{image_name}", which include image name"', $language_domain )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
    }

    public function validate_options( $input ) {
        $new_input = $this->get_option_instacne()->get_options( 'wizzaro_gallery_image' );
        
        $filter_instance = Filter::get_instance();
        $validator_instance = Validator::get_instance();
        
        foreach( $input as $f_name => $f_value ) {
            switch( $f_name ) {
                case 'width':
                case 'height':
                case 'thumb_width':
                case 'thumb_height':
                    $f_value = $filter_instance->filter_int( $f_value );

                    if ( $validator_instance->validate_min_max_int( $f_value, 1 ) ) {
                        $new_input[$f_name] = $f_value;
                    }
                    break;
                case 'default_alt':
                    $new_input[$f_name] = $filter_instance->filter_text( $f_value );
                    break;
            }
        }
        
        return $new_input;
    }
}