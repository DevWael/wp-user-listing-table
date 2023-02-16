<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: WP User Listing Table
 * Plugin URI: https://github.com/DevWael/wp-user-listing-table
 * Description: WordPress plugin that provide users listing from a remote API on a custom URL endpoint
 * Version: 1.0.0
 * Author: Ahmad Wael
 * Author URI: https://www.bbioon.com
 * License: GPL-2.0+
 * Text Domain: wp-user-listing
 */

namespace WpUserListingTable;

if (!function_exists('add_filter')) {
    return;
}

define('PLUGIN_NAME', 'wp-user-listing');
define('PLUGIN_VERSION', '1.0.0');
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_URI', plugin_dir_url(__FILE__));

add_action('plugins_loaded', __NAMESPACE__.'\initialize');
function initialize()
{
    load_plugin_textdomain( 'wp-user-listing', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    $autoLoad = plugin_dir_path(__FILE__).'vendor/autoload.php';
    if (!class_exists(UserListing::class) && is_readable($autoLoad)) {
        /** @noinspection PhpIncludeInspection */
        require_once $autoLoad;
    }

    class_exists(UserListing::class) && UserListing::instance();
}
