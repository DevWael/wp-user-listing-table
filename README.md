# WP User Listing Table

[![codecov](https://codecov.io/gh/DevWael/wp-user-listing-table/branch/master/graph/badge.svg?token=8CO5HHR57R)](https://codecov.io/gh/DevWael/wp-user-listing-table)


WP User Listing Table is a WordPress plugin that provides a front-end portal to preview the users being fetched from a remote API. It uses WP rewrite rules API to create a frontend page,  `wp_remote_*()` functions to call the remote API, `*_transient()` functions for the caching mechanism, adds custom nav menu meta box and also provide an admin options page to be able to set the frontend page slug.

## Table of content
- [Installation](#installation)
    * [Using Composer :](#using-composer-)
    * [Using Release assets:](#using-release-assets)
    * [Requirements](#requirements)
- [Usage  & Features](#usage---features)
    * [Admin Menu Page](#admin-menu-page)
    * [Admin Nav Menu](#admin-nav-menu)
    * [Frontend](#frontend)
- [Developer Docs](#developer-docs)
    * [How to start development](#how-to-start-development)
    * [How it works](#how-it-works)
    * [Cache System](#cache-system)
    * [Template File](#template-file)
    * [Actions](#actions)
    * [Filters](#filters)
    * [PHP CS](#phpcs)
    * [PHPUnit Tests Configuration File](#phpunit-tests-configuration-file)
    * [PHPUnit Test Cases](#phpunit-test-cases)
- [Composer Scripts](#composer-scripts)
- [Composer Packages](#composer-packages)
- [GitHub Actions](#github-actions)
- [License](#license)

## Installation

To install the plugin, there are two methods:

### Using Composer :
Clone this repository in the WordPress plugins directory and do `composer install --no-dev` in your terminal, then go to your dashboard and activate the plugin.

### Using Release assets:
1. Go to the releases into this repository, and download the latest release `wp-user-listing-table-plugin-*.zip` zip file.
2. Upload zip file to WordPress and hit activate.

### Requirements

- WordPress >= 5.9
- PHP 7.4 or higher.

## Usage  & Features

Once the plugin is installed and activated, you can start using it. Here's an overview of the plugin features:

### Admin Menu Page

The plugin adds an admin submenu page which will help set the users table URL slug. Go to the WordPress dashboard `Settings -> User listing settings`. You will find a form that can help you set the frontend page URL slug, and it will be something like this `https://example.com/slug-name`.

###  Admin Nav Menu

The plugin adds a custom admin menu metabox which will help the user adding the page link into the navigation menus. Go to the WordPress dashboard `Themes -> menus` and you will find a new metabox called `Users table` which will help add the page to the nav menu.

### Frontend

To be able to access the frontend and preview the users table, navigate to your website `https://example.com/slug-name`.  The default users table URL will be `https://example.com/user-listing-table`.

### Uninstall
The plugin contains `uninstall.php` file which will clean the database, delete the option `wpul-table-slug`, and call `flush_rewrite_rules()` to remove all rewrite rules and then recreate rewrite rules.

## Developer Docs

### How to start development
The plugin does not include PHP-dependencies. Just clone this repository and run `composer install`

### How it works

The plugin uses the WordPress rewrite rules API to register a rewrite rule for the frontend page. This rewrite rule can be controlled using a setting page provided under the Setting menu in the WordPress admin dashboard.

When a user visits the plugin frontend page, it fires a `GET` request to `https://jsonplaceholder.typicode.com/users` API using `wp_remote_get()` to get the list of users, then if the request successes it will cache the response using WordPress transients API and then display the ID, name, and username into a table on the page.

When the user clicks on any cell in a row of the users table, the plugin sends a `GET` request using `jQuery Ajax` the website server, then the server send `GET` request to `https://jsonplaceholder.typicode.com/users/{id}` API using `wp_remote_get()` to fetch the single user data, then if the request successes it will cache the response with the user ID in the cache key, so it can load it from the cache every time.

The cache expiration is 1 Hour, and it can be modified using a filter.

### Cache System

Although the WordPress introduces the `wp_cache_*()` functions, the plugin caching system is relying on the WordPress transients API because of the following cons:
1. The transients are always available and using `options` MySQL table.
2. If there are any persistent cache plugin installed, transients will use it instead of MySQL.

### Template File

The plugin includes a template file located in `plugin_dir/templates/users-table.php` which contains the frontend logic. It can be overridden in the active theme by copying it to `yourtheme/user-listing-table/users-table.php`.

### Actions

- `wp_users_table_plugin_frontend_loaded` Fires when all plugin logic loaded.
- `wp_users_table_load_css_assets` Fires when all plugin CSS assets loaded.
- `wp_users_table_load_js_assets` Fires when all plugin JS assets loaded.
- `wp_users_table_rewrite_rule_added` Fires when all plugin Rewrite rule added.
- `wp_users_table_before_table` Fires before rendering frontend users table.
- `wp_users_table_after_table` Fires after rendering frontend users table.

### Filters

- `wp_users_table_template_object` Filter the instance of `UsersTableTemplate` class.
- `wp_users_table_template_regex` Filter the template regex with 1 string parameter.
- `wp_users_table_template_query` Filter the template query with 1 string parameter.
- `wp_users_table_template_query_vars` Filter the WordPress main query vars with array `$vars` parameter.
- `wp_users_table_template_path` Filter the template absolute path with 1 string parameter.
- `wp_users_table_template_tab_title` Filter the template title with 1 string parameter.
- `wp_users_table_endpoint_object` Filter the instance of `EndPoint` class.
- `wp_users_table_get_cached_data` Filter the cached data with 2 parameters `$data` array and string `$key`.
- `wp_users_table_set_cache_data` Filter the data before set in cache with `$data` array parameter.
- `wp_users_table_cache_expiration_time` Filter the cache expiration time with `int` number of seconds parameter.
- `wp_users_table_ajax_users_object` Filter the instance of `Users` class in `AjaxEndpoint` class.
- `wp_users_table_ajax_cache_object` Filter the instance of `UsersCache` class in `AjaxEndpoint` class.
- `wp_users_table_ajax_request_endpoint_object` Filter the instance of `Request` class.
- `wp_users_table_data_provider_users_object` Filter the instance of `Users` class in `UsersProvider` class.
- `wp_users_table_data_provider_cache_object` Filter the instance of `UsersCache` class in `UsersProvider` class.
- `wp_users_table_assets_object` Filter the instance of `AssetsLoader` class.
- `wp_users_table_rewrite_rule_object` Filter the instance of `RewriteRule` class.
- `wp_users_table_users_list_template` Filter the array of users before rendering on the template with array `$usersList`.

### PHP CS

The PHP code sniffer configuration file contains the following rules:
1. It will test the `plugin/src` and `plugin/tests/PHPUnit/Unit` directories.
2. It will test the code against the [Inpsyde coding standards](https://github.com/inpsyde/php-coding-standards).

To run the PHPCS test, use the following command:
`vendor/bin/phpcs`

### PHPUnit Tests Configuration File

The PHPUnit configuration file contains the following main configurations:
1. It will test only the `plugin/src` directory.
2. The test reports will be generated into `plugin/coverage` directory.
3. To generate the reports, you will need to install PHP x debug.

### PHPUnit Test Cases

PHPUnit tests can be run using the following commands:
- `composer tests` or `vendor/bin/phpunit` this command will run all test cases and generate the tests report.
- `composer tests:no-cov` or `vendor/bin/phpunit --no-coverage`  this command will run all test cases without generating the reports.
- `composer tests:codecov` this command will run all test cases and generate a report XML file for automated testing using GitHub actions.

The PHPUnit tests is replying on [WP_Mock](https://github.com/10up/wp_mock) to mock the WordPress functions and make it possible to run the tests without loading WordPress core.

## Composer Scripts

The plugin includes Composer scripts that allow you to perform various tasks, such as running tests and linting code. Here's an overview of the available scripts:

- `composer cs` - Lints the PHP code using PHPCS.
- `composer tests` - Runs the plugin's PHPUnit test cases.
- `composer tests:no-cov` - Runs the plugin's PHPUnit test cases without generating code coverage.
- `composer tests:codecov` - Runs the plugin's PHPUnit test cases and generate `coverage.xml` file.
- `composer qa` - Do the `cs` and `tests` in the same run.

To run a script, open a command line in the plugin's directory and enter the command `composer [script]`, where `[script]` is the name of the script you want to run.

## Composer Packages
Development environment:
- `phpunit/phpunit` v9.6.3 for PHP unit testing.
- `squizlabs/php_codesniffer` v3.6 for linting, coding standards, and beautifying PHP code.
- `inpsyde/php-coding-standards` v1.0 for coding standards testing.
- `10up/wp_mock` for mocking WordPress functions.

No packages required for production environment.


## GitHub Actions

1. `php-tests.yml` run tests and lints the code whenever changes are pushed to the repository on the master branch.
2. `production-plugin.yml` generate the full production plugin zip file ready to upload to WordPress whenever tag is created.

## License
GPL-2.0+