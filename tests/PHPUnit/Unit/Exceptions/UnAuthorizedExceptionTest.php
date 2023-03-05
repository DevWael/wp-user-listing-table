<?php # -*- coding: utf-8 -*-

namespace PHPUnit\Unit\Exceptions;

use WpUserListingTable\Exceptions\UnAuthorizedException;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class UnAuthorizedExceptionTest extends AbstractUnitTestCase
{
    /**
     * Test if the given message equals the expected
     *
     * @return void
     */
    public function testExceptionMessage(): void
    {
        try {
            throw new UnAuthorizedException("unauthorized access.");
        } catch (UnAuthorizedException $exception) {
            $this->assertEquals("unauthorized access.", $exception->getMessage());
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
            throw new UnAuthorizedException("unauthorized access.", 401);
        } catch (UnAuthorizedException $exception) {
            $this->assertEquals(401, $exception->getCode());
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
            $previousException = new \Exception("Previous exception.");
            throw new UnAuthorizedException("unauthorized access.", 401, $previousException);
        } catch (UnAuthorizedException $newException) {
            $this->assertSame($previousException, $newException->getPrevious());
        }
    }
}