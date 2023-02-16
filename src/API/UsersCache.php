<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

class UsersCache
{
    private string $key;
    /**
     * @var int
     */
    private $expiration;

    public function __construct(string $key, int $expiration = HOUR_IN_SECONDS)
    {
        $this->key = $key;
        $this->expiration = $expiration;
    }

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

    public function set(string $key, array $data): void
    {
        // Add the data to the cache with the given key.
        set_transient($this->key, $data, $this->expiration);
    }
}
