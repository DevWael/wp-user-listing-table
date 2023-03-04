<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Routes\Templates;

/**
 * This class provide template information like
 * 1. Template file path.
 * 2. Template regex.
 * 3. Template query.
 *
 * @package WpUserListingTable\FrontEnd\Routes\Templates
 */
class UsersTableTemplate implements Template
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
        $template = \locate_template('user-listing-table/users-table.php');

        /**
         * Check if the file located in the active theme, then load it.
         */
        if ($template) {
            return $template;
        }

        /**
         * Load the plugin provided template file.
         */
        return dirname(__FILE__, 5) . '/templates/users-table.php';
    }

    /**
     * @return string template regex to be used in add_rewrite_rule function
     */
    public function templateRegex(): string
    {
        $regex = \get_option('wpul-table-slug', 'user-listing-table');
        return '^' . \untrailingslashit(\sanitize_text_field($regex)) . '$';
    }

    /**
     * @return string template query to be used in add_rewrite_rule function
     */
    public function templateQuery(): string
    {
        return 'index.php?table_template=user-listing-table';
    }
}
