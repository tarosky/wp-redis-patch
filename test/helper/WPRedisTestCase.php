<?php

use PHPUnit\Framework\TestCase;

/**
 * Test the persistent object cache using core's cache tests
 */
abstract class WPRedisTestCase extends TestCase {
	public function assertInternalType( $type, $actual ) {
		$this->assertEquals( 'int', $type );
		$this->assertIsInt( $actual );
	}

	public static function assertRegExp(
		string $pattern,
		string $str,
		string $message = ''
	): void {
		self::assertMatchesRegularExpression( $pattern, $str );
	}

	public function setExpectedException( $ex ) {
		$this->expectException( $ex );
	}

	public function flush_cache() {
		self::wp_redis_flush_cache();
	}

	private static function wp_redis_flush_cache() {
		global $wp_object_cache;

		$wp_object_cache->cache = array();
		wp_cache_flush();
		wp_cache_add_global_groups(array(
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
		));
		wp_cache_add_non_persistent_groups( array( 'comment', 'counts', 'plugins' ) );
	}
}
