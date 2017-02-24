<?php
namespace Wizzaro\Gallery\v1\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;

use Wizzaro\Gallery\v1\Component\Metabox\Shortcode as ShortcodeMetabox;

class Shortcode extends AbstractPluginController {

    public function init_admin() {
        add_action( 'wizzaro_gallery_after_register_post_types', array( $this, 'action_admin_init_shordcodes' ), 10, 1 );
    }

    public function action_admin_init_shordcodes( $post_types_settings ) {
        $shordcodes_galleries_post_types = array_filter( $post_types_settings, function( $post_type_setings ) {
            return array_key_exists( 'shordcode', $post_type_setings ) &&
            (
                ( is_bool( $post_type_setings['shordcode'] ) && $post_type_setings['shordcode'] === true ) ||
                is_string( $post_type_setings['shordcode'] )
            );
        } );

        if ( count( $shordcodes_galleries_post_types ) > 0 ) {
            $default_shordcode_name = $this->_config->get( 'shortcode', 'default_name' );

            foreach( $shordcodes_galleries_post_types as $post_type => $post_type_settings ) {
                $this->_config->set_post_type_shordcode( $post_type, ( is_string( $post_type_settings['shordcode'] ) ? $post_type_settings['shordcode'] : $default_shordcode_name ) );
                add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'filter_reservation_data_columns' ) );
                add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'action_render_columns' ), 2 );
            }
        }

        if ( count( $shordcodes_galleries_post_types ) > 0 ) {
            ShortcodeMetabox::get_instance()->set_config( array( 'screen' => array_keys( $shordcodes_galleries_post_types ) ) );
        }
    }

    public function filter_reservation_data_columns( $existing_columns ) {
        $date_column = $existing_columns['date'];
        unset( $existing_columns['date'] );


        return array_merge( $existing_columns, array(
            'shortcode' => __( 'Shortcode', $this->_config->get( 'languages', 'domain' ) ),
            'date' => $date_column
        ));
    }

    public function action_render_columns( $column ) {
        global $post;

        if ( ! strcasecmp( $column, 'shortcode' ) && ! wp_is_post_revision( $post->ID ) ) {
            ?>
            <code>[<?php echo $this->_config->get_post_type_shordcode( $post->post_type ) ?> id="<?php echo $post->ID ?>"]</code>
            <?php
        }
    }
}
