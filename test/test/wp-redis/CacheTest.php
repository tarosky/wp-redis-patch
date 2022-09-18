<?php

/**
 * Originally created by Pantheon as https://github.com/pantheon-systems/wp-redis.
 * Modified by Tarosky INC.
 */

class CacheTest extends WPRedisTestCase {
    private $cache;

    private static $client_parameters = [
        'host'           => 'redis',
        'port'           => 6379,
        'timeout'        => 1000,
        'retry_interval' => 100,
    ];

    private function reset_cache_state() {
        $this->cache->redis_calls = [];
        $this->cache->cache = [];
    }

    public function setUp(): void {
        global $wp_object_cache, $redis_server;

        parent::setUp();

        $redis_server = [
            'host' => 'redis',
            'port' => 6379,
        ];

        $wp_object_cache = new WP_Object_Cache;
        $wp_object_cache->flush();

        // create two cache objects with a shared cache dir
        // this simulates a typical cache situation, two separate requests interacting
        $this->cache = &$this->init_cache();
        $this->cache->redis_calls = [];
    }

    public function tearDown(): void {
        parent::tearDown();
        $this->flush_cache();
    }

    public function &init_cache() {
        $cache = new WP_Object_Cache();
        $cache->add_global_groups([
            'blog-details',
            'blog-id-cache',
            'blog-lookup',
            'global-cache-test',
            'global-posts',
            'rss',
            'site-lookup',
            'site-options',
            'site-transient',
            'user_meta',
            'userlogins',
            'usermeta',
            'users',
        ]);
        return $cache;
    }

    public function test_connection_details() {
        $actual = $this->cache->build_client_parameters([
            'host'      => '127.0.0.1',
            'port'      => 6379,
            'extra'     => true,
            'recursive' => ['child' => true],
        ]);

        $this->assertEquals([
            'host'           => '127.0.0.1',
            'port'           => 6379,
            'extra'          => true,
            'recursive'      => ['child' => true],
            'timeout'        => 1000,
            'retry_interval' => 100,
        ], $actual);
    }

    public function test_redis_connected() {
        $this->assertTrue(isset($this->cache->redis));
        $this->assertTrue($this->cache->redis->IsConnected());
    }

    public function test_redis_reload_connection_closed() {
        // Connection is live
        $this->cache->set('foo', 'bar');
        $this->assertTrue($this->cache->redis->IsConnected());
        $this->assertTrue($this->cache->is_redis_connected);
        $this->assertEquals('bar', $this->cache->get('foo', 'default', true));
        // Connection is closed, but automatic reconnection occurs.
        $this->cache->redis->close();
        $this->assertTrue($this->cache->is_redis_connected);
        $this->assertTrue($this->cache->redis->IsConnected());
        // Reload occurs with set()
        $this->cache->set('foo', 'banana');
        $this->assertEquals('banana', $this->cache->get('foo'));
        $this->assertTrue($this->cache->is_redis_connected);
        $this->assertTrue($this->cache->redis->IsConnected());
    }

    public function test_redis_bad_authentication() {
        global $redis_server;

        $redis_server['host'] = '127.0.0.1';
        $redis_server['port'] = 9999;
        $redis_server['auth'] = 'foobar';

        $this->expectWarning();

        $cache = new WP_Object_Cache;
        $this->assertTrue($cache->exception_message_matches(
            str_replace('WP Redis: ', '', $cache->last_triggered_error),
            $cache->retry_exception_messages()
        ));
        $this->assertFalse($cache->is_redis_connected);
        // Fails back to the internal object cache
        $cache->set('foo', 'bar');
        $this->assertEquals('bar', $cache->get('foo'));
    }

    public function test_miss() {
        $this->assertFalse($this->cache->get(rand_str()));
        $this->assertEquals(['get' => 1], $this->cache->redis_calls);
    }

    public function test_set() {
        $key  = rand_str();
        $val1 = rand_str();
        $val2 = rand_str();

        // memcached accepts set() if the key does not exist
        $this->assertTrue($this->cache->set($key, $val1));
        $this->assertEquals($val1, $this->cache->get($key));
        // Second set() with same key should be allowed
        $this->assertTrue($this->cache->set($key, $val2));
        $this->assertEquals($val2, $this->cache->get($key));
        $this->assertEquals(['set' => 2], $this->cache->redis_calls);
    }

    // Make sure objects are cloned going to and from the cache
    public function test_object_refs() {
        $key           = rand_str();
        $object_a      = new stdClass;
        $object_a->foo = 'alpha';
        $this->cache->set($key, $object_a);
        $object_a->foo = 'bravo';
        $object_b      = $this->cache->get($key);
        $this->assertEquals('alpha', $object_b->foo);
        $object_b->foo = 'charlie';
        $this->assertEquals('bravo', $object_a->foo);

        $key           = rand_str();
        $object_a      = new stdClass;
        $object_a->foo = 'alpha';
        $this->cache->add($key, $object_a);
        $object_a->foo = 'bravo';
        $object_b      = $this->cache->get($key);
        $this->assertEquals('alpha', $object_b->foo);
        $object_b->foo = 'charlie';
        $this->assertEquals('bravo', $object_a->foo);
    }

    public function test_get_already_exists_internal() {
        $key = rand_str();
        $this->cache->set($key, 'alpha');
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
        $this->cache->redis_calls = []; // reset to limit scope of test
        $this->assertEquals('alpha', $this->cache->get($key));
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_get_missing_persistent() {
        $key = rand_str();
        $this->cache->get($key);
        $this->cache->get($key);
        $this->assertEquals(['get' => 2], $this->cache->redis_calls);
    }

    public function test_get_non_persistent_group() {
        $key   = rand_str();
        $group = 'nonpersistent';
        $this->cache->add_non_persistent_groups($group);
        $this->cache->get($key, $group);
        $this->assertEmpty($this->cache->redis_calls);
        $this->cache->get($key, $group);
        $this->assertEmpty($this->cache->redis_calls);
        $this->cache->set($key, 'alpha', $group);
        $this->cache->get($key, $group);
        $this->assertEmpty($this->cache->redis_calls);
        $this->cache->get($key, $group);
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_get_false_value_persistent_cache() {
        $key = rand_str();
        $this->cache->set($key, false);
        $this->reset_cache_state();
        $found = null;
        $this->assertFalse($this->cache->get($key, 'default', false, $found));
        $this->assertTrue($found);
        $this->assertEquals(['get' => 1], $this->cache->redis_calls);
    }

    public function test_get_true_value_persistent_cache() {
        $key = rand_str();
        $this->cache->set($key, true);
        $this->reset_cache_state();
        $found = null;
        $this->assertTrue($this->cache->get($key, 'default', false, $found));
        $this->assertTrue($found);
        $this->assertEquals(['get' => 1], $this->cache->redis_calls);
    }

    public function test_get_null_value_persistent_cache() {
        $key = rand_str();
        $this->cache->set($key, null);
        $this->reset_cache_state();
        $found = null;
        $this->assertNull($this->cache->get($key, 'default', false, $found));
        $this->assertTrue($found);
        $this->assertEquals(['get' => 1], $this->cache->redis_calls);
    }

    public function test_get_int_values_persistent_cache() {
        $key1 = rand_str();
        $key2 = rand_str();
        $this->cache->set($key1, 123);
        $this->cache->set($key2, 0xf4c3b00c);
        $this->reset_cache_state();
        // Should be upgraded to more strict comparison if change proposed in issue #181 is merged.
        $this->assertSame(123, $this->cache->get($key1));
        $this->assertSame(4106465292, $this->cache->get($key2));
        $this->assertEquals(['get' => 2], $this->cache->redis_calls);
    }

    public function test_get_float_values_persistent_cache() {
        $key1 = rand_str();
        $key2 = rand_str();
        $this->cache->set($key1, 123.456);
        $this->cache->set($key2, +0123.45e6);
        $this->reset_cache_state();
        $this->assertSame(123.456, $this->cache->get($key1));
        $this->assertSame(123450000.0, $this->cache->get($key2));
        $this->assertEquals(['get' => 2], $this->cache->redis_calls);
    }

    public function test_get_string_values_persistent_cache() {
        $key1 = rand_str();
        $key2 = rand_str();
        $key3 = rand_str();
        $key4 = rand_str();
        $this->cache->set($key1, 'a plain old string');
        // To ensure numeric strings are not converted to integers.
        $this->cache->set($key2, '42');
        $this->cache->set($key3, '123.456');
        $this->cache->set($key4, '+0123.45e6');
        $this->reset_cache_state();
        $this->assertEquals('a plain old string', $this->cache->get($key1));
        $this->assertSame('42', $this->cache->get($key2));
        $this->assertSame('123.456', $this->cache->get($key3));
        $this->assertSame('+0123.45e6', $this->cache->get($key4));
        $this->assertEquals(['get' => 4], $this->cache->redis_calls);
    }

    public function test_get_array_values_persistent_cache() {
        $key   = rand_str();
        $value = ['one', 2, true];
        $this->cache->set($key, $value);
        $this->reset_cache_state();
        $this->assertEquals($value, $this->cache->get($key));
        $this->assertEquals(['get' => 1], $this->cache->redis_calls);
    }

    public function test_get_object_values_persistent_cache() {
        $key          = rand_str();
        $value        = new stdClass;
        $value->one   = 'two';
        $value->three = 'four';
        $this->cache->set($key, $value);
        $this->reset_cache_state();
        $this->assertEquals($value, $this->cache->get($key));
        $this->assertEquals(['get' => 1], $this->cache->redis_calls);
    }

    public function test_get_found() {
        $key   = rand_str();
        $found = null;
        $this->cache->get($key, 'default', false, $found);
        $this->assertFalse($found);
        $this->cache->set($key, 'alpha', 'default');
        $this->cache->get($key, 'default', false, $found);
        $this->assertTrue($found);
    }

    public function test_get_multiple() {
        $this->cache->set('foo1', 'bar', 'group1');
        $this->cache->set('foo2', 'bar', 'group1');
        $this->cache->set('foo1', 'bar', 'group2');

        $found = $this->cache->get_multiple(['foo1', 'foo2', 'foo3'], 'group1');

        $this->assertSame([
            'foo1' => 'bar',
            'foo2' => 'bar',
            'foo3' => false,
        ], $found);

        $this->assertEquals([
            'mget' => 1,
            'set'  => 3,
        ], $this->cache->redis_calls);
    }

    public function test_get_multiple_non_persistent() {
        $this->cache->add_non_persistent_groups(['group1']);
        $this->cache->set('foo1', 'bar', 'group1');
        $this->cache->set('foo2', 'bar', 'group1');

        $found = $this->cache->get_multiple(['foo1', 'foo2', 'foo3'], 'group1');

        $this->assertSame([
            'foo1' => 'bar',
            'foo2' => 'bar',
            'foo3' => false,
        ], $found);

        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_incr_non_persistent() {
        $key = rand_str();

        $this->cache->add_non_persistent_groups(['nonpersistent']);
        $this->assertFalse($this->cache->incr($key, 1, 'nonpersistent'));

        $this->cache->set($key, 0, 'nonpersistent');
        $this->cache->incr($key, 1, 'nonpersistent');
        $this->assertEquals(1, $this->cache->get($key, 'nonpersistent'));

        $this->cache->incr($key, 2, 'nonpersistent');
        $this->assertEquals(3, $this->cache->get($key, 'nonpersistent'));
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_incr_non_persistent_never_below_zero() {
        $key = rand_str();
        $this->cache->add_non_persistent_groups(['nonpersistent']);
        $this->cache->set($key, 1, 'nonpersistent');
        $this->assertEquals(1, $this->cache->get($key, 'nonpersistent'));
        $this->cache->incr($key, -2, 'nonpersistent');
        $this->assertEquals(0, $this->cache->get($key, 'nonpersistent'));
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_wp_cache_incr() {
        $key = rand_str();

        $this->assertFalse(wp_cache_incr($key));

        wp_cache_set($key, 0);
        wp_cache_incr($key);
        $this->assertEquals(1, wp_cache_get($key));

        wp_cache_incr($key, 2);
        $this->assertEquals(3, wp_cache_get($key));
    }

    public function test_decr_non_persistent() {
        $key = rand_str();

        $this->cache->add_non_persistent_groups(['nonpersistent']);
        $this->assertFalse($this->cache->decr($key, 1, 'nonpersistent'));

        $this->cache->set($key, 0, 'nonpersistent');
        $this->cache->decr($key, 1, 'nonpersistent');
        $this->assertEquals(0, $this->cache->get($key, 'nonpersistent'));

        $this->cache->set($key, 3, 'nonpersistent');
        $this->cache->decr($key, 1, 'nonpersistent');
        $this->assertEquals(2, $this->cache->get($key, 'nonpersistent'));

        $this->cache->decr($key, 2, 'nonpersistent');
        $this->assertEquals(0, $this->cache->get($key, 'nonpersistent'));
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_decr_non_persistent_never_below_zero() {
        $key = rand_str();
        $this->cache->add_non_persistent_groups(['nonpersistent']);
        $this->cache->set($key, 1, 'nonpersistent');
        $this->assertEquals(1, $this->cache->get($key, 'nonpersistent'));
        $this->cache->decr($key, 2, 'nonpersistent');
        $this->assertEquals(0, $this->cache->get($key, 'nonpersistent'));
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_wp_cache_decr() {
        $key = rand_str();

        $this->assertFalse(wp_cache_decr($key));

        wp_cache_set($key, 0);
        wp_cache_decr($key);
        $this->assertEquals(0, wp_cache_get($key));

        wp_cache_set($key, 3);
        wp_cache_decr($key);
        $this->assertEquals(2, wp_cache_get($key));

        wp_cache_decr($key, 2);
        $this->assertEquals(0, wp_cache_get($key));
    }

    public function test_wp_cache_delete() {
        $key = rand_str();
        $val = rand_str();

        // Verify set
        $this->assertTrue(wp_cache_set($key, $val));
        $this->assertEquals($val, wp_cache_get($key));

        // Verify successful delete
        $this->assertTrue(wp_cache_delete($key));
        $this->assertFalse(wp_cache_get($key));

        // wp_cache_delete() does not have a $force method.
        // Delete returns (bool) true when key is not set and $force is true
        // $this->assertTrue( wp_cache_delete( $key, 'default', true ) );

        $this->assertFalse(wp_cache_delete($key, 'default'));
    }

    public function test_switch_to_blog() {
        $key  = rand_str();
        $val  = rand_str();
        $val2 = rand_str();

        // Single site ingnores switch_to_blog().
        $this->assertTrue($this->cache->set($key, $val));
        $this->assertEquals($val, $this->cache->get($key));
        $this->cache->switch_to_blog(999);
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertTrue($this->cache->set($key, $val2));
        $this->assertEquals($val2, $this->cache->get($key));
        $this->cache->switch_to_blog(get_current_blog_id());
        $this->assertEquals($val2, $this->cache->get($key));

        // Global group
        $this->assertTrue($this->cache->set($key, $val, 'global-cache-test'));
        $this->assertEquals($val, $this->cache->get($key, 'global-cache-test'));
        $this->cache->switch_to_blog(999);
        $this->assertEquals($val, $this->cache->get($key, 'global-cache-test'));
        $this->assertTrue($this->cache->set($key, $val2, 'global-cache-test'));
        $this->assertEquals($val2, $this->cache->get($key, 'global-cache-test'));
        $this->cache->switch_to_blog(get_current_blog_id());
        $this->assertEquals($val2, $this->cache->get($key, 'global-cache-test'));
    }

    public function test_wp_cache_init() {
        $new_blank_cache_object = new WP_Object_Cache();
        wp_cache_init();

        global $wp_object_cache;
        // Differs from core tests because we'll have two different Redis sockets
        $this->assertEquals($wp_object_cache->cache, $new_blank_cache_object->cache);
    }

    public function test_redis_connect_custom_database() {
        global $redis_server;

        $redis_server['database'] = 2;
        $second_cache             = new WP_Object_Cache;
        $second_cache->flush(); // Make sure it's in pristine state.
        $this->cache->set('foo', 'bar');
        $this->assertEquals('bar', $this->cache->get('foo'));
        $this->assertFalse($second_cache->get('foo'));
        $second_cache->set('foo', 'apple');
        $this->assertEquals('apple', $second_cache->get('foo'));
        $this->assertEquals('bar', $this->cache->get('foo'));
    }

    public function test_wp_cache_replace() {
        $key  = 'my-key';
        $val1 = 'first-val';
        $val2 = 'second-val';

        $fake_key = 'my-fake-key';

        // Save the first value to cache and verify
        wp_cache_set($key, $val1);
        $this->assertEquals($val1, wp_cache_get($key));

        // Replace the value and verify
        wp_cache_replace($key, $val2);
        $this->assertEquals($val2, wp_cache_get($key));

        // Non-existant key should fail
        $this->assertFalse(wp_cache_replace($fake_key, $val1));

        // Make sure $fake_key is not stored
        $this->assertFalse(wp_cache_get($fake_key));
    }

    public function test_dependencies() {
        $this->assertTrue($this->cache->check_client_dependencies());
    }

    public function test_redis_client_connection() {
        $redis = $this->cache->prepare_client_connection(self::$client_parameters);
        $this->assertTrue($redis->isConnected());
    }

    public function test_setup_connection() {
        $redis   = $this->cache->prepare_client_connection(self::$client_parameters);
        $isSetUp = $this->cache->perform_client_connection($redis, [], []);
        $this->assertTrue($isSetUp);
    }

    public function test_setup_connection_throws_exception() {
        $redis = $this->getMockBuilder('Redis')->getMock();
        $redis->method('select')->will($this->throwException(new RedisException));

        $redis->connect(
            self::$client_parameters['host'],
            self::$client_parameters['port'],
            self::$client_parameters['timeout'],
            null,
            self::$client_parameters['retry_interval']
        );
        $settings = ['database' => 2];
        $keys_methods = ['database' => 'select'];
        $this->setExpectedException('Exception');
        $this->cache->perform_client_connection($redis, $settings, $keys_methods);
    }

    public function test_cache_key() {
        $key = rand_str();
        $group = 'default';
        $true_key = WP_CACHE_KEY_SALT . json_encode(['', $group, $key]);
        $this->cache->cache[$true_key] = 'beta';
        $this->assertEquals('beta', $this->cache->get($key, $group));
        $this->assertEmpty($this->cache->redis_calls);
    }

    public function test_get_force() {
        $key = rand_str();
        $group = 'default';
        $this->cache->set($key, 'alpha', $group);
        $this->assertEquals('alpha', $this->cache->get($key, $group, true));
        $this->assertEquals(['get' => 1, 'set' => 1], $this->cache->redis_calls);
    }

    public function test_add_get() {
        $key = rand_str();
        $val = rand_str();

        $this->cache->add($key, $val);
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
    }

    public function test_add_get_0() {
        $key = rand_str();
        $val = 0;

        // you can store zero in the cache
        $this->cache->add($key, $val);
        $this->assertEquals($val, $this->cache->get($key));
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
    }

    public function test_add_get_null() {
        $key = rand_str();
        $val = null;

        $this->assertTrue($this->cache->add($key, $val));
        $this->assertNull($this->cache->get($key));
        $this->assertEquals(['set' => 1], $this->cache->redis_calls);
    }

    public function test_flush() {
        $key = rand_str();
        $val = rand_str();

        $this->cache->add($key, $val);
        // item is visible to both cache objects
        $this->assertEquals($val, $this->cache->get($key));
        $this->cache->flush();
        // If there is no value get returns false.
        $this->assertFalse($this->cache->get($key));
        $this->assertEquals([
            'flushdb' => 1,
            'get' => 1,
            'set' => 1,
        ], $this->cache->redis_calls);
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
        $this->assertEquals([
            // The second `add()`, which fails, removes internal cache.
            // While this behavior isn't desirable from the performance perspective,
            // it simplifies implementation.
            'get' => 1,
            'set' => 2,
        ], $this->cache->redis_calls);
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
        $this->assertEquals(['get' => 1, 'set' => 3], $this->cache->redis_calls);
    }

    public function test_delete() {
        $key = rand_str();
        $val = rand_str();

        // Verify set
        $this->assertTrue($this->cache->set($key, $val));
        $this->assertEquals($val, $this->cache->get($key));

        // Verify successful delete
        $this->assertTrue($this->cache->delete($key));
        $this->assertFalse($this->cache->get($key));

        $this->assertFalse($this->cache->delete($key, 'default'));
        $this->assertEquals([
            'del' => 2,
            'get' => 1,
            'set' => 1,
        ], $this->cache->redis_calls);
    }

    public function test_incr() {
        $key = rand_str();

        $this->assertFalse($this->cache->incr($key));

        $this->cache->set($key, 0);
        $this->cache->incr($key);
        $this->assertEquals(1, $this->cache->get($key));

        $this->cache->incr($key, 2);
        $this->assertEquals(3, $this->cache->get($key));
        $this->assertEquals(['evalSha' => 3, 'set' => 1], $this->cache->redis_calls);
    }

    public function test_incr_separate_groups() {
        $key = rand_str();
        $group1 = 'group1';
        $group2 = 'group2';

        $this->assertFalse($this->cache->incr($key, 1, $group1));
        $this->assertFalse($this->cache->incr($key, 1, $group2));

        $this->cache->set($key, 0, $group1);
        $this->cache->incr($key, 1, $group1);
        $this->cache->set($key, 0, $group2);
        $this->cache->incr($key, 1, $group2);
        $this->assertEquals(1, $this->cache->get($key, $group1));
        $this->assertEquals(1, $this->cache->get($key, $group2));

        $this->cache->incr($key, 2, $group1);
        $this->cache->incr($key, 1, $group2);
        $this->assertEquals(3, $this->cache->get($key, $group1));
        $this->assertEquals(2, $this->cache->get($key, $group2));
        $this->assertEquals(['evalSha' => 6, 'set' => 2], $this->cache->redis_calls);
    }

    public function test_incr_never_below_zero() {
        $key = rand_str();
        $this->cache->set($key, 1);
        $this->assertEquals(1, $this->cache->get($key));
        $this->cache->incr($key, -2);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(['evalSha' => 1, 'set' => 1], $this->cache->redis_calls);
    }

    public function test_decr() {
        $key = rand_str();

        $this->assertFalse($this->cache->decr($key));

        $this->cache->set($key, 0);
        $this->cache->decr($key);
        $this->assertEquals(0, $this->cache->get($key));

        $this->cache->set($key, 3);
        $this->cache->decr($key);
        $this->assertEquals(2, $this->cache->get($key));

        $this->cache->decr($key, 2);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(['evalSha' => 4, 'set' => 2], $this->cache->redis_calls);
    }

    public function test_decr_separate_groups() {
        $key = rand_str();
        $group1 = 'group1';
        $group2 = 'group2';

        $this->assertFalse($this->cache->decr($key, 1, $group1));
        $this->assertFalse($this->cache->decr($key, 1, $group2));

        $this->cache->set($key, 0, $group1);
        $this->cache->decr($key, 1, $group1);
        $this->cache->set($key, 0, $group2);
        $this->cache->decr($key, 1, $group2);
        $this->assertEquals(0, $this->cache->get($key, $group1));
        $this->assertEquals(0, $this->cache->get($key, $group2));

        $this->cache->set($key, 3, $group1);
        $this->cache->decr($key, 1, $group1);
        $this->cache->set($key, 2, $group2);
        $this->cache->decr($key, 1, $group2);
        $this->assertEquals(2, $this->cache->get($key, $group1));
        $this->assertEquals(1, $this->cache->get($key, $group2));

        $this->cache->decr($key, 2, $group1);
        $this->cache->decr($key, 2, $group2);
        $this->assertEquals(0, $this->cache->get($key, $group1));
        $this->assertEquals(0, $this->cache->get($key, $group2));
        $this->assertEquals(['evalSha' => 8, 'set' => 4], $this->cache->redis_calls);
    }

    public function test_decr_never_below_zero() {
        $key = rand_str();
        $this->cache->set($key, 1);
        $this->assertEquals(1, $this->cache->get($key));
        $this->cache->decr($key, 2);
        $this->assertEquals(0, $this->cache->get($key));
        $this->assertEquals(['evalSha' => 1, 'set' => 1], $this->cache->redis_calls);
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
}
