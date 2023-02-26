<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

/**
 * WP User Listing Table
 *
 * @package           DevWael\WpUserListingTable
 * @author            Ahmad Wael
 * @copyright         2023 Ahmad Wael
 * @license           GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WP User Listing Table
 * Plugin URI: https://github.com/DevWael/wp-user-listing-table
 * Description: WordPress plugin that provide users listing from a remote API on a custom URL endpoint.
 * Version: 1.0.0
 * Author: Ahmad Wael
 * Author URI: https://www.bbioon.com
 * Requires PHP: 7.4
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-user-listing
 * Domain Path: /languages
 */

namespace WpUserListingTable;

/**
 * Check if loaded inside a WordPress environment.
 */
defined('\ABSPATH') || exit;

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

/**
 * Flush rewrite rules on plugin activation to register the plugin rule.
 */
\register_activation_hook(__FILE__, static function(){
    flush_rewrite_rules();
});

/**
 * Flush rewrite rules on plugin deactivation to clean the rewrite rules.
 */
\register_deactivation_hook(__FILE__, static function(){
    /**
     * flush the rewrite rules before deleting the plugin option to
     * prevent reloading the plugin rewrite rules again.
     */
    flush_rewrite_rules();
    /**
     * delete the plugin option to allow register the rewrite rules again on activation.
     */
    //\delete_option('');
});