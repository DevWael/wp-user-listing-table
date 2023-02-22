<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Assets;

/**
 * This class loads all frontend needed assets.
 */
class AssetsLoader implements Assets
{
    /**
     * The unique identifier of this plugin.
     */
    private const PLUGIN_NAME = 'wp-user-listing';

    /**
     * The current version of the plugin.
     */
    private const PLUGIN_VERSION = '1.0.0';

    /**
     * Load CSS styles files.
     *
     * @return void
     */
    public function loadCSS(): void
    {
        /**
         * Check if the plugin frontend page is opened before loading the assets files.
         */
        if (\get_query_var('table_template') === 'user-listing-table') {
            $handle = self::PLUGIN_NAME . '-style';
            $src = $this->pluginResourcesDirUrl() . 'css/style.css';
            \wp_enqueue_style($handle, $src, [], self::PLUGIN_VERSION);
            \do_action('wp_users_table_load_css_assets');
        }
    }

    /**
     * Load Javascript files.
     *
     * @return void
     */
    public function loadJS(): void
    {
        /**
         * Check if the plugin frontend page is opened before loading the assets files.
         */
        if (\get_query_var('table_template') === 'user-listing-table') {
            $handle = self::PLUGIN_NAME . '-script';
            $src = $this->pluginResourcesDirUrl() . 'js/main.js';
            \wp_enqueue_script(
                $handle,
                $src,
                ['jquery'],
                self::PLUGIN_VERSION,
                true
            );
            /**
             * Add the object that will be used for the ajax and for localizing
             * frontend components.
             */
            \wp_localize_script($handle, 'usersTableObject', [
                'ajaxURL' => esc_url(admin_url('admin-ajax.php')),
                'nonce' => wp_create_nonce(),
                'action' => 'users_table_request',
                'i18n' => [
                    'popupTitle' => esc_html__(
                        'User Information',
                        'wp-user-listing'
                    ),
                    'id' => esc_html__('ID', 'wp-user-listing'),
                    'name' => esc_html__('Name', 'wp-user-listing'),
                    'username' => esc_html__('Username', 'wp-user-listing'),
                    'email' => esc_html__('Email', 'wp-user-listing'),
                    'phone' => esc_html__('Phone', 'wp-user-listing'),
                    'address' => esc_html__('Address', 'wp-user-listing'),
                    'street' => esc_html__('Street', 'wp-user-listing'),
                    'suite' => esc_html__('Suite', 'wp-user-listing'),
                    'city' => esc_html__('City', 'wp-user-listing'),
                    'zip' => esc_html__('Zip Code', 'wp-user-listing'),
                    'lat' => esc_html__('Latitude', 'wp-user-listing'),
                    'lng' => esc_html__('Longitude', 'wp-user-listing'),
                    'website' => esc_html__('Website', 'wp-user-listing'),
                    'company' => esc_html__('Company', 'wp-user-listing'),
                    'companyName' => esc_html__('Name', 'wp-user-listing'),
                    'catchphrase' => esc_html__(
                        'Catchphrase',
                        'wp-user-listing'
                    ),
                    'business' => esc_html__('Business', 'wp-user-listing'),
                ],
            ]);
            \do_action('wp_users_table_load_js_assets');
        }
    }

    /**
     * Prepare the resources URL to the resources plugin directory.
     *
     * @return string
     */
    private function pluginResourcesDirUrl(): string
    {
        return \plugin_dir_url(dirname(__FILE__, 3)) . 'resources/';
    }

    /**
     * Attach the class functions to WordPress hooks
     *
     * @return void
     */
    public function init(): void
    {
        \add_action('wp_enqueue_scripts', [$this, 'loadCSS']);
        \add_action('wp_enqueue_scripts', [$this, 'loadJS']);
    }
}
