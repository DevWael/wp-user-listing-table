<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable;

 use WpUserListingTable\Admin\AdminPage;
 use WpUserListingTable\FrontEnd\View;

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
     * @return UserListing
     *
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
            return;
        }
        if (is_admin()) {
            $adminPage = new AdminPage();
            \add_action('init', [$adminPage, 'init']);
        }

        $frontEnd = new View();
        $frontEnd->init();
    }
}
