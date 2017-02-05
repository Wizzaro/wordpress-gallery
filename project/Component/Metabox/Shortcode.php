<?php
namespace Wizzaro\Gallery\v1\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Gallery\v1\Config\PluginConfig;

class Shortcode extends AbstractMetabox {
    private $_shordcodes = array();

    protected function __construct() {
        add_action( 'wizzaro_gallery_after_register_post_types', array( $this, 'action_construct' ), 10, 1 );
    }

    public function action_construct( $post_types_settings ) {
        $shordcodes_galleries_post_types = array_filter( $post_types_settings, function( $post_type_setings ) {
            return array_key_exists( 'shordcode', $post_type_setings ) &&
            (
                ( is_bool( $post_type_setings['shordcode'] ) && $post_type_setings['shordcode'] === true ) ||
                is_string( $post_type_setings['shordcode'] )
            );
        } );

        if ( count( $shordcodes_galleries_post_types ) > 0 ) {
            $default_shordcode_name = PluginConfig::get_instance()->get( 'shortcode', 'default_name' );

            foreach( $shordcodes_galleries_post_types as $post_type => $post_type_settings ) {
                $this->_shordcodes[$post_type] = is_string( $post_type_settings['shordcode'] ) ? $post_type_settings['shordcode'] : $default_shordcode_name;
            }

            parent::__construct();
        }
    }

    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }

    protected function _get_metabox_config() {
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-gallery-shortcode',
                'title' => __( 'Shortcode', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array_keys( $this->_shordcodes ),
                'context' => 'side',
                'priority' => 'core'
            );
        }

        return $this->_config;
    }

    public function render( $post ) {
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'shortcode' => $this->_shordcodes[$post->post_type],
            'post_id' => $post->ID
        ) );
    }
}
