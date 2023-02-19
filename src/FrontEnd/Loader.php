<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd;

use WpUserListingTable\FrontEnd\Assets\Assets;
use WpUserListingTable\FrontEnd\Assets\AssetsLoader;
use WpUserListingTable\FrontEnd\Routes\RewriteRule;
use WpUserListingTable\FrontEnd\Routes\Rule;

/**
 * The class that responsible for loading all frontend logic.
 *
 * @package WpUserListingTable\FrontEnd
 */
class Loader
{
    /**
     * @var Assets $assets AssetsLoader class instance.
     */
    private Assets $assets;

    /**
     * @var Rule $rewriteRule Rewrite rules class instance.
     */
    private Rule $rewriteRule;

    /**
     * Loader constructor.
     *
     * @param Assets|null $assets Assets interface compatible class
     * @param Rule|null $rewriteRule Rule interface compatible class
     */
    public function __construct(
        Assets $assets = null,
        Rule $rewriteRule = null
    ) {
        $assetsObject = $assets ?? new AssetsLoader();
        /**
         * Instance of AssetsLoader class to load the assets
         */
        $this->assets = \apply_filters('wp_users_table_assets_object', $assetsObject);

        $rewriteRuleObject = $rewriteRule ?? new RewriteRule();
        /**
         * Instance of RewriteRule class to load the assets
         */
        $this->rewriteRule = \apply_filters(
            'wp_users_table_rewrite_rule_object',
            $rewriteRuleObject
        );
    }

    /**
     * Attach the class functions to WordPress hooks
     * @return void
     */
    public function init(): void
    {
        \add_action('update_option_rewrite_rules', [$this->rewriteRule, 'register']);
        \add_filter('query_vars', [$this->rewriteRule, 'registerQueryVar']);
        \add_filter('template_include', [$this->rewriteRule, 'loadTemplate']);
        \add_action('wp_enqueue_scripts', [$this->assets, 'loadCSS']);
        \add_action('wp_enqueue_scripts', [$this->assets, 'loadJS']);
    }
}
