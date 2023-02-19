<?php

namespace WpUserListingTable\FrontEnd\Assets;

interface Assets
{
    /**
     * Load CSS style files.
     *
     * @return void
     */
    public function loadCSS(): void;

    /**
     * Load Javascript files.
     *
     * @return void
     */
    public function loadJS(): void;
}
