<?php

# -*- coding: utf-8 -*-

declare(strict_types=1);

namespace WpUserListingTable\PHPUnit\Unit\API;

use WpUserListingTable\API\EndPoint;
use WpUserListingTable\API\Users;
use WpUserListingTable\Exceptions\NotFoundException;
use WpUserListingTable\Exceptions\TimedOutException;
use WpUserListingTable\Exceptions\UnAuthorizedException;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class UsersTest extends AbstractUnitTestCase
{
    /**
     * @var string the mocked successful api json response for list of 2 users.
     */
    private string $response =
        '[{"id":1,"name":"Leanne Graham","username":"Bret","email":"Sincere@april.biz"},
        {"id":2,"name":"Ervin Howell","username":"Antonette","email":"Shanna@melissa.tv"}]';

    /**
     * Test users method returns valid array of users based on given api response mock
     * with exact keys and values.
     *
     * @return void
     */
    public function testUsersMethodReturnsArrayOfUsers(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);
        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
        ]);
        \WP_Mock::userFunction('wp_remote_retrieve_body', [
            'times' => 1,
            'return' => $this->response,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 200,
        ]);

        $usersObject = new Users();
        $users = $usersObject->users();
        $this->assertIsArray($users);
        $this->assertCount(2, $users);
        $this->assertArrayHasKey('id', $users[0]);
        $this->assertArrayHasKey('name', $users[0]);
        $this->assertArrayHasKey('id', $users[1]);
        $this->assertArrayHasKey('name', $users[1]);
        $this->assertSame('Leanne Graham', $users[0]['name']);
        $this->assertSame('Ervin Howell', $users[1]['name']);
    }

    /**
     * Test users method throws RuntimeException exception
     *
     * @return void
     * @throws \RuntimeException
     */
    public function testUsersThrowsRuntimeException(): void
    {
        $error = new class {
            // phpcs:disable
            public function get_error_message(): string
            {
                return 'error';
            }
            // phpcs:enable
        };

        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => $error,
        ]);

        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
            'return' => true,
        ]);

        $users = new Users();
        $this->expectException(\RuntimeException::class);
        $users->users();
    }

    /**
     * Test users method throws JsonException because of bad json
     *
     * @return void
     * @throws \JsonException
     */
    public function testUsersReturnsExceptionWithBadJson(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);

        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
            'return' => false,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 200,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_body', [
            'times' => 1,
            'return' => '',
        ]);

        $users = new Users();
        $this->expectException(\JsonException::class);
        $users->users();
    }

    /**
     * Test users by ID method throws JsonException because of bad json
     *
     * @return void
     * @throws \JsonException
     */
    public function testUserByIdReturnsExceptionWithBadJson(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);

        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
            'return' => false,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 200,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_body', [
            'times' => 1,
            'return' => '',
        ]);
        $endPoint = new EndPoint();
        $users = new Users($endPoint, 5);
        $this->expectException(\JsonException::class);
        $users->users();
    }

    /**
     * Test if user by ID returns valid user array with given of single user data array.
     *
     * @return void
     */
    public function testUserByIdReturnsArray(): void
    {
        $response = '{"id":1,"name":"Leanne Graham","username":"Bret","email":"Sincere@april.biz"}';
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $response],
        ]);

        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
            'return' => false,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 200,
        ]);

        \WP_Mock::userFunction('wp_remote_retrieve_body', [
            'times' => 1,
            'return' => $response,
        ]);

        $endPoint = new EndPoint();
        $users = new Users($endPoint, 5);
        $result = $users->userById(5);
        $this->assertIsArray($result);
    }

    /**
     * Test users method throws NotFoundException if the API returned error 404
     *
     * @return void
     * @throws NotFoundException
     */
    public function testUsersThrowsNotFoundException(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);
        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
        ]);
        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 404,
        ]);
        $usersObject = new Users();
        $this->expectException(NotFoundException::class);
        $usersObject->users();
    }

    /**
     * Test users method throws UnAuthorizedException if the API returned error 401
     *
     * @return void
     * @throws UnAuthorizedException
     */
    public function testUsersThrowsUnAuthorizedException(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);
        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
        ]);
        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 401,
        ]);

        $usersObject = new Users();
        $this->expectException(UnAuthorizedException::class);
        $usersObject->users();
    }

    /**
     * Test users method throws TimedOutException if the API returned error 408
     *
     * @return void
     * @throws TimedOutException
     */
    public function testUsersThrowsTimedOutException(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);
        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
        ]);
        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 408,
        ]);
        $usersObject = new Users();
        $this->expectException(TimedOutException::class);
        $usersObject->users();
    }

    /**
     * Test users method throws general Exception if the API returned any other errors
     *
     * @return void
     * @throws \Exception
     */
    public function testUsersThrowsException(): void
    {
        \WP_Mock::userFunction('wp_remote_request', [
            'times' => 1,
            'return' => ['body' => $this->response],
        ]);
        \WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
        ]);
        \WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'times' => 1,
            'return' => 301,
        ]);
        $usersObject = new Users();
        $this->expectException(\Exception::class);
        $usersObject->users();
    }
}
