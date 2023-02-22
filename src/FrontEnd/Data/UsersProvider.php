<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Data;

use WpUserListingTable\API\Users;
use WpUserListingTable\API\Cache;
use WpUserListingTable\API\UsersCache;
use WpUserListingTable\API\UsersClient;

/**
 * This class will provide the data to the frontend.
 *
 * @package WpUserListingTable\FrontEnd\Data
 */
class UsersProvider implements Provider
{
    /**
     * UsersClient implementation object
     *
     * @var UsersClient|Users
     */
    private UsersClient $client;

    /**
     * Cache implementation object
     *
     * @var Cache|Users
     */
    private Cache $cache;

    private const CACHE_KEY = 'wp_user_listing';

    public function __construct(
        UsersClient $client = null,
        Cache $cache = null
    ) {

        $this->client = \apply_filters(
            'wp_users_table_data_provider_users_object',
            $client ?? new Users()
        );
        $this->cache = \apply_filters(
            'wp_users_table_data_provider_cache_object',
            $cache ?? new UsersCache()
        );
    }

    /**
     * Get the users from the API.
     *
     * @return array Users array.
     * @throws \Throwable will throw exception if something goes wrong.
     */
    public function fetchUsers(): array
    {
        $data = $this->cache->get(self::CACHE_KEY);
        if (empty($data)) {
            $data = $this->client->users();
            $this->cache->set(self::CACHE_KEY, $data);
        }

        return $data;
    }

    /**
     * Get all users array and catch exceptions thrown.
     *
     * @return array
     */
    public function usersList(): array
    {
        try {
            $list = $this->fetchUsers();
        } catch (\Throwable $e) {
            return [];
        }

        return $list;
    }
}
