<?php

namespace WpUserListingTable\API;

/**
 * Interface Cache
 *
 * @package WpUserListingTable\API
 */
interface Cache
{
    /**
     * Get the data from the cache storage.
     *
     * @return array the cached data.
     */
    public function get(): array;

    /**
     * Save data to cache storage.
     *
     * @param array $data data to be cached.
     * @return void
     */
    public function set(array $data): void;

    //todo: add purge function
}
