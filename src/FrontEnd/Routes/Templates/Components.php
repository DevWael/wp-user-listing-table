<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\FrontEnd\Routes\Templates;

/**
 * This class is responsible for loading the header and footer components
 * for the front-end template.
 *
 * @package WpUserListingTable
 */
class Components
{
    /**
     * Check if the installed theme is block theme (FSE Themes).
     * If so, then we won't load the get_header() function as the theme doesn't
     * include any header.php file.
     * We will load the theme header block instead.
     *
     * @see https://core.trac.wordpress.org/ticket/55023
     *
     * @return void
     */
    public static function header(): void
    {
        $blockTheme = self::isBlockTheme();
        if ($blockTheme) {
            ?><!doctype html>
                <html <?php \language_attributes(); ?>>
                    <head>
                        <meta charset="<?php \bloginfo('charset'); ?>">
                                <?php \wp_head(); ?>
                    </head>
                    <body <?php \body_class(); ?>>
                        <?php \wp_body_open(); ?>
                        <div class="wp-site-blocks">
                            <header class="wp-block-template-part">
                                    <?php \block_header_area() ?>
                            </header><?php
        }
        /**
         * If it's traditional WP theme, then load the header.
         */
        if (!$blockTheme) {
            \get_header();
        }
    }

    /**
     * Check if the installed theme is block theme (FSE Themes).
     * If so, then we won't load the get_footer() function as the theme doesn't
     * include any footer.php file.
     * We will load the theme footer block instead.
     */
    public static function footer(): void
    {
        $blockTheme = self::isBlockTheme();
        if ($blockTheme) {
            \block_footer_area(); ?>
                    </div>
                    <?php \wp_footer(); ?>
                </body>
            </html><?php
        }
        /**
         * If it's traditional WP theme, then load the footer.
         */
        if (!$blockTheme) {
            \get_footer();
        }
    }

    private static function isBlockTheme(): bool
    {
        return \wp_is_block_theme();
    }
}