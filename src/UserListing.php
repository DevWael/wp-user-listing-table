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
     * @var AdminPage instance of the admin page
     */
    private AdminPage $adminPage;

    /**
     * @var MenuNav instance of the menu
     */
    private MenuNav $menuNav;

    /**
     * @var AjaxEndpoint instance of the ajax class
     */
    private AjaxEndpoint $ajaxEndpoint;

    /**
     * @var Loader instance of the frontend loader
     */
    private Loader $frontEndLoader;

    /**
     * @var Languages instance of languages and text domain loader
     */
    private Languages $languages;

    /**
     * User_Listing constructor.
     */
    private function __construct(
        AdminPage $adminPage,
        MenuNav $menuNav,
        AjaxEndpoint $ajaxEndpoint,
        Loader $loader,
        Languages $languages
    ) {

        $this->adminPage = $adminPage;
        $this->menuNav = $menuNav;
        $this->ajaxEndpoint = $ajaxEndpoint;
        $this->frontEndLoader = $loader;
        $this->languages = $languages;
    }

    /**
     * Load class singleton instance.
     *
     * @param  AdminPage|null     $adminPage     instance of AdminPage object.
     * @param  MenuNav|null       $menuNav       instance of MenuNav object.
     * @param  AjaxEndpoint|null  $ajaxEndpoint  instance of AjaxEndpoint object.
     * @param  Loader|null        $loader        instance of frontend Loader object.
     * @param  Languages|null     $languages     instance of Languages object.
     *
     * @return UserListing singleton instance
     */
    public static function instance(
        AdminPage $adminPage = null,
        MenuNav $menuNav = null,
        AjaxEndpoint $ajaxEndpoint = null,
        Loader $loader = null,
        Languages $languages = null
    ): self {

        if (null === self::$instance) {
            $adminPageObject = $adminPage ?? new AdminPage(); // new instance of AdminPage object
            $menuNavObject = $menuNav ?? new MenuNav(); // new instance of MenuNav object
            $ajaxEndpointObject = $ajaxEndpoint ?? new AjaxEndpoint(); // new instance of AjaxEndpoint object
            $loaderObject = $loader ?? new Loader(); // new instance of frontend Loader object
            $languagesObject = $languages ?? new Languages(); // new instance of Languages object
            self::$instance = new self($adminPageObject, $menuNavObject, $ajaxEndpointObject, $loaderObject, $languagesObject);
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * Initialize the logic
     */
    public function init()
    {
        if (\wp_installing()) {
            return; //prevent loading when we are installing WordPress
        }

        /**
         * Load all admin side logic
         */
        if (\is_admin()) {
            $this->adminPage->init(); //load all admin page actions

            $this->menuNav->init(); // load all menu nav actions

            $this->ajaxEndpoint->init(); // load all Ajax functionality
        }

        $this->languages->init(); // load all languages

        /**
         * Load all frontend logic.
         */
        $this->frontEndLoader->init();
    }
}
