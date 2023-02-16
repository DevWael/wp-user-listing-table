<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd;

use WpUserListingTable\FrontEnd\Assets\AssetsLoader;
use WpUserListingTable\FrontEnd\Templates\UsersTableTemplate;

class View
{
    private UsersTableTemplate $template;
    private AssetsLoader $assets;

    /**
     * View constructor.
     */
    public function __construct(UsersTableTemplate $template = null, AssetsLoader $assets = null)
    {
        $templateObject = $template ?? new UsersTableTemplate();
        $this->template = \apply_filters('wp_users_table_template_object', $templateObject);

        $assetsObject = $assets ?? new AssetsLoader(PLUGIN_NAME, PLUGIN_VERSION);
        $this->assets = \apply_filters('wp_users_table_assets_object', $assetsObject);
    }

    public function rewriteRule(): void
    {
        $regex = \apply_filters('wp_users_table_template_regex', $this->template->templateRegex());
        $query = \apply_filters('wp_users_table_template_query', $this->template->templateQuery());
        \add_rewrite_rule($regex, $query, 'top');
    }

    public function registerQueryVar(array $vars): array
    {
        $vars[] = 'table_template';
        return \apply_filters('wp_users_table_template_query_vars', $vars);
    }

    public function loadTemplate(string $template): string
    {
        if (\get_query_var('table_template') === 'user-listing-table') {
            return \apply_filters('wp_user_listing_template_path', $this->template->templatePath());
        }

        return $template;
    }

    public function init(): void
    {
        \add_action('init', [$this, 'rewriteRule']);
        \add_filter('query_vars', [$this, 'registerQueryVar']);
        \add_filter('template_include', [$this, 'loadTemplate']);
        \add_action('wp_enqueue_scripts', [$this->assets, 'loadCSS']);
        \add_action('wp_enqueue_scripts', [$this->assets, 'loadJS']);
    }
}
