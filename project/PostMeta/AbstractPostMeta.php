<?php
namespace Wizzaro\Gallery\v1\PostMeta;

abstract class AbstractPostMeta {

    /**
     * @var int
     */
    private $post_id;

    /**
     * @var string
     */
    protected $_meta_key = '';

    /**
     * @var array
     */
    protected $_meta_value = array();

    /**
     * @constructor
     */
    public function __construct( $post_id ) {
        $this->post_id = $post_id;
        $this->set_values( get_post_meta( $this->post_id, $this->_meta_key, true ) );
    }

    /**
     * @param string $value_key
     * @param mixed $default
     * @return mixed
     */
    public function get( $value_key, $default = null ) {
        if ( array_key_exists( $value_key, $this->_meta_value ) ) {
            return $this->_meta_value[$value_key];
        }

        return $default;
    }

    public function get_values() {
        return $this->_meta_value;
    }

    /**
     * @param string $value_key
     * @param mixed $value
     */
    public function set( $value_key, $value ) {
        $this->_meta_value[$value_key] = $value;
    }

    /**
     * @param array $meta_values
     */
    public function set_values( $meta_values ) {
        if ( is_array( $meta_values ) ) {
            $this->_meta_value = array_merge( $this->_meta_value, $meta_values );
        }
    }

    public function save() {
        if ( ! update_post_meta( $this->post_id, $this->_meta_key,  $this->get_values() ) ) {
            add_post_meta( $this->post_id, $this->_meta_key,  $this->get_values(), true);
        }
    }
}
