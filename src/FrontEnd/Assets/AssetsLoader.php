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
     * @var string $pluginName plugin unique name.
     */
    private string $pluginName;

    /**
     * @var string $pluginName current plugin version.
     */
    private string $pluginVersion;

    /**
     * @param string $pluginName plugin unique name.
     * @param string $pluginVersion current plugin version.
     */
    public function __construct(string $pluginName, string $pluginVersion)
    {
        $this->pluginName = $pluginName;
        $this->pluginVersion = $pluginVersion;
    }

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
            $handle = $this->pluginName . '-style';
            $src = PLUGIN_URI . 'resources/css/style.css';
            \wp_enqueue_style($handle, $src, [], $this->pluginVersion);
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
            $handle = $this->pluginName . '-script';
            $src = PLUGIN_URI . 'resources/js/main.js';
            \wp_enqueue_script($handle, $src, ['jquery'], $this->pluginVersion, true);
        }
    }
}
