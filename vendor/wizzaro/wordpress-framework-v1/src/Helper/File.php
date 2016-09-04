<?php
namespace Wizzaro\WPFramework\v1\Helper;

use Wizzaro\WPFramework\v1\AbstractSingleton;  

class File extends AbstractSingleton {
    
    public function rrmdir( $dir ) {
        if ( is_dir( $dir ) ) {
            $files = array_diff( scandir( $dir ), array( '.', '..' ) ); 
            
            foreach ($files as $file) {
                ( is_dir( "$dir/$file" ) ) ? $this->rrmdir( "$dir/$file" ) : @unlink( "$dir/$file" ); 
            } 
            
            @rmdir( $dir ); 
        }
    }
}
