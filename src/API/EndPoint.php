<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

/**
 * This class defines the endpoints for the API calls
 *
 * @package WpUserListingTable\API
 */
class EndPoint implements ApiEndpoint
{
    /**
     * @return string the API host name.
     */
    public function host(): string
    {
        return 'https://jsonplaceholder.typicode.com';
    }

    /**
     * @return string[] array of strings for the request method and endpoint for listing the users
     */
    public function list(): array
    {
        return [
            'type' => 'GET',
            'url' => $this->host() . '/users',
        ];
    }

    /**
     * @param int $id user ID
     * @return string[] array of strings for the request method and endpoint for listing one user
     */
    public function single(int $id): array
    {
        return [
            'type' => 'GET',
            'url' => $this->host() . '/users/' . $id,
        ];
    }
}
