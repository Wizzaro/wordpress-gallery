<?php
namespace Wizzaro\Gallery\v1\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Validator;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Gallery\v1\Config\PluginConfig;
use Wizzaro\Gallery\v1\PostMeta\Settings as SettingsPostMeta;

class Settings extends AbstractMetabox {

    protected function _get_metabox_config() {
        return array(
            'id' => 'gallery-settings',
            'title' => __( 'Gallery Settings', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
            'screen' => PluginConfig::get_instance()->get_galeries_post_types(),
            'context' => 'side',
            'priority' => 'core'
        );
    }

    public function render( $post ) {
        $plugin_config = PluginConfig::get_instance();
        $settings = new SettingsPostMeta( $post->ID );

        View::get_instance()->render_view_for_instance( $plugin_config->get_view_templates_path(), $this, 'metabox', array(
            'languages_domain' => $plugin_config->get( 'languages', 'domain' ),
            'settings' => $settings->get_values()
        ) );
    }

    public function save( $post_id, $post ) {
        if( ! is_admin() || ! isset ( $_POST['wizzaro_gallery_save_post_settings'] ) || ! wp_verify_nonce( $_POST['wizzaro_gallery_save_post_settings'], 'wizzaro_gallery_save_post_settings_nonce' ) ) {
            return;
        }

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post->ID;
        }



        if ( isset( $_POST['wizzaro_gallery_settings'] ) && is_array( $_POST['wizzaro_gallery_settings'] ) ) {
            $settings = new SettingsPostMeta( $post->ID );
            $new_settings = $_POST['wizzaro_gallery_settings'];

            $filter_instance = Filter::get_instance();
            $validator_instance = Validator::get_instance();

            if( array_key_exists( 'columns_count', $new_settings ) ) {
                $columns_count = $filter_instance->filter_int( $new_settings['columns_count'] );

                if ( $validator_instance->validate_min_max_int( $columns_count, 1, 9 ) ) {
                    $settings->set( 'columns_count', $columns_count );
                }
            }

            if( array_key_exists( 'display_empty_info', $new_settings ) ) {
                $display_empty_info = $filter_instance->filter_int( $new_settings['display_empty_info'] );

                if ( $validator_instance->validate_min_max_int( $display_empty_info, 0, 1 ) ) {
                    $settings->set( 'display_empty_info', $display_empty_info );
                }
            }

            $settings->save();
        }



        if ( isset( $_POST['post_color'] ) ) {
            $color_no = Filter::get_instance()->filter_int( $_POST['post_color'] );

            if ( Validator::get_instance()->validate_min_max_int( $color_no, 1, ThemeConfig::get_instance()->get( 'colors', 'count' ) ) ) {
                if ( ! update_post_meta( $post->ID, '_post_color',  $color_no) ) {
                    add_post_meta( $post->ID, '_post_color',  $color_no, true);
                }
            } else {
                delete_post_meta( $post->ID, '_post_color' );
            }
        }
    }
}
