<?php # -*- coding: utf-8 -*-

namespace PHPUnit\Unit\API;

use WpUserListingTable\API\UsersCache;
use WpUserListingTable\PHPUnit\Unit\AbstractUnitTestCase;

class UsersCacheTest extends AbstractUnitTestCase
{
    /**
     * Test Get method returns empty array if the cache is empty or invalid
     *
     * @return void
     */
    public function testGetReturnsEmptyArrayWhenTransientFalse(): void
    {
        \WP_Mock::userFunction('get_transient', [
            'times' => 2,
            'args' => 'WPUL_cache_key',
            'return' => false,
        ]);
        $cache = new UsersCache();
        $this->assertIsArray($cache->get('cache_key'));
        $this->assertEquals([], $cache->get('cache_key'));
    }

    /**
     * Test Get method returns same data that sent Set method
     *
     * @return void
     */
    public function testGetReturnsSameDataPassedToSet(): void
    {
        $data = ['val1', 'val2'];
        \WP_Mock::userFunction('get_transient', [
            'times' => 1,
            'args' => 'WPUL_cache_key',
            'return' => $data,
        ]);

        \WP_Mock::userFunction('set_transient', [
            'times' => 1,
            'args' => ['WPUL_cache_key', $data, 3600],
        ]);

        $cache = new UsersCache();
        $cache->set('cache_key', $data);
        $this->assertContains('val1', $cache->get('cache_key'));
    }

    /**
     * Test purge method makes Get method returns empty array
     *
     * @return void
     */
    public function testPurgeCauseEmptyArrayOnGetMethod(): void
    {
        \WP_Mock::userFunction('get_transient', [
            'times' => 1,
            'args' => 'WPUL_cache_key',
            'return' => [],
        ]);

        \WP_Mock::userFunction('delete_transient', [
            'times' => 1,
            'args' => 'WPUL_cache_key',
        ]);

        $cache = new UsersCache();
        $cache->purge('cache_key');
        $this->assertEmpty($cache->get('cache_key'));
    }
}
