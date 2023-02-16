<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

/**
 * This class is responsible for all the admin side logic
 */
class AdminPage
{
    /**
     * @var AdminPageView AdminPageView instance.
     */
    private AdminPageView $view;

    /**
     * View constructor.
     */
    public function __construct(AdminPageView $view = null)
    {
        $this->view = $view ?? new AdminPageView();
    }

    /**
     * @return string print the nonce field to be used in the options page form.
     */
    private function nonce(): string
    {
        return wp_nonce_field();
    }

    /**
     * Register the options page with the WordPress.
     */
    public function register(): bool
    {
        $hook = add_options_page(
            $this->view->name(),
            $this->view->name(),
            $this->view->cap(),
            $this->view->slug(),
            function () {
                $this->view->render(
                    $this->nonce()
                );
            }
        );

        add_action('load-' . $hook, [$this, 'update']);

        return true;
    }

    /**
     * Save options page fields and settings.
     *
     * @return void
     */
    public function update(): void
    {
        //todo: to be implemented.
    }

    /**
     * Hook the functionality to WordPress.
     *
     * @return void
     */
    public function init(): void
    {
        add_action('admin_menu', [$this, 'register']);
    }
}
