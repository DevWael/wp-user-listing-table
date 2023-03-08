<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\FrontEnd\Assets;

use WpUserListingTable\FrontEnd\Assets\AssetsLoader;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class AssetsLoaderTest extends AbstractUnitTestCase
{
    /**
     * Test if load js method works when query var equals user-listing-table
     *
     * @return void
     */
    public function testLoadJSWorksIfQueryVarEqualsString()
    {
        \WP_Mock::userFunction('get_query_var', [
            'args' => 'table_template',
            'return' => 'user-listing-table',
        ]);
        \WP_Mock::userFunction('plugin_dir_url', [
            'return' => '/',
        ]);
        \WP_Mock::userFunction('wp_enqueue_script');
        \WP_Mock::userFunction('wp_localize_script');
        \WP_Mock::userFunction('admin_url');
        \WP_Mock::userFunction('wp_create_nonce');
        \WP_Mock::expectAction('wp_users_table_load_js_assets');
        $assetsLoader = new AssetsLoader();
        $assetsLoader->loadJS();
        \WP_Mock::assertActionsCalled();
    }

    /**
     * Test if load css method works when query var equals user-listing-table
     *
     * @return void
     */
    public function testLoadCSSWorksIfQueryVarEqualsString(): void
    {
        \WP_Mock::userFunction('get_query_var', [
            'args' => 'table_template',
            'return' => 'user-listing-table',
        ]);
        \WP_Mock::userFunction('plugin_dir_url', [
            'return' => '/',
        ]);
        \WP_Mock::userFunction('wp_enqueue_style');
        \WP_Mock::expectAction('wp_users_table_load_css_assets');
        $assetsLoader = new AssetsLoader();
        $assetsLoader->loadCSS();
        \WP_Mock::assertActionsCalled();
    }

    /**
     * Test if the init method contains required hooks
     *
     * @return void
     */
    public function testInitMethod(): void
    {
        $assetsLoader = new AssetsLoader();
        \WP_Mock::expectActionAdded(
            'wp_enqueue_scripts',
            [$assetsLoader, 'loadCSS']
        );
        \WP_Mock::expectActionAdded(
            'wp_enqueue_scripts',
            [$assetsLoader, 'loadJS']
        );
        $assetsLoader->init();
        \WP_Mock::assertActionsCalled();
    }
}
