<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

use Exception;
use RuntimeException;
use WpUserListingTable\Exceptions\NotFoundException;
use WpUserListingTable\Exceptions\TimedOutException;
use WpUserListingTable\Exceptions\UnAuthorizedException;

/**
 * This class provides the functionality to contact the API to get the users data.
 *
 * @package WpUserListingTable\API
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
    public function __construct(ApiEndpoint $endPoints = null)
    {
        $this->endPoints = \apply_filters(
            'wp_users_table_endpoint_object',
            $endPoints ?? new EndPoint()
        );
    }

    /**
     * Get list of users data.
     *
     * @throws \JsonException|NotFoundException if failed to decode the response
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
     */
    public function userById(int $id): array
    {
        $this->id = $id;
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
     * @throws RuntimeException if failed to contact the remote API.
     * @throws NotFoundException if the API responded with 404.
     * @throws Exception If the API responded with any other  error.
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
            'method' => $api['type'], //set the request method like (GET, POST, etc)
        ]);
        if (\is_wp_error($response)) {
            //Failed to contact the API
            throw new RuntimeException(esc_html__('Failed to contact the API', 'wp-user-listing'));
        }

        $statusCode = \wp_remote_retrieve_response_code($response);

        if (401 === $statusCode) {
            /**
             * Throw Exception if the request Unauthorized.
             */
            throw new UnAuthorizedException(esc_html__('Unauthorized request', 'wp-user-listing'));
        }

        if (404 === $statusCode) {
            /**
             * Throw Exception if the request not found.
             */
            throw new NotFoundException(esc_html__('Not found', 'wp-user-listing'));
        }

        if (408 === $statusCode) {
            /**
             * Throw Exception if the request timed out.
             */
            throw new TimedOutException(esc_html__('Request timeout', 'wp-user-listing'));
        }

        if ($statusCode < 200 || $statusCode > 299) {
            /**
             * Throw Exception if the API responded with status code other than 2xx.
             */
            throw new Exception(esc_html__('Something went wrong', 'wp-user-listing'));
        }

        return \wp_remote_retrieve_body($response);
    }
}
