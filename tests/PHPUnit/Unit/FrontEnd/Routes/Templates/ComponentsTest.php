<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\FrontEnd\Routes\Templates;

use WpUserListingTable\FrontEnd\Routes\Templates\Components;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class ComponentsTest extends AbstractUnitTestCase
{
    /**
     * Test when the installed theme is block theme, the header should be loaded
     * from the block theme
     *
     * @return void
     */
    public function testHeaderBlockTheme(): void
    {
        \WP_Mock::userFunction('wp_is_block_theme')->once()->andReturn(true);
        \WP_Mock::userFunction('language_attributes')->once()->andReturn('en');
        \WP_Mock::userFunction('bloginfo')->once()->andReturn('en');
        \WP_Mock::userFunction('wp_head')->once();
        \WP_Mock::userFunction('body_class')->once();
        \WP_Mock::userFunction('wp_body_open')->once();
        \WP_Mock::userFunction('block_header_area')->once();

        ob_start();
        Components::header();
        $data = ob_get_clean();
        $this->assertStringStartsWith('<!doctype html>', $data);
    }

    /**
     * Test when the installed theme is not a block theme, the header should be loaded
     * from the theme header.php file
     *
     * @return void
     */
    public function testHeaderNonBlockTheme(): void
    {
        \WP_Mock::userFunction('wp_is_block_theme')->once()->andReturn(false);
        \WP_Mock::userFunction('get_header')->once()->andReturnUsing(static function (): void {
            echo '<!doctype html>';
        });

        ob_start();
        Components::header();
        $data = ob_get_clean();
        $this->assertStringStartsWith('<!doctype html>', $data);
    }

    /**
     * Test when the installed theme is block theme, the footer should be loaded
     * from the block theme
     *
     * @return void
     */
    public function testFooterBlockTheme(): void
    {
        \WP_Mock::userFunction('wp_is_block_theme')->once()->andReturn(true);
        \WP_Mock::userFunction('block_footer_area')
            ->once()
            ->andReturnUsing(static function (): void {
                echo '</div>';
            });
        \WP_Mock::userFunction('wp_footer')->once();

        ob_start();
        Components::footer();
        $data = ob_get_clean();
        $this->assertStringStartsWith('</div>', $data);
    }

    /**
     * Test when the installed theme is not a block theme, the footer should be loaded
     * from the theme footer.php file
     *
     * @return void
     */
    public function testFooterNonBlockTheme(): void
    {
        \WP_Mock::userFunction('wp_is_block_theme')->once()->andReturn(false);
        \WP_Mock::userFunction('get_footer')->once()->andReturnUsing(static function (): void {
            echo '</html>';
        });

        ob_start();
        Components::footer();
        $data = ob_get_clean();
        $this->assertStringEndsWith('</html>', $data);
    }
}
