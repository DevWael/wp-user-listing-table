<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

/**
 * This class provides the functionality to contact the API to get the users data.
 */
class Users implements UsersClient
{
    /**
     * @var ApiEndpoint instance of EndPoint class
     */
    private ApiEndpoint $endPoints;

    /**
     * @var int|null user ID
     */
    private ?int $id;

    /**
     * @param ApiEndpoint|null $endPoints instance of EndPoint class
     * @param int|null $id user ID
     */
    public function __construct(ApiEndpoint $endPoints = null, int $id = null)
    {
        $this->endPoints = \apply_filters(
            'wp_users_table_endpoint_object',
            $endPoints ?? new EndPoint()
        );
        $this->id = $id;
    }

    /**
     * Get list of users data.
     *
     * @throws \JsonException if failed to decode the response
     */
    public function users(): array
    {
        $requestType = 'list';
        $response = $this->makeRequest($requestType);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get list of users data.
     *
     * @throws \JsonException if failed to decode the response
     * @throws \InvalidArgumentException if no user ID provided in the instance.
     */
    public function userById(): array
    {
        if ($this->id === null) {
            throw new \InvalidArgumentException('User ID cannot be null.');
        }
        $requestType = 'single';
        $response = $this->makeRequest($requestType);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Make the request to the remote API.
     *
     * @param string $requestType set request type to single or list to fetch users.
     * @return string JSON data from the API.
     *
     * @throws \RuntimeException if failed to contact the remote API.
     */
    private function makeRequest(string $requestType): string
    {
        switch ($requestType) {
            case 'single':
                $api = $this->endPoints->single($this->id);
                break;
            case 'list':
            default:
                $api = $this->endPoints->list();
                break;
        }

        $response = \wp_remote_request($api['url'], [
            'method' => $api['url'], //set the request method like (GET, POST, etc)
        ]);
        if (\is_wp_error($response)) {
            //Failed to contact the API
            throw new \RuntimeException($response->get_error_message());
        }

        return \wp_remote_retrieve_body($response);
    }
}
