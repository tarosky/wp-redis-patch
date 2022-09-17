<?php

require_once __DIR__ . '/CacheTestCase.php';

class CacheTest extends CacheTestCase {
    private $cache;

    public function &init_cache() {
        $orig_cache = &parent::init_cache();

        $this->cache = new WP_Object_Cache();
        $this->cache->global_groups = $orig_cache->global_groups;
        return $this->cache;
    }

    public function test_loaded() {
        $this->markTestSkipped('This feature is not supported.');
    }

    public function test_delete_group() {
        $this->markTestSkipped('This feature is not supported.');
    }

    public function test_delete_group_non_persistent() {
        $this->markTestSkipped('This feature is not supported.');
    }

    public function test_wp_cache_delete_group() {
        $this->markTestSkipped('This feature is not supported.');
    }

    public function test_wp_redis_get_info() {
        $this->markTestSkipped('This feature is not supported.');
    }

    public function test_get_force() {
        $key = rand_str();
        $group = 'default';
        $this->cache->set($key, 'alpha', $group);
        $this->assertEquals('alpha', $this->cache->get($key, $group));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $true_key = WP_CACHE_KEY_SALT . json_encode(['', $group, $key]);
        $this->cache->cache[$true_key] = 'beta';
        $this->assertEquals('beta', $this->cache->get($key, $group));
        $this->assertEquals('alpha', $this->cache->get($key, $group, true));
        $this->assertEquals(3, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['get' => 1, 'set' => 1], $this->cache->redis_calls);
    }

    public function test_add_get() {
        $key = rand_str();
        $val = rand_str();

        $this->cache->add($key, $val);
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
    }

    public function test_add_get_0() {
        $key = rand_str();
        $val = 0;

        // you can store zero in the cache
        $this->cache->add($key, $val);
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
    }

    public function test_add_get_null() {
        $key = rand_str();
        $val = null;

        $this->assertTrue($this->cache->add($key, $val));
        $this->assertNull($this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
    }

    public function test_flush() {
        $key = rand_str();
        $val = rand_str();

        $this->cache->add($key, $val);
        // item is visible to both cache objects
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->cache->flush();
        // If there is no value get returns false.
        $this->assertFalse($this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(1, $this->cache->cache_misses);
        $this->assertEquals(
            [
                'set' => 1,
                'get' => 1,
                'flushdb' => 1,
            ],
            $this->cache->redis_calls,
        );
    }

    public function test_add() {
        $key = rand_str();
        $val1 = rand_str();
        $val2 = rand_str();

        // add $key to the cache
        $this->assertTrue($this->cache->add($key, $val1));
        $this->assertEquals($val1, $this->cache->get($key));
        // $key is in the cache, so reject new calls to add()
        $this->assertFalse($this->cache->add($key, $val2));
        $this->assertEquals($val1, $this->cache->get($key));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(
            [
                'set' => 2,
                // The second `add()`, which fails, removes internal cache.
                // While this behavior isn't desirable from the performance perspective,
                // it simplifies implementation.
                'get' => 1,
            ],
            $this->cache->redis_calls,
        );
    }

    public function test_replace() {
        $key = rand_str();
        $val = rand_str();
        $val2 = rand_str();

        // memcached rejects replace() if the key does not exist
        $this->assertFalse($this->cache->replace($key, $val));
        $this->assertFalse($this->cache->get($key));
        $this->assertTrue($this->cache->add($key, $val));
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertTrue($this->cache->replace($key, $val2));
        $this->assertEquals($val2, $this->cache->get($key));
        $this->assertEquals(['set' => 3, 'get' => 1], $this->cache->redis_calls);
    }

    public function test_delete() {
        $key = rand_str();
        $val = rand_str();

        // Verify set
        $this->assertTrue($this->cache->set($key, $val));
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        // Verify successful delete
        $this->assertTrue($this->cache->delete($key));
        $this->assertFalse($this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(1, $this->cache->cache_misses);

        $this->assertFalse($this->cache->delete($key, 'default'));
        $this->assertEquals(
            [
                'set' => 1,
                'del' => 2,
                'get' => 1,
            ],
            $this->cache->redis_calls,
        );
    }

    public function test_incr() {
        $key = rand_str();

        $this->assertFalse($this->cache->incr($key));
        $this->assertEquals(0, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->set($key, 0);
        $this->cache->incr($key);
        $this->assertEquals(1, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->incr($key, 2);
        $this->assertEquals(3, $this->cache->get($key));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['set' => 1, 'evalSha' => 3], $this->cache->redis_calls);
    }

    public function test_incr_separate_groups() {
        $key = rand_str();
        $group1 = 'group1';
        $group2 = 'group2';

        $this->assertFalse($this->cache->incr($key, 1, $group1));
        $this->assertFalse($this->cache->incr($key, 1, $group2));
        $this->assertEquals(0, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->set($key, 0, $group1);
        $this->cache->incr($key, 1, $group1);
        $this->cache->set($key, 0, $group2);
        $this->cache->incr($key, 1, $group2);
        $this->assertEquals(1, $this->cache->get($key, $group1));
        $this->assertEquals(1, $this->cache->get($key, $group2));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->incr($key, 2, $group1);
        $this->cache->incr($key, 1, $group2);
        $this->assertEquals(3, $this->cache->get($key, $group1));
        $this->assertEquals(2, $this->cache->get($key, $group2));
        $this->assertEquals(4, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['set' => 2, 'evalSha' => 6], $this->cache->redis_calls);
    }

    public function test_incr_never_below_zero() {
        $key = rand_str();
        $this->cache->set($key, 1);
        $this->assertEquals(1, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->cache->incr($key, -2);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(['evalSha' => 1, 'set' => 1], $this->cache->redis_calls);
    }

    public function test_decr() {
        $key = rand_str();

        $this->assertFalse($this->cache->decr($key));
        $this->assertEquals(0, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->set($key, 0);
        $this->cache->decr($key);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->set($key, 3);
        $this->cache->decr($key);
        $this->assertEquals(2, $this->cache->get($key));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->decr($key, 2);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(3, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(
            [
                'set' => 2,
                'evalSha' => 4,
            ],
            $this->cache->redis_calls,
        );
    }

    public function test_decr_separate_groups() {
        $key = rand_str();
        $group1 = 'group1';
        $group2 = 'group2';

        $this->assertFalse($this->cache->decr($key, 1, $group1));
        $this->assertFalse($this->cache->decr($key, 1, $group2));
        $this->assertEquals(0, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->set($key, 0, $group1);
        $this->cache->decr($key, 1, $group1);
        $this->cache->set($key, 0, $group2);
        $this->cache->decr($key, 1, $group2);
        $this->assertEquals(0, $this->cache->get($key, $group1));
        $this->assertEquals(0, $this->cache->get($key, $group2));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->set($key, 3, $group1);
        $this->cache->decr($key, 1, $group1);
        $this->cache->set($key, 2, $group2);
        $this->cache->decr($key, 1, $group2);
        $this->assertEquals(2, $this->cache->get($key, $group1));
        $this->assertEquals(1, $this->cache->get($key, $group2));
        $this->assertEquals(4, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);

        $this->cache->decr($key, 2, $group1);
        $this->cache->decr($key, 2, $group2);
        $this->assertEquals(0, $this->cache->get($key, $group1));
        $this->assertEquals(0, $this->cache->get($key, $group2));
        $this->assertEquals(6, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(
            [
                'set' => 4,
                'evalSha' => 8,
            ],
            $this->cache->redis_calls,
        );
    }

    public function test_decr_never_below_zero() {
        $key = rand_str();
        $this->cache->set($key, 1);
        $this->assertEquals(1, $this->cache->get($key));
        $this->assertEquals(1, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->cache->decr($key, 2);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(2, $this->cache->cache_hits);
        $this->assertEquals(0, $this->cache->cache_misses);
        $this->assertEquals(
            [
                'evalSha' => 1,
                'set' => 1,
            ],
            $this->cache->redis_calls,
        );
    }

    public function test_get_multiple_no_connection() {
        $this->cache->set('foo1', 'bar', 'group1');
        $this->cache->set('foo2', 'bar', 'group1');

        $this->cache->is_redis_connected = false;
        $this->cache->cache = [];

        $found = $this->cache->get_multiple(['foo1', 'foo2', 'foo3'], 'group1');

        $this->assertSame([
            'foo1' => false,
            'foo2' => false,
            'foo3' => false,
        ], $found);
    }

    static function tearDownAfterClass(): void {
        // No-op
    }
}
