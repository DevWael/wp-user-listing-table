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
                    foreach ($users

                    as $user):
                    $userID = $user['id'] ?? '';
                    $name = $user['name'] ?? '';
                    $userName = $user['username'] ?? '';
                    ?>
                    <tr>
                        <td data-user-id="<?php
                        echo esc_attr($userID) ?>"><?php
                            echo esc_html($userID) ?></td>
                        <td data-user-id="<?php
                        echo esc_attr($userID) ?>"><?php
                            echo esc_html($name) ?></td>
                        <td data-user-id="<?php
                        echo esc_attr($userID) ?>"><?php
                            echo esc_html($userName) ?></td>
                        <?php
                        endforeach; ?>
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
get_footer();
