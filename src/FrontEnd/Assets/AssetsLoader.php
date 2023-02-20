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
            \wp_enqueue_script($handle, $src, ['jquery'], self::PLUGIN_VERSION, true);
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
     * @return void
     */
    public function init(): void
    {
        \add_action('wp_enqueue_scripts', [$this, 'loadCSS']);
        \add_action('wp_enqueue_scripts', [$this, 'loadJS']);
    }
}
