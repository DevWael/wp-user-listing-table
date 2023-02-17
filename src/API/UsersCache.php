<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

/**
 * This class provides the caching functionality for the API.
 */
class UsersCache
{
    /**
     * @var string cache key
     */
    private string $key;

    /**
     * @var int $expiration expiration in seconds
     */
    private $expiration;

    /**
     * @param string $key cache key
     * @param int $expiration expiration in seconds (default = 1 HOUR in seconds)
     */
    public function __construct(string $key, int $expiration = 3600)
    {
        $this->key = $key;
        $this->expiration = $expiration;
    }

    /**
     * @return array of cached data if existing.
     */
    public function get(): array
    {
        $data = get_transient($this->key);
        // Check if the key exists in the cache.
        if ($data) {
            // If it does, return the cached data.
            return $data;
        }

        // If it doesn't, return empty array.
        return [];
    }

    /**
     * Cache the provided data
     * @param array $data data to be cached
     * @return void
     */
    public function set(array $data): void
    {
        // Add the data to the cache with the given key.
        set_transient($this->key, $data, $this->expiration);
    }

    //todo: add purge function
}
