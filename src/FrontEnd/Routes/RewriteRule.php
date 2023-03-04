<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Routes;

use WpUserListingTable\FrontEnd\Routes\Templates\Template;
use WpUserListingTable\FrontEnd\Routes\Templates\UsersTableTemplate;

/**
 * The class that responsible for loading all custom rewrite rules logic.
 *
 * @package WpUserListingTable\FrontEnd\Routes
 */
class RewriteRule implements Rule
{
    /**
     * @var Template $template UsersTableTemplate class instance or any class implements Template
     */
    private Template $template;

    /**
     * RewriteRule constructor.
     *
     * @param  Template|null  $template  UsersTable interface
     */
    public function __construct(Template $template = null)
    {
        $templateObject = $template ?? new UsersTableTemplate();
        /**
         * Instance of UsersTableTemplate class to load the template.
         */
        $this->template = \apply_filters(
            'wp_users_table_template_object',
            $templateObject
        );
    }

    /**
     * Register custom WordPress rewrite rule to catch the page URL
     * and let the WP knows how to deal with it.
     *
     * @return void
     */
    public function register(): void
    {
        /**
         * String Template Regex
         *
         * @see /plugin/src/FrontEnd/Templates/UsersTableTemplate.php
         */
        $regex = \apply_filters(
            'wp_users_table_template_regex',
            $this->template->templateRegex()
        );

        /**
         * String Template Query
         *
         * @see /plugin/src/FrontEnd/Templates/UsersTableTemplate.php
         */
        $query = \apply_filters(
            'wp_users_table_template_query',
            $this->template->templateQuery()
        );

        /**
         * Adds a rewrite rule to WordPress to transforms it to query vars
         */
        \add_rewrite_rule($regex, $query, 'top');

        /**
         * Set the rewrite rule as added
         */
        \update_option('wpul-rules-flag', true, false);

        /**
         * todo add comment for this action
         */
        \do_action(
            'wp_users_table_rewrite_rule_added',
            $regex,
            $query,
            'top'
        );
    }

    /**
     * Add table_template to WordPress main query.
     *
     * @param  array  $vars
     *
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

    /**
     * Provide template path for WordPress.
     *
     * @param  string  $template  path
     *
     * @return string path
     */
    public function loadTemplate(string $template): string
    {
        if (\get_query_var('table_template') === 'user-listing-table') {
            $this->adjustWpQueryParam();
            /**
             * String template path.
             * Set the path of the template that will be loaded
             * when the custom url is being visited.
             * Can be overridden with it the active theme under the following directory:
             * /user-listing-table/users-table.php
             */
            return \apply_filters(
                'wp_user_listing_template_path',
                $this->template->templatePath()
            );
        }

        return $template;
    }

    /**
     * Set the rule page title.
     *
     * @param  array  $titleParts strings of the page title
     *
     * @return array strings of the page title
     */
    public function templateTitle(array $titleParts): array
    {
        if (\get_query_var('table_template') === 'user-listing-table') {
            $title = esc_html__('Users Table', 'wp-user-listing');
            $titleParts['title'] = apply_filters('wp_user_listing_template_tab_title', $title);
        }

        return $titleParts;
    }

    /**
     * Set the is_home() to false on the users table page.
     *
     * @return void
     */
    private function adjustWpQueryParam(): void
    {
        global $wp_query;
        $wp_query->is_home = false;
    }

    /**
     * Attach the class functions to WordPress hooks
     *
     * @return void
     */
    public function init(): void
    {
        \add_action('init', [$this, 'register']);
        \add_filter('query_vars', [$this, 'registerQueryVar']);
        \add_filter('template_include', [$this, 'loadTemplate']);
        \add_filter('document_title_parts', [$this, 'templateTitle']);
    }
}
