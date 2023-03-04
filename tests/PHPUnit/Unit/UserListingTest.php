<?php # -*- coding: utf-8 -*-

namespace PHPUnit\Unit;

use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;
use WpUserListingTable\UserListing;

class UserListingTest extends AbstractUnitTestCase
{
    /**
     * Test when the wp_installing returns true, the execution will stop.
     *
     * @return void
     */
    public function testWpInstallingPreventExecution(): void
    {
        /**
         * Mock wp_installing function
         */
        \WP_Mock::userFunction('wp_installing')->twice()->andReturn(true);
        $userListing = UserListing::instance();
        $result = $userListing->init();
        $this->assertNull($result);
    }

    /**
     * Test when the wp_installing returns false, the execution will continue with no errors.
     *
     * @return void
     */
    public function testNoWpInstalling(): void
    {
        /**
         * Mock is_admin function
         */
        \WP_Mock::userFunction('is_admin')->once()->andReturn(true);
        try {
            $userListing = UserListing::instance();
            $userListing->init();
        } catch (\Throwable $exception) {
            $this->fail($exception->getMessage());
        }
        $this->assertTrue(TRUE);
    }
}