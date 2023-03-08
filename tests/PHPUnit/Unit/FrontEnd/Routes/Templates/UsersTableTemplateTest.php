<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\FrontEnd\Routes\Templates;

use WpUserListingTable\FrontEnd\Routes\Templates\UsersTableTemplate;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class UsersTableTemplateTest extends AbstractUnitTestCase
{
    /**
     * Test the ability of loading the template file from inside the active theme.
     *
     * @return void
     */
    public function testTemplatePathThemeFile(): void
    {
        \WP_Mock::userFunction('locate_template')
            ->once()
            ->andReturn('user-listing-table/users-table.php');
        $usersTableTemplate = new UsersTableTemplate();
        $this->assertStringContainsString(
            'user-listing-table/users-table.php',
            $usersTableTemplate->templatePath()
        );
    }

    /**
     * Test the loading the template file from inside the plugin.
     *
     * @return void
     */
    public function testTemplatePathPluginFile(): void
    {
        \WP_Mock::userFunction('locate_template')->once()->andReturn('');
        $usersTableTemplate = new UsersTableTemplate();
        $this->assertStringEndsWith(
            '/templates/users-table.php',
            $usersTableTemplate->templatePath()
        );
    }

    /**
     * Test template regex returns the correct string.
     *
     * @return void
     */
    public function testTemplateRegexReturnCorrectString()
    {
        \WP_Mock::userFunction('get_option')->once()->andReturnArg(1);
        \WP_Mock::userFunction('sanitize_text_field')->andReturnArg(0);
        \WP_Mock::userFunction('untrailingslashit')->andReturn('user-listing-table');
        $usersTableTemplate = new UsersTableTemplate();
        $result = $usersTableTemplate->templateRegex();
        $this->assertStringStartsWith('^user-listing-table', $result);
    }

    /**
     * Test template query returns the correct string
     *
     * @return void
     */
    public function testTemplateQueryReturnString()
    {
        $usersTableTemplate = new UsersTableTemplate();
        $query = $usersTableTemplate->templateQuery();
        $this->assertIsString($query);
        $this->assertStringContainsString('index.php?table_template=', $query);
    }
}
