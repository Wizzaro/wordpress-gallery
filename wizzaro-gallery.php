<?php
   /*
   Plugin Name: Wizzaro Gallery
   Description: This is plugin for create gallery post type
   Version: 1.0
   Author: Przemysław Dziadek
   Author URI: http://www.wizzaro.com
   License: GPL-2.0+
   Text Domain: wizzaro-gallery-v1
   Domain Path: /languages
   */
   
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

load_plugin_textdomain( 'wizzaro-gallery-v1', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

require_once 'vendor/autoload.php';
require_once 'functions/functions.php';
Wizzaro\Gallery\v1\Plugin::create();