<?php # -*- coding: utf-8 -*-

namespace PHPUnit\Unit\Admin;

use WpUserListingTable\Admin\AdminPage;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class AdminPageTest extends AbstractUnitTestCase
{

    /**
     *  Test register method works properly.
     *
     * @return void
     */
    public function testRegister(): void
    {
        \WP_Mock::userFunction('add_options_page');
        try {
            $adminPage = new AdminPage();
            $adminPage->register();
        } catch (\Throwable $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * Test render method contains the correct html content.
     *
     * @return void
     */
    public function testRender(): void
    {
        \WP_Mock::userFunction('get_admin_page_title')->once()->andReturn('admin title');
        \WP_Mock::userFunction('settings_fields')->once();
        \WP_Mock::userFunction('do_settings_sections')->once();
        \WP_Mock::userFunction('submit_button')->once();
        ob_start();
        $adminPage = new AdminPage();
        $adminPage->render();
        $output = ob_get_clean();
        $this->assertStringStartsWith('<div class="wrap">',$output);
        $this->assertStringEndsWith('</div>',$output);
    }

    /**
     * Test settingsFields method works properly.
     *
     * @return void
     */
    public function testSettingsFields(): void
    {
        \WP_Mock::userFunction('add_settings_section')->once();
        \WP_Mock::userFunction('register_setting')->once();
        \WP_Mock::userFunction('add_settings_field')->once();
        try {
            $adminPage = new AdminPage();
            $adminPage->settingsFields();
        } catch (\Throwable $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * Test textField method contains the correct html content.
     *
     * @return void
     */
    public function testTextField(): void
    {
        $fieldArgs = [
            'label_for' => 'wpul-table-slug',
            'class' => 'wpul-slug-field',
            'name' => 'wpul-table-slug',
            'default' => 'user-listing-table',
            'required' => true,
            'description' => 'description message',
        ];
        \WP_Mock::userFunction('get_option')->once()->andReturn('admin title');
        ob_start();
        $adminPage = new AdminPage();
        $adminPage->textField($fieldArgs);
        $output = ob_get_clean();
        $this->assertStringStartsWith('<input required',$output);
        $this->assertStringEndsWith('description message</p>',$output);
    }

    /**
     * Test sanitize with given 4 characters string should return the old value or default value.
     *
     * @return void
     */
    public function testSanitizeSmallerValue(): void
    {
        \WP_Mock::userFunction('get_option')->once()->andReturnArg(1);
        \WP_Mock::userFunction('sanitize_text_field')->once()->andReturnArg(0);
        \WP_Mock::userFunction('add_settings_error')->once();

        $adminPage = new AdminPage();
        $result = $adminPage->sanitize('abcd');

        $this->assertStringContainsString('user-listing-table',$result);
    }

    /**
     * Test sanitize with given +50 characters string
     * should return the old value or default value.
     *
     * @return void
     */
    public function testSanitizeGreaterValue(): void
    {
        \WP_Mock::userFunction('get_option')->once()->andReturnArg(1);
        \WP_Mock::userFunction('sanitize_text_field')->once()->andReturnArg(0);
        \WP_Mock::userFunction('add_settings_error')->once();

        $adminPage = new AdminPage();
        $result = $adminPage->sanitize('we-should-have-string-with-more-than-50-characters.');

        $this->assertStringContainsString('user-listing-table',$result);
    }

    /**
     * Test sanitize with given correct string should return the same string.
     *
     * @return void
     */
    public function testSanitizeIdealValue(): void
    {
        \WP_Mock::userFunction('get_option')->once()->andReturnArg(1);
        \WP_Mock::userFunction('sanitize_text_field')->once()->andReturnArg(0);

        $adminPage = new AdminPage();
        $result = $adminPage->sanitize('user-listing-table');

        $this->assertStringContainsString('user-listing-table',$result);
    }

    /**
     * Test sanitize method with given bad regex value should
     * return the old value or default value
     *
     * @return void
     */
    public function testSanitizeBadRegexValue(): void
    {
        \WP_Mock::userFunction('get_option')->once()->andReturnArg(1);
        \WP_Mock::userFunction('sanitize_text_field')->once()->andReturnArg(0);

        $adminPage = new AdminPage();
        $result = $adminPage->sanitize('user-/listing##-table?');

        $this->assertStringContainsString('user-listing-table',$result);
    }

    /**
     * Test flushRewriteRules works correctly
     *
     * @return void
     */
    public function testFlushRewriteRules(): void
    {
        \WP_Mock::userFunction('delete_option');
        \WP_Mock::userFunction('flush_rewrite_rules');
        try {
            $adminPage = new AdminPage();
            $adminPage->flushRewriteRules();
        } catch (\Throwable $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * Test if the init method contains required hooks
     *
     * @return void
     */
    public function testInit(): void
    {
        $adminPage = new AdminPage();
        \WP_Mock::expectActionAdded('admin_init',[$adminPage,'settingsFields']);
        \WP_Mock::expectActionAdded('admin_menu',[$adminPage,'register']);
        \WP_Mock::expectActionAdded('update_option_wpul-table-slug',[$adminPage,'flushRewriteRules']);
        $adminPage->init();
        \WP_Mock::assertActionsCalled();
    }
}