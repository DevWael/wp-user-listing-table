<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\FrontEnd\Routes;

use WpUserListingTable\FrontEnd\Routes\RewriteRule;
use WpUserListingTable\FrontEnd\Routes\Templates\UsersTableTemplate;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class RewriteRuleTest extends AbstractUnitTestCase
{
    /**
     * Test if the register query var returns an array with the correct value
     *
     * @return void
     */
    public function testRegisterQueryVarAddsExpectedVar(): void
    {
        $vars = ['var1', 'var2'];
        $view = new RewriteRule();
        $result = $view->registerQueryVar($vars);
        $this->assertIsArray($result);
        $this->assertContains('table_template', $result);
    }

    /**
     * Test load template returns the correct value
     *
     * @return void
     */
    public function testLoadTemplateReturnsString(): void
    {
        global $wp_query;
        $wp_query = new \stdClass();
        $wp_query->is_home = true;

        \WP_Mock::userFunction('get_query_var')
            ->once()
            ->with('table_template')
            ->andReturn('user-listing-table');

        $template = \Mockery::mock(UsersTableTemplate::class);
        $template->shouldReceive('templatePath')->andReturn('/templates/users-table.php');

        $rulePath = new RewriteRule($template);
        $result = $rulePath->loadTemplate('');
        $this->assertStringEndsWith('/templates/users-table.php', $result);
    }

    /**
     * Test load template returns the same as prameter if the query var
     * is not equals user-listing-table
     *
     * @return void
     */
    public function testLoadTemplateReturnsStringWithoutQueryVar(): void
    {
        \WP_Mock::userFunction('get_query_var', [
            'times' => 1,
            'args' => 'table_template',
            'return' => '',
        ]);

        $view = new RewriteRule();
        $this->assertStringContainsString('test', $view->loadTemplate('test'));
    }

    /**
     * Test if the init method contains required hooks
     *
     * @return void
     */
    public function testInit(): void
    {
        $rewriteRule = new RewriteRule();
        \WP_Mock::expectActionAdded(
            'init',
            [$rewriteRule, 'register']
        );
        \WP_Mock::expectFilterAdded(
            'query_vars',
            [$rewriteRule, 'registerQueryVar']
        );
        \WP_Mock::expectFilterAdded(
            'template_include',
            [$rewriteRule, 'loadTemplate']
        );
        $rewriteRule->init();
        \WP_Mock::assertActionsCalled();
        \WP_Mock::assertFiltersCalled();
    }

    /**
     * Test if the Register method contains required custom actions
     *
     * @return void
     */
    public function testRegisterContainsAction(): void
    {
        \WP_Mock::userFunction('add_rewrite_rule');
        \WP_Mock::userFunction('update_option');
        $template = \Mockery::mock(UsersTableTemplate::class);
        $template->shouldReceive('templateRegex')
            ->andReturn('user-listing-table');
        $template->shouldReceive('templateQuery')
            ->andReturn('index.php?table_template=user-listing-table');
        $template->shouldReceive('templatePath')
            ->andReturn('users-table.php');

        $rewriteRule = new RewriteRule($template);
        \WP_Mock::expectAction(
            'wp_users_table_rewrite_rule_added',
            $template->templateRegex(),
            $template->templateQuery(),
            'top'
        );
        $rewriteRule->register();
        \WP_Mock::assertActionsCalled();
    }

    /**
     * Test if the template title adds the title key to the title parts array
     *
     * @return void
     */
    public function testTitlePartsHasTitleKey(): void
    {
        \WP_Mock::userFunction('get_query_var')
            ->with('table_template')
            ->andReturn('user-listing-table');
        $titleParts = [
            'link' => '',
            'query' => '',
        ];
        $rewriteRule = new RewriteRule();
        $result = $rewriteRule->templateTitle($titleParts);
        $this->assertArrayHasKey('title', $result);
    }
}
