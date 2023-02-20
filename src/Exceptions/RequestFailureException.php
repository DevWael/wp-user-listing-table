<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Exceptions;

/**
 * This exception will be thrown when the API returns any other than 2xx.
 *
 * @package WpUserListingTable\Exceptions
 */
class RequestFailureException extends \Exception
{
    public function __construct(
        string $message,
        int $code = 0,
        \Throwable $previous = null
    ) {

        parent::__construct($message, $code, $previous);
    }
}
