<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

/**
 * This class provides the caching functionality for the API.
 */
class UsersCache implements Cache
{
    /**
     * Get cached data.
     *
     * @param string $key
     * @return array of cached data if existing.
     */
    public function get(string $key): array
    {
        $key = 'WPUL_' . $key;
        $data = \get_transient($key);
        // Check if the key exists in the cache.
        if ($data && \is_array($data)) {
            // If it does, return the cached data.
            return $data;
        }

        // If it doesn't, return empty array.
        return [];
    }

    /**
     * Save the provided data to cache storage.
     *
     * @param string $key
     * @param array $data data to be cached
     * @param int $expiration
     * @return void
     */
    public function set(string $key, array $data, int $expiration = 3600): void
    {
        $key = 'WPUL_' . $key;
        // Add the data to the cache with the given key.
        \set_transient($key, $data, $expiration);
    }

    //todo: add purge function
}
