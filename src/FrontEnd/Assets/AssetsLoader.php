<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Assets;

class AssetsLoader
{
    private string $pluginName;
    private string $pluginVersion;

    public function __construct(string $pluginName, string $pluginVersion)
    {
        $this->pluginName = $pluginName;
        $this->pluginVersion = $pluginVersion;
    }

    public function loadCSS(): void
    {
        if (\get_query_var('table_template') === 'user-listing-table') {
            $handle = $this->pluginName . '-style';
            $src = PLUGIN_URI . 'resources/css/style.css';
            \wp_enqueue_style($handle, $src, [], $this->pluginVersion);
        }
    }

    public function loadJS(): void
    {
        if (\get_query_var('table_template') === 'user-listing-table') {
            $handle = $this->pluginName . '-script';
            $src = PLUGIN_URI . 'resources/js/main.js';
            \wp_enqueue_script($handle, $src, ['jquery'], $this->pluginVersion, true);
        }
    }
}
