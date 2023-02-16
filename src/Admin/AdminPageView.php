<?php

// -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\Admin;

/**
 * Class responsible for the options page information.
 */
class AdminPageView
{
    /**
     * @return string options page name
     */
    public function name(): string
    {
        return esc_html__('User Listing Settings', 'wp-user-listing');
    }

    /**
     * @return string options page access capability
     */
    public function cap(): string
    {
        return 'manage_options';
    }

    /**
     * @return string options page slug
     */
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
            <form method="post" action="<?php echo esc_url($url) ?>" class="user-listing-form" id="user-listing-form">

                <p class="submit clear">
                    <?php echo $nonce ?>
                    <input type="submit"
                           name="submit"
                           id="submit"
                           class="user-listing-form-field__submit button button-primary"
                           value="<?php echo esc_attr__('Save Changes', 'wp-user-listing') ?>"
                    />
                </p>
            </form>
        </div>
        <?php
    }
}
