<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Templates;

/**
 * This class provide template information like
 * 1. Template file path.
 * 2. Template regex.
 * 3. Template query.
 */
class UsersTableTemplate implements UsersTable
{
    /**
     * Load template file path.
     * Can be overridden with it the active theme under the following directory:
     * /user-listing-table/users-table.php
     *
     * @return string template file path.
     */
    public function templatePath(): string
    {
        $path = dirname(__FILE__, 4) . '/templates/users-table.php';
        $themeFile = \get_template_directory() . '/user-listing-table/users-table.php';
        if (\file_exists($themeFile)) { //check if the file exists inside the active theme
            $path = $themeFile;
        }

        return $path;
    }

    /**
     * @return string template regex to be used in add_rewrite_rule function
     */
    public function templateRegex(): string
    {
        return '^user-listing-table/?';
    }

    /**
     * @return string template query to be used in add_rewrite_rule function
     */
    public function templateQuery(): string
    {
        return 'index.php?table_template=user-listing-table';
    }
}
