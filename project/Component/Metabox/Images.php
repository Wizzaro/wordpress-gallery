<?php
namespace Wizzaro\Gallery\v1\Component\Metabox;

use Wizzaro\WPFramework\v1\Component\Metabox\AbstractMetabox;
use Wizzaro\WPFramework\v1\Helper\Encrypt;
use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Validator;
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Gallery\v1\Config\PluginConfig;
use Wizzaro\Gallery\v1\Service\Images as ImagesService; 

class Images extends AbstractMetabox {
    
    protected function _get_metabox_config() {
        return array(
            'id' => 'gallery-images',
            'title' => __( 'Images', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
            'screen' => PluginConfig::get_instance()->get_galeries_post_types(),
            'context' => 'advanced',
            'priority' => 'core'
        );
    }
    
    public function render( $post ) {
        
        $plugin_config = PluginConfig::get_instance();
        
        $languages_domain = $plugin_config->get( 'languages', 'domain' );
        
        wp_register_script( 'wizzaro-gallery-admin-script', $plugin_config->get_js_admin_url() . 'wizzaro-gallery.js', array( 'jquery', 'jquery-ui-sortable', 'plupload' ), '1.0', true );
        
        $post_id_encrypt = Encrypt::get_instance()->encryption( $post->ID );
        
        //set uploader view and js data
        if ( _device_can_upload() && ! ( is_multisite() && ! is_upload_space_available() ) && current_user_can( 'upload_files' ) ) {
            
            $max_upload_size = wp_max_upload_size();
        
            if ( ! $max_upload_size ) {
                $max_upload_size = 0;
            }
            
            $post_params = array(
                'post_id' => $post_id_encrypt,
                '_wpnonce' => wp_create_nonce( 'wizzaro_gallery_images_add_nonce' ),
                'action' => $plugin_config->get( 'ajax_actions', 'image_upload' )
            );
            
            $plupload_init = array(
                'runtimes'            => 'html5,flash,silverlight,html4',
                'container'           => 'wizzaro-gal-plupload-upload-ui',
                'browse_button'       => 'wizzaro-gal-plupload-browse-button',
                'drop_element'        => 'wizzaro-gal-drag-drop-area',
                'file_data_name'      => 'wizzaro-async-upload',
                'url'                 => admin_url('admin-ajax.php'),
                'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
                'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
                'filters' => array(
                    'mime_types' => array(
                        array( 
                            'title' => "Image files", 
                            'extensions' => 'jpg,jpeg,gif,png'
                        )
                    ),
                    'max_file_size'   => $max_upload_size . 'b',
                ),
                'multipart_params'    => $post_params,
            );
            
            if ( wp_is_mobile() && strpos( $_SERVER['HTTP_USER_AGENT'], 'OS 7_' ) !== false &&
                strpos( $_SERVER['HTTP_USER_AGENT'], 'like Mac OS X' ) !== false ) {
            
                $plupload_init['multi_selection'] = false;
            }
                
            wp_localize_script( 'wizzaro-gallery-admin-script', 'wpWizzaroGalleryUploaderInit', $plupload_init );
            
            View::get_instance()->render_view_for_instance( $plugin_config->get_view_templates_path(), $this, 'uploader', array (
                'languages_domain' => $languages_domain,
                'max_upload_size' => $max_upload_size
            ) );
            
        } elseif ( ! current_user_can( 'upload_files' ) ) {
            View::get_instance()->render_view_for_instance( $plugin_config->get_view_templates_path(), $this, 'uploader-error', array (
                'message' => __( 'You do not have permission to upload files.', $languages_domain )
            ) );
        } elseif ( ! _device_can_upload() ) {
            View::get_instance()->render_view_for_instance( $plugin_config->get_view_templates_path(), $this, 'uploader-error', array (
                'message' => sprintf( __('The web browser on your device cannot be used to upload files. You may be able to use the <a href="%s">native app for your device</a> instead.', $languages_domain ), 'https://apps.wordpress.org/' )
            ) );
        }

        $buttons_titles = array(
            'set_thumbnail' => __( 'Set as thumbnail', $languages_domain ),
            //'preview' => __( 'Preview image', $languages_domain ),
            //'edit' => __( 'Edit image', $languages_domain ),
            'delete' => __( 'Delete image', $languages_domain )
        );
            
        $images_init = array(
            'post_id' => $post_id_encrypt,
            'support_thumbnail' => post_type_supports( $post->post_type, 'thumbnail' ) ? '1' : '0',
            'thumbnail' => array(
                'action' => $plugin_config->get( 'ajax_actions', 'set_thumbnail' ),
                'nonce' => wp_create_nonce( 'wizzaro_gallery_images_set_thumbnail_nonce' ),
                'l10n' => array(
                    'error' => __( 'Error during set image as thumbnail.', $languages_domain )
                )
            ),
            'delete' => array(
                'action' => $plugin_config->get( 'ajax_actions', 'image_delete' ),
                'nonce' => wp_create_nonce( 'wizzaro_gallery_images_delete_nonce' ),
                'l10n' => array(
                    'error' => __( 'Error during deleting image.', $languages_domain )
                )
            ),
            'l10n' => array(
                'no_image' => __( 'In this gallery has no photos. Upload something.', $languages_domain ),
                'buttons_titles' => $buttons_titles
            )
        );
        
        wp_localize_script( 'wizzaro-gallery-admin-script', 'wpWizzaroGalleryImagesInit', $images_init );
        
        wp_enqueue_script( 'wizzaro-gallery-admin-script' );
        
        $images_service = ImagesService::get_instance();
        
        View::get_instance()->render_view_for_instance( $plugin_config->get_view_templates_path(), $this, 'images', array (
            'languages_domain' => $languages_domain,
            'encrypt_instance' => Encrypt::get_instance(),
            'urls' => $images_service->get_gallery_url( $images_service->get_gallery_dir( $post, false ) ),
            'support_thumbnail' => post_type_supports( $post->post_type, 'thumbnail' ),
            'thumbnail_id' => get_post_meta( $post->ID, '_post_gallery_thumbnail_id', true ),
            'buttons_titles' => $buttons_titles,
            'images' => $images_service->get_post_images( $post )
        ) );
    }
    
    public function save( $post_id, $post ) {
        
        if ( ! is_admin() ) {
            return;
        }
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post->ID;   
        }
            
        //save image
        if( $_FILES && is_array( $_FILES['wizzaro-gal-upload-image'] ) && ! is_array( $_FILES['wizzaro-gal-upload-image']['name'] ) ) {
            ImagesService::get_instance()->upload_post_image( $_FILES['wizzaro-gal-upload-image'], $post );
        }
            
        //set all image visible on frontend
        ImagesService::get_instance()->set_all_post_image_visible( $post );
        
        //resort images
        if ( $_POST && is_array( $_POST['wizzaro-gal-images'] ) ) {
            $imgs_ids = array_map( array( Encrypt::get_instance(), 'decryption' ), $_POST['wizzaro-gal-images'] );
            $imgs_ids = array_map( array( Filter::get_instance(), 'filter_int' ), $imgs_ids );
            
            ImagesService::get_instance()->resort_post_images( $imgs_ids, $post);
        }
    }
}