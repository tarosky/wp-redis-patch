<?php

use PHPUnit\Framework\TestCase;

/**
 * Test the persistent object cache using core's cache tests
 */
abstract class WPRedisTestCase extends TestCase {
    public function assertInternalType($type, $actual) {
        $this->assertEquals('int', $type);
        $this->assertIsInt($actual);
    }

    public static function assertRegExp(
        string $pattern,
        string $string,
        string $message = ''
    ): void {
        self::assertMatchesRegularExpression($pattern, $string);
    }

    public function setExpectedException($ex) {
        $this->expectException($ex);
    }

    public function flush_cache() {
        self::wp_redis_flush_cache();
    }

    private static function wp_redis_flush_cache() {
        global $wp_object_cache;

        $wp_object_cache->group_ops = [];
        $wp_object_cache->stats = [];
        $wp_object_cache->memcache_debug = [];
        $wp_object_cache->cache = [];
        if (method_exists($wp_object_cache, '__remoteset')) {
            $wp_object_cache->__remoteset();
        }
        wp_cache_flush();
        wp_cache_add_global_groups([
            'users',
            'userlogins',
            'usermeta',
            'user_meta',
            'useremail',
            'userslugs',
            'site-transient',
            'site-options',
            'blog-lookup',
            'blog-details',
            'rss',
            'global-posts',
            'blog-id-cache',
            'networks',
            'sites',
            'site-details',
            'blog_meta',
        ]);
        wp_cache_add_non_persistent_groups(['comment', 'counts', 'plugins']);
    }
}
