<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

/**
 * Add users table to WordPress menu items in menu dashboard
 * to make it easier to add users table link to the nav menu.
 *
 * Adapted from:
 * https://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
 */
class MenuNav
{
    /**
     * Add metabox to the menus list of the WordPress
     * to display the users table menu item.
     *
     * @return void
     */
    public function navMenuMetaBox()
    {
        add_meta_box(
            'users-list-table-box',
            __('Users Table'),
            [$this, 'navMenuContent'],
            'nav-menus',
            'side',
            'low'
        );
    }

    /**
     * Display the menu item html.
     *
     * @return void
     */
    public function navMenuContent(): void
    {
        ?>
        <div id="users-list-link" class="users-list-link">
            <div id="tabs-panel-users-list-table"
                 class="tabs-panel tabs-panel-active">
                <ul id="users-list-table-inputs"
                    class="categorychecklist form-no-clear">
                    <li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox"
                                   name="menu-item[-1][menu-item-object-id]"
                                   value="-1"> <?php
                                    esc_html_e('Users Table') ?>
                        </label>
                        <input type="hidden" class="menu-item-type"
                               name="menu-item[-1][menu-item-type]"
                               value="custom">
                        <input type="hidden" class="menu-item-title"
                               name="menu-item[-1][menu-item-title]"
                               value="<?php
                                esc_attr_e('Users Table') ?>">
                        <input type="hidden" class="menu-item-url"
                               name="menu-item[-1][menu-item-url]" value="<?php
                                echo esc_url(home_url('/user-listing-table')) ?>">
                        <input type="hidden" class="menu-item-classes"
                               name="menu-item[-1][menu-item-classes]"
                               value="users-listing-table">
                    </li>
                </ul>
            </div>
            <p class="button-controls wp-clearfix"
               data-items-type="users-list-link">
                <span class="list-controls hide-if-no-js">
                    <input type="checkbox" id="users-table-tab"
                           class="select-all">
                    <label for="users-table-tab"><?php
                        esc_html_e('Select All'); ?></label>
                </span>
                <span class="add-to-menu">
                    <input type="submit"
                           class="button-secondary submit-add-to-menu right"
                           value="<?php
                            esc_attr_e('Add to Menu'); ?>"
                           name="add-users-list-menu-item"
                           id="submit-users-list-link">
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
        <?php
    }

    /**
     * Hook the functionality to WordPress.
     *
     * @return void
     */
    public function init(): void
    {
        add_action('admin_init', [$this, 'navMenuMetaBox']);
    }
}
