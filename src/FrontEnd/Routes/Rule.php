<?php

namespace WpUserListingTable\FrontEnd\Routes;

/**
 * Interface Rule
 *
 * @package WpUserListingTable\FrontEnd\Routes
 */
interface Rule
{
    /**
     * Register the custom rewrite rule to WordPress.
     *
     * @return void
     */
    public function register(): void;

    /**
     * Register query var to use it with the template.
     *
     * @param array $vars
     * @return array
     */
    public function registerQueryVar(array $vars): array;

    /**
     * Provide the template file path for WordPress.
     *
     * @param string $template path.
     * @return string path.
     */
    public function loadTemplate(string $template): string;
}
