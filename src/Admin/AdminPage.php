<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

/**
 * This class is responsible for all the admin side logic.
 *
 * @package WpUserListingTable\Admin
 */
class AdminPage
{
    /**
     * Register the options page with the WordPress.
     */
    public function register(): void
    {
        \add_options_page(
            \esc_html__('User Listing Settings', 'wp-user-listing'),
            \esc_html__('User Listing Settings', 'wp-user-listing'),
            'manage_options',
            'user_listing_settings',
            [$this, 'render']
        );
    }

    /**
     * Render the settings form.
     *
     * @return void
     */
    public function render(): void
    {
        ?>
        <div class="wrap">
            <h1><?php
                echo \esc_html(\get_admin_page_title()) ?></h1>
            <form method="post" action="options.php">
                <?php
                \settings_fields('wpul'); // settings group name
                \do_settings_sections('user_listing_settings'); // just a page slug
                \submit_button(); // "Save Changes" button
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register the setting fields and sections.
     *
     * @return void
     */
    public function settingsFields(): void
    {
        // 1. create section
        \add_settings_section(
            'wpul-table-settings-section', // section ID
            '', // title (optional)
            '', // callback function to display the section (optional)
            'user_listing_settings'
        );

        \register_setting(
            'wpul',
            'wpul-table-slug',
            [
                'type' => 'string',
                'sanitize_callback' => [$this, 'sanitize'],
                'default' => 'user-listing-table',
            ]
        );

        \add_settings_field(
            'user-listing-table',
            \esc_html__('Page slug', 'wp-user-listing'),
            [$this, 'textField'],
            // function to print the field
            'user_listing_settings',
            'wpul-table-settings-section', // section ID,
            [
                'label_for' => 'wpul-table-slug',
                'class' => 'wpul-slug-field',
                'name' => 'wpul-table-slug',
                'default' => 'user-listing-table',
                'required' => true,
                'description' => \esc_html__(
                    'Length between 8 to 50 characters containing only letters, numbers, dashes, and underscores.',
                    'wp-user-listing'
                ),
            ]
        );
    }

    /**
     * Print the text input with the description.
     *
     * @param  array  $args
     *
     * @return void
     */
    public function textField(array $args): void
    {
        printf(
            '<input %s type="text" id="%s" name="%s" value="%s" /><p class="description">%s</p>',
            \esc_attr($args['required']) ? 'required' : '',
            \esc_attr($args['name']),
            \esc_attr($args['name']),
            \esc_attr(
                \get_option(
                    'wpul-table-slug',
                    $args['default']
                )
            ),
            \esc_html($args['description'])
        );
    }

    /**
     * Remove white spaces from the value then test if the string length less than 8
     * And display an error message.
     * Also check if the string contains any characters other than
     * letters, numbers, dashes, and underscores.
     *
     * @param $value string to be checked
     *
     * @return string sanitized string
     */
    public function sanitize(string $value): string
    {
        $value = trim($value);
        $oldValue = \get_option('wpul-table-slug', 'user-listing-table');
        if (strlen($value) < 8) {
            /**
             * Check if the string length less than 8 characters
             */
            \add_settings_error(
                'user-listing-table',
                'not-enough-characters',
                \esc_html__('Slug should be at least 8 characters.', 'wp-user-listing'),
                'error' // success, warning, info
            );
            return \sanitize_text_field($oldValue);
        }

        if (strlen($value) > 50) {
            /**
             * Check if the string length more than 50 characters
             */
            \add_settings_error(
                'user-listing-table',
                'not-enough-characters',
                \esc_html__('Slug should be maximum 50 characters.', 'wp-user-listing'),
                'error' // success, warning, info
            );
            return \sanitize_text_field($oldValue);
        }

        if (preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            return \sanitize_text_field($value);
        }

        \add_settings_error(
            'user-listing-table',
            'spaces-not-allowed',
            \esc_html__(
                'Only letters, numbers, dashes, and underscores allowed!',
                'wp-user-listing'
            ),
            'error' // success, warning, info
        );
        return \sanitize_text_field($oldValue);
    }

    /**
     * Flush rewrite rules on page save to register the new rule.
     *
     * @return void
     */
    public function flushRewriteRules(): void
    {
        \delete_option('wpul-rules-flag');
        \flush_rewrite_rules();
    }

    /**
     * Hook the functionality to WordPress.
     *
     * @return void
     */
    public function init(): void
    {
        \add_action('admin_init', [$this, 'settingsFields']);
        \add_action('admin_menu', [$this, 'register']);
        \add_action('update_option_wpul-table-slug', [$this, 'flushRewriteRules']);
    }
}
