<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton; 
use Wizzaro\WPFramework\v1\Helper\Request;

class WordpressRequest extends AbstractSingleton {
    
    public function is_wp_page() {
        $uri = Request::get_instance()->get_request_uri( false );
        
        if ( 
            preg_match( '/^\/(admin|wp-activate|wp-blog-header|wp-comments-post|wp-cron|wp-links-opml|wp-load|wp-login|wp-mail|wp-settings|wp-signup|wp-trackback|xmlrpc)/i', $uri ) ||
            preg_match( '/^\/wp-/i', $uri ) ||
            preg_match( '/.(php|xml)$/i', $uri ) 
        ) {
            return true;
        }
        
        return false;
    }
    
    public function is_wp_search_page() {
        if ( isset( $_GET['s'] ) ) {
            return true;    
        }
        
        return false;
    }
}