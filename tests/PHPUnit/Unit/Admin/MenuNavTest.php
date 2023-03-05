<?php # -*- coding: utf-8 -*-

namespace PHPUnit\Unit\Admin;

use WpUserListingTable\Admin\MenuNav;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class MenuNavTest extends AbstractUnitTestCase
{
    /**
     * Test navMenuMetaBox method Not throws an exception
     *
     * @return void
     */
    public function testNavMenuMetaBox(): void
    {
        \WP_Mock::userFunction('add_meta_box');
        try {
            $menuNav = new MenuNav();
            $menuNav->navMenuMetaBox();
        } catch (\Throwable $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * Test nav menu content have the correct html output.
     *
     * @return void
     */
    public function testNavMenuContent(): void
    {
        \WP_Mock::userFunction('home_url')->once()->andReturn('https://example.com');
        ob_start();
        $menuNav = new MenuNav();
        $menuNav->navMenuContent();
        $output = ob_get_clean();
        $this->assertStringStartsWith('<div id="users-list-link"',$output);
        $this->assertStringEndsWith('</div>',$output);
    }

    /**
     * Test if the init method contains required hooks
     *
     * @return void
     */
    public function testInit(): void
    {
        $menuNav = new MenuNav();
        \WP_Mock::expectActionAdded('admin_init',[$menuNav,'navMenuMetaBox']);
        $menuNav->init();
        \WP_Mock::assertActionsCalled();
    }
}