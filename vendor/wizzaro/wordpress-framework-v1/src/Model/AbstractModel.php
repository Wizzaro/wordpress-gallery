<?php
namespace Wizzaro\WPFramework\v1\Model;

use Wizzaro\WPFramework\v1\AbstractSingleton;

abstract class AbstractModel extends AbstractSingleton {
    
    public function start_transaction() {
        global $wpdb;
        return $wpdb->query( 'START TRANSACTION' );
    }
    
    public function commit() {
        global $wpdb;
        return $wpdb->query( 'COMMIT' );
    }
    
    public function rollback( ) {
        global $wpdb;
        return $wpdb->query( 'ROLLBACK' );
    }
}
