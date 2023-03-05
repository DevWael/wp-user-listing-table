<?php # -*- coding: utf-8 -*-

namespace PHPUnit\Unit\Admin;

use WpUserListingTable\Admin\AjaxEndpoint;
use WpUserListingTable\Admin\Request;
use WpUserListingTable\API\Cache;
use WpUserListingTable\API\UsersClient;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class AjaxEndpointTest extends AbstractUnitTestCase
{
    /**
     * Test checkNonce should throw InvalidArgumentException for bad nonce.
     *
     * @return void
     */
    public function testInvalidNonceShouldThrowException(): void
    {
        \WP_Mock::userFunction('wp_verify_nonce', [
            'times' => 1,
            'args' => [null],
            'return' => false,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $ajax = new AjaxEndpoint();
        $ajax->checkNonce();
    }

    /**
     * Test wpRequest method should not throw an exception when invalid nonce.
     *
     * @return void
     */
    public function testWpRequestInvalidNonceShouldNotThrowException(): void
    {
        \WP_Mock::userFunction('wp_verify_nonce', [
            'times' => 1,
            'args' => [null],
            'return' => false,
        ]);

        \WP_Mock::userFunction('wp_send_json_error');
        try{
            $ajax = new AjaxEndpoint();
            $ajax->wpRequest();
        } catch(\Throwable $exception){
            $this->fail($exception->getMessage());
        }

        $this->assertTrue(true);
    }

    /**
     * Test wpRequest get single user successfully without exceptions.
     *
     * @return void
     */
    public function testWpRequestGetSingleUserSuccess(): void
    {
        $users = \Mockery::mock( UsersClient::class );
        $users->shouldReceive('userById')->with('17')->andReturn([
            'data'
        ]);

        $cache = \Mockery::mock( Cache::class );
        $cache->shouldReceive('get')->andReturn(array('data'));

        $request = \Mockery::mock( Request::class );
        $request->shouldReceive('get')->andReturn(['user_id' => '17']);

        \WP_Mock::userFunction('wp_unslash')->andReturnArg(0);
        \WP_Mock::userFunction('absint')->andReturnUsing(function($id){
            return (int) $id;
        });
        \WP_Mock::userFunction('wp_send_json_success');

        try{
            $ajax = new AjaxEndpoint($users,$cache,$request);
            $ajax->wpRequest();
        } catch(\Throwable $exception){
            $this->fail($exception->getMessage());
        }

        $this->assertTrue(true);
    }

    /**
     * Test verifyUserId should throw InvalidArgumentException when invalid user ID is provided
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function testUserIdInvalid(): void
    {
        $users = \Mockery::mock( UsersClient::class );
        $users->shouldReceive('userById')->with('0')->andReturn([
            'data'
        ]);
        $cache = \Mockery::mock( Cache::class );
        $cache->shouldReceive('get')->andReturn(array('data'));
        $request = \Mockery::mock( Request::class );
        $request->shouldReceive('get')->andReturn(['user_id' => '0']);
        \WP_Mock::userFunction('wp_unslash')->once()->andReturnArg(0);
        $ajax = new AjaxEndpoint($users,$cache,$request);
        $this->expectException(\InvalidArgumentException::class);
        $ajax->verifyUserId();
    }

    /**
     * Test verifyUserId should throw exception when no user ID is provided
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function testNoProvidedUserIdShouldThrowException(): void
    {
        $users = \Mockery::mock( UsersClient::class );
        $users->shouldReceive('userById')->with('0')->andReturn([
            'data'
        ]);
        $cache = \Mockery::mock( Cache::class );
        $cache->shouldReceive('get')->andReturn(array('data'));
        $request = \Mockery::mock( Request::class );
        $request->shouldReceive('get')->andReturn(['user' => '12']);
        \WP_Mock::userFunction('wp_send_json_error');
        $ajax = new AjaxEndpoint($users,$cache,$request);
        $this->expectException(\InvalidArgumentException::class);
        $result = $ajax->verifyUserId();
        $this->assertSame(0, $result);
    }

    /**
     * Test single user should return at least one user
     *
     * @return void
     */
    public function testSingleUsersShouldNotReturnEmptyArray(): void
    {
        $userId = 10;
        $users = \Mockery::mock( UsersClient::class );
        $users->shouldReceive('userById')->once()->with($userId)->andReturn(array('data'));

        $cache = \Mockery::mock( Cache::class );
        $cache->shouldReceive('get')->once()->with('wp_user_listing_' . $userId)->andReturn(array());
        $cache->shouldReceive('set')->once();

        $request = \Mockery::mock( Request::class );
        $request->shouldReceive('get')->andReturn(['user_id' => 137]);

        $ajax = new AjaxEndpoint($users,$cache,$request);
        $result = $ajax->singleUserDetails($userId);
        $this->assertCount(1, $result);
        $this->assertIsArray($result);
    }

    /**
     * Test if the init method contains required hooks
     *
     * @return void
     */
    public function testInitContainsActions(): void
    {
        $ajax = new AjaxEndpoint();
        \WP_Mock::expectActionAdded('wp_ajax_users_table_request',[$ajax,'wpRequest']);
        \WP_Mock::expectActionAdded('wp_ajax_nopriv_users_table_request',[$ajax,'wpRequest']);
        $ajax->init();
        \WP_Mock::assertActionsCalled();
    }
}
