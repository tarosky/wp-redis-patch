<?php

class KeyTest extends ObjectCacheTestCase {
    public function testSameKey() {
        global $wp_object_cache;

        $key1 = $wp_object_cache->redis_key('abc');
        $key2 = $wp_object_cache->redis_key('abc');
        $this->assertEquals($key1, $key2);
    }

    public function testDifferentTypeKey() {
        global $wp_object_cache;

        $key1 = $wp_object_cache->redis_key('1');
        $key2 = $wp_object_cache->redis_key(1);
        $this->assertEquals($key1, $key2);
    }

    public function testSameVersionKey() {
        global $wp_object_cache;

        $key1 = $wp_object_cache->version_key('abc');
        $key2 = $wp_object_cache->version_key('abc');
        $this->assertEquals($key1, $key2);
    }

    public function testDifferentTypeVersionKey() {
        global $wp_object_cache;

        $key1 = $wp_object_cache->version_key('1');
        $key2 = $wp_object_cache->version_key(1);
        $this->assertEquals($key1, $key2);
    }
}
