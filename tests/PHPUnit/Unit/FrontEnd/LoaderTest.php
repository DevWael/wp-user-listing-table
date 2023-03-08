<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\FrontEnd;

use WpUserListingTable\FrontEnd\Loader;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class LoaderTest extends AbstractUnitTestCase
{
    /**
     * Test if the init method contains required custom hook
     *
     * @return void
     */
    public function testInit()
    {
        $loader = new Loader();
        \WP_Mock::expectAction(
            'wp_users_table_plugin_frontend_loaded'
        );
        $loader->init();
        \WP_Mock::assertActionsCalled();
    }
}
