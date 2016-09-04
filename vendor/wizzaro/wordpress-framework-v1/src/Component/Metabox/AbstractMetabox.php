<?php
namespace Wizzaro\WPFramework\v1\Component\Metabox;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

abstract class AbstractMetabox extends AbstractSingleton {
    
    protected function __construct() {
        add_action( 'do_meta_boxes', array( $this, 'register' ) );
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }
    
    protected function _get_metabox_config() {
        return array(
            'id' => '',
            'title' => '',
            'screen' => '',
            'context' => 'side',
            'priority' => 'core'
        );
    }
    
    public function register() {
        $config = $this->_get_metabox_config();
        add_meta_box( $config['id'], $config['title'], array( $this, 'render' ) , $config['screen'], $config['context'], $config['priority'] );
    }
    
    public function render( $post ) {
    }
    
    public function save( $post_id, $post ) {
    }
}
