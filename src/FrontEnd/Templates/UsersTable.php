<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Templates;

class UsersTable
{
    public static function templatePath(): string
    {
        $path = PLUGIN_PATH . 'templates/users-table.php';
        $themeFile = get_template_directory() . '/user-listing-table/users-table.php';
        if (file_exists($themeFile)) {
            $path = $themeFile;
        }

        return $path;
    }
}
