<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace WpUserListingTable\Admin;

/**
 * This class provides the get request data.
 *
 * @package WpUserListingTable\Admin
 */
class Request
{
    /**
     * Get $_GET request parameters.
     *
     * @return array $_GET request parameters
     */
    public function get(): array
    {
        // phpcs:disable]
        return $_GET;
        // phpcs:enable
    }
}
