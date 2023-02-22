<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

use WpUserListingTable\API\Cache;
use WpUserListingTable\API\Users;
use WpUserListingTable\API\UsersCache;
use WpUserListingTable\API\UsersClient;

class AjaxEndpoint
{
    /**
     * UsersClient implementation object
     *
     * @var UsersClient
     */
    private UsersClient $client;

    /**
     * Cache implementation object
     *
     * @var Cache
     */
    private Cache $cache;

    /**
     * Request implementation object
     *
     * @var Request
     */
    private Request $request;

    private const CACHE_KEY = 'wp_user_listing';

    public function __construct(
        UsersClient $client = null,
        Cache $cache = null,
        Request $request = null
    ) {

        $this->client = \apply_filters(
            'wp_users_table_ajax_users_object',
            $client ?? new Users()
        );
        $this->cache = \apply_filters(
            'wp_users_table_ajax_cache_object',
            $cache ?? new UsersCache()
        );
        $this->request = \apply_filters(
            'wp_users_table_ajax_request_endpoint_object',
            $request ?? new Request()
        );
    }

    /**
     * Receive the ajax request and run the functionality.
     *
     * @return void
     */
    public function wpRequest(): void
    {
        try {
            $this->checkNonce();
            $userId = $this->userId();
            if ($userId) {
                //go with provided user id.
                $singleUserData = $this->singleUserDetails($userId);
                $this->sendJsonSuccess($singleUserData);
            }
        } catch (\Throwable $exception) {
            $this->sendJsonError(
                $exception->getMessage() . $exception->getTraceAsString()
            );
        }
    }

    /**
     * Check if the nonce is valid and stop the execution if it fails.
     *
     * @return void
     */
    public function checkNonce(): void
    {
        $requestQuery = $this->request->get();
        $nonce = $requestQuery['nonce'] ?? null;
        if (false === \wp_verify_nonce($nonce)) {
            throw new \InvalidArgumentException(
                esc_html__('Failed to pass security check')
            );
        }
    }

    /**
     * Get the user id from the GET parameters.
     * Sanitize it and return an integer.
     *
     * @return int user id.
     */
    public function userId()
    {
        $requestQuery = $this->request->get();
        $userId = 0;
        if (isset($requestQuery['user_id'])) {
            $requestUserId = $requestQuery['user_id'];
            $requestUserId = \wp_unslash($requestUserId);
            if (! $this->isValidUserId($requestUserId)) {
                $this->sendJsonError(esc_html__('invalid user id'));
            }
            $userId = \absint($requestUserId);
        } else {
            $this->sendJsonError(esc_html__('user id is required'));
        }

        return $userId;
    }

    /**
     * Check if the user ID is a number.
     *
     * @param  string  $id  user id
     *
     * @return bool
     */
    private function isValidUserId(string $id): bool
    {
        return is_numeric($id) && $id > 0;
    }

    /**
     * Send the error message to the browser.
     *
     * @param  string  $message  error message.
     *
     * @return void
     */
    private function sendJsonError(string $message): void
    {
        \wp_send_json_error($message);
    }

    /**
     * Send the success message to the browser
     *
     * @param  array  $data  users data
     *
     * @return void
     */
    private function sendJsonSuccess(array $data): void
    {
        \wp_send_json_success($data);
    }

    /**
     * Get single user details from the API.
     *
     * @param  int  $id
     *
     * @return array user details
     * @throws \Throwable
     */
    public function singleUserDetails(int $id): array
    {
        $cacheKey = self::CACHE_KEY . '_' . $id;
        $data = $this->cache->get($cacheKey);
        if (empty($data)) {
            $data = $this->client->userById($id);
            $this->cache->set($cacheKey, $data);
        }

        return $this->escape($data);
    }

    /**
     * Walk through the user array and escape the data
     *
     * @param  array  $data
     *
     * @return array
     */
    public function escape(array $data): array
    {
        array_walk_recursive($data, static function (string &$value) {
            if ($value !== '') {
                $value = \esc_html($value);
            }
        });

        return $data;
    }

    /**
     * Hook the ajax functionality to the WordPress.
     * Using the action 'users_table_request'
     *
     * @return void
     */
    public function init(): void
    {
        \add_action('wp_ajax_nopriv_users_table_request', [$this, 'wpRequest']);
        \add_action('wp_ajax_users_table_request', [$this, 'wpRequest']);
    }
}
