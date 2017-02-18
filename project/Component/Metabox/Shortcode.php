<?php
namespace Wizzaro\Gallery\v1\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Gallery\v1\Config\PluginConfig;

class Shortcode extends AbstractMetabox {
    private $_config;

    public function set_config( array $config ) {
        $this->_config = array_merge( $this->_get_metabox_config(), $config );
    }

    protected function _get_metabox_config() {
        if ( ! $this->_config ) {
            $this->_config = array(
                'id' => 'wizzaro-gallery-shortcode',
                'title' => __( 'Gallery Shortcode', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
                'screen' => array(),
                'context' => 'side',
                'priority' => 'core'
            );
        }

        return $this->_config;
    }

    public function render( $post ) {
        View::get_instance()->render_view_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'metabox', array(
            'shortcode' => PluginConfig::get_instance()->get_post_type_shordcode( $post->post_type ),
            'post_id' => $post->ID
        ) );
    }
}
