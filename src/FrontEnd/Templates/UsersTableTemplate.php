<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Templates;

class UsersTableTemplate
{
    public function templatePath(): string
    {
        $path = PLUGIN_PATH . 'templates/users-table.php';
        $themeFile = get_template_directory() . '/user-listing-table/users-table.php';
        if (file_exists($themeFile)) {
            $path = $themeFile;
        }

        return $path;
    }

    public function templateRegex(): string
    {
        return '^user-listing-table/?';
    }

    public function templateQuery(): string
    {
        return 'index.php?table_template=user-listing-table';
    }
}
