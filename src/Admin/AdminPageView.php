<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

class AdminPageView
{
    public function name(): string
    {
        return 'User Listing Settings';
    }

    public function cap(): string
    {
        return 'manage_options';
    }

    public function slug(): string
    {
        return 'user_listing_settings';
    }

    /**
     * Render the form html.
     */
    // phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    public function render(string $nonce): void
    {
        $url = add_query_arg(
            [
                'page' => $this->slug(),
            ],
            admin_url('options-general.php')
        );
        ?>
        <div class="wrap">
            <h2 class="settings__headline"><?php
                esc_html__('Settings') ?></h2>
            <form method="post" action="<?php
            echo esc_url($url) ?>"
                  class="inpsyde-form" id="inpsyde-form">

                <p class="submit clear">
                    <?php echo $nonce ?>
                    <input type="submit"
                           name="submit"
                           id="submit"
                           class="inpsyde-form-field__submit"
                           value="<?php echo esc_attr__('Save Changes', 'inpsyde-google-tag-manager') ?>"
                    />
                </p>
            </form>
        </div>
        <?php
    }
}
