<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\Exceptions;

use WpUserListingTable\Exceptions\TimedOutException;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class TimedOutExceptionTest extends AbstractUnitTestCase
{
    /**
     * Test if the given message equals the expected
     *
     * @return void
     */
    public function testExceptionMessage(): void
    {
        try {
            throw new TimedOutException("Time out");
        } catch (TimedOutException $exception) {
            $this->assertEquals("Time out", $exception->getMessage());
        }
    }

    /**
     * Test if the given exception code is equal to the expected
     *
     * @return void
     */
    public function testExceptionCode(): void
    {
        try {
            throw new TimedOutException("Time out.", 408);
        } catch (TimedOutException $exception) {
            $this->assertEquals(408, $exception->getCode());
        }
    }

    /**
     * Test if the given previous exception message can be retrieved
     *
     * @return void
     */
    public function testPreviousException(): void
    {
        try {
            $previousException = new \Exception("Time out.");
            throw new TimedOutException("Time out.", 408, $previousException);
        } catch (TimedOutException $newException) {
            $this->assertSame($previousException, $newException->getPrevious());
        }
    }
}
