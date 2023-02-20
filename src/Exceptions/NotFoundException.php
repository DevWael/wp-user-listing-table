<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Exceptions;

/**
 * This exception will be thrown when the API returns 404.
 *
 * @package WpUserListingTable\Exceptions
 */
class NotFoundException extends \Exception
{
    public function __construct(
        string $message,
        int $code = 0,
        \Throwable $previous = null
    ) {

        parent::__construct($message, $code, $previous);
    }
}
