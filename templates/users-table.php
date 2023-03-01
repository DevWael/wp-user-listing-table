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

/**
 * Check if the installed theme is block theme (FSE Themes).
 * If so, then we won't load the get_header() function as the theme doesn't
 * include any header.php file.
 * We will load the theme header block instead.
 *
 * @see https://core.trac.wordpress.org/ticket/55023
 */
$blockTheme = wp_is_block_theme();
if ($blockTheme) {
    ?><!doctype html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="wp-site-blocks">
        <header class="wp-block-template-part">
            <?php block_header_area() ?>
        </header>
    <?php
}
/**
 * If it's traditional WP theme, then load the header.
 */
if (!$blockTheme) {
    get_header();
}

do_action('wp_user_table_start');
$usersProvider = new WpUserListingTable\FrontEnd\Data\UsersProvider();
$users = apply_filters('wp_user_table_users_list', $usersProvider->usersList());
?>
    <div class="wp-users-table-template">
        <?php
        if ( ! empty($users)): ?>
            <div class="table-wrapper">
                <table id="usersTable"
                       class="wp-users-table wp-users-table-js has-background">
                    <thead>
                    <tr>
                        <th><?php
                            esc_html_e('ID', 'wp-user-listing') ?></th>
                        <th><?php
                            esc_html_e('Name', 'wp-user-listing') ?></th>
                        <th><?php
                            esc_html_e('Username', 'wp-user-listing') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($users as $user):
                        /**
                         * Use this filter to modify the user data before it printed.
                         *
                         * @param $user array user data.
                         */
                        $user = apply_filters('wp_user_table_user_loop_item', $user);
                        $userID = $user['id'] ?? '';
                        $name = $user['name'] ?? '';
                        $userName = $user['username'] ?? '';
                    ?>
                        <tr>
                            <td data-user-id="<?php echo esc_attr($userID) ?>"><?php echo esc_html($userID) ?></td>
                            <td data-user-id="<?php echo esc_attr($userID) ?>"><?php echo esc_html($name) ?></td>
                            <td data-user-id="<?php echo esc_attr($userID) ?>"><?php echo esc_html($userName) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php
        endif; ?>
        <div class="overlay"></div>
        <div class="wp-single-user-popup-container"></div>
    </div>
<?php
do_action('wp_user_table_end');

/**
 * Check if the installed theme is block theme (FSE Themes).
 * If so, then we won't load the get_footer() function as the theme doesn't
 * include any footer.php file.
 * We will load the theme footer block instead.
 */
if ($blockTheme) {
    ?>
    <?php block_footer_area(); ?>
    </div>
    <?php wp_footer(); ?>
    </body>
    </html><?php
}
/**
 * If it's traditional WP theme, then load the footer.
 */
if (!$blockTheme) {
    get_footer();
}