<?php

namespace WpUserListingTable\API;

/**
 * Interface ApiEndpoint
 *
 * @package WpUserListingTable\API
 */
interface ApiEndpoint
{
    /**
     * Provide the domain of the API
     * @return string
     */
    public function host(): string;

    /**
     * Provide an array for the users list API and request method
     * @return array
     */
    public function list(): array;

    /**
     * Provide an array for the single user API and request method
     * @param int $id user ID.
     * @return array
     */
    public function single(int $id): array;
}
