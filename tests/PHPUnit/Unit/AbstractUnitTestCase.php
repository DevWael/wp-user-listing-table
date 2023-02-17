<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit;

use PHPUnit\Framework\TestCase;

/**
 * This class will be inherited in all test classes to provide the setUp and tearDown functions.
 */
abstract class AbstractUnitTestCase extends TestCase
{
    /**
     * Sets up the environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        \WP_Mock::setUp();
    }

    /**
     * Tears down the environment.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        \WP_Mock::tearDown();
        parent::tearDown();
    }
}
