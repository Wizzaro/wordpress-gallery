<?php
namespace Wizzaro\Gallery\v1\Service;

use Wizzaro\WPFramework\v1\AbstractSingleton;
use Wizzaro\WPFramework\v1\Helper\File; 
use Wizzaro\WPFramework\v1\Helper\View;

use Wizzaro\Gallery\v1\Config\PluginConfig;
use Wizzaro\Gallery\v1\Model\Table\Images as ImagesDBTable;
use Wizzaro\Gallery\v1\Option\Image as ImageOption;

use \Exception;

class Images extends AbstractSingleton {
    
    private $_path_settings;
    
    protected function __construct() {
        $this->_path_settings = PluginConfig::get_instance()->get_group( 'dir_names' );
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for dirs
    
    public function get_gallery_dir( $post, $create = true ) {
        $gallery_dir = get_post_meta( $post->ID, '_post_gallery_images_dir', true );
        
        if ( mb_strlen( $gallery_dir ) <= 0 && $create === true ) {
            $gallery_dir = $this->_path_settings['gallery'] . $this->_path_settings['gallery_prefix'] . $post->ID;

            if ( ! update_post_meta( $post->ID, '_post_gallery_images_dir',  $gallery_dir) ) {
                add_post_meta( $post->ID, '_post_gallery_images_dir',  $gallery_dir, true);
            }
        }
        
        return $gallery_dir;
    }
    
    public function get_gallery_url( $gallery_dir ) {
        $return = array(
            'url' => '',
            'url_original'    => '',
            'url_tumbnail'    => '',
            'url_gallery_thumbnail' => '',
            'error'   => false,
        );
        
        if ( ! ( ( $uploads = wp_upload_dir( $time ) ) && false === $uploads['error'] ) ) {
            $return['error'] = $uploads['error'];
        } else {
            $return['url'] = $uploads['baseurl'] . $gallery_dir . '/';
            $return['url_original'] = $return['url'] . $this->_path_settings['original_images'] . '/';
            $return['url_tumbnail'] = $return['url'] . $this->_path_settings['thumbnails'] . '/';
            $return['url_gallery_thumbnail'] = $return['url'] . $this->_path_settings['gallery_thumbnail'] . '/';
        }
        
        return $return;
    }

    public function get_upload_dir( $gallery_dir ) {
        $return = array(
            'folder' => $gallery_dir,
            'folder_original'    => $gallery_dir . DIRECTORY_SEPARATOR . $this->_path_settings['original_images'],
            'folder_tumbnail'    => $gallery_dir . DIRECTORY_SEPARATOR . $this->_path_settings['thumbnails'],
            'folder_gallery_thumbnail' => $gallery_dir . DIRECTORY_SEPARATOR . $this->_path_settings['gallery_thumbnail'],
            'path' => '',
            'path_original'    => '',
            'path_tumbnail'    => '',
            'path_gallery_thumbnail' => '',
            'url' => '',
            'url_original'    => '',
            'url_tumbnail'    => '',
            'url_gallery_thumbnail' => '',
            'error'   => false,
        );
        
        if ( ! ( ( $uploads = wp_upload_dir( $time ) ) && false === $uploads['error'] ) ) {
            $return['error'] = $uploads['error'];
        } else {
            $gallery_path = wp_normalize_path( $uploads['basedir'] . $gallery_dir );
            
            try {
                //create gallery path
                if ( ! wp_mkdir_p( $gallery_path ) ) {
                    throw new Exception( $gallery_path );
                }
                
                $return['path'] = $gallery_path;
                
                //create original images path
                
                $return['path_original'] = $gallery_path . DIRECTORY_SEPARATOR . $this->_path_settings['original_images'];
                
                if ( ! wp_mkdir_p( $return['path_original'] ) ) {
                    throw new Exception( $gallery_path );
                }
                
                //create thumbnail path
                $return['path_tumbnail'] = $gallery_path . DIRECTORY_SEPARATOR . $this->_path_settings['thumbnails'];
                
                if ( ! wp_mkdir_p( $return['path_tumbnail'] ) ) {
                    throw new Exception( $gallery_path );
                }
                
                //create gallery thumbnail path
                $return['path_gallery_thumbnail'] = $gallery_path . DIRECTORY_SEPARATOR . $this->_path_settings['gallery_thumbnail'];
                
                if ( ! wp_mkdir_p( $return['path_gallery_thumbnail'] ) ) {
                    throw new Exception( $gallery_path );
                }
                
            } catch(Exception $e) {
                $return['error'] = sprintf( __( 'Unable to create directory %s. Is its parent directory writable by the server?', PluginConfig::get_instance()->get( 'languages', 'domain' ) ), $e->getMessage() );
            }
            
            $return['url'] = $uploads['baseurl'] . $gallery_dir . '/';
            $return['url_original'] = $return['url'] . $this->_path_settings['original_images'] . '/';
            $return['url_tumbnail'] = $return['url'] . $this->_path_settings['thumbnails'] . '/';
            $return['url_gallery_thumbnail'] = $return['url'] . $this->_path_settings['gallery_thumbnail'] . '/';
        }

        return $return;
    }
    
    //----------------------------------------------------------------------------------------------------
    // CRUD Interface Functions for image
    
    //----------------------------------------------------------------------------------------------------
    // Functions for create
    
    private function _handle_error( $message ) {
        return array( 'error' => $message );
    }
    
    public function upload_post_image( $file, $post ) {
        $images_db_table = ImagesDBTable::get_instance();
        
        $languages_domain = PluginConfig::get_instance()->get( 'languages', 'domain' );
        
        if ( ! current_user_can( 'upload_files' ) ) {
            return $this->_handle_error( __( 'You do not have permission to upload files.', $languages_domain ) );
        }
        
        //check file errors 
        $upload_error_strings = array(
            false,
            __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini.', $languages_domain ),
            __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.', $languages_domain ),
            __( 'The uploaded file was only partially uploaded.', $languages_domain ),
            __( 'No file was uploaded.', $languages_domain ),
            '',
            __( 'Missing a temporary folder.', $languages_domain ),
            __( 'Failed to write file to disk.', $languages_domain ),
            __( 'File upload stopped by extension.', $languages_domain )
        );
        
        // A successful upload will pass this test.
        if ( isset( $file['error'] ) && $file['error'] > 0 ) {
            return $this->_handle_error( $upload_error_strings[ $file['error'] ] );
        }

        // A non-empty file will pass this test.
        if ( ! ( $file['size'] > 0 ) ) {
            $this->_handle_error( __( 'File is empty. Please upload something more substantial.', $languages_domain ) );
        }
        
        // A properly uploaded file will pass this test. There should be no reason to override this one.
        if ( ! @ is_uploaded_file( $file['tmp_name'] ) ) {
            return $this->_handle_error( __( 'Specified file failed upload.', $languages_domain ) );
        }
        
        // A correct MIME type will pass this test. Override $mimes or use the upload_mimes filter.
        $wp_filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], array( 
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg', 
            'png' => 'image/png', 
            'gif' => 'image/gif' 
        ) );
        
        $ext = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
        $type = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
        $proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];

        // Check to see if wp_check_filetype_and_ext() determined the filename was incorrect
        if ( $proper_filename ) {
            $file['name'] = $proper_filename;
        }
        
        $file['name'] = mb_strtolower($file['name']);
        
        if ( ( ! $type || ! $ext ) && ! current_user_can( 'unfiltered_upload' ) ) {
            return $this->_handle_error( __( 'Sorry, this file type is not permitted for security reasons.', $languages_domain ) );
        }
        
        if ( ! $type ) {
            $type = $file['type'];
        }
        
        //check is file no exist in gallery
        if( $images_db_table->is_post_image_exist_by_name( $post->ID, $file['name'] ) ) { //TODO add force upload
            return $this->_handle_error( __( 'This image already exists in the gallery.', $languages_domain ) );
        }

        //get gallery images dir path 
        $gallery_dir = $this->get_gallery_dir( $post );
        
        //Get uploads folder
        if ( ! ( ( $uploads_dir = $this->get_upload_dir( $gallery_dir ) ) && false === $uploads_dir['error'] ) ) {
            $this->_handle_error( $uploads_dir['error'] );
        }
    
        //if force upload
        //$file_name = wp_unique_filename( $uploads['path'], $file['name'], $unique_filename_callback );
        
        $file_name = $file['name'];
        
        $file_path = $uploads_dir['path_original'] . DIRECTORY_SEPARATOR . $file_name;
        
        // Move the file to the uploads original dir.
        if ( ! @move_uploaded_file( $file['tmp_name'], $file_path ) ) {
            return $this->_handle_error( sprintf( __('The uploaded file could not be moved to %s.', $languages_domain ), $file_path ) );
        }
        
        // Set correct file permissions.
        $stat = stat( dirname( $file_path ));
        $perms = $stat['mode'] & 0000666;
        @ chmod( $file_path, $perms );
    
        if ( is_multisite() ) {
            delete_transient( 'dirsize_cache' );
        }
        
        $image_editor = wp_get_image_editor( $file_path, array( 'mime_type' => $type ) );
        
        if ( is_wp_error( $image_editor ) ) {
            return $this->_handle_error( __( 'Failed to write file to disk.', $languages_domain ) );
        }
        
        //start transactions
        $images_db_table->start_transaction();
        
        $sizes = $image_editor->get_size();
        
        try {
            //insert in new image to database
            $image_id = $images_db_table->insert( $post->ID, get_current_user_id(), $file_name, $sizes['width'], $sizes['height'], $type );
            
            if ( $image_id  === false ) { 
                throw new Exception( 'Filed add new image to database' );
            }
            
            //check is image have orientation exif data
            $exif = @exif_read_data( $file_path );
        
            if ( is_array( $exif ) && array_key_exists( 'Orientation', $exif ) ) {
                $angle = false;
                
                switch( $exif['Orientation']) {
                    case 3:
                        $angle = 180;
                        break;
                    case 6:
                        $angle = 90;
                        break;
                    case 8:
                        $angle = -90;
                        break;
                }
                
                if ( $angle !== false ) {
                    if ( is_wp_error( $image_editor->rotate( $angle ) ) ) {
                        throw new Exception( 'Failed rotete original image' );
                    }
                    
                    if ( is_wp_error( $image_editor->save( $file_path ) ) ) {
                        throw new Exception( 'Failed save original image after rotate' );
                    }
                }
            }
            
            //create image
            $image_sizes = ImageOption::get_instance()->get_options( 'wizzaro_gallery_image' );
            
            $image_editor->resize( $image_sizes['width'], $image_sizes['height'], false);
            
            if ( is_wp_error( $image_editor->save( $uploads_dir['path'] . DIRECTORY_SEPARATOR . $file_name ) ) ) {
                throw new Exception( 'Failed save image' );
            }
            //create thumbnail
            $image_editor->resize( $image_sizes['thumb_width'], $image_sizes['thumb_height'], false);

            if ( is_wp_error( $image_editor->save( $uploads_dir['path_tumbnail'] . DIRECTORY_SEPARATOR . $file_name ) ) ) {
                throw new Exception( 'Failed save thumbnail' );
            }
            
            $images_db_table->commit();
            
            return array(
                'id' => $image_id,
                'name' => $file_name,
                'thumbnail_url' => $uploads_dir['url_tumbnail'] . $file_name
            );
            
        } catch(Exception $e) {
            $images_db_table->rollback();
            //return $this->_handle_error($e->getMessage());
            return $this->_handle_error( __( 'Failed to write file to disk.', $languages_domain ) );
        }
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for read
    
    public function get_post_images( $post, $visible = false, $count = false ) {
        return ImagesDBTable::get_instance()->get_post_images( $post->ID, $visible, $count );
    }
    
    public function get_post_image_by_id( $post_id, $img_id, $visible = false ) {
        return ImagesDBTable::get_instance()->get_post_image_by_id( $post_id, $img_id, $visible );
    }
    
    public function get_post_thumbnail( $post ) {
        $thumbnail_id = get_post_meta( $post->ID, '_post_gallery_thumbnail_id', true );
        
        if ( $thumbnail_id ) {
            $thumbnail = $this->get_post_image_by_id( $post->ID, $thumbnail_id );
            
            if ( $thumbnail ) {
                return View::get_instance()->get_content_for_instance( PluginConfig::get_instance()->get_view_templates_path(), $this, 'post-thumbnail', array (
                    'urls' => $this->get_gallery_url( $this->get_gallery_dir( $post, false ) ),
                    'thumbnail' => $thumbnail
                ) );
            }
        }
        
        return '';
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for edit
    
    public function set_all_post_image_visible( $post ) {
        ImagesDBTable::get_instance()->set_all_post_image_visible( $post->ID );
    }
    
    public function resort_post_images( $ids, $post ) {
        ImagesDBTable::get_instance()->resort_post_images( $ids, $post->ID );
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for delete
    
    public function delete_post_image( $post, $img_id ) {
        $image = $this->get_post_image_by_id( $post->ID, $img_id );
            
        if ( ! $image ) {
            return $this->_handle_error( __( 'Image no exist. Has it been deleted already?', PluginConfig::get_instance()->get( 'languages', 'domain' ) ) );
        }

        //Get uploads folders
        if ( ! ( ( $uploads_dir = $this->get_upload_dir( $this->get_gallery_dir( $post ) ) ) && false === $uploads_dir['error'] ) ) {
            return $this->_handle_error( $uploads_dir['error'] );
        }

        //delete from db
        if ( ImagesDBTable::get_instance()->delete( $image->ID ) === false ) {
            return $this->_handle_error( __( 'Error during deleting image. Has it been deleted already?', PluginConfig::get_instance()->get( 'languages', 'domain' ) ) );
        }

        //delete original
        @unlink( $uploads_dir['path_original'] . DIRECTORY_SEPARATOR . $image->name );
        //delete thumbnail
        @unlink( $uploads_dir['path_tumbnail'] . DIRECTORY_SEPARATOR . $image->name );
        //delete img
        @unlink( $uploads_dir['path'] . DIRECTORY_SEPARATOR . $image->name );
        
        return true;
    }

    public function delete_all_post_images( $post ) {
        if ( ImagesDBTable::get_instance()->delete_all_post_images( $post->ID ) !== false ) {
            
            if ( ( ( $uploads_dir = $this->get_upload_dir( $this->get_gallery_dir( $post ) ) ) && false === $uploads_dir['error'] ) ) {
                File::get_instance()->rrmdir( $uploads_dir['path'] );
            }
            
            return true;
        }

        return false;
    }
 }
