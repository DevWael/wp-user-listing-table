<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable;

use WpUserListingTable\Admin\AdminPage;
use WpUserListingTable\Admin\AjaxEndpoint;
use WpUserListingTable\Admin\MenuNav;
use WpUserListingTable\FrontEnd\Loader;
use WpUserListingTable\I18n\Languages;

/**
  * The plugin main class that responsible for loading all plugin logic.
  *
  * @package WpUserListingTable
  */
final class UserListing
{
    /**
     * Unique instance of the UserListing class.
     *
     * @var UserListing|null
     */
    private static ?UserListing $instance = null;

    /**
     * User_Listing constructor.
     */
    private function __construct()
    {
    }

    /**
     * Load class singleton instance.
     *
     * @return UserListing
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * Initialize the logic
     */
    public function init()
    {
        if (wp_installing()) {
            return; //prevent loading when we are installing WordPress
        }
        if (is_admin()) {
            /**
             * Load all admin side logic
             */
            $adminPage = new AdminPage();
            $adminPage->init();
        }
        //todo: reorganize class instances here
        $menu = new MenuNav();
        $menu->init();

        $ajax = new AjaxEndpoint();
        $ajax->init();

        $lang = new Languages();
        $lang->init();

        /**
         * Load all frontend logic.
         */
        $frontEnd = new Loader();
        $frontEnd->init();
    }
}
