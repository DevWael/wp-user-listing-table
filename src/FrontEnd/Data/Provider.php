<?php

// -*- coding: utf-8 -*-

namespace WpUserListingTable\FrontEnd\Data;

/**
 * Interface Provider
 *
 * @package WpUserListingTable\FrontEnd\Data
 */
interface Provider
{
    /**
     * Fetch the users list from the API
     * @return array
     */
    public function fetchUsers(): array;

    /**
     * Check if any exception is thrown and do the required preparation.
     * @return array
     */
    public function usersList(): array;
}
