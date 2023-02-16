<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/user-listing-table/users-table.php.
 *
 * @package devwael/wp-user-listing-table
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
get_header();
do_action('wp_user_table_start');
?>
    <div id="main" class="wp-users-table-template">
        Main template goes here...
    </div>
<?php
do_action('wp_user_table_end');
get_footer();
