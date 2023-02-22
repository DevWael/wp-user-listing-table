<?php

namespace WpUserListingTable\API;

/**
 * Interface UsersClient
 *
 * @package WpUserListingTable\API
 */
interface UsersClient
{
    /**
     * Get the users data from the remote api
     *
     * @return array the users data on success.
     * @throws \Throwable throw some exceptions based on the returning errors.
     */
    public function users(): array;

    /**
     * Get the user data from the remote api with the provided user ID.
     *
     * @return array the single user data on success.
     * @throws \Throwable throw some exceptions based on the returning errors.
     */
    public function userById(int $id): array;
}
