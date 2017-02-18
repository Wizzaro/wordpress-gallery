<?php
namespace Wizzaro\Gallery\v1\Controller;

use Wizzaro\WPFramework\v1\Controller\AbstractPluginController;

use Wizzaro\WPFramework\v1\Helper\Filter;
use Wizzaro\WPFramework\v1\Helper\Encrypt;

use Wizzaro\Gallery\v1\Config\PluginConfig;
use Wizzaro\Gallery\v1\Setting\SettingsPage;
use Wizzaro\Gallery\v1\Setting\OptionFormTab\Image as ImageOptionFormTab;
use Wizzaro\Gallery\v1\Option\Image as ImageOption;
use Wizzaro\Gallery\v1\Model\Table\Images as ImagesDBTable;
use Wizzaro\Gallery\v1\Component\Metabox\Images as ImagesMetabox;
use Wizzaro\Gallery\v1\Component\Metabox\Settings as SettingsMetabox;
use Wizzaro\Gallery\v1\Service\Images as ImagesService;
use Wizzaro\Gallery\v1\PostMeta\Settings as SettingsPostMeta;

use \Exception;

class Images extends AbstractPluginController {

    public function init_front() {
        add_action( 'wizzaro_gallery_after_register_post_types', array( $this, 'action_init_shordcode' ), 10, 1 );
        add_filter( 'the_content', array( $this, 'filter_add_images_to_content' ) );
    }

    public function init_admin() {
        register_activation_hook( $this->_config->get_main_file_path() , array( $this, 'action_plugin_activation' ) );

        new ImageOptionFormTab( SettingsPage::get_instance(), ImageOption::get_instance() );
        ImagesMetabox::create();
        SettingsMetabox::create();

        add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue_gallery_style' ) );
        add_action( 'before_delete_post', array( $this, 'action_delete_gallery_folder' ) );

        $plugin_config = PluginConfig::get_instance();

        add_action( 'wp_ajax_' . $plugin_config->get( 'ajax_actions', 'image_upload' ), array( $this, 'ajax_action_image_upload' ) );
        add_action( 'wp_ajax_' . $plugin_config->get( 'ajax_actions', 'set_thumbnail' ), array( $this, 'ajax_action_image_set_thumbnail' ) );
        add_action( 'wp_ajax_' . $plugin_config->get( 'ajax_actions', 'image_edit' ), array( $this, 'ajax_action_image_edit' ) );
        add_action( 'wp_ajax_' . $plugin_config->get( 'ajax_actions', 'image_delete' ), array( $this, 'ajax_action_image_delete' ) );

        add_action( 'post_edit_form_tag' , array( $this, 'action_post_edit_form_tag' ) );
        add_action( 'save_post', array( $this, 'reset_images_view_cache' ), 10, 2 );
    }

    public function action_init_shordcode( $post_types_settings ) {
        $default_shordcode_name = PluginConfig::get_instance()->get( 'shortcode', 'default_name' );

        $shordcodes_galleries_post_types = array_filter( $post_types_settings, function( $post_type_setings ) {
            return array_key_exists( 'shordcode', $post_type_setings ) && is_string( $post_type_setings['shordcode'] ) && $post_type_setings['shordcode'] !== $default_shordcode_name;
        } );

        add_shortcode( $default_shordcode_name, array( $this, 'render_shordcode') );
        foreach( $shordcodes_galleries_post_types as $post_type_setings ) {
            add_shortcode( $post_type_setings['shordcode'], array( $this, 'render_shordcode') );
        }
    }

    public function render_shordcode( $attrs ) {
        $view = '';

        if ( isset( $attrs['id'] ) ) {
            $post = get_post( $attrs['id'] );

            if ( $post && in_array( $post->post_type, PluginConfig::get_instance()->get_galeries_post_types() ) ) {
                $view = $this->get_gallery_view( $post );
            }
        }

        return $view;
    }

    public function filter_add_images_to_content( $content ) {
        if ( is_singular() ) {
            $post = get_post();

            if ( $post && in_array( $post->post_type, PluginConfig::get_instance()->get_galeries_post_types() ) ) {
                $content .= $this->get_gallery_view( $post );
            }
        }

        return $content;
    }

    private function get_gallery_view( $post ) {
        $view = wp_cache_get( 'wizzaro_gallery_images', $post->post_type . '-' . $post->ID );

        if ( ! $view ) {
            $service = ImagesService::get_instance();
            $settings = new SettingsPostMeta( $post->ID );

            $view_data = array (
                'post' => $post,
                'languages_domain' => $this->_config->get( 'languages', 'domain' ),
                'urls' => $service->get_gallery_url( $service->get_gallery_dir( $post, false ) ),
                'images' => $service->get_post_images( $post, true ),
                'settings' => $settings->get_values()
            );

            if ( $this->is_themes_view_exist( 'post-gallery-' . $post->post_type ) ) {
                $view = $this->get_themes_view( 'post-gallery-' . $post->post_type, $view_data );
            } elseif ( $this->is_themes_view_exist( 'post-gallery' ) ) {
                $view = $this->get_themes_view( 'post-gallery', $view_data );
            } else {
                $view = $this->get_view( 'post-gallery', $view_data );
            }

            wp_cache_set( 'wizzaro_gallery_images', $view , $post->post_type . '-' . $post->ID );
        }

        return $view;
    }

    public function reset_images_view_cache( $post_id, $post ) {
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( $post && in_array( $post->post_type, PluginConfig::get_instance()->get_galeries_post_types() ) ) {
            $service = ImagesService::get_instance();

            $view_data = array (
                'languages_domain' => $this->_config->get( 'languages', 'domain' ),
                'urls' => $service->get_gallery_url( $service->get_gallery_dir( $post, false ) ),
                'images' => $service->get_post_images( $post, true )
            );

            if ( $this->is_themes_view_exist( 'post-gallery-' . $post->post_type ) ) {
                $view = $this->get_themes_view( 'post-gallery-' . $post->post_type, $view_data );
            } elseif ( $this->is_themes_view_exist( 'post-gallery' ) ) {
                $view = $this->get_themes_view( 'post-gallery', $view_data );
            } else {
                $view = $this->get_view( 'post-gallery', $view_data );
            }

            wp_cache_set( 'wizzaro_gallery_images', $view , $post->post_type . '-' . $post->ID );
        }
    }

    public function action_post_edit_form_tag() {
        global $post;
        if ( $post && in_array( $post->post_type, PluginConfig::get_instance()->get_galeries_post_types() ) ) {
            echo ' enctype="multipart/form-data"';
        }
    }

    public function action_plugin_activation() {
        $create = ImagesDBTable::get_instance()->create_table();

        if ( $create !== true ) {
            die( $create );
        }
    }

    public function action_enqueue_gallery_style() {
        wp_enqueue_style( 'wizzaro-gallery-style', $this->_config->get_css_admin_url() . 'wizzaro-gallery.css' );
    }

    public function action_delete_gallery_folder( $post_id ) {
        $post = get_post( $post_id );

        if ( $post ) {
            ImagesService::get_instance()->delete_all_post_images( $post );
        }
    }

    public function ajax_action_image_upload() {
        $response = array( 'status' => false, 'data' => array() );

        $languages_domain = $this->_config->get( 'languages', 'domain' );

        try {
            if( ! is_admin() || ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'wizzaro_gallery_images_add_nonce' ) || ! isset( $_POST['post_id'] ) ) {
                throw new Exception( __( 'Failed to write file to disk.', $languages_domain ) );
            }

            //check if post exist and current user can edit post
            $post = get_post( Encrypt::get_instance()->decryption( Filter::get_instance()->filter_text( $_POST['post_id'] ) ) );

            if ( ! $post ) {
                throw new Exception( __( 'Unknown galley.', $languages_domain ) );
            }

            if ( ! current_user_can( 'edit_post', $post->ID ) ) {
                throw new Exception( __( 'You are not allowed to edit this item.', $languages_domain ) );
            }

            //check is sended files is correct
            if( ! $_FILES || ! is_array( $_FILES['wizzaro-async-upload'] ) || is_array( $_FILES['wizzaro-async-upload']['name'] ) ) {
                return $this->_handle_upload_error( __( 'No file was uploaded.', $languages_domain ) );
            }

            $result = ImagesService::get_instance()->upload_post_image( $_FILES['wizzaro-async-upload'], $post );

            if ( isset( $result['error'] ) ) {
                throw new Exception( $result['error'] );
            }

            $result['id'] = Encrypt::get_instance()->encryption( $result['id'] );

            $response['status'] = true;
            $response['data'] = $result;

        } catch(Exception $e) {
            $response['data']['error'] = $e->getMessage();
        }

        wp_send_json( $response );
        die();
    }

    public function ajax_action_image_set_thumbnail() {
        global $wpdb;

        $response = array( 'status' => false );

        $languages_domain = $this->_config->get( 'languages', 'domain' );

        $image_service = ImagesService::get_instance();

        try {
            if( ! is_admin() || ! isset( $_POST['thumbnail_nonce'] ) || ! wp_verify_nonce( $_POST['thumbnail_nonce'], 'wizzaro_gallery_images_set_thumbnail_nonce' ) || ! isset( $_POST['post_id'] ) || ! isset( $_POST['img_id'] ) ) {
                throw new Exception( __( 'Error during set image as thumbnail.', $languages_domain ) );
            }

            //check if post exist and current user can edit post
            $post = get_post( Encrypt::get_instance()->decryption( Filter::get_instance()->filter_text( $_POST['post_id'] ) ) );

            if ( ! $post ) {
                throw new Exception( __( 'Unknown galley.', $languages_domain ) );
            }

            if ( ! current_user_can( 'edit_post', $post->ID ) ) {
                throw new Exception( __( 'You are not allowed to edit this item. Has your session expired?', $languages_domain ) );
            }

            if ( post_type_supports( $post->post_type, 'thumbnail' ) ) {
                throw new Exception( __( 'This post supports a standard mechanism for adding thumbnails - use it if you want to define thumbnail for him', $languages_domain ) );
            }

            $image = $image_service->get_post_image_by_id( $post->ID, Filter::get_instance()->filter_int( Encrypt::get_instance()->decryption(  $_POST['img_id'] ) ) );

            if ( ! $image ) {
                throw new Exception( __( 'Image no exist. Has it been deleted already?', $languages_domain ) );
            }

            if ( get_post_meta( $post->ID, '_post_gallery_thumbnail_id', true ) != $image->ID ) {
                if ( ! update_post_meta( $post->ID, '_post_gallery_thumbnail_id',  $image->ID ) ) {
                    add_post_meta( $post->ID, '_post_gallery_thumbnail_id',  $image->ID, true);
                }


                $uploads_dir = $image_service->get_upload_dir( $image_service->get_gallery_dir( $post, false ) );

                // Insert the attachment.
                if ( ! copy( $uploads_dir['path'] . DIRECTORY_SEPARATOR . $image->name, $uploads_dir['path_gallery_thumbnail'] . DIRECTORY_SEPARATOR . $image->name ) ) {
                    throw new Exception( __( 'Error during set image as thumbnail.', $languages_domain ) );
                }

                $attachment = array(
                    'guid'           => $upload_dir['url_gallery_thumbnail'] . '/' . basename( $image->name ),
                    'post_mime_type' => $image->mime_type,
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $image->name ) ),
                    'post_content'   => '',
                    'post_status'    => 'private'
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, ltrim( $uploads_dir['folder_gallery_thumbnail'] . '/' . $image->name, '/' ) );

                if ( $attach_id ) {
                    //metadata
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $uploads_dir['path_gallery_thumbnail'] . DIRECTORY_SEPARATOR . $image->name );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    //change post status
                    $wpdb->update( $wpdb->posts, array( 'post_status' => 'w-gallery-noread' ), array( 'ID' => $attach_id ) );

                    //remove old attachment
                    $old_attachment_id =  get_post_thumbnail_id( $post->ID );

                    if ( $old_attachment_id ) {
                        wp_delete_attachment( $old_attachment_id, true );
                    }

                    //update post thumbnail
                    set_post_thumbnail( $post->ID, $attach_id );

                } else {
                    throw new Exception( __( 'Error during set image as thumbnail.', $languages_domain ) );
                }
            }

            $response['status'] = true;

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }

        wp_send_json( $response );
        die();
    }

    public function ajax_action_image_edit() {
        $response = array( 'status' => false );

        $languages_domain = $this->_config->get( 'languages', 'domain' );

        try {
            if(
                ! is_admin() ||
                ! isset( $_POST['edit_nonce'] ) ||
                ! wp_verify_nonce( $_POST['edit_nonce'], 'wizzaro_gallery_images_edit_nonce' ) ||
                ! isset( $_POST['post_id'] ) ||
                ! isset( $_POST['img_id'] ) ||
                ! isset( $_POST['img_data'] )
            ) {
                throw new Exception( __( 'Error during deleting image.', $languages_domain ) );
            }

            //check if post exist and current user can edit post
            $post = get_post( Encrypt::get_instance()->decryption( Filter::get_instance()->filter_text( $_POST['post_id'] ) ) );

            if ( ! $post ) {
                throw new Exception( __( 'Unknown galley.', $languages_domain ) );
            }

            if ( ! current_user_can( 'edit_post', $post->ID ) ) {
                throw new Exception( __( 'You are not allowed to delete this item. Has your session expired?', $languages_domain ) );
            }

            $result = ImagesService::get_instance()->edit_post_image_data( $post, Filter::get_instance()->filter_int( Encrypt::get_instance()->decryption(  $_POST['img_id'] ) ), $_POST['img_data'] );

            if ( isset( $result['error'] ) ) {
                throw new Exception( $result['error'] );
            }

            $response['data'] = $result;
            $response['status'] = true;

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }

        wp_send_json( $response );
        die();
    }

    public function ajax_action_image_delete() {
        $response = array( 'status' => false );

        $languages_domain = $this->_config->get( 'languages', 'domain' );

        try {
            if( ! is_admin() || ! isset( $_POST['delete_nonce'] ) || ! wp_verify_nonce( $_POST['delete_nonce'], 'wizzaro_gallery_images_delete_nonce' ) || ! isset( $_POST['post_id'] ) || ! isset( $_POST['img_id'] ) ) {
                throw new Exception( __( 'Error during deleting image.', $languages_domain ) );
            }

            //check if post exist and current user can edit post
            $post = get_post( Encrypt::get_instance()->decryption( Filter::get_instance()->filter_text( $_POST['post_id'] ) ) );

            if ( ! $post ) {
                throw new Exception( __( 'Unknown galley.', $languages_domain ) );
            }

            if ( ! current_user_can( 'edit_post', $post->ID ) ) {
                throw new Exception( __( 'You are not allowed to delete this item. Has your session expired?', $languages_domain ) );
            }

            $result = ImagesService::get_instance()->delete_post_image( $post, Filter::get_instance()->filter_int( Encrypt::get_instance()->decryption(  $_POST['img_id'] ) ) );

            if ( isset( $result['error'] ) ) {
                throw new Exception( $result['error'] );
            }

            $response['status'] = true;

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
        }

        wp_send_json( $response );
        die();
    }
}
