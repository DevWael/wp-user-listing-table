<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

class AdminPage
{
    private AdminPageView $view;

    /**
     * View constructor.
     */
    public function __construct(AdminPageView $view = null)
    {
        $this->view = $view ?? new AdminPageView();
    }

    private function nonce(): string
    {
        return wp_nonce_field();
    }

    /**
     * @return bool
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

    public function update(): void
    {
    }

    public function init(): void
    {
        add_action('admin_menu', [$this, 'register']);
    }
}
