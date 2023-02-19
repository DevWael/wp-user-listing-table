<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

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

/**
 * Check if loaded inside a WordPress environment.
 */
defined('ABSPATH') || exit;

/**
 * The plugin path with trailing slash.
 */
define('PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * The plugin URL with trailing slash.
 */
define('PLUGIN_URI', plugin_dir_url(__FILE__));

/**
 * Load all plugin logic after loading all activated plugins
 */
\add_action('plugins_loaded', __NAMESPACE__ . '\initialize');
function initialize()
{
    //todo create the languages class.
    /**
     * Load plugin text domain
     */
    \load_plugin_textdomain('wp-user-listing', false, dirname(plugin_basename(__FILE__)) . '/languages');


}

/**
 * Load composer packages
 */
$autoLoad = plugin_dir_path(__FILE__) . 'vendor/autoload.php';
if (!class_exists(UserListing::class) && is_readable($autoLoad)) {
    //check if the Main plugin class is loaded
    /** @noinspection PhpIncludeInspection */
    require_once $autoLoad;
}

/**
 * Create instance from the main plugin class
 */
class_exists(UserListing::class) && UserListing::instance();
