<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\I18n;

use WpUserListingTable\I18n\Languages;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class LanguagesTest extends AbstractUnitTestCase
{
    /**
     * Test loading languages.
     *
     * @return void
     */
    public function testLoadTextDomain(): void
    {
        \WP_Mock::userFunction('plugin_basename')->andReturn('plugin_basename');
        \WP_Mock::userFunction('load_plugin_textdomain');
        try {
            $languages = new Languages();
            $languages->loadTextDomain();
        } catch (\InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * Test if the init method contains required hooks
     *
     * @return void
     */
    public function testinit(): void
    {
        $languages = new Languages();
        \WP_Mock::expectActionAdded('plugins_loaded', [$languages, 'loadTextDomain']);
        $languages->init();
        \WP_Mock::assertActionsCalled();
    }
}
