<?php
/**
 * Plugin Uninstall
 *
 * Deleting options and flushing WP rewrite rules.
 *
 * @package WpUserListingTable\Uninstaller
 * @version 2.3.0
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

//delete saved page slug
delete_option('wpul-table-slug');

//flush WP rewrite rules
flush_rewrite_rules();