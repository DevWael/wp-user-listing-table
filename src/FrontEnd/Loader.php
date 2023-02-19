<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd;

use WpUserListingTable\FrontEnd\Assets\Assets;
use WpUserListingTable\FrontEnd\Assets\AssetsLoader;
use WpUserListingTable\FrontEnd\Templates\UsersTable;
use WpUserListingTable\FrontEnd\Templates\UsersTableTemplate;

/**
 * The class that responsible for loading all frontend logic.
 *
 * @package WpUserListingTable
 */
class Loader
{
    /**
     * @var UsersTable $template UsersTableTemplate class instance.
     */
    private UsersTable $template;

    /**
     * @var Assets $assets AssetsLoader class instance.
     */
    private Assets $assets;

    /**
     * View constructor.
     *
     * @param UsersTable|null $template UsersTable interface
     * @param Assets|null $assets Assets interface
     */
    public function __construct(
        UsersTable $template = null,
        Assets $assets = null
    ) {

        $templateObject = $template ?? new UsersTableTemplate();
        /**
         * Instance of UsersTableTemplate class to load the template.
         */
        $this->template = \apply_filters('wp_users_table_template_object', $templateObject);

        $assetsObject = $assets ?? new AssetsLoader();
        /**
         * Instance of AssetsLoader class to load the assets
         */
        $this->assets = \apply_filters('wp_users_table_assets_object', $assetsObject);
    }

    /**
     * Register custom WordPress rewrite rule to catch the page URL
     * and let the WP knows how to deal witt it.
     *
     * @return void
     */
    public function rewriteRule(): void
    {
        /**
         * String Template Regex
         * @see /plugin/src/FrontEnd/Templates/UsersTableTemplate.php
         */
        $regex = \apply_filters('wp_users_table_template_regex', $this->template->templateRegex());

        /**
         * String Template Query
         * @see /plugin/src/FrontEnd/Templates/UsersTableTemplate.php
         */
        $query = \apply_filters('wp_users_table_template_query', $this->template->templateQuery());

        /**
         * Adds a rewrite rule to WordPress to transforms it to query vars
         */
        \add_rewrite_rule($regex, $query, 'top');
    }

    /**
     * Add table_template to WordPress main query.
     *
     * @param array $vars
     * @return array $vars
     */
    public function registerQueryVar(array $vars): array
    {
        $vars[] = 'table_template';

        /**
         * Array $vars
         * Add the ability to modify vars data.
         */
        return \apply_filters('wp_users_table_template_query_vars', $vars);
    }

    public function loadTemplate(string $template): string
    {
        if (\get_query_var('table_template') === 'user-listing-table') {
            /**
             * String template path.
             * Set the path of the template that will be loaded
             * when the custom url is being visited.
             * Can be overridden with it the active theme under the following directory:
             * /user-listing-table/users-table.php
             */
            return \apply_filters('wp_user_listing_template_path', $this->template->templatePath());
        }

        return $template;
    }

    /**
     * Attach the class functions to WordPress hooks
     * @return void
     */
    public function init(): void
    {
        \add_action('init', [$this, 'rewriteRule']);
        \add_filter('query_vars', [$this, 'registerQueryVar']);
        \add_filter('template_include', [$this, 'loadTemplate']);
        \add_action('wp_enqueue_scripts', [$this->assets, 'loadCSS']);
        \add_action('wp_enqueue_scripts', [$this->assets, 'loadJS']);
    }
}
