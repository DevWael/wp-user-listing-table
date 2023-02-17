<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

putenv('TESTS_PATH=' . __DIR__);
putenv('LIBRARY_PATH=' . dirname(__DIR__));

$vendor = dirname(__FILE__, 3) . '/vendor';

if (! realpath($vendor)) {
    die('Please do composer install before running tests.');
}

require_once $vendor . '/antecedent/patchwork/Patchwork.php';
require_once $vendor . '/autoload.php';
// Bootstrap WP_Mock to initialize built-in features
WP_Mock::bootstrap();

unset($vendor);
