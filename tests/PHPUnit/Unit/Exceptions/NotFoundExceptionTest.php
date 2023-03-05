<?php

namespace PHPUnit\Unit\Exceptions;

use WpUserListingTable\Exceptions\NotFoundException;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class NotFoundExceptionTest extends AbstractUnitTestCase
{

    /**
     * Test if the given message equals the expected
     *
     * @return void
     */
    public function testExceptionMessage(): void
    {
        try {
            throw new NotFoundException("User not found.");
        } catch (NotFoundException $exception) {
            $this->assertEquals("User not found.", $exception->getMessage());
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
            throw new NotFoundException("User not found.", 404);
        } catch (NotFoundException $exception) {
            $this->assertEquals(404, $exception->getCode());
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
            throw new NotFoundException("User not found.", 404, $previousException);
        } catch (NotFoundException $newException) {
            $this->assertSame($previousException, $newException->getPrevious());
        }
    }
}