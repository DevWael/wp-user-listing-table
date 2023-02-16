<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\API;

class Users
{
    private EndPoint $endPoints;
    private int $id;

    public function __construct(EndPoint $endPoints = null, int $id = null)
    {
        $this->endPoints = $endPoints ?? new EndPoint();
        $this->id = $id;
    }

    /**
     * @throws \JsonException
     */
    public function users(): array
    {
        $requestType = 'list';
        $response = $this->makeRequest($requestType);
        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \Exception
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

        $response = wp_remote_request($api['url'], [
            'method' => $api['url'],
        ]);
        if (is_wp_error($response)) {
            throw new \RuntimeException($response->get_error_message());
        }

        return wp_remote_retrieve_body($response);
    }
}
