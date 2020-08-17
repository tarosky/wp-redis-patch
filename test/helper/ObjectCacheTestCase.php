<?php

use PHPUnit\Framework\TestCase;

abstract class ObjectCacheTestCase extends TestCase {
    protected static $redis;
    protected const RIVAL_VERSION = 'deadbeef-dead-beef-dead-beefdeadbeef';
    protected const UNUSED_DUMMY_VERSION = 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa';
    protected const VERSION_FORMAT = '%x-%x-%x-%x-%x';
    protected const GROUP = 'default';
    protected const KEY = 'alloptions';
    protected const VAL = 'sample-value';
    protected const VAL_SUP = 'another-value';
    protected const VAL_SUP_2 = 'yet-another-value';
    protected $old_version;
    protected $next_versioning_keys;

    public static function encode_redis_string($data) {
        return is_numeric($data) && intval($data) === $data
            ? $data
            : igbinary_serialize($data);
    }

    public static function setUpBeforeClass(): void {
        parent::setUp();

        global $redis_server;
        $redis_server = ['host' => 'redis', 'port' => 6379];

        self::$redis = new Redis();
        self::$redis->connect('redis');
    }

    protected function set_next_versioning_keys($versioning_keys) {
        $this->next_versioning_keys = $versioning_keys;
    }

    public function setUp(): void {
        global $wp_object_cache, $redis_server_default_versioning_keys, $redis_server_versioning_keys;
        self::$redis->flushdb();
        $redis_server_versioning_keys = $this->next_versioning_keys ?? $redis_server_default_versioning_keys;
        $this->next_versioning_keys = null;
        $wp_object_cache = new TaroskyObjectCache();
        $this->old_version = self::UNUSED_DUMMY_VERSION;
    }

    protected function assertRedisEquals($expected, $key, $group = 'default') {
        list($actual, $found) = TaroskyObjectCache::decode_redis_get(
            self::$redis->get($this->redis_key($key, $group)),
        );
        $this->assertTrue($found);
        $this->assertEquals($expected, $actual);
    }

    protected function redis_key($key, $group = 'default') {
        global $wp_object_cache;

        return $wp_object_cache->redis_key($key, $group);
    }

    protected function version_key($key, $group = 'default') {
        global $wp_object_cache;

        return $wp_object_cache->version_key($key, $group);
    }

    protected function cache() {
        global $wp_object_cache;

        return $wp_object_cache->cache;
    }

    protected function cache_version($redis_key) {
        global $wp_object_cache;

        return $wp_object_cache->get_cache_version($redis_key);
    }

    protected function connection_kept() {
        global $wp_object_cache;

        return $wp_object_cache->is_connected();
    }

    protected function set_rival_version() {
        self::$redis->set($this->version_key(self::KEY), self::RIVAL_VERSION);
    }

    protected function set_sup_value() {
        self::$redis->set(
            $this->redis_key(self::KEY),
            self::encode_redis_string(self::VAL_SUP),
        );
    }

    protected function set_sup_2_value() {
        self::$redis->set(
            $this->redis_key(self::KEY),
            self::encode_redis_string(self::VAL_SUP_2),
        );
    }

    protected function set_value() {
        self::$redis->set(
            $this->redis_key(self::KEY),
            self::encode_redis_string(self::VAL),
        );
    }

    protected function get_and_store_version() {
        $this->old_version = self::$redis->get($this->version_key(self::KEY));
    }

    protected function del_val() {
        self::$redis->del($this->redis_key(self::KEY));
    }
}
