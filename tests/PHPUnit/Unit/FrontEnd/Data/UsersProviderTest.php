<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\FrontEnd\Data;

use WpUserListingTable\API\Users;
use WpUserListingTable\API\UsersCache;
use WpUserListingTable\FrontEnd\Data\UsersProvider;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class UsersProviderTest extends AbstractUnitTestCase
{
    /**
     * Test if fetch users method returns the correct array of users
     *
     * @return void
     * @throws \Throwable
     */
    public function testFetchUsersReturnArray(): void
    {
        /**
         * Mock users class and it's methods
         */
        $usersClient = \Mockery::mock(Users::class);
        $usersClient->shouldReceive('users')->andReturn(['users' => []]);

        /**
         * Mock cache class and it's methods
         */
        $cache = \Mockery::mock(UsersCache::class);
        $cache->shouldReceive('get')->andReturn([]);
        $cache->shouldReceive('set');

        $usersProvider = new UsersProvider($usersClient, $cache);
        $result = $usersProvider->fetchUsers();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
    }

    /**
     * Test if the users list method returns empty array of there are any exceptions.
     *
     * @return void
     * @throws \Throwable
     */
    public function testUsersListReturnEmptyArray(): void
    {
        $usersClient = \Mockery::mock(Users::class);
        $usersClient->shouldReceive('users')->andReturnUsing(static function () {
            throw new \InvalidArgumentException('bad users');
        });

        $cache = \Mockery::mock(UsersCache::class);
        $cache->shouldReceive('get')->andReturn([]);
        $cache->shouldReceive('set');

        $usersProvider = new UsersProvider($usersClient, $cache);
        $result = $usersProvider->usersList();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test users list method returns the correct users array
     *
     * @return void
     */
    public function testUsersListReturnUsersArray(): void
    {
        $usersClient = \Mockery::mock(Users::class);
        $usersClient->shouldReceive('users')->andReturn(['users' => []]);

        $cache = \Mockery::mock(UsersCache::class);
        $cache->shouldReceive('get')->andReturn([]);
        $cache->shouldReceive('set');

        $usersProvider = new UsersProvider($usersClient, $cache);
        $result = $usersProvider->usersList();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
    }
}
