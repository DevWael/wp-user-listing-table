<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable;

use WpUserListingTable\Admin\AdminPage;
use WpUserListingTable\FrontEnd\Loader;

 /**
  * The plugin main class that responsible for loading all plugin logic.
  *
  * @package WpUserListingTable
  */
class UserListing
{
    // Hold the class instance.
    private static $instance = null;

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
        if (self::$instance === null) {
            $instance = new self();
            $instance->init();
        }

        return $instance;
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

        /**
         * Load all frontend logic.
         */
        $frontEnd = new Loader();
        $frontEnd->init();
    }
}
