<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

/**
 * This class provides the caching functionality for the API.
 *
 * @package WpUserListingTable\API
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
            return \apply_filters('wp_users_table_get_cached_data', $data, $key);
        }

        // If it doesn't, return empty array.
        return \apply_filters('wp_users_table_get_cached_data', [], $key);
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
        \set_transient(
            $key,
            \apply_filters('wp_users_table_set_cache_data', $data),
            \apply_filters('wp_users_table_cache_expiration_time', $expiration, $data, $key)
        );
    }

    public function purge(string $key): void
    {
        $key = 'WPUL_' . $key;
        \delete_transient($key);
    }
}
