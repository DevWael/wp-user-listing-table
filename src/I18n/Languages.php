<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\I18n;

/**
 * Load plugin text domain
 *
 * @package WpUserListingTable\I18n
 */
class Languages
{
    public function loadTextDomain(): void
    {
        $languagesDirRelPath = dirname(\plugin_basename(__FILE__), 3) . '/languages';
        /**
         * Load plugin text domain
         */
        \load_plugin_textdomain('wp-user-listing', false, $languagesDirRelPath);
    }

    /**
     * Attach the class functions to WordPress hooks
     *
     * @return void
     */
    public function init(): void
    {
        \add_action('plugins_loaded', [$this, 'loadTextDomain']);
    }
}
