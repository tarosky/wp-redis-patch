<?php

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase {
    protected static $redis;

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();

        global $redis_server;
        $redis_server = [
            'host' => 'redis',
            'port' => 6379,
            'timeout' => 1000,
            'retry_interval' => 100,
        ];

        self::$redis = new Redis();
        self::$redis->connect('redis');
    }

    public function setUp(): void {
        global $wp_object_cache;

        self::$redis->flushdb();
        self::$redis->set('foo', 'bar');
        $wp_object_cache = new ThrowOnGetObjectCache();
    }

    public function testException() {
        global $wp_object_cache;

        try {
            $wp_object_cache->call_redis('get', ['foo']);
            $this->fail();
        } catch (WP_Object_Cache_RedisCallException $e) {
            $this->assertEquals('get', $e->getMethod());
            $this->assertEquals(['foo'], $e->getArgs());
            $this->assertStringContainsString('get', $e->getMessage());
            $this->assertStringContainsString('foo', $e->getMessage());
            $this->assertStringContainsString(
                'default error message',
                $e->getMessage()
            );
            $this->assertInstanceOf(RedisTestException::class, $e->getPrevious());
            $this->assertEquals(0, $wp_object_cache->trigger_error_count);
        }
    }

    public function testRetry1() {
        global $wp_object_cache;

        $wp_object_cache->error_message = 'Connection closed';
        $wp_object_cache->error_ifs = [true, false];

        $this->assertEquals('bar', $wp_object_cache->call_redis('get', ['foo']));
        $this->assertEquals(1, $wp_object_cache->trigger_error_count);
    }

    public function testRetry2() {
        global $wp_object_cache;

        $wp_object_cache->error_message = 'Connection closed';
        $wp_object_cache->error_ifs = [true, true, false];

        $this->assertEquals('bar', $wp_object_cache->call_redis('get', ['foo']));
        $this->assertEquals(2, $wp_object_cache->trigger_error_count);
    }

    public function testRetry3() {
        global $wp_object_cache;

        $wp_object_cache->error_message = 'Connection closed';
        $wp_object_cache->error_ifs = [true, true, true, false];

        $this->assertEquals('bar', $wp_object_cache->call_redis('get', ['foo']));
        $this->assertEquals(3, $wp_object_cache->trigger_error_count);
    }

    public function testRetry4() {
        global $wp_object_cache;

        $wp_object_cache->error_message = 'Connection closed';
        $wp_object_cache->error_ifs = [true, true, true, true, false];

        try {
            $wp_object_cache->call_redis('get', ['foo']);
            $this->fail();
        } catch (WP_Object_Cache_RedisCallException $e) {
            $this->assertEquals('get', $e->getMethod());
            $this->assertEquals(['foo'], $e->getArgs());
            $this->assertStringContainsString('get', $e->getMessage());
            $this->assertStringContainsString('foo', $e->getMessage());
            $this->assertStringContainsString(
                'Connection closed',
                $e->getMessage()
            );
            $this->assertInstanceOf(RedisTestException::class, $e->getPrevious());
            $this->assertEquals(3, $wp_object_cache->trigger_error_count);
        }
    }
}
