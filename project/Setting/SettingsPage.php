<?php
namespace Wizzaro\Gallery\v1\Setting;

use Wizzaro\WPFramework\v1\Setting\AbstractSettingsPageSingleton;
use Wizzaro\Gallery\v1\Config\PluginConfig;

class SettingsPage extends AbstractSettingsPageSingleton {
    
    protected function get_page_config() {
        return array(
            'page_title' => __( 'Gallery Settings', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
            'menu_title' => __( 'Gallery', PluginConfig::get_instance()->get( 'languages', 'domain' ) ),
            'capability' => 'manage_options',
            'menu_slug' => 'wizzaro-gallery-settings'
        );
    }
}