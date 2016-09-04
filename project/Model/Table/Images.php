<?php
namespace Wizzaro\Gallery\v1\Model\Table;

use Wizzaro\WPFramework\v1\Model\AbstractModel; 

use Wizzaro\Gallery\v1\Config\PluginConfig;

class Images extends AbstractModel {
    
    protected $_table_name = 'wizzaro_gallery_images';
    
    protected function _get_table_prefix() {
        global $wpdb;
        return $wpdb->base_prefix . $this->_table_name;
    }
    
    public function create_table() {
        //init database
        global $wpdb;

        $query = 'CREATE TABLE IF NOT EXISTS `' . $this->_get_table_prefix() . '` (' .
        '`ID` INT AUTO_INCREMENT NOT NULL,' .
        '`post_id` BIGINT(20) NOT NULL,' .
        '`upload_autor` BIGINT(20) NOT NULL,' .
        '`name` VARCHAR(255) NOT NULL,' .
        '`width` INT(10) NOT NULL DEFAULT 0,' .
        '`height` INT(10) NOT NULL DEFAULT 0,' .
        '`alt_text` VARCHAR(255) NULL DEFAULT NULL,' .
        '`description` VARCHAR(255) NULL DEFAULT NULL,' .
        '`mime_type` VARCHAR(10) NOT NULL,' .
        '`order` INT(10) NOT NULL DEFAULT 1,' .
        '`visible` BOOLEAN NOT NULL DEFAULT 0,' .
        '`modified_at` TIMESTAMP NULL DEFAULT NULL,' .
        '`create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,' .
        'PRIMARY KEY (`ID`)' .
        ')ENGINE = InnoDB;';

        if ( ! $wpdb->query( $query ) ) {
            return __( 'No add "' . $this->_table_name . '" table to database', PluginConfig::get_instance()->get( 'languages', 'domain' ) );
        };
        
        return true;
    }

    public function get_next_order( $post_id ) {
        global $wpdb;

        $query = $wpdb->prepare( 'SELECT (MAX(`order`) + 1) AS `next_order` FROM `' . $this->_get_table_prefix() . '` WHERE post_id = %d', $post_id );

        $row = $wpdb->get_row( $query );
        
        if ( $row && $row->next_order ) {
            return $row->next_order;
        }
        
        return 1;
    }

    //----------------------------------------------------------------------------------------------------
    // CRUD Interface Functions
    
    //----------------------------------------------------------------------------------------------------
    // Functions for create
    
    public function insert( $post_id, $autor_id, $file_name, $width, $height, $mime, $order = false ) {
        global $wpdb;
        
        if ( ! $wpdb->insert(
                $this->_get_table_prefix(), 
                array(
                    'post_id' => $post_id,
                    'upload_autor' => $autor_id,
                    'name' => $file_name,
                    'width' => $width,
                    'height' => $height,
                    'mime_type' => $mime,
                    'order' => $order !== false ? $order : $this->get_next_order( $post_id )
                ), 
                array('%d', '%d', '%s', '%d', '%d', '%s')
            ) ) {
                return false;
            }
            
            return $wpdb->insert_id;
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for read
    
     public function is_post_image_exist_by_name( $post_id, $img_name ) {
        global $wpdb;

        $query = $wpdb->prepare( 'SELECT `ID` FROM `' . $this->_get_table_prefix() . '` WHERE `name` = %s AND post_id = %d', $img_name, $post_id );

        if ( $wpdb->get_row( $query ) ) {
            return true;
        }
        
        return false;
    }
     
    public function get_post_images( $post_id, $visible = false, $limit = false ) {
        global $wpdb;
        
        $prepare = array( $post_id );

        $query = 'SELECT * FROM `' . $this->_get_table_prefix() . '` WHERE post_id = %d';
        
        if ( $visible ) {
            $query .= ' AND `visible` = 1';
        }
        
        $query .= ' ORDER BY `order` ASC, `create_at` ASC';
        
        if ( is_numeric( $limit ) && $limit > 0 ) {
            $query .= ' LIMIT %d';
            array_push( $prepare, $limit );
        }
        
        $query = $wpdb->prepare( $query, $prepare );
        
        return $wpdb->get_results( $query ); 
    }
    
    public function get_post_image_by_id( $post_id, $img_id, $visible = false ) {
        global $wpdb;

        $query = 'SELECT * FROM `' . $this->_get_table_prefix() . '` WHERE `ID` = %d AND post_id = %d';
        
        if ( $visible ) {
            $query .= ' AND `visible` = 1';
        }

        $result = $wpdb->get_row( $wpdb->prepare( $query , $img_id, $post_id ) );

        if ( $result ) {
            return $result;
        }
        
        return false;
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for edit
    
    public function set_all_post_image_visible( $post_id ) {
        global $wpdb;
        $wpdb->update( $this->_get_table_prefix(), array( 'visible' => 1 ), array( 'post_id' => $post_id ), array( '%d' ), array( '%d' ) );
    }
    
    public function resort_post_images( $ids, $post_id ) {
        global $wpdb;
        
        $ids_quantity = count( $ids );
        
        if ( $ids_quantity > 0 ) {
            
            $query = 'UPDATE ' . $this->_get_table_prefix() . ' SET `order` = CASE';
            
            $prepare = array();
            
            for( $i = 0; $i <= $ids_quantity; $i++ ) {
                $query .= ' WHEN `ID` = %d THEN %d';
                
                array_push( $prepare, $ids[$i] );
                array_push( $prepare, ( $i + 1 ) );
            }
            
            $query .= ' ELSE `order` END WHERE `post_id` = %d';
            
            array_push( $prepare, $post_id );
            
            $query = $wpdb->prepare( $query, $prepare );
            
            $wpdb->query( $query );
        }
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for delete
    
    public function delete( $img_id ) {
        global $wpdb;
        
        if ( ! $wpdb->delete( $this->_get_table_prefix(), array( 'ID' => $img_id ), array( '%d' ) ) ) {
            return false;
        }
        
        return true;                
    }
    
    public function delete_all_post_images( $post_id ) {
        global $wpdb;
        
        if ( ! $wpdb->delete( $this->_get_table_prefix(), array( 'post_id' => $post_id ), array( '%d' ) ) ) {
            return false;
        }
        
        return true;
    }
}
