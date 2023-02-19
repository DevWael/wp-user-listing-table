<?php

namespace WpUserListingTable\FrontEnd\Templates;

/**
 * Interface UsersTable
 *
 * @package WpUserListingTable\FrontEnd\Templates
 */
interface UsersTable
{
    /**
     * This method should provide the path of the frontend template.
     *
     * @return string
     */
    public function templatePath(): string;

    /**
     * This method should provide the regex that will be provided to
     * the WP rewrite rule.
     *
     * @return string
     */
    public function templateRegex(): string;

    /**
     * This method should provide the query that will make the WP understand
     * what to do when the custom URL visited.
     *
     * @return string
     */
    public function templateQuery(): string;
}
