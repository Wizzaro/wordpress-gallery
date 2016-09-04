<?php
namespace Wizzaro\WPFramework\v1\Setting;

use Wizzaro\WPFramework\v1\Setting\AbstractSettingsPage;

class AbstractSettingsPageSingleton extends AbstractSettingsPage {
    
    private static $_instances = array();
    
    protected function __clone() {}
    
    public static function create() {
        $class = get_called_class();
        
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }
        
        return self::$_instances[$class];
    } 
    
    public static function get_instance() {
        return self::create();
    }
}